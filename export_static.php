<?php

/**
 * Static Site Exporter for Laravel (GitHub Pages & Static Hosting)
 * Converts dynamic Laravel full-stack LMS into high-performance static pages
 * with automated asset re-linking, GitHub Pages subpath routing, and interactive mock triggers.
 */

// 1. Ensure storage directories exist
@mkdir(__DIR__ . '/storage/framework/sessions', 0777, true);
@mkdir(__DIR__ . '/storage/framework/views', 0777, true);
@mkdir(__DIR__ . '/storage/framework/cache/data', 0777, true);
@mkdir(__DIR__ . '/storage/logs', 0777, true);
@mkdir(__DIR__ . '/bootstrap/cache', 0777, true);

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$distDir = __DIR__ . '/dist';
$repoPrefix = '/PROJECT-BAPS-LMS';

// 2. Clean and prepare dist directory
if (is_dir($distDir)) {
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

// 3. Copy public directory assets (build, images, css, js, icons)
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

// 4. Create .nojekyll to prevent GitHub Pages from dropping assets
file_put_contents($distDir . '/.nojekyll', '');

// 5. List of core routes to export
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

// Client-side interactive helper for GitHub Pages static preview
$staticScript = <<<HTML
<script>
/** Deela LMS - GitHub Pages Interactive Routing & Demo Adapter **/
document.addEventListener('DOMContentLoaded', function() {
    // 1. Hide loader immediately once assets ready
    var loader = document.getElementById('baps-global-loader');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(function() { loader.style.display = 'none'; }, 300);
    }

    // 2. Intercept Login/Auth forms for instant rich Demo Dashboard access
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var action = (form.getAttribute('action') || '').toLowerCase();
            if (action.includes('login') || action.includes('auth')) {
                e.preventDefault();
                var btn = form.querySelector('button[type="submit"]') || form.querySelector('button');
                if (btn) btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Entering Portal...';
                setTimeout(function() {
                    if (action.includes('admin') || window.location.href.includes('admin')) {
                        window.location.href = '$repoPrefix/admin.html';
                    } else if (action.includes('parent') || window.location.href.includes('parent')) {
                        window.location.href = '$repoPrefix/parent/dashboard.html';
                    } else {
                        window.location.href = '$repoPrefix/dashboard.html';
                    }
                }, 500);
            }
        });
    });
});
</script>
HTML;

foreach ($routes as $uri => $outFile) {
    echo "Rendering $uri -> $outFile\n";
    
    $request = Illuminate\Http\Request::create($uri, 'GET');
    
    // Set simulated session role based on route
    if (str_starts_with($uri, '/admin')) {
        $sessionData = ['user_role' => 'admin', 'staff_name' => 'BHAVIKKUMAR PATEL', 'staff_id' => 1];
    } elseif (str_starts_with($uri, '/parent')) {
        $sessionData = ['user_role' => 'parent', 'user_id' => 1];
    } else {
        $sessionData = ['demo_user_id' => 1, 'user_id' => 1];
    }

    $response = $kernel->handle($request);
    $content = $response->getContent();
    
    // If response was redirect, follow
    if ($response->isRedirection()) {
        $target = $response->headers->get('Location');
        echo "  [Redirect -> $target]\n";
        $request = Illuminate\Http\Request::create($target, 'GET');
        $response = $kernel->handle($request);
        $content = $response->getContent();
    }

    // 6. Post-process HTML for GitHub Pages subpath compatibility
    // Replace domain-specific absolute paths with GitHub Pages repo path
    $content = str_replace('http://localhost/', $repoPrefix . '/', $content);
    $content = str_replace('http://127.0.0.1:8000/', $repoPrefix . '/', $content);
    $content = str_replace('http://localhost', $repoPrefix, $content);
    $content = str_replace('http://127.0.0.1:8000', $repoPrefix, $content);

    // Re-link static assets
    $content = preg_replace('/href="\/build\//', 'href="' . $repoPrefix . '/build/', $content);
    $content = preg_replace('/src="\/build\//', 'src="' . $repoPrefix . '/build/', $content);
    $content = preg_replace('/href="\/css\//', 'href="' . $repoPrefix . '/css/', $content);
    $content = preg_replace('/src="\/js\//', 'src="' . $repoPrefix . '/js/', $content);
    $content = preg_replace('/src="\/images\//', 'src="' . $repoPrefix . '/images/', $content);
    $content = preg_replace('/src="\/img\//', 'src="' . $repoPrefix . '/img/', $content);
    $content = preg_replace('/href="\/storage\//', 'href="' . $repoPrefix . '/storage/', $content);
    $content = preg_replace('/src="\/storage\//', 'src="' . $repoPrefix . '/storage/', $content);
    $content = preg_replace('/href="\/favicon\.ico"/', 'href="' . $repoPrefix . '/favicon.ico"', $content);

    // Re-link internal navigation URLs to exported HTML files
    $urlMappings = [
        'href="/"' => 'href="' . $repoPrefix . '/index.html"',
        'href="' . $repoPrefix . '/"' => 'href="' . $repoPrefix . '/index.html"',
        'href="/login"' => 'href="' . $repoPrefix . '/login.html"',
        'href="/admin/login"' => 'href="' . $repoPrefix . '/admin/login.html"',
        'href="/register"' => 'href="' . $repoPrefix . '/register.html"',
        'href="/parent/register"' => 'href="' . $repoPrefix . '/parent/register.html"',
        'href="/dashboard"' => 'href="' . $repoPrefix . '/dashboard.html"',
        'href="/admin"' => 'href="' . $repoPrefix . '/admin.html"',
        'href="/parent/dashboard"' => 'href="' . $repoPrefix . '/parent/dashboard.html"',
        'href="/user-manual"' => 'href="' . $repoPrefix . '/user-manual.html"',
        'href="/timetables"' => 'href="' . $repoPrefix . '/timetables.html"',
        'href="/synergy-circle"' => 'href="' . $repoPrefix . '/synergy-circle.html"',
        'href="/ipdc/vault"' => 'href="' . $repoPrefix . '/ipdc/vault.html"',
        'href="/time-capsule"' => 'href="' . $repoPrefix . '/time-capsule.html"',
        'href="/circulars-notices"' => 'href="' . $repoPrefix . '/circulars-notices.html"',
        'href="/courses"' => 'href="' . $repoPrefix . '/courses.html"',
    ];

    foreach ($urlMappings as $from => $to) {
        $content = str_replace($from, $to, $content);
    }

    // Inject static adapter script before </body>
    if (str_contains($content, '</body>')) {
        $content = str_replace('</body>', $staticScript . '</body>', $content);
    } else {
        $content .= $staticScript;
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

// 7. Create 404.html fallback
if (file_exists($distDir . '/index.html')) {
    copy($distDir . '/index.html', $distDir . '/404.html');
}

echo "Static export completed successfully in dist/\n";
