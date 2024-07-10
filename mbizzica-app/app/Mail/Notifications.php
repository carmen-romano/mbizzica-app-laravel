<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Paste;
use App\Models\User;


class Notifications extends Mailable
{
    use Queueable, SerializesModels;

    public $paste;
    public $user;

    public function __construct(Paste $paste, User $user)
    {
        $this->paste = $paste;
        $this->user = $user;
    }

    public function build()
    {
        return $this->from('cantineromanoo@gmail.com')
            ->subject('Il tuo Paste è in scadenza')
            ->view('emails.notifications');
    }
}
