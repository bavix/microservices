<?php

return [
    'v1' => [
        'type' => 'prefix',
        'path' => '/api/v1/',
        'resolver' => include __DIR__ . '/resolver.php'
    ]
];
