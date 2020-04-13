<?php

namespace App\Http\Controllers\API\ExportPdf\Penyusutan;
use PDF;
use MPDF;
use Validator;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use Illuminate\Http\Request;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Masa_tambahan;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Kamus\Sub_rincian_108;
use App\Models\Jurnal\Rincian_koreksi;
use App\Models\Kamus\Sub_sub_rincian_108;
use App\Models\Kamus\Kamus_mapping_permen;

use App\Http\Resources\Jurnal\KibCollection;
use App\Http\Resources\Jurnal\RehabCollection;
use App\Models\Kamus\Kamus_masa_manfaat_tambahan;
use App\Http\Resources\Jurnal\Rincian_masukCollection;
use App\Http\Controllers\API\BaseController as BaseController;

class Penyusutan_pdf extends BaseController
{
    public $nomor_lokasi;
    public $kode_kepemilikan;
    public $bidang_barang;
    public $jenis_aset;
    public $nama_lokasi;
    public $nama_jurnal;

    public function __construct()
    {
        $this->tahun_sekarang = date('Y')-1;
    }

    public function exportSusut108($nomor_lokasi, $bidang_barang, $kode_kepemilikan, $jenis_aset)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($kode_kepemilikan == '0' && $jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
            ->where("bidang_barang", 'like', $bidang_barang ."%")
            ->get()
            ->toArray();
        } else if($kode_kepemilikan != '0' && $jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
            ->where("bidang_barang", 'like', $bidang_barang ."%")
            ->where("kode_kepemilikan", $kode_kepemilikan)
            ->get()
            ->toArray();
        } else if($kode_kepemilikan == '0' && $jenis_aset != '0'){
            if($nomor_lokasi == '12.01.35.16.111.00001') {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    if($bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            } else {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    $kode_108 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $bidang_barang ."%")
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            }
        } else if($kode_kepemilikan != '0' && $jenis_aset != '0') {
            if($nomor_lokasi == '12.01.35.16.111.00001') {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    if($bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("kode_kepemilikan", $kode_kepemilikan)
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            } else {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    $kode_108 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $bidang_barang ."%")
                ->where("kode_kepemilikan", $kode_kepemilikan)
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            }
        }

