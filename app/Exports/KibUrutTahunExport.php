<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
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

class KibUrutTahunExport implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $nomor_lokasi;
	public $kode_kepemilikan;
	public $nama_lokasi;
	public $bidang_barang;
    public $nama_jurnal;

	function __construct($args){
		$this->nomor_lokasi = $args['nomor_lokasi'];
		$this->kode_kepemilikan = $args['kode_kepemilikan'];
		$this->nama_lokasi = $args['nama_lokasi'];
		$this->bidang_barang = $args['bidang_barang'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun_sekarang = date('Y')-1;

        $this->total_harga = 0;
	}

    public function collection()
    {
        if($this->bidang_barang == "A") {
            $data = Kib::select('kode_108', 'no_register', 'nama_barang', 'merk_alamat', 'luas_tanah', 'tahun_pengadaan', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "B") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.2%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "C") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.3%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "D") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','panjang_tanah','lebar_tanah','luas_tanah','no_imb', 'tgl_imb', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.4%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "E") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.5%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')->get()
                ->toArray();
        } else if($this->bidang_barang == "F") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('kode_108', 'like', '1.3.6%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "G") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.5.3%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "RB") {
            if($this->nomor_lokasi == '12.01.35.16.445.00001.00001') {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('kode_108', 'like', '1.5.4%')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
            } else {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'perolehan_pemda', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('kode_108', 'like', '1.5.4%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
            }

        }
        $asets = array();

        $total_harga = 0;
        foreach ($data as $value) {
            $aset = $value;
            $no_register = (string)$value['no_register'] . ' ';
            $aset['no_register'] = $no_register;

            $total_harga_tmp = $value["harga_total_plus_pajak_saldo"];
            $total_harga+=$total_harga_tmp;
            $this->total_harga = $total_harga;

            array_push($asets, $aset);
        }

        $export = collect($asets);
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

        if($this->bidang_barang == "A") {
            $heading = [
                ['KODE 108', 'NO REGISTER', 'NAMA BARANG', 'MERK/ALAMAT', 'LUAS TANAH', 'TAHUN PENGADAAN', 'TGL SERTIFIKAT', 'NO SERTIFIKAT', 'PENGGUNAAN', 'HARGA TOTAL', 'KETERANGAN'],
                [2,3,4,5,6,7,8,9,10,11,12]
            ];
        } else if($this->bidang_barang == "B") {
            $heading =[
                ['KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','UKURAN','CC','BAHAN','TAHUN PENGADAAN','BAIK','KB', 'RB', 'NO RANGKA SERI', 'NO MESIN', 'NOPOL', 'NO BPKB', 'JUMLAH BARANG', 'SATUAN', 'HARGA TOTAL', 'KETERANGAN'],
                [
                    2,3,4,5,6,7,8,9,10,
                    11,12,13,14,15,16,17,18,19,20
                ]
            ];
        } else if($this->bidang_barang == "C") {
            $heading = [
                ['KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','BAIK', 'KB', 'RB','KONSTRUKSI','BAHAN','JUMLAH LANTAI','LUAS LANTAI','NO IMB', 'TGL IMB', 'LUAS BANGUNAN', 'STATUS TANAH', 'TAHUN PENGADAAN', 'HARGA TOTAL', 'KETERANGAN'],
                [
                    2,3,4,5,6,7,8,9,10,
                    11,12,13,14,15,16,17,18,19
                ]
            ];
        } else if($this->bidang_barang == "D") {
            $heading = [
                ['KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','BAIK', 'KB', 'RB','KONSTRUKSI','BAHAN','PANJANG','LEBAR','LUAS','NO IMB', 'TGL IMB', 'STATUS TANAH', 'TAHUN PENGADAAN', 'HARGA TOTAL', 'KETERANGAN'],
                [
                    2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
                ]
            ];
        } else if($this->bidang_barang == "E") {
            $heading = [
                [
                    'KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','BAIK', 'KB', 'RB','KONSTRUKSI','BAHAN','JUMLAH LANTAI','LUAS LANTAI','NO IMB', 'TGL IMB', 'LUAS BANGUNAN', 'STATUS TANAH', 'TAHUN PENGADAAN', 'HARGA TOTAL', 'KETERANGAN'
                ],
                [
                    2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
                ]
            ];
        } else if($this->bidang_barang == "F") {
            $heading = [
                ['KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','BAIK', 'KB', 'RB','KONSTRUKSI','BAHAN','JUMLAH LANTAI','LUAS LANTAI','NO IMB', 'TGL IMB', 'LUAS BANGUNAN', 'STATUS TANAH', 'TAHUN PENGADAAN', 'HARGA TOTAL', 'KETERANGAN'],
                [
                    2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
                ]
            ];
        } else if($this->bidang_barang == "G") {
            $heading = [
                [
                'KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','BAIK', 'KB', 'RB','KONSTRUKSI','BAHAN','JUMLAH LANTAI','LUAS LANTAI','NO IMB', 'TGL IMB', 'LUAS BANGUNAN', 'STATUS TANAH', 'TAHUN PENGADAAN', 'HARGA TOTAL', 'KETERANGAN'
                ],
                [
                        2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
                ]
            ];
        } else if($this->bidang_barang == "RB") {
            $heading = [
                ['KODE 108','NO REGISTER','NAMA BARANG','MERK/ALAMAT','UKURAN','CC','BAHAN','TAHUN PENGADAAN','BAIK','KB', 'RB', 'NO RANGKA SERI', 'NO MESIN', 'NOPOL', 'NO BPKB', 'JUMLAH BARANG','', 'SATUAN', 'HARGA TOTAL', 'KETERANGAN'],
                [
                    2,3,4,5,6,7,8,9,10,
                    11,12,13,14,15,16,17,'',18,19,20
                ]

            ];
        } else {
            $heading = [
                ['KODE 108', 'NO REGISTER', 'NAMA BARANG', 'MERK/ALAMAT', 'LUAS TANAH', 'TAHUN PENGADAAN', 'TGL SERTIFIKAT', 'NO SERTIFIKAT', 'PENGGUNAAN', 'HARGA TOTAL', 'KETERANGAN'],
                [2,3,4,5,6,7,8,9,10,11,12]
            ];
        }

        return $heading;
    }

    public function registerEvents(): array
    {
        if($this->bidang_barang == "A") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);

                    $event->sheet->freezePane('M5');

                    // end set paper

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' / '.$this->tahun_sekarang.' / '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A3:L4')->applyFromArray([
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
                    $event->sheet->getStyle('A5:L'.$max)->applyFromArray([
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

                    // format text
                    $event->sheet->getStyle('J4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // end format text

                    //////////////centering
                    $event->sheet->getStyle('F5:J'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    //////end centering

                    ////////////////numbering
                    // A3
                    $event->sheet->getDelegate()->setCellValue("A3", "No.");
                    $event->sheet->getStyle('A3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A4
                    $event->sheet->getDelegate()->setCellValue("A4", "1");
                    // nomor
                    $nomor = 1;
                    for($i=5;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////column width
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(18);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(25);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    //////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    //////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    //////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(15);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    //////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    //////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(15);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    //////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    //////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(true);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    //////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(30);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    ///////////end column


                    /////header
                    $event->sheet->getStyle('A3:L3')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('A1:L1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi ." ".$this->tahun_sekarang);
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    /////end header

                    ///////////////border total
                    $f2 = $max+1;
                    $event->sheet->getStyle('A'.$f2.':L'.$f2)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('J'.$f2, "Total");
                    $event->sheet->getStyle('J'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('K'.$f2 , $this->total_harga);
                    $event->sheet->getStyle('K'.$f2)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('K'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    ////end total

                    $date = date('d/m/Y');
                    $f1 = $max+3;
                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f1.':F'.$f1);
                        $event->sheet->getDelegate()->mergeCells('G'.$f1.':L'.$f1);
                        $event->sheet->getStyle('A'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('G'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('G'.$f1, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                            $event->sheet->getDelegate()->setCellValue('G'.$f1, "NIP");
                        }

                        $f1++;
                    }
                },
            ];
        } else if($this->bidang_barang == "B"){
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);

                    $event->sheet->freezePane('U5');

                    // end set paper

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A3:T4')->applyFromArray([
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
                    $event->sheet->getStyle('A5:T'.$max)->applyFromArray([
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

                    // format text
                    $event->sheet->getStyle('S4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // end format text

                    //////////////centering
                    $event->sheet->getStyle('F5:R'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    //////end centering

                    ////////////////numbering
                    // A3
                    $event->sheet->getDelegate()->setCellValue("A3", "No.");
                    $event->sheet->getStyle('A3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A4
                    $event->sheet->getDelegate()->setCellValue("A4", "1");
                    // nomor
                    $nomor = 1;
                    for($i=5;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////column width
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(18);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(25);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    //////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    //////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    //////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(10);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    //////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    //////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(20);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    //////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(10);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    //////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(10);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    //////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(10);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    //////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(15);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    //////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(15);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    //////////column O
                    $event->sheet->getColumnDimension('O')->setAutoSize(false);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    //////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(false);
                    $event->sheet->getColumnDimension('P')->setWidth(15);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);
                    //////////column Q
                    $event->sheet->getColumnDimension('Q')->setAutoSize(false);
                    $event->sheet->getColumnDimension('Q')->setWidth(15);
                    $event->sheet->getStyle('Q1:Q'.$max)->getAlignment()->setWrapText(true);
                    //////////column R
                    $event->sheet->getColumnDimension('R')->setAutoSize(false);
                    $event->sheet->getColumnDimension('R')->setWidth(15);
                    $event->sheet->getStyle('R1:R'.$max)->getAlignment()->setWrapText(true);
                    //////////column S
                    $event->sheet->getColumnDimension('S')->setAutoSize(true);
                    $event->sheet->getStyle('S1:S'.$max)->getAlignment()->setWrapText(true);
                    //////////column T
                    $event->sheet->getColumnDimension('T')->setAutoSize(false)->setWidth(30);
                    $event->sheet->getStyle('T1:T'.$max)->getAlignment()->setWrapText(true);
                    ///////////end column


                    /////header
                    $event->sheet->getStyle('A3:T3')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('A1:T1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi ." ".$this->tahun_sekarang);
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    /////end header

                    ///////////////border total
                    $f2 = $max+1;
                    $event->sheet->getStyle('A'.$f2.':T'.$f2)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells("Q".$f2.":R".$f2);
                    $event->sheet->getDelegate()->setCellValue('Q'.$f2, "Total");
                    $event->sheet->getStyle('Q'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('S'.$f2 , $this->total_harga);
                    $event->sheet->getStyle('S'.$f2)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('S'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    ////end total

                    $date = date('d/m/Y');
                    $f1 = $max+3;
                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f1.':G'.$f1);
                        $event->sheet->getDelegate()->mergeCells('H'.$f1.':L'.$f1);
                        $event->sheet->getDelegate()->mergeCells('M'.$f1.':T'.$f1);
                        $event->sheet->getStyle('A'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('M'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('M'.$f1, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                            $event->sheet->getDelegate()->setCellValue('M'.$f1, "NIP");
                        }

                        $f1++;
                    }
                },
            ];
        } else if($this->bidang_barang == "RB"){
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);

                    $event->sheet->freezePane('V5');

                    // end set paper

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A3:U4')->applyFromArray([
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
                    $event->sheet->getStyle('A5:U'.$max)->applyFromArray([
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

                    // format text
                    $event->sheet->getStyle('T4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // end format text

                    //////////////centering
                    $event->sheet->getStyle('F5:S'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    //////end centering

                    ////////////////numbering
                    // A3
                    $event->sheet->getDelegate()->setCellValue("A3", "No.");
                    $event->sheet->getStyle('A3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A4
                    $event->sheet->getDelegate()->setCellValue("A4", "1");
                    // nomor
                    $nomor = 1;
                    for($i=5;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    // merger
                    for($i = 3;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('Q'.$i.':R'.$i);
                    }

                    ////////column width
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(18);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(25);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    //////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    //////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    //////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(10);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    //////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    //////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(20);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    //////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(10);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    //////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(10);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    //////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(10);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    //////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(15);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    //////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(15);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    //////////column O
                    $event->sheet->getColumnDimension('O')->setAutoSize(false);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    //////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(false);
                    $event->sheet->getColumnDimension('P')->setWidth(15);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);
                    //////////column Q
                    $event->sheet->getColumnDimension('Q')->setAutoSize(false);
                    $event->sheet->getColumnDimension('Q')->setWidth(10);
                    $event->sheet->getStyle('Q1:Q'.$max)->getAlignment()->setWrapText(true);
                    //////////column R
                    $event->sheet->getColumnDimension('R')->setAutoSize(false);
                    $event->sheet->getColumnDimension('R')->setWidth(5);
                    $event->sheet->getStyle('R1:R'.$max)->getAlignment()->setWrapText(true);
                    //////////column S
                    $event->sheet->getColumnDimension('S')->setAutoSize(false);
                    $event->sheet->getColumnDimension('S')->setWidth(15);
                    $event->sheet->getStyle('S1:S'.$max)->getAlignment()->setWrapText(true);
                    //////////column T
                    $event->sheet->getColumnDimension('T')->setAutoSize(true);
                    $event->sheet->getStyle('T1:T'.$max)->getAlignment()->setWrapText(true);
                    //////////column T
                    $event->sheet->getColumnDimension('U')->setAutoSize(false)->setWidth(30);
                    $event->sheet->getStyle('U1:U'.$max)->getAlignment()->setWrapText(true);
                    ///////////end column


                    /////header
                    $event->sheet->getStyle('A3:U3')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('A1:U1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi ." ".$this->tahun_sekarang);
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    /////end header

                    ///////////////border total
                    $f2 = $max+1;
                    $event->sheet->getStyle('A'.$f2.':U'.$f2)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells("Q".$f2.":S".$f2);
                    $event->sheet->getDelegate()->setCellValue('Q'.$f2, "Total");
                    $event->sheet->getStyle('Q'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('T'.$f2 , $this->total_harga);
                    $event->sheet->getStyle('T'.$f2)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('T'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    ////end total

                    $date = date('d/m/Y');
                    $f1 = $max+3;
                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f1.':G'.$f1);
                        $event->sheet->getDelegate()->mergeCells('H'.$f1.':L'.$f1);
                        $event->sheet->getDelegate()->mergeCells('M'.$f1.':T'.$f1);
                        $event->sheet->getStyle('A'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('M'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('M'.$f1, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                            $event->sheet->getDelegate()->setCellValue('M'.$f1, "NIP");
                        }

                        $f1++;
                    }
                },
            ];
        } else {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);

                    $event->sheet->freezePane('T5');

                    // end set paper

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A3:S4')->applyFromArray([
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
                    $event->sheet->getStyle('A5:S'.$max)->applyFromArray([
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

                    // format text
                    $event->sheet->getStyle('R4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // end format text

                    //////////////centering
                    $event->sheet->getStyle('F5:Q'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    //////end centering

                    ////////////////numbering
                    // A3
                    $event->sheet->getDelegate()->setCellValue("A3", "No.");
                    $event->sheet->getStyle('A3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A4
                    $event->sheet->getDelegate()->setCellValue("A4", "1");
                    // nomor
                    $nomor = 1;
                    for($i=5;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////column width
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(18);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(25);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    //////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    //////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(10);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    //////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(10);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    //////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(10);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    //////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(15);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    //////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    //////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(15);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    //////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(15);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    //////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(15);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    //////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(15);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    //////////column O
                    $event->sheet->getColumnDimension('O')->setAutoSize(false);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    //////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(false);
                    $event->sheet->getColumnDimension('P')->setWidth(15);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);
                    //////////column Q
                    $event->sheet->getColumnDimension('Q')->setAutoSize(false);
                    $event->sheet->getColumnDimension('Q')->setWidth(20);
                    $event->sheet->getStyle('Q1:Q'.$max)->getAlignment()->setWrapText(true);
                    //////////column R
                    $event->sheet->getColumnDimension('R')->setAutoSize(true);
                    $event->sheet->getStyle('R1:R'.$max)->getAlignment()->setWrapText(true);
                    //////////column S
                    $event->sheet->getColumnDimension('S')->setAutoSize(false)->setWidth(30);
                    $event->sheet->getStyle('S1:S'.$max)->getAlignment()->setWrapText(true);
                    ///////////end column


                    /////header
                    $event->sheet->getStyle('A3:S3')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('A1:S1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi ." ".$this->tahun_sekarang);
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    /////end header

                    ///////////////border total
                    $f2 = $max+1;
                    $event->sheet->getStyle('A'.$f2.':S'.$f2)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('Q'.$f2, "Total");
                    $event->sheet->getStyle('Q'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('R'.$f2 , $this->total_harga);
                    $event->sheet->getStyle('R'.$f2)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('R'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    ////end total

                    $date = date('d/m/Y');
                    $f1 = $max+3;
                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f1.':G'.$f1);
                        $event->sheet->getDelegate()->mergeCells('H'.$f1.':L'.$f1);
                        $event->sheet->getDelegate()->mergeCells('M'.$f1.':S'.$f1);
                        $event->sheet->getStyle('A'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('M'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('M'.$f1, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                            $event->sheet->getDelegate()->setCellValue('M'.$f1, "NIP");
                        }

                        $f1++;
                    }
                },
            ];
        }

    }

    public function columnFormats(): array
    {
        if($this->bidang_barang == "A") {
            return [
                'C' => NumberFormat::FORMAT_TEXT,
                'K' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "B") {
            return [
                'C' => NumberFormat::FORMAT_TEXT,
                'S' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "RB"){
            return [
                'C' => NumberFormat::FORMAT_TEXT,
                'T' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }
        else{
            return [
                'C' => NumberFormat::FORMAT_TEXT,
                'R' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }
    }

    public function title(): string
    {
        return 'Data';
    }
}
