<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ChildAuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MchatChallenge;
use App\Http\Controllers\SimulationChallenge;
use App\Http\Controllers\MythChallengeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\TeenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameRecordController;
use App\Http\Controllers\ChildGamesController;
use App\Http\Controllers\InformationController;

/*
|--------------------------------------------------------------------------
| Vistas Públicas Principales (Raíz)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/information', function () {
    $currentLocale = App::getLocale();
    $path = lang_path($currentLocale . '.json');
    $translations = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

    return view('information', compact('translations'));
});

// Selector de Idioma
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');


/*
|--------------------------------------------------------------------------
| Flujo de Actividades (Carpeta: activities/)
|--------------------------------------------------------------------------
*/
Route::get('/activities', function () {
    return view('activities');
})->name('activities');

Route::get('/activities/start', function () {
    return view('activities.activities-start');
})->name('activities.start');

Route::middleware(['auth'])->group(function () {
    Route::get('/activities/ninez', function () {
        return view('stages.child');
    })->name('activities.child');

    Route::get('/activities/juventud', function () {
        return view('stages.youth');
    })->name('activities.youth');

    Route::get('/activities/adultez', function () {
        return view('stages.adult');
    })->name('activities.adultez');
});


/*
|--------------------------------------------------------------------------
| Autenticación General (Carpeta: auth/)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::post('/forgot-password', function () {
    return view('auth.forgot-password');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::post('/minor/store', [AdminController::class, 'storeMinor'])->name('admin.minor.store');
    Route::put('/minor/{id}', [AdminController::class, 'updateMinor'])->name('admin.minor.update');
    Route::delete('/minor/{id}', [AdminController::class, 'destroyMinor'])->name('admin.minor.destroy');
});


/*
|--------------------------------------------------------------------------
| Panel Familiar y Gestión de Menores
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/family-panel', [FamilyController::class, 'index'])->name('family-panel');
    Route::post('/family-panel', [FamilyController::class, 'store'])->name('family-panel.store');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/child/{id}/login', [ChildAuthController::class, 'showPinForm'])->name('child.login.form');
    Route::post('/child/{id}/login', [ChildAuthController::class, 'verifyPin'])->name('child.login.verify');
});


/*
|--------------------------------------------------------------------------
| Rutas Protegidas para el Joven y Vinculación
|--------------------------------------------------------------------------
*/
Route::get('/stage-youth', function () {
    return view('stages.youth');
})->name('stage.youth')->middleware('auth');

Route::get('/vincular-adulto', [TeenController::class, 'dashboard'])
    ->name('supervisor.vincular')
    ->middleware('auth');

Route::post('/vincular-adulto', [TeenController::class, 'updateSupervisor'])
    ->name('supervisor.store')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Rutas Protegidas Exclusivamente para el Menor Autenticado
|--------------------------------------------------------------------------
*/
Route::middleware(['child.auth'])->group(function () {
    Route::get('/actividades/ninez', function () {
        return view('stages.child');
    })->name('child.activities');
    
    Route::get('/child/logout-confirm', [ChildAuthController::class, 'showLogoutPinForm'])->name('child.logout.form');
    Route::post('/child/logout-confirm', [ChildAuthController::class, 'verifyLogoutPin'])->name('child.logout.verify');
});


/*
|--------------------------------------------------------------------------
| Módulos de Pruebas, Simulaciones y Mitos (Tus cambios + los de tu amigo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/information/mchat/progress', [MchatChallenge::class, 'saveProgress']);
    Route::post('/information/mchat/submit', [MchatChallenge::class, 'calculateResult']);
    Route::post('/information/mchat/reset', [MchatChallenge::class, 'resetTest'])->name('information.mchat.reset');

    Route::get('/simulation/progress', [SimulationChallenge::class, 'getProgress']);
    Route::post('/simulation/save-progress', [SimulationChallenge::class, 'saveProgress']);
    Route::post('/simulation/submit', [SimulationChallenge::class, 'submitSimulation']);

    Route::get('/challenges/mitos/progress', [MythChallengeController::class, 'getProgress']);
    Route::post('/challenges/mitos/save-progress', [MythChallengeController::class, 'saveProgress']);
    Route::post('/challenges/mitos/submit', [MythChallengeController::class, 'submitChallenge']);
    Route::post('/challenges/mitos/reset', [MythChallengeController::class, 'resetChallenge']);
});

// Ruta con parámetro opcional para los módulos de información
Route::get('/information/{module}', [InformationController::class, 'index'])->name('information.module');


/*
|--------------------------------------------------------------------------
| Juegos Niñez (Aportados por tu amigo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('games/childs')->group(function () {
    Route::get('/guardianes', [ChildGamesController::class, 'guardianes'])->name('games.guardianes');
    Route::get('/eco', [ChildGamesController::class, 'eco'])->name('games.eco');
    Route::get('/cazador', [ChildGamesController::class, 'cazador'])->name('games.cazador');

    Route::post('/record/update', [GameRecordController::class, 'updateRecord'])->name('games.childs.record.update');
    Route::get('/record/get', [GameRecordController::class, 'getRecords'])->name('games.childs.record.get');
});