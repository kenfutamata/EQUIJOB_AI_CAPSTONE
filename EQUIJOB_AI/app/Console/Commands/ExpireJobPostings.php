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

        // 1. Look for any job where the endDate has passed
        // 2. AND the status is NOT already 'Expired'
        $expiredPostings = JobPosting::where('endDate', '<', now())
            ->where('status', '!=', 'Expired')
            ->get();

        if ($expiredPostings->isEmpty()) {
            $this->info('No expired job postings found.');
            return;
        }

        $count = 0;
        foreach ($expiredPostings as $posting) {
            try {
                $posting->status = 'Expired';
                $posting->save();
                $this->info("Updated: #{$posting->id} - {$posting->position}");
                Log::info("Job posting #{$posting->id} marked as Expired.");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to update #{$posting->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully updated {$count} job postings.");
    }
}
