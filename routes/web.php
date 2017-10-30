<?php

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

Route::redirect('/', '/file');

Auth::routes();

Route::post('/admin/publish', 'AdminController@publish')->name('admin.publish');

Route::resource('admin', 'AdminController');

Route::get('/file/lists', 'FileServerController@lists')->name('file.lists');

Route::get('/file/download', 'FileServerController@download');

Route::resource('file', 'FileServerController', [
    'except' => ['create', 'show']
]);

Route::get('/csv_temp/{one?}', function () {
    App\Util\FileHandle::fileStorageShow();
});