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

    static function generateApiToken($userName): string
    {
        return self::uuidV5(self::sha1($userName), $userName);
    }

    static function generateTokenID($id): string
    {
        return self::uuidV5(self::sha1($id), $id);
    }

    private static function sha1($string): string
    {
        return sha1($string . "x23");
    }

    private static function uuidV5($name_space, $string): string
    {
        $n_hex = str_replace(array('-', '{', '}'), '', $name_space); // Getting hexadecimal components of namespace
        $binaryStr = ''; // Binary value string
        //Namespace UUID to bits conversion
        for ($i = 0; $i < strlen($n_hex); $i += 2) {
            $binaryStr .= chr(hexdec($n_hex[$i] . $n_hex[$i + 1]));
        }
        //hash value
        $hashing = sha1($binaryStr . $string . now() . rand(0, 99));

        return sprintf('%08s-%04s-%04x-%04x-%12s',
            // 32 bits for the time_low
            substr($hashing, 0, 8),
            // 16 bits for the time_mid
            substr($hashing, 8, 4),
            // 16 bits for the time_hi,
            (hexdec(substr($hashing, 12, 4)) & 0x0fff) | 0x5000,
            // 8 bits and 16 bits for the clk_seq_hi_res,
            // 8 bits for the clk_seq_low,
            (hexdec(substr($hashing, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for the node
            substr($hashing, 20, 12)
        );
    }
}
