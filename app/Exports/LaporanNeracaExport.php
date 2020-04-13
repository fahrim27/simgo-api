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
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter;

class LaporanNeracaExport implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $nomor_lokasi;
	public $kode_kepemilikan;
	public $nama_lokasi;
	public $bidang_barang;
    public $nama_jurnal;
    public $kode_108;
    // public $uraian_sub_rincian;

	function __construct($args){
		$this->kode_kepemilikan = $args['kode_kepemilikan'];
		$this->bidang_barang = $args['bidang_barang'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->kode_108 = $args['kode_108'];
        $this->uraian_sub_rincian = $args['uraian_sub_rincian'];
        $this->tahun_sekarang = date('Y')-1;

        $this->total_harga = 0;
	}

    public function collection()
    {
        if($this->bidang_barang == "A") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.status_tanah', 'kibs.tgl_sertifikat', 'kibs.no_sertifikat', 'kibs.luas_tanah', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "B") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.tipe', 'kibs.ukuran', 'kibs.cc', 'kibs.bahan', 'kibs.tahun_pengadaan', 'kibs.no_rangka_seri', 'kibs.no_mesin', 'kibs.nopol','kibs.no_bpkb','kibs.saldo_barang','kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "C") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.panjang', 'kibs.lebar', 'kibs.luas_bangunan', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "D") {
            $data = Kib::select('kibs.kode_64','kibs.kode_108','kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.panjang', 'kibs.lebar', 'kibs.luas_bangunan', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "E") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "F") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.panjang', 'kibs.lebar', 'kibs.luas_bangunan', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "G") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else{
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.status_tanah', 'kibs.tgl_sertifikat', 'kibs.no_sertifikat', 'kibs.luas_tanah', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $this->kode_108.'%')
                ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();

        }

        $asets = array();
        $total_harga = 0;
        foreach ($data as $value) {
            $aset = $value;
            $no_register = (string)$value['no_register'] . ' ';

            if($this->bidang_barang == "B"){
                $merk_tipe = (string)$value['merk_alamat'].''.PHP_EOL.''.$value['tipe'];
                $aset['merk_alamat'] = $merk_tipe;

                $ukuran_cc = (string)$value['ukuran'].''.PHP_EOL.''.$value['cc'];
                $aset['ukuran'] = $ukuran_cc;

                $no_seri_mesin = (string)$value['no_rangka_seri'].''.PHP_EOL.''.$value['no_mesin'];
                $aset['no_rangka_seri'] = $no_seri_mesin;

                $no_pol_bpkb = (string)$value['nopol'].''.PHP_EOL.''.$value['no_bpkb'];
                $aset['nopol'] = $no_pol_bpkb;
            } else if($this->bidang_barang == "C"){
                $panjang_lebar = (string)$value['panjang'].''.PHP_EOL.''.$value['lebar'];
                $aset['panjang'] = $panjang_lebar;
            } else if($this->bidang_barang == "D"){
                $new_no_reg = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'].''.PHP_EOL.''.$value['no_register'];
                $aset['kode_64'] = $new_no_reg;

                $panjang_lebar = (string)$value['panjang'].''.PHP_EOL.''.$value['lebar'];
                $aset['panjang'] = $panjang_lebar;
            } else if($this->bidang_barang == "F"){
                $panjang_lebar = (string)$value['panjang'].''.PHP_EOL.''.$value['lebar'];
                $aset['panjang'] = $panjang_lebar;
            }

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
        return 'B4';
    }

    public function headingRow(): int
    {
        return 4;
    }

    public function headings(): array
    {

        if($this->bidang_barang == "A") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','Jenis / Nama Barang',
                    'Letak / Lokasi / Alamat',
                    'Status Tanah', '', '',
                    'Luas (M2)', 'Konstruksi',
                    'Tahun Pengadaan & Tahun Perolehan', 'Jumlah Barang & Kondisi',
                    'Harga', 'Keterangan'
                ],
                [
                    '','',
                    '',
                    'Status','Tanggal','Nomor',
                    '','',
                    '','',
                    '','',
                ],
                [
                    2,3,
                    4,
                    5,6,7,
                    8,9,
                    10,11,
                    12,13
                ]
            ];
        } else if($this->bidang_barang == "B") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk',
                    'Jenis / Nama', 'Merk / Tipe', '',
                    'Ukuran / CC','',
                    'Bahan', 'Tahun Pengadaan & Tahun Perolehan',
                    'No. Seri / Rangka & No. Mesin','',
                    'No. Polisi & No. BPKB', '',
                    'Jumlah Barang & Kondisi','Harga (Rp.)','Keterangan'
                ],
                [
                    2,
                    3,4,'',
                    5,'',
                    6,7,
                    8,'',
                    9,'',
                    10,11,12
                ]
            ];
        } else if($this->bidang_barang == "C") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','Jenis / Nama Barang',
                    'Letak / Lokasi / Alamat','Panjang (M) & Lebar (M)','',
                    'Luas (M2)', 'Konstruksi',
                    'Tahun Pengadaan & Tahun Perolehan', 'Jumlah Barang & Kondisi',
                    'Harga (Rp.)','Keterangan'
                ],
                [
                    2,3,
                    4,5,'',
                    6,7,
                    8,9,
                    10,11
                ]
            ];
        } else if($this->bidang_barang == "D") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','','',
                    'Jenis / Nama Barang',
                    'Letak / Lokasi / Alamat','Panjang (M) & Lebar (M)','',
                    'Luas (M2)', 'Konstruksi',
                    'Tahun Pengadaan & Tahun Perolehan', 'Jumlah Barang & Kondisi',
                    'Harga (Rp.)','Keterangan'
                ],
                [
                    2,'','',
                    3,
                    4,5,'',
                    6,7,
                    8,9,
                    10,11
                ]
            ];
        } else if($this->bidang_barang == "E") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','Jenis / Nama Barang',
                    'Uraian', 'Tahun Pengadaan & Tahun Perolehan',
                    'Jumlah Barang & Kondisi','Harga (Rp.)','Keterangan'
                ],
                [
                    2,3,
                    4,5,
                    6,7,8
                ]
            ];
        } else if($this->bidang_barang == "F") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','Jenis / Nama Barang',
                    'Letak / Lokasi / Alamat','Panjang (M) & Lebar (M)','',
                    'Luas (M2)', 'Konstruksi',
                    'Tahun Pengadaan & Tahun Perolehan', 'Jumlah Barang & Kondisi',
                    'Harga (Rp.)','Keterangan'
                ],
                [
                    2,3,
                    4,5,'',
                    6,7,
                    8,9,
                    10,11
                ]
            ];
        } else if($this->bidang_barang == "G") {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','Jenis / Nama Barang',
                    'Uraian', 'Tahun Pengadaan & Tahun Perolehan',
                    'Jumlah Barang & Kondisi','Harga (Rp.)','Keterangan'
                ],
                [
                    2,3,
                    4,5,
                    6,7,8
                ]
            ];
        } else {
            $heading = [
                [
                    'Kode Rinc. Objek No. Regs. Induk','Jenis / Nama Barang',
                    'Letak / Lokasi / Alamat',
                    'Status Tanah', '', '',
                    'Luas (M2)', 'Konstruksi',
                    'Tahun Pengadaan & Tahun Perolehan', 'Jumlah Barang & Kondisi',
                    'Harga', 'Keterangan'
                ],
                [
                    '','',
                    '',
                    'Status','Tanggal','Nomor',
                    '','',
                    '','',
                    '','',
                ],
                [
                    2,3,
                    4,
                    5,6,7,
                    8,9,
                    10,11,
                    12,13
                ]
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
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 6);
                    $event->sheet->freezePane('N7');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:M5')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A6:M6')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A7:M'.$max)->applyFromArray([
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

                    // set row
                    $event->sheet->getRowDimension('4')->setRowHeight(20);
                    $event->sheet->getRowDimension('5')->setRowHeight(20);
                    // end row

                    //////////////heading
                    //b4 no regs
                    $event->sheet->getDelegate()->mergeCells('B4:B5');
                    //C4 jenis nama barang
                    $event->sheet->getDelegate()->mergeCells('C4:C5');
                    // D4 letak lokasi
                    $event->sheet->getDelegate()->mergeCells('D4:D5');
                    // E4 Status tanah
                    $event->sheet->getDelegate()->mergeCells('E4:G4');
                    // H4 luas
                    $event->sheet->getDelegate()->mergeCells('H4:H5');
                    // I4 konstruksi
                    $event->sheet->getDelegate()->mergeCells('I4:I5');
                    // J4 tahun mengadaan & tahun perolehan
                    $event->sheet->getDelegate()->mergeCells('J4:J5');
                    // K4 jumlah barang
                    $event->sheet->getDelegate()->mergeCells('K4:K5');
                    // L4 harga
                    $event->sheet->getDelegate()->mergeCells('L4:L5');
                    // M4 Keterangan
                    $event->sheet->getDelegate()->mergeCells('M4:M5');
                    // L6
                    $event->sheet->getStyle('L6')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('E7:H'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('I7:K'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
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
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(20);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(20);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(true);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    /////////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(30);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);


                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:M1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // j3
                    $event->sheet->getDelegate()->mergeCells('I3:M3');
                    $event->sheet->getDelegate()->setCellValue("I3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('I3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A6", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
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

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':M'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('I'.$f1.':K'.$f1);
                    $event->sheet->getDelegate()->setCellValue('I'.$f1, "Total");
                    $event->sheet->getStyle('I'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('L'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('L'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('L'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':E'.$f2);
                        $event->sheet->getDelegate()->mergeCells('F'.$f2.':M'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('F'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('F'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('F'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "B") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 5);
                    $event->sheet->freezePane('Q6');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:P4')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A5:P5')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A6:P'.$max)->applyFromArray([
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
                    //D4 merk tipe
                    $event->sheet->getDelegate()->mergeCells('D4:E4');
                    // F4 ukuran cc
                    $event->sheet->getDelegate()->mergeCells('F4:G4');
                    // J4 no seri rangka
                    $event->sheet->getDelegate()->mergeCells('J4:K4');
                    // L4 nopol bpkb
                    $event->sheet->getDelegate()->mergeCells('L4:M4');
                    // D5
                    $event->sheet->getDelegate()->mergeCells('D5:E5');
                    // F5 ukuran cc
                    $event->sheet->getDelegate()->mergeCells('F5:G5');
                    // J5 no seri rangka
                    $event->sheet->getDelegate()->mergeCells('J5:K5');
                    // L5 nopol bpkb
                    $event->sheet->getDelegate()->mergeCells('L5:M5');

                    // T5
                    $event->sheet->getStyle('O5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading


                    //////////////centering
                    $event->sheet->getStyle('F6:I'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('L6:N'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(5);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(10);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(5);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(20);
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
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(5);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    /////////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(15);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    /////////////column o
                    $event->sheet->getColumnDimension('O')->setAutoSize(true);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    /////////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(false);
                    $event->sheet->getColumnDimension('P')->setWidth(30);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:P1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // I3
                    $event->sheet->getDelegate()->mergeCells('I3:P3');
                    $event->sheet->getDelegate()->setCellValue("I3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('I3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    // $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A5", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
                    // nomor
                    $nomor = 1;
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace isi
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('D'.$i.':E'.$i);
                        $event->sheet->getDelegate()->mergeCells('F'.$i.':G'.$i);
                        $event->sheet->getDelegate()->mergeCells('J'.$i.':K'.$i);
                        $event->sheet->getDelegate()->mergeCells('L'.$i.':M'.$i);
                    }

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':P'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('L'.$f1.':N'.$f1);
                    $event->sheet->getDelegate()->setCellValue('L'.$f1, "Total");
                    $event->sheet->getStyle('L'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('O'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('O'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('O'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':G'.$f2);
                        $event->sheet->getDelegate()->mergeCells('I'.$f2.':P'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('I'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
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
        } else if($this->bidang_barang == "C") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 5);
                    $event->sheet->freezePane('M6');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:L4')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A5:L5')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A6:L'.$max)->applyFromArray([
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
                    //E4 panjang lebar
                    $event->sheet->getDelegate()->mergeCells('E4:F4');
                    // E5
                    $event->sheet->getDelegate()->mergeCells('E5:F5');

                    // K5
                    $event->sheet->getStyle('K5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading


                    //////////////centering
                    $event->sheet->getStyle('E6:J'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(10);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(5);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(10);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(20);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(true);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(30);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:L1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // I3
                    $event->sheet->getDelegate()->mergeCells("H3:L3");
                    $event->sheet->getDelegate()->setCellValue("H3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('H3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    // $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A5", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
                    // nomor
                    $nomor = 1;
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace isi
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('E'.$i.':F'.$i);
                    }

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':L'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('I'.$f1.':J'.$f1);
                    $event->sheet->getDelegate()->setCellValue('I'.$f1, "Total");
                    $event->sheet->getStyle('I'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('K'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('K'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('K'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                        $event->sheet->getDelegate()->mergeCells('G'.$f2.':L'.$f2);
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
        } else if($this->bidang_barang == "D") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 5);
                    $event->sheet->freezePane('O6');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:N4')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A5:N5')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A6:N'.$max)->applyFromArray([
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
                    //B4 no reg
                    $event->sheet->getDelegate()->mergeCells('B4:D4');
                    // B5
                    $event->sheet->getDelegate()->mergeCells('B5:D5');
                    //E4 panjang lebar
                    $event->sheet->getDelegate()->mergeCells('G4:H4');
                    // E5
                    $event->sheet->getDelegate()->mergeCells('G5:H5');

                    // K5
                    $event->sheet->getStyle('M5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading


                    //////////////centering
                    $event->sheet->getStyle('G6:L'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(5);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(5);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    //////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    //////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(20);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(10);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(5);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(20);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(15);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    /////////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(true);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    /////////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(30);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:N1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // I3
                    $event->sheet->getDelegate()->mergeCells("I3:N3");
                    $event->sheet->getDelegate()->setCellValue("I3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('I3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    // $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A5", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
                    // nomor
                    $nomor = 1;
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace isi
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('B'.$i.':D'.$i);
                        $event->sheet->getDelegate()->mergeCells('G'.$i.':H'.$i);
                    }

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
                    $event->sheet->getDelegate()->mergeCells('K'.$f1.':L'.$f1);
                    $event->sheet->getDelegate()->setCellValue('K'.$f1, "Total");
                    $event->sheet->getStyle('K'.$f1)->applyFromArray([
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
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':G'.$f2);
                        $event->sheet->getDelegate()->mergeCells('H'.$f2.':N'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('H'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('H'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('H'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "E") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 5);
                    $event->sheet->freezePane('I6');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:H4')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A5:H5')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A6:H'.$max)->applyFromArray([
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
                    // K5
                    $event->sheet->getStyle('G5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading


                    //////////////centering
                    $event->sheet->getStyle('E6:F'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(25);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    ////////////colun G
                    $event->sheet->getColumnDimension('G')->setAutoSize(true);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(30);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:H1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // I3
                    $event->sheet->getDelegate()->mergeCells('E3:H3');
                    $event->sheet->getDelegate()->setCellValue("E3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('E3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    // $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A5", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
                    // nomor
                    $nomor = 1;
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':H'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('E'.$f1.':F'.$f1);
                    $event->sheet->getDelegate()->setCellValue('E'.$f1, "Total");
                    $event->sheet->getStyle('E'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('G'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('G'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('G'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':C'.$f2);
                        $event->sheet->getDelegate()->mergeCells('E'.$f2.':H'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('E'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('E'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('E'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "F") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 5);
                    // $event->sheet->freezePane('L6');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:L4')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A5:L5')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A6:L'.$max)->applyFromArray([
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
                    //E4 panjang lebar
                    $event->sheet->getDelegate()->mergeCells('E4:F4');
                    // E5
                    $event->sheet->getDelegate()->mergeCells('E5:F5');

                    // K5
                    $event->sheet->getStyle('K5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading


                    //////////////centering
                    $event->sheet->getStyle('E6:J'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    //////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(10);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    //////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(5);
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
                    $event->sheet->getColumnDimension('I')->setWidth(20);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(true);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(30);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:L1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // I3
                    $event->sheet->getDelegate()->mergeCells("I3:L3");
                    $event->sheet->getDelegate()->setCellValue("I3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('I3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A5", "1");
                    $nomor = 1;
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace isi
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('E'.$i.':F'.$i);
                    }

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':L'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('I'.$f1.':J'.$f1);
                    $event->sheet->getDelegate()->setCellValue('I'.$f1, "Total");
                    $event->sheet->getStyle('I'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('K'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('K'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('K'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                        $event->sheet->getDelegate()->mergeCells('H'.$f2.':L'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('H'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('H'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('H'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "G") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 5);
                    $event->sheet->freezePane('I6');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:H4')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A5:H5')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A6:H'.$max)->applyFromArray([
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
                    // K5
                    $event->sheet->getStyle('G5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading


                    //////////////centering
                    $event->sheet->getStyle('E6:F'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(25);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    ////////////colun G
                    $event->sheet->getColumnDimension('G')->setAutoSize(true);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(30);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:H1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // I3
                    $event->sheet->getDelegate()->mergeCells('E3:H3');
                    $event->sheet->getDelegate()->setCellValue("E3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('E3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    // $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A5", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
                    // nomor
                    $nomor = 1;
                    for($i=6;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':H'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('E'.$f1.':F'.$f1);
                    $event->sheet->getDelegate()->setCellValue('E'.$f1, "Total");
                    $event->sheet->getStyle('E'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('G'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('G'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('G'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':C'.$f2);
                        $event->sheet->getDelegate()->mergeCells('E'.$f2.':H'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('E'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('E'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('E'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    /////////set paper
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 6);
                    $event->sheet->freezePane('N7');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '.$this->tahun_sekarang.' /'.$this->kode_108 . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A4:M5')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('A6:M6')->applyFromArray([
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
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    $event->sheet->getStyle('A7:M'.$max)->applyFromArray([
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

                    // set row
                    $event->sheet->getRowDimension('4')->setRowHeight(20);
                    $event->sheet->getRowDimension('5')->setRowHeight(20);
                    // end row

                    //////////////heading
                    //b4 no regs
                    $event->sheet->getDelegate()->mergeCells('B4:B5');
                    //C4 jenis nama barang
                    $event->sheet->getDelegate()->mergeCells('C4:C5');
                    // D4 letak lokasi
                    $event->sheet->getDelegate()->mergeCells('D4:D5');
                    // E4 Status tanah
                    $event->sheet->getDelegate()->mergeCells('E4:G4');
                    // H4 luas
                    $event->sheet->getDelegate()->mergeCells('H4:H5');
                    // I4 konstruksi
                    $event->sheet->getDelegate()->mergeCells('I4:I5');
                    // J4 tahun mengadaan & tahun perolehan
                    $event->sheet->getDelegate()->mergeCells('J4:J5');
                    // K4 jumlah barang
                    $event->sheet->getDelegate()->mergeCells('K4:K5');
                    // L4 harga
                    $event->sheet->getDelegate()->mergeCells('L4:L5');
                    // M4 Keterangan
                    $event->sheet->getDelegate()->mergeCells('M4:M5');
                    // L6
                    $event->sheet->getStyle('L6')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('E7:H'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('I7:K'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    $event->sheet->getColumnDimension('A')->setWidth(10);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(20);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
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
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(20);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(20);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(true);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    /////////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(30);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);


                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:M1');
                    $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ". $this->bidang_barang ." ". $this->nama_lokasi . "\n Tanah \n ".$this->tahun_sekarang);
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
                    $event->sheet->getDelegate()->setCellValue("A2", "Propinsi");
                    $event->sheet->getStyle('A2')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // a3
                    $event->sheet->getDelegate()->setCellValue("A3", "Kab / Kota");
                    $event->sheet->getStyle('A3')->applyFromArray([
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
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b3
                    $event->sheet->getDelegate()->mergeCells('B3:C3');
                    $event->sheet->getDelegate()->setCellValue("B3", ": Kabupaten Mojokerto");
                    $event->sheet->getStyle('B3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // j3
                    $event->sheet->getDelegate()->mergeCells('I3:M3');
                    $event->sheet->getDelegate()->setCellValue("I3", "Kode Rincian : ".$this->kode_108.' '.$this->uraian_sub_rincian);
                    $event->sheet->getStyle('I3')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A4
                    $event->sheet->getDelegate()->mergeCells('A4:A5');
                    $event->sheet->getDelegate()->setCellValue("A4", "No.");
                    $event->sheet->getStyle('A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A6
                    $event->sheet->getDelegate()->setCellValue("A6", "1");
                    // A7
                    // $event->sheet->getDelegate()->mergeCells('A7:N7');
                    // $event->sheet->getDelegate()->setCellValue("A7", "aaaa");
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

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':M'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('I'.$f1.':K'.$f1);
                    $event->sheet->getDelegate()->setCellValue('I'.$f1, "Total");
                    $event->sheet->getStyle('I'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('L'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('L'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('L'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':E'.$f2);
                        $event->sheet->getDelegate()->mergeCells('F'.$f2.':M'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('F'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('F'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('F'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        }

    }

    public function columnFormats(): array
    {
        if($this->bidang_barang == "A") {
            return [
                'B' => NumberFormat::FORMAT_TEXT,
                'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "B") {
            return [
                'B' => NumberFormat::FORMAT_TEXT,
                'O' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "C") {
            return [
                'K' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }else if($this->bidang_barang == "D") {
            return [
                'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "E") {
            return [
                'G' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }else if($this->bidang_barang == "F") {
            return [
                'K' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "G") {
            return [
                'G' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else {
            return [
                'B' => NumberFormat::FORMAT_TEXT,
                'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }
    }

    public function title(): string
    {
        return 'Data';
    }
}
