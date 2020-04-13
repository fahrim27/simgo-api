<?php

namespace App\Exports;

use App\Models\Jurnal\Rincian_keluar;
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

class LaporanMutasiKeluarBarangExport implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

	public $nomor_lokasi;
	public $kode_kepemilikan;

	function __construct($args){
		$this->nomor_lokasi = $args['nomor_lokasi'];
		$this->nama_lokasi = $args['nama_lokasi'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun_sekarang = date('Y')-1;

        $this->kode_kepemilikan = $args['kode_kepemilikan'];

        $this->total_harga_awal = 0;
        $this->total_nilai_keluar = 0;
        $this->total_harga_akhir = 0;
	}

    public function collection()
    {
        ini_set('max_execution_time', 1800);

        $data = Rincian_keluar::select('rincian_keluars.id_aset','kamus_rekenings.uraian_64','kibs.merk_alamat','kibs.no_sertifikat','kibs.no_rangka_seri','kibs.no_mesin','kibs.nopol','kibs.bahan','kibs.konstruksi','kibs.satuan','kib_awals.saldo_barang','kib_awals.harga_total_plus_pajak_saldo','rincian_keluars.jumlah_barang','rincian_keluars.nilai_keluar','kibs.saldo_barang as saldo_barang_baru','kibs.harga_total_plus_pajak_saldo as harga_total_plus_pajak_saldo_baru','kibs.tahun_pengadaan','kibs.keterangan')
                ->join('kamus_rekenings', 'rincian_keluars.kode_64', '=', 'kamus_rekenings.kode_64')
                ->join('kib_awals','rincian_keluars.id_aset','=','kib_awals.id_aset')
                ->join('kibs','rincian_keluars.id_aset','=','kibs.id_aset')
                ->where("rincian_keluars.nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->orderBy('rincian_keluars.no_register', 'ASC')
                ->get()
                ->toArray();

        $asets = array();

        $total_harga_awal = 0;
        $total_nilai_keluar = 0;
        $total_harga_akhir = 0;

        foreach ($data as $value) {
            $aset = $value;

            $no_setifikat_rangka = (string)$value['no_sertifikat'].''.PHP_EOL.''.$value['no_rangka_seri'];
            $aset['no_sertifikat'] = $no_setifikat_rangka;

            $no_mesin_pol = (string)$value['no_mesin'].''.PHP_EOL.''.$value['nopol'];
            $aset['no_mesin'] = $no_mesin_pol;

            $bahan_konstruksi = (string)$value['bahan'].''.PHP_EOL.''.$value['konstruksi'];
            $aset['bahan']= $bahan_konstruksi;

            $total_harga_awal_tmp = $value["harga_total_plus_pajak_saldo"];
            $total_harga_awal+=$total_harga_awal_tmp;
            $this->total_harga_awal = $total_harga_awal;

            $total_nilai_keluar_tmp = $value["nilai_keluar"];
            $total_nilai_keluar+=$total_nilai_keluar_tmp;
            $this->total_nilai_keluar = $total_nilai_keluar;

            $total_harga_akhir_tmp = $value["harga_total_plus_pajak_saldo_baru"];
            $total_harga_akhir+=$total_harga_akhir_tmp;
            $this->total_harga_akhir = $total_harga_akhir;

            array_push($asets, $aset);
        }

        $export = collect($asets);
        return $export;
    }

    public function startCell(): string
    {
        return 'B6';
    }

    public function headingRow(): int
    {
        return 6;
    }

    public function headings(): array
    {
        $heading = [
            [
                'Kode Barang Kode Rinc. Objek No. Regs. Induk',
                'Nama Barang Merk / Alamat Tipe','',
                'No. Sertifikat No. Pabrik No. Rangka','',
                'No. Mesin No.Polisi','',
                'Bahan / Konstruksi (P/S/D)','',
                'Satuan / Asal-Usul',
                'Jumlah Awal','',
                'Mutasi','',
                'Jumlah Akhir','',
                'Tahun Pengadaan',
                'Ket.'
            ],
            [
                '',
                '','',
                '','',
                '','',
                '','',
                '',
                'Barang','Harga',
                'Berkurang','',
                'Barang','Harga',
                '',
                ''
            ],
            [
                '',
                '','',
                '','',
                '','',
                '','',
                '',
                '','',
                'Bertambah','',
                '','',
                '',
                ''
            ],
            [
                '',
                '','',
                '','',
                '','',
                '','',
                '',
                '','',
                'Barang','Harga',
                '','',
                '',
                ''
            ],
            [
                '2',
                '3','',
                '4','',
                '5','',
                '6','',
                '7',
                '8','9',
                '10','11',
                '12','13',
                '14',
                '15'
            ]
        ];

        return $heading;
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
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(6, 10);
                $event->sheet->setShowGridlines(false);

                $event->sheet->freezePane('T11');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A6:S9')->applyFromArray([
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
                $event->sheet->getStyle('A10:S10')->applyFromArray([
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
                $event->sheet->getStyle('A11:S'.$max)->applyFromArray([
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
                $event->sheet->getStyle('A'.($max+1).':S'.($max+1))->applyFromArray([
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
                    'font'=>[
                        'bold'=>true,
                    ]
                ]);
                // end border

                // footer
                $event->sheet->getHeaderFooter()
                ->setOddFooter('&L&B '.$this->nama_jurnal.' '. $this->nomor_lokasi.' / '.$this->tahun_sekarang. '&R &P / &N');
                // end footer

                /////////header
                $event->sheet->getDelegate()->mergeCells('A1:B1');
                $event->sheet->getDelegate()->setCellValue("A1", "Provinsi");
                $event->sheet->getDelegate()->mergeCells('A2:B2');
                $event->sheet->getDelegate()->setCellValue("A2", "Kab /");
                $event->sheet->getDelegate()->mergeCells('A3:B3');
                $event->sheet->getDelegate()->setCellValue("A3", "Lokasi");
                $event->sheet->getStyle('A1:A3')->applyFromArray([
                    'alignment' => [
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('C1:D1');
                $event->sheet->getDelegate()->setCellValue("C1", ": Jawa Timur");
                $event->sheet->getDelegate()->mergeCells('C2:D2');
                $event->sheet->getDelegate()->setCellValue("C2", ": Kabupaten Mojokerto");
                $event->sheet->getDelegate()->mergeCells('C3:E3');
                $event->sheet->getDelegate()->setCellValue("C3", ": ".$this->nama_lokasi);
                $event->sheet->getStyle('C1')->applyFromArray([
                    'alignment' => [
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('A4:S4');
                $event->sheet->getDelegate()->setCellValue("A4", "Laporan ".$this->nama_jurnal ." ".$this->tahun_sekarang);
	            $event->sheet->getStyle('A4')->applyFromArray([
                    'alignment' => [
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				    ],
                    'font' => [
                        'bold' => true,
                        'size' => 18
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('A5:B5');
                $event->sheet->getDelegate()->setCellValue("A5", "Kepemilikan");
                $event->sheet->getStyle('A5')->applyFromArray([
                    'alignment' => [
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('C5:D5');
                $event->sheet->getDelegate()->setCellValue("C5", ": ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                $event->sheet->getStyle('C5')->applyFromArray([
                    'alignment' => [
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				    ]
                ]);
                // end header

                ///////heading
                $event->sheet->getStyle('A6:S10')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A6:S10')->getAlignment()->setWrapText(true);
                // end heading

                //////////numbering
                $event->sheet->getDelegate()->mergeCells('A6:A9');
                $event->sheet->getDelegate()->setCellValue("A6", "No.");
                $event->sheet->getDelegate()->setCellValue("A10", "1");
                $event->sheet->getStyle('A9:A'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $nomor = 1;
                for($i=11;$i<=$max;$i++){
                    $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                    $nomor++;
                }
                ///////////////end numbering

                ///////centering
                // no sertifikat - jumlah awal barang
                $event->sheet->getStyle('E11:L'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('N11:N'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('P11:P'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('R11:R'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                //////////end centering

                // merging column
                //////////////heading
                //b6 no regs
                $event->sheet->getDelegate()->mergeCells('B6:B9');
                //C6 nama barang
                $event->sheet->getDelegate()->mergeCells('C6:D9');
                //E6 no sertifikat
                $event->sheet->getDelegate()->mergeCells('E6:F9');
                //E6 no pol
                $event->sheet->getDelegate()->mergeCells('G6:H9');
                //I6 bahan
                $event->sheet->getDelegate()->mergeCells('I6:J9');
                //I6 satuan
                $event->sheet->getDelegate()->mergeCells('K6:K9');
                // L6 jumlah awal
                $event->sheet->getDelegate()->mergeCells('L6:M6');
                // L7 Barang
                $event->sheet->getDelegate()->mergeCells('L7:L9');
                // M7 Harga
                $event->sheet->getDelegate()->mergeCells('M7:M9');
                // N6 mutasi
                $event->sheet->getDelegate()->mergeCells('N6:O6');
                // N7 Berkurang
                $event->sheet->getDelegate()->mergeCells('N7:O7');
                // N8 Bertambah
                $event->sheet->getDelegate()->mergeCells('N8:O8');
                // P6 jumlah akhir
                $event->sheet->getDelegate()->mergeCells('P6:Q6');
                // P7 Barang
                $event->sheet->getDelegate()->mergeCells('P7:P9');
                // Q7 Harga
                $event->sheet->getDelegate()->mergeCells('Q7:Q9');
                // R6 Tahun pengadaan
                $event->sheet->getDelegate()->mergeCells('R6:R9');
                // S6 Keterangan
                $event->sheet->getDelegate()->mergeCells('S6:S9');
                ///////end heading
                //////////nomor
                $event->sheet->getDelegate()->mergeCells('C10:D10');
                $event->sheet->getDelegate()->mergeCells('E10:F10');
                $event->sheet->getDelegate()->mergeCells('G10:H10');
                $event->sheet->getDelegate()->mergeCells('I10:J10');
                //////end nomor
                //////////end merging

                //////////format nomor
                $event->sheet->getStyle('M10:S10')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);


                ////////////perulangan untuk replace isi
                for($i=11;$i<=$max;$i++){
                    $event->sheet->getDelegate()->mergeCells('E'.$i.':F'.$i);
                    $event->sheet->getDelegate()->mergeCells('G'.$i.':H'.$i);
                    $event->sheet->getDelegate()->mergeCells('I'.$i.':J'.$i);
                }


                /////////column
                // B no register
                $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('B6:B'.$max)->getAlignment()->setWrapText(true);
                // C nama barang
                $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('C6:C'.$max)->getAlignment()->setWrapText(true);
                // D nama barang
                $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('D6:D'.$max)->getAlignment()->setWrapText(true);
                // E no sertifikat
                $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('E6:E'.$max)->getAlignment()->setWrapText(true);
                // F no sertifikat
                $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(5);
                $event->sheet->getStyle('F6:F'.$max)->getAlignment()->setWrapText(true);
                // G no mesin
                $event->sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('G6:G'.$max)->getAlignment()->setWrapText(true);
                // H no mesin
                $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(5);
                $event->sheet->getStyle('H6:H'.$max)->getAlignment()->setWrapText(true);
                // I bahan
                $event->sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('I6:I'.$max)->getAlignment()->setWrapText(true);
                // J bahan
                $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(5);
                $event->sheet->getStyle('J6:J'.$max)->getAlignment()->setWrapText(true);
                // K satuan
                $event->sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('K6:K'.$max)->getAlignment()->setWrapText(true);
                // L barang
                $event->sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('L6:L'.$max)->getAlignment()->setWrapText(true);
                // M harga
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getStyle('M6:M'.$max)->getAlignment()->setWrapText(true);
                // N barang
                $event->sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('N6:N'.$max)->getAlignment()->setWrapText(true);
                // O Harga
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getStyle('O6:O'.$max)->getAlignment()->setWrapText(true);
                // P barang
                $event->sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('P6:P'.$max)->getAlignment()->setWrapText(true);
                // Q Harga
                $event->sheet->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getStyle('Q6:Q'.$max)->getAlignment()->setWrapText(true);
                // R tahun pengadaan
                $event->sheet->getColumnDimension('R')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('R6:R'.$max)->getAlignment()->setWrapText(true);
                // S Keterangan
                $event->sheet->getColumnDimension('S')->setAutoSize(false)->setWidth(30);
                $event->sheet->getStyle('S6:S'.$max)->getAlignment()->setWrapText(true);

                ////////end column

                $date = date('d/m/Y');
                $f1 = $max+1;
                $f2 = $max+3;

                ///////////total
                $event->sheet->getStyle('A'.$f1.':S'.$f1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('I'.$f1.':L'.$f1);
                $event->sheet->getDelegate()->setCellValue('I'.$f1, "TOTAL");
                $event->sheet->getDelegate()->setCellValue('M'.$f1, $this->total_harga_awal);
                $event->sheet->getStyle('M'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );

                $event->sheet->getDelegate()->setCellValue('O'.$f1, $this->total_nilai_keluar);
                $event->sheet->getStyle('O'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('Q'.$f1, $this->total_harga_akhir);
                $event->sheet->getStyle('Q'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                ///////////end total

                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('B'.$f2.':H'.$f2);
                    $event->sheet->getDelegate()->mergeCells('H'.$f2.':L'.$f2);
                    $event->sheet->getDelegate()->mergeCells('M'.$f2.':S'.$f2);
                    $event->sheet->getStyle('B'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('M'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('B'.$f2, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('M'.$f2, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('B'.$f2, "NIP");
                        $event->sheet->getDelegate()->setCellValue('M'.$f2, "NIP");
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
            'O' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'Q' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

	public function title(): string
    {
        return 'Data Penyusutan';
    }

}
