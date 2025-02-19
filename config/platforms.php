<?php

return [
    'openai' => [
        'url' => env('OPENAI_URL', 'https://api.openai.com/v1/chat/completions'),
        'api_key' => env('OPENAI_API_KEY'),
    ],
];
