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
Route::prefix('school')->name('school.')->group(function () {
	Route::get('edit', 'SchoolController@edit')->name('edit');
	Route::put('update', 'SchoolController@update')->name('update');

	// School: document
	Route::prefix('document')->name('document.')->group(function () {
		Route::get('filter/{token?}', 'DocumentController@filter')->name('filter');
		Route::delete('destroy', 'DocumentController@destroy')->name('destroy');
	});
	Route::resource('document', 'DocumentController', ['only' => [
		'store',
	]]);

	// School: photo
	Route::prefix('photo')->name('photo.')->group(function () {
		Route::get('filter/{token?}', 'SchoolPhotoController@filter')->name('filter');
		Route::delete('destroy', 'SchoolPhotoController@destroy')->name('destroy');
	});
	Route::resource('photo', 'SchoolPhotoController', ['only' => [
		'store',
	]]);
});
Route::resource('school', 'SchoolController', ['only' => [
	'index'
]]);

// Document
Route::resource('document', 'DocumentController', ['except' => [
	'destroy',
]]);

// Student
Route::prefix('student')->name('student.')->group(function () {
	Route::post('list', 'StudentController@list')->name('list');
	// Route::post('export', 'StudentController@export')->name('export');
	// Route::delete('destroy', 'StudentController@destroy')->name('destroy');
});
Route::resource('student', 'StudentController', ['except' => [
	'destroy',
]]);

// Activity
Route::prefix('activity')->name('activity.')->group(function () {
	// Route::post('list', 'ActivityController@list')->name('list');
	Route::delete('destroy', 'ActivityController@destroy')->name('destroy');
});
Route::resource('activity', 'ActivityController', ['except' => [
	'destroy',
]]);

// Subsidy
Route::prefix('subsidy')->name('subsidy.')->group(function () {
	Route::post('list', 'SubsidyController@list')->name('list');
	// Route::post('export', 'SubsidyController@export')->name('export');
	// Route::delete('destroy', 'SubsidyController@destroy')->name('destroy');
});
Route::resource('subsidy', 'SubsidyController', ['except' => [
	'destroy',
]]);

// Training
Route::prefix('training')->name('training.')->group(function () {
	Route::post('list', 'TrainingController@list')->name('list');
	// Route::post('export', 'TrainingController@export')->name('export');
	// Route::delete('destroy', 'TrainingController@destroy')->name('destroy');
});
Route::resource('training', 'TrainingController', ['except' => [
	'destroy',
]]);

// Payment
Route::prefix('payment')->name('payment.')->group(function () {
	Route::post('list', 'PaymentController@list')->name('list');
	Route::get('{payment}/fill', 'PaymentController@fill')->name('fill');
	Route::put('{payment}/confirm', 'PaymentController@confirm')->name('confirm');
	// Route::post('export', 'PaymentController@export')->name('export');
	// Route::delete('destroy', 'PaymentController@destroy')->name('destroy');
});
Route::resource('payment', 'PaymentController', ['except' => [
	'destroy',
]]);

// Account
Route::prefix('account')->name('account.')->group(function () {
	Route::put('update', 'AccountController@update')->name('update');
});
Route::resource('account', 'AccountController', ['parameters' => [
	'account' => 'user'
], 'only' => [
	'index',
]]);

