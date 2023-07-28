<?php

use App\Http\Controllers\AzureController;
use App\Http\Controllers\BlobController;
use Illuminate\Support\Facades\Route;
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

Route::fallback(function () {
    $object = 'page';
    return view('container.404', compact('object'));
});

// Containers

Route::resource('container', AzureController::class);

Route::post('/container/deleteContainers', [AzureController::class, 'deleteContainers']);

// Blobs

Route::get('/container/{container}/{blob}/delete', [AzureController::class, 'deleteBlob']);

Route::post('/container/deleteBlobs', [AzureController::class, 'deleteBlobs']);

Route::get('/container/{container}/create', [AzureController::class, 'uploadFilesForm']);

Route::post('/container/{container}/upload', [AzureController::class, 'uploadFiles']);

Route::get('/container/{container}/{blob}', [AzureController::class, 'showBlob']);

Route::get('/container/{container}/{blob}/download', [AzureController::class, 'downloadFile']);
