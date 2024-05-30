<?php

namespace Quanticheart\Laravel\Controllers\Push;

class Push
{
    private string $title;
    private string $message;
    private string $to;
    private ?string $deeplink = null;
    private array $payload = [];

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to)
    {
        $this->to = $to;
    }

    /**
     * @param string|null $deeplink
     */
    public function setDeeplink(?string $deeplink)
    {
        $this->deeplink = $deeplink;
    }

    /**
     * @return string
     */
    public function send(): string
    {
        //
        $request = array();
        //
        $request['notification'] = $this->makeNotification();
        $request['data'] = $this->makeData();
        $request['apns']['payload']['aps'] = $this->makeApns();
        //
        $request['content_available'] = true;
        $request['priority'] = 5;
        //
        if (count($this->payload) > 0) {
            $request['registration_ids'] = $this->payload;
        } else {
            $request['to'] = $this->to;
        }
        //
        return $this->makeSend($request);
    }

    private function makeSend($request): string
    {
        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
        return curl_exec($ch);
    }

    private function makeNotification(): array
    {
        $notification = array();
        if ($this->title !== null) {
            $notification['title'] = $this->title;
        }

        if ($this->message !== null) {
            $notification['body'] = $this->message;
        }

        if ($this->deeplink !== null) {
            $notification['deeplink'] = $this->deeplink;
        }

        $notification['badge'] = 1;
        $notification['priority'] = 'high';
        $notification['mutable_content'] = true;
        return $notification;
    }

    private function makeData(): array
    {
        $data = array();
        if ($this->title !== null) {
            $data['title'] = $this->title;
        }

        if ($this->message !== null) {
            $data['body'] = $this->message;
        }

        if ($this->deeplink !== null) {
            $data['deeplink'] = $this->deeplink;
        }

        $data['timestamp'] = date('Y-m-d G:i:s');
        return $data;
    }

    private function makeApns(): array
    {
        $apns = array();
        if ($this->deeplink !== null) {
            $apns['mutableContent'] = true;
            $apns['deeplink'] = $this->deeplink;
        }
        return $apns;
    }
}
