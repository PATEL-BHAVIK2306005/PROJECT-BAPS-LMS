<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseStateService;

class CheckDatabaseHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the health of Hybrid Database connections (Online/Offline)';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseStateService $dbService)
    {
        $this->info("Checking Hybrid Database Health...");
        
        $health = $dbService->checkHealth();

        $this->table(
            ['Connection', 'Status', 'Error'],
            [
                ['Offline (Local)', $health['offline']['status'], $health['offline']['error'] ?? 'None'],
                ['Online (Cloud)', $health['online']['status'], $health['online']['error'] ?? 'None'],
            ]
        );

        $this->info("Current Active Connection: " . $health['current']);
        $this->info("State in .env: " . env('DB_STATE', 'offline'));

        if ($health[$health['current'] === 'mysql_online' ? 'online' : 'offline']['status'] === 'connected') {
            $this->info("System is READY for operations.");
        } else {
            $this->error("ALERT: The active connection is DISCONNECTED!");
        }
    }
}
