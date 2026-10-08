<?php

$storagePath = '/tmp/laravel-storage';
$bootstrapPath = '/tmp/laravel-bootstrap';

@mkdir($storagePath, 0777, true);
@mkdir($storagePath . '/logs', 0777, true);
@mkdir($storagePath . '/framework/cache/data', 0777, true);
@mkdir($storagePath . '/framework/sessions', 0777, true);
@mkdir($storagePath . '/framework/views', 0777, true);

@mkdir($bootstrapPath . '/cache', 0777, true);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$request = Illuminate\Http\Request::capture();

$app->handleRequest($request);