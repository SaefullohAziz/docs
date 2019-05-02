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

// Auth
Auth::routes(['register' => false]);

// Home
Route::get('/', 'HomeController@index')->name('home');

// School
Route::resource('school', 'SchoolController', ['only' => [
	'index', 'update'
]]);

// Account
Route::resource('account', 'AccountController', ['parameters' => [
	'account' => 'user'
], 'except' => [
	'destroy',
]]);

// Custom
Route::prefix('get')->name('get.')->group(function () {
    Route::post('regency/by/province', 'Admin\SchoolController@regencyByProvince')->name('regencyByProvince');
});

/**
 * Admin
 */
Route::prefix('admin')->name('admin.')->group(function () {
	// Home
    Route::get('/', 'Admin\HomeController@index')->name('home');

    // Auth
    Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Admin\Auth\LoginController@login');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('logout');

    // School
    Route::prefix('school')->name('school.')->group(function () {
    	Route::post('list', 'Admin\SchoolController@list')->name('list');
    	Route::post('export', 'Admin\SchoolController@export')->name('export');
    	Route::delete('destroy', 'Admin\SchoolController@destroy')->name('destroy');
    	// School: comment
    	Route::prefix('comment')->name('comment.')->group(function () {
	    	Route::post('{school}', 'Admin\SchoolCommentController@store')->name('store');
	    });
    	/**
    	Route::resource('comment', 'Admin\SchoolCommentController', ['only' => [
			'index',
		]]);
		**/
    });
    Route::resource('school', 'Admin\SchoolController', ['except' => [
		'destroy',
	]]);

	// Student
	Route::prefix('student')->name('student.')->group(function () {
    	Route::post('list', 'Admin\StudentController@list')->name('list');
    	Route::delete('destroy', 'Admin\StudentController@destroy')->name('destroy');
    });
	Route::resource('student', 'Admin\StudentController', ['except' => [
		'destroy',
	]]);

    // Account
    Route::prefix('account')->name('account.')->group(function () {
    	Route::post('list', 'Admin\AccountController@list')->name('list');
    	Route::delete('destroy', 'Admin\AccountController@destroy')->name('destroy');
    });
    Route::resource('account', 'Admin\AccountController', ['parameters' => [
	    'account' => 'user'
	], 'except' => [
		'destroy',
	]]);
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('download/{dir}/{file}', function ($dir, $file) {
	return response()->download(storage_path('app/public/' . decrypt($dir) . '/' . decrypt($file)));
})->name('download');

Route::get('check', function () {
	dd(App\User::all());
});