<?php

namespace Quanticheart\Laravel\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Quanticheart\Laravel\Models\User\User;

class UserRegisteredEmail extends Mailable
{

    use Queueable, SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject('test send email')
            ->replyTo(env('MAIL_FROM_ADDRESS'))
            ->html('ok');
    }
}
