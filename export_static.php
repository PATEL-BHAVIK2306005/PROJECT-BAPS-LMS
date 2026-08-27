<?php

/**
 * Static Site Exporter for Laravel (GitHub Pages & Static Hosting)
 */

// Ensure storage directories exist
@mkdir(__DIR__ . '/storage/framework/sessions', 0777, true);
@mkdir(__DIR__ . '/storage/framework/views', 0777, true);
@mkdir(__DIR__ . '/storage/framework/cache/data', 0777, true);
@mkdir(__DIR__ . '/storage/logs', 0777, true);
@mkdir(__DIR__ . '/bootstrap/cache', 0777, true);

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$distDir = __DIR__ . '/dist';

// 1. Clean and prepare dist directory
if (is_dir($distDir)) {
    // Delete existing files
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($distDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
} else {
    mkdir($distDir, 0777, true);
}

// 2. Copy public directory assets
function copyFolder($src, $dst) {
    if (!is_dir($src)) return;
    $dir = opendir($src);
    @mkdir($dst, 0777, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..') && $file != 'index.php') {
            if (is_dir($src . '/' . $file)) {
                copyFolder($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

echo "Copying public assets to dist/...\n";
copyFolder(__DIR__ . '/public', $distDir);

// 3. Create .nojekyll
file_put_contents($distDir . '/.nojekyll', '');

// 4. List of routes to export
$routes = [
    '/' => 'index.html',
    '/login' => 'login.html',
    '/admin/login' => 'admin/login.html',
    '/register' => 'register.html',
    '/parent/register' => 'parent/register.html',
    '/dashboard' => 'dashboard.html',
    '/admin' => 'admin.html',
    '/parent/dashboard' => 'parent/dashboard.html',
    '/user-manual' => 'user-manual.html',
    '/timetables' => 'timetables.html',
    '/synergy-circle' => 'synergy-circle.html',
    '/ipdc/vault' => 'ipdc/vault.html',
    '/time-capsule' => 'time-capsule.html',
    '/circulars-notices' => 'circulars-notices.html',
    '/courses' => 'courses.html',
];

putenv('STATIC_EXPORT=true');
$_ENV['STATIC_EXPORT'] = 'true';

// Bootstrap application kernel
$kernel->bootstrap();
config(['session.driver' => 'array']);

foreach ($routes as $uri => $outFile) {
    echo "Rendering $uri -> $outFile\n";
    
    $request = Illuminate\Http\Request::create($uri, 'GET');
    
    // Set simulated session role based on path
    if (str_starts_with($uri, '/admin')) {
        $sessionData = ['user_role' => 'admin', 'staff_name' => 'BHAVIKKUMAR PATEL', 'staff_id' => 1];
    } elseif (str_starts_with($uri, '/parent')) {
        $sessionData = ['user_role' => 'parent', 'user_id' => 1];
    } else {
        $sessionData = ['demo_user_id' => 1, 'user_id' => 1];
    }

    $response = $kernel->handle($request);
    $content = $response->getContent();
    
    // If response was redirect, follow or capture
    if ($response->isRedirection()) {
        $target = $response->headers->get('Location');
        echo "  [Redirect -> $target]\n";
        $request = Illuminate\Http\Request::create($target, 'GET');
        $response = $kernel->handle($request);
        $content = $response->getContent();
    }

    $outPath = $distDir . '/' . $outFile;
    @mkdir(dirname($outPath), 0777, true);
    file_put_contents($outPath, $content);
    
    // Also create directory/index.html version for pretty URLs (e.g. /login/index.html)
    if ($outFile !== 'index.html' && str_ends_with($outFile, '.html')) {
        $slug = substr($outFile, 0, -5);
        $prettyPath = $distDir . '/' . $slug . '/index.html';
        @mkdir(dirname($prettyPath), 0777, true);
        file_put_contents($prettyPath, $content);
    }
}

// 5. Create 404.html fallback
if (file_exists($distDir . '/index.html')) {
    copy($distDir . '/index.html', $distDir . '/404.html');
}

echo "Static export completed successfully in dist/\n";
