<?php

return [
    'processor' => [
        'type' => 'pattern',
        'path' => '<processor>(/<action>)',
        'defaults' => [
            'action' => 'default',
            'timeout' => 15
        ]
    ],
];
