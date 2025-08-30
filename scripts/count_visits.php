<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
// change the date below as needed
$date = '2025-08-30';
echo \App\Models\Visit::whereHas('appointment', function($q) use ($date) {
    $q->whereDate('appointment_start_time', $date);
})->count();
echo PHP_EOL;
