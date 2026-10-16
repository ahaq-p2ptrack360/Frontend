<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AgentChatApiTest extends TestCase
{
    public function test_agent_chat_returns_answer_when_configured(): void
    {
        config()->set('services.agent_chat.enabled', true);
        config()->set('services.agent_chat.provider', 'openai-compatible');
        config()->set('services.agent_chat.url', 'https://example.com/v1/chat/completions');
        config()->set('services.agent_chat.api_key', 'test-key');
        config()->set('services.agent_chat.model', 'gpt-4o-mini');

        Http::fake([
            'https://example.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Hello from agent chat',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/agent-chat', [
            'message' => 'Hi there',
        ]);

        $response->assertOk();
        $response->assertJsonPath('answer', 'Hello from agent chat');
        $response->assertJsonPath('provider', 'openai-compatible');
    }

    public function test_agent_chat_falls_back_to_local_insights_when_provider_is_missing(): void
    {
        config()->set('services.agent_chat.enabled', false);
        config()->set('services.agent_chat.url', null);
        config()->set('services.agent_chat.api_key', null);

        $response = $this->postJson('/api/agent-chat', [
            'message' => 'Create a report about system issues',
        ]);

        $response->assertOk();
        $response->assertJsonPath('provider', 'local-insights');
        $response->assertJsonFragment(['report' => 'Insights report']);
    }

    public function test_agent_chat_uses_ollama_local_model_when_selected(): void
    {
        config()->set('services.agent_chat.enabled', true);
        config()->set('services.agent_chat.provider', 'ollama');
        config()->set('services.agent_chat.url', 'http://localhost:11434/api/generate');
        config()->set('services.agent_chat.model', 'llama3.1');
        config()->set('services.agent_chat.api_key', null);

        Http::fake([
            'http://localhost:11434/api/generate' => Http::response([
                'response' => "Subject: Support Query Resolution\nSummary: The issue is under review.\nRecommended Action: Assign ownership and verify the latest update.",
            ], 200),
        ]);

        $response = $this->postJson('/api/agent-chat', [
            'message' => 'What is next step for this support issue?',
        ]);

        $response->assertOk();
        $response->assertJsonPath('provider', 'ollama');
        $this->assertStringContainsString('Subject: Support Query Resolution', $response->json('answer'));
    }
}
