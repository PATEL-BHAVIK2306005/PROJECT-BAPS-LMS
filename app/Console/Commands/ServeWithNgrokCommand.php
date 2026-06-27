<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;
use Illuminate\Support\Facades\Http;

class ServeWithNgrokCommand extends BaseServeCommand
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
    protected $description = 'Serve the application on the PHP development server and output the Ngrok public link';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $port = $this->input->getOption('port') ?: env('SERVER_PORT', 8000);
        
        $this->line('<info>Initializing built-in server and Ngrok...</info>');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $ngrokCmd = file_exists(base_path('ngrok.exe')) ? '.\ngrok.exe' : 'ngrok';
            pclose(popen('start /B ' . $ngrokCmd . ' http ' . $port . ' > NUL 2>&1', 'r'));
        } else {
            $ngrokCmd = file_exists(base_path('ngrok')) ? './ngrok' : 'ngrok';
            exec($ngrokCmd . ' http ' . $port . ' > /dev/null 2>&1 &');
        }

        // Wait for ngrok to initialize its local API
        sleep(3);

        try {
            $response = Http::get('http://127.0.0.1:4040/api/tunnels');
            if ($response->successful()) {
                $tunnels = $response->json('tunnels');
                if (!empty($tunnels)) {
                    $publicUrl = $tunnels[0]['public_url'];
                    $this->line('');
                    $this->line("  Ngrok Public Link: <fg=green;options=bold>{$publicUrl}</>");
                    $this->line('');
                }
            } else {
                $this->warn("Ngrok running, but couldn't fetch URL. Please ensure ngrok is authenticated.");
            }
        } catch (\Exception $e) {
            $this->warn("Ngrok could not be started or found.");
            $this->line("  <fg=yellow>Please ensure Ngrok is installed.</>");
            $this->line("  <fg=gray>Action Required: Download ngrok from https://ngrok.com/download</>");
            $this->line("  <fg=gray>Extract it and place 'ngrok.exe' directly inside your project folder: " . base_path() . "</>");
            $this->line('');
        }

        // Proceed to start the Laravel local server
        return parent::handle();
    }
}
