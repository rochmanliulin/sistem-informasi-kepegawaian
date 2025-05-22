<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class SendPasswordResetEmails extends Command
{
    protected $signature = 'users:send-reset-emails';
    protected $description = 'Send password reset emails to all users';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            Password::sendResetLink(['email' => $user->email]);
            $this->info("Sent reset email to: {$user->email}");
        }

        $this->info("Completed sending reset emails to {$users->count()} users");
    }
}
