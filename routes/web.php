<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\UpdateController;

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

// Installation
Route::get('/install', [InstallController::class, 'index'])->name('install');
Route::post('/install', [InstallController::class, 'install'])->name('install.process');

// Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Websites
Route::resource('websites', WebsiteController::class);

// Domains
Route::post('/websites/{website}/domains', [DomainController::class, 'store'])->name('domains.store');
Route::put('/websites/{website}/domains/{domain}', [DomainController::class, 'update'])->name('domains.update');
Route::delete('/websites/{website}/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');

// Files
Route::get('/websites/{website}/files', [FileController::class, 'index'])->name('files.index');
Route::post('/websites/{website}/files', [FileController::class, 'upload'])->name('files.upload');
Route::get('/websites/{website}/files/{filename}', [FileController::class, 'download'])->name('files.download');
Route::delete('/websites/{website}/files/{filename}', [FileController::class, 'destroy'])->name('files.destroy');

// Update System
Route::get('/update', [UpdateController::class, 'index'])->name('update');
Route::post('/update/migrate', [UpdateController::class, 'migrate'])->name('update.migrate');
Route::post('/update/rollback', [UpdateController::class, 'rollback'])->name('update.rollback');
Route::get('/update/status', [UpdateController::class, 'status'])->name('update.status');
