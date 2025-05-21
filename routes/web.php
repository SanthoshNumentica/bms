<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;


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

Route::get('/debug-assets', function () {
    return response()->json([
        'public_build_files' => File::allFiles(public_path('build')),
        'public_css_filament' => File::allFiles(public_path('css/filament')),
    ]);
});


Route::get('/', fn () => Redirect::to('/admin'));


Route::post('/send-whatsapp/{id}', [WhatsAppController::class, 'findCaseReportById'])->name('send.whatsapp');


