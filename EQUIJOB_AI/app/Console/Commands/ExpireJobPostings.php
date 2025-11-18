<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Log;

class ExpireJobPostings extends Command
{
    protected $signature = 'job-postings:expire';
    protected $description = 'Updates the status of job postings that have passed their end date';

    public function handle()
    {
        $this->info('Checking for expired job postings...');

        $expiredPostings = JobPosting::where('status', 'For Posting')
                                     ->where('endDate', '<', now()->startOfDay())
                                     ->get();

        if ($expiredPostings->isEmpty()) {
            $this->info('No expired job postings found.');
            return;
        }

        foreach ($expiredPostings as $posting) {
            $posting->status = 'Expired'; 
            $posting->save();
            Log::info("Job posting #{$posting->id} ('{$posting->position}') has been marked as Expired.");
        }

        $this->info("Successfully updated {$expiredPostings->count()} job postings to 'Expired'.");
    }
}