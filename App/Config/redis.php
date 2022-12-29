<?php

return [
    'REDIS_HOST' => env("REDIS_SERVER", "127.0.0.1"),
    'REDIS_PORT' => env("REDIS_PORT", 6379),
    'REDIS_DATABASE' => env("REDIS_DB_NUMBER", 0),
    "REDIS_AUTH_PASS" => env("REDIS_AUTH_PASS", "")
];
