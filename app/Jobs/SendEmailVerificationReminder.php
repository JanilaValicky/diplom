<?php

namespace App\Jobs;

use App\Mail\EmailVerificationReminder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::query()
            ->whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays(2))
            ->where('created_at', '>', now()->subDays(3))
            ->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new EmailVerificationReminder($user));

            $user->email_verified_reminder_sent_at = now();
            $user->save();
        }
    }
}
