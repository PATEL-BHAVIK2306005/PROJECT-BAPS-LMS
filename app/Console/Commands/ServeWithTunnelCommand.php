<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;
use Illuminate\Support\Facades\Http;

class ServeWithTunnelCommand extends BaseServeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server and output a public tunnel link';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $port = $this->input->getOption('port') ?: env('SERVER_PORT', 8000);
        $provider = strtolower(env('TUNNEL_PROVIDER', 'localtunnel'));
        
        $this->line("<info>Initializing built-in server and Tunnel Provider ({$provider})...</info>");

        $logPath = storage_path('logs/tunnel.log');
        if (file_exists($logPath)) {
            @unlink($logPath);
        }

        if ($provider === 'pinggy') {
            // Pinggy SSH remote port forwarding setup
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $cmd = 'start /B ssh -o StrictHostKeyChecking=no -N -p 443 -R0:localhost:' . $port . ' -L4300:localhost:4300 free.pinggy.io > "' . $logPath . '" 2>&1';
                pclose(popen($cmd, 'r'));
            } else {
                $cmd = 'ssh -o StrictHostKeyChecking=no -N -p 443 -R0:localhost:' . $port . ' -L4300:localhost:4300 free.pinggy.io > "' . $logPath . '" 2>&1 &';
                exec($cmd);
            }

            // Wait for Pinggy to establish connection and fetch from local debugger API
            $publicUrl = null;
            for ($i = 0; $i < 8; $i++) {
                sleep(1);
                try {
                    $response = Http::timeout(2)->get('http://127.0.0.1:4300/urls');
                    if ($response->successful()) {
                        $urls = $response->json('urls');
                        if (!empty($urls)) {
                            // Prefer HTTPS url
                            foreach ($urls as $url) {
                                if (str_starts_with($url, 'https://')) {
                                    $publicUrl = $url;
                                    break;
                                }
                            }
                            if (!$publicUrl) {
                                $publicUrl = $urls[0];
                            }
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    // Debugger not running yet
                }
            }

            if ($publicUrl) {
                $this->line('');
                $this->line("  Pinggy Public Link: <fg=green;options=bold>{$publicUrl}</>");
                $this->line('');
            } else {
                $this->warn("Pinggy tunnel started, but couldn't fetch URL.");
                $this->line("  <fg=gray>Please verify SSH connectivity and check: " . $logPath . "</>");
                $this->line('');
            }

        } else {
            // Default to LocalTunnel (npx localtunnel)
            $subdomain = env('TUNNEL_SUBDOMAIN') ?: env('NGROK_SUBDOMAIN');
            if (!$subdomain && env('NGROK_DOMAIN')) {
                $parts = explode('.', env('NGROK_DOMAIN'));
                $subdomain = $parts[0];
            }

            $subdomainOption = $subdomain ? ' --subdomain ' . escapeshellarg($subdomain) : '';

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $cmd = 'start /B npx localtunnel --port ' . $port . $subdomainOption . ' > "' . $logPath . '" 2>&1';
                pclose(popen($cmd, 'r'));
            } else {
                $cmd = 'npx localtunnel --port ' . $port . $subdomainOption . ' > "' . $logPath . '" 2>&1 &';
                exec($cmd);
            }

            // Wait for LocalTunnel to print the URL to the log
            $publicUrl = null;
            for ($i = 0; $i < 6; $i++) {
                sleep(1);
                if (file_exists($logPath)) {
                    $content = file_get_contents($logPath);
                    if (preg_match('/your url is:\s*(https?:\/\/[^\s]+)/i', $content, $matches)) {
                        $publicUrl = $matches[1];
                        break;
                    }
                }
            }

            if ($publicUrl) {
                $this->line('');
                $this->line("  LocalTunnel Public Link: <fg=green;options=bold>{$publicUrl}</>");
                $this->line('');
            } else {
                $this->warn("LocalTunnel started, but couldn't fetch URL.");
                $this->line("  <fg=yellow>Please ensure Node.js is installed on your system.</>");
                $this->line("  <fg=gray>You can manually view logs at: " . $logPath . "</>");
                $this->line('');
            }
        }

        // Proceed to start the Laravel local server
        return parent::handle();
    }
}
