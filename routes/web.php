<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ChildAuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MchatChallenge;
use App\Http\Controllers\SimulationChallenge;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeenController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Vistas Públicas Principales (Raíz)
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/information', [MchatChallenge::class, 'index'])->name('information');


/*
|--------------------------------------------------------------------------
| Flujo de Actividades (Carpeta: activities/)
|--------------------------------------------------------------------------
*/
// Vista principal con el título "ACTIVIDADES" y el faro
Route::get('/activities', function () {
    return view('activities');
})->name('activities');

// Línea de tiempo interactiva (Antes activities-start)
Route::get('/activities/start', function () {
    return view('activities.activities-start');
})->name('activities.start');

// Dinámica por etapas individuales
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

// Recuperación de contraseña
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
    
    // Rutas de Usuarios (Adultos y Adolescentes)
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    // Rutas de Menores
    Route::post('/minor/store', [AdminController::class, 'storeMinor'])->name('admin.minor.store');
    Route::put('/minor/{id}', [AdminController::class, 'updateMinor'])->name('admin.minor.update');
    Route::delete('/minor/{id}', [AdminController::class, 'destroyMinor'])->name('admin.minor.destroy');
});
/*
|--------------------------------------------------------------------------
| Panel Familiar y Gestión de Menores (Carpeta: profile/ y auth/)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Panel de control del tutor
    Route::get('/family-panel', [FamilyController::class, 'index'])->name('family-panel');
    Route::post('/family-panel', [FamilyController::class, 'store'])->name('family-panel.store');

    // Edición de perfil del usuario (Carpeta: profile/)
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');

    // Acceso mediante PIN para menores
    Route::get('/child/{id}/login', [ChildAuthController::class, 'showPinForm'])->name('child.login.form');
    Route::post('/child/{id}/login', [ChildAuthController::class, 'verifyPin'])->name('child.login.verify');
});
/*
|--------------------------------------------------------------------------
| Rutas Protegidas Exclusivamente para el joven Autenticado
|--------------------------------------------------------------------------
*/
Route::get('/stage-youth', function () {
    return view('stages.youth'); // Cambia esto por la ruta de tu vista real si se llama diferente
})->name('stage.youth')->middleware('auth');

// Ruta para mostrar la vista de vincular adulto supervisor
Route::get('/vincular-adulto', function () {
    return view('teen.vincular'); // Ajusta la ruta de la vista según la tengas creada
})->name('supervisor.vincular')->middleware('auth');

// Ruta para procesar el formulario de vinculación (usando el controlador que ya tienes)
Route::get('/vincular-adulto', function () {
    return view('profile.adult-supervisor');
})->name('supervisor.vincular')->middleware('auth');

Route::get('/vincular-adulto', [TeenController::class, 'dashboard'])
    ->name('supervisor.vincular')
    ->middleware('auth');

// Ruta POST para procesar y guardar la vinculación del tutor
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
    
    // Cierre de sesión protegido con PIN
    Route::get('/child/logout-confirm', [ChildAuthController::class, 'showLogoutPinForm'])->name('child.logout.form');
    Route::post('/child/logout-confirm', [ChildAuthController::class, 'verifyLogoutPin'])->name('child.logout.verify');
});

/*
|--------------------------------------------------------------------------
| Módulos de Pruebas y Simulaciones (M-CHAT y Simulaciones)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/information/mchat/progress', [MchatChallenge::class, 'saveProgress']);
    Route::post('/information/mchat/submit', [MchatChallenge::class, 'calculateResult']);
    Route::post('/information/mchat/reset', [MchatChallenge::class, 'resetTest'])->name('information.mchat.reset');

    Route::get('/', [SimulationChallenge::class, 'getProgress']);
    Route::post('/simulation/save-progress', [SimulationChallenge::class, 'saveProgress']);
    Route::post('/simulation/submit', [SimulationChallenge::class, 'submitSimulation']);
});