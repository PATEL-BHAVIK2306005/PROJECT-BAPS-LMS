<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

echo "--- Cache Test ---\n";
$start = microtime(true);
Cache::put('speed_test', 'working', 60);
$writeTime = (microtime(true) - $start) * 1000;
echo "Cache Write: " . round($writeTime, 2) . "ms\n";

$start = microtime(true);
$val = Cache::get('speed_test');
$readTime = (microtime(true) - $start) * 1000;
echo "Cache Read: " . round($readTime, 2) . "ms (Value: {$val})\n";

echo "\n--- DB Latency Test ---\n";
$conn = config('database.default');
echo "Active connection: {$conn}\n";

$start = microtime(true);
try {
    DB::connection($conn)->getPdo();
    $connectTime = (microtime(true) - $start) * 1000;
    echo "Connection Pdo Open Time: " . round($connectTime, 2) . "ms\n";
    
    $start = microtime(true);
    $res = DB::connection($conn)->select("SELECT 1");
    $queryTime = (microtime(true) - $start) * 1000;
    echo "Query 'SELECT 1' Time: " . round($queryTime, 2) . "ms\n";
} catch (\Exception $e) {
    echo "DB Connection Failed: " . $e->getMessage() . "\n";
}
