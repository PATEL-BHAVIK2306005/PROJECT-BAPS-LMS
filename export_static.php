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
    '/guest' => 'guest.html',
    '/profile' => 'profile.html',
    '/hub' => 'hub.html',
];

putenv('STATIC_EXPORT=true');
$_ENV['STATIC_EXPORT'] = 'true';

// Bootstrap application kernel
$kernel->bootstrap();
config(['session.driver' => 'array']);

// Client-side interactive helper & router adapter for GitHub Pages
$staticScript = <<<HTML
<script>
/** Deela LMS - Global GitHub Pages Subpath & Link Router Adapter **/
(function() {
    var REPO_PREFIX = '$repoPrefix';

    // 1. Hide loader immediately once DOM is ready
    function dismissLoader() {
        var loader = document.getElementById('baps-global-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(function() { loader.style.display = 'none'; }, 300);
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', dismissLoader);
    } else {
        dismissLoader();
    }
    window.addEventListener('load', dismissLoader);
    setTimeout(dismissLoader, 800);

    // 2. Global Link Click Interceptor - Guarantees NEVER leaving /PROJECT-BAPS-LMS/ subpath
    document.addEventListener('click', function(e) {
        var a = e.target.closest('a');
        if (!a) return;
        var href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('http://') || href.startsWith('https://')) {
            return;
        }

        // If link is root-relative (starts with /) but does not have the repo prefix
        if (href.startsWith('/') && !href.startsWith(REPO_PREFIX)) {
            e.preventDefault();
            var targetPage = href;
            
            // Map root
            if (targetPage === '/' || targetPage === '') {
                window.location.href = REPO_PREFIX + '/index.html';
                return;
            }
            
            // Map admin internal sub-routes to tabs
            if (targetPage === '/admin' || targetPage === '/admin/') {
                window.location.href = REPO_PREFIX + '/admin.html#tab-overview';
                return;
            }
            if (targetPage.startsWith('/admin/timetables')) {
                window.location.href = REPO_PREFIX + '/timetables.html';
                return;
            }
            if (targetPage.startsWith('/admin/') && targetPage !== '/admin/login') {
                var tab = '#tab-overview';
                var lower = targetPage.toLowerCase();
                if (lower.includes('exam')) tab = '#tab-exams';
                else if (lower.includes('attendance')) tab = '#tab-academic';
                else if (lower.includes('chat') || lower.includes('profile') || lower.includes('staff') || lower.includes('student')) tab = '#tab-directory';
                else if (lower.includes('placement') || lower.includes('operation')) tab = '#tab-operations';
                else if (lower.includes('ipdc') || lower.includes('assignment')) tab = '#tab-ipdc';
                else if (lower.includes('approval')) tab = '#tab-approvals';
                else if (lower.includes('hostel')) tab = '#tab-hostel';
                else if (lower.includes('report')) tab = '#tab-reports';
                else if (lower.includes('system')) tab = '#tab-system';
                else if (lower.includes('ptm')) tab = '#tab-admin-ptm';
                else if (lower.includes('circular')) tab = '#tab-circulars';
                else if (lower.includes('synergy')) tab = '#tab-synergy-circle';
                else if (lower.includes('special')) tab = '#tab-special-courses';
                else if (lower.includes('query') || lower.includes('queries')) tab = '#tab-student-queries';
                else if (lower.includes('payroll')) tab = '#tab-payroll';
                else if (lower.includes('setting')) tab = '#tab-settings';
                else if (lower.includes('maintenance')) tab = '#tab-maintenance';
                else if (lower.includes('volunteer')) tab = '#tab-volunteer';

                window.location.href = REPO_PREFIX + '/admin.html' + tab;
                return;
            }

            // Append .html if needed
            var cleanPath = targetPage;
            if (!cleanPath.endsWith('.html') && !cleanPath.includes('.')) {
                cleanPath = cleanPath + '.html';
            }
            
            window.location.href = REPO_PREFIX + cleanPath;
        }
    }, true);

    // 3. Intercept Login/Auth forms for instant rich Demo Dashboard access
    document.addEventListener('submit', function(e) {
        var form = e.target;
        var action = (form.getAttribute('action') || '').toLowerCase();
        if (action.includes('login') || action.includes('auth')) {
            e.preventDefault();
            var btn = form.querySelector('button[type="submit"]') || form.querySelector('button');
            if (btn) btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Entering Portal...';
            setTimeout(function() {
                if (action.includes('admin') || window.location.href.includes('admin')) {
                    window.location.href = REPO_PREFIX + '/admin.html#tab-overview';
                } else if (action.includes('parent') || window.location.href.includes('parent')) {
                    window.location.href = REPO_PREFIX + '/parent/dashboard.html';
                } else {
                    window.location.href = REPO_PREFIX + '/dashboard.html';
                }
            }, 400);
        }
    }, true);
})();
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
    } elseif (in_array($uri, ['/', '/login', '/register', '/parent/register', '/courses', '/guest', '/user-manual'])) {
        $sessionData = [];
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

    // Re-link internal navigation URLs to exported HTML files with repo prefix
    $urlMappings = [
        'href="/"' => 'href="' . $repoPrefix . '/index.html"',
        'href="' . $repoPrefix . '/"' => 'href="' . $repoPrefix . '/index.html"',
        'href="/login"' => 'href="' . $repoPrefix . '/login.html"',
        'href="/admin/login"' => 'href="' . $repoPrefix . '/admin/login.html"',
        'href="/register"' => 'href="' . $repoPrefix . '/register.html"',
        'href="/parent/register"' => 'href="' . $repoPrefix . '/parent/register.html"',
        'href="/dashboard"' => 'href="' . $repoPrefix . '/dashboard.html"',
        'href="/admin"' => 'href="' . $repoPrefix . '/admin.html#tab-overview"',
        'href="/admin/"' => 'href="' . $repoPrefix . '/admin.html#tab-overview"',
        'href="/parent/dashboard"' => 'href="' . $repoPrefix . '/parent/dashboard.html"',
        'href="/user-manual"' => 'href="' . $repoPrefix . '/user-manual.html"',
        'href="/timetables"' => 'href="' . $repoPrefix . '/timetables.html"',
        'href="/admin/timetables"' => 'href="' . $repoPrefix . '/timetables.html"',
        'href="/admin/attendance"' => 'href="' . $repoPrefix . '/admin.html#tab-academic"',
        'href="/admin/placement"' => 'href="' . $repoPrefix . '/admin.html#tab-operations"',
        'href="/admin/chat"' => 'href="' . $repoPrefix . '/admin.html#tab-directory"',
        'href="/admin/profile"' => 'href="' . $repoPrefix . '/admin.html#tab-directory"',
        'href="/admin/ipdc"' => 'href="' . $repoPrefix . '/admin.html#tab-ipdc"',
        'href="/admin/exam/schedule"' => 'href="' . $repoPrefix . '/admin.html#tab-exams"',
        'href="/admin/exam"' => 'href="' . $repoPrefix . '/admin.html#tab-exams"',
        'href="/admin/exams"' => 'href="' . $repoPrefix . '/admin.html#tab-exams"',
        'href="/admin/approvals"' => 'href="' . $repoPrefix . '/admin.html#tab-approvals"',
        'href="/admin/hostel"' => 'href="' . $repoPrefix . '/admin.html#tab-hostel"',
        'href="/admin/reports"' => 'href="' . $repoPrefix . '/admin.html#tab-reports"',
        'href="/admin/system"' => 'href="' . $repoPrefix . '/admin.html#tab-system"',
        'href="/synergy-circle"' => 'href="' . $repoPrefix . '/synergy-circle.html"',
        'href="/ipdc/vault"' => 'href="' . $repoPrefix . '/ipdc/vault.html"',
        'href="/time-capsule"' => 'href="' . $repoPrefix . '/time-capsule.html"',
        'href="/circulars-notices"' => 'href="' . $repoPrefix . '/circulars-notices.html"',
        'href="/courses"' => 'href="' . $repoPrefix . '/courses.html"',
        'href="/guest"' => 'href="' . $repoPrefix . '/courses.html"',
        'href="/profile"' => 'href="' . $repoPrefix . '/profile.html"',
        'href="/hub"' => 'href="' . $repoPrefix . '/hub.html"',
    ];

    foreach ($urlMappings as $from => $to) {
        $content = str_replace($from, $to, $content);
    }

    // Replace any remaining root-relative href="/..." links with repo-prefixed links
    $content = preg_replace_callback('/href="\/([a-zA-Z0-9_\-\/]+)"/', function($matches) use ($repoPrefix) {
        $path = $matches[1];
        if (str_starts_with($path, 'PROJECT-BAPS-LMS')) return $matches[0];
        return 'href="' . $repoPrefix . '/' . $path . '.html"';
    }, $content);

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

// 7. Generate redirect helper files for direct admin sub-paths
$subTabRedirects = [
    'admin/exam' => 'tab-exams',
    'admin/exams' => 'tab-exams',
    'admin/exam/schedule' => 'tab-exams',
    'admin/attendance' => 'tab-academic',
    'admin/academic' => 'tab-academic',
    'admin/chat' => 'tab-directory',
    'admin/profile' => 'tab-directory',
    'admin/directory' => 'tab-directory',
    'admin/placement' => 'tab-operations',
    'admin/operations' => 'tab-operations',
    'admin/ipdc' => 'tab-ipdc',
    'admin/approvals' => 'tab-approvals',
    'admin/hostel' => 'tab-hostel',
    'admin/reports' => 'tab-reports',
    'admin/system' => 'tab-system',
    'admin/ptm' => 'tab-admin-ptm',
    'admin/circulars' => 'tab-circulars',
    'admin/synergy' => 'tab-synergy-circle',
    'admin/special-courses' => 'tab-special-courses',
    'admin/student-queries' => 'tab-student-queries',
    'admin/role-settings' => 'tab-role-settings',
    'admin/payroll' => 'tab-payroll',
    'admin/volunteer' => 'tab-volunteer',
    'admin/maintenance' => 'tab-maintenance',
];

foreach ($subTabRedirects as $subPath => $tabId) {
    $redirectHtml = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url=$repoPrefix/admin.html#$tabId">
    <title>Redirecting to Management Portal...</title>
    <script>
        window.location.replace('$repoPrefix/admin.html#$tabId');
    </script>
</head>
<body style="font-family:sans-serif; text-align:center; padding:50px; background:#f8fafc; color:#334155;">
    <h2>BAPS LMS Administrative Portal</h2>
    <p>Navigating to section...</p>
    <a href="$repoPrefix/admin.html#$tabId" style="color:#ea580c; font-weight:bold;">Click here if not redirected automatically</a>
</body>
</html>
HTML;
    
    $dirTarget = $distDir . '/' . $subPath . '/index.html';
    @mkdir(dirname($dirTarget), 0777, true);
    file_put_contents($dirTarget, $redirectHtml);

    $fileTarget = $distDir . '/' . $subPath . '.html';
    @mkdir(dirname($fileTarget), 0777, true);
    file_put_contents($fileTarget, $redirectHtml);
}

// 8. Create intelligent SPA 404.html fallback for GitHub Pages
$spa404Html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS LMS - Routing...</title>
    <script>
        (function() {
            var repo = '$repoPrefix';
            var path = window.location.pathname;
            var search = window.location.search || '';
            var hash = window.location.hash || '';

            // Clean path
            var relative = path;
            if (relative.startsWith(repo)) {
                relative = relative.substring(repo.length);
            }
            relative = relative.replace(/^\/+|\/+$/g, '');
            var lower = relative.toLowerCase();

            // 1. Admin sub-route or tab requests like admin.html/EXAM or admin/exam or admin/attendance
            if (lower.includes('admin') || lower.includes('exam') || lower.includes('attendance') || lower.includes('chat') || lower.includes('placement')) {
                var tab = 'tab-overview';
                if (lower.includes('exam')) tab = 'tab-exams';
                else if (lower.includes('attendance') || lower.includes('academic') || lower.includes('timetable')) tab = 'tab-academic';
                else if (lower.includes('chat') || lower.includes('profile') || lower.includes('directory') || lower.includes('staff') || lower.includes('student')) tab = 'tab-directory';
                else if (lower.includes('placement') || lower.includes('operation')) tab = 'tab-operations';
                else if (lower.includes('ipdc') || lower.includes('assignment')) tab = 'tab-ipdc';
                else if (lower.includes('approval')) tab = 'tab-approvals';
                else if (lower.includes('hostel')) tab = 'tab-hostel';
                else if (lower.includes('report')) tab = 'tab-reports';
                else if (lower.includes('system')) tab = 'tab-system';
                else if (lower.includes('ptm')) tab = 'tab-admin-ptm';
                else if (lower.includes('circular')) tab = 'tab-circulars';
                else if (lower.includes('synergy')) tab = 'tab-synergy-circle';
                else if (lower.includes('special')) tab = 'tab-special-courses';
                else if (lower.includes('query') || lower.includes('queries')) tab = 'tab-student-queries';
                else if (lower.includes('payroll')) tab = 'tab-payroll';
                else if (lower.includes('setting')) tab = 'tab-settings';
                else if (lower.includes('maintenance')) tab = 'tab-maintenance';
                else if (lower.includes('volunteer')) tab = 'tab-volunteer';

                window.location.replace(repo + '/admin.html#' + tab);
                return;
            }

            // 2. Main Page routes
            var pages = ['dashboard', 'courses', 'hub', 'time-capsule', 'timetables', 'profile', 'circulars-notices', 'synergy-circle', 'login', 'register', 'user-manual'];
            for (var i = 0; i < pages.length; i++) {
                if (lower.includes(pages[i])) {
                    window.location.replace(repo + '/' + pages[i] + '.html' + search + hash);
                    return;
                }
            }

            // 3. Fallback to index
            window.location.replace(repo + '/index.html');
        })();
    </script>
</head>
<body style="font-family:system-ui,sans-serif; text-align:center; padding:50px; background:#f8fafc; color:#334155;">
    <h2 style="color:#ea580c;">BAPS LMS Global Portal</h2>
    <p>Navigating to your requested destination...</p>
    <a href="$repoPrefix/index.html" style="color:#ea580c; font-weight:bold;">Click here to return to Home</a>
</body>
</html>
HTML;

file_put_contents($distDir . '/404.html', $spa404Html);

echo "Static export completed successfully in dist/\n";
