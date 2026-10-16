<?php

namespace App\Http\Controllers;

use App\AI\QueryPlanner;
use App\AI\QueryValidator;
use App\AI\QueryExecutor;
use App\AI\AnswerGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class AIQueryController extends Controller
{
    public function plan(
        Request $request,
        QueryPlanner $planner,
        QueryValidator $validator,
        QueryExecutor $executor,
        AnswerGenerator $answerGenerator,
        
    ) {
        $validated = $request->validate([
            'question' => [
                'required',
                'string',
                'max:5000',
            ],
            'company_id' => [
                'nullable',
                'integer',
            ],
            'user_id' => [
                'nullable',
                'integer',
            ],
            'conversation_id' => [
                'nullable',
                'integer',
            ],
        ]);

        $user = Auth::user();
        $companyId = $validated['company_id'] ?? $user?->company_id;
        $userId = $validated['user_id'] ?? $user?->id;
        $conversation = $this->conversationFor($validated['conversation_id'] ?? null, $user, $validated['question'], $companyId, $userId);
        
        DB::table('ai_messages')->insert([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['question'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Ask AI for query plan
            |--------------------------------------------------------------------------
            */

            $plan = $planner->plan(
                $validated['question']
            );

            /*
            |--------------------------------------------------------------------------
            | 2. Validate AI output
            |--------------------------------------------------------------------------
            */

            $plan = $validator->validate($plan);

            /*
            |--------------------------------------------------------------------------
            | 3. Execute safely
            |--------------------------------------------------------------------------
            */

            DB::enableQueryLog();

            $result = $executor->execute(
                $plan,
                $companyId,
                $userId
            );

            $sql = $this->formatQueryLog(DB::getQueryLog());
            DB::disableQueryLog();

            $answer = $answerGenerator->generate(
                $validated['question'],
                $plan,
                $result
            );

            $chart = $answerGenerator->generateChart(
                $plan,
                $result
            );

            DB::table('ai_messages')->insertGetId([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => $answer,
                'chart' => $chart ? json_encode($chart) : null,
                'plan' => json_encode([
                    'plan' => $plan,
                    'result' => $result,
                    'sql' => $sql,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('ai_conversations')->where('id', $conversation->id)->update(['updated_at' => now()]);

            return response()->json([
                'success' => true,
                'question' => $validated['question'],
                'plan' => $plan,
                'result' => $result,
                'answer' => $answer,
                'chart' => $chart,
                'sql' => $sql,
                'conversation_id' => $conversation->id,
            ]);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function formatQueryLog(array $queryLog): array
    {
        return array_map(function ($entry) {
            $sql = $entry['query'];
            foreach ($entry['bindings'] as $binding) {
                $value = is_numeric($binding) ? $binding : "'" . addslashes((string) $binding) . "'";
                $sql = preg_replace('/\?/', $value, $sql, 1);
            }
            return $sql;
        }, $queryLog);
    }

    protected function conversationFor(?int $conversationId, $user, string $message, $companyId = null, $userId = null): object
    {
        if ($conversationId) {
            $conversation = DB::table('ai_conversations')->where('id', $conversationId)->first();
            if ($conversation && (! $user || $conversation->user_id == $user->id)) {
                return $conversation;
            }
        }

        $id = DB::table('ai_conversations')->insertGetId([
            'user_id' => $userId ?? $user?->id,
            'company_id' => $companyId ?? $user?->company_id,
            'title' => mb_strimwidth($message, 0, 60, '...'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('ai_conversations')->where('id', $id)->first();
    }
}