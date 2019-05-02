<?php

namespace App\Exports;

use App\School;
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

class SchoolsExport implements ShouldAutoSize, WithEvents
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
    	return School::list($this->request)->get()->toArray();
    }

    /**
     * Set all attribute
     */
    public function worksheets()
    {
    	return [
    		['column' => 'created_at', 'title' => 'Created At'], 
    		['column' => 'name', 'title' => 'Name'], 
    		['column' => 'address', 'title' => 'Address'], 
    		['column' => 'province', 'title' => 'Province'], 
    		['column' => 'regency', 'title' => 'Regency'], 
    		['column' => 'police_number', 'title' => 'Police Number'], 
    		['column' => 'since', 'title' => 'Since'], 
    		['column' => 'school_phone_number', 'title' => 'School Phone Number'], 
    		['column' => 'school_email', 'title' => 'School E-Mail'], 
    		['column' => 'school_web', 'title' => 'School Website'], 
    		['column' => 'total_student', 'title' => 'Total Student'], 
    		['column' => 'acp_student', 'title' => 'ACP Student'], 
    		['column' => 'department', 'title' => 'Department'], 
    		['column' => 'iso_certificate', 'title' => 'ISO Certificate'], 
    		['column' => 'mikrotik_academy', 'title' => 'MikroTik Academy'], 
    		['column' => 'headmaster_name', 'title' => 'Headmaster Name'], 
    		['column' => 'headmaster_phone_number', 'title' => 'Headmaster Phone Number'], 
    		['column' => 'headmaster_email', 'title' => 'Headmaster E-Mail'], 
    		['column' => 'pic_name', 'title' => 'PIC Name'], 
    		['column' => 'pic_position', 'title' => 'PIC Position'], 
    		['column' => 'pic_phone_number', 'title' => 'PIC Phone Number'], 
    		['column' => 'pic_email', 'title' => 'PIC E-Mail'], 
    		['column' => 'proposal', 'title' => 'Proposal'], 
    		['column' => 'reference', 'title' => 'Referensi'], 
    		['column' => 'dealer_name', 'title' => 'Dealer Name'], 
    		['column' => 'dealer_name', 'title' => 'Dealer Phone Number'], 
    		['column' => 'dealer_email', 'title' => 'Dealer E-Mail'], 
    		['column' => 'document', 'title' => 'Document'], 
    		['column' => 'level_name', 'title' => 'Level'], 
    		['column' => 'status_name', 'title' => 'Status']
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
                    $event->writer->getActiveSheet()->setCellValue(Coordinate::stringFromColumnIndex($count).($count+1), $count);
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
                $count = 1;
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
