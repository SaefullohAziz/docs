<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
    	$this->call(AccountPermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(SchoolStatusesTableSeeder::class);
        $this->call(SchoolPermissionsTableSeeder::class);
        $this->call(StudentPermissionsTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(SubsidyPermissionsTableSeeder::class);
        $this->call(TrainingPermissionsTableSeeder::class);
        $this->call(PaymentPermissionsTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(PoliceNumbersTableSeeder::class);
        $this->call(StudentClassPermissionsSeeder::class);
        $this->call(ExamReadinessPermissionsSeeder::class);
        $this->call(ExamTypesSeeder::class);
        $this->call(ActivityPermissionsTableSeeder::class);
        $this->call(ExamReadinessSchoolsTableSeeder::class);
        $this->call(TeacherPermissionsTableSeeder::class);
        $this->call(AttendancePermissionsTableSeeder::class);
        $this->call(SettingPermissionsTableSeeder::class);
        $this->call(FormSettingsSeeder::class);
        $this->call(TrainingSettingsSeeder::class);
        $this->call(ExamReadinessSettingsSeeder::class);
        $this->call(UpdatePermissionsSeeder::class);
        $this->call(GrantPermissionsSeeder::class);
    }
}
