<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', fn () => 'Admin Panel Placeholder');



Route::post('/send-whatsapp/{id}', [WhatsAppController::class, 'findCaseReportById'])->name('send.whatsapp');


