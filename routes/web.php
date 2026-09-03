<?php

use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InvestmentController as AdminInvestmentController;
use App\Http\Controllers\Admin\SourceController as AdminSourceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContentProgressController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialPlanningController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentFavoriteController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimulationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/health', fn () => response()->json(['status' => 'ok']))->name('health');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/aprender', [LearningController::class, 'index'])->name('learn.index');
    Route::get('/aprender/{slug}', [LearningController::class, 'show'])->name('learn.show');
    Route::put('/aprender/{content:slug}/progresso', [ContentProgressController::class, 'store'])->name('learn.progress.store');
    Route::delete('/aprender/{content:slug}/progresso', [ContentProgressController::class, 'destroy'])->name('learn.progress.destroy');

    Route::get('/investimentos', [InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investimentos/{slug}', [InvestmentController::class, 'show'])->name('investments.show');
    Route::put('/investimentos/{investment:slug}/favorito', [InvestmentFavoriteController::class, 'store'])->name('investments.favorite.store');
    Route::delete('/investimentos/{investment:slug}/favorito', [InvestmentFavoriteController::class, 'destroy'])->name('investments.favorite.destroy');

    Route::get('/simulador', [SimulationController::class, 'index'])->name('simulator.index');
    Route::post('/simulador', [SimulationController::class, 'calculate'])->name('simulator.calculate');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/planejamento', [FinancialPlanningController::class, 'index'])->name('planning.index');
    Route::post('/planejamento', [FinancialPlanningController::class, 'store'])->name('planning.store');
    Route::get('/planejamento/{financialGoal}/editar', [FinancialPlanningController::class, 'edit'])->name('planning.edit');
    Route::match(['put', 'patch'], '/planejamento/{financialGoal}', [FinancialPlanningController::class, 'update'])->name('planning.update');
    Route::delete('/planejamento/{financialGoal}', [FinancialPlanningController::class, 'destroy'])->name('planning.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::resource('conteudos', AdminContentController::class)->parameters(['conteudos' => 'content'])->except('show');
        Route::resource('investimentos', AdminInvestmentController::class)->parameters(['investimentos' => 'investment'])->except('show');
        Route::resource('fontes', AdminSourceController::class)->parameters(['fontes' => 'source'])->except('show');
    });
});
