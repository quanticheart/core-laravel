<?php

namespace Quanticheart\Laravel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class LocaleController extends Controller
{
    public function locale($locale): RedirectResponse
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return back();
    }

    public static function defaultLocale()
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            $agent = new Agent();
            $newLocale = array_key_exists(0, $agent->languages()) ? $agent->languages()[0] : App::getLocale();
            App::setLocale($newLocale);
            Session::put('locale', $newLocale);
        }
    }
}
