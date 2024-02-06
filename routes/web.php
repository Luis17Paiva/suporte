<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentralController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\AcessoController;

Auth::routes();

Route::middleware(['auth'])->group(function () {

    // -----------------------------ROTAS GET--------------------------------
    // Rotas para o AcessoController(GET)
    Route::prefix('acessos')->group(function () {
        Route::get('/', [AcessoController::class, 'index'])->name('acessos');
        Route::get('/create', [AcessoController::class, 'create'])->name('acessos.create');
        Route::get('/{id}/edit', [AcessoController::class, 'edit'])->name('acessos.edit');
        Route::get('/historico-acessos/{acessoId}', [AcessoController::class, 'historicoAcessos']);
    });
    // Rotas para o CentralController(GET)
    Route::prefix('central')->group(function () {
        Route::get('/', [CentralController::class, 'index'])->name('central');
        Route::get('/result', [CentralController::class, 'result'])->name('central.result');
    });
    // Rotas para o ColaboradorController(GET)
    Route::prefix('colaboradores')->group(function () {
        Route::get('/', [ColaboradorController::class, 'index'])->name('colaboradores');
        Route::get('/{id}/edit', [ColaboradorController::class, 'edit'])->name('colaboradores.edit');
        Route::get('/create', [ColaboradorController::class, 'create'])->name('colaboradores.create');
    });
    // Rotas para o RelatorioController(GET)
    Route::prefix('relatorios')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('relatorios');
    });

    // -----------------------------ROTAS PUT--------------------------------
    // Rotas para o ColaboradorController(PUT)
    Route::prefix('colaboradores')->group(function () {
        Route::put('/{id}', [ColaboradorController::class, 'update'])->name('colaboradores.update');
    });

    // -----------------------------ROTAS POST--------------------------------
    // Rotas para o AcessoController(POST)
    Route::prefix('acessos')->group(function () {
        Route::post('/', [AcessoController::class, 'store'])->name('acessos.store');
        Route::post('/{id}', [AcessoController::class, 'update'])->name('acessos.update');
        Route::post('/registrar-acesso/{acessoId}', [AcessoController::class, 'registrarAcesso']);
    });
    // Rotas para o ColaboradorController(POST)
    Route::prefix('colaboradores')->group(function () {
        Route::post('/store', [ColaboradorController::class, 'store'])->name('colaboradores.store');
    });
    // Rotas para o RelatorioController(POST)
    Route::prefix('relatorios')->group(function () {
        Route::post('/', [RelatorioController::class, 'showRelatorio'])->name('relatorios.post');
    });
});

