<?php

namespace App\Exports;

use App\Models\Jurnal\Kib_awal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter;

class KibAwalExport implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
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
        $this->total_nilai_lunas = 0;
	}

    public function collection()
    {
        if($this->bidang_barang == "A") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'luas_tanah', 'tahun_pengadaan', 'merk_alamat', 'status_tanah', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "B") {
            $data = Kib_awal::select('no_register', 'kode_64', 'kode_108', 'nama_barang','merk_alamat', 'tipe', 'ukuran', 'cc', 'bahan', 'tahun_pengadaan', 'baik', 'kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.2%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "C") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'baik', 'kb', 'rb', 'jumlah_lantai', 'bahan', 'konstruksi', 'luas_lantai', 'merk_alamat','tgl_sertifikat', 'no_sertifikat', 'luas_bangunan', 'status_tanah', 'no_regs_induk_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.3%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "D") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'konstruksi', 'tahun_pengadaan', 'panjang_tanah', 'lebar_tanah', 'luas_bangunan', 'merk_alamat', 'tgl_sertifikat', 'no_sertifikat', 'status_tanah', 'no_regs_induk_tanah', 'harga_total_plus_pajak_saldo', 'baik', 'kb', 'rb', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.4%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "E") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'merk_alamat', 'tipe', 'bahan', 'ukuran', 'saldo_barang', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.5%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')->get()
                ->toArray();
        } else if($this->bidang_barang == "F") {
            $data = Kib_awal::select('nama_barang', 'no_register', 'konstruksi', 'jumlah_lantai', 'bahan', 'luas_bangunan', 'merk_alamat', 'tgl_sertifikat', 'no_sertifikat', 'status_tanah', 'harga_total_plus_pajak_saldo', 'nilai_lunas', 'keterangan')
                ->where('kode_108', 'like', '1.3.6%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "G") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'luas_tanah', 'tahun_pengadaan', 'merk_alamat', 'status_tanah', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($this->bidang_barang == "RB") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'luas_tanah', 'tahun_pengadaan', 'merk_alamat', 'status_tanah', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();

        } else {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'luas_tanah', 'tahun_pengadaan', 'merk_alamat', 'status_tanah', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();

        }

        $asets = array();
        $total_harga = 0;
        $total_nilai_lunas = 0;
        foreach ($data as $value) {
            $aset = $value;
            // $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
            $no_register = (string)$value['no_register'];

            if($this->bidang_barang == "A"){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            } else if($this->bidang_barang == "B"){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $new_no_register = $no_register.''.PHP_EOL.''.$kode_64;
                $aset['no_register'] = $new_no_register;

                $merk_tipe = (string)$value['merk_alamat'].''.PHP_EOL.''.$value['tipe'];
                $aset['merk_alamat'] = $merk_tipe;

                $ukuran_cc = (string)$value['ukuran'].''.PHP_EOL.''.$value['cc'];
                $aset['ukuran'] = $ukuran_cc;

                $baik = (string)$value['baik'];
                $kurang_baik = (string)$value['kb'];
                $rusak_berat = (string)$value['rb'];

                if($baik> 0 && $baik>$kurang_baik && $baik>$rusak_berat){
                    $kondisi = "Baik";
                } else if($kurang_baik>0 && $kurang_baik>$baik && $kurang_baik>$rusak_berat){
                    $kondisi = "Kurang Baik";
                } else if($rusak_berat>0 && $rusak_berat>$baik && $rusak_berat>$kurang_baik){
                    $kondisi = "Rusak Berat";
                }

                $aset['baik'] = $kondisi;

                $jumlah_barang_satuan = (string)$value['saldo_barang'].''.PHP_EOL.''.$value['satuan'];
                $aset['saldo_barang'] = $jumlah_barang_satuan;
            } else if($this->bidang_barang == "C"){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;

                $baik = (string)$value['baik'];
                $kurang_baik = (string)$value['kb'];
                $rusak_berat = (string)$value['rb'];
                if($baik> 0 && $baik>$kurang_baik && $baik>$rusak_berat){
                    $kondisi = "Baik";
                } else if($kurang_baik>0 && $kurang_baik>$baik && $kurang_baik>$rusak_berat){
                    $kondisi = "Kurang Baik";
                } else if($rusak_berat>0 && $rusak_berat>$baik && $rusak_berat>$kurang_baik){
                    $kondisi = "Rusak Berat";
                }
                $aset['baik'] = $kondisi;

                $bahan_konstruksi = (string)$value['bahan'].''.PHP_EOL.''.$value['konstruksi'];
                $aset['bahan'] = $bahan_konstruksi;
            } else if($this->bidang_barang == "D"){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;

                $konstruksi_tahun = (string)$value['konstruksi'].''.PHP_EOL.''.$value['tahun_pengadaan'];
                $aset['konstruksi'] = $konstruksi_tahun;

                $baik = (string)$value['baik'];
                $kurang_baik = (string)$value['kb'];
                $rusak_berat = (string)$value['rb'];
                if($baik> 0 && $baik>$kurang_baik && $baik>$rusak_berat){
                    $kondisi = "Baik";
                } else if($kurang_baik>0 && $kurang_baik>$baik && $kurang_baik>$rusak_berat){
                    $kondisi = "Kurang Baik";
                } else if($rusak_berat>0 && $rusak_berat>$baik && $rusak_berat>$kurang_baik){
                    $kondisi = "Rusak Berat";
                }
                $aset['baik'] = $kondisi;
            } else if($this->bidang_barang == 'E'){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            } else if($this->bidang_barang == "F"){
                $aset['no_register'] = $no_register;

                $total_nilai_lunas_tmp = $value["nilai_lunas"];
                $total_nilai_lunas+=$total_nilai_lunas_tmp;
                $this->total_nilai_lunas = $total_nilai_lunas;
            } else if($this->bidang_barang == "G"){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            } else if($this->bidang_barang == "RB"){
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            } else {
                $kode_64 = (string)$value['kode_64'].''.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
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
        return 'B5';
    }

    public function headingRow(): int
    {
        return 5;
    }

    public function headings(): array
    {

        if($this->bidang_barang == "A") {
            $heading = [
                ['Nomor','','','','','','','Status Tanah','', '', 'Penggunaan', 'Harga(Rp.)', 'Keterangan'],
                ['Jenis / Nama', 'Kode Barang', '', 'No. Regs. Induk & Lokal', 'Luas(M2)', 'Tahun Pengadaan', 'Letak / Alamat', 'Status','Tanggal', 'Nomor', '', '', ''],
                [2,3,'','4',5,6,7,8,9,10,11,12,13]
            ];
        } else if($this->bidang_barang == "B") {
            $heading = [
                [
                    'Kode Barang No. Regs. Induk & Lokal', '', '', 'Jenis / Nama', 'Merk / Tipe', '', 'Ukuran / CC', '', 'Bahan', 'Tahun Pengadaan', 'Kondisi', '', '', 'Nomor', '', '', '', 'Jumlah Barang & Satuan', '', 'Harga (Rp.)', 'Keterangan'
                ],
                [
                    '', '', '', '', '', '', '', '', '', '', '', '', '', 'Rangka', 'Mesin', 'NOPOL', 'BPKB', '', '', ''
                ],
                [2, '', '',3,4, '',5, '',6,7,8,'','',9,10,11,12,13, '',14,15]
        ];
        } else if($this->bidang_barang == "C") {
            $heading = [
                [
                    'Jenis / Nama','Nomor','','',
                    'Kondisi Bangunan','','',
                    'Konstruksi Bangunan','','',
                    'Luas Lantai (M2)','Letak / Lokasi / Alamat',
                    'Dokumen Gedung', '',
                    'Luas Bangunan (M2)', 'Status Tanah', 'Nomor Kode Tanah','Asal-usul & Tahun','Harga (Rp.)','Keterangan'
                ],
                [
                    '', 'Kode Barang','', 'No. Regs. Induk & Lokal',
                    '(B, KB, RB)', '', '',
                    'Bertingkat / Tingkat', 'Bahan / Jenis Konstruksi','',
                    '','',
                    'Tanggal', 'Nomor',
                    '','','','','',''
                ],
                [
                    2,3,'',4,
                    5,'','',
                    6,7,'',
                    8,9,
                    10,11,
                    12,13,14,15,16,17
                ]
        ];
        } else if($this->bidang_barang == "D") {
            $heading = [
                [
                    'Jenis / Nama', 'Nomor', '', '',
                    'Konstruksi & Tahun', '',
                    'Panjang (M)', 'Lebar (M)', 'Luas (M2)', 'Letak / Lokasi / Alamat',
                    'Dokumen Gedung', '',
                    'Status Tanah', 'Nomor Kode Tanah', 'Harga (Rp.)',
                    'Kondisi Bangunan', '', '',
                    'Keterangan'
                ],
                [
                    '', 'Kode Barang','', 'No. Regs. Induk & Lokal',
                    '','',
                    '','','','',
                    'Tanggal','Nomor',
                    '','','',
                    '( B, KB, RB )','','',
                    ''
                ],
                [
                    2,3,'',4,
                    5,'',
                    6,7,8,9,
                    10,11,
                    12,13,14,
                    15,'','',
                    16
                ]
        ];
        } else if($this->bidang_barang == "E") {
            $heading = [
                [
                    'Jenis / Nama', 'Nomor', '', '',
                    'Buku Perpustakaan', '',
                    'Barang Bercorak Kesenian / Kebudayaan',
                    'Hewan, Ternak / Tumbuhan',
                    'Jumlah & Kondisi', 'Tahun Pengadaan',
                    'Harga (Rp.)','Keterangan'
                ],
                [
                    '', 'Kode Barang','', 'No. Regs. Induk & Lokal',
                    'Judul / Pencipta','Spesifikasi',
                    'Bahan',
                    'Ukuran',
                    '','',
                    '',''
                ],
                [
                    2,3,'',4,
                    5,6,
                    7,
                    8,
                    9,10,
                    11,12,
                ]
        ];
        } else if($this->bidang_barang == "F") {
            $heading = [
                [
                    'Jenis / Nama', 'Nomor',
                    'Konstruksi Bangunan', '', '', '',
                    'Letak / Lokasi / Alamat',
                    'Dokumen', '',
                    'Status Tanah', 'Nilai Saat Ini (Rp.)', 'Nilai Kontrak (Rp.)', 'Ket.'
                ],
                [
                    '','No. Regs. Induk & Lokal',
                    'Bangunan (P, SP, D)', 'Jumlah Tingkat', 'Beton / Tidak', 'Luas (M2)',
                    '',
                    'Tanggal', 'Nomor',
                    '','','',''
                ],
                [
                    2,3,
                    4,5,6,7,
                    8,
                    9,10,
                    11,12,13,14
                ]
        ];
        } else if($this->bidang_barang == "G") {
            $heading = [
                ['Nomor','','','','','','','Status Tanah','', '', 'Penggunaan', 'Harga(Rp.)', 'Keterangan'],
                ['Jenis / Nama', 'Kode Barang', '', 'No. Regs. Induk & Lokal', 'Luas(M2)', 'Tahun Pengadaan', 'Letak / Alamat', 'Status','Tanggal', 'Nomor', '', '', ''],
                [2,3,'','4',5,6,7,8,9,10,11,12,13]
            ];
        } else if($this->bidang_barang == "RB") {
            $heading = [
                ['Nomor','','','','','','','Status Tanah','', '', 'Penggunaan', 'Harga(Rp.)', 'Keterangan'],
                ['Jenis / Nama', 'Kode Barang', '', 'No. Regs. Induk & Lokal', 'Luas(M2)', 'Tahun Pengadaan', 'Letak / Alamat', 'Status','Tanggal', 'Nomor', '', '', ''],
                [2,3,'','4',5,6,7,8,9,10,11,12,13]
            ];
        } else {
            $heading = [
                ['Nomor','','','','','','','Status Tanah','', '', 'Penggunaan', 'Harga(Rp.)', 'Keterangan'],
                ['Jenis / Nama', 'Kode Barang', '', 'No. Regs. Induk & Lokal', 'Luas(M2)', 'Tahun Pengadaan', 'Letak / Alamat', 'Status','Tanggal', 'Nomor', '', '', ''],
                [2,3,'','4',5,6,7,8,9,10,11,12,13]
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

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);

                    $event->sheet->freezePane('O8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:N6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:N7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:N'.$max)->applyFromArray([
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
                    //b5 Nomor
                    $event->sheet->getDelegate()->mergeCells('B5:H5');
                    $event->sheet->getStyle('B5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    //I5 Status Tanah
                    $event->sheet->getDelegate()->mergeCells('I5:K5');
                    $event->sheet->getStyle('I5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    // L5 Penggunaan
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M5 Harga
                    $event->sheet->getDelegate()->mergeCells('M5:M6');
                    // N5 Keterangan
                    $event->sheet->getDelegate()->mergeCells('N5:N6');
                    // M7
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('C7:G'.$max)->applyFromArray([
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
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(9);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(10);
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

                    // merge kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    $event->sheet->getDelegate()->mergeCells('C7:D7');


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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
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
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // j4
                    $event->sheet->getDelegate()->mergeCells('J4:M4');
                    $event->sheet->getDelegate()->setCellValue("J4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('J4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
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
        } else if($this->bidang_barang == "B") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);

                    $event->sheet->freezePane('W8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:V6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:V7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:V'.$max)->applyFromArray([
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
                    //b5 Nomor registrasi
                    $event->sheet->getDelegate()->mergeCells('B5:D6');
                    $event->sheet->getDelegate()->mergeCells('B7:D7');
                    // E5 nama jenis / nama
                    $event->sheet->getDelegate()->mergeCells('E5:E6');
                    // F5 merk / tipe
                    $event->sheet->getDelegate()->mergeCells('F5:G6');
                    $event->sheet->getDelegate()->mergeCells('F7:G7');
                    // H5 ukuran CC
                    $event->sheet->getDelegate()->mergeCells('H5:I6');
                    $event->sheet->getDelegate()->mergeCells('H7:I7');
                    // J5 bahan
                    $event->sheet->getDelegate()->mergeCells('J5:J6');
                    // K5 tahun pengadaan
                    $event->sheet->getDelegate()->mergeCells('K5:K6');
                    // L5 kondisi
                    $event->sheet->getDelegate()->mergeCells('L5:N6');
                    $event->sheet->getDelegate()->mergeCells('L7:N7');
                    // Nomor
                    $event->sheet->getDelegate()->mergeCells('O5:R5');
                    // o5 rangka
                    $event->sheet->getDelegate()->mergeCells('O6:O6');
                    // P5 mesin
                    $event->sheet->getDelegate()->mergeCells('P6:P6');
                    // Q5 nopol
                    $event->sheet->getDelegate()->mergeCells('Q6:Q6');
                    // r5 bpkb
                    $event->sheet->getDelegate()->mergeCells('R6:R6');
                    // S5 jumlah barang satuan
                    $event->sheet->getDelegate()->mergeCells('S5:T6');
                    $event->sheet->getDelegate()->mergeCells('S7:T7');
                    // u5 harga
                    $event->sheet->getDelegate()->mergeCells('U5:U6');
                    // v5 jumlah barang satuan
                    $event->sheet->getDelegate()->mergeCells('V5:V6');
                    // U7 14
                    $event->sheet->getStyle('U7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    ////////////perulangan untuk replace no register & kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('B'.$i.':D'.$i);
                        $event->sheet->getDelegate()->mergeCells('F'.$i.':G'.$i);
                        $event->sheet->getDelegate()->mergeCells('H'.$i.':I'.$i);
                        $event->sheet->getDelegate()->mergeCells('L'.$i.':N'.$i);
                        $event->sheet->getDelegate()->mergeCells('S'.$i.':T'.$i);
                    }

                    //////////////centering
                    $event->sheet->getStyle('B8:B'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('H8:J'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('K8:K'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('O8:R'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('S8:T'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

                    //////////////width column
                    //////////column A
                    $event->sheet->getColumnDimension('A')->setAutoSize(true);
                    $event->sheet->getStyle('A1:A'.$max)->getAlignment()->setWrapText(true);
                    //////////column B
                    $event->sheet->getColumnDimension('B')->setAutoSize(false);
                    $event->sheet->getColumnDimension('B')->setWidth(10);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(20);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(10);
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
                    $event->sheet->getColumnDimension('I')->setWidth(5);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(15);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(false);
                    $event->sheet->getColumnDimension('L')->setWidth(4);
                    $event->sheet->getStyle('L1:L'.$max)->getAlignment()->setWrapText(true);
                    /////////////column M
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(4);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    /////////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(4);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    /////////////column O
                    $event->sheet->getColumnDimension('O')->setAutoSize(false);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    /////////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(false);
                    $event->sheet->getColumnDimension('P')->setWidth(15);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);
                    /////////////column Q
                    $event->sheet->getColumnDimension('Q')->setAutoSize(false);
                    $event->sheet->getColumnDimension('Q')->setWidth(15);
                    $event->sheet->getStyle('Q1:Q'.$max)->getAlignment()->setWrapText(true);
                    /////////////column R
                    $event->sheet->getColumnDimension('R')->setAutoSize(false);
                    $event->sheet->getColumnDimension('R')->setWidth(15);
                    $event->sheet->getStyle('R1:R'.$max)->getAlignment()->setWrapText(true);
                    /////////////column S
                    $event->sheet->getColumnDimension('S')->setAutoSize(false);
                    $event->sheet->getColumnDimension('S')->setWidth(5);
                    $event->sheet->getStyle('S1:S'.$max)->getAlignment()->setWrapText(true);
                    /////////////column T
                    $event->sheet->getColumnDimension('T')->setAutoSize(false);
                    $event->sheet->getColumnDimension('T')->setWidth(5);
                    $event->sheet->getStyle('T1:T'.$max)->getAlignment()->setWrapText(true);
                    ////////////column U
                    $event->sheet->getColumnDimension('U')->setAutoSize(true);
                    /////////////column V
                    $event->sheet->getColumnDimension('V')->setAutoSize(false);
                    $event->sheet->getColumnDimension('V')->setWidth(30);
                    $event->sheet->getStyle('V1:V'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:V1');
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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
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
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // H4
                    $event->sheet->getDelegate()->mergeCells('H4:M4');
                    $event->sheet->getDelegate()->setCellValue("H4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('H4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace no register & kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('B'.$i.':D'.$i);
                    }

                    $date = date('d/m/Y');
                    $f1 = $max+1;
                    $f2 = $max+3;

                    ///////////////border total
                    $event->sheet->getStyle('A'.$f1.':V'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('R'.$f1.':T'.$f1);
                    $event->sheet->getDelegate()->setCellValue('R'.$f1, "Total");
                    $event->sheet->getStyle('R'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('U'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('U'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('U'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':K'.$f2);
                        $event->sheet->getDelegate()->mergeCells('L'.$f2.':V'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('L'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('L'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('L'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "C") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);

                    $event->sheet->freezePane('V8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:U6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:U7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:U'.$max)->applyFromArray([
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
                    //b6 jenis / nama
                    $event->sheet->getDelegate()->mergeCells('B5:B6');
                    // C5 nomor
                    $event->sheet->getDelegate()->mergeCells('C5:E5');
                    // C6 kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    // C7 kode barang
                    $event->sheet->getDelegate()->mergeCells('C7:D7');
                    // f5 kondisi
                    $event->sheet->getDelegate()->mergeCells('F5:H5');
                    // F6 kondisi
                    $event->sheet->getDelegate()->mergeCells('F6:H6');
                    // F7 kondisi
                    $event->sheet->getDelegate()->mergeCells('F7:H7');
                    // I5 konstruksi bangunan
                    $event->sheet->getDelegate()->mergeCells('I5:K5');
                    // J6 bahan konstruksi
                    $event->sheet->getDelegate()->mergeCells('J6:K6');
                    // J7 bahan konstruksi
                    $event->sheet->getDelegate()->mergeCells('J7:K7');
                    // L6 luas lantai
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M6 letak
                    $event->sheet->getDelegate()->mergeCells('M5:M6');
                    // N5 dokumen gedung
                    $event->sheet->getDelegate()->mergeCells('N5:O5');
                    // P5 luas bangunan
                    $event->sheet->getDelegate()->mergeCells('P5:P6');
                    // Q5 status
                    $event->sheet->getDelegate()->mergeCells('Q5:Q6');
                    // R5 nomor
                    $event->sheet->getDelegate()->mergeCells('R5:R6');
                    // S5 asal usul
                    $event->sheet->getDelegate()->mergeCells('S5:S6');
                    // T5 harga
                    $event->sheet->getDelegate()->mergeCells('T5:T6');
                    // U5 keterangan
                    $event->sheet->getDelegate()->mergeCells('U5:U6');



                    // T7 16
                    $event->sheet->getStyle('T7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    ////////////perulangan untuk replace no register & kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
                        $event->sheet->getDelegate()->mergeCells('F'.$i.':H'.$i);
                        $event->sheet->getDelegate()->mergeCells('J'.$i.':K'.$i);
                    }

                    //////////////centering
                    $event->sheet->getStyle('C8:L'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('N8:S'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

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
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(25);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(4);
                    $event->sheet->getRowDimension('5')->setRowHeight(30);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(4);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(4);
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
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(20);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    /////////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(10);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    /////////////column O
                    $event->sheet->getColumnDimension('O')->setAutoSize(false);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    /////////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(false);
                    $event->sheet->getColumnDimension('P')->setWidth(10);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);
                    /////////////column Q
                    $event->sheet->getColumnDimension('Q')->setAutoSize(false);
                    $event->sheet->getColumnDimension('Q')->setWidth(15);
                    $event->sheet->getStyle('Q1:Q'.$max)->getAlignment()->setWrapText(true);
                    /////////////column R
                    $event->sheet->getColumnDimension('R')->setAutoSize(false);
                    $event->sheet->getColumnDimension('R')->setWidth(15);
                    $event->sheet->getStyle('R1:R'.$max)->getAlignment()->setWrapText(true);
                    /////////////column S
                    $event->sheet->getColumnDimension('S')->setAutoSize(false);
                    $event->sheet->getColumnDimension('S')->setWidth(15);
                    $event->sheet->getStyle('S1:S'.$max)->getAlignment()->setWrapText(true);
                    /////////////column T
                    $event->sheet->getColumnDimension('T')->setAutoSize(true);
                    ////////////column U
                    $event->sheet->getColumnDimension('U')->setAutoSize(false);
                    $event->sheet->getColumnDimension('U')->setWidth(30);
                    $event->sheet->getStyle('U1:U'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:V1');
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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
                    $event->sheet->getStyle('A6')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b2
                    $event->sheet->getDelegate()->mergeCells('B2:C2');
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // H4
                    $event->sheet->getDelegate()->mergeCells('H4:N4');
                    $event->sheet->getDelegate()->setCellValue("H4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('H4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
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
                    $event->sheet->getStyle('A'.$f1.':U'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('R'.$f1.':S'.$f1);
                    $event->sheet->getDelegate()->setCellValue('R'.$f1, "Total");
                    $event->sheet->getStyle('R'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('T'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('T'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('T'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':K'.$f2);
                        $event->sheet->getDelegate()->mergeCells('L'.$f2.':U'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('L'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('L'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('L'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "D") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);
                    $event->sheet->freezePane('U8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:T6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:T7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:T'.$max)->applyFromArray([
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
                    //b6 jenis / nama
                    $event->sheet->getDelegate()->mergeCells('B5:B6');
                    // C5 nomor
                    $event->sheet->getDelegate()->mergeCells('C5:E5');
                    // C6 kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    // C7 kode barang
                    $event->sheet->getDelegate()->mergeCells('C7:D7');
                    // f5 konstruksi tahun
                    $event->sheet->getDelegate()->mergeCells('F5:G6');
                    // F7 konstruksi tahun
                    $event->sheet->getDelegate()->mergeCells('F7:G7');
                    // H5 panjang
                    $event->sheet->getDelegate()->mergeCells('H5:H6');
                    // I5 lebar
                    $event->sheet->getDelegate()->mergeCells('I5:I6');
                    // J5 luas bangunan
                    $event->sheet->getDelegate()->mergeCells('J5:J6');
                    // K5 letak lokasi alamat
                    $event->sheet->getDelegate()->mergeCells('K5:K6');
                    // L5 dokumen gedung
                    $event->sheet->getDelegate()->mergeCells('L5:M5');
                    // N5 status tanah
                    $event->sheet->getDelegate()->mergeCells('N5:N6');
                    // O5 nomor kode tanah
                    $event->sheet->getDelegate()->mergeCells('O5:O6');
                    // P5 harga
                    $event->sheet->getDelegate()->mergeCells('P5:P6');
                    // Q5 kondisi bangunan
                    $event->sheet->getDelegate()->mergeCells('Q5:S5');
                    // Q6 kondisi bangunan
                    $event->sheet->getDelegate()->mergeCells('Q6:S6');
                    // Q7 kondisi bangunan
                    $event->sheet->getDelegate()->mergeCells('Q7:S7');
                    // T5 keterangan
                    $event->sheet->getDelegate()->mergeCells('T5:T6');



                    // M7 11
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // P7 14
                    $event->sheet->getStyle('P7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    ////////////perulangan untuk replace no register & kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
                        $event->sheet->getDelegate()->mergeCells('F'.$i.':G'.$i);
                        $event->sheet->getDelegate()->mergeCells('Q'.$i.':S'.$i);
                    }

                    //////////////centering
                    $event->sheet->getStyle('C8:J'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('L8:O'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('Q8:S'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

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
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(25);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(10);
                    // $event->sheet->getRowDimension('5')->setRowHeight(30);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(5);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(10);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(10);
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
                    $event->sheet->getColumnDimension('M')->setAutoSize(false);
                    $event->sheet->getColumnDimension('M')->setWidth(15);
                    $event->sheet->getStyle('M1:M'.$max)->getAlignment()->setWrapText(true);
                    /////////////column N
                    $event->sheet->getColumnDimension('N')->setAutoSize(false);
                    $event->sheet->getColumnDimension('N')->setWidth(10);
                    $event->sheet->getStyle('N1:N'.$max)->getAlignment()->setWrapText(true);
                    /////////////column O
                    $event->sheet->getColumnDimension('O')->setAutoSize(false);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getStyle('O1:O'.$max)->getAlignment()->setWrapText(true);
                    /////////////column P
                    $event->sheet->getColumnDimension('P')->setAutoSize(true);
                    $event->sheet->getStyle('P1:P'.$max)->getAlignment()->setWrapText(true);
                    /////////////column Q
                    $event->sheet->getColumnDimension('Q')->setAutoSize(false);
                    $event->sheet->getColumnDimension('Q')->setWidth(5);
                    $event->sheet->getStyle('Q1:Q'.$max)->getAlignment()->setWrapText(true);
                    /////////////column R
                    $event->sheet->getColumnDimension('R')->setAutoSize(false);
                    $event->sheet->getColumnDimension('R')->setWidth(5);
                    $event->sheet->getStyle('R1:R'.$max)->getAlignment()->setWrapText(true);
                    /////////////column S
                    $event->sheet->getColumnDimension('S')->setAutoSize(false);
                    $event->sheet->getColumnDimension('S')->setWidth(4);
                    $event->sheet->getStyle('S1:S'.$max)->getAlignment()->setWrapText(true);
                    ////////////column T
                    $event->sheet->getColumnDimension('T')->setAutoSize(false);
                    $event->sheet->getColumnDimension('T')->setWidth(30);
                    $event->sheet->getStyle('T1:T'.$max)->getAlignment()->setWrapText(true);

                    ///////////////header
                    // a1
                    $event->sheet->getDelegate()->mergeCells('A1:T1');
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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
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
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // H4
                    $event->sheet->getDelegate()->mergeCells('H4:N4');
                    $event->sheet->getDelegate()->setCellValue("H4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('H4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
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
                    $event->sheet->getStyle('A'.$f1.':T'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('N'.$f1.':O'.$f1);
                    $event->sheet->getDelegate()->setCellValue('N'.$f1, "Total");
                    $event->sheet->getStyle('N'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    $event->sheet->getDelegate()->setCellValue('P'.$f1 , $this->total_harga);
                    $event->sheet->getStyle('P'.$f1)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                    $event->sheet->getStyle('P'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':J'.$f2);
                        $event->sheet->getDelegate()->mergeCells('K'.$f2.':T'.$f2);
                        $event->sheet->getStyle('A'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('K'.$f2)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        if($i == 0) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                            $event->sheet->getDelegate()->setCellValue('K'.$f2, "Mojokerto, ".$date);
                        }

                        if($i == 4) {
                            $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                            $event->sheet->getDelegate()->setCellValue('K'.$f2, "NIP");
                        }

                        $f2++;
                    }
                },
            ];
        } else if($this->bidang_barang == "E") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    ///////////set page
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);
                    $event->sheet->freezePane('N8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:M6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:M7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:M'.$max)->applyFromArray([
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
                    //b6 jenis / nama
                    $event->sheet->getDelegate()->mergeCells('B5:B6');
                    // C5 nomor
                    $event->sheet->getDelegate()->mergeCells('C5:E5');
                    // C6 kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    // C7 kode barang
                    $event->sheet->getDelegate()->mergeCells('C7:D7');
                    // F5 buku perpustakaan
                    $event->sheet->getDelegate()->mergeCells('F5:G5');
                    // J5 Jumlah & Kondisi
                    $event->sheet->getDelegate()->mergeCells('J5:J6');
                    // K5 tahun pengadaan
                    $event->sheet->getDelegate()->mergeCells('K5:K6');
                    //  L5 status tanah
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M5 nomor kode tanah
                    $event->sheet->getDelegate()->mergeCells('M5:M6');

                    // L7 11
                    $event->sheet->getStyle('L7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // M7 12
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    ////////////perulangan untuk replace no register & kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
                    }

                    //////////////centering
                    $event->sheet->getStyle('C8:D'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('I8:K'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

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
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(25);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(20);
                    // $event->sheet->getRowDimension('5')->setRowHeight(30);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(15);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(25);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(25);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(10);
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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
                    $event->sheet->getStyle('A6')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b2
                    $event->sheet->getDelegate()->mergeCells('B2:C2');
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // I4
                    $event->sheet->getDelegate()->mergeCells('I4:N4');
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
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
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
                    $event->sheet->getDelegate()->mergeCells('J'.$f1.':K'.$f1);
                    $event->sheet->getDelegate()->setCellValue('J'.$f1, "Total");
                    $event->sheet->getStyle('J'.$f1)->applyFromArray([
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
                    $event->sheet->getStyle('P'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    for($i = 0; $i<5; $i++) {
                        $event->sheet->getDelegate()->mergeCells('A'.$f2.':G'.$f2);
                        $event->sheet->getDelegate()->mergeCells('H'.$f2.':M'.$f2);
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
        } else if($this->bidang_barang == "F") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();
                    ///////////set page
                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);
                    $event->sheet->freezePane('O8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:N6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:N7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:N'.$max)->applyFromArray([
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
                    //b6 jenis / nama
                    $event->sheet->getDelegate()->mergeCells('B5:B6');
                    // D5 konstruksi bangunan
                    $event->sheet->getDelegate()->mergeCells('D5:G5');
                    // h5 lokasi
                    $event->sheet->getDelegate()->mergeCells('H5:H6');
                    // i5 dokumen
                    $event->sheet->getDelegate()->mergeCells('I5:J5');
                    // K5 status tanah
                    $event->sheet->getDelegate()->mergeCells('K5:K6');
                    //  L5 nilai saat ini
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M5 nilai kontrak
                    $event->sheet->getDelegate()->mergeCells('M5:M6');
                    // N5 ket
                    $event->sheet->getDelegate()->mergeCells('N5:N6');

                    // L7 11
                    $event->sheet->getStyle('L7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    // M7 12
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('C8:G'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('I8:K'.$max)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    ////////////// end centering

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
                    $event->sheet->getColumnDimension('C')->setWidth(20);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(10);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column F
                    $event->sheet->getColumnDimension('F')->setAutoSize(false);
                    $event->sheet->getColumnDimension('F')->setWidth(10);
                    $event->sheet->getRowDimension('5')->setRowHeight(30);
                    $event->sheet->getStyle('F1:F'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(10);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(20);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(15);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column J
                    $event->sheet->getColumnDimension('J')->setAutoSize(false);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getStyle('J1:J'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(15);
                    $event->sheet->getStyle('K1:K'.$max)->getAlignment()->setWrapText(true);
                    /////////////column L
                    $event->sheet->getColumnDimension('L')->setAutoSize(true);
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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
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
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // I4
                    $event->sheet->getDelegate()->mergeCells('I4:N4');
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
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
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
                    $event->sheet->getStyle('A'.$f1.':N'.$f1)->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000']
                            ],
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('J'.$f1.':K'.$f1);
                    $event->sheet->getDelegate()->setCellValue('J'.$f1, "Total");
                    $event->sheet->getStyle('J'.$f1)->applyFromArray([
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

                    $event->sheet->getDelegate()->setCellValue('M'.$f1 , $this->total_nilai_lunas);
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
        } else if($this->bidang_barang == "G") {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);
                    $event->sheet->freezePane('O8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:N6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:N7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:N'.$max)->applyFromArray([
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
                    //b5 Nomor
                    $event->sheet->getDelegate()->mergeCells('B5:H5');
                    $event->sheet->getStyle('B5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    //I5 Status Tanah
                    $event->sheet->getDelegate()->mergeCells('I5:K5');
                    $event->sheet->getStyle('I5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    // L5 Penggunaan
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M5 Harga
                    $event->sheet->getDelegate()->mergeCells('M5:M6');
                    // N5 Keterangan
                    $event->sheet->getDelegate()->mergeCells('N5:N6');
                    // M7
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('C7:G'.$max)->applyFromArray([
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
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(9);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(10);
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

                    // merge kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    $event->sheet->getDelegate()->mergeCells('C7:D7');


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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
                    $event->sheet->getStyle('A6')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true,
                        ]
                    ]);
                    // b2
                    $event->sheet->getDelegate()->mergeCells('B2:C2');
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // j4
                    $event->sheet->getDelegate()->mergeCells('J4:M4');
                    $event->sheet->getDelegate()->setCellValue("J4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('J4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
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
        } else if($this->bidang_barang == "RB"){
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);
                    $event->sheet->freezePane('O8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:N6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:N7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:N'.$max)->applyFromArray([
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
                    //b5 Nomor
                    $event->sheet->getDelegate()->mergeCells('B5:H5');
                    $event->sheet->getStyle('B5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    //I5 Status Tanah
                    $event->sheet->getDelegate()->mergeCells('I5:K5');
                    $event->sheet->getStyle('I5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    // L5 Penggunaan
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M5 Harga
                    $event->sheet->getDelegate()->mergeCells('M5:M6');
                    // N5 Keterangan
                    $event->sheet->getDelegate()->mergeCells('N5:N6');
                    // M7
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('C7:G'.$max)->applyFromArray([
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
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(9);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(10);
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

                    // merge kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    $event->sheet->getDelegate()->mergeCells('C7:D7');


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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
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
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // j4
                    $event->sheet->getDelegate()->mergeCells('J4:M4');
                    $event->sheet->getDelegate()->setCellValue("J4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('J4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
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
        }else {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    $max = $event->sheet->getDelegate()->getHighestRow();

                    $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                    $event->sheet->getPageSetup()->setFitToWidth(1);
                    $event->sheet->getPageSetup()->setFitToHeight(0);
                    $event->sheet->getPageSetup()->setFitToPage(true);
                    $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                    $event->sheet->setShowGridlines(false);
                    $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 7);
                    $event->sheet->freezePane('O8');

                    // footer
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B '. $this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' /'.$this->tahun_sekarang.'/ '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                    // end footer

                    ////////////////Border
                    $event->sheet->getStyle('A5:N6')->applyFromArray([
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
                    $event->sheet->getStyle('A7:N7')->applyFromArray([
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
                    $event->sheet->getStyle('A8:N'.$max)->applyFromArray([
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
                    //b5 Nomor
                    $event->sheet->getDelegate()->mergeCells('B5:H5');
                    $event->sheet->getStyle('B5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    //I5 Status Tanah
                    $event->sheet->getDelegate()->mergeCells('I5:K5');
                    $event->sheet->getStyle('I5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => true,
                            'size' => 18
                        ]
                    ]);
                    // L5 Penggunaan
                    $event->sheet->getDelegate()->mergeCells('L5:L6');
                    // M5 Harga
                    $event->sheet->getDelegate()->mergeCells('M5:M6');
                    // N5 Keterangan
                    $event->sheet->getDelegate()->mergeCells('N5:N6');
                    // M7
                    $event->sheet->getStyle('M7')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    ///////////////////end heading

                    //////////////centering
                    $event->sheet->getStyle('C7:G'.$max)->applyFromArray([
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
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getStyle('B1:B'.$max)->getAlignment()->setWrapText(true);
                    //////////column C
                    $event->sheet->getColumnDimension('C')->setAutoSize(false);
                    $event->sheet->getColumnDimension('C')->setWidth(10);
                    $event->sheet->getStyle('C1:C'.$max)->getAlignment()->setWrapText(true);
                    //////////column D
                    $event->sheet->getColumnDimension('D')->setAutoSize(false);
                    $event->sheet->getColumnDimension('D')->setWidth(10);
                    $event->sheet->getStyle('D1:D'.$max)->getAlignment()->setWrapText(true);
                    /////////////column E
                    $event->sheet->getColumnDimension('E')->setAutoSize(false);
                    $event->sheet->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getStyle('E1:E'.$max)->getAlignment()->setWrapText(true);
                    /////////////column G
                    $event->sheet->getColumnDimension('G')->setAutoSize(false);
                    $event->sheet->getColumnDimension('G')->setWidth(9);
                    $event->sheet->getStyle('G1:G'.$max)->getAlignment()->setWrapText(true);
                    /////////////column H
                    $event->sheet->getColumnDimension('H')->setAutoSize(false);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getStyle('H1:H'.$max)->getAlignment()->setWrapText(true);
                    /////////////column I
                    $event->sheet->getColumnDimension('I')->setAutoSize(false);
                    $event->sheet->getColumnDimension('I')->setWidth(10);
                    $event->sheet->getStyle('I1:I'.$max)->getAlignment()->setWrapText(true);
                    /////////////column K
                    $event->sheet->getColumnDimension('K')->setAutoSize(false);
                    $event->sheet->getColumnDimension('K')->setWidth(10);
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

                    // merge kode barang
                    $event->sheet->getDelegate()->mergeCells('C6:D6');
                    $event->sheet->getDelegate()->mergeCells('C7:D7');


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
                    // a4
                    $event->sheet->getDelegate()->setCellValue("A4", "Lokasi");
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
                    $event->sheet->getDelegate()->setCellValue("B2", ": Jawa Timur");
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
                    // j4
                    $event->sheet->getDelegate()->mergeCells('J4:M4');
                    $event->sheet->getDelegate()->setCellValue("J4", "Kepemilikan : ".$this->kode_kepemilikan." - Pemerintah Kab / Kota");
                    $event->sheet->getStyle('J4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                        'font' => [
                            'bold' => true
                        ]
                    ]);
                    //////////////end header

                    ////////////////numbering
                    // A5
                    $event->sheet->getDelegate()->mergeCells('A5:A6');
                    $event->sheet->getDelegate()->setCellValue("A5", "No.");
                    $event->sheet->getStyle('A5')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ]
                    ]);
                    // A7
                    $event->sheet->getDelegate()->setCellValue("A7", "1");
                    // nomor
                    $nomor = 1;
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                        $event->sheet->getStyle('A'.$i)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                        $nomor++;
                    }
                    ////////////end numbering

                    ////////////perulangan untuk replace kode_64 & 108
                    for($i=8;$i<=$max;$i++){
                        $event->sheet->getDelegate()->mergeCells('C'.$i.':D'.$i);
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

    }

    public function columnFormats(): array
    {
        if($this->bidang_barang == "A") {
            return [
                'E' => NumberFormat::FORMAT_TEXT,
                'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "B") {
            return [
                'E' => NumberFormat::FORMAT_TEXT,
                'U' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "C") {
            return [
                'T' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }else if($this->bidang_barang == "D") {
            return [
                'P' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "E") {
            return [
                'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "F") {
            return [
                'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
                'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "G"){
            return [
                'E' => NumberFormat::FORMAT_TEXT,
                'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else if($this->bidang_barang == "RB"){
            return [
                'E' => NumberFormat::FORMAT_TEXT,
                'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        } else {
            return [
                'E' => NumberFormat::FORMAT_TEXT,
                'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
            ];
        }
    }

    public function title(): string
    {
        return 'Data';
    }
}
