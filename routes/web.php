<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\HitungController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KriteriasController;
use App\Http\Controllers\LoginController;
use App\Models\Alternatif;
use Illuminate\Support\Facades\Route;



Route::get('/',[LoginController::class,'index'])->name('login');
Route::post('/login-proses',[LoginController::class,'login_proses'])->name('login-proses');
Route::get('/logout',[LoginController::class,'logout'])->name('logout');


Route::get('/register',[LoginController::class,'register'])->name('register');
Route::post('/register-proses',[LoginController::class,'register_proses'])->name('register-proses');

Route::group(['prefix' => 'admin','middleware' => ['auth'], 'as' => 'admin.'] , function(){
    Route::get('/dashboard',[HomeController::class,'dashboard'])->name('dashboard');
    Route::get('/user',[HomeController::class,'index'])->name('index');

    
    Route::get('/create',[HomeController::class,'create'])->name('user.create');
    Route::post('/store',[HomeController::class,'store'])->name('user.store');
    
    Route::get('/edit/{id}',[HomeController::class,'edit'])->name('user.edit');
    Route::put('/update/{id}',[HomeController::class,'update'])->name('user.update');
    Route::delete('/delete/{id}',[HomeController::class,'delete'])->name('user.delete');

    Route::get('/kriteria', [KriteriasController::class, 'index'])->name('kriteria');
    Route::post('/kriteria/tambah', [KriteriasController::class, 'store'])->name('kriteria.store');
    Route::put('/kriteria/update/{id}', [KriteriasController::class, 'update'])->name('kriteria.update');
    Route::delete('/kriteria/delete{id}', [KriteriasController::class, 'destroy'])->name('kriteria.delete');

    Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif');
    Route::post('/alternatif/tambah', [AlternatifController::class, 'store'])->name('alternatif.store');
    Route::put('/alternatif/update/{id}', [AlternatifController::class, 'update'])->name('alternatif.update');
    Route::delete('/alternatif/delete{id}', [AlternatifController::class, 'destroy'])->name('alternatif.delete');

    Route::get('/Bobot', [BobotController::class, 'index'])->name('Bobot');
    Route::post('/Bobot/tambah', [BobotController::class, 'store'])->name('Bobot.store');
    Route::put('/Bobot/update/{id}', [BobotController::class, 'update'])->name('Bobot.update');
    Route::delete('/Bobot/delete{id}', [BobotController::class, 'destroy'])->name('Bobot.delete');

    Route::get('/hitung', [HitungController::class, 'index'])->name('hitung');
    Route::get('/hitung/SAW', [HitungController::class, 'HitungSAW'])->name('hitungSAW');
});

