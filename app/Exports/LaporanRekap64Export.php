<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Kamus\Kamus_rekening;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LaporanRekap64Export implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

	public $nomor_lokasi;
	public $kode_kepemilikan;

	function __construct($args){
		$this->nomor_lokasi = $args['nomor_lokasi'];
		$this->kode_kepemilikan = $args['kode_kepemilikan'];
		$this->nama_lokasi = $args['nama_lokasi'];
		$this->nama_jurnal = $args['nama_jurnal'];
        $this->total_nilai = 0;
        $this->tahun_sekarang = date('Y')-1;
	}

    public function collection()
    {
        $data = Kib::select('kibs.kode_64','kibs.no_register','kibs.nama_barang','kibs.merk_alamat','kibs.saldo_barang','kibs.harga_total_plus_pajak_saldo','kibs.baik','kibs.kb','kibs.rb','kibs.keterangan')
                    ->where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                    ->where('kode_kepemilikan', $this->kode_kepemilikan)
                    ->get();

        $rekap_64 = array();

        // loop khusus build map 64
        foreach($data as $value) {
            $kode_64 = $value["kode_64"];

            if($kode_64 != '' || !is_null($kode_64) || $kode_64 != 0) {
                $uraian = Kamus_rekening::select("uraian_64")->where('kode_64', $kode_64)->first();
            }

            if(!is_null($uraian)) {
                $uraian_64 = $uraian->uraian_64;
            }

            $found = false;
            foreach($rekap_64 as $key => $value)
            {
                if ($value["kode_64"] == $kode_64) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_64)) {
                    array_push($rekap_64, array(
                        "kode_64" => 0,
                        "uraian_64" => 0,
                        "nilai" => 0
                    ));
                } else {
                    array_push($rekap_64, array(
                        "kode_64" => $kode_64,
                        "uraian_64" => $uraian_64,
                        "nilai" => 0
                    ));
                }
            }
        }

        //loop khusus penjumlahan data
        foreach($data as $value) {
            $kode_64 = $value["kode_64"];
            $this->total_nilai += $value["harga_total_plus_pajak_saldo"];

            foreach($rekap_64 as $key => $rekap)
            {
                if($kode_64 != '' || !is_null($kode_64) || $kode_64 != 0) {
                    if ($rekap["kode_64"] == $kode_64) {
                        $rekap_64[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                        $found = true;
                        break;
                    }
                } else {
                    $rekap_64[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                    $found = true;
                    break;
                }
            }
        }

        array_multisort(array_column($rekap_64, 'kode_64'), SORT_ASC, $rekap_64);

        $export = collect($rekap_64);
        return $export;
    }

    public function startCell(): string
    {
        return 'B3';
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function headings(): array
    {
        $headings = [
            ["Kode Rekening Permen 64", "Uraian", "Nilai"],
            [2,3,4]
        ];

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
            	$max = $event->sheet->getDelegate()->getHighestRow();
                ////////// set paper
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                $event->sheet->getPageSetup()->setFitToPage(true);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                $event->sheet->setShowGridlines(false);
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                $event->sheet->freezePane('E5');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A3:D3')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A4:D4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // end border heading
                // border
                $event->sheet->getStyle('A5:D'.$max)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A'.($max+1).':D'.($max+1))->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // end border

                // footer
                $event->sheet->getHeaderFooter()
                ->setOddFooter('&L&B '.$this->nama_jurnal.' / '. $this->nama_lokasi.' / '.$this->tahun_sekarang.' / '.$this->kode_kepemilikan.' - Pemerintah Kab / kota' . '&R &P / &N');
                // end footer

                /////////header
                $event->sheet->getDelegate()->mergeCells('A1:D1');
                $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal . " " . $this->nama_lokasi ." ".$this->tahun_sekarang);
	            $event->sheet->getStyle('A1')->applyFromArray([
                    'alignment' => [
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				    ],
                    'font' => [
                        'bold' => true,
                        'size' => 18
                    ]
                ]);
                $event->sheet->getRowDimension('1')->setRowHeight(50);
                $event->sheet->getStyle('A1')->getAlignment()->setWrapText(true);
                // end header

                ///////heading
                $event->sheet->getStyle('A3:D3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A3:D3')->getAlignment()->setWrapText(true);
                // end heading

                //////////numbering
                $event->sheet->getDelegate()->setCellValue("A3", "No.");
                $event->sheet->getDelegate()->setCellValue("A4", "1");
                $event->sheet->getStyle('A3:A'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $nomor = 1;
                for($i=5;$i<=$max;$i++){
                    $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                    $nomor++;
                }
                ///////////////end numbering
                // format text
                $event->sheet->getStyle('A4:D4')->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );

                $date = date('d/m/Y');
                $baris_total = $max+1;
                $event->sheet->getStyle('D'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('D'.$baris_total, $this->total_nilai);
                $event->sheet->getDelegate()->setCellValue('C'.$baris_total, 'Total');
                $event->sheet->getStyle('C'.$baris_total)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $f1 = $max+3;
                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('A'.$f1.':B'.$f1);
                    $event->sheet->getStyle('A'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('C'.$f1.':D'.$f1);
                    $event->sheet->getStyle('C'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('C'.$f1, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                        $event->sheet->getDelegate()->setCellValue('C'.$f1, "NIP");
                    }

                    $f1++;
                }
            },
        ];
	}

	public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

	public function title(): string
    {
        return 'Data';
    }

}
