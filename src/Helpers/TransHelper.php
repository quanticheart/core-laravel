<?php

namespace Quanticheart\Laravel\Helpers;

use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Agent;
use PHPUnit\Exception;

if (!function_exists('trans_fallback')) {
    /**
     * Translate the given message with a fallback string if none exists.
     *
     * @param string $id
     * @param string $fallback
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    function trans_fallback(string $id, string $fallback, $replace = [])
    {
        return ($id === ($translation = trans($id, $replace))) ? $fallback : $translation;
    }
}

if (!function_exists('request_is_br')) {

    /**
     * @return bool
     */
    function request_is_br(): bool
    {
        $local = App::getLocale();
        if (!isset($local)) {
            $agent = new Agent();
            try {
                $local = strtolower($agent->languages()[0]);
            } catch (\Exception $exception) {
                $local = "en";
            }
        }
        return $local === "pt-br" || $local === "pt";
    }
}

if (!function_exists('trans_verify')) {

    function trans_verify(&$array, $nameKey, $defaultString, $stringForVerify)
    {
        $isBR = request_is_br();
        if ($isBR) {
            if (isset($stringForVerify)) {
                $array[$nameKey] = $stringForVerify;
            } else {
                $array[$nameKey] = $defaultString;
            }
        } else {
            $array[$nameKey] = $defaultString;
        }
    }
}
