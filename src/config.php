<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'debug'         => true,
        'whoops.editor' => 'sublime', // Support click to open editor
        'determineRouteBeforeAppMiddleware' => true,
        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];