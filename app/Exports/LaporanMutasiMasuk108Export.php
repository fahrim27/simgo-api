<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Kamus\Kamus_lokasi;
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

class LaporanMutasiMasuk108Export implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

	public $nomor_lokasi;
	public $kode_kepemilikan;
    public $bidang_barang;
    public $jenis_aset;

	function __construct($args){
		$this->nomor_lokasi = $args['nomor_lokasi'];
		$this->nama_lokasi = $args['nama_lokasi'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun_sekarang = date('Y')-1;

        $this->total_jumlah_barang = 0;
        $this->total_harga_satuan = 0;
        $this->total_harga_total = 0;
	}

    public function collection()
    {
        ini_set('max_execution_time', 1800);
        $export = array();

        $total_jumlah_barang = 0;
        $total_harga_satuan = 0;
        $total_harga_total = 0;

        $data = Kib::select('jurnals.dari_lokasi', 'jurnals.no_ba_st', 'jurnals.tgl_ba_st', 'kibs.*')
                ->join('jurnals', 'kibs.no_key', '=', 'jurnals.no_key')
                ->where("kibs.nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->where("kibs.kode_jurnal", "102")
                ->where("kibs.tahun_spj", 2019)
                ->orderBy('kibs.no_register', 'ASC')
                ->get()
                ->toArray();

        $i= 0;
        foreach ($data as $value) {
            $lokasi_asal = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $value["dari_lokasi"])->first();

            if(!empty($lokasi_asal)) {
                $nama_lokasi = $lokasi_asal->nama_lokasi;
            } else {
                $nama_lokasi = "";
            }

            $total_jumlah_barang_tmp = $value['jumlah_barang'];
            $total_jumlah_barang += $total_jumlah_barang_tmp;
            $this->total_jumlah_barang = $total_jumlah_barang;

            $total_harga_satuan_tmp = $value['harga_satuan'];
            $total_harga_satuan += $total_harga_satuan_tmp;
            $this->total_harga_satuan = $total_harga_satuan;

            $total_harga_total_tmp = $value['harga_total_plus_pajak_saldo'];
            $total_harga_total += $total_harga_total_tmp;
            $this->total_harga_total = $total_harga_total;

            $export[$i++] =
                array(
                    "nama_lokasi" => $nama_lokasi,
                    "no_ba_st" => $value["no_ba_st"],
                    "tgl_ba_st" => $value["tgl_ba_st"],
                    "no_register" => $value["no_register"],
                    "kode_108" => $value["kode_108"],
                    "kode_64" => $value["kode_64"],
                    "nama_barang" => $value["nama_barang"],
                    "merk_alamat" => $value["merk_alamat"],
                    "jumlah_barang" => $value["jumlah_barang"],
                    "harga_satuan" => $value["harga_satuan"],
                    "harga_total_plus_pajak_saldo" => $value["harga_total_plus_pajak_saldo"],
                    "nopol" => $value["nopol"]
                );
        }

        array_multisort(array_column($export, 'no_register'), SORT_ASC, $export);
        $yes = collect($export);
        return $yes;
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
            [
                "LOKASI ASAL", "NO BA ST", "TGL BA ST",
                "NO REGISTER", "KODE 108", "KODE 64",
                "NAMA BARANG", "MERK/ALAMAT", "JUMLAH BARANG",
                "HARGA SATUAN", "HARGA TOTAL", "NOPOL"
            ],
            [
                2,3,4,5,6,7,8,9,10,11,12,13
            ]
        ];

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
            	$max = $event->sheet->getDelegate()->getHighestRow();
                ////////// set paper
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                $event->sheet->getPageSetup()->setFitToPage(true);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                $event->sheet->setShowGridlines(false);
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                $event->sheet->freezePane('N5');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A3:M3')->applyFromArray([
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
                $event->sheet->getStyle('A4:M4')->applyFromArray([
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
                $event->sheet->getStyle('A5:M'.$max)->applyFromArray([
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
                $event->sheet->getStyle('A'.($max+1).':M'.($max+1))->applyFromArray([
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
                ->setOddFooter('&L&B '.$this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' / '.$this->tahun_sekarang. '&R &P / &N');
                // end footer

                /////////header
                $event->sheet->getDelegate()->mergeCells('A1:M1');
                $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal . " " . $this->bidang_barang . " " . $this->nama_lokasi ." ".$this->tahun_sekarang);
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
                // end header

                ///////heading
                $event->sheet->getStyle('A3:M3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A3:H3')->getAlignment()->setWrapText(true);
                // end heading

                // format text
                $event->sheet->getStyle('K4:L4')->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );

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

                ///////centering
                // D tgl ba st
                $event->sheet->getStyle('D4:D'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                // j jumlah barang
                $event->sheet->getStyle('J4:J'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                // m nopol
                $event->sheet->getStyle('M4:M'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                //////////end centering

                /////////////column
                // B Lokasi Asal
                $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                // C no ba st
                $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                // E no register
                $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                // F kode 108
                $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(18);
                $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                // G kode 64
                $event->sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(12);
                $event->sheet->getStyle('G3:G'.$max)->getAlignment()->setWrapText(true);
                // H nama barang
                $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                // I merk alamat
                $event->sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('I3:I'.$max)->getAlignment()->setWrapText(true);
                // J jumlah barang
                $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                // M nopol
                $event->sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('M3:M'.$max)->getAlignment()->setWrapText(true);
                // end column

                $date = date('d/m/Y');
                $f1 = $max+1;
                $f2 = $max+3;

                ///////total
                $event->sheet->getStyle('A'.$f1.':M'.$f1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('G'.$f1.':J'.$f1);
                $event->sheet->getDelegate()->setCellValue('G'.$f1, "TOTAL");
                // $event->sheet->getDelegate()->setCellValue('J'.$f1, $this->total_jumlah_barang);
                $event->sheet->getStyle('J'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );
                $event->sheet->getDelegate()->setCellValue('K'.$f1, $this->total_harga_satuan);
                $event->sheet->getStyle('K'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('L'.$f1, $this->total_harga_total);
                $event->sheet->getStyle('L'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );

                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('A'.$f2.':D'.$f2);
                    $event->sheet->getDelegate()->mergeCells('E'.$f2.':H'.$f2);
                    $event->sheet->getDelegate()->mergeCells('I'.$f2.':L'.$f2);
                    $event->sheet->getStyle('A'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('I'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('I'.$f2, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                        $event->sheet->getDelegate()->setCellValue('I'.$f2, "NIP");
                    }

                    $f2++;
                }
            },
        ];
	}

	public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

	public function title(): string
    {
        return 'Data Mutasi Masuk';
    }

}
