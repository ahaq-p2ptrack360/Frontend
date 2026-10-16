<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OllamaService
{
    protected string $url;
    protected string $model;

    public function __construct()
    {
        $this->url = rtrim(
            config('ai.ollama_url'),
            '/'
        );

        $this->model = config('ai.model');
    }

    /**
     * Send a normal prompt to Ollama.
     */
    public function generate(string $prompt): string
    {
        $response = Http::timeout(1000)
            ->post($this->url . '/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,

                'think' => false,

                'stream' => false,

                'options' => [
                    'temperature' => 0,
                    'num_predict' => 1000,
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Ollama request failed: ' . $response->body()
            );
        }

        $answer = $response->json('response', '');

        if (! is_string($answer)) {
            return '';
        }

        // Some models include their hidden reasoning in the text response.
        $answer = preg_replace(
            '/<think>.*?<\/think>/is',
            '',
            $answer
        ) ?? $answer;

        return trim($answer);
    }

    /**
     * Ask Ollama to return JSON.
     */
    /* public function generateJson(string $prompt): array
    {
        $response = Http::timeout(120)
            ->post($this->url . '/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,
                'format' => 'json',
                'stream' => false,
                'options' => [
                    'temperature' => 0,
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Ollama JSON request failed: ' . $response->body()
            );
        }

        $text = $response->json('response');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException(
                'Ollama returned an empty response.'
            );
        }

        $data = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Ollama returned invalid JSON: ' . $text
            );
        }

        return $data;
    } */

    public function generateJson(
        string $prompt,
        ?array $schema = null
    ): array {
    $response = Http::timeout(120)
        ->post($this->url . '/api/generate', [
            'model' => $this->model,
            'prompt' => $prompt,

            // We want the actual answer in "response"
            'think' => false,

            // Force JSON output, and optionally enforce its exact shape.
            'format' => $schema ?? 'json',

            'stream' => false,

            'options' => [
                'temperature' => 0,
            ],
        ]);

    if ($response->failed()) {
        throw new RuntimeException(
            'Ollama JSON request failed: ' . $response->body()
        );
    }

    $text = $response->json('response');

    if (! is_string($text) || trim($text) === '') {
        throw new RuntimeException(
            'Ollama returned an empty response. Raw response: '
            . $response->body()
        );
    }

    $data = json_decode($text, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException(
            'Ollama returned invalid JSON: ' . $text
        );
    }

    return $data;
}
}