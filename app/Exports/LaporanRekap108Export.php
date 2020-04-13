<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Rincian_108;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Kamus\Sub_sub_rincian_108;
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

class LaporanRekap108Export implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
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
        $data = Kib::select('kibs.kode_108','kibs.no_register','kibs.nama_barang','kibs.merk_alamat','kibs.saldo_barang','kibs.harga_total_plus_pajak_saldo','kibs.baik','kibs.kb','kibs.rb','kibs.keterangan')
                    ->where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                    ->where('kode_kepemilikan', $this->kode_kepemilikan)
                    ->get();

        $data_rehab = Rehab::where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')->get();

        $rekap_108 = array();

        // loop khusus build map 108
        foreach($data as $value) {
            $rehab = false;
            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $rehab = true;
                        break;
                    }
                }
            }

            $kode_108 = $value["kode_108"];

            if(!is_null($kode_108)) {
                $uraian = Sub_sub_rincian_108::select("uraian_sub_sub_rincian")->where('sub_sub_rincian', $kode_108)->first();
            }

            if(!is_null($uraian)) {
                $uraian_108 = $uraian->uraian_sub_sub_rincian;
            } else {
                $uraian_108 = '';
            }

            $found = false;
            foreach($rekap_108 as $key => $value)
            {
                if ($value["kode_108"] == $kode_108) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_108)) {
                    if(!$rehab) {
                        array_push($rekap_108, array(
                            "kode_108" => 0,
                            "uraian_108" => 0,
                            "nilai" => 0
                        ));
                    }
                } else {
                    array_push($rekap_108, array(
                        "kode_108" => $kode_108,
                        "uraian_108" => $uraian_108,
                        "nilai" => 0
                    ));
                }
            }
        }

        foreach($data as $value) {
            $kode_108 = $value["kode_108"];
            $this->total_nilai += $value["harga_total_plus_pajak_saldo"];

            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $kode_108_induk = Kib::select("kode_108")->where("id_aset", $value_rehab["aset_induk_id"])->first();
                        if(!empty($kode_108_induk)) {
                            $kode_108 = $kode_108_induk->kode_108;
                            break;
                        }
                    }
                }
            }

            foreach($rekap_108 as $key => $rekap)
            {
                if($kode_108 != '' || !is_null($kode_108) || $kode_108 != 0) {
                    if ($rekap["kode_108"] == $kode_108) {
                        $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                        $found = true;
                        break;
                    }
                } else {
                    $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                    $found = true;
                    break;
                }
            }
        }

        array_multisort(array_column($rekap_108, 'kode_108'), SORT_ASC, $rekap_108);

        $export = collect($rekap_108);
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
            ["Kode Rekening Permen 108", "Uraian", "Nilai"],
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
