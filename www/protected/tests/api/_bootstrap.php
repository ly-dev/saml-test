<?php
// Here you can initialize variables that will be available to your tests
require_once '_ApiCest.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();