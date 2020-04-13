<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Rincian_108;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Masa_tambahan;
use App\Models\Kamus\Kamus_rekening;
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

class LaporanPenyusutan108Export implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
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
        $this->bidang_barang = $args['bidang_barang'];
		$this->kode_kepemilikan = $args['kode_kepemilikan'];
        $this->jenis_aset = $args['jenis_aset'];
		$this->nama_lokasi = $args['nama_lokasi'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun_sekarang = date('Y')-1;

        $this->total_nilai_perolehan = 0;
        $this->total_akumulasi_penyusutan = 0;
        $this->total_akumulasi_penyusutan_berjalan = 0;
        $this->total_beban = 0;
        $this->total_nilai_buku = 0;
	}

    public function collection()
    {
        ini_set('max_execution_time', 1800);

        if($this->kode_kepemilikan == '0' && $this->jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $this->nomor_lokasi . '%')
            ->where("bidang_barang", 'like', $this->bidang_barang ."%")
            ->get()
            ->toArray();
        } else if($this->kode_kepemilikan != '0' && $this->jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $this->nomor_lokasi . '%')
            ->where("bidang_barang", 'like', $this->bidang_barang ."%")
            ->where("kode_kepemilikan", $this->kode_kepemilikan)
            ->get()
            ->toArray();
        } else if($this->kode_kepemilikan == '0' && $this->jenis_aset != '0'){
            if($this->nomor_lokasi == '12.01.35.16.111.00001') {
                if($this->jenis_aset == "A") {
                    if($this->bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($this->bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($this->bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($this->bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($this->bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($this->bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($this->bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($this->jenis_aset == "R") {
                    if($this->bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($this->bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($this->bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($this->bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($this->bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }
                }
                $data = Kib::where("nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            } else {
                if($this->jenis_aset == "A") {
                    if($this->bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($this->bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($this->bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($this->bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($this->bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($this->bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($this->bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($this->jenis_aset == "R") {
                    $kode_108 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $this->bidang_barang ."%")
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            }
        } else if($this->kode_kepemilikan != '0' && $this->jenis_aset != '0') {
            if($this->nomor_lokasi == '12.01.35.16.111.00001') {
                if($this->jenis_aset == "A") {
                    if($this->bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($this->bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($this->bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($this->bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($this->bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($this->bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($this->bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($this->jenis_aset == "R") {
                    if($this->bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($this->bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($this->bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($this->bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($this->bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }
                }
                $data = Kib::where("nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->where("kode_kepemilikan", $this->kode_kepemilikan)
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            } else {
                if($this->jenis_aset == "A") {
                    if($this->bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($this->bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($this->bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($this->bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($this->bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($this->bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($this->bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($this->jenis_aset == "R") {
                    $kode_108 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $this->nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $this->bidang_barang ."%")
                ->where("kode_kepemilikan", $this->kode_kepemilikan)
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            }
        }

        $nama_unit = Kamus_lokasi::select("nama_lokasi")->where("nomor_lokasi", 'like', $this->nomor_lokasi . "%")->first();

        if(!is_null($nama_unit)) {
            $nama_unit = $nama_unit->nama_lokasi;
        } else {
            $nama_unit = "";
        }

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

        // loop khusus menjumlahkan nilai berdasarkan kesamaan kode rekening 64
        foreach($data as $value) {
            $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $value["kode_64"])->first();

            $saldo_kosong = false;
            $aset_induk = false;
            $aset_rehab = false;
            $kib_e = false;

            if($value["saldo_barang"] == 0) {
                $saldo_kosong = true;
            }

            $kode_e = array("1.3.5.01",  "1.3.5.02",  "1.3.5.04",  "1.3.5.06",  "1.3.5.07");
            $sub_64 = substr($value["kode_64"], 0, 8);

            if(in_array($sub_64, $kode_e)) {
                if($value["kode_64"] == "1.3.5.01.12" || $value["kode_64"] == "1.3.5.01.14") {
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

            $induk = Rehab::select("aset_induk_id")->where("aset_induk_id", 'like', $value["id_aset"])->first();
            $anak = Rehab::select("rehab_id")->where("rehab_id", 'like', $value["id_aset"])->first();

            if(!is_null($induk)) {
                $aset_induk = true;
            }

            if(!is_null($anak)) {
                $aset_rehab = true;
            }

            if($saldo_kosong || $aset_rehab || $pakai_habis || $ekstrakom) {
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

            if($masa_manfaat == 0) {
                if($value['kode_108'] == '1.5.4.01.01.01.002') {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                } else if($value['kode_108'] == '1.5.4.01.01.01.003') {
                    $masa_manfaat = 50;
                    $mm_induk = 50;
                } else if($value['kode_108'] == '1.5.4.01.01.01.004') {
                    $masa_manfaat = 40;
                    $mm_induk = 40;
                } else if($value['kode_108'] == '1.5.4.01.01.01.005') {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                }
            }

            if($aset_induk) {
                //untuk mengambil aset rehab berdasarkan id aset induk
                $rehabs = Rehab::where("aset_induk_id", $value["id_aset"])->orderBy('tahun_rehab', 'asc')->get();

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

                            if($detail_penyusutan[$index]["tambah_manfaat"] == 1) {
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

                            if($detail_penyusutan[$index]["tambah_manfaat"] == 1) {
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
                            if($sisa_masa_tmp > 0) {
                                --$sisa_masa_tmp;
                            }

                            if($index < $count-1) {
                                $index++;
                            }
                        }
                    } else {
                        ++$masa_terpakai_tmp;

                        if($sisa_masa_tmp > 0) {
                            --$sisa_masa_tmp;
                        }

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

                $no_register = (string)$value['no_register'] . ' ';
                $value['no_register'] = $no_register;

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
                        "kode_64" => $value["kode_64"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_perolehan" => $tahun_pengadaan_tmp,
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai_tmp,
                        "masa_sisa" => $sisa_masa_tmp,
                        "nilai_pengadaan" => $nilai_pengadaan_tmp,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan_tmp,
                        "beban" => $penyusutan_per_tahun_tmp,
                        "nilai_buku" => $nilai_buku_tmp
                    );

                $this->total_nilai_perolehan += $nilai_pengadaan_tmp;
                $this->total_akumulasi_penyusutan += $akumulasi_penyusutan_tmp;
                $this->total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan_tmp;
                $this->total_beban += $penyusutan_per_tahun_tmp;
                $this->total_nilai_buku += $nilai_buku_tmp;
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

                $this->total_nilai_perolehan += $nilai_perolehan;
                $this->total_akumulasi_penyusutan += $akumulasi_penyusutan;
                $this->total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan;
                $this->total_beban += $beban;
                $this->total_nilai_buku += $nilai_buku;

                $no_register = (string)$value['no_register'] . ' ';
                $value['no_register'] = $no_register;

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
                        "kode_64" => $value["kode_64"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_perolehan" => $value["tahun_pengadaan"],
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai,
                        "masa_sisa" => $masa_sisa,
                        "nilai_perolehan" => $nilai_perolehan,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan,
                        "beban" => $beban,
                        "nilai_buku" => $nilai_buku
                    );
            }
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
            ["NO REGISTER", "KODE 108", "KODE 64", "NAMA BARANG", "MERK/ALAMAT", "TAHUN PEROLEHAN", "MASA MANFAAT", "MASA TERPAKAI", "MASA SISA", "NILAI PEROLEHAN", "AKUMULASI PENYUSUTAN", "BEBAN", "NILAI BUKU"],
            [
                2,3,4,5,6,7,8,9,10,11,12,13,14
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
                $event->sheet->freezePane('O5');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A3:N3')->applyFromArray([
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
                $event->sheet->getStyle('A4:N4')->applyFromArray([
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
                $event->sheet->getStyle('A5:N'.$max)->applyFromArray([
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
                $event->sheet->getStyle('A'.($max+1).':N'.($max+1))->applyFromArray([
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
                ->setOddFooter('&L&B '.$this->nama_jurnal.' '. $this->bidang_barang.' / '. $this->nama_lokasi.' / '.$this->tahun_sekarang.' / '.$this->kode_kepemilikan.' - Pemerintah Kab / kota / ' .$this->jenis_aset . '&R &P / &N');
                // end footer

                /////////header
                $event->sheet->getDelegate()->mergeCells('A1:N1');
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
                $event->sheet->getStyle('A3:N3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A3:N3')->getAlignment()->setWrapText(true);
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
                $event->sheet->getStyle('K4:N4')->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );

                /////////////centering
                $event->sheet->getStyle('G5:J'.$max)->applyFromArray([
                    'alignment' =>[
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                // end centering

                //////////column
                // B no register
                $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                // C kode 108
                $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(18);
                $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                // D kode 64
                $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(12);
                $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                // E nama barang
                $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(20);
                $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                // F merk / alamat
                $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                // G tahun perolehan
                $event->sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(15);
                $event->sheet->getStyle('G3:G'.$max)->getAlignment()->setWrapText(true);
                // H masa manfaat
                $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                // I masa terpakai
                $event->sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('I3:I'.$max)->getAlignment()->setWrapText(true);
                // J masa sisa
                $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(10);
                $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                // K nilai perolehan
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                // l akumulasi penyusutan
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                // m beban
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                // n nilai buku
                $event->sheet->getColumnDimension('N')->setAutoSize(true);



                $date = date('d/m/Y');
                $baris_total = $max+1;
                ////////// total
                $event->sheet->getDelegate()->mergeCells('F'.$baris_total.':G'.$baris_total);
                $event->sheet->getStyle('F'.$baris_total)->applyFromArray([ 'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->setCellValue('F'.$baris_total, "TOTAL");
                $event->sheet->getStyle('J'.$baris_total.':N'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getStyle('J'.$baris_total.':N'.$baris_total)->applyFromArray([ 'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->setCellValue('K'.$baris_total, $this->total_nilai_perolehan);
                $event->sheet->getDelegate()->setCellValue('L'.$baris_total, $this->total_akumulasi_penyusutan);
                $event->sheet->getDelegate()->setCellValue('M'.$baris_total, $this->total_beban);
                $event->sheet->getDelegate()->setCellValue('N'.$baris_total, $this->total_nilai_buku);
                ///////////end total

                $f1 = $max+3;
                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('A'.$f1.':D'.$f1);
                    $event->sheet->getDelegate()->mergeCells('E'.$f1.':I'.$f1);
                    $event->sheet->getDelegate()->mergeCells('J'.$f1.':N'.$f1);
                    $event->sheet->getStyle('A'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getStyle('J'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('J'.$f1, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                        $event->sheet->getDelegate()->setCellValue('J'.$f1, "NIP");
                    }

                    $f1++;
                }
            },
        ];
	}

	public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'L' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'M' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'N' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

	public function title(): string
    {
        return 'Data Penyusutan';
    }

}
