<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/storage/{path}', function ($path) {
    $path = storage_path('app/' . $path);
	// dd($path);
    /*if (!Storage::exists($path)) {
        abort(404);
    }*/

    $file = Storage::get($path);

    $type = Storage::mimeType($path);

    $response = new Response($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('path', '.*');
