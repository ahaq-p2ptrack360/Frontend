<?php

return [

    'ollama_url' => env(
        'OLLAMA_URL',
        'http://127.0.0.1:11434'
    ),

    'model' => env(
        'OLLAMA_MODEL',
        'qwen3:1.7b'
    ),

];  