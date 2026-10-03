<?php

namespace App\Console\Commands;

use App\Modules\Course\Models\CourseBatch;
use App\Modules\Course\Notifications\BatchStartingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

/**
 * Daily reminder for students enrolled in a batch starting tomorrow
 * (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7).
 */
class SendBatchStartReminders extends Command
{
    protected $signature = 'courses:batch-reminders';

    protected $description = 'Send a reminder email to students enrolled in batches starting tomorrow';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $batches = CourseBatch::whereDate('starts_at', $tomorrow)->get();

        foreach ($batches as $batch) {
            $students = $batch->batchEnrollments()
                ->where('status', 'enrolled')
                ->with('enrollment.user')
                ->get()
                ->pluck('enrollment.user')
                ->filter();

            if ($students->isEmpty()) {
                continue;
            }

            Notification::send($students, new BatchStartingReminder($batch));
        }

        return self::SUCCESS;
    }
}
