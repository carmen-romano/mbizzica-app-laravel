<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasteController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {

    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Auth::routes();


// Rotte per i paste
Route::get('/pastes/create', [PasteController::class, 'create'])->name('pastes.create');
Route::post('/pastes', [PasteController::class, 'store'])->name('pastes.store');
Route::get('/pastes/{pastes}', [PasteController::class, 'show'])->name('pastes.show');
Route::post('/pastes/{paste}/password', [PasteController::class, 'validatePassword'])->name('pastes.password');




// Rotte per la lista dei paste di utenti autentificati
Route::get('/pastes/user/mypastes', [PasteController::class, 'userPastes'])->name('pastes.user');


// Rotte per ricerca
Route::get('/pastes/search', [PasteController::class, 'search'])->name('pastes.search');




// Rotte per la modifica e cancellazione di un paste
Route::get('/pastes/{paste}/edit', [PasteController::class, 'edit'])->name('pastes.edit');
Route::post('/pastes/{paste}/update', [PasteController::class, 'update'])->name('pastes.update');
Route::delete('/pastes/{paste}/delete', [PasteController::class, 'delete'])->name('pastes.delete');




// Rotte per i tag
Route::get('/pastes/{paste}/tags/create', [TagController::class, 'create'])->name('tags.create');
Route::post('/pastes/{paste}/tags', [TagController::class, 'store'])->name('pastes.tags.store');

// Rotte per i commenti/vote

Route::post('/pastes/{paste}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/pastes/{paste}/like', [PasteController::class, 'likePaste'])->name('pastes.like');


Route::get('/pastes/take/{id}-{slug}', [PasteController::class, 'take'])->name('pastes.take');



Route::post('/pastes/store-unauthenticated', [PasteController::class, 'storeUnauthenticated'])->name('pastes.store-unauthenticated');


// Rotte di reset della password
Route::get('/password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');


// Rotta di completamento della registrazione
Route::get('/composer-registration', [RegisterController::class, 'completeRegistration'])->name('complete.registration');

// Middleware 2FA per proteggere le rotte
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/2fa', function () {
        return redirect(route('dashboard'));
    })->name('2fa');
});
