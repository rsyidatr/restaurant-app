<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/admin/menu', 'GET');
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Headers: " . json_encode($response->headers->all()) . "\n";
echo "Content preview: " . substr($response->getContent(), 0, 500) . "\n";
