<?php

namespace Quanticheart\Laravel\Helpers\Base;

class BaseResponse
{

    private array $response = array();

    private string $keyMsg = 'msg';
    private string $keyStatus = 'status';
    private string $keyData = 'data';
    private string $keyCode = 'code';

    function __construct()
    {
        $this->response = $this->createBaseResponse('ok');
    }

    function ok(string $msg, $data = null, int $code = -1): array
    {
        if (trim($msg) != '') {
            $this->setMsg(trim($msg));
        }

        if ($code != -1) {
            $this->setCode($code);
        }

        if ($data !== null) {
            $this->setData($data);
        }
        return $this->response;
    }

    function error(string $msg, int $code = -1, $data = null): array
    {
        $this->setStatus(false);

        if (trim($msg) != '') {
            $this->setMsg(trim($msg));
        }

        if ($code != -1) {
            $this->setCode($code);
        }

        if ($data !== null) {
            $this->setData($data);
        }

        return $this->response;
    }

    /**
     * for create default data
     *
     * @param string $msg
     * @param bool $status
     * @return array with default data
     */
    private function createBaseResponse(string $msg, bool $status = true): array
    {
        return [
            $this->keyStatus => $status,
            $this->keyMsg => $msg
        ];
    }

    private function setMsg(string $msg)
    {
        $this->response[$this->keyMsg] = $msg;
    }

    private function setStatus(bool $status)
    {
        $this->response[$this->keyStatus] = $status;
    }

    private function setData($data)
    {
        $this->response[$this->keyData] = $data;
    }

    private function setCode(int $code)
    {
        $this->response[$this->keyCode] = $code;
    }
}
