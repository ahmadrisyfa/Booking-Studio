<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Laporan;
use App\Http\Controllers\Laporanpaket;
// use App\Http\Controllers\Admin\LaporanBokingController;

use App\Mail\MalasngodingEmail;
use Illuminate\Support\Facades\Mail;
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

// Route::get('/coba', function () {
//     Mail::to("anggkerputra@gmail.com")->send(new MalasngodingEmail());
//     return view('coba');
// });

Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('booking.index');
Route::get('booking/create/{id}', [\App\Http\Controllers\BookingController::class, 'booking'])->name('booking')->middleware('auth');
Route::post('booking', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
Route::put('booking/{id}/edit', [\App\Http\Controllers\BookingController::class, 'edit'])->name('booking.edit');
Route::post('booking/{id}/update-status', [\App\Http\Controllers\BookingController::class, 'updateStatus'])->name('booking.updateStatus');
Route::get('booking/success/{booking}/{date}/{harga}', [\App\Http\Controllers\BookingController::class, 'success'])->name('booking.success');
Route::put('booking/uploadbukti/{id}', [\App\Http\Controllers\BookingController::class, 'uploadBukti'])->name('booking.uploadBukti');
Route::put('bookingadmin/uploadbukti/{id}', [\App\Http\Controllers\BookingController::class, 'uploadBuktiadmin'])->name('bookingadmin.uploadBukti1');

Route::get('bookingan-saya', [\App\Http\Controllers\BookingController::class, 'mine'])->name('booking.mine');
Route::get('bookingan-saya/nota_pemesanan/{id}', [\App\Http\Controllers\BookingController::class, 'NotaPemesanan']);

Route::get('notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index']);


Route::get('bookingpakets/create/{id}', [\App\Http\Controllers\BookingpaketsController::class, 'bookingpakets'])->name('bookingpakets')->middleware('auth');
Route::post('bookingpakets', [\App\Http\Controllers\BookingpaketsController::class, 'store'])->name('bookingpakets.store');
Route::get('bookingpakets/success/{bookingpakets}/{date}/{harga}', [\App\Http\Controllers\BookingpaketsController::class, 'success'])->name('bookingpakets.success');

Route::put('bookingpakets/uploadbukti/{id}', [\App\Http\Controllers\BookingpaketsController::class, 'uploadBukti'])->name('bookingpakets.uploadBukti');
Route::put('bookingpaketsadmin/uploadbukti/{id}', [\App\Http\Controllers\BookingpaketsController::class, 'uploadBuktiadmin'])->name('bookingpaketsadmin.uploadBukti');

Route::get('bookingan-paket-saya', [\App\Http\Controllers\BookingpaketsController::class, 'mine'])->name('booking-paket.mine');
Route::post('booking-paket/{id}/update-status', [\App\Http\Controllers\BookingpaketsController::class, 'updateStatus'])->name('booking-paket.updateStatus');
Route::put('booking-paket/{id}/edit', [\App\Http\Controllers\BookingpaketsController::class, 'edit'])->name('booking-paket.edit');
Route::get('bookingan-paket-saya/nota_pemesanan/{id}', [\App\Http\Controllers\BookingpaketsController::class, 'NotaPemesanan']);


Route::post('event/booking', [\App\Http\Controllers\Admin\DashboardController::class, 'store'])->name('event.store');
Route::group(['middleware' => ['isAdmin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');



    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::delete('permissions_mass_destroy', [\App\Http\Controllers\Admin\PermissionController::class, 'massDestroy'])->name('permissions.mass_destroy');
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::delete('roles_mass_destroy', [\App\Http\Controllers\Admin\RoleController::class, 'massDestroy'])->name('roles.mass_destroy');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::delete('users_mass_destroy', [\App\Http\Controllers\Admin\UserController::class, 'massDestroy'])->name('users.mass_destroy');

    Route::resource('studios', \App\Http\Controllers\Admin\StudiosController::class);
    Route::post('studios/media', [\App\Http\Controllers\Admin\StudiosController::class, 'storeMedia'])->name('studios.storeMedia');
    Route::delete('studios_mass_destroy', [\App\Http\Controllers\Admin\StudiosController::class, 'massDestroy'])->name('studios.mass_destroy');

    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
    Route::get('bookings/nota_pemesanan/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'NotaPemesanan']);

    Route::post('bookings/search', [\App\Http\Controllers\Admin\BookingController::class, 'laporanSearch']);

    Route::delete('bookings_mass_destroy', [\App\Http\Controllers\Admin\BookingController::class, 'massDestroy'])->name('bookings.mass_destroy');

    Route::resource('services', \App\Http\Controllers\Admin\ServicesController::class);
    Route::delete('services_mass_destroy', [\App\Http\Controllers\Admin\ServicesController::class, 'massDestroy'])->name('services.mass_destroy');
    Route::post('services/media', [\App\Http\Controllers\Admin\ServicesController::class, 'storeMedia'])->name('services.storeMedia');

    Route::resource('bookingpaket', \App\Http\Controllers\Admin\BookingpaketController::class);
    Route::post('bookingpaket/search', [\App\Http\Controllers\Admin\BookingpaketController::class, 'laporanSearch']);
    Route::get('bookingpaket/nota_pemesanan/{id}', [\App\Http\Controllers\Admin\BookingpaketController::class, 'NotaPemesanan']);

    
    Route::get('admin/bookingpaket/edit/{id}/{total}', [\App\Http\Controllers\Admin\BookingpaketController::class, 'edit']);
    Route::delete('bookingpaket_mass_destroy', [\App\Http\Controllers\Admin\BookingpaketController::class, 'massDestroy'])->name('bookingpaket.mass_destroy');

    Route::resource('laporan', \App\Http\Controllers\Admin\LaporanPaketController::class);
    // Route::get('laporan/boking', \App\Http\Controllers\Admin\LaporanPaketController::class);

    Route::get('laporan_booking', [\App\Http\Controllers\Admin\LaporanBokingController::class, 'index'])->name('laporan.booking');
    Route::post('laporan_booking/search', [\App\Http\Controllers\Admin\LaporanBokingController::class, 'laporanSearch']);

    Route::post('laporan/search', [\App\Http\Controllers\Admin\LaporanPaketController::class, 'laporanSearch']);
    Route::get('laporan_penyewa', [\App\Http\Controllers\Admin\LaporanPenyewaController::class, 'index']);
    Route::post('laporan_penyewa/search', [\App\Http\Controllers\Admin\LaporanPenyewaController::class, 'laporanSearch']);
    
    Route::get('notifikasi', [\App\Http\Controllers\Admin\NotifikasiController::class, 'index']);

    
    // Route::get('laporan/transaksi', 'ReportController@transaksi');
});

Route::post('register1', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
Route::get('update/status/{id}', [\App\Http\Controllers\Auth\LoginController::class, 'UpdateStatus']);

// Auth::routes();
Auth::routes(['verify' => true]);
