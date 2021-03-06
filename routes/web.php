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

Auth::routes();

Route::redirect('/', '/manage');

Route::get('/manage', function () {
    return view('manage');
})->name('manage')->middleware('auth');

Route::post('/admin/publish', 'AdminController@publish')->name('admin.publish');
Route::resource('admin', 'AdminController');

Route::get('/task/lists', 'TaskController@lists')->name('task.lists');
Route::get('/task/download', 'TaskController@download');
Route::resource('task', 'TaskController', [
    'except' => ['create', 'show']
]);

Route::resource('genfile', 'GenFileController', [
    'only' => ['index', 'store']
]);
Route::get('genfile/translates', 'GenFileController@translates')->name('genfile.translates');
Route::post('genfile/translates_store', 'GenFileController@translatesStore')->name('genfile.translates.store');

Route::get('/csv_temp/{one?}', function () {
    App\Util\FileHandle::fileStorageShow();
});