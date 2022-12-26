<?php

return [
    'model' => env('APP_MODELS_DRIVER', 'asmvc'),
    'view' => env('APP_VIEW_ENGINE', 'latte'),
    'router' => env("ROUTING_DRIVER", "new")
];
