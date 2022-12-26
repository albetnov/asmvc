<?php

return [
    'type' => env('SESSION_TYPE', 'php'),
    'ip-validation' => env('SESSION_IP_VALIDATION', false),
    'session-basic-validation' => env('SESSION_VALIDATION', true),
    'secure' => env('SESSION_SECURE', false)
];
