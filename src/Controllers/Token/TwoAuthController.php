<?php

namespace App\Http\Controllers\Api\Token;

use Exception;
use Quanticheart\Laravel\OTP\OTP;
use Symfony\Component\HttpFoundation\Request;

class TwoAuthController
{
    function validate(Request $request)
    {
    }

//    function generateTwoAuth(Request $request)
//    {
//        $twoAuth = new OTP();
//
//    }

//    function test()
//    {
//        try {
//
//            $code = "337488";
//
//            $twoAuth = new OTP();
//            $base32 = $twoAuth->generateSecretKeyForUser("2");
//            echo("Base32: " . $base32 . "\n");
//            $twoAuth->setSecretKey($base32);
//            // Decode it into binary
//            $otp = $twoAuth->getOtpCode();
//            echo("One time Password: $otp\n");
//
//            // Use this to verify a key as it allows for some time drift.
//            $result = $twoAuth->verifyOtpCode($code);
//            echo("One time Password Result: ");
//            var_dump($result);
//
//            echo "\n\n";
//
//            $qrCode = new QRCode();
//            $qrCode->setText($twoAuth->getOtpLinkForQRCode());
//            echo "Link: " . $twoAuth->getOtpLinkForQRCode() . "\n";
//            echo "QRCode Link: " . $qrCode->getQRCodeLink() . "\n";
//
//            echo "\n\n";
//
//            if ($code === $otp) {
//                echo "Código Válidao com sucesso!";
//            } else {
//                echo "Código Inválido!Por favor verifique novamente!";
//            }
//
//        } catch (Exception $e) {
//            echo $e;
//        }
//    }
}
