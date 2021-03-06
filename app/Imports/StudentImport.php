<?php

namespace App\Imports;

use App\Student;
use App\StudentClass;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public function __construct(StudentClass $studentClass)
    {
        $this->studentClass = $studentClass;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row['dateofbirth'] = date('Y-m-d', ($row['dateofbirth'] - 25569) * 86400);
        return $this->studentClass->students()->create([
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

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'nickname' => ['required'],
            'province' => ['required'],
            'nisn' => ['required', 'digits:10', 'unique:students,nisn'],
            'email' => ['required', 'email', 'unique:students,email'],
            'gender' => ['required'],
            'father_earning_nominal' => ['numeric'],
            'mother_name' => ['required'],
            'mother_earning_nominal' => ['numeric'],
            'religion' => ['required'],
            'blood_type' => ['required'],
            'special_need' => ['required'],
            'distance' => ['numeric'],
            'height' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'dateofbirth' => ['required'],
            'address' => ['required'],
            'phone_number' => ['required', 'numeric', 'digits_between:8,11', 'unique:students,phone_number'],
            'computer_basic_score' => ['integer'],
            'intelligence_score' => ['integer'],
            'reasoning_score' => ['integer'],
            'analogy_score' => ['integer'],
            'numerical_score' => ['integer'],
        ];
    }
}

            // 'father_name' => Rule::in([]),

            // 'father_education' => Rule::in([]),

            // 'father_earning' => Rule::in([]),

            // 'mother_education' => Rule::in([]),

            // 'mother_earning' => Rule::in([]),

            // 'trustee_name' => Rule::in([]),

            // 'trustee_education' => Rule::in([]),

            // 'economy_status' => Rule::in([]),

            // 'mileage' => Rule::in([]),

            // 'diploma_number' => Rule::in([]),

            // 'child_order' => Rule::in([]),

            // 'sibling_number' => Rule::in([]),

            // 'stepbrother_number' => Rule::in([]),

            // 'step_sibling_number' => Rule::in([]),

            // 'father_address' => Rule::in([]),

            // 'trustee_address' => Rule::in([]),