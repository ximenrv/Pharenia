<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class InformationController extends Controller
{
    public function index()
    {
        // 1. Obtenemos el idioma actual ('es' o 'en') gracias a tu middleware
        $currentLocale = App::getLocale(); 

        // 2. Leemos el archivo JSON correspondiente a ese idioma
        $path = lang_path($currentLocale . '.json');
        $translations = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

        // 3. Pasamos las traducciones a la vista Blade
        return view('information', compact('translations'));
    }
}