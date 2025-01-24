<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\RegisteredUserController;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('auth.login',  ['title' => 'Login']);
})->middleware('guest');

// Routes Role Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('adminDashboard', [AdminController::class, 'index']);
    Route::get('/admin/chart-data', [AdminController::class, 'getChartData']);

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register.view');
    Route::post('/register/store', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('dataAnggota', [AdminController::class, 'dataAnggota'])->name('dataAnggota');
    Route::get('dataAnggota/edit/{id_user}', [AdminController::class, 'editAnggota'])->name('dataAnggota.edit');
    Route::patch('dataAnggota/update/{id_user}', [AdminController::class, 'updateUser'])->name('dataAnggota.update');
    Route::delete('dataAnggota/delete/{id_user}', [AdminController::class, 'destroyUser'])->name('dataAnggota.delete');

    Route::get('dataTanggungan', [AdminController::class, 'dataTanggungan']);
    Route::get('dataSimpananPokok', [AdminController::class, 'dataSimpananPokok']);
    Route::post('buatTransaksiSimpanan', [AdminController::class, 'buatTransaksiSimpanan']);
    Route::get('checkSimpananStatus', [AdminController::class, 'checkSimpananStatus']);
    Route::post('/transaksi/{id}/update', [AdminController::class, 'updateTransaksiPokok'])->name('transaksi.update');

    Route::get('dataPinjaman', [AdminController::class, 'dataPinjaman']);
    Route::post('updatePinjamanStatus/{id_pinjaman}', [AdminController::class, 'updatePinjamanStatus'])->name('pinjaman.updateStatus');

    Route::get('transaksiSimpanan', [AdminController::class, 'viewTransaksiSimpanan'])->name('viewTransaksiSimpanan.view');
    Route::get('transaksiPinjaman', [AdminController::class, 'viewTransaksiPinjaman'])->name('viewTransaksiPinjaman.view');

    Route::get('export-anggota', [AdminController::class, 'exportExcelAnggota']);
    Route::get('export-transaksi-simpanan', [AdminController::class, 'exportExcelTransaksiSimpanan']);
    Route::get('export-tanggungan', [AdminController::class, 'exportExcelTanggungan']);
    Route::get('export-transaksi-pinjaman', [AdminController::class, 'exportExcelTransaksiPinjaman']);
});

// Routes Role Anggota
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [AnggotaController::class, 'index'])->name('dashboard');
    Route::get('/anggota/chart-data', [AnggotaController::class, 'getChartData']);

    Route::get('pengajuan', [AnggotaController::class, 'pengajuan'])->name('pengajuan.view');
    Route::post('pengajuan', [AnggotaController::class, 'createPengajuan'])->name('pengajuan.create');

    Route::get('/tanggungan', [AnggotaController::class, 'tanggungan'])->name('tanggungan.view');
    Route::post('/updatePinjamanLunas', [AnggotaController::class, 'updatePinjamanLunas'])->name('pinjamanLunas.update');
    Route::post('/updatePinjaman/{id_transaksiPinjaman}', [AnggotaController::class, 'updatePinjaman'])->name('pinjaman.update');
    Route::post('/updateSimpanan/{id_transaksiPokok}', [AnggotaController::class, 'updateSimpanan'])->name('simpanan.update');

    Route::get('history', [AnggotaController::class, 'history']);
    Route::get('transaksiSimpananUser', [AnggotaController::class, 'viewTransaksiSimpanan']);
    Route::get('transaksiPinjamanUser', [AnggotaController::class, 'viewTransaksiPinjaman']);

    Route::get('/profile', [AnggotaController::class, 'viewUser'])->name('profile.view');
    Route::patch('/profile', [AnggotaController::class, 'updateUser'])->name('profile.update');

    Route::get('export-transaksi-simpananAnggota', [AnggotaController::class, 'exportExcelTransaksiSimpananAnggota']);
    Route::get('export-transaksi-pinjamanAnggota', [AnggotaController::class, 'exportExcelTransaksipinjamanAnggota']);
});