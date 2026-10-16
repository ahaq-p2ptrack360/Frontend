<?php

namespace App\AI;

use App\Services\OllamaService;

class AnswerGenerator
{
    public function __construct(
        protected OllamaService $ollama
    ) {
    }

    public function generateChart(
        array $plan,
        mixed $result
    ): ?array {
        if (!($plan['chart_requested'] ?? false)) {
            return null;
        }

        $chartType = $plan['chart_type'] ?? 'bar';
        $labels = [];
        $series = [];

        if (is_array($result)) {
            foreach ($result as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $label = null;
                foreach (['status', 'priority', 'vendor', 'business_unit', 'month', 'date', 'name', 'title', 'label'] as $key) {
                    if (array_key_exists($key, $row)) {
                        $label = (string) $row[$key];
                        break;
                    }
                }

                if ($label === null) {
                    $label = (string) array_key_first($row);
                }

                $value = $row['count'] ?? $row['total'] ?? null;

                if ($value === null && count($row) > 0) {
                    foreach ($row as $candidate) {
                        if (is_numeric($candidate)) {
                            $value = (int) $candidate;
                            break;
                        }
                    }
                }

                $labels[] = $label;
                $series[] = $value ?? 0;
            }
        }

        return [
            'type' => $chartType,
            'labels' => $labels,
            'series' => $series,
        ];
    }

    public function generate(
        string $question,
        array $plan,
        mixed $result
    ): string {
        $resultJson = json_encode(
            $result,
            JSON_UNESCAPED_UNICODE
        );

        $planJson = json_encode(
            $plan,
            JSON_UNESCAPED_UNICODE
        );

        $prompt = <<<PROMPT
You are a response generator. Use only the database result to answer the user's question.
Answer in the same language and style as the user's question.
Return ONLY valid JSON in exactly this format:
{"answer":"your concise final answer"}
The answer must be one or two short sentences and must explain what the result means.
Never return only a number or a raw database value.
For a count result, include the relevant subject from the question, for example:
Question: How many total tickets do I have? Result: 5
Answer: You have 5 total tickets.
Question: Mere total tickets kitne hain? Result: 5
Answer: Aapke total 5 tickets hain.
Never include reasoning, analysis, drafts, steps, or <think> tags in the answer.

Question:
{$question}

Query plan:
{$planJson}

Database result:
{$resultJson}

PROMPT;

        $data = $this->ollama->generateJson(
            $prompt,
            [
                'type' => 'object',
                'properties' => [
                    'answer' => [
                        'type' => 'string',
                    ],
                ],
                'required' => ['answer'],
                'additionalProperties' => false,
            ]
        );

        if (! isset($data['answer']) || ! is_string($data['answer'])) {
            throw new \RuntimeException(
                'Ollama answer did not contain a valid answer field.'
            );
        }

        return trim($data['answer']);
    }
}
