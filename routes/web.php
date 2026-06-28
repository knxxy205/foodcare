<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonasiController;

// Public Website
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programs', [HomeController::class, 'programs'])->name('programs.index');
Route::get('/programs/{programDonasi}', [HomeController::class, 'programDetail'])->name('programs.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Donatur Area
Route::middleware(['auth', 'role:donatur'])->group(function () {
    Route::get('/donasi-saya', [DonasiController::class, 'index'])->name('donatur.index');
    Route::post('/donasi-saya', [DonasiController::class, 'store'])->name('donatur.store');
    Route::get('/profil', [DonasiController::class, 'profil'])->name('donatur.profil');
    Route::post('/profil', [DonasiController::class, 'updateProfil'])->name('donatur.profil.update');
});

Route::post('/midtrans/notification', [DonasiController::class, 'midtransNotification'])
    ->name('midtrans.notification');
