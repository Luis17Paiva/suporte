<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\RamalController;
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
    // Rotas para o AtendimentoController(GET)
    Route::prefix('atendimentos')->group(function () {
        Route::get('/', [AtendimentoController::class, 'index'])->name('atendimentos');
        Route::get('/result', [AtendimentoController::class, 'result'])->name('atendimento.result');
    });
    // Rotas para o RamalController(GET)
    Route::prefix('ramais')->group(function () {
        Route::get('/', [RamalController::class, 'index'])->name('ramais');
        Route::get('/{id}/edit', [RamalController::class, 'edit'])->name('ramal.edit');
        Route::get('/create', [RamalController::class, 'create'])->name('ramal.create');
    });
    // Rotas para o RelatorioController(GET)
    Route::prefix('relatorios')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('relatorios');
    });

    // -----------------------------ROTAS PUT--------------------------------
    // Rotas para o RamalController(PUT)
    Route::prefix('ramais')->group(function () {
        Route::put('/{id}', [RamalController::class, 'update'])->name('ramal.update');
    });

    // -----------------------------ROTAS POST--------------------------------
    // Rotas para o AcessoController(POST)
    Route::prefix('acessos')->group(function () {
        Route::post('/', [AcessoController::class, 'store'])->name('acessos.store');
        Route::post('/{id}', [AcessoController::class, 'update'])->name('acessos.update');
        Route::post('/registrar-acesso/{acessoId}', [AcessoController::class, 'registrarAcesso']);
    });
    // Rotas para o RamalController(POST)
    Route::prefix('ramais')->group(function () {
        Route::post('/store', [RamalController::class, 'store'])->name('ramal.store');
    });
    // Rotas para o RelatorioController(POST)
    Route::prefix('relatorios')->group(function () {
        Route::post('/', [RelatorioController::class, 'showRelatorio'])->name('relatorios.post');
    });
});

