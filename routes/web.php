<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/information', function () {
    return view('information');
})->name('information');

Route::get('/activities', function () {
    return view('activities');
})->name('activities');

Route::get('/panel-familiar', function () {
    return view('family-panel');
})->name('family.dashboard');

use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ChildAuthController;

// Rutas accesibles para tutores autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/panel-familiar', [FamilyController::class, 'index'])->name('family-panel');
    Route::post('/panel-familiar', [FamilyController::class, 'store'])->name('family-panel.store');

    Route::middleware(['auth'])->group(function () {
    Route::get('/actividades/ninez', [ChildAuthController::class, 'activities'])->name('child.activities');
});

    // Rutas de inicio de sesión con PIN para los menores
    Route::get('/child/{id}/login', [ChildAuthController::class, 'showPinForm'])->name('child.login.form');
    Route::post('/child/{id}/login', [ChildAuthController::class, 'verifyPin'])->name('child.login.verify');
});

// Rutas protegidas exclusivamente para el menor autenticado (vía middleware)
Route::middleware(['child.auth'])->group(function () {
    Route::get('/actividades/ninez', [ChildAuthController::class, 'activities'])->name('child.activities');
    
    // Cierre de sesión protegido con PIN
    Route::get('/child/logout-confirm', [ChildAuthController::class, 'showLogoutPinForm'])->name('child.logout.form');
    Route::post('/child/logout-confirm', [ChildAuthController::class, 'verifyLogoutPin'])->name('child.logout.verify');
});


// Ruta directa para el adulto TEA (sin restricciones de PIN infantil)



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Si no tienes este método, usa la vista directa abajo
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register'); // O tu vista de registro
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta clave que estaba dando error: password.request
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    return view('auth.forgot-password');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::match(['get', 'post'], '/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');

Route::middleware(['auth'])->group(function () {
    Route::get('/actividades', function () {
        return view('activities-start');
    })->name('actividades.start');
});

use App\Http\Controllers\ActivityController;

Route::get('/actividades/{stage}', [ActivityController::class, 'showStage'])->name('activities.stage');

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\ChallengeController;

Route::get('/information', [ChallengeController::class, 'index'])->name('information');

use App\Http\Controllers\MchatChallenge;

// Ruta principal para cargar la vista del test (GET)
Route::get('/information', [MchatChallenge::class, 'index'])->name('information');

Route::middleware(['auth'])->group(function () {
    Route::post('/information/mchat/progress', [MchatChallenge::class, 'saveProgress']);
    Route::post('/information/mchat/submit', [MchatChallenge::class, 'calculateResult']);
    Route::post('/information/mchat/reset', [MchatChallenge::class, 'resetTest'])->name('information.mchat.reset');
});

use App\Http\Controllers\SimulationChallenge;

Route::middleware(['auth'])->group(function () {
    Route::get('/simulation/progress', [SimulationChallenge::class, 'getProgress']);
    Route::post('/simulation/save-progress', [SimulationChallenge::class, 'saveProgress']);
    Route::post('/simulation/submit', [SimulationChallenge::class, 'submitSimulation']);
});