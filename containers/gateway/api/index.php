<?php

include_once __DIR__ . '/vendor/autoload.php';

/**
 * Route list
 *
 * @var array $routes
 */
$routes = include __DIR__ . '/etc/routes.php';

/**
 * Application Router
 */
$router = new \Bavix\Router\Router($routes);

/**
 * Application
 */
$app = new \Gateway\Api\App($router);

/**
 * API Gateway
 */
\header('Content-Type: application/json; charset=utf-8');

try {
    $result = $app->execute();
} catch (\Throwable $throwable) {
    $result = \json_encode([
        'class' => \get_class($throwable),
        'code' => $throwable->getCode(),
        'message' => $throwable->getMessage(),
        'file' => $throwable->getFile(),
        'line' => $throwable->getLine(),
        'trace' => $throwable->getTrace(),
    ]);

    if ($throwable->getCode()) {
        \http_response_code($throwable->getCode());
    }
}

echo $result;
