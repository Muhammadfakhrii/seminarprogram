<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;



//login admin
Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    Route::post('/loginadmin' , [AuthController::class, 'loginadmin']);
});


// <!-- Untuk karyawan -->
//login karyawan
Route::middleware(['guest:karyawan'])->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
Route::post('/proseslogin' , [AuthController::class, 'proseslogin']);
});

Route::middleware(['auth:karyawan'])->group(function(){
});

Route::get('/dashboard',[DashboardController::class, 'index']);

Route::get('/proseslogout', [AuthController::class, 'proseslogout'])->name('proseslogout');

//presensi untuk foto
Route::get('/presensi/create', [PresensiController::class, 'create']);

Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');

//edit profil karyawan
Route::get('/edit', [PresensiController::class, 'edit']);
Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile'])->name('updateprofile');

//history presensi
Route::get('presensi/history', [PresensiController::class, 'history']);
Route::post('gethistory', [PresensiController::class, 'gethistory']);

Route::middleware(['auth:user'])->group(function () {
    Route::get('/logoutadmin', [AuthController::class, 'logoutadmin'])->name('logoutadmin');
    Route::get('/panel/dashboardadmin' ,[DashboardController::class, 'dashboardadmin']);
});

Route::get('/karyawan', [KaryawanController::class, 'index']);
Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);

Route::get('/karyawan/delete/{nik}', [KaryawanController::class, 'destroy'])->name('karyawan.delete');






