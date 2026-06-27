<?php
// BAPS LMS performance optimizer and cache cleaner script

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

echo "=============================================\n";
echo "       BAPS LMS PERFORMANCE OPTIMIZER        \n";
echo "=============================================\n\n";

echo "1. Clearing Laravel application caches...\n";
try {
    Artisan::call('optimize:clear');
    echo "   [✓] Caches cleared successfully.\n";
} catch (\Exception $e) {
    echo "   [✗] Error clearing caches: " . $e->getMessage() . "\n";
}

echo "\n2. Clearing Custom Database Health caches...\n";
try {
    $keys = [
        'db_health_offline_status',
        'db_health_online_status',
        'db_health_mysql_online',
        'db_health_mysql',
        'speed_test'
    ];
    foreach ($keys as $key) {
        Cache::forget($key);
        echo "   [✓] Cache key '{$key}' cleared.\n";
    }
} catch (\Exception $e) {
    echo "   [✗] Error clearing database health caches: " . $e->getMessage() . "\n";
}

echo "\n3. Re-caching configurations, routes, and views...\n";
try {
    Artisan::call('optimize');
    echo "   [✓] System configurations, routes, and views compiled and cached.\n";
} catch (\Exception $e) {
    echo "   [✗] Error during optimization: " . $e->getMessage() . "\n";
}

echo "\n4. Running Composer Autoloader Optimization...\n";
$composerCmd = 'composer dump-autoload -o';
echo "   Executing: '{$composerCmd}'...\n";
$output = shell_exec($composerCmd);
if ($output) {
    echo "   " . trim($output) . "\n";
    echo "   [✓] Composer classloader map optimized.\n";
} else {
    echo "   [!] Composer optimization skipped or failed (composer might not be in PATH).\n";
}

echo "\n=============================================\n";
echo "   SYSTEM SUCCESSFULLY OPTIMIZED FOR SPEED   \n";
echo "=============================================\n";
