<?php

namespace Quanticheart\Laravel\Constants;

class ResponseCodes
{
    /**
     * Code Errors
     */
    /* TOKEN */
    const RESPONSE_CODE_TOKEN_EXPIRED = 15;
    const RESPONSE_CODE_TOKEN_INVALID = 16;
    const RESPONSE_CODE_TOKEN_TOKEN_OUT = 17;

    /* LOGIN */
    const RESPONSE_CODE_LOGIN_SUCCESS = 50;
    const RESPONSE_CODE_LOGIN_USER_NOT_FOUND = 55;
    const RESPONSE_CODE_LOGIN_ERROR = 56;
    const RESPONSE_CODE_LOGIN_USER_BLOCKED = 57;
    const RESPONSE_CODE_LOGIN_USER_NOT_HAVE_ACCESS = 58;
    const RESPONSE_CODE_LOGIN_USER_NOT_ACTIVATED_YET = 59;

    /* Email */
    const RESPONSE_CODE_EMAIL_FAILED_SEND = 65;

    /*Validation Fails*/
    const RESPONSE_CODE_VALIDATE_FAILED = 75;
    const RESPONSE_CODE_QUERY_FAILED = 76;

    /*Api TOKEN*/
    const RESPONSE_CODE_API_TOKEN_OUT = 85;

    /*CSRF TOKEN*/
    const RESPONSE_CODE_CSRF_TOKEN_OUT = 419;

    /*Server Erros*/
    const RESPONSE_CODE_500 = 150;
    const RESPONSE_CODE_400 = 151;
}
