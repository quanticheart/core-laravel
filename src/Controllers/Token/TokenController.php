<?php

namespace App\Http\Controllers\Api\Token;


use Quanticheart\Laravel\Helpers\HashHelper;

class TokenController
{
    function generateToken()
    {
        $token = HashHelper::generateApiToken("master");
        dd($token);
    }

}
