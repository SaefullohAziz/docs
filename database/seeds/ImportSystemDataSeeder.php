<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ImportSystemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Import staffs
        $staffs = DB::connection('mysql_2')->table('users')->join('schools', 'users.school_id', '=', 'schools.school_id')->where('schools.school_name', 'ACP')->select('users.*')->get();
        foreach ($staffs as $staff) {
            $newStaff = \App\Admin\User::firstOrCreate(
                ['username' => $staff->username],
                ['name' => $staff->name,
                'email' => $staff->email,
                'password' => Hash::make('!Indonesia45!')],
            );
        }
        if ( ! file_exists(storage_path('app/public/school/'))) {
            mkdir(storage_path('app/public/school/'));
        }

        // Import Schools
        $schools = DB::connection('mysql_2')
        ->table('schools')
        ->join('school_pics', 'schools.school_id', '=', 'school_pics.school_id')
        ->join('pics', 'school_pics.pic_id', '=', 'pics.pic_id')
        ->where('schools.school_name', '!=', 'ACP')
        ->select('schools.*', 'pics.pic_name', 'pics.pic_position', 'pics.pic_phone_number', 'pics.pic_email', 'pics.created_at as pic_created_at', 'school_pics.created_at as school_pic_created_at')
        ->get();
        $admin = \App\Admin\User::where('username', 'admin')->first();
        $faker = Faker::create('id_ID');
        foreach ($schools as $school) {
            $dispatcher = \App\School::getEventDispatcher();
            \App\School::unsetEventDispatcher();
            $newSchool = \App\School::create([
                'id' => Str::uuid(),
                'type' => $school->school_type,
                'name' => $school->school_name, 
                'address' => $school->school_address, 
                'province' => $school->province, 
                'regency' => $school->regency, 
                'police_number' => $school->police_number, 
                'since' => $school->since, 
                'school_phone_number' => $school->school_phone_number, 
                'school_email' => $school->school_email, 
                'school_web' => $school->school_web, 
                'total_student' => $school->total_student, 
                'department' => (empty($school->department)?$school->other_department:$school->department), 
                'iso_certificate' => $school->iso_certificate, 
                'mikrotik_academy' => $school->mikrotik_academy, 
                'headmaster_name' => $school->headmaster_name, 
                'headmaster_phone_number' => $school->headmaster_phone_number, 
                'headmaster_email' => $school->headmaster_email, 
                'reference' => $school->reference, 
                'dealer_name' => $school->dealer_name, 
                'dealer_phone_number' => $school->dealer_phone_number, 
                'dealer_email' => $school->dealer_email, 
                'proposal' => $school->proposal, 
                'code' => mt_rand(1000000, 9999999),
                'created_at' => $this->validDate($school->created_at),
                'updated_at' => $this->validDate($school->created_at)
            ]);
            \App\School::setEventDispatcher($dispatcher);
            if ( ! empty($school->documents)) {
                if (Storage::disk('acp')->exists('/schools/documents/' . $school->documents)) {
                    $folder = storage_path('app/public/school/document/' . strstr($school->documents, '/', true));
                    if ( ! file_exists(storage_path('app/public/school/document/'))) {
                        mkdir(storage_path('app/public/school/document/'));
                    }
                    if ( ! file_exists($folder)) {
                        mkdir($folder);
                    }
                    shell_exec('cp ' . Storage::disk('acp')->path('schools/documents/' . $school->documents) . ' ' . $folder);
                }
            }

            // Import School: PIC
            $newPic = \App\Pic::create([
                'name' => $school->pic_name,
                'position' => $school->pic_position,
                'phone_number' => $school->pic_phone_number,
                'email' => $school->pic_email,
                'created_at' => $this->validDate($school->pic_created_at),
                'updated_at' => $this->validDate($school->pic_created_at)
            ]);
            $newSchool->schoolPic()->create([
                'pic_id' => $newPic->id,
                'created_at' => $this->validDate($school->school_pic_created_at),
                'updated_at' => $this->validDate($school->school_pic_created_at),
            ]);

            // Import School: Statuses
            $schoolStatuses = DB::connection('mysql_2')
            ->table('school_status_updates')
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.status_id')
            ->where('school_status_updates.school_id', $school->school_id)
            ->select('school_status_updates.*', 'school_statuses.order_by')
            ->get();
            foreach ($schoolStatuses as $schoolStatus) {
                $newStatus = \App\SchoolStatus::where('order_by', $schoolStatus->order_by)->first();
                $newSchool->statusUpdates()->create([
                    'school_status_id' => $newStatus->id,
                    'participant' => $schoolStatus->participant, 
                    'total' => $schoolStatus->total, 
                    'date' => $schoolStatus->date, 
                    'location' => $schoolStatus->location, 
                    'detail' => $schoolStatus->detail, 
                    'email_status' => $schoolStatus->email_status,
                    'created_by' => 'staff',
                    'staff_id' => $admin->id,
                    'created_at' => $this->validDate($schoolStatus->created_at),
                    'updated_at' => $this->validDate($schoolStatus->created_at),
                ]);
            }

            // Import School: User
            $user = DB::connection('mysql_2')
            ->table('users')
            ->where('school_id', $school->school_id)
            ->select('*')
            ->first();
            $schoolUserEmail = 'user' . $faker->unique()->numberBetween(100000, 999999) . '@mail.com';
            $dispatcher = \App\User::getEventDispatcher();
            \App\User::unsetEventDispatcher();
            if ($user) {
                $existUser = \App\User::where('email', $user->email)->first();
                $newUser = $newSchool->user()->create([
                    'id' => Str::uuid(),
                    'username' => $user->username, 
                    'name' => $user->name, 
                    'email' => ($existUser?$schoolUserEmail:$user->email), 
                    'password' => Hash::make('!Indo!Joss!'),
                ]);
            }
            \App\User::setEventDispatcher($dispatcher);
            if ($newSchool->user()->count() == 0) {
                $existUser = \App\User::where('email', $newSchool->pic[0]->email)->first();
                $newUser = $newSchool->user()->create([
                    'name' => 'User', 
                    'email' => ($existUser?$schoolUserEmail:$newSchool->pic[0]->email), 
                    'password' => Hash::make('!Indo45!Joss!'),
                ]);
            }

            // Import School: Documents
            $documents = DB::connection('mysql_2')
            ->table('school_documents')
            ->where('school_id', $school->school_id)
            ->select('*')
            ->get();
            foreach ($documents as $document) {
                $dispatcher = \App\Document::getEventDispatcher();
                \App\Document::unsetEventDispatcher();
                $newDocument = $newSchool->documents()->create([
                    'id' => Str::uuid(),
                    'implementation' => $document->implementation, 
                    'category' => $document->document_category, 
                    'filename' => (empty($document->document_filename)?'-':$document->document_filename), 
                    'created_at' => $this->validDate($document->created_at),
                    'updated_at' => $this->validDate($document->created_at),
                ]);
                \App\Document::setEventDispatcher($dispatcher);

                // Copy related-file
                if ( ! empty($document->document_filename)) {
                    if (Storage::disk('acp')->exists('/schools/documents/updates/' . $document->document_filename)) {
                        $folder = storage_path('app/public/school/document/update/' . strstr($document->document_filename, '/', true));
                        if ( ! file_exists(storage_path('app/public/school/document/'))) {
                            mkdir(storage_path('app/public/school/document/'));
                        }
                        if ( ! file_exists(storage_path('app/public/school/document/update/'))) {
                            mkdir(storage_path('app/public/school/document/update/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/documents/updates/' . $document->document_filename) . ' ' . $folder);
                    }
                }

                // Import Document:: status
                $documentStatuses = DB::connection('mysql_2')
                ->table('school_document_statuses')
                ->join('statuses', 'school_document_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_document_statuses.log_id', '=', 'activity_log.id')
                ->where('school_document_statuses.school_document_id', $document->school_document_id)
                ->select('school_document_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($documentStatuses as $documentStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id
                    ];
                    if ($documentStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $documentStatus->log,
                        'created_at' => $this->validDate($documentStatus->created_at),
                        'updated_at' => $this->validDate($documentStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $documentStatus->status)->first();
                    $newDocument->documentStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($documentStatus->created_at),
                        'updated_at' => $this->validDate($documentStatus->created_at),
                    ]);
                }
            }

            // Import School: Galleries
            $photos = DB::connection('mysql_2')
            ->table('school_photos')
            ->where('school_id', $school->school_id)
            ->select('*')
            ->get();
            foreach ($photos as $photo) {
                $newSchool->photos()->create([
                    'category' => 'Dokumentasi', 
                    'name' => $photo->photo_name, 
                    'created_at' => $this->validDate($photo->created_at),
                    'updated_at' => $this->validDate($photo->created_at),
                ]);

                if ( ! empty($photo->photo_name)) {
                    if (Storage::disk('acp')->exists('/schools/photos/' . $photo->photo_name)) {
                        $folder = storage_path('app/public/school/photo/' . strstr($photo->photo_name, '/', true));
                        if ( ! file_exists(storage_path('app/public/school/photo/'))) {
                            mkdir(storage_path('app/public/school/photo/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/photos/' . $photo->photo_name) . ' ' . $folder);
                    }
                }
            }

            // Import School: Comments
            $comments = DB::connection('mysql_2')
            ->table('school_comments')
            ->join('users', 'school_comments.user_id', '=', 'users.user_id')
            ->where('school_comments.school_id', $school->school_id)
            ->select('school_comments.*', 'users.username')
            ->get();
            foreach ($comments as $comment) {
                $commentStaff = \App\Admin\User::where('username', $comment->username)->first();
                $newSchool->comments()->create([
                    'staff_id' => ($commentStaff?$commentStaff->id:$admin->id), 
                    'message' => $comment->message,
                    'created_at' => $this->validDate($comment->created_at),
                    'updated_at' => $this->validDate($comment->created_at), 
                ]);
            }

            // Import School: Implementations
            $studentDepartments = DB::connection('mysql_2')
            ->table('students')
            ->where('school_id', $school->school_id)
            ->selectRaw('DISTINCT("department") as department')
            ->get();
            foreach ($studentDepartments as $studentDepartment) {
                $department = \App\Department::where('name', $studentDepartment->department)
                ->orWhere('abbreviation', $studentDepartment->department)
                ->first();
                if ( ! $department) {
                    $department = \App\Department::firstOrCreate([
                        'name' => $studentDepartment->department,
                        'abbreviation' => $studentDepartment->department
                    ]);
                }
                $newSchool->implementations()->firstOrCreate([
                    'department_id' => $department->id
                ]);
            }

            // Import School: Students
            $students = DB::connection('mysql_2')
            ->table('students')
            ->where('students.school_id', $school->school_id)
            ->where(function ($query) {
                $query->whereNotNull('name')
                ->orWhere('name', '!=', '');
            })
            ->where(function ($query) {
                $query->whereNotNull('generation')
                ->orWhere('generation', '!=', '');
            })
            ->where(function ($query) {
                $query->whereNotNull('school_year')
                ->orWhere('school_year', '!=', '');
            })
            ->where(function ($query) {
                $query->whereNotNull('department')
                ->orWhere('department', '!=', '');
            })
            ->orderBy('generation', 'asc')
            ->orderBy('name', 'asc')
            ->select('*')
            ->get();
            foreach ($students as $student) {
                $department = \App\Department::where('name', $student->department)
                ->orWhere('abbreviation', $student->department)
                ->first();
                if ( ! $department) {
                    $department = \App\Department::firstOrCreate([
                        'name' => $student->department,
                        'abbreviation' => $student->department
                    ]);
                }
                $studentGrade = (date('y')-substr($student->school_year, -2))+1;
                if ($studentGrade > 4) {
                    $studentGrade = 'Alumni';
                }
                $newClass = $newSchool->studentClasses()->firstOrCreate([
                    'department_id' => $department->id,
                    'generation' => $student->generation,
                    'school_year' => $student->school_year,
                    'grade' => $studentGrade
                ]);
                $studentData = [
                    'name' => $student->name,
                    'nickname' => (empty($student->nickname)?strstr($student->name, ' ', true):$student->nickname),
                    'province' => (empty($student->province)?$newSchool->province:$student->province),
                    'nisn' => (empty($student->nisn)?'nisn' . $faker->unique()->numberBetween(100000, 999999):$student->nisn),
                    'email' => (empty($student->email)?'student' . $faker->unique()->numberBetween(100000, 999999) . '@mail.com':$student->email),
                    'gender' => (empty($student->gender)?'-':$student->gender),
                    'father_name' => $student->father_name,
                    'father_education' => $student->father_education, 
                    'father_earning' => $student->father_earning, 
                    'father_earning_nominal' => (is_numeric($student->father_earning_nominal)?$student->father_earning_nominal:null),
                    'mother_name' => (empty($student->mother_name)?'-':$student->mother_name),
                    'mother_education' => $student->mother_education, 
                    'mother_earning' => $student->mother_earning, 
                    'mother_earning_nominal' => (is_numeric($student->mother_earning_nominal)?$student->mother_earning_nominal:null), 
                    'trustee_name' => $student->trustee_name, 
                    'trustee_education' => $student->trustee_education, 
                    'economy_status' => $student->economy_status, 
                    'religion' => (empty($student->religion)?'-':$student->religion),
                    'blood_type' => (empty($student->blood_type)?'-':$student->blood_type),
                    'special_need' => $student->special_need, 
                    'mileage' => $student->mileage, 
                    'distance' => (is_int($student->distance)?$student->distance:null), 
                    'diploma_number' => $student->diploma_number, 
                    'height' => (is_int($student->height)?$student->height:0),
                    'weight' => (is_int($student->weight)?$student->weight:0),
                    'child_order' => $student->child_order, 
                    'sibling_number' => $student->sibling_number, 
                    'stepbrother_number' => $student->stepbrother_number, 
                    'step_sibling_number' => $student->step_sibling_number, 
                    'dateofbirth' => $this->validDate($student->dateofbirth),
                    'address' => (empty($student->address)?'-':$student->address),
                    'father_address' => $student->father_address, 
                    'trustee_address' => $student->trustee_address, 
                    'phone_number' => (empty($student->phone_number)?'-':$student->phone_number),
                    'photo' => (empty($student->photo)?'default.png':($student->photo=='default-photo.png'?'default.png':$student->photo)), 
                    'computer_basic_score' => (is_int($student->computer_basic_score)?$student->computer_basic_score:null), 
                    'intelligence_score' => (is_int($student->intelligence_score)?$student->intelligence_score:null), 
                    'reasoning_score' => (is_int($student->reasoning_score)?$student->reasoning_score:null), 
                    'analogy_score' => (is_int($student->analogy_score)?$student->analogy_score:null), 
                    'numerical_score' => (is_int($student->numerical_score)?$student->numerical_score:null), 
                    'created_at' => $this->validDate($student->created_at),
                    'updated_at' => $this->validDate($student->created_at),
                ];
                if ( ! empty($student->username)) {
                    $dispatcher = \App\Student::getEventDispatcher();
                    \App\Student::unsetEventDispatcher();
                    $studentData = array_merge($studentData, ['id' => Str::uuid(), 'username' => $student->username]);
                }
                $newClass->students()->create($studentData);
                if ( ! empty($student->username)) {
                    \App\Student::setEventDispatcher($dispatcher);
                }
                // Copy Student: Photo
                if ( ! empty($student->photo) && $student->photo != 'default-photo.png') {
                    if (Storage::disk('acp')->exists('/schools/students/photos/' . $student->photo)) {
                        $folder = storage_path('app/public/student/' . strstr($student->photo, '/', true));
                        if ( ! file_exists(storage_path('app/public/student/'))) {
                            mkdir(storage_path('app/public/student/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/students/photos/' . $student->photo) . ' ' . $folder);
                    }
                }
            }

            // Import School: Activities
            $activities = DB::connection('mysql_2')
            ->table('school_activities')
            ->join('activity_pics', 'school_activities.school_activity_id', '=', 'activity_pics.school_activity_id')
            ->join('pics', 'activity_pics.pic_id', '=', 'pics.pic_id')
            ->where('school_activities.school_id', $school->school_id)
            ->select('school_activities.*', 'activity_pics.created_at as pic_created_at', 'pics.pic_name', 'pics.pic_position', 'pics.pic_phone_number', 'pics.pic_email')
            ->get();
            foreach ($activities as $activity) {
                $dispatcher = \App\Activity::getEventDispatcher();
                \App\Activity::unsetEventDispatcher();
                $newActivity = $newSchool->activities()->create([
                    'id' => Str::uuid(), 
                    'type' => $activity->activity_type, 
                    'date' => $activity->date, 
                    'until_date' => $activity->until_date, 
                    'time' => $activity->time, 
                    'destination' => $activity->destination, 
                    'participant' => $activity->participant_data, 
                    'amount_of_teacher' => $activity->amount_of_teacher, 
                    'amount_of_acp_student' => $activity->amount_of_acp_student, 
                    'amount_of_reguler_student' => $activity->amount_of_reguler_student, 
                    'amount_of_student' => $activity->amount_of_student, 
                    'implementer' => $activity->implementer, 
                    'activity' => $activity->activity, 
                    'activity_time' => $activity->activity_time, 
                    'period' => $activity->period, 
                    'submission_letter' => $activity->submission_letter, 
                    'detail' => $activity->detail, 
                    'created_at' => $this->validDate($activity->created_at),
                    'updated_at' => $this->validDate($activity->created_at),
                ]);
                \App\Activity::setEventDispatcher($dispatcher);
                // Import Activity: PIC
                $newPic = \App\Pic::firstOrCreate(
                    ['email' => $activity->pic_email],
                    ['name' => $activity->pic_name,
                    'position' => $activity->pic_position,
                    'phone_number' => $activity->pic_phone_number,
                    'created_at' => $this->validDate($activity->pic_created_at),
                    'updated_at' => $this->validDate($activity->pic_created_at)]
                );
                $newActivity->activityPic()->create([
                    'pic_id' => $newPic->id,
                    'created_at' => $this->validDate($activity->pic_created_at),
                    'updated_at' => $this->validDate($activity->pic_created_at),
                ]);
                // Import Activity: Statuses
                $activityStatuses = DB::connection('mysql_2')
                ->table('activity_statuses')
                ->join('statuses', 'activity_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'activity_statuses.log_id', '=', 'activity_log.id')
                ->where('activity_statuses.school_activity_id', $activity->school_activity_id)
                ->select('activity_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($activityStatuses as $activityStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id
                    ];
                    if ($activityStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $activityStatus->log,
                        'created_at' => $this->validDate($activityStatus->created_at),
                        'updated_at' => $this->validDate($activityStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $activityStatus->status)->first();
                    $newActivity->activityStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($activityStatus->created_at),
                        'updated_at' => $this->validDate($activityStatus->created_at),
                    ]);
                }
            }
            // Import School: Industry Visit
            $industryVisits = DB::connection('mysql_2')
            ->table('school_industry_visits')
            ->join('school_industry_visit_pics', 'school_industry_visits.school_industry_visit_id', '=', 'school_industry_visit_pics.school_industry_visit_id')
            ->join('pics', 'school_industry_visit_pics.pic_id', '=', 'pics.pic_id')
            ->where('school_industry_visits.school_id', $school->school_id)
            ->select('school_industry_visits.*', 'pics.pic_name', 'pics.pic_position', 'pics.pic_phone_number', 'pics.pic_email', 'school_industry_visit_pics.created_at as pic_created_at')
            ->get();
            foreach ($industryVisits as $industryVisit) {
                $dispatcher = \App\Activity::getEventDispatcher();
                \App\Activity::unsetEventDispatcher();
                $newIndustryVisit = $newSchool->activities()->create([
                    'id' => Str::uuid(),
                    'type' => 'Kunjungan Industri',
                    'date' => $industryVisit->date, 
                    'time' => $industryVisit->time, 
                    'destination' => $industryVisit->destination,  
                    'participant' => $industryVisit->participant_data, 
                    'amount_of_teacher' => $industryVisit->amount_of_teacher, 
                    'amount_of_acp_student' => $industryVisit->amount_of_acp_student, 
                    'amount_of_reguler_student' => $industryVisit->amount_of_reguler_student, 
                    'amount_of_student' => $industryVisit->amount_of_student,
                    'submission_letter' => $industryVisit->submission_letter, 
                    'detail' => $industryVisit->detail,
                    'created_at' => $this->validDate($industryVisit->created_at),
                    'updated_at' => $this->validDate($industryVisit->created_at),
                ]);
                \App\Activity::setEventDispatcher($dispatcher);
                // Copy Industry Visit: Participant
                if ( ! empty($industryVisit->participant_data)) {
                    if (Storage::disk('acp')->exists('/schools/industry_visits/' . $industryVisit->participant_data)) {
                        $folder = storage_path('app/public/activity/participant/' . strstr($industryVisit->participant_data, '/', true));
                        if ( ! file_exists(storage_path('app/public/activity/'))) {
                            mkdir(storage_path('app/public/activity/'));
                        }
                        if ( ! file_exists(storage_path('app/public/activity/participant/'))) {
                            mkdir(storage_path('app/public/activity/participant/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/industry_visits/' . $industryVisit->participant_data) . ' ' . $folder);
                    }
                }
                // Copy Industry Visit: Submission Letter
                if ( ! empty($industryVisit->submission_letter)) {
                    if (Storage::disk('acp')->exists('/schools/industry_visits/' . $industryVisit->submission_letter)) {
                        $folder = storage_path('app/public/activity/submission-letter/' . strstr($industryVisit->submission_letter, '/', true));
                        if ( ! file_exists(storage_path('app/public/activity/'))) {
                            mkdir(storage_path('app/public/activity/'));
                        }
                        if ( ! file_exists(storage_path('app/public/activity/submission-letter/'))) {
                            mkdir(storage_path('app/public/activity/submission-letter/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/industry_visits/' . $industryVisit->submission_letter) . ' ' . $folder);
                    }
                }
                // Import Industry Visit: PIC
                $newPic = \App\Pic::firstOrCreate(
                    ['email' => $industryVisit->pic_email],
                    ['name' => $industryVisit->pic_name,
                    'position' => $industryVisit->pic_position,
                    'phone_number' => $industryVisit->pic_phone_number,
                    'created_at' => $this->validDate($industryVisit->pic_created_at),
                    'updated_at' => $this->validDate($industryVisit->pic_created_at)]
                );
                $newIndustryVisit->activityPic()->create([
                    'pic_id' => $newPic->id,
                    'created_at' => $this->validDate($industryVisit->pic_created_at),
                    'updated_at' => $this->validDate($industryVisit->pic_created_at),
                ]);
                // Import Industry Visit: Statuses
                $industryVisitStatuses = DB::connection('mysql_2')
                ->table('school_industry_visit_statuses')
                ->join('statuses', 'school_industry_visit_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_industry_visit_statuses.log_id', '=', 'activity_log.id')
                ->where('school_industry_visit_statuses.school_industry_visit_id', $industryVisit->school_industry_visit_id)
                ->select('school_industry_visit_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($industryVisitStatuses as $industryVisitStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id
                    ];
                    if ($industryVisitStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $industryVisitStatus->log,
                        'created_at' => $this->validDate($industryVisitStatus->created_at),
                        'updated_at' => $this->validDate($industryVisitStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $industryVisitStatus->status)->first();
                    $newIndustryVisit->activityStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($industryVisitStatus->created_at),
                        'updated_at' => $this->validDate($industryVisitStatus->created_at),
                    ]);
                }
            }

            // Import School: Subsidies
            $subsidies = DB::connection('mysql_2')
            ->table('school_subsidies')
            ->join('school_subsidy_pics', 'school_subsidies.school_subsidy_id', '=', 'school_subsidy_pics.school_subsidy_id')
            ->join('pics', 'school_subsidy_pics.pic_id', '=', 'pics.pic_id')
            ->where('school_subsidies.school_id', $school->school_id)
            ->select('school_subsidies.*', 'school_subsidy_pics.created_at as pic_created_at', 'pics.pic_name', 'pics.pic_position', 'pics.pic_phone_number', 'pics.pic_email')
            ->get();
            foreach ($subsidies as $subsidy) {
                $dispatcher = \App\Subsidy::getEventDispatcher();
                \App\Subsidy::unsetEventDispatcher();
                $newSubsidy = $newSchool->subsidies()->create([
                    'id' => Str::uuid(),
                    'type' => $subsidy->subsidy_type, 
                    'submission_letter' => (empty($subsidy->submission_letter)?'-':$subsidy->submission_letter), 
                    'report' => $subsidy->report, 
                    'student_year' => $subsidy->student_year, 
                    'created_at' => $this->validDate($subsidy->created_at),
                    'updated_at' => $this->validDate($subsidy->created_at),
                ]);
                \App\Subsidy::setEventDispatcher($dispatcher);
                // Copy Subsidy: Submission Letter
                if ( ! empty($subsidy->submission_letter)) {
                    if (Storage::disk('acp')->exists('/schools/subsidies/' . $subsidy->submission_letter)) {
                        $folder = storage_path('app/public/subsidy/submission-letter/' . strstr($subsidy->submission_letter, '/', true));
                        if ( ! file_exists(storage_path('app/public/subsidy/'))) {
                            mkdir(storage_path('app/public/subsidy/'));
                        }
                        if ( ! file_exists(storage_path('app/public/subsidy/submission-letter/'))) {
                            mkdir(storage_path('app/public/subsidy/submission-letter/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/subsidies/' . $subsidy->submission_letter) . ' ' . $folder);
                    } elseif (Storage::disk('acp')->exists('/schools/subsidies/' . date('Y-m-d', strtotime($subsidy->created_at)) . '/' . $subsidy->submission_letter)) {
                        $folder = storage_path('app/public/subsidy/submission-letter/' . date('Y-m-d', strtotime($subsidy->created_at)));
                        if ( ! file_exists(storage_path('app/public/subsidy/'))) {
                            mkdir(storage_path('app/public/subsidy/'));
                        }
                        if ( ! file_exists(storage_path('app/public/subsidy/submission-letter/'))) {
                            mkdir(storage_path('app/public/subsidy/submission-letter/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/subsidies/' . date('Y-m-d', strtotime($subsidy->created_at)) . '/' . $subsidy->submission_letter) . ' ' . $folder);
                    }
                }
                // Copy Subsidy: Report
                if ( ! empty($subsidy->report)) {
                    if (Storage::disk('acp')->exists('/schools/subsidies/' . $subsidy->report)) {
                        $folder = storage_path('app/public/subsidy/report/' . strstr($subsidy->report, '/', true));
                        if ( ! file_exists(storage_path('app/public/subsidy/'))) {
                            mkdir(storage_path('app/public/subsidy/'));
                        }
                        if ( ! file_exists(storage_path('app/public/subsidy/report/'))) {
                            mkdir(storage_path('app/public/subsidy/report/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/subsidies/' . $subsidy->report) . ' ' . $folder);
                    } elseif (Storage::disk('acp')->exists('/schools/subsidies/' . date('Y-m-d', strtotime($subsidy->created_at)) . '/' . $subsidy->report)) {
                        $folder = storage_path('app/public/subsidy/report/' . date('Y-m-d', strtotime($subsidy->created_at)));
                        if ( ! file_exists(storage_path('app/public/subsidy/'))) {
                            mkdir(storage_path('app/public/subsidy/'));
                        }
                        if ( ! file_exists(storage_path('app/public/subsidy/report/'))) {
                            mkdir(storage_path('app/public/subsidy/report/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/subsidies/' . date('Y-m-d', strtotime($subsidy->created_at)) . '/' . $subsidy->report) . ' ' . $folder);
                    }
                }
                // Import Subsidy: SSP Student
                $subsidyStudents = DB::connection('mysql_2')
                ->table('school_ssp_relations')
                ->join('students', 'school_ssp_relations.student_id', '=', 'students.student_id')
                ->where('school_ssp_relations.school_subsidy_id', $subsidy->school_subsidy_id)
                ->select('school_ssp_relations.*', 'students.name', 'students.generation', 'students.department')
                ->get();
                foreach ($subsidyStudents as $subsidyStudent) {
                    $sspStudent = DB::table('students')
                    ->join('student_classes', 'students.class_id', '=', 'student_classes.id')
                    ->join('departments', 'student_classes.department_id', '=', 'departments.id')
                    ->where('student_classes.school_id', $newSchool->id)
                    ->where('students.name', $subsidyStudent->name)
                    ->where('student_classes.generation', $subsidyStudent->generation)
                    ->where(function ($query) use ($subsidyStudent) {
                        $query->where('departments.name', $subsidyStudent->department)
                        ->orWhere('departments.abbreviation', $subsidyStudent->department);
                    })->select('students.id')->first();
                    if ($sspStudent) {
                        $newSubsidy->students()->attach($sspStudent->id, [
                            'created_at' => $this->validDate($subsidyStudent->created_at),
                            'updated_at' => $this->validDate($subsidyStudent->created_at),
                        ]);
                    }
                }
                // Import Subsidy: PIC
                $newPic = \App\Pic::firstOrCreate(
                    ['email' => $subsidy->pic_email],
                    ['name' => $subsidy->pic_name,
                    'position' => $subsidy->pic_position,
                    'phone_number' => $subsidy->pic_phone_number,
                    'created_at' => $this->validDate($subsidy->pic_created_at),
                    'updated_at' => $this->validDate($subsidy->pic_created_at)]
                );
                $newSubsidy->subsidyPic()->create([
                    'pic_id' => $newPic->id,
                    'created_at' => $this->validDate($subsidy->pic_created_at),
                    'updated_at' => $this->validDate($subsidy->pic_created_at),
                ]);
                // Import Subsidy: Statuses
                $subsidyStatuses = DB::connection('mysql_2')
                ->table('school_subsidy_statuses')
                ->join('statuses', 'school_subsidy_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_subsidy_statuses.log_id', '=', 'activity_log.id')
                ->where('school_subsidy_statuses.school_subsidy_id', $subsidy->school_subsidy_id)
                ->select('school_subsidy_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($subsidyStatuses as $subsidyStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id,
                    ];
                    if ($subsidyStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $subsidyStatus->log,
                        'created_at' => $this->validDate($subsidyStatus->created_at),
                        'updated_at' => $this->validDate($subsidyStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $subsidyStatus->status)->first();
                    $newSubsidy->subsidyStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($subsidyStatus->created_at),
                        'updated_at' => $this->validDate($subsidyStatus->created_at),
                    ]);
                }
            }

            // Import School: Trainings
            $trainings = DB::connection('mysql_2')
            ->table('school_trainings')
            ->join('school_training_pics', 'school_trainings.school_training_id', '=', 'school_training_pics.school_training_id')
            ->join('pics', 'school_training_pics.pic_id', '=', 'pics.pic_id')
            ->where('school_trainings.school_id', $school->school_id)
            ->select('school_trainings.*', 'school_training_pics.created_at as pic_created_at', 'pics.pic_name', 'pics.pic_position', 'pics.pic_phone_number', 'pics.pic_email')
            ->get();
            foreach ($trainings as $training) {
                $dispatcher = \App\Training::getEventDispatcher();
                \App\Training::unsetEventDispatcher();
                $newTraining = $newSchool->trainings()->create([
                    'id' => Str::uuid(), 
                    'type' => $training->training_type, 
                    'has_asset' => (empty($training->has_asset)?null:($training->has_asset=='1'?'2':($training->has_asset=='0'?'1':$training->has_asset))), 
                    'date' => ($training->date=='0000-00-00'?null:$training->date), 
                    'until_date' => ($training->until_date=='0000-00-00'?null:$training->until_date), 
                    'implementation' => $training->implementation, 
                    'approval_code' => $training->approval_code, 
                    'selection_result' => $training->selection_result, 
                    'room_type' => $training->room_type, 
                    'room_size' => $training->room_size, 
                    'booking_code' => (empty($training->booking_code)?Str::random(12):$training->booking_code), 
                    'batch' => $training->batch, 
                    'approval_letter_of_commitment_fee' => (empty($training->approval_letter_of_commitment_fee)?'-': $training->approval_letter_of_commitment_fee), 
                    'detail' => $training->detail, 
                    'created_at' => $this->validDate($training->created_at),
                    'updated_at' => $this->validDate($training->created_at),
                ]);
                \App\Training::setEventDispatcher($dispatcher);
                // Copy Training: Approval Letter
                if ( ! empty($training->approval_letter_of_commitment_fee)) {
                    if (Storage::disk('acp')->exists('/schools/trainings/' . $training->approval_letter_of_commitment_fee)) {
                        $folder = storage_path('app/public/training/commitment-letter/' . strstr($training->approval_letter_of_commitment_fee, '/', true));
                        if ( ! file_exists(storage_path('app/public/training/'))) {
                            mkdir(storage_path('app/public/training/'));
                        }
                        if ( ! file_exists(storage_path('app/public/training/commitment-letter/'))) {
                            mkdir(storage_path('app/public/training/commitment-letter/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/trainings/' . $training->approval_letter_of_commitment_fee) . ' ' . $folder);
                    } elseif (Storage::disk('acp')->exists('/schools/trainings/' . date('Y-m-d', strtotime($training->created_at)) . '/' . $training->approval_letter_of_commitment_fee)) {
                        $folder = storage_path('app/public/training/commitment-letter/' . date('Y-m-d', strtotime($training->created_at)));
                        if ( ! file_exists(storage_path('app/public/training/'))) {
                            mkdir(storage_path('app/public/training/'));
                        }
                        if ( ! file_exists(storage_path('app/public/training/commitment-letter/'))) {
                            mkdir(storage_path('app/public/training/commitment-letter/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/trainings/' . date('Y-m-d', strtotime($training->created_at)) . '/' . $training->approval_letter_of_commitment_fee) . ' ' . $folder);
                    }
                }
                // Copy Training: Selection Result
                if ( ! empty($training->selection_result)) {
                    if (Storage::disk('acp')->exists('/schools/trainings/' . $training->selection_result)) {
                        $folder = storage_path('app/public/training/selection-result/' . strstr($training->selection_result, '/', true));
                        if ( ! file_exists(storage_path('app/public/training/'))) {
                            mkdir(storage_path('app/public/training/'));
                        }
                        if ( ! file_exists(storage_path('app/public/training/selection-result/'))) {
                            mkdir(storage_path('app/public/training/selection-result/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/trainings/' . $training->selection_result) . ' ' . $folder);
                    } elseif (Storage::disk('acp')->exists('/schools/trainings/' . date('Y-m-d', strtotime($training->created_at)) . '/' . $training->selection_result)) {
                        $folder = storage_path('app/public/training/selection-result/' . date('Y-m-d', strtotime($training->created_at)));
                        if ( ! file_exists(storage_path('app/public/training/'))) {
                            mkdir(storage_path('app/public/training/'));
                        }
                        if ( ! file_exists(storage_path('app/public/training/selection-result/'))) {
                            mkdir(storage_path('app/public/training/selection-result/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/trainings/' . date('Y-m-d', strtotime($training->created_at)) . '/' . $training->selection_result) . ' ' . $folder);
                    }
                }
                // Import Training: Participant
                $trainingParticipants = DB::connection('mysql_2')
                ->table('school_training_participants')
                ->where('school_training_id', $training->school_training_id)
                ->whereNotNull('name')
                ->select('*')
                ->get();
                foreach ($trainingParticipants as $trainingParticipant) {
                    $existTeacher = \App\Teacher::where('email', $trainingParticipant->email)->first();
                    $teacherEmail = 'teacher' . $faker->unique()->numberBetween(100000, 999999) . '@mail.com';
                    $newTeacher = $newSchool->teachers()->firstOrCreate(
                        ['email' => (empty($trainingParticipant->email)?$teacherEmail:($existTeacher?$teacherEmail:$trainingParticipant->email))],
                        ['name' => $trainingParticipant->name,
                        'gender' => (empty($trainingParticipant->gender)?'-':$trainingParticipant->gender),
                        'phone_number' => (empty($trainingParticipant->phone_number)?'-':$trainingParticipant->phone_number), 
                        'position' => (empty($trainingParticipant->position)?'-':$trainingParticipant->position),
                        'created_at' => $this->validDate($trainingParticipant->created_at),
                        'updated_at' => $this->validDate($trainingParticipant->created_at)]
                    );
                    $newTraining->participants()->attach($newTeacher->id, [
                        'status' => $trainingParticipant->status,
                        'presence_code' => $trainingParticipant->presence_code,
                        'presenced_at' => ($trainingParticipant->presenced_at=='0000-00-00 00:00:00'?null:$trainingParticipant->presenced_at),
                        'created_at' => $this->validDate($trainingParticipant->created_at),
                        'updated_at' => $this->validDate($trainingParticipant->created_at)
                    ]);
                }
                // Import Training: PIC
                $newPic = \App\Pic::firstOrCreate(
                    ['email' => $training->pic_email],
                    ['name' => $training->pic_name,
                    'position' => $training->pic_position,
                    'phone_number' => $training->pic_phone_number,
                    'created_at' => $this->validDate($training->pic_created_at),
                    'updated_at' => $this->validDate($training->pic_created_at)]
                );
                $newTraining->trainingPic()->create([
                    'pic_id' => $newPic->id,
                    'created_at' => $this->validDate($training->pic_created_at),
                    'updated_at' => $this->validDate($training->pic_created_at),
                ]);
                // Import Training: Statuses
                $trainingStatuses = DB::connection('mysql_2')
                ->table('school_training_statuses')
                ->join('statuses', 'school_training_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_training_statuses.log_id', '=', 'activity_log.id')
                ->where('school_training_statuses.school_training_id', $training->school_training_id)
                ->select('school_training_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($trainingStatuses as $trainingStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id,
                    ];
                    if ($trainingStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $trainingStatus->log,
                        'created_at' => $this->validDate($trainingStatus->created_at),
                        'updated_at' => $this->validDate($trainingStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $trainingStatus->status)->first();
                    $newTraining->trainingStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($trainingStatus->created_at),
                        'updated_at' => $this->validDate($trainingStatus->created_at),
                    ]);
                }
            }

            // Import School: Exam Readinesses
            $examReadinesses = DB::connection('mysql_2')
            ->table('exam_confirmations')
            ->join('exam_confirmation_pics', 'exam_confirmations.id', '=', 'exam_confirmation_pics.exam_confirmation_id')
            ->join('pics', 'exam_confirmation_pics.pic_id', '=', 'pics.pic_id')
            ->where('exam_confirmations.school_id', $school->school_id)
            ->whereNotNull('exam_type')
            ->select('exam_confirmations.*', 'exam_confirmation_pics.created_at as pic_created_at', 'pics.pic_name', 'pics.pic_position', 'pics.pic_phone_number', 'pics.pic_email')
            ->get();
            foreach ($examReadinesses as $examReadiness) {
                $dispatcher = \App\ExamReadiness::getEventDispatcher();
                \App\ExamReadiness::unsetEventDispatcher();
                $newExamReadiness = $newSchool->examReadinesses()->create([
                    'id' => Str::uuid(),
                    'exam_type' => $examReadiness->exam_type, 
                    'sub_exam_type' => $examReadiness->sub_exam_type, 
                    'ma_status' => $examReadiness->ma_status, 
                    'reference_school' => $examReadiness->reference_school, 
                    'execution' => $examReadiness->execution, 
                    'token' => (empty($examReadiness->random_code)?Str::random(10):$examReadiness->random_code), 
                    'created_at' => $this->validDate($examReadiness->created_at),
                    'updated_at' => $this->validDate($examReadiness->created_at),
                ]);
                \App\ExamReadiness::setEventDispatcher($dispatcher);
                // Import Exam Readiness: Students
                $examReadinessStudents = DB::connection('mysql_2')
                ->table('exam_confirmation_students')
                ->join('students', 'exam_confirmation_students.student_id', '=', 'students.student_id')
                ->where('exam_confirmation_students.exam_confirmation_id', $examReadiness->id)
                ->select('exam_confirmation_students.*', 'students.name', 'students.generation', 'students.department')
                ->get();
                foreach ($examReadinessStudents as $examReadinessStudent) {
                    $selectedStudent = DB::table('students')
                    ->join('student_classes', 'students.class_id', '=', 'student_classes.id')
                    ->join('departments', 'student_classes.department_id', '=', 'departments.id')
                    ->where('student_classes.school_id', $newSchool->id)
                    ->where('students.name', $examReadinessStudent->name)
                    ->where('student_classes.generation', $examReadinessStudent->generation)
                    ->where(function ($query) use ($examReadinessStudent) {
                        $query->where('departments.name', $examReadinessStudent->department)
                        ->orWhere('departments.abbreviation', $examReadinessStudent->department);
                    })->select('students.id')->first();
                    if ($selectedStudent) {
                        $newExamReadiness->students()->attach($selectedStudent->id, [
                            'created_at' => $this->validDate($examReadinessStudent->created_at),
                            'updated_at' => $this->validDate($examReadinessStudent->created_at),
                        ]);
                    }
                }
                // Import Exam Readiness: PIC
                $newPic = \App\Pic::firstOrCreate(
                    ['email' => $examReadiness->pic_email],
                    ['name' => $examReadiness->pic_name,
                    'position' => $examReadiness->pic_position,
                    'phone_number' => $examReadiness->pic_phone_number,
                    'created_at' => $this->validDate($examReadiness->pic_created_at),
                    'updated_at' => $this->validDate($examReadiness->pic_created_at)]
                );
                $newExamReadiness->examReadinessPic()->create([
                    'pic_id' => $newPic->id,
                    'created_at' => $this->validDate($examReadiness->pic_created_at),
                    'updated_at' => $this->validDate($examReadiness->pic_created_at),
                ]);
                // Import Exam Readiness: Statuses
                $examReadinessStatuses = DB::connection('mysql_2')
                ->table('exam_confirmation_statuses')
                ->join('statuses', 'exam_confirmation_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'exam_confirmation_statuses.log_id', '=', 'activity_log.id')
                ->where('exam_confirmation_statuses.exam_confirmation_id', $examReadiness->id)
                ->select('exam_confirmation_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($examReadinessStatuses as $examReadinessStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id,
                    ];
                    if ($examReadinessStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $examReadinessStatus->log,
                        'created_at' => $this->validDate($examReadinessStatus->created_at),
                        'updated_at' => $this->validDate($examReadinessStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $examReadinessStatus->status)->first();
                    $newExamReadiness->examReadinessStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($examReadinessStatus->created_at),
                        'updated_at' => $this->validDate($examReadinessStatus->created_at),
                    ]);
                }
            }

            // Import School: Visitations
            $visitations = DB::connection('mysql_2')
            ->table('school_visitations')
            ->where('school_id', $school->school_id)
            ->select('school_visitations.*')
            ->get();
            foreach ($visitations as $visitation) {
                $dispatcher = \App\Attendance::getEventDispatcher();
                \App\Attendance::unsetEventDispatcher();
                $newVisitation = $newSchool->attendances()->create([
                    'id' => Str::uuid(),
                    'type' => 'Visitasi',
                    'destination' => $visitation->school_destination, 
                    'participant' => $visitation->participant, 
                    'submission_letter' => $visitation->submission_letter, 
                    'created_at' => $this->validDate($visitation->created_at),
                    'updated_at' => $this->validDate($visitation->created_at),
                ]);
                \App\Attendance::setEventDispatcher($dispatcher);
                // Copy Visitation: Submission Letter
                if ( ! empty($visitation->submission_letter)) {
                    if (Storage::disk('acp')->exists('/schools/visitations/' . $visitation->submission_letter)) {
                        $folder = storage_path('app/public/attendance/submission-letter/' . strstr($visitation->submission_letter, '/', true));
                        if ( ! file_exists(storage_path('app/public/attendance/'))) {
                            mkdir(storage_path('app/public/attendance/'));
                        }
                        if ( ! file_exists(storage_path('app/public/attendance/submission-letter/'))) {
                            mkdir(storage_path('app/public/attendance/submission-letter/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/visitations/' . $visitation->submission_letter) . ' ' . $folder);
                    }
                }
                // Import Visitation: Statuses
                $visitationStatuses = DB::connection('mysql_2')
                ->table('school_visitation_statuses')
                ->join('statuses', 'school_visitation_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_visitation_statuses.log_id', '=', 'activity_log.id')
                ->where('school_visitation_statuses.school_visitation_id', $visitation->school_visitation_id)
                ->select('school_visitation_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($visitationStatuses as $visitationStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id
                    ];
                    if ($visitationStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $visitationStatus->log,
                        'created_at' => $this->validDate($visitationStatus->created_at),
                        'updated_at' => $this->validDate($visitationStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $visitationStatus->status)->first();
                    $newVisitation->attendanceStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($visitationStatus->created_at),
                        'updated_at' => $this->validDate($visitationStatus->created_at),
                    ]);
                }
            }

            // Import School: Audiences
            $audiences = DB::connection('mysql_2')
            ->table('school_audiences')
            ->where('school_audiences.school_id', $school->school_id)
            ->select('school_audiences.*')
            ->get();
            foreach ($audiences as $audience) {
                $dispatcher = \App\Attendance::getEventDispatcher();
                \App\Attendance::unsetEventDispatcher();
                $newAudience = $newSchool->attendances()->create([
                    'id' => Str::uuid(),
                    'type' => 'Audiensi',
                    'number_of_participant' => $audience->number_of_participant, 
                    'transportation' => $audience->transportation, 
                    'date' => $audience->date, 
                    'until_date' => $audience->return_date, 
                    'arrival_point' => $audience->arrival_point, 
                    'contact_person' => $audience->contact_person, 
                    'contact_person_phone_number' => $audience->contact_person_phone_number, 
                    'created_at' => $this->validDate($audience->created_at),
                    'updated_at' => $this->validDate($audience->created_at),
                ]);
                \App\Attendance::setEventDispatcher($dispatcher);
                // Import Audience: Participants
                $audienceParticipants = DB::connection('mysql_2')
                ->table('audience_participants')
                ->where('school_audience_id', $audience->school_audience_id)
                ->select('*')
                ->get();
                foreach ($audienceParticipants as $audienceParticipant) {
                    $existTeacher = \App\Teacher::where('email', $audienceParticipant->email)->first();
                    $teacherEmail = 'teacher' . $faker->unique()->numberBetween(100000, 999999) . '@mail.com';
                    $newTeacher = $newSchool->teachers()->firstOrCreate(
                        ['email' => (empty($audienceParticipant->email)?$teacherEmail:($existTeacher?$teacherEmail:$audienceParticipant->email))],
                        ['name' => $audienceParticipant->name,
                        'phone_number' => $audienceParticipant->phone_number, 
                        'position' => $audienceParticipant->position,
                        'created_at' => $this->validDate($audienceParticipant->created_at),
                        'updated_at' => $this->validDate($audienceParticipant->created_at)]
                    );
                    $newAudience->participants()->attach($newTeacher->id, [
                        'created_at' => $this->validDate($audienceParticipant->created_at),
                        'updated_at' => $this->validDate($audienceParticipant->created_at)
                    ]);
                }
                if ($audienceParticipants->count() == 0) {
                    if ( ! empty($audience->participant_name_1st)) {
                        $newTeacher = $newSchool->teachers()->firstOrCreate(
                            ['email' => $audience->participant_email_1st],
                            ['name' => $audience->participant_name_1st,
                            'phone_number' => $audience->participant_phone_number_1st, 
                            'position' => $audience->participant_position_1st,
                            'created_at' => $this->validDate($audience->created_at),
                            'updated_at' => $this->validDate($audience->created_at)]
                        );
                        $newAudience->participants()->attach($newTeacher->id, [
                            'created_at' => $this->validDate($audience->created_at),
                            'updated_at' => $this->validDate($audience->created_at)
                        ]);
                    }
                    if ( ! empty($audience->participant_name_2nd)) {
                        $newTeacher = $newSchool->teachers()->firstOrCreate(
                            ['email' => $audience->participant_email_2nd],
                            ['name' => $audience->participant_name_2nd,
                            'phone_number' => $audience->participant_phone_number_2nd, 
                            'position' => $audience->participant_position_2nd,
                            'created_at' => $this->validDate($audience->created_at),
                            'updated_at' => $this->validDate($audience->created_at)]
                        );
                        $newAudience->participants()->attach($newTeacher->id, [
                            'created_at' => $this->validDate($audience->created_at),
                            'updated_at' => $this->validDate($audience->created_at)
                        ]);
                    }
                }
                // Import Audience: Statuses
                $audienceStatuses = DB::connection('mysql_2')
                ->table('school_audience_statuses')
                ->join('statuses', 'school_audience_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_audience_statuses.log_id', '=', 'activity_log.id')
                ->where('school_audience_statuses.school_audience_id', $audience->school_audience_id)
                ->select('school_audience_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($audienceStatuses as $audienceStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id
                    ];
                    if ($audienceStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $audienceStatus->log,
                        'created_at' => $this->validDate($audienceStatus->created_at),
                        'updated_at' => $this->validDate($audienceStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $audienceStatus->status)->first();
                    $newAudience->attendanceStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($audienceStatus->created_at),
                        'updated_at' => $this->validDate($audienceStatus->created_at),
                    ]);
                }
            }

            // Import School: Payment
            $payments = DB::connection('mysql_2')
            ->table('school_payments')
            ->leftJoin('school_subsidy_payments', 'school_payments.school_payment_id', '=', 'school_subsidy_payments.school_payment_id')
            ->leftJoin('school_subsidies', 'school_subsidy_payments.school_subsidy_id', '=', 'school_subsidies.school_subsidy_id')
            ->leftJoin('school_training_payments', 'school_payments.school_payment_id', '=', 'school_training_payments.school_payment_id')
            ->leftJoin('school_trainings', 'school_training_payments.school_training_id', '=', 'school_trainings.school_training_id')
            ->where('school_payments.school_id', $school->school_id)
            ->select('school_payments.*', 'school_subsidies.created_at as subsidy_created_at', 'school_subsidies.subsidy_type', 'school_trainings.created_at as training_created_at', 'school_trainings.training_type')
            ->get();
            foreach ($payments as $payment) {
                $dispatcher = \App\Payment::getEventDispatcher();
                \App\Payment::unsetEventDispatcher();
                $newPayment = $newSchool->payments()->create([
                    'id' => Str::uuid(),
                    'type' => $payment->transaction_type, 
                    'repayment' => null, 
                    'invoice' => $payment->invoice, 
                    'date' => $payment->date, 
                    'total' => str_replace('_', '', $payment->total), 
                    'method' => $payment->payment_method, 
                    'payment_receipt' => $payment->payment_receipt, 
                    'bank_sender' => $payment->bank_sender, 
                    'bank_name' => $payment->bank_name, 
                    'bill_number' => $payment->bill_number, 
                    'on_behalf_of' => $payment->in_the_name_of, 
                    'receiver_bank_name' => $payment->receiver_bank_name, 
                    'receiver_bill_number' => $payment->receiver_bill_number, 
                    'receiver_on_behalf_of' => $payment->receiver_in_the_name_of, 
                    'commitment_letter' => $payment->commitment_letter, 
                    'bank_account_book' => $payment->bank_account_book, 
                    'npwp_number' => $payment->npwp_number, 
                    'npwp_on_behalf_of' => $payment->npwp_in_the_name_of, 
                    'npwp_address' => $payment->npwp_address, 
                    'npwp_file' => $payment->npwp_file, 
                    'created_at' => $this->validDate($payment->created_at),
                    'updated_at' => $this->validDate($payment->created_at),
                ]);
                \App\Payment::setEventDispatcher($dispatcher);
                // Copy Payment: Payment Receipt
                if ( ! empty($payment->payment_receipt)) {
                    if (Storage::disk('acp')->exists('/schools/payments/' . $payment->payment_receipt)) {
                        $folder = storage_path('app/public/payment/payment-receipt/' . strstr($payment->payment_receipt, '/', true));
                        if ( ! file_exists(storage_path('app/public/payment/'))) {
                            mkdir(storage_path('app/public/payment/'));
                        }
                        if ( ! file_exists(storage_path('app/public/payment/payment-receipt/'))) {
                            mkdir(storage_path('app/public/payment/payment-receipt/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/payments/' . $payment->payment_receipt) . ' ' . $folder);
                    }
                }
                // Copy Payment: Bank Account Book
                if ( ! empty($payment->bank_account_book)) {
                    if (Storage::disk('acp')->exists('/schools/payments/' . $payment->bank_account_book)) {
                        $folder = storage_path('app/public/payment/bank-account-book/' . strstr($payment->bank_account_book, '/', true));
                        if ( ! file_exists(storage_path('app/public/payment/'))) {
                            mkdir(storage_path('app/public/payment/'));
                        }
                        if ( ! file_exists(storage_path('app/public/payment/bank-account-book/'))) {
                            mkdir(storage_path('app/public/payment/bank-account-book/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/payments/' . $payment->bank_account_book) . ' ' . $folder);
                    }
                }
                // Copy Payment: NPWP File
                if ( ! empty($payment->npwp_file)) {
                    if (Storage::disk('acp')->exists('/schools/payments/' . $payment->npwp_file)) {
                        $folder = storage_path('app/public/payment/npwp/' . strstr($payment->npwp_file, '/', true));
                        if ( ! file_exists(storage_path('app/public/payment/'))) {
                            mkdir(storage_path('app/public/payment/'));
                        }
                        if ( ! file_exists(storage_path('app/public/payment/npwp/'))) {
                            mkdir(storage_path('app/public/payment/npwp/'));
                        }
                        if ( ! file_exists($folder)) {
                            mkdir($folder);
                        }
                        shell_exec('cp ' . Storage::disk('acp')->path('schools/payments/' . $payment->npwp_file) . ' ' . $folder);
                    }
                }
                // Import Payment: Subsidy & Training Relation
                if ($payment->transaction_type == 'Subsidi') {
                    if ( ! empty($payment->subsidy_type)) {
                        $subsidyPayment = DB::table('subsidies')
                        ->where('school_id', $newSchool->id)
                        ->where('type', $payment->subsidy_type)
                        ->where('created_at', $payment->subsidy_created_at)
                        ->select('*')
                        ->first();
                        if ($subsidyPayment) {
                            $newPayment->subsidy()->attach($subsidyPayment->id);
                        }
                    }
                } elseif ($payment->transaction_type == 'Commitment Fee') {
                    if ( ! empty($payment->training_type)) {
                        $trainingPayment = DB::table('trainings')
                        ->where('school_id', $newSchool->id)
                        ->where('type', $payment->training_type)
                        ->where('created_at', $payment->training_created_at)
                        ->select('*')
                        ->first();
                        if ($trainingPayment) {
                            $newPayment->training()->attach($trainingPayment->id);
                        }
                    }
                }
                // Import Payment: Statuses
                $paymentStatuses = DB::connection('mysql_2')
                ->table('school_payment_statuses')
                ->join('statuses', 'school_payment_statuses.status_id', '=', 'statuses.id')
                ->join('activity_log', 'school_payment_statuses.log_id', '=', 'activity_log.id')
                ->where('school_payment_statuses.school_payment_id', $payment->school_payment_id)
                ->select('school_payment_statuses.*', 'statuses.name as status', 'activity_log.description as log', 'activity_log.created_by')
                ->get();
                foreach ($paymentStatuses as $paymentStatus) {
                    $logData = [
                        'created_by' => 'admin',
                        'staff_id' => $admin->id
                    ];
                    if ($paymentStatus->created_by != 'admin') {
                        $logData = [
                            'created_by' => 'school',
                            'user_id' => $newUser->id
                        ];
                    }
                    $logData = array_merge($logData, [
                        'description' => $paymentStatus->log,
                        'created_at' => $this->validDate($paymentStatus->created_at),
                        'updated_at' => $this->validDate($paymentStatus->created_at),
                    ]);
                    $log = \App\ActivityLog::create($logData);
                    $status = \App\Status::where('name', $paymentStatus->status)->first();
                    $newPayment->paymentStatuses()->create([
                        'status_id' => $status->id,
                        'log_id' => $log->id,
                        'created_at' => $this->validDate($paymentStatus->created_at),
                        'updated_at' => $this->validDate($paymentStatus->created_at),
                    ]);
                }
            }
        }
    }

    /**
     * Validate the date
     */
    public function validDate($datetime)
    {
		if ( ! empty($datetime)) {
			if (checkdate(date('m', strtotime($datetime)), date('d', strtotime($datetime)), date('Y', strtotime($datetime)))) {
				if (date('Y-m-d h:m:s', strtotime($datetime)) > date('Y-m-d h:m:s', 0)) {
                    return $datetime;
				}
			}
		}
        return null;
    }
}