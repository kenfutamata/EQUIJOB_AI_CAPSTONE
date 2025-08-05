<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class TestPythonReport extends Command
{
    protected $signature = 'test:python-report {userId}';
    protected $description = 'Tests the Python report generation script directly.';

    public function handle()
    {
        $userId = $this->argument('userId');
        $this->info("Testing report generation for User ID: {$userId}");

        $pythonExecutable = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('scripts/job-provider-report-generation.py');

        $db_config = [
            'host'     => config('database.connections.pgsql.host'),
            'port'     => config('database.connections.pgsql.port'),
            'database' => config('database.connections.pgsql.database'),
            'username' => config('database.connections.pgsql.username'),
            'password' => config('database.connections.pgsql.password'),
        ];


        $process = new Process([
            $pythonExecutable,
            $scriptPath,
            $userId, // Pass the raw user ID
            json_encode($db_config), // Pass the raw, valid JSON string
        ]);

        $process->setWorkingDirectory(base_path());
        $process->setTimeout(120);

        try {
            $process->mustRun();
            $this->info("--- Python Script Output ---");
            $this->line($process->getOutput());
            $this->info("✅ SUCCESS: The command executed without crashing.");
        } catch (\Exception $e) {
            $this->error("❌ FAILED: The process failed to run.");
            $this->error($e->getMessage());
            $this->comment("--- Python Error Output ---");
            $this->line($process->getErrorOutput());
        }
    }
}
