<?php

namespace Quanticheart\Laravel\Helpers;

use Illuminate\Support\Facades\Crypt;

class HashHelper
{
    static function encrypt(string $string): string
    {
        return Crypt::encryptString('keySecret:::' . $string);
    }

    static function decrypt(string $string): string
    {
        return explode(":::", Crypt::decryptString($string))[1];
    }
}
