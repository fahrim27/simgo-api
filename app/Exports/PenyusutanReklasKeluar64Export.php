<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Rincian_108;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Masa_tambahan;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Jurnal\Rincian_koreksi;
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
use DB;

//penggantian tahun menggunakan db selector dengan implement DB class usage

class PenyusutanReklasKeluar64Export implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
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
        $this->jenis_aset = $args['jenis_aset'];
        $this->nama_lokasi = $args['nama_lokasi'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun_sekarang = DB::table('tahun_spj')->select('tahun')->first()->tahun;

        $this->total_nilai_perolehan = 0;
        $this->total_akumulasi_penyusutan = 0;
        $this->total_akumulasi_penyusutan_berjalan = 0;
        $this->total_beban = 0;
        $this->total_nilai_buku = 0;
    }

    public function collection()
    {
        ini_set('max_execution_time', 1800);

        if($this->jenis_aset == "A") {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                                    ->where("kode_64", 'not like', '1.5.4%')
                                    ->orderBy('kode_64')
                                    ->get();

        } else {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                                    ->where("kode_64", 'like', '1.5.4%')
                                    ->orderBy('kode_64')
                                    ->get();
        }

        if(!empty($data)) {
            $data = $data->toArray();
        } else {
            $data = [];
        }

        $rekap_64 = array();
        // loop khusus build map 64
        foreach($data as $value) {
            $kode_64 = $value["kode_64"];
            $kode_64_baru = $value["kode_64_baru"];
            $nomor_lokasi = $value["nomor_lokasi"];

            $found = false;
            foreach($rekap_64 as $key => $as)
            {
                if ($as["kode_64"] == $kode_64 && $as["kode_64_baru"] == $kode_64_baru) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_64)) {
                    $kode_64 = 0;
                }

                if(is_null($kode_64_baru)) {
                    $kode_64_baru = 0;
                }

                array_push($rekap_64, array(
                    "kode_64" => $kode_64,
                    "kode_64_baru" => $kode_64_baru,
                    "nilai_perolehan" => 0,
                    "akumulasi_penyusutan" => 0,
                    "beban" => 0,
                    "akumulasi_penyusutan_berjalan" => 0,
                    "nilai_buku" => 0
                ));
            }
        }

        $aset_susut = array();
        $i = 0;
        $j = 0;
        $jumlah_barang = 0;
        $tahun_acuan = DB::table('tahun_spj')->select('tahun')->first()->tahun;
        $masa_terpakai;
        $total_nilai_perolehan = 0;
        $total_akumulasi_penyusutan = 0;
        $total_akumulasi_penyusutan_berjalan = 0;
        $total_beban = 0;
        $total_nilai_buku = 0;
        $total_rehab = 0;
        $jumlah_rehab = 0;

        // loop khusus menjumlahkan nilai berdasarkan kesamaan kode rekening 64
        foreach($data as $d) {
            $aset = Kib::where('id_aset', $d['id_aset'])->first();
            $value = json_decode(json_encode($aset), true);

            $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $d["kode_64"])->first();

            $saldo_kosong = false;
            $aset_induk = false;
            $aset_rehab = false;
            $kib_e = false;

            $kode_e = array("1.3.5.01",  "1.3.5.02",  "1.3.5.04",  "1.3.5.06",  "1.3.5.07");
            $sub_64 = substr($d["kode_64"], 0, 8);

            if(in_array($sub_64, $kode_e)) {
                if($d["kode_64"] == "1.3.5.01.12" || $d["kode_64"] == "1.3.5.01.14") {
                    $kib_e = false;
                } else {
                    $kib_e = true;
                }
            }

            $pakai_habis = false;
            $ekstrakom = false;

            if($value["pos_entri"] == "PAKAI_HABIS") {
                $pakai_habis = true;
            }

            if($value["pos_entri"] == "EKSTRAKOMPTABEL") {
                $ekstrakom = true;
            }

            $induk = Rehab::select("aset_induk_id")->where("aset_induk_id", 'like', $d["id_aset"])->first();
            $anak = Rehab::select("rehab_id")->where("rehab_id", 'like', $d["id_aset"])->first();

            if(!is_null($induk)) {
                $aset_induk = true;
            }

            if(!is_null($anak)) {
                $aset_rehab = true;
            }

            if($aset_rehab || $pakai_habis || $ekstrakom) {
                continue;
            }

            if(!is_null($mm)) {
                if($mm->masa_manfaat == 0) {
                    $masa_manfaat = 0;
                    $mm_induk = 0;
                } else {
                    $masa_manfaat = (int)$mm->masa_manfaat;
                    $mm_induk = (int)$mm->masa_manfaat;
                }
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
                $rehabs = Rehab::join('kibs', 'kibs.id_aset', '=', 'rehabs.rehab_id')
                ->select('rehabs.rehab_id', 'kibs.tahun_pengadaan as tahun_rehab', 'kibs.harga_total_plus_pajak_saldo as nilai_rehab', 'kibs.kode_108 as kode_rek_rehab', 'rehabs.tambah_manfaat')
                ->where('rehabs.aset_induk_id', $value['id_aset'])
                ->orderBy('kibs.tahun_pengadaan', 'asc')->get();

                //$rehabs = Rehab::where("aset_induk_id", $d["id_aset"])->orderBy('tahun_rehab', 'asc')->get();

                if(!empty($rehabs)) {
                    $detail_penyusutan = $rehabs->toArray();
                }

                $count = sizeof($detail_penyusutan);

                $status_aset = 1;

                $jumlah_barang_tmp = $d["jumlah_barang"];
                $tahun_pengadaan_tmp = intval($value["tahun_pengadaan"]);
                $nilai_pengadaan_tmp = floatval($d["nilai"]);
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

                            $persen = (int)$persen;
                            $kode_108 = $detail_penyusutan[$index]["kode_rek_rehab"];

                            $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode_108 . '%')->first();

                            if(is_null($kode_64)) {
                                $kode_108 = substr($kode_108, 0, 14);
                                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode_108 . '%')->first();

                                if(is_null($kode_64)) {
                                    $kode_108 = substr($kode_108, 0, 11);
                                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode_108 . '%')->first();
                                } else {
                                    $kode_64 = $kode_64->kode_64;
                                }
                            } else {
                                $kode_64 = $kode_64->kode_64;
                            }

                            $kode_64 = substr($kode_64, 0, 8);

                            $masa_tambah = Masa_tambahan::where('kode_64', $kode_64)->where('minim', '<', $persen)->orderBy('minim', 'desc')->first();

                            if(!is_null($masa_tambah)) {
                                $masa_tambahan = $masa_tambah->masa_tambahan;
                            }

                            if($detail_penyusutan[$index]["tambah_manfaat"] == "0") {
                                ++$masa_terpakai_tmp;
                            } else {
                                $sisa_masa_tmp += $masa_tambahan;
                                if($sisa_masa_tmp > $mm_induk) {
                                    $masa_manfaat = $mm_induk;
                                    $sisa_masa_tmp = $mm_induk;
                                } else {
                                    $masa_manfaat = $sisa_masa_tmp;
                                }
                                $masa_terpakai_tmp = 1;
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

                            $persen = $detail_penyusutan[$index]["nilai_rehab"]/$nilai_pengadaan_tmp*100;

                            $persen = (int)$persen;
                            $kode_108 = $detail_penyusutan[$index]["kode_rek_rehab"];

                            $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode_108 . '%')->first();

                            if(is_null($kode_64)) {
                                $kode_108 = substr($kode_108, 0, 14);
                                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode_108 . '%')->first();

                                if(is_null($kode_64)) {
                                    $kode_108 = substr($kode_108, 0, 11);
                                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode_108 . '%')->first();
                                } else {
                                    $kode_64 = $kode_64->kode_64;
                                }
                            } else {
                                $kode_64 = $kode_64->kode_64;
                            }

                            $kode_64 = substr($kode_64, 0, 8);
                            $masa_tambah = Masa_tambahan::where('kode_64', $kode_64)->where('minim', '<', $persen)->orderBy('minim', 'desc')->first();

                            if(!is_null($masa_tambah)) {
                                $masa_tambahan = $masa_tambah->masa_tambahan;
                            }

                            if($detail_penyusutan[$index]["tambah_manfaat"] == "0") {
                                ++$masa_terpakai_tmp;
                            } else {
                                $sisa_masa_tmp += $masa_tambahan;
                                if($sisa_masa_tmp > $mm_induk) {
                                    $masa_manfaat = $mm_induk;
                                    $sisa_masa_tmp = $mm_induk;
                                } else {
                                    $masa_manfaat = $sisa_masa_tmp;
                                }
                                $masa_terpakai_tmp = 1;
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

                $this->total_nilai_perolehan += $nilai_pengadaan_tmp;
                $this->total_akumulasi_penyusutan += $akumulasi_penyusutan_tmp;
                $this->total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan_tmp;
                $this->total_beban += $penyusutan_per_tahun_tmp;
                $this->total_nilai_buku += $nilai_buku_tmp;

                $kode_64 = $d["kode_64"];
                $kode_64_baru = $d["kode_64_baru"];
                $nomor_lokasi_aset = $value["nomor_lokasi"];

                foreach($rekap_64 as $key => $rekap)
                {
                    if($kode_64 == $rekap['kode_64'] && $kode_64_baru == $rekap['kode_64_baru'])
                    {
                        $rekap_64[$key]["nilai_perolehan"] += $nilai_pengadaan_tmp;
                        $rekap_64[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan_tmp;
                        $rekap_64[$key]["beban"] += $penyusutan_per_tahun_tmp;
                        $rekap_64[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan_tmp;
                        $rekap_64[$key]["nilai_buku"] += $nilai_buku_tmp;
                        break;
                    }
                }
            } else {
                $masa_terpakai = $tahun_acuan - $value["tahun_pengadaan"] + 1;
                $masa_sisa = $masa_manfaat - $masa_terpakai;
                $nilai_perolehan_tmp = floatval($d["nilai"]);
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

                $this->total_nilai_perolehan += $nilai_perolehan;
                $this->total_akumulasi_penyusutan += $akumulasi_penyusutan;
                $this->total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan;
                $this->total_beban += $beban;
                $this->total_nilai_buku += $nilai_buku;

                $kode_64 = $d["kode_64"];
                $kode_64_baru = $d["kode_64_baru"];
                $nomor_lokasi_aset = $value["nomor_lokasi"];

                foreach($rekap_64 as $key => $rekap)
                {
                    if($kode_64 == $rekap['kode_64'] && $kode_64_baru == $rekap['kode_64_baru'])
                    {
                        $rekap_64[$key]["nilai_perolehan"] += $nilai_perolehan;
                        $rekap_64[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan;
                        $rekap_64[$key]["beban"] += $beban;
                        $rekap_64[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan;
                        $rekap_64[$key]["nilai_buku"] += $nilai_buku;
                        break;
                    }
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
            ["KODE 64", "KODE 64 BARU", "NILAI PEROLEHAN", "AKUMULASI PENYUSUTAN", "BEBAN", "AKUMULASI PENYUSUTAN BERJALAN", "NILAI BUKU"],
            [
                2,3,4,5,6,7,8
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
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                $event->sheet->getPageSetup()->setFitToPage(true);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                $event->sheet->setShowGridlines(false);
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                $event->sheet->freezePane('I5');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A3:H3')->applyFromArray([
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
                $event->sheet->getStyle('A4:H4')->applyFromArray([
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
                $event->sheet->getStyle('A5:H'.$max)->applyFromArray([
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
                $event->sheet->getStyle('A'.($max+1).':H'.($max+1))->applyFromArray([
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
                ->setOddFooter('&L&B '.$this->nama_jurnal.' / '. $this->nama_lokasi.' / '.$this->tahun_sekarang.' / ' .$this->jenis_aset . '&R &P / &N');
                // end footer

                /////////header
                $event->sheet->getDelegate()->mergeCells('A1:H1');
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
                $event->sheet->getStyle('A3:H3')->applyFromArray([
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
                $event->sheet->getStyle('A4:H4')->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );

                $date = date('d/m/Y');
                $baris_total = $max+1;

                $event->sheet->getDelegate()->mergeCells('A'.$baris_total.':C'.$baris_total);
                $event->sheet->getStyle('A'.$baris_total)->applyFromArray([ 'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->setCellValue('A'.$baris_total, "TOTAL");
                $event->sheet->getStyle('D'.$baris_total.':H'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getStyle('D'.$baris_total.':H'.$baris_total)->applyFromArray([ 'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);

                $event->sheet->getStyle('D'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getStyle('E'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getStyle('F'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getStyle('G'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getStyle('H'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('D'.$baris_total, $this->total_nilai_perolehan);
                $event->sheet->getDelegate()->setCellValue('E'.$baris_total, $this->total_akumulasi_penyusutan);
                $event->sheet->getDelegate()->setCellValue('F'.$baris_total, $this->total_beban);
                $event->sheet->getDelegate()->setCellValue('G'.$baris_total, $this->total_akumulasi_penyusutan_berjalan);
                $event->sheet->getDelegate()->setCellValue('H'.$baris_total, $this->total_nilai_buku);
                $f1 = $max+3;
                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('A'.$f1.':E'.$f1);
                    $event->sheet->getDelegate()->mergeCells('F'.$f1.':H'.$f1);
                    $event->sheet->getStyle('A'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('F'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('F'.$f1, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                        $event->sheet->getDelegate()->setCellValue('F'.$f1, "NIP");
                    }

                    $f1++;
                }
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'E' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'G' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'H' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

    public function title(): string
    {
        return 'Data Penyusutan';
    }

}
