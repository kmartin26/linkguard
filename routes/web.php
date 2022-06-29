<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DmcaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;

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

Route::get('/', [HomeController::class,'index',
])->name('front.index');

// About page
Route::get('about', function (){
    return view('about');
});

// Contact form page
Route::get('contact', [ContactController::class, 'create'])->name('front.contact.get');

Route::post('contact', [ContactController::class, 'store'])->name('front.contact.post');

// DMCA form page
Route::get('dmca', [DmcaController::class, 'create'])->name('front.dmca.get');

Route::post('dmca', [DmcaController::class, 'store'])->name('front.dmca.post');

// Link Routes
Route::post('create', [HomeController::class, 'create'])->name('front.create');

Route::get('create', function () {
    return redirect()->route('front.index');
});

Route::get('{id}/delete', [HomeController::class, 'getDelete'])->name('front.delete.get');

Route::post('{id}/delete', [HomeController::class, 'postDelete'])->name('front.delete.post');

Route::get('{id}', [HomeController::class, 'getShow'])->name('front.show.get');

Route::match(['get','post'], '{id}/show', [HomeController::class, 'postShow'])->name('front.show.post');