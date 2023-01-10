<?php

/**
 * Setting allowedMethods, allowedHeaders, and allowedOrigins to *.
 * will ignore rest of the array
 * Setting allowedMethods to 'route' will also ignore the rest of the array.
 */
return [
    'allowedMethods' => ['route'],
    'allowedHeaders' => ['*'],
    'allowedOrigins' => ['*'],
    'maxAge' => 0
];
