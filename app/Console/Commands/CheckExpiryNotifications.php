<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paste;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\Notifications;

class CheckExpiryNotifications extends Command
{
    protected $signature = 'notify:checkexpiry';

    protected $description = 'Check expiry of pastes and send notifications';

    public function handle()
    {
        $expiringPastes = Paste::whereDate('expires_at', '<=', Carbon::tomorrow()->toDateString())->get();

        foreach ($expiringPastes as $paste) {
            if ($paste->user) { // Ensure the paste has a user relationship
                $this->sendNotification($paste->user, $paste);
            }
        }

        $this->info('Expiry notifications sent successfully.');
    }

    private function sendNotification($user, $paste)
    {
        Mail::to($user->email)->send(new Notifications($paste, $user));
    }
}
