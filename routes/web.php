<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleDriveController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('google/login',[GoogleDriveController::class,'googleLogin'])->name('google.login');
Route::get('google-drive/file-upload',[GoogleDriveController::class,'googleDriveFileUpload'])->name('google.drive.file.upload');
Route::get('/google/drive/upload', [GoogleDriveController::class, 'showUploadForm'])->name('google.drive.upload.form');
Route::post('/google/drive/upload', [GoogleDriveController::class, 'uploadFile'])->name('google.drive.upload');
// Add a route for the listFiles method
Route::get('/google/drive/list', [GoogleDriveController::class, 'listFiles'])->name('google.drive.list');
Route::delete('/google/drive/delete', [GoogleDriveController::class, 'deleteFiles'])->name('google.drive.delete');
// routes/web.php
Route::get('/google/drive/update/{driveId}', [GoogleDriveController::class, 'showUpdateForm'])
    ->name('google.drive.update.form');

Route::put('/google/drive/update/{driveId}', [GoogleDriveController::class, 'updateFile'])
    ->name('google.drive.update');
