<?php

namespace App\Exports;

use App\Models\Jurnal\Kib_awal;
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

class LaporanInventaris implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
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
        $this->kode_kepemilikan = $args['kode_kepemilikan'];
        $this->tahun_sekarang = date('Y')-1;

        $this->total_harga = 0;
	}

    public function collection()
    {
        ini_set('max_execution_time', 1800);
        $export = array();
        $total_harga = 0;
        $data = Kib_awal::select('kamus_lokasis.nomor_lokasi', 'kib_awals.*')
                ->join('kamus_lokasis', 'kib_awals.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                ->where("kib_awals.nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->orderBy('kib_awals.no_register', 'ASC')
                ->get()
                ->toArray();

        $i= 0;
        $no = '1';
        foreach ($data as $value) {
            $lokasi_asal = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $value["nomor_lokasi"])->first();

            if(!empty($lokasi_asal)) {
                $nama_lokasi = $lokasi_asal->nama_lokasi;
            } else {
                $nama_lokasi = "";
            }

            // if (!empty($)) {
            //     # code...
            // }

            if ($value["rb"] > 0) {
                $keterangan = "Rusak Berat";
            }
            else{
                $keterangan = "Baik";
            }

            $nomor = $no++;

            $export[$i++] =
                array(
                    "no." => $nomor,
                    "no_register" => $value["no_register"].PHP_EOL.$value["kode_64"].PHP_EOL.$value["kode_108"],
                    "nama_barang" => $value["nama_barang"],
                    "merk_alamat" => $value["merk_alamat"].PHP_EOL.$value["tipe"],
                    "no_sertifikat" => $value["no_sertifikat"].PHP_EOL.$value["no_rangka_seri"],
                    "no_mesin" => $value["no_mesin"].PHP_EOL.$value["nopol"],
                    "bahan" => $value["bahan"],
                    "tahun_pengadaan" => $value["tahun_pengadaan"],
                    "konstruksi" => $value["konstruksi"].PHP_EOL.$value["ukuran"],
                    "satuan" => $value["satuan"],
                    "baik" => $keterangan,
                    "saldo_barang" => $value["saldo_barang"],
                    "harga_total_plus_pajak_saldo" => $value["harga_total_plus_pajak_saldo"],
                    "keterangan" => $value["keterangan"],
                );

            $total_harga_tmp = $value["harga_total_plus_pajak_saldo"];
            $total_harga+=$total_harga_tmp;
            $this->total_harga = $total_harga;
        }

        array_multisort(array_column($export, 'no_register'), SORT_ASC, $export);
        $yes = collect($export);
        return $yes;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function headingRow(): int
    {
        return 5;
    }

    public function headings(): array
    {
        $headings = [
            [
                "No.", "No. REGISTER INDUK", "NAMA BARANG", "MERK / ALAMAT & TIPE", "NO SERTIFIKAT & NO RANGKA SERI", "NO MESIN & NOPOL", "BAHAN", "TAHUN PENGADAAN", "KONSTRUKSI & UKURAN", "SATUAN", "KONDISI", "JUMLAH", "NILAI (Rp.)", "KETERANGAN"
            ],
            [
                1,2,3,4,5,6,7,8,9,10,11,12,13,14
            ]
        ];

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $max = $event->sheet->getDelegate()->getHighestRow();

                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                $event->sheet->getPageSetup()->setFitToPage(true);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                $event->sheet->setShowGridlines(false);
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 6);

                $event->sheet->freezePane('O7');

                // footer
                $event->sheet->getHeaderFooter()
                    ->setOddFooter('&L&B '. $this->nama_jurnal.' / '.$this->tahun_sekarang.' / '. $this->nama_lokasi. '&R Halaman &P / &N');
                // end footer

                ////////////////Border
                $event->sheet->getStyle('A5:N5')->applyFromArray([
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
                $event->sheet->getStyle('A6:N6')->applyFromArray([
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
                $event->sheet->getStyle('A7:N'.$max)->applyFromArray([
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
                //////////////endborder

                //////////////heading
                // M6
                $event->sheet->getStyle('M6')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                ///////////////////end heading

                //////////////centering
                $event->sheet->getStyle('E7:L'.$max)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                ////////////// end centering

                ///////////////header
                // a1
                $event->sheet->getDelegate()->mergeCells('A1:N1');
                $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->nama_lokasi . "\n ".$this->tahun_sekarang);
                $event->sheet->getRowDimension('1')->setRowHeight(80);
                $event->sheet->getStyle('A1')->getAlignment()->setWrapText(true);
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
                // a2
                $event->sheet->getDelegate()->setCellValue("A2", "PROVINSI");
                $event->sheet->getStyle('A2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                // a3
                $event->sheet->getDelegate()->setCellValue("A3", "KAB / KOTA");
                $event->sheet->getStyle('A3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                // a4
                $event->sheet->getDelegate()->setCellValue("A4", "LOKASI");
                $event->sheet->getStyle('A4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                // b2
                $event->sheet->getDelegate()->mergeCells('B2:C2');
                $event->sheet->getDelegate()->setCellValue("B2", ": JAWA TIMUR");
                $event->sheet->getStyle('B2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
                // b3
                $event->sheet->getDelegate()->mergeCells('B3:C3');
                $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                $event->sheet->getStyle('B3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
                // b4
                $event->sheet->getDelegate()->mergeCells('B4:C4');
                $event->sheet->getDelegate()->setCellValue("B4", ": ".$this->nama_lokasi);
                $event->sheet->getStyle('B4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
                // i4
                $event->sheet->getDelegate()->mergeCells('I4:M4');
                $event->sheet->getDelegate()->setCellValue("I4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                $event->sheet->getStyle('I4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'font' => [
                        'bold' => true
                    ]
                ]);
                //////////////end header

                ////////////////numbering
                // nomor
                $nomor = 1;
                for($i=7;$i<=$max;$i++){
                    $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                    $event->sheet->getStyle('A'.$i)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                    $nomor++;
                }
                ////////////end numbering

                //////////////width column
                //////////column A
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                //////////column B
                $event->sheet->getColumnDimension('B')->setAutoSize(false);
                $event->sheet->getColumnDimension('B')->setWidth(25);
                $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                //////////column C
                $event->sheet->getColumnDimension('C')->setAutoSize(false);
                $event->sheet->getColumnDimension('C')->setWidth(25);
                $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                //////////column D
                $event->sheet->getColumnDimension('D')->setAutoSize(false);
                $event->sheet->getColumnDimension('D')->setWidth(15);
                $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                /////////////column E
                $event->sheet->getColumnDimension('E')->setAutoSize(false);
                $event->sheet->getColumnDimension('E')->setWidth(15);
                $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                /////////////column F
                $event->sheet->getColumnDimension('F')->setAutoSize(false);
                $event->sheet->getColumnDimension('F')->setWidth(15);
                $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                /////////////column G
                $event->sheet->getColumnDimension('G')->setAutoSize(false);
                $event->sheet->getColumnDimension('G')->setWidth(15);
                $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                /////////////column H
                $event->sheet->getColumnDimension('H')->setAutoSize(false);
                $event->sheet->getColumnDimension('H')->setWidth(15);
                $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                /////////////column I
                $event->sheet->getColumnDimension('I')->setAutoSize(false);
                $event->sheet->getColumnDimension('I')->setWidth(15);
                $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                /////////////column J
                $event->sheet->getColumnDimension('J')->setAutoSize(false);
                $event->sheet->getColumnDimension('J')->setWidth(10);
                $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                /////////////column K
                $event->sheet->getColumnDimension('K')->setAutoSize(false);
                $event->sheet->getColumnDimension('K')->setWidth(10);
                $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                /////////////column L
                $event->sheet->getColumnDimension('L')->setAutoSize(false);
                $event->sheet->getColumnDimension('L')->setWidth(10);
                $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                /////////////column M
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                /////////////column N
                $event->sheet->getColumnDimension('N')->setAutoSize(false);
                $event->sheet->getColumnDimension('N')->setWidth(30);
                $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                // end column

                $date = date('d/m/Y');
                $f1 = $max+1;
                $f2 = $max+3;

                ///////////////border total
                $event->sheet->getStyle('A'.$f1.':N'.$f1)->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->mergeCells('J'.$f1.':L'.$f1);
                $event->sheet->getDelegate()->setCellValue('J'.$f1, "Total");
                $event->sheet->getStyle('J'.$f1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ]
                ]);

                $event->sheet->getDelegate()->setCellValue('M'.$f1 , $this->total_harga);
                $event->sheet->getStyle('M'.$f1)->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                $event->sheet->getStyle('M'.$f1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ]
                ]);

                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                    $event->sheet->getDelegate()->mergeCells('G'.$f2.':N'.$f2);
                    $event->sheet->getStyle('A'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('G'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('G'.$f2, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                        $event->sheet->getDelegate()->setCellValue('G'.$f2, "NIP");
                    }

                    $f2++;
                }
            },
        ];
	}

	public function columnFormats(): array
    {
        return [
            'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
        ];
    }

	public function title(): string
    {
        return 'Saldo Awal';
    }

}
