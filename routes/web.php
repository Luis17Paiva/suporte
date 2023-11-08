<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginAndRegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CentralController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\RelatorioController;

Route::get('/login', [LoginAndRegisterController::class, 'showLoginAndRegisterForm'])->name('login');
Route::post('/login', [LoginAndRegisterController::class, 'login']);
Route::post('/logout', [LoginAndRegisterController::class, 'logout'])->name('logout');

Route::get('/register', [LoginAndRegisterController::class, 'showLoginAndRegisterForm'])->name('register');
Route::post('/register', [LoginAndRegisterController::class, 'register']);

Route::get("/verify", [VerificationController::class, "index"])->name('verify.get');
Route::post("/verify", [VerificationController::class, "index"])->name('verify.post');
Route::get("/confirm", [ConfirmPasswordController::class, "index"])->name('confirm.get');
Route::post("/confirm", [ConfirmPasswordController::class, "index"])->name('confirm.post');
Route::get("/reset", [ForgotPasswordController::class, "index"])->name('reset.get');
Route::post("/reset", [ForgotPasswordController::class, "index"])->name('reset.post');

Route::middleware(['auth'])->group(function () {
    Route::get("/home", [HomeController::class,"index"])->name('home');
    Route::post("/home", [HomeController::class,"index"])->name('home.post');
    Route::get('/central',[CentralController::class,'index'])->name('central');
    Route::get('/colaboradores', [ColaboradorController::class,'index'])->name('colaboradores');
    Route::get('/colaboradores/{id}/edit', [ColaboradorController::class, 'edit'])->name('colaboradores.edit');
    Route::put('/colaboradores/{id}', [ColaboradorController::class, 'update'])->name('colaboradores.update');
    Route::get('/colaboradores/create', [ColaboradorController::class, 'create'])->name('colaboradores.create');
    Route::get('/colaboradores/store', [ColaboradorController::class, 'store'])->name('colaboradores.store.get');
    Route::post('/colaboradores/store', [ColaboradorController::class, 'store'])->name('colaboradores.store.post');
    Route::post('/relatorios',[RelatorioController::class,'ShowRelatorio'])->name('relatorios.post');
    Route::get('/relatorios',[RelatorioController::class,'index'])->name('relatorios');
});