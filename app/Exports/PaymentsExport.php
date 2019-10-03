<?php

namespace App\Exports;

use App\Payment;
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

class PaymentsExport implements ShouldAutoSize, WithEvents
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
    	return Payment::list($this->request)->get()->toArray();
    }

    /**
     * Set all attribute
     */
    public function worksheets()
    {
    	return [
    		['column' => 'created_at', 'title' => 'Created At'], 
    		['column' => 'school', 'title' => 'School'], 
    		['column' => 'type', 'title' => 'Type'],
            ['column' => 'invoice', 'title' => 'Invoice'],
            ['column' => 'date', 'title' => 'Date'],
            ['column' => 'total', 'title' => 'Total'],
            ['column' => 'method', 'title' => 'Method'],
            ['column' => 'bank_sender', 'title' => 'Bank Sender'],
            ['column' => 'bank_name', 'title' => 'Bank Name'],
            ['column' => 'bill_number', 'title' => 'Bill Number'],
            ['column' => 'on_behalf_of', 'title' => 'On Behalf of'],
            ['column' => 'receiver_bank_name', 'title' => 'Receiver Bank Name'],
            ['column' => 'receiver_bill_number', 'title' => 'Receiver Bill Number'],
    		['column' => 'receiver_on_behalf_of', 'title' => 'Receiver On Behalf of'],
    		['column' => 'npwp_number', 'title' => 'NPWP Number'],
    		['column' => 'npwp_on_behalf_of', 'title' => 'NPWP On Behalf of'],
    		['column' => 'npwp_address', 'title' => 'NPWP Address'],
    		['column' => 'status', 'title' => 'Status'],
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
