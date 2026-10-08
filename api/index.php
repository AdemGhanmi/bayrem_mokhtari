<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$request = Illuminate\Http\Request::capture();

try {
    $response = $app->handleRequest($request);

    echo 'HANDLE OK';
} catch (\Throwable $e) {
    http_response_code(500);

    echo '<h1>Laravel Error</h1>';
    echo '<pre>';
    echo 'Class: ' . get_class($e) . "\n";
    echo 'Message: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . "\n";
    echo 'Line: ' . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
    echo '</pre>';
}