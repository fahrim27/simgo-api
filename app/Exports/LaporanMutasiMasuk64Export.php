<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Masa_tambahan;
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

class LaporanMutasiMasuk64Export implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
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

        $this->total_nilai_perolehan = 0;
        $this->total_total_akumulasi_penyusutan = 0;
        $this->total_total_beban = 0;
        $this->total_total_nilai_buku = 0;
	}

    public function collection()
    {
        ini_set('max_execution_time', 1800);

        $data = Kib::select('jurnals.dari_lokasi', 'kibs.*')
                ->join('jurnals', 'kibs.no_key', '=', 'jurnals.no_key')
                ->where("kibs.nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->where("kibs.kode_jurnal", "102")
                ->where("kibs.tahun_spj", 2019)
                ->orderBy('kibs.no_register', 'ASC')
                ->get()
                ->toArray();

        $aset_susut = array();
        $i = 0;
        $j = 0;
        $jumlah_barang = 0;
        $tahun_acuan = date('Y')-1;
        $masa_terpakai;
        $total_nilai_perolehan = 0;
        $total_akumulasi_penyusutan = 0;
        $total_akumulasi_penyusutan_berjalan = 0;
        $total_beban = 0;
        $total_nilai_buku = 0;
        $total_rehab = 0;
        $jumlah_rehab = 0;

        $total_total_nilai_perolehan = 0;
        $total_total_akumulasi_penyusutan = 0;
        $total_total_beban = 0;
        $total_total_nilai_buku = 0;

        // loop khusus menjumlahkan nilai berdasarkan kesamaan kode rekening 64
        foreach($data as $value) {
            $lokasi_asal = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $value["dari_lokasi"])->first();

            if(!empty($lokasi_asal)) {
                $nama_lokasi = $lokasi_asal->nama_lokasi;
            } else {
                $nama_lokasi = "";
            }

            $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $value["kode_64"])->first();

            $saldo_kosong = false;
            $aset_induk = false;
            $aset_rehab = false;
            $kib_e = false;

            if($value["saldo_barang"] === 0) {
                $saldo_kosong = true;
            }

            if(substr($value["kode_108"],0,11) === "1.3.5.01.01") {
                $kib_e = true;
            }

            if($saldo_kosong) {
                continue;
            }

            if(!empty($mm)) {
                $masa_manfaat = (int)$mm->masa_manfaat;
                $mm_induk = (int)$mm->masa_manfaat;
            } else {
                if($this->bidang_barang == "A") {
                    $masa_manfaat = 0;
                    $mm_induk = 0;
                } else if($this->bidang_barang == "B") {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                } else if($this->bidang_barang == "C" || $this->bidang_barang == "D") {
                    $masa_manfaat = 50;
                    $mm_induk = 50;
                } else if($this->bidang_barang == "G") {
                    $masa_manfaat = 4;
                    $mm_induk = 4;
                } else if(strpos($this->bidang_barang, 'E') !== false) {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                }
            }

            if($aset_induk) {
                //untuk mengambil aset rehab berdasarkan id aset induk
                $rehabs = Rehab::where("aset_induk_id", $value["no_register"])->get();

                if(!empty($rehabs)) {
                    $detail_penyusutan = $rehabs->toArray();
                }

                $count = sizeof($detail_penyusutan);

                $status_aset = 1;

                $jumlah_barang_tmp = $value["saldo_barang"];
                $tahun_pengadaan_tmp = intval($value["tahun_pengadaan"]);
                $nilai_pengadaan_tmp = floatval($value["harga_total_plus_pajak_saldo"]);
                $persen = 0;
                $penambahan_nilai = 0;
                $masa_tambahan = 0;
                $masa_terpakai_tmp = 0;
                $sisa_masa_tmp = $masa_manfaat;
                $penyusutan_per_tahun_tmp = $nilai_pengadaan_tmp/$masa_manfaat;
                $akumulasi_penyusutan_tmp = 0;
                $akumulasi_penyusutan_berjalan_tmp = 0;
                $nilai_buku_tmp = 0;
                $index = 0;
                $k = 0;

                for($th = $tahun_pengadaan_tmp; $th <= $tahun_acuan; $th++) {
                    $tahun_rehab = intval($detail_penyusutan[$index]["tahun_rehab"]);
                    if($th == $tahun_rehab) {
                        $jumlah_renov_tahunx = 0;
                        $tahun_pembanding = 0;

                        if($tahun_pengadaan_tmp == $tahun_rehab) {
                            $nilai_buku_tmp = $nilai_pengadaan_tmp;
                        }

                        for ($x = 0; $x < $count; $x++) {
                             $tahun_pembanding = intval($detail_penyusutan[$x]["tahun_rehab"]);

                             if($tahun_pembanding === $tahun_rehab) {
                                $jumlah_renov_tahunx++;
                             }
                        }

                        if($jumlah_renov_tahunx == 1) {
                            $persen = $detail_penyusutan[$index]["nilai_rehab"]/$nilai_pengadaan_tmp*100;

                            $masa_tambah = Masa_tambahan::where('min', '<', $persen)->orderBy('min', 'asc')->first();

                            if(!empty($masa_tambahan)) {
                                $masa_tambahan = $masa_tambah->masa_tambahan;
                            }

                            $rehab_id = intval($detail_penyusutan[$index]["rehab_id"]);

                            if($rehab_id == 'F73536.19012915273754' || $rehab_id == 'F73536.19020411480071' || $rehab_id == 'F8DA36.19032710574252') {
                                $sisa_masa_tmp += $masa_tambahan;
                                if($sisa_masa_tmp > $mm_induk) {
                                    $masa_manfaat = $mm_induk;
                                    $sisa_masa_tmp = $mm_induk;
                                } else {
                                    $masa_manfaat = $sisa_masa_tmp;
                                }
                                $masa_terpakai_tmp = 1;
                            } else {
                                ++$masa_terpakai_tmp;
                            }

                            $nilai_buku_tmp += $detail_penyusutan[$index]["nilai_rehab"];

                            if($sisa_masa_tmp > 0) {
                                $penyusutan_per_tahun_tmp = $nilai_buku_tmp/$sisa_masa_tmp;
                            } else {
                                $penyusutan_per_tahun_tmp = 0;
                            }

                            $akumulasi_penyusutan_tmp += $penyusutan_per_tahun_tmp;
                            $nilai_buku_tmp -= $penyusutan_per_tahun_tmp;
                            --$sisa_masa_tmp;
                            $nilai_pengadaan_tmp += $detail_penyusutan[$index]["nilai_rehab"];
                            $akumulasi_penyusutan_berjalan_tmp += $penyusutan_per_tahun_tmp;

                            if($index < $count-1) {
                                $index++;
                            }
                        } else {
                            $penambahan_nilai = 0;
                            for ($y=0; $y<$jumlah_renov_tahunx; $y++) {
                                $penambahan_nilai += $detail_penyusutan[$index]["nilai_rehab"];
                                if($index < $count-1 && $y < ($jumlah_renov_tahunx - 1)) {
                                    $index++;
                                }
                            }

                            $persen = $penambahan_nilai/$nilai_pengadaan_tmp*100;
                            $masa_tambah = Masa_tambahan::where('min', '<', $persen)->orderBy('min', 'asc')->first();

                            if(!empty($masa_tambah)) {
                                $masa_tambahan = $masa_tambah->masa_tambahan;
                            }

                            $rehab_id = intval($detail_penyusutan[$index]["rehab_id"]);

                            if($rehab_id == 'F73536.19012915273754' || $rehab_id == 'F73536.19020411480071' || $rehab_id == 'F8DA36.19032710574252') {
                                $sisa_masa_tmp += $masa_tambahan;
                                if($sisa_masa_tmp > $mm_induk) {
                                    $masa_manfaat = $mm_induk;
                                    $sisa_masa_tmp = $mm_induk;
                                } else {
                                    $masa_manfaat = $sisa_masa_tmp;
                                }
                                $masa_terpakai_tmp = 1;
                            } else {
                                ++ $masa_terpakai_tmp;
                            }

                            $nilai_buku_tmp += $penambahan_nilai;
                            $penyusutan_per_tahun_tmp = $nilai_buku_tmp/$sisa_masa_tmp;
                            $akumulasi_penyusutan_tmp += $penyusutan_per_tahun_tmp;
                            $nilai_buku_tmp -= $penyusutan_per_tahun_tmp;
                            $nilai_pengadaan_tmp += $penambahan_nilai;

                            $akumulasi_penyusutan_berjalan_tmp += $penyusutan_per_tahun_tmp;

                            if($index < $count-1) {
                                $index++;
                            }
                        }
                    } else {
                        ++$masa_terpakai_tmp;
                        --$sisa_masa_tmp;
                        $akumulasi_penyusutan_tmp += $penyusutan_per_tahun_tmp;
                        $nilai_buku_tmp = $nilai_pengadaan_tmp - $akumulasi_penyusutan_tmp;
                        $akumulasi_penyusutan_berjalan_tmp += $penyusutan_per_tahun_tmp;

                        if($masa_terpakai_tmp > $masa_manfaat) {
                            $akumulasi_penyusutan_tmp = $nilai_pengadaan_tmp;
                            $akumulasi_penyusutan_berjalan_tmp = $nilai_pengadaan_tmp;
                            $nilai_buku_tmp = 0;
                            $sisa_masa_tmp = 0;
                        }
                    }
                }

                $akumulasi_penyusutan_tmp -= $penyusutan_per_tahun_tmp;
                $jumlah_barang += $jumlah_barang_tmp;

                if($this->bidang_barang == "A" || $kib_e == true) {
                    $akumulasi_penyusutan_tmp = 0;
                    $akumulasi_penyusutan_berjalan_tmp = 0;
                    $penyusutan_per_tahun_tmp = 0;
                    $nilai_buku_tmp = $nilai_pengadaan_tmp;
                    $akumulasi_penyusutan = 0;
                    $beban = 0;
                    $nilai_buku = $nilai_pengadaan_tmp;
                }

                $aset_susut[$i++] =
                    array(
                        "nama_lokasi" => $nama_lokasi,
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
                        "kode_64" => $value["kode_64"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_pengadaan" => $tahun_pengadaan_tmp,
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai_tmp,
                        "masa_sisa" => $sisa_masa_tmp,
                        "nilai_pengadaan" => $nilai_pengadaan_tmp,
                        "beban" => $penyusutan_per_tahun_tmp,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan_tmp,
                        "nilai_buku" => $nilai_buku_tmp
                    );

                $no_register = $value["id_aset"] . "-" . $value["kode_sub_kel_at"];
                $kode_13 = $value["kode_sub_kel_at"];
                $id_aset = $value["id_aset"];

                $total_nilai_perolehan += $nilai_pengadaan_tmp;
                $total_akumulasi_penyusutan += $akumulasi_penyusutan_tmp;
                $total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan_tmp;
                $total_beban += $penyusutan_per_tahun_tmp;
                $total_nilai_buku += $nilai_buku_tmp;
            } else {
                $masa_terpakai = $tahun_acuan - $value["tahun_pengadaan"] + 1;
                $masa_sisa = $masa_manfaat - $masa_terpakai;
                $nilai_perolehan_tmp = floatval($value["harga_total_plus_pajak_saldo"]);
                $status_aset = 0;
                $jumlah_barang_tmp = $value["saldo_barang"];

                if($masa_terpakai > $masa_manfaat) {
                    $nilai_perolehan = $nilai_perolehan_tmp;
                    $akumulasi_penyusutan = $nilai_perolehan_tmp;
                    $akumulasi_penyusutan_berjalan = $nilai_perolehan_tmp;
                    $masa_sisa = 0;
                    $beban = 0;
                    $nilai_buku = 0;
                } else {
                    $nilai_perolehan = $nilai_perolehan_tmp;
                    $akumulasi_penyusutan = (($masa_terpakai-1)/$masa_manfaat)*$nilai_perolehan_tmp;
                    $akumulasi_penyusutan_berjalan = ($masa_terpakai/$masa_manfaat)*$nilai_perolehan_tmp;
                    $beban = 1/$masa_manfaat*$nilai_perolehan_tmp;
                    $nilai_buku = $nilai_perolehan - ($akumulasi_penyusutan+$beban);
                }

                if($this->bidang_barang == "A" || $kib_e == true) {
                    $akumulasi_penyusutan = 0;
                    $akumulasi_penyusutan_berjalan = 0;
                    $beban = 0;
                    $nilai_buku = $nilai_perolehan_tmp;
                }

                $jumlah_barang += $jumlah_barang_tmp;

                $total_nilai_perolehan += $nilai_perolehan;
                $total_akumulasi_penyusutan += $akumulasi_penyusutan;
                $total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan;
                $total_beban += $beban;
                $total_nilai_buku += $nilai_buku;

                $aset_susut[$i++] =
                    array(
                        "nama_lokasi" => $nama_lokasi,
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
                        "kode_64" => $value["kode_64"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_pengadaan" => $value["tahun_pengadaan"],
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai,
                        "masa_sisa" => $masa_sisa,
                        "nilai_perolehan" => $nilai_perolehan,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan,
                        "beban" => $beban,
                        "nilai_buku" => $nilai_buku
                    );
            }

            $total_total_nilai_perolehan_tmp = $nilai_perolehan;
            $total_total_nilai_perolehan += $total_total_nilai_perolehan_tmp;
            $this->total_total_nilai_perolehan = $total_total_nilai_perolehan;

            $total_total_akumulasi_penyusutan_tmp = $akumulasi_penyusutan;
            $total_total_akumulasi_penyusutan += $total_total_akumulasi_penyusutan_tmp;
            $this->total_total_akumulasi_penyusutan = $total_total_akumulasi_penyusutan;

            $total_total_beban_tmp = $beban;
            $total_total_beban += $total_total_beban_tmp;
            $this->total_total_beban = $total_total_beban;

            $total_total_nilai_buku_tmp = $nilai_buku;
            $total_total_nilai_buku += $total_total_nilai_buku_tmp;
            $this->total_total_nilai_buku = $total_total_nilai_buku;
        }

        array_multisort(array_column($aset_susut, 'no_register'), SORT_ASC, $aset_susut);
        $export = collect($aset_susut);

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
            [
                "LOKASI ASAL", "NO REGISTER", "KODE 108",
                "KODE 64", "NAMA BARANG", "MERK/ALAMAT",
                "TAHUN PENGADAAN", "MASA MANFAAT",
                "MASA TERPAKAI", "MASA SISA",
                "NILAI PEROLEHAN", "AKUMULASI PENYUSUTAN",
                "BEBAN", "NILAI BUKU"
            ],
            [
                2,3,4,
                5,6,7,
                8,9,
                10,11,
                12,13,
                14,15
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
                $event->sheet->freezePane('P5');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A3:O3')->applyFromArray([
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
                $event->sheet->getStyle('A4:O4')->applyFromArray([
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
                $event->sheet->getStyle('A5:O'.$max)->applyFromArray([
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
                $event->sheet->getStyle('A'.($max+1).':O'.($max+1))->applyFromArray([
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
                $event->sheet->getDelegate()->mergeCells('A1:O1');
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
                $event->sheet->getStyle('A3:O3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A3:H3')->getAlignment()->setWrapText(true);
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
                $event->sheet->getStyle('L4:O4')->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );

                ///////centering
                // D tgl ba st
                $event->sheet->getStyle('H4:K'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                //////////end centering

                /////////column
                // B Lokasi Asal
                $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                // C no register
                $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                // D kode 108
                $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(18);
                $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                // E kode 64
                $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(12);
                $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                // F nama barang
                $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(20);
                $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                // G merk alamat
                $event->sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(20);
                $event->sheet->getStyle('G3:G'.$max)->getAlignment()->setWrapText(true);
                // H tahun pengadaan
                $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                // I masa manfaat
                $event->sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('I3:I'.$max)->getAlignment()->setWrapText(true);
                // J masa terpakai
                $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                // K masa sisa
                $event->sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('K3:K'.$max)->getAlignment()->setWrapText(true);

                ////////end column

                $date = date('d/m/Y');
                $f1 = $max+1;
                $f2 = $max+3;

                ///////////total
                $event->sheet->getStyle('A'.$f1.':O'.$f1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('H'.$f1.':K'.$f1);
                $event->sheet->getDelegate()->setCellValue('H'.$f1, "TOTAL");
                $event->sheet->getDelegate()->setCellValue('L'.$f1, $this->total_total_nilai_perolehan);
                $event->sheet->getStyle('L'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('M'.$f1, $this->total_total_akumulasi_penyusutan);
                $event->sheet->getStyle('M'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('N'.$f1, $this->total_total_beban);
                $event->sheet->getStyle('N'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('O'.$f1, $this->total_total_nilai_buku);
                $event->sheet->getStyle('O'.$f1)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                ///////////end total

                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('B'.$f2.':G'.$f2);
                    $event->sheet->getDelegate()->mergeCells('H'.$f2.':I'.$f2);
                    $event->sheet->getDelegate()->mergeCells('J'.$f2.':O'.$f2);
                    $event->sheet->getStyle('B'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('J'.$f2)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('B'.$f2, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('J'.$f2, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('B'.$f2, "NIP");
                        $event->sheet->getDelegate()->setCellValue('J'.$f2, "NIP");
                    }

                    $f2++;
                }
            },
        ];
	}

	public function columnFormats(): array
    {
        return [
            'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'N' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'O' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

	public function title(): string
    {
        return 'Data Penyusutan';
    }

}
