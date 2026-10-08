<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$request = Illuminate\Http\Request::capture();

echo 'REQUEST OK';