<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\RamalController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\AcessoController;

Auth::routes();

Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::prefix('cadastro')->group(function (){
        Route::prefix('acesso')->group(function () {
            Route::post('/', [AcessoController::class, 'idx'])->name('idx');
            Route::post('/put', [AcessoController::class, 'put'])->name('put');
            Route::post('/edt', [AcessoController::class, 'edt'])->name('edt');
            Route::get('/hst', [AcessoController::class, 'hst'])->name('hst');
        });
        Route::prefix('ramal')->group(function () {
            Route::post('/', [RamalController::class, 'idx'])->name('idx');
            Route::post('/put', [RamalController::class, 'put'])->name('put');
            Route::post('/edt', [RamalController::class, 'edt'])->name('edt');
            Route::get('/hst', [AcessoController::class, 'hst'])->name('hst');
        });
    });
    Route::prefix('suporte')->group(function (){
        Route::prefix('atendimento')->group(function () {
            Route::post('/', [AtendimentoController::class, 'idx'])->name('idx');
            Route::post('/put', [AtendimentoController::class, 'put'])->name('put');
            Route::post('/edt', [AtendimentoController::class, 'edt'])->name('edt');
            Route::get('/hst', [AtendimentoController::class, 'hst'])->name('hst');
        });
    });
    Route::prefix('relatorio')->group(function (){
        Route::prefix('atendimento')->group(function () {
            Route::get('/', [RelatorioController::class, 'index'])->name('idx');
        });
    });
});

