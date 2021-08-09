<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\HeadingExcel;

class DosenExports implements FromCollection, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    
    private $fileName = "Dosen.xlsx";
    public $rows;
    public $head;

    function __construct($rows,$head) {
        $this->row = $rows;
        $this->head = $head;
    }
    public function collection()
    {
        return new Collection([
            $this->row
        ]);
    }
    
    public function array_remove_by_value($array, $value){
        return array_values(array_diff($array, $value));
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {

                // last column as letter value (e.g., D)
                $last_column = Coordinate::stringFromColumnIndex(count($this->head));

                // calculate last row + 1 (total results + header rows + column headings row + new row)
                $last_row = count($this->rows) + 2 + 1 + 1;

                // set up a style array for cell formatting
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];
                
                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 2);

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('A1:%s1',$last_column));
                $event->sheet->mergeCells(sprintf('A2:%s2',$last_column));
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$last_row,$last_column,$last_row));

                // assign cell values
                $event->sheet->setCellValue('A1','Top Triggers Report');
                $event->sheet->setCellValue('A2','SECURITY CLASSIFICATION - UNCLASSIFIED [Generator: Admin]');
                $event->sheet->setCellValue(sprintf('A%d',$last_row),'SECURITY CLASSIFICATION - UNCLASSIFIED [Generated: ...]');

                // assign cell styles
                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
                $event->sheet->getStyle(sprintf('A%d',$last_row))->applyFromArray($style_text_center);
            },
        ];
    }

    public function headings(): array
    {
        $headArray = array();
        $head = HeadingExcel::all();
        $counthead = count($head);
        foreach ($head as $header){
            $headArray[] =  (
                $header->heading
            );
        }
        $t = array();
        $t[] =(
            $this->head
        ); 
        foreach ($t as $x=>$val){
            $newarray = self::array_remove_by_value($headArray, $val);
        }
        return $newarray;
    }

    
    
}
