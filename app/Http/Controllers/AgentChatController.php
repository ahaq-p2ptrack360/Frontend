<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class AgentChatController extends Controller
{
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        $user = Auth::user();
        $conversation = $this->conversationFor($validated['conversation_id'] ?? null, $user, $validated['message']);
        $history = DB::table('ai_messages')->where('conversation_id', $conversation->id)->orderBy('id')->get();
        DB::table('ai_messages')->insert([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $answer = $this->requestChatProvider($validated['message'], $history, $user);
        $messageId = DB::table('ai_messages')->insertGetId([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $answer['answer'],
            'chart' => isset($answer['chart']) ? json_encode($answer['chart']) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('ai_conversations')->where('id', $conversation->id)->update(['updated_at' => now()]);

        return response()->json(array_merge($answer, [
            'conversation_id' => $conversation->id,
            'message_id' => $messageId,
        ]));
    }

    public function plan(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:4000'],
            'company_id' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        $user = Auth::user();
        $conversation = $this->conversationFor($validated['conversation_id'] ?? null, $user, $validated['question']);
        DB::table('ai_messages')->insert([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['question'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $url = config('services.agent_chat.plan_url');
        if (empty($url)) {
            return response()->json(['message' => 'AI_PLAN_URL is not configured.'], 503);
        }

        $response = Http::withOptions(['verify' => false])->acceptJson()->post($url, [
            'question' => $validated['question'],
            'company_id' => $validated['company_id'] ?? $user?->company_id,
            'user_id' => $validated['user_id'] ?? $user?->id,
        ]);
        $payload = $response->json() ?: ['message' => $response->body()];
        if ($response->successful()) {
            $answer = data_get($payload, 'answer', data_get($payload, 'message', 'No answer returned.'));
            $chart = data_get($payload, 'chart');
            DB::table('ai_messages')->insert([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => is_string($answer) ? $answer : json_encode($answer),
                'chart' => $chart ? json_encode($chart) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('ai_conversations')->where('id', $conversation->id)->update(['updated_at' => now()]);
            $payload['conversation_id'] = $conversation->id;
        }

        return response()->json($payload, $response->status());
    }

    public function conversations()
    {
        $query = DB::table('ai_conversations')->latest('updated_at');
        if (Auth::id()) {
            $query->where('user_id', Auth::id());
        }

        return response()->json($query->get());
    }

    public function messages(int $conversation)
    {
        $this->assertConversationAccess($conversation);
        return response()->json(DB::table('ai_messages')->where('conversation_id', $conversation)->orderBy('id')->get());
    }

    public function destroy(int $conversation)
    {
        $this->assertConversationAccess($conversation);
        DB::table('ai_conversations')->where('id', $conversation)->delete();
        return response()->json(['deleted' => true]);
    }

    protected function conversationFor(?int $conversationId, $user, string $message): object
    {
        if ($conversationId) {
            $conversation = DB::table('ai_conversations')->where('id', $conversationId)->first();
            if ($conversation && (! $user || $conversation->user_id == $user->id)) {
                return $conversation;
            }
        }

        $id = DB::table('ai_conversations')->insertGetId([
            'user_id' => $user?->id,
            'company_id' => $user?->company_id,
            'title' => mb_strimwidth($message, 0, 60, '...'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('ai_conversations')->where('id', $id)->first();
    }

    protected function assertConversationAccess(int $conversation): void
    {
        $query = DB::table('ai_conversations')->where('id', $conversation);
        if (Auth::id()) {
            $query->where('user_id', Auth::id());
        }

        abort_unless($query->exists(), 404);
    }

    protected function requestChatProvider(string $message, $history, $user): array
    {
        $url = config('services.agent_chat.chat_url');
        $payload = [
            'message' => $message,
            'company_id' => $user?->company_id,
            'user_id' => $user?->id,
            'history' => $history->map(fn ($item) => ['role' => $item->role, 'content' => $item->content])->values()->all(),
        ];

        if ($url) {
            $response = Http::withOptions(['verify' => false])->acceptJson()->post($url, $payload);
            if ($response->successful()) {
                return $this->normalizeProviderResponse($response->json() ?: []);
            }
        }

        return $this->requestOllamaWithHistory($message, $history);
    }

    protected function normalizeProviderResponse(array $payload): array
    {
        $answer = data_get($payload, 'answer', data_get($payload, 'message', data_get($payload, 'response', data_get($payload, 'data.answer', 'No answer returned.'))));
        $result = ['enabled' => true, 'answer' => is_string($answer) ? trim($answer) : json_encode($answer)];
        $chart = data_get($payload, 'chart', data_get($payload, 'data.chart'));
        if ($chart) {
            $result['chart'] = $chart;
        }
        return $result;
    }

    protected function requestOllamaWithHistory(string $message, $history): array
    {
        $url = config('services.agent_chat.url');
        $model = config('services.agent_chat.model', 'llama3.1');
        $context = $history->map(fn ($item) => strtoupper($item->role).': '.$item->content)->implode("\n\n");
        $prompt = $this->buildFormalPrompt(($context ? $context."\n\n" : '').'USER: '.$message);
        $response = Http::withOptions(['verify' => false])->post($url, ['model' => $model, 'prompt' => $prompt, 'stream' => false, 'options' => ['temperature' => 0.3]]);
        if ($response->successful() && data_get($response->json(), 'response')) {
            return ['enabled' => true, 'answer' => trim(data_get($response->json(), 'response')), 'provider' => 'ollama', 'model' => $model];
        }

        return json_decode($this->localInsightsResponse($message)->getContent(), true);
    }

    protected function legacyAsk(Request $request)
    {

        $enabled = (bool) config('services.agent_chat.enabled', false);
        $configuredProvider = config('services.agent_chat.provider');
        $url = config('services.agent_chat.url');
        $apiKey = config('services.agent_chat.api_key');
        $model = config('services.agent_chat.model', 'llama3.1');

        $provider = $configuredProvider ?: (($apiKey && ! empty($url) && ! str_contains($url, '11434')) ? 'openai-compatible' : 'ollama');

        if (! $enabled || empty($url)) {
            return $this->localInsightsResponse($validated['message']);
        }

        if ($provider === 'ollama') {
            return $this->callOllama($validated['message'], $url, $model);
        }

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $this->buildFormalPrompt($validated['message'])],
                ],
                'temperature' => 0.4,
            ]);

        if (! $response->successful()) {
            return $this->localInsightsResponse($validated['message'], $response->json('error.message', $response->body()));
        }

        $payload = $response->json();
        $answer = data_get($payload, 'choices.0.message.content');

        if (empty($answer)) {
            return $this->localInsightsResponse($validated['message'], 'No content returned by provider.');
        }

        return response()->json([
            'enabled' => true,
            'answer' => trim($answer),
            'provider' => 'openai-compatible',
            'model' => data_get($payload, 'model', $model),
        ], 200);
    }

    protected function callOllama(string $message, string $url, string $model): \Illuminate\Http\JsonResponse
    {
        $payload = [
            'model' => $model,
            'prompt' => $this->buildFormalPrompt($message),
            'stream' => false,
            'options' => [
                'temperature' => 0.3,
            ],
        ];

        $response = Http::withOptions(['verify' => false])->post($url, $payload);

        if (! $response->successful()) {
            return $this->localInsightsResponse($message, $response->body());
        }

        $body = $response->json();
        $answer = data_get($body, 'response');

        if (empty($answer)) {
            return $this->localInsightsResponse($message, 'Local model returned empty output.');
        }

        return response()->json([
            'enabled' => true,
            'answer' => trim($answer),
            'provider' => 'ollama',
            'model' => $model,
        ], 200);
    }

    protected function buildFormalPrompt(string $message): string
    {
        $context = $this->buildDatabaseContext();

        $contextBlock = $context !== '' ? "\n\nDatabase context:\n".$context : '';

        return "You are a formal support and operations assistant for a service management system. " .
            "Answer in a professional business tone. " .
            "Use headings such as Subject, Summary, Findings, Recommended Action, Escalation, and Follow-up. " .
            "Keep it concise but useful. " .
            "Base your answer on the available system context when possible. " .
            $contextBlock .
            "\n\nUser request: \n\n" . $message;
    }

    protected function buildDatabaseContext(): string
    {
        try {
            $tables = Schema::getAllTables();
            $tableNames = [];

            foreach ($tables as $table) {
                $tableNames[] = is_array($table) ? reset($table) : $table;
            }

            $relevant = ['users', 'company', 'business_units', 'customers', 'domains', 'designations', 'conversations', 'tickets'];
            $contextLines = [];

            foreach ($relevant as $table) {
                if (! in_array($table, $tableNames, true)) {
                    continue;
                }

                $count = DB::table($table)->count();
                $contextLines[] = $table . ': ' . $count . ' records';

                if (in_array($table, ['company', 'business_units', 'customers', 'users'], true)) {
                    $rows = DB::table($table)->orderByDesc('id')->limit(3)->get();
                    if ($rows->isNotEmpty()) {
                        $contextLines[] = 'Recent ' . $table . ' rows: ' . $rows->take(3)->map(function ($row) {
                            $parts = [];
                            foreach (['id', 'name', 'title', 'email', 'company_id', 'status', 'created_at'] as $key) {
                                if (isset($row->{$key})) {
                                    $parts[] = $key . '=' . $row->{$key};
                                }
                            }

                            return '{' . implode(', ', $parts) . '}';
                        })->implode(' | ');
                    }
                }
            }

            return implode("\n", $contextLines);
        } catch (\Throwable $e) {
            return '';
        }
    }

    protected function localInsightsResponse(string $message, ?string $fallbackReason = null): 
    \Illuminate\Http\JsonResponse
    {
        $summary = $this->buildLocalInsightSummary($message);

        return response()->json([
            'enabled' => false,
            'provider' => 'local-insights',
            'answer' => $summary,
            'report' => 'Insights report',
            'note' => $fallbackReason ? 'Provider fallback: '.$fallbackReason : 'No external AI provider is configured yet.',
        ], 200);
    }

    protected function buildLocalInsightSummary(string $message): string
    {
        $lowerMessage = strtolower($message);

        if (str_contains($lowerMessage, 'report') || str_contains($lowerMessage, 'summary')) {
            return "Subject: Support Summary\nSummary: A concise operational review is recommended to assess open issues and unresolved service impacts.\nFindings: \n- Open tickets should be reviewed by urgency.\n- Prioritization should be based on business impact and SLA risk.\n- Assigned ownership should be confirmed before escalation.\nRecommended Action: Review active tickets, confirm owners, and prepare a short status summary for stakeholders.\nEscalation: Escalate only if unresolved within the agreed service window.\nFollow-up: Reassess after the next operational review.";
        }

        if (str_contains($lowerMessage, 'ticket') || str_contains($lowerMessage, 'incident') || str_contains($lowerMessage, 'support') || str_contains($lowerMessage, 'customer')) {
            return "Subject: Ticket Resolution Guidance\nSummary: The issue should be handled through a structured support workflow.\nFindings: \n- Review ticket severity and response time.\n- Confirm whether the problem is recurring or isolated.\n- Validate whether the customer has received a timely update.\nRecommended Action: Assign a clear owner, verify root cause, and communicate a realistic resolution timeline.\nEscalation: Required if the issue affects business continuity or exceeds SLA.\nFollow-up: Update the ticket with the final outcome and customer communication.";
        }

        if (str_contains($lowerMessage, 'system') || str_contains($lowerMessage, 'health') || str_contains($lowerMessage, 'performance') || str_contains($lowerMessage, 'uptime')) {
            return "Subject: System Health Review\nSummary: A preventive review is recommended to assess current system stability and operational risk.\nFindings: \n- Monitor uptime, failed processes, and queue activity.\n- Review recurring error patterns and service delays.\n- Confirm whether any alert threshold has been exceeded.\nRecommended Action: Validate service health, prioritize critical alerts, and document preventive measures.\nEscalation: Escalate if the issue impacts production availability or customer operations.\nFollow-up: Schedule a maintenance or monitoring review after resolution.";
        }

        return "Subject: Operational Recommendation\nSummary: The request requires a structured review based on current service activity and ownership.\nFindings: \n- Confirm the exact issue scope and business impact.\n- Identify the affected user, team, or system area.\n- Validate the latest status, ownership, and unresolved dependencies.\nRecommended Action: Capture the issue clearly, assign ownership, and define the next action with a deadline.\nEscalation: Escalate only if operational risk or SLA impact becomes significant.\nFollow-up: Prepare a concise status update for the relevant stakeholder group.";
    }
}