/**
 * Admin
 */
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
	// Home
    Route::get('/', 'HomeController@index')->name('home');

	// Auth
	/**
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
	**/

    // School
    Route::prefix('school')->name('school.')->group(function () {
    	Route::post('list', 'SchoolController@list')->name('list');
    	Route::post('export', 'SchoolController@export')->name('export');
		Route::delete('destroy', 'SchoolController@destroy')->name('destroy');
		
		// School: document
		Route::prefix('document')->name('document.')->group(function () {
			Route::get('{school}/filter/{token?}', 'DocumentController@filter')->name('filter');
			Route::delete('destroy', 'DocumentController@destroy')->name('destroy');
		});
		Route::resource('{school}/document', 'DocumentController', ['only' => [
			'store',
		]]);

		// School: photo
		Route::prefix('photo')->name('photo.')->group(function () {
			Route::get('{school}/filter/{token?}', 'SchoolPhotoController@filter')->name('filter');
			Route::delete('destroy', 'SchoolPhotoController@destroy')->name('destroy');
		});
		Route::resource('{school}/photo', 'SchoolPhotoController', ['only' => [
			'store',
		]]);

    	// School: comment
    	Route::prefix('comment')->name('comment.')->group(function () {
	    	Route::post('{school}', 'SchoolCommentController@store')->name('store');
	    });
    	/**
    	Route::resource('comment', 'SchoolCommentController', ['only' => [
			'index',
		]]);
		**/
    });
    Route::resource('school', 'SchoolController', ['except' => [
		'destroy',
	]]);

	// Class
	Route::prefix('class')->name('class.')->group(function () {
    	Route::post('list', 'StudentClassController@list')->name('list');
    	Route::post('export', 'StudentClassController@export')->name('export');
		Route::delete('destroy', 'StudentClassController@destroy')->name('destroy');
		// Student
		Route::prefix('{studentClass}/student')->name('student.')->group(function () {
			Route::post('list', 'StudentController@list')->name('list');
			Route::post('export', 'StudentController@export')->name('export');
			Route::delete('destroy', 'StudentController@destroy')->name('destroy');
		});
		Route::resource('{studentClass}/student', 'StudentController', ['except' => [
			'destroy',
		]]);
    });
	Route::resource('class', 'StudentClassController', ['parameters' => [
	    'class' => 'studentClass'
	], 'except' => [
		'destroy',
	]]);

	// Activity
	Route::prefix('activity')->name('activity.')->group(function () {
    	// Route::post('list', 'ActivityController@list')->name('list');
    	Route::delete('destroy', 'ActivityController@destroy')->name('destroy');
    });
	Route::resource('activity', 'ActivityController', ['except' => [
		'destroy',
	]]);

	// Subsidy
	Route::prefix('subsidy')->name('subsidy.')->group(function () {
    	Route::post('list', 'SubsidyController@list')->name('list');
    	Route::post('export', 'SubsidyController@export')->name('export');
    	Route::post('cancel', 'SubsidyController@cancel')->name('cancel');
    	Route::post('reject', 'SubsidyController@reject')->name('reject');
    	Route::post('approve', 'SubsidyController@approve')->name('approve');
    	Route::delete('destroy', 'SubsidyController@destroy')->name('destroy');
    });
	Route::resource('subsidy', 'SubsidyController', ['except' => [
		'destroy',
	]]);

	// Training
	Route::prefix('training')->name('training.')->group(function () {
    	Route::post('list', 'TrainingController@list')->name('list');
    	Route::post('cancel', 'TrainingController@cancel')->name('cancel');
    	Route::post('process', 'TrainingController@process')->name('process');
    	Route::post('approve', 'TrainingController@approve')->name('approve');
    	Route::post('export', 'TrainingController@export')->name('export');
    	Route::delete('destroy', 'TrainingController@destroy')->name('destroy');
    });
	Route::resource('training', 'TrainingController', ['except' => [
		'destroy',
	]]);

	// Payment
	Route::prefix('payment')->name('payment.')->group(function () {
    	Route::post('list', 'PaymentController@list')->name('list');
    	Route::post('export', 'PaymentController@export')->name('export');
    	Route::delete('destroy', 'PaymentController@destroy')->name('destroy');
    });
	Route::resource('payment', 'PaymentController', ['except' => [
		'destroy',
	]]);

    // Account
    Route::prefix('account')->name('account.')->group(function () {
    	Route::post('list', 'AccountController@list')->name('list');
    	Route::get('me', 'AccountController@me')->name('me');
    	Route::delete('destroy', 'AccountController@destroy')->name('destroy');
    });
    Route::resource('account', 'AccountController', ['parameters' => [
	    'account' => 'user'
	], 'except' => [
		'destroy',
	]]);
});

// Custom
Route::prefix('get')->name('get.')->middleware(['auth:web,admin'])->group(function () {
	Route::post('regency/by/province', 'GetController@regencyByProvince')->name('regencyByProvince');
	Route::post('school/by/level', 'GetController@schoolByLevel')->name('schoolByLevel');
	Route::post('generation/by/school', 'GetController@generationBySchool')->name('generationBySchool');
	Route::post('generation/from/class', 'GetController@generationFromClass')->name('generationFromClass');
	Route::post('schoolYear/by/school', 'GetController@schoolYearBySchool')->name('schoolYearBySchool');
	Route::post('department/by/school', 'GetController@departmentBySchool')->name('departmentBySchool');
	Route::prefix('teacher')->name('teacher.')->group(function () {
		Route::post('by/school', 'GetController@teacherBySchool')->name('bySchool');
		Route::post('by', 'GetController@teacherBy')->name('by');
	});
	Route::post('pic/by/school', 'GetController@picBySchool')->name('picBySchool');
	Route::prefix('student')->name('student.')->group(function () {
		Route::post('by/school', 'GetController@studentBySchool')->name('bySchool');
		Route::post('by/generation', 'GetController@studentByGeneration')->name('byGeneration');
		Route::post('by/grade', 'GetController@studentByGrade')->name('byGrade');
		Route::post('by', 'GetController@studentBy')->name('by');
	});
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('download/{dir}/{file}', function ($dir, $file) {
	return response()->download(storage_path('app/public/' . decrypt($dir) . '/' . decrypt($file)));
})->name('download');

Route::get('check', function () {
	$school = App\School::find(497);
	$department = App\Department::find(7);
	return studentGeneration($school, $department);
});

Route::get('mailable', function () {
    $training = App\Training::find(5);

    return new App\Mail\TrainingWaited($training);
});