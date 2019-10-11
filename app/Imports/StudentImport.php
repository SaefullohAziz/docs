<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'name' => $row['name'],
            'nickname' => $row['nickname'],
            'province' => $row['province'],
            'nisn' => $row['nisn'],
            'email' => $row['email'],
            'gender' => $row['gender'],
            'father_name' => $row['father_name'],
            'father_education' => $row['father_education'],
            'father_earning' => $row['father_earning'],
            'father_earning_nominal' => $row['father_earning_nominal'],
            'mother_name' => $row['mother_name'],
            'mother_education' => $row['mother_education'],
            'mother_earning' => $row['mother_earning'],
            'mother_earning_nominal' => $row['mother_earning_nominal'],
            'trustee_name' => $row['trustee_name'],
            'trustee_education' => $row['trustee_education'],
            'economy_status' => $row['economy_status'],
            'religion' => $row['religion'],
            'blood_type' => $row['blood_type'],
            'special_need' => $row['special_need'],
            'mileage' => $row['mileage'],
            'distance' => $row['distance'],
            'diploma_number' => $row['diploma_number'],
            'height' => $row['height'],
            'weight' => $row['weight'],
            'child_order' => $row['child_order'],
            'sibling_number' => $row['sibling_number'],
            'stepbrother_number' => $row['stepbrother_number'],
            'step_sibling_number' => $row['step_sibling_number'],
            'dateofbirth' => $row['dateofbirth'],
            'address' => $row['address'],
            'father_address' => $row['father_address'],
            'trustee_address' => $row['trustee_address'],
            'phone_number' => $row['phone_number'],
            'photo' => $row['photo'],
            'computer_basic_score' => $row['computer_basic_score'],
            'intelligence_score' => $row['intelligence_score'],
            'reasoning_score' => $row['reasoning_score'],
            'analogy_score' => $row['analogy_score'],
            'numerical_score' => $row['numerical_score'],
            'approval' => $row['approval'],
            'notif' => $row['notif']
        ]);
    }

    // public function rules()
    // {
    //     return [
    //         'name' => $row['name'],
    //         'nickname' => $row['nickname'],
    //         'province' => $row['province'],
    //         'nisn' => $row['nisn'],
    //         'email' => $row['email'],
    //         'gender' => $row['gender'],
    //         'father_name' => $row['father_name'],
    //         'father_education' => $row['father_education'],
    //         'father_earning' => $row['father_earning'],
    //         'father_earning_nominal' => $row['father_earning_nominal'],
    //         'mother_name' => $row['mother_name'],
    //         'mother_education' => $row['mother_education'],
    //         'mother_earning' => $row['mother_earning'],
    //         'mother_earning_nominal' => $row['mother_earning_nominal'],
    //         'trustee_name' => $row['trustee_name'],
    //         'trustee_education' => $row['trustee_education'],
    //         'economy_status' => $row['economy_status'],
    //         'religion' => $row['religion'],
    //         'blood_type' => $row['blood_type'],
    //         'special_need' => $row['special_need'],
    //         'mileage' => $row['mileage'],
    //         'distance' => $row['distance'],
    //         'diploma_number' => $row['diploma_number'],
    //         'height' => $row['height'],
    //         'weight' => $row['weight'],
    //         'child_order' => $row['child_order'],
    //         'sibling_number' => $row['sibling_number'],
    //         'stepbrother_number' => $row['stepbrother_number'],
    //         'step_sibling_number' => $row['step_sibling_number'],
    //         'dateofbirth' => $row['dateofbirth'],
    //         'address' => $row['address'],
    //         'father_address' => $row['father_address'],
    //         'trustee_address' => $row['trustee_address'],
    //         'phone_number' => $row['phone_number'],
    //         'photo' => $row['photo'],
    //         'computer_basic_score' => $row['computer_basic_score'],
    //         'intelligence_score' => $row['intelligence_score'],
    //         'reasoning_score' => $row['reasoning_score'],
    //         'analogy_score' => $row['analogy_score'],
    //         'numerical_score' => $row['numerical_score'],
    //         'approval' => $row['approval'],
    //         'notif' => $row['notif']
    //     ];
    // }
}
