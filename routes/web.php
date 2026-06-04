<?php

use App\Http\Controllers\CartaoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FluxoCaixaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('fluxo-caixa')->name('fluxo-caixa.')->group(function () {
    Route::get('/', [FluxoCaixaController::class, 'index'])->name('index');
    Route::get('/criar', [FluxoCaixaController::class, 'create'])->name('create');
    Route::post('/', [FluxoCaixaController::class, 'store'])->name('store');
    Route::get('/{id}', [FluxoCaixaController::class, 'show'])->name('show');
    Route::get('/{id}/editar', [FluxoCaixaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FluxoCaixaController::class, 'update'])->name('update');
    Route::delete('/{id}', [FluxoCaixaController::class, 'destroy'])->name('destroy');
});

Route::prefix('contas')->name('contas.')->group(function () {
    Route::get('/', [ContaController::class, 'index'])->name('index');
    Route::get('/transferencia', [ContaController::class, 'transferencia'])->name('transferencia');
    Route::post('/transferencia', [ContaController::class, 'transferir'])->name('transferir');
    Route::get('/{conta}', [ContaController::class, 'show'])->name('show');
});

Route::prefix('cartoes')->name('cartoes.')->group(function () {
    Route::get('/', [CartaoController::class, 'index'])->name('index');
    Route::get('/{cartao}', [CartaoController::class, 'show'])->name('show');
    Route::get('/{cartao}/fatura', [CartaoController::class, 'fatura'])->name('fatura');
    Route::get('/{cartao}/compras/criar', [CartaoController::class, 'createCompra'])->name('compras.create');
    Route::post('/{cartao}/compras', [CartaoController::class, 'storeCompra'])->name('compras.store');
});

Route::resource('clientes', ClienteController::class)->parameters(['clientes' => 'cliente']);

Route::prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/criar', [CategoriaController::class, 'create'])->name('create');
    Route::post('/', [CategoriaController::class, 'store'])->name('store');
    Route::get('/{categoria}/editar', [CategoriaController::class, 'edit'])->name('edit');
    Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('update');
    Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('destroy');
});

Route::prefix('relatorios')->name('relatorios.')->group(function () {
    Route::get('/receitas', [RelatorioController::class, 'receitas'])->name('receitas');
    Route::get('/despesas', [RelatorioController::class, 'despesas'])->name('despesas');
    Route::get('/fluxo', [RelatorioController::class, 'fluxo'])->name('fluxo');
    Route::post('/exportar-pdf', [RelatorioController::class, 'exportarPdf'])->name('exportar-pdf');
});

Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('index');
    Route::get('/criar', [UsuarioController::class, 'create'])->name('create');
    Route::post('/', [UsuarioController::class, 'store'])->name('store');
    Route::get('/{usuario}/editar', [UsuarioController::class, 'edit'])->name('edit');
    Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
    Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
    Route::post('/trocar-perfil', [UsuarioController::class, 'trocarPerfil'])->name('trocar-perfil');
});
