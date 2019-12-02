<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsExport implements ShouldAutoSize, WithEvents
{
    use Exportable, RegistersEventListeners;

    /**
     * Instantiate a new class instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    	$this->request = $request;
    }

    /**
     * Show listing of data for export
     */
    public function get()
    {
    	return Student::list($this->request)->get()->toArray();
    }

    /**
     * Set all attribute
     */
    public function worksheets()
    {
    	return [
            ['column' => 'id', 'title' => 'id'], 
    		['column' => 'created_at', 'title' => 'Created At'], 
    		['column' => 'name', 'title' => 'Name'], 
    		['column' => 'username', 'title' => 'Username'], 
    		['column' => 'nickname', 'title' => 'Nickname'],
    		['column' => 'school', 'title' => 'School'],
    		['column' => 'school_year', 'title' => 'School Year'],
    		['column' => 'province', 'title' => 'Province'],
    		['column' => 'nisn', 'title' => 'NISN'],
    		['column' => 'department', 'title' => 'Department'],
    		['column' => 'email', 'title' => 'E-Mail'],
    		['column' => 'gender', 'title' => 'Gender'],
    		['column' => 'grade', 'title' => 'Grade'],
    		['column' => 'generation', 'title' => 'generation'],
    		['column' => 'father_name', 'title' => 'Father Name'],
    		['column' => 'father_education', 'title' => 'Father Education'],
    		['column' => 'father_earning', 'title' => 'Father Earning'],
    		['column' => 'father_earning_nominal', 'title' => 'father Earning Nominal'],
    		['column' => 'mother_name', 'title' => 'Mother Name'],
    		['column' => 'mother_education', 'title' => 'Mother Education'],
    		['column' => 'mother_earning', 'title' => 'Mother Earning'],
    		['column' => 'mother_earning_nominal', 'title' => 'Mother Earning Nominal'],
    		['column' => 'trustee_name', 'title' => 'Trustee Name'],
    		['column' => 'trustee_education', 'title' => 'Trustee Education'],
    		['column' => 'economy_status', 'title' => 'Economy Status'],
    		['column' => 'religion', 'title' => 'Religion'],
    		['column' => 'blood_type', 'title' => 'Blood Type'],
    		['column' => 'special_need', 'title' => 'Special Need'],
    		['column' => 'mileage', 'title' => 'Mileage'],
    		['column' => 'distance', 'title' => 'Distance'],
    		['column' => 'diploma_number', 'title' => 'Diploma Number'],
    		['column' => 'height', 'title' => 'Height'],
    		['column' => 'weight', 'title' => 'Weight'],
    		['column' => 'child_order', 'title' => 'Child Order'],
    		['column' => 'sibling_number', 'title' => 'Number of siblings'],
    		['column' => 'stepbrother_number', 'title' => 'Number of Stepbrothers'],
    		['column' => 'step_sibling_number', 'title' => 'Number of siblings raised'],
    		['column' => 'dateofbirth', 'title' => 'Date of Birth'],
    		['column' => 'address', 'title' => 'Address'],
    		['column' => 'father_address', 'title' => 'Father Address'],
    		['column' => 'trustee_address', 'title' => 'Trustee Address'],
    		['column' => 'phone_number', 'title' => 'Phone Number'],
    		['column' => 'computer_basic_score', 'title' => 'Basic Computer'],
    		['column' => 'intelligence_score', 'title' => 'Intelligence'],
    		['column' => 'reasoning_score', 'title' => 'Reasoning'],
    		['column' => 'analogy_score', 'title' => 'Analogy'],
    		['column' => 'numerical_score', 'title' => 'Numerical & Accuracy'],
    	];
    }

    /**
     * All function to export data
     * 
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event)
            {
                // Heading
                $count = 2;
                $event->writer->getActiveSheet()->setCellValue('A1', '#');
                foreach ($this->worksheets() as $worksheet) {
                    $event->writer->getActiveSheet()->setCellValue(Coordinate::stringFromColumnIndex($count++).'1', $worksheet['title']);
                }
                // Content
                $count = 1;
                foreach ($this->get() as $data) {
                    $event->writer->getActiveSheet()->setCellValue(Coordinate::stringFromColumnIndex(1).($count+1), $count);
                    $subCount = 2;
                    foreach ($this->worksheets() as $worksheet) {
                        $event->writer->getActiveSheet()->setCellValueExplicit(Coordinate::stringFromColumnIndex($subCount).($count+1), $data->{$worksheet['column']}, DataType::TYPE_STRING);
                        $subCount++;
                    }
                    $count++;
                }
            },
            AfterSheet::class => function(AfterSheet $event)
            {
                $allBorders = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];
                $headingStyle = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFFFCC00',
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle('A1:'.Coordinate::stringFromColumnIndex(count($this->worksheets())+1).'1')->applyFromArray($headingStyle);
                $count = 2;
                foreach ($this->worksheets() as $worksheet) {
                    $event->sheet->getDelegate()->getColumnDimension(Coordinate::stringFromColumnIndex($count++))->setAutoSize(true);
                }
                if ( ! empty($this->get())) {
                    $event->sheet->getDelegate()->getStyle('A1:'.Coordinate::stringFromColumnIndex(count($this->worksheets())+1).(count($this->get())+1))->applyFromArray($allBorders);
                }
            },
        ];
    }
}
