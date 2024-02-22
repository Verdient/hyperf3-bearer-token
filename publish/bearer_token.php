<?php

use Verdient\token\encoder\Base62Encoder;

use function Hyperf\Support\env;

return [
    'default' => [
        'duration' => env('BEARER_TOKEN_DEFAULT_DURATION', 2592000),
        'key' => env('BEARER_TOKEN_DEFAULT_KEY', 2592000),
        'cost' => env('BEARER_TOKEN_DEFAULT_COST', 10),
        'encoder' => env('BEARER_TOKEN_DEFAULT_ENCODER', Base62Encoder::class),
    ]
];
