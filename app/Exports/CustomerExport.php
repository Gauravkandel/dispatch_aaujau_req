<?php

namespace App\Exports;
use App\Model\Agent;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;

use Auth;
class CustomerExport implements WithEvents, FromArray, WithHeadings, ShouldAutoSize{

    use Exportable;
    private $myArray;
    private $myHeadings;

    public function __construct($myArray, $myHeadings){
        $this->myArray = $myArray;
        $this->myHeadings = $myHeadings;
      }
      public function array(): array{
        return $this->myArray;
      }
      public function headings(): array
      {
        return $this->myHeadings;
      }
      public function headingRow(): int
      {
          return 2;
      }
      public function registerEvents(): array
      {
          return [
              AfterSheet::class    => function(AfterSheet $event) {
                  $cellRange = 'A1:E1'; // All headers
                  $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
    
                  $styleArray = [
                      // 'borders' => [
                      //     'outline' => [
                      //         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                      //         'color' => ['argb' => 'FFFF0000'],
                      //     ]
                      // ],
                      'font' => [
                          'name' =>  'Calibri',
                          'size' =>  11,
                          'bold' =>  true,
                      ],
                      'alignment' => [
                          'horizontal' => 'center',
                      ]
                  ];
                  $event->sheet->getStyle('A2')->applyFromArray([
                      'alignment' => [
                          'horizontal' => 'center',
                      ]
                  ]);
                  
                  $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                  $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(16);
                  $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
              },
          ];
      }
    
}