        $nama_unit = Kamus_lokasi::select("nama_lokasi")->where("nomor_lokasi", 'like', $nomor_lokasi . "%")->first();

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
                if($bidang_barang == "A") {
                    $masa_manfaat = 0;
                    $mm_induk = 0;
                } else if($bidang_barang == "B") {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                } else if($bidang_barang == "C" || $bidang_barang == "D") {
                    $masa_manfaat = 50;
                    $mm_induk = 50;
                } else if($bidang_barang == "G") {
                    $masa_manfaat = 4;
                    $mm_induk = 4;
                } else if(strpos($bidang_barang, 'E') !== false) {
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

                if($bidang_barang == "A" || $kib_e == true) {
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

                if($bidang_barang == "A" || $kib_e == true) {
                    $akumulasi_penyusutan = 0;
                    $akumulasi_penyusutan_berjalan = 0;
                    $beban = 0;
                    $nilai_buku = $nilai_perolehan_tmp;
                }

                $jumlah_barang += $jumlah_barang_tmp;

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
                        "nilai_pengadaan" => $nilai_perolehan,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan,
                        "beban" => $beban,
                        "nilai_buku" => $nilai_buku
                    );
            }
        }

        array_multisort(array_column($aset_susut, 'no_register'), SORT_ASC, $aset_susut);
        $data = collect($aset_susut);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }

        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan KIB";

        $nama_file = "Laporan Penyusutan KIB " . $bidang_barang . " " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusut108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi]);

        return $pdf->stream($nama_file.$format_file);
    }

    public function exportSusut64($nomor_lokasi, $bidang_barang, $kode_kepemilikan, $jenis_aset)
    {
        if($bidang_barang == "A") {
            $kode_64 = '1.3.1';
        } else if($bidang_barang == "B") {
            $kode_64 = '1.3.2';
        } else if($bidang_barang == "C") {
            $kode_64 = '1.3.3';
        } else if($bidang_barang == "D") {
            $kode_64 = '1.3.4';
        } else if($bidang_barang == "E") {
            $kode_64 = '1.3.5';
        } if($bidang_barang == "F") {
            $kode_64 = '1.3.6';
        } if($bidang_barang == "G") {
            $kode_64 = '1.5.3';
        }

        if($kode_kepemilikan == '0' && $jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
            ->where("kode_64", 'like', $kode_64 ."%")
            ->get()
            ->toArray();
        } else if($kode_kepemilikan != '0' && $jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
            ->where("kode_64", 'like', $kode_64 ."%")
            ->where("kode_kepemilikan", $kode_kepemilikan)
            ->get()
            ->toArray();
        } else if($kode_kepemilikan == '0' && $jenis_aset != '0'){
            if($nomor_lokasi == '12.01.35.16.111.00001' || $nomor_lokasi == '12.01.35.16.111.00002') {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_64 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_64 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_64 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_64 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_64 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_64 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_64 = "1.3.6";
                    }

                    $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                        ->where("kode_64", 'like', $kode_64 . "%")
                        ->get()
                        ->toArray();

                } else if($jenis_aset == "R") {
                    if($bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }

                    $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                        ->where("kode_108", 'like', $kode_108 . "%")
                        ->get()
                        ->toArray();
                }
            } else {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_64 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_64 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_64 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_64 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_64 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_64 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_64 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    $kode_64 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $bidang_barang ."%")
                ->where("kode_64", 'like', $kode_64 . "%")
                ->get()
                ->toArray();
            }
        } else if($kode_kepemilikan != '0' && $jenis_aset != '0') {
            if($nomor_lokasi == '12.01.35.16.111.00001' || $nomor_lokasi == '12.01.35.16.111.00002') {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_64 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_64 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_64 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_64 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_64 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_64 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_64 = "1.3.6";
                    }

                    $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                        ->where("kode_kepemilikan", $kode_kepemilikan)
                        ->where("kode_64", 'like', $kode_64 . "%")
                        ->get()
                        ->toArray();
                } else if($jenis_aset == "R") {
                    if($bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }

                    $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                        ->where("kode_kepemilikan", $kode_kepemilikan)
                        ->where("kode_108", 'like', $kode_108 . "%")
                        ->get()
                        ->toArray();
                }
            } else {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_64 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_64 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_64 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_64 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_64 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_64 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_64 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    $kode_64 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $bidang_barang ."%")
                ->where("kode_kepemilikan", $kode_kepemilikan)
                ->where("kode_64", 'like', $kode_64 . "%")
                ->get()
                ->toArray();
            }
        }

        $rekap_64 = array();
        // loop khusus build map 64
        foreach($data as $value) {
            $kode_64 = $value["kode_64"];

            if($kode_64 != '' || !is_null($kode_64) || $kode_64 != 0) {
                $uraian = Kamus_rekening::select("uraian_64")->where('kode_64', $kode_64)->first();
            }

            if(!is_null($uraian)) {
                $uraian_64 = $uraian->uraian_64;
            }

            $found = false;
            foreach($rekap_64 as $key => $value)
            {
                if ($value["kode_64"] == $kode_64) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_64)) {
                    array_push($rekap_64, array(
                        "kode_64" => 0,
                        "uraian_64" => 0,
                        "nilai_perolehan" => 0,
                        "akumulasi_penyusutan" => 0,
                        "beban" => 0,
                        "akumulasi_penyusutan_berjalan" => 0,
                        "nilai_buku" => 0
                    ));
                } else {
                    array_push($rekap_64, array(
                        "kode_64" => $kode_64,
                        "uraian_64" => $uraian_64,
                        "nilai_perolehan" => 0,
                        "akumulasi_penyusutan" => 0,
                        "beban" => 0,
                        "akumulasi_penyusutan_berjalan" => 0,
                        "nilai_buku" => 0
                    ));
                }
            }
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
                if($bidang_barang == "A") {
                    $masa_manfaat = 0;
                    $mm_induk = 0;
                } else if($bidang_barang == "B") {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                } else if($bidang_barang == "C" || $bidang_barang == "D") {
                    $masa_manfaat = 50;
                    $mm_induk = 50;
                } else if($bidang_barang == "G") {
                    $masa_manfaat = 4;
                    $mm_induk = 4;
                } else if(strpos($bidang_barang, 'E') !== false) {
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

                if($bidang_barang == "A" || $kib_e == true) {
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
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_perolehan" => $tahun_pengadaan_tmp,
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai_tmp,
                        "masa_sisa" => $sisa_masa_tmp,
                        "nilai_pengadaan" => $nilai_pengadaan_tmp,
                        "beban" => $penyusutan_per_tahun_tmp,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan_tmp,
                        "nilai_buku" => $nilai_buku_tmp
                    );

                $kode_64 = $value["kode_64"];

                foreach($rekap_64 as $key => $rekap)
                {
                    if($kode_64 != '' || !is_null($kode_64) || $kode_64 != 0) {
                        if ($rekap["kode_64"] == $kode_64) {
                            $rekap_64[$key]["nilai_perolehan"] += $nilai_pengadaan_tmp;
                            $rekap_64[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan_tmp;
                            $rekap_64[$key]["beban"] += $penyusutan_per_tahun_tmp;
                            $rekap_64[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan_tmp;
                            $rekap_64[$key]["nilai_buku"] += $nilai_buku_tmp;
                            break;
                        }
                    } else {
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

                if($bidang_barang == "A" || $kib_e == true) {
                    $akumulasi_penyusutan = 0;
                    $akumulasi_penyusutan_berjalan = 0;
                    $beban = 0;
                    $nilai_buku = $nilai_perolehan_tmp;
                }

                $jumlah_barang += $jumlah_barang_tmp;


                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
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

                $kode_64 = $value["kode_64"];

                foreach($rekap_64 as $key => $rekap)
                {
                    if($kode_64 != '' || !is_null($kode_64) || $kode_64 != 0) {
                        if ($rekap["kode_64"] == $kode_64) {
                            $rekap_64[$key]["nilai_perolehan"] += $nilai_perolehan;
                            $rekap_64[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan;
                            $rekap_64[$key]["beban"] += $beban;
                            $rekap_64[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan;
                            $rekap_64[$key]["nilai_buku"] += $nilai_buku;
                            break;
                        }
                    } else {
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
        $data = collect($rekap_64);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan 64 KIB";

        $nama_file = "Laporan Penyusutan 64 KIB " . $bidang_barang . " " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusut64', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi]);
        // $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        return $pdf->stream($nama_file.$format_file);
    }

    public function exportSusutAk108($nomor_lokasi, $bidang_barang, $kode_kepemilikan, $jenis_aset)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($kode_kepemilikan == '0' && $jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
            ->where("bidang_barang", 'like', $bidang_barang ."%")
            ->get()
            ->toArray();
        } else if($kode_kepemilikan != '0' && $jenis_aset == '0'){
            $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
            ->where("bidang_barang", 'like', $bidang_barang ."%")
            ->where("kode_kepemilikan", $kode_kepemilikan)
            ->get()
            ->toArray();
        } else if($kode_kepemilikan == '0' && $jenis_aset != '0'){
            if($nomor_lokasi == '12.01.35.16.111.00001') {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    if($bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            } else {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    $kode_108 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $bidang_barang ."%")
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            }
        } else if($kode_kepemilikan != '0' && $jenis_aset != '0') {
            if($nomor_lokasi == '12.01.35.16.111.00001') {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    if($bidang_barang == "A") {
                        $kode_108 = "1.5.4.01.01.01.001";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.5.4.01.01.01.002";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.5.4.01.01.01.003";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.5.4.01.01.01.004";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.5.4.01.01.01.005";
                    }
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("kode_kepemilikan", $kode_kepemilikan)
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            } else {
                if($jenis_aset == "A") {
                    if($bidang_barang == "G"){
                        $kode_108 = "1.5.3";
                    } else if($bidang_barang == "A"){
                        $kode_108 = "1.3.1";
                    } else if($bidang_barang == "B"){
                        $kode_108 = "1.3.2";
                    } else if($bidang_barang == "C"){
                        $kode_108 = "1.3.3";
                    } else if($bidang_barang == "D"){
                        $kode_108 = "1.3.4";
                    } else if($bidang_barang == "E"){
                        $kode_108 = "1.3.5";
                    } else if($bidang_barang == "F"){
                        $kode_108 = "1.3.6";
                    }
                } else if($jenis_aset == "R") {
                    $kode_108 = "1.5.4.01.01";
                }
                $data = Kib::where("nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("bidang_barang", 'like', $bidang_barang ."%")
                ->where("kode_kepemilikan", $kode_kepemilikan)
                ->where("kode_108", 'like', $kode_108 . "%")
                ->get()
                ->toArray();
            }
        }

        $rekap_108p = array();
        // loop khusus build map 64
        foreach($data as $value) {
            $kode_108 = substr($value["kode_108"],0,14);

            if($kode_108 != '' || !is_null($kode_108) || $kode_108 != 0) {
                $uraian = Kamus_mapping_permen::select("kode_2", "uraian_2")->where('permen_1', '108')
                                                                    ->where('permen_2', '108p')
                                                                    ->where('kode_1', $kode_108)
                                                                    ->first();
            }

            if(!is_null($uraian)) {
                $kode_108p = $uraian->kode_2;
                $uraian_108p = $uraian->uraian_2;
            } else {
                $kode_108p = null;
                $uraian_108p = "";
            }

            $found = false;
            foreach($rekap_108p as $key => $value)
            {
                if ($value["kode_108p"] == $kode_108p) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_108p)) {
                    array_push($rekap_108p, array(
                        "kode_108p" => 0,
                        "uraian_108p" => "",
                        "nilai_perolehan" => 0,
                        "akumulasi_penyusutan" => 0,
                        "beban" => 0,
                        "akumulasi_penyusutan_berjalan" => 0,
                        "nilai_buku" => 0
                    ));
                } else {
                    array_push($rekap_108p, array(
                        "kode_108p" => $kode_108p,
                        "uraian_108p" => $uraian_108p,
                        "nilai_perolehan" => 0,
                        "akumulasi_penyusutan" => 0,
                        "beban" => 0,
                        "akumulasi_penyusutan_berjalan" => 0,
                        "nilai_buku" => 0
                    ));
                }
            }
        }

        $nama_unit = Kamus_lokasi::select("nama_lokasi")->where("nomor_lokasi", 'like', $nomor_lokasi . "%")->first();

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
            if(is_null($value["kode_108"])) {
                $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $value["kode_64"])->first();
            } else {
                $mm = Kamus_rekening::select("masa_manfaat")->where("kode_108", 'like', $value["kode_108"])->first();
            }

            $kode_108 = substr($value["kode_108"],0,14);

            if($kode_108 != '' || !is_null($kode_108) || $kode_108 != 0) {
                $uraian = Kamus_mapping_permen::select("kode_2", "uraian_2")->where('permen_1', '108')
                                                                    ->where('permen_2', '108p')
                                                                    ->where('kode_1', $kode_108)
                                                                    ->first();
            }

            if(!is_null($uraian)) {
                $kode_108p = $uraian->kode_2;
                $uraian_108p = $uraian->uraian_2;
            } else {
                $kode_108p = null;
                $uraian_108p = "";
            }

            $saldo_kosong = false;
            $aset_induk = false;
            $aset_rehab = false;
            $kib_e = false;

            if($value["saldo_barang"] === 0) {
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
                if($bidang_barang == "A") {
                    $masa_manfaat = 0;
                    $mm_induk = 0;
                } else if($bidang_barang == "B") {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                } else if($bidang_barang == "C" || $bidang_barang == "D") {
                    $masa_manfaat = 50;
                    $mm_induk = 50;
                } else if($bidang_barang == "G") {
                    $masa_manfaat = 4;
                    $mm_induk = 4;
                } else if(strpos($bidang_barang, 'E') !== false) {
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

                if($bidang_barang == "A" || $kib_e == true) {
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
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_perolehan" => $tahun_pengadaan_tmp,
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai_tmp,
                        "masa_sisa" => $sisa_masa_tmp,
                        "nilai_pengadaan" => $nilai_pengadaan_tmp,
                        "beban" => $penyusutan_per_tahun_tmp,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan_tmp,
                        "nilai_buku" => $nilai_buku_tmp
                    );

                $total_nilai_perolehan += $nilai_pengadaan_tmp;
                $total_akumulasi_penyusutan += $akumulasi_penyusutan_tmp;
                $total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan_tmp;
                $total_beban += $penyusutan_per_tahun_tmp;
                $total_nilai_buku += $nilai_buku_tmp;

                foreach($rekap_108p as $key => $rekap)
                {
                    if($kode_108p != '' || !is_null($kode_108p) || $kode_108p != 0) {
                        if ($rekap["kode_108p"] == $kode_108p) {
                            $rekap_108p[$key]["nilai_perolehan"] += $nilai_pengadaan_tmp;
                            $rekap_108p[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan_tmp;
                            $rekap_108p[$key]["beban"] += $penyusutan_per_tahun_tmp;
                            $rekap_108p[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan_tmp;
                            $rekap_108p[$key]["nilai_buku"] += $nilai_buku_tmp;
                            break;
                        }
                    } else {
                        $rekap_108p[$key]["nilai_perolehan"] += $nilai_pengadaan_tmp;
                        $rekap_108p[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan_tmp;
                        $rekap_108p[$key]["beban"] += $penyusutan_per_tahun_tmp;
                        $rekap_108p[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan_tmp;
                        $rekap_108p[$key]["nilai_buku"] += $nilai_buku_tmp;
                        break;
                    }
                }
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

                if($bidang_barang == "A" || $kib_e == true) {
                    $akumulasi_penyusutan = 0;
                    $akumulasi_penyusutan_berjalan = 0;
                    $beban = 0;
                    $nilai_buku = $nilai_perolehan_tmp;
                }

                $jumlah_barang += $jumlah_barang_tmp;

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108" => $value["kode_108"],
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

                foreach($rekap_108p as $key => $rekap)
                {
                    if($kode_108p != '' || !is_null($kode_108p) || $kode_108p != 0) {
                        if ($rekap["kode_108p"] == $kode_108p) {
                            $rekap_108p[$key]["nilai_perolehan"] += $nilai_perolehan;
                            $rekap_108p[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan;
                            $rekap_108p[$key]["beban"] += $beban;
                            $rekap_108p[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan;
                            $rekap_108p[$key]["nilai_buku"] += $nilai_buku;
                            break;
                        }
                    } else {
                        $rekap_108p[$key]["nilai_perolehan"] += $nilai_perolehan;
                        $rekap_108p[$key]["akumulasi_penyusutan"] += $akumulasi_penyusutan;
                        $rekap_108p[$key]["beban"] += $beban;
                        $rekap_108p[$key]["akumulasi_penyusutan_berjalan"] += $akumulasi_penyusutan_berjalan;
                        $rekap_108p[$key]["nilai_buku"] += $nilai_buku;
                        break;
                    }
                }
            }
        }

        array_multisort(array_column($rekap_108p, 'kode_108p'), SORT_ASC, $rekap_108p);
        // $export = collect($rekap_108p);
        $data = collect($rekap_108p);


        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Akumulasi Penyusutan KIB";

        $nama_file = "Laporan Akumulasi Penyusutan 108 KIB " . $bidang_barang . " " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusutAk108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi, 'jenis_aset'=>$jenis_aset]);

        return $pdf->stream($nama_file.$format_file);
        // return $data;
    }

    public function exportSusutReklasKeluar64($nomor_lokasi, $jenis_aset)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($jenis_aset == "A") {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where("kode_64", 'not like', '1.5.4%')
                                    ->orderBy('kode_64')
                                    ->get();

        } else {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
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

                $total_nilai_perolehan += $nilai_pengadaan_tmp;
                $total_akumulasi_penyusutan += $akumulasi_penyusutan_tmp;
                $total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan_tmp;
                $total_beban += $penyusutan_per_tahun_tmp;
                $total_nilai_buku += $nilai_buku_tmp;

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

                $total_nilai_perolehan += $nilai_perolehan;
                $total_akumulasi_penyusutan += $akumulasi_penyusutan;
                $total_akumulasi_penyusutan_berjalan += $akumulasi_penyusutan_berjalan;
                $total_beban += $beban;
                $total_nilai_buku += $nilai_buku;

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
        $data = collect($rekap_64);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Keluar 64";

        $nama_file = "Laporan Penyusutan Aset Reklas Keluar 64 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusutReklasKeluar64', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi, 'jenis_aset'=>$jenis_aset]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportSusutReklasKeluar108($nomor_lokasi, $jenis_aset)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($jenis_aset == "A") {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where("kode_108", 'not like', '1.5.4%')
                                    ->orderBy('kode_108')
                                    ->get();

        } else {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where("kode_108", 'like', '1.5.4%')
                                    ->orderBy('kode_108')
                                    ->get();
        }

        if(!empty($data)) {
            $data = $data->toArray();
        } else {
            $data = [];
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
        foreach($data as $d) {
            $aset = Kib::where('id_aset', $d['id_aset'])->first();
            $value = json_decode(json_encode($aset), true);

            $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $d["kode_64"])->first();

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

            $induk = Rehab::select("aset_induk_id")->where("aset_induk_id", 'like', $d['id_aset'])->first();
            $anak = Rehab::select("rehab_id")->where("rehab_id", 'like', $d['id_aset'])->first();

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

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108" => $d["kode_108"],
                        "kode_108_baru" => $d["kode_108_baru"],
                        "nama_barang" => $d["nama_barang"],
                        "merk_alamat" => $d["merk_alamat"],
                        "tahun_perolehan" => $tahun_pengadaan_tmp,
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai_tmp,
                        "masa_sisa" => $sisa_masa_tmp,
                        "nilai_pengadaan" => $nilai_pengadaan_tmp,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan_tmp,
                        "beban" => $penyusutan_per_tahun_tmp,
                        "nilai_buku" => $nilai_buku_tmp
                    );

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

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108" => $d["kode_108"],
                        "kode_108_baru" => $d["kode_108_baru"],
                        "nama_barang" => $d["nama_barang"],
                        "merk_alamat" => $d["merk_alamat"],
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
        $data = collect($aset_susut);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Keluar 108";

        $nama_file = "Laporan Penyusutan Aset Reklas Keluar 108 " . $nama_lokasi." ".$this->tahun_sekarang ;

        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusutReklasKeluar108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi, 'jenis_aset'=>$jenis_aset]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportSusutReklasMasuk64($nomor_lokasi, $jenis_aset)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($jenis_aset == "A") {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where("kode_64_baru", 'not like', '1.5.4%')
                                    ->orderBy('nomor_lokasi')
                                    ->orderBy('kode_64')
                                    ->get();

        } else {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where("kode_64_baru", 'like', '1.5.4%')
                                    ->orderBy('nomor_lokasi')
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
                    "kode_64_baru" => $kode_64_baru,
                    "kode_64" => $kode_64,
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
        foreach($data as $d) {
            $aset = Kib::where('id_aset', $d['id_aset'])->first();
            $value = json_decode(json_encode($aset), true);

            if($jenis_aset == "A") {
                $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $d["kode_64_baru"])->first();
            } else {
                $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $d["kode_64"])->first();
            }

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

                $kode_64 = $d["kode_64"];
                $kode_64_baru = $d["kode_64_baru"];

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
        $data = collect($rekap_64);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Masuk 64";

        $nama_file = "Laporan Penyusutan Aset Reklas Masuk 64 " . $nama_lokasi." ".$this->tahun_sekarang ;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusutReklasMasuk64', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi, 'jenis_aset'=>$jenis_aset]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportSusutReklasMasuk108($nomor_lokasi, $jenis_aset)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($jenis_aset == "A") {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where('kode_108_baru', 'not like', '1.5.4%')
                                    ->orderBy('nomor_lokasi')
                                    ->orderBy('kode_108')
                                    ->get();

        } else {
            $data = Rincian_koreksi::where('kode_koreksi', 'like', '%6')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                    ->where('kode_108_baru', 'like', '1.5.4%')
                                    ->orderBy('nomor_lokasi')
                                    ->orderBy('kode_108')
                                    ->get();
        }

        if(!empty($data)) {
            $data = $data->toArray();
        } else {
            $data = [];
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
        foreach($data as $d) {
            $aset = Kib::where('id_aset', $d['id_aset'])->first();
            if(!is_null($aset)) {
                $value = json_decode(json_encode($aset), true);
            }

            if($jenis_aset == "A") {
                $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $d["kode_64_baru"])->first();
            } else {
                $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $d["kode_64"])->first();
            }

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

            $induk = Rehab::select("aset_induk_id")->where("aset_induk_id", 'like', $d['id_aset'])->first();
            $anak = Rehab::select("rehab_id")->where("rehab_id", 'like', $d['id_aset'])->first();

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

                //$rehabs = Rehab::where("aset_induk_id", $d['id_aset'])->orderBy('tahun_rehab', 'asc')->get();

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

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108_baru" => $d["kode_108_baru"],
                        "kode_108" => $d["kode_108"],
                        "nama_barang" => $d["nama_barang"],
                        "merk_alamat" => $d["merk_alamat"],
                        "tahun_perolehan" => $tahun_pengadaan_tmp,
                        "masa_manfaat" => $masa_manfaat,
                        "masa_terpakai" => $masa_terpakai_tmp,
                        "masa_sisa" => $sisa_masa_tmp,
                        "nilai_pengadaan" => $nilai_pengadaan_tmp,
                        "akumulasi_penyusutan" => $akumulasi_penyusutan_tmp,
                        "beban" => $penyusutan_per_tahun_tmp,
                        "nilai_buku" => $nilai_buku_tmp
                    );
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

                $aset_susut[$i++] =
                    array(
                        "no_register" => $value["no_register"],
                        "kode_108_baru" => $d["kode_108_baru"],
                        "kode_108" => $d["kode_108"],
                        "nama_barang" => $d["nama_barang"],
                        "merk_alamat" => $d["merk_alamat"],
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
        $data = collect($aset_susut);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Masuk 108";

        $nama_file = "Laporan Penyusutan Aset Reklas Masuk 108 " . $nama_lokasi." ".$this->tahun_sekarang ;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.penyusutan.exportSusutReklasMasuk108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi, 'jenis_aset'=>$jenis_aset]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;

    }

}
