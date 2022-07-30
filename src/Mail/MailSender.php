<?php

namespace Quanticheart\Laravel\Mail;

use Illuminate\Support\Facades\Mail;

class MailSender
{
    public static function allUsers(array $emailList): bool
    {
        $result = true;
        foreach ($emailList as $email) {
            $rawResult = self::toUser($email);
            if (!$rawResult) {
                $result = false;
            }
        }
        return $result;
    }

    public static function toUser(string $email): bool
    {
        Mail::to($email)->send(new SendExampleEmail($email));
        if (count(Mail::failures()) > 0) {
//                foreach (Mail::failures() as $email_address) {
//                    echo "$email_address <br/>";
//                }
            return false;
        } else {
            return true;
        }
    }
}
