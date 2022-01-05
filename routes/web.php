<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BincangController;
use App\Http\Controllers\EditprofilController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\KritikController;
use App\Http\Controllers\artikelController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\TulisCeritaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataController;

//use App\Http\Controllers\SocialShareButtonsController;


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

//tampilan
Route::get('/', function () {
    return view('beranda.index');
})->name('dashboard');

// tentang
Route::get('/tentang', [TentangController::class, 'index']);
Route::get('/bidang', [TentangController::class, 'bidang']);
Route::get('/kampungkeren', [TentangController::class, 'kampungkeren']);
Route::get('/pokmas', [TentangController::class, 'pokmas']);

// data
Route::get('/grafik', [DataController::class, 'grafik']);
Route::get('/banksampah', [DataController::class, 'banksampah']);
Route::get('/peta', [DataController::class, 'peta']);

// informasi
Route::resource('informasi', InformasiController::class);
// media
Route::resource('media', MediaController::class);
// kritik
Route::resource('kritik', KritikController::class);

//audio
Route::resource('audio', AudioController::class);
// Route::get('/audio', [AudioController::class, 'index']);

//video
Route::get('/video', [VideoController::class, 'index']);

//foto
Route::resource('foto', FotoController::class);

//tulis Cerita
Route::resource('tuliscerita', TulisCeritaController::class);


//login & profil
Route::get('/loginuser', [AuthController::class, 'loginuser'])->name('login');
Route::post('/postlogin', [AuthController::class, 'postlogin']);
Route::get('/registrasi', [AuthController::class, 'registrasi']);
Route::post('/postregist', [AuthController::class, 'postregist']);
Route::resource('profil', EditprofilController::class);

Route::group(['middleware' => 'auth'], function(){ //agar tidak dapat tampil menggunakan linknya
    Route::get('/profil/{id}/edit', [ProfilController::class, 'index']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});

//berlangganan
Route::get('beranda.index', [NewsletterController::class, 'create']);
Route::post('beranda.index', [NewsletterController::class, 'store']);

//artikel
Route::resource('artikel', artikelController::class);
Route::get('/artikel/1', function () {
    return view('artikel.artikelLay');
});
//Route::post('add_prosess', 'artikelController@add_process');

//kritik saran
Route::get('kritik.index', [App\Http\Controllers\KritikController::class, 'kritik'])->name('kritik');
Route::post('kritik-saran', [App\Http\Controllers\KritikController::class, 'storeContactForm'])->name('contact-form.store');

//Admin
Route::post('/admin', 'AuthSubmissionController@home');
Route::post('/admin/submission', 'SubmissionController@store');
Route::get('/admin/submission', 'SubmissionController@index');
Route::get('/admin/submission/{subs_id}', 'SubmissionController@show');
Route::delete('/admin/submission/{subs_id}', 'SubmissionController@destroy');

//artikel admin
Route::get('/admin/add-article', 'ArticleController@create');
Route::post('/admin/list-article', 'ArticleController@store');
Route::get('/admin/list-article', 'ArticleController@index');
Route::get('/admin/article/{article_id}', 'ArticleController@edit');
Route::put('/admin/article/{article_id}', 'ArticleController@update');
Route::delete('/admin/article/{article_id}', 'ArticleController@destroy');

//foto
Route::get('/admin/add-foto', 'FotoController@create');
Route::post('/admin/list-foto', 'FotoController@store');
Route::get('/admin/list-foto', 'FotoController@index');
Route::get('/admin/foto/{foto_id}', 'FotoController@edit');
Route::put('/admin/foto/{foto_id}', 'FotoController@update');
Route::delete('/admin/foto/{foto_id}', 'FotoController@destroy');

//video
Route::get('/admin/add-video', 'VideoController@create');
Route::post('/admin/list-video', 'VideoController@store');
Route::get('/admin/list-video', 'VideoController@index');

//audio
Route::get('/admin/add-audio', 'AudioController@create');
Route::post('/admin/list-audio', 'AudioController@store');
Route::get('/admin/list-audio', 'AudioController@index');

//admin
Route::resource('admin', AdminController::class);
