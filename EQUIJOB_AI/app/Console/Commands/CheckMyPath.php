<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMyPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-my-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('This command is being executed from the project at: ' . base_path());
    }
}
