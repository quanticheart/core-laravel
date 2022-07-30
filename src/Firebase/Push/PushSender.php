<?php

namespace Quanticheart\Laravel\Firebase\Push;

class PushSender
{
    public static function allUsers(array $tokenList, string $title, string $message, ?string $deeplink = null): array
    {
        $push = new Push();
        $push->setTitle($title);
        $push->setMessage($message);
        $push->setDeeplink($deeplink);
        $push->setPayload($tokenList);
        $result = json_decode($push->send());

        $arrResponse = array();
        if ($result->failure > 0) {
            foreach ($result->results as $index => $result) {
                if (isset($result->error)) {
                    $arrResponse[] = $index;
                }
            }
        }
        return $arrResponse;
    }

    public static function toUser(string $token, string $title, string $message, ?string $deeplink): string
    {
        $push = new Push();
        $push->setTitle($title);
        $push->setMessage($message);
        $push->setDeeplink($deeplink);
        $push->setTo($token);
        return $push->send();
    }
}
