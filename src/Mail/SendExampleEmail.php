<?php

namespace Quanticheart\Laravel\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendExampleEmail extends Mailable
{

    use Queueable, SerializesModels;

    private string $sendEmail;

    public function __construct(string $sendEmail)
    {
        $this->sendEmail = $sendEmail;
    }

    public function build()
    {
        return $this
            ->subject('test send email')
            ->replyTo(env('MAIL_FROM_ADDRESS'))
            ->html('ok <br> email for ' . $this->sendEmail);
    }
}
