<?php

namespace App\Http\Controllers;

use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AIChatController extends Controller
{
    public function chat(Request $request, OllamaService $ollama)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'conversation_id' => ['nullable', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer'],
        ]);

        $user = Auth::user();
        $companyId = $validated['company_id'] ?? $user?->company_id;
        $userId = $validated['user_id'] ?? $user?->id;
        $conversation = $this->conversationFor($validated['conversation_id'] ?? null, $user, $validated['message'], $companyId, $userId);
        
        DB::table('ai_messages')->insert([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $answer = $ollama->generate($validated['message']);

        DB::table('ai_messages')->insertGetId([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $answer,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('ai_conversations')->where('id', $conversation->id)->update(['updated_at' => now()]);

        return response()->json([
            'answer' => $answer,
            'conversation_id' => $conversation->id,
        ]);
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