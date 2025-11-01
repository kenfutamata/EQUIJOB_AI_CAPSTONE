    <?php

    use Illuminate\Foundation\Console\ClosureCommand;
    use Illuminate\Foundation\Inspiring;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Schedule;

    Artisan::command('inspire', function () {
        /** @var ClosureCommand $this */
        $this->comment(Inspiring::quote());
    })->purpose('Display an inspiring quote');

    Schedule::command('feedback:send-request')->dailyAt('00:00')->appendOutputTo(storage_path('logs/feedback_requests.log'));
    Schedule::command('app:send-interview-reminders')->dailyAt('08:00')->appendOutputTo(storage_path('logs/interview_reminders.log'));
    Schedule::command('app:check-my-path');
    Schedule::command('app:send-interview-details {application}')->appendOutputTo(storage_path('logs/interview_details.log'));