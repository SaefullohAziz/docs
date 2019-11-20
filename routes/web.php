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
Auth::routes(['register' => false, 'reset' => false]);

// School
Route::prefix('school')->name('school.')->group(function () {
	Route::get('register', 'SchoolController@create')->name('register');
	Route::post('/', 'SchoolController@store')->name('store');
});

// Default Guard (Web)
Route::middleware(['auth'])->group(function () {
	Route::middleware(['joined'])->group(function () {
		Route::middleware(['level:C|B|A'])->group(function () {
			// Class
			Route::prefix('class')->name('class.')->group(function () {
				Route::post('list', 'StudentClassController@list')->name('list');
				Route::post('close', 'StudentClassController@close')->name('close');
				// Route::post('export', 'StudentClassController@export')->name('export');
				// Route::delete('destroy', 'StudentClassController@destroy')->name('destroy');
				// Student
				Route::prefix('{studentClass}/student')->name('student.')->group(function () {
					Route::post('list', 'StudentController@list')->name('list');
					// Route::post('export', 'StudentController@export')->name('export');
					Route::post('import', 'StudentController@importExcel')->name('import');
					// Route::delete('destroy', 'StudentController@destroy')->name('destroy');
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
				Route::post('list', 'ActivityController@list')->name('list');
				// Route::delete('destroy', 'ActivityController@destroy')->name('destroy');
			});
			Route::resource('activity', 'ActivityController', ['except' => [
				'edit', 'update', 'destroy',
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
				Route::post('preCreate', 'TrainingController@preCreate')->name('preCreate');
				// Route::post('export', 'TrainingController@export')->name('export');
				// Route::delete('destroy', 'TrainingController@destroy')->name('destroy');
			});
			Route::resource('training', 'TrainingController', ['except' => [
				'destroy',
			]]);
		
			// Exam
			Route::prefix('exam')->name('exam.')->group(function () {
				// Exam: readiness
				Route::prefix('readiness')->name('readiness.')->group(function () {
					Route::post('list', 'ExamReadinessController@list')->name('list');
					// Route::post('export', 'ExamReadinessController@export')->name('export');
					// Route::delete('destroy', 'ExamReadinessController@destroy')->name('destroy');
				});
				Route::resource('readiness', 'ExamReadinessController', ['parameters' => [
					'readiness' => 'examReadiness'
				], 'except' => [
					'destroy',
				]]);
			});
		
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
		});
	
		Route::middleware(['level:Dalam proses'])->group(function () {
			// Attendance
			Route::prefix('attendance')->name('attendance.')->group(function () {
				Route::post('list', 'AttendanceController@list')->name('list');
				Route::delete('destroy', 'AttendanceController@destroy')->name('destroy');
			});
			Route::resource('attendance', 'AttendanceController', ['except' => [
				'destroy',
			]]);
		});
	
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
		
		// Teacher
		Route::prefix('teacher')->name('teacher.')->group(function () {
			Route::post('list', 'TeacherController@list')->name('list');
			Route::post('export', 'TeacherController@export')->name('export');
			Route::delete('destroy', 'TeacherController@destroy')->name('destroy');
		});
		Route::resource('teacher', 'TeacherController', ['except' => [
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
	});

	// School
	Route::prefix('school')->name('school.')->group(function () {
		Route::get('set', 'SchoolController@set')->name('set');
		Route::post('set', 'SchoolController@setStore')->name('set.store');
	});
});

/**
 * Admin Guard
 */
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
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
		Route::get('bin', 'SchoolController@bin')->name('bin');
		Route::post('binList', 'SchoolController@list')->name('binList');
		Route::post('restore', 'SchoolController@restore')->name('restore');
		Route::delete('destroyPermanently', 'SchoolController@destroyPermanently')->name('destroyPermanently');
		
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

	// Teacher
	Route::prefix('teacher')->name('teacher.')->group(function () {
		Route::post('list', 'TeacherController@list')->name('list');
    	Route::post('export', 'TeacherController@export')->name('export');
		Route::delete('destroy', 'TeacherController@destroy')->name('destroy');
	});
	Route::resource('teacher', 'TeacherController', ['except' => [
		'destroy',
	]]);

	// Class
	Route::prefix('class')->name('class.')->group(function () {
    	Route::post('list', 'StudentClassController@list')->name('list');
    	Route::post('export', 'StudentClassController@export')->name('export');
		Route::delete('destroy', 'StudentClassController@destroy')->name('destroy');
		Route::post('close', 'StudentClassController@close')->name('close');
		Route::post('open', 'StudentClassController@open')->name('open');
		// Student
		Route::prefix('{studentClass}/student')->name('student.')->group(function () {
			Route::post('list', 'StudentController@list')->name('list');
			Route::post('export', 'StudentController@export')->name('export');
			Route::post('import', 'StudentController@importExcel')->name('import');
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
    	Route::post('list', 'ActivityController@list')->name('list');
    	Route::post('date', 'ActivityController@date')->name('date');
    	Route::post('cancel', 'ActivityController@cancel')->name('cancel');
    	Route::post('reject', 'ActivityController@reject')->name('reject');
    	Route::post('approve', 'ActivityController@approve')->name('approve');
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
		Route::get('bin', 'SubsidyController@bin')->name('bin');
		Route::post('binList', 'SubsidyController@list')->name('binList');
		Route::post('restore', 'SubsidyController@restore')->name('restore');
		Route::delete('destroyPermanently', 'SubsidyController@destroyPermanently')->name('destroyPermanently');
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
		Route::get('bin', 'TrainingController@bin')->name('bin');
		Route::post('binList', 'TrainingController@list')->name('binList');
		Route::post('restore', 'TrainingController@restore')->name('restore');
		Route::delete('destroyPermanently', 'TrainingController@destroyPermanently')->name('destroyPermanently');
    });
	Route::resource('training', 'TrainingController', ['except' => [
		'destroy',
	]]);

	// Exam
	Route::prefix('exam')->name('exam.')->group(function () {
		// Exam: readiness
		Route::prefix('readiness')->name('readiness.')->group(function () {
			Route::post('list', 'ExamReadinessController@list')->name('list');
			Route::post('export', 'ExamReadinessController@export')->name('export');
			Route::delete('destroy', 'ExamReadinessController@destroy')->name('destroy');
			Route::get('bin', 'ExamReadinessController@bin')->name('bin');
			Route::post('binList', 'ExamReadinessController@list')->name('binList');
			Route::post('restore', 'ExamReadinessController@restore')->name('restore');
			Route::delete('destroyPermanently', 'ExamReadinessController@destroyPermanently')->name('destroyPermanently');
		});
		Route::resource('readiness', 'ExamReadinessController', ['parameters' => [
			'readiness' => 'examReadiness'
		], 'except' => [
			'destroy',
		]]);
	});
	
	// Attendance
	Route::prefix('attendance')->name('attendance.')->group(function () {
    	Route::post('list', 'AttendanceController@list')->name('list');
    	Route::post('process', 'AttendanceController@process')->name('process');
    	Route::post('approve', 'AttendanceController@approve')->name('approve');
    	Route::post('export', 'AttendanceController@export')->name('export');
    	Route::delete('destroy', 'AttendanceController@destroy')->name('destroy');
    	Route::get('bin', 'AttendanceController@bin')->name('bin');
    	Route::post('binList', 'AttendanceController@list')->name('binList');
    	Route::post('restore', 'AttendanceController@restore')->name('restore');
    	Route::delete('destroyPermanently', 'AttendanceController@destroyPermanently')->name('destroyPermanently');
    });
	Route::resource('attendance', 'AttendanceController', ['except' => [
		'destroy',
	]]);

	// Payment
	Route::prefix('payment')->name('payment.')->group(function () {
    	Route::post('list', 'PaymentController@list')->name('list');
    	Route::post('process', 'PaymentController@process')->name('process');
    	Route::post('approve', 'PaymentController@approve')->name('approve');
    	Route::post('send', 'PaymentController@send')->name('send');
    	Route::post('refund', 'PaymentController@refund')->name('refund');
    	Route::post('export', 'PaymentController@export')->name('export');
    	Route::delete('destroy', 'PaymentController@destroy')->name('destroy');
		Route::get('bin', 'PaymentController@bin')->name('bin');
		Route::post('binList', 'PaymentController@list')->name('binList');
		Route::post('restore', 'PaymentController@restore')->name('restore');
		Route::delete('destroyPermanently', 'PaymentController@destroyPermanently')->name('destroyPermanently');
    });
	Route::resource('payment', 'PaymentController', ['except' => [
		'destroy',
	]]);

	// Setting
    Route::prefix('setting')->name('setting.')->group(function () {
    	Route::get('/', 'SettingController@index')->name('index');
		Route::prefix('general')->name('general.')->group(function () {
			Route::get('/', 'SettingController@general')->name('index');
			Route::post('/', 'SettingController@generalStore')->name('store');
		});
		Route::prefix('role')->name('role.')->group(function () {
			Route::get('/', 'SettingController@role')->name('index');
			Route::post('/', 'SettingController@roleStore')->name('store');
		});
		Route::prefix('form')->name('form.')->group(function () {
			Route::get('/', 'SettingController@form')->name('index');
			Route::post('/', 'SettingController@formStore')->name('store');
		});
		Route::prefix('training')->name('training.')->group(function () {
			Route::get('/', 'SettingController@training')->name('index');
			Route::post('/', 'SettingController@trainingStore')->name('store');
		});
		Route::prefix('exam')->name('exam.')->group(function () {
			Route::prefix('readiness')->name('readiness.')->group(function () {
				Route::get('/', 'SettingController@examReadiness')->name('index');
				Route::post('/', 'SettingController@examReadinessStore')->name('store');
			});
		});
    });

    // Account
    Route::prefix('account')->name('account.')->group(function () {
    	Route::post('list', 'AccountController@list')->name('list');
    	Route::get('me', 'AccountController@me')->name('me');
    	Route::get('school/{user}', 'AccountController@showSchool')->name('school.show');
    	Route::get('school/{user}/edit', 'AccountController@editSchool')->name('school.edit');
    	Route::match(['put', 'patch'], 'school/{user}', 'AccountController@updateSchool')->name('school.update');
    	Route::delete('destroy', 'AccountController@destroy')->name('destroy');
    	Route::delete('reset', 'AccountController@reset')->name('reset');
    });
    Route::resource('account', 'AccountController', ['parameters' => [
	    'account' => 'user'
	], 'except' => [
		'destroy',
	]]);
});

// All Guard
Route::prefix('get')->name('get.')->middleware(['auth:web,admin'])->group(function () {
	Route::post('staff', 'GetController@staff')->name('staff');
	Route::post('schoolChart', 'GetController@schoolChart')->name('schoolChart');
	Route::post('studentChart', 'GetController@studentChart')->name('studentChart');
	Route::post('regency', 'GetController@regency')->name('regency');
	Route::post('schoolStatus', 'GetController@schoolStatus')->name('schoolStatus');
	Route::post('school', 'GetController@school')->name('school');
	Route::post('teacher', 'GetController@teacher')->name('teacher');
	Route::post('generation', 'GetController@generation')->name('generation');
	Route::post('generation/from/class', 'GetController@generationFromClass')->name('generationFromClass');
	Route::post('schoolYear', 'GetController@schoolYear')->name('schoolYear');
	Route::post('department', 'GetController@department')->name('department');
	Route::post('pic', 'GetController@pic')->name('pic');
	Route::post('student', 'GetController@student')->name('student');
	Route::post('subExam', 'GetController@subExam')->name('subExam');
	Route::post('trainingSettingResult', 'GetController@trainingSettingResult')->name('trainingSettingResult');
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('download/{dir}/{file}', function ($dir, $file) {
	$path = storage_path('app/public/' . decrypt($dir) . '/' . decrypt($file));
	if (file_exists(public_path(decrypt($dir) . '/' . decrypt($file)))) {
		$path = public_path(decrypt($dir) . '/' . decrypt($file));
	}
	return response()->download($path);
})->name('download');

Route::get('check', function (\Illuminate\Http\Request $request) {
	if (env('APP_ENV') == 'local') {
		$data = \Gate::abilities();
		dd($data);
	}
});

Route::get('mailable', function () {
	if (env('APP_ENV') == 'local') {
		$school = \App\School::first();
		return new App\Mail\SchoolCreated($school);
	}
});