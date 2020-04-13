<?php

namespace App\Http\Controllers\API\ExportPdf\Mutasi;
use MPDF;
use Validator;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Masa_tambahan;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Jurnal\Rincian_keluar;
use App\Http\Resources\Jurnal\Rincian_keluarCollection;
use App\Http\Controllers\API\BaseController as BaseController;

class Mutasi_pdf extends BaseController
{

    public $nomor_lokasi;
	public $kode_kepemilikan;
    public $bidang_barang;
    public $jenis_aset;

    public function __construct()
    {
        $this->tahun_sekarang = date('Y')-1;
    }

    public function exportMasuk108($nomor_lokasi)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $export = array();

        $total_jumlah_barang = 0;
        $total_harga_satuan = 0;
        $total_harga_total = 0;

        $data = Kib::select('jurnals.dari_lokasi', 'jurnals.no_ba_st', 'jurnals.tgl_ba_st', 'kibs.*')
                ->join('jurnals', 'kibs.no_key', '=', 'jurnals.no_key')
                ->where("kibs.nomor_lokasi", 'like', $nomor_lokasi . '%')
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
        $data = collect($export);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Masuk 108";

        $nama_file = "Laporan Mutasi Masuk 108 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.mutasi.exportMutasiMasuk108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportKeluar108($nomor_lokasi)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $data = new Rincian_keluarCollection(Rincian_keluar::select('jurnals.ke_lokasi', 'jurnals.no_ba_st', 'jurnals.tgl_ba_st', 'rincian_keluars.no_register', 'rincian_keluars.kode_108', 'rincian_keluars.kode_64', 'rincian_keluars.nama_barang', 'rincian_keluars.merk_alamat', 'rincian_keluars.jumlah_barang', 'rincian_keluars.harga_satuan', 'rincian_keluars.nilai_keluar', 'rincian_keluars.nopol')
                ->join('jurnals', 'rincian_keluars.no_key', '=', 'jurnals.no_key')
                ->where("rincian_keluars.nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("rincian_keluars.tahun_spj", 2019)
                ->orderBy('rincian_keluars.id_aset', 'ASC')
                ->get());

        $export = array();

        $total_jumlah_barang = 0;
        $total_harga_satuan = 0;
        $total_harga_total = 0;

        $i = 0;

        foreach ($data as $value) {
            $lokasi_tujuan = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $value["ke_lokasi"])->first();

            if(!empty($lokasi_tujuan)) {
                $nama_lokasi = $lokasi_tujuan->nama_lokasi;
            } else {
                $nama_lokasi = "";
            }

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
                    "nilai_keluar" => $value["nilai_keluar"],
                    "nopol" => $value["nopol"]
                );
        }

        array_multisort(array_column($export, 'no_register'), SORT_ASC, $export);
        $data = collect($export);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Keluar 108";

        $nama_file = "Laporan Mutasi Keluar 108 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.mutasi.exportMutasiKeluar108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;

    }

    public function exportMasuk64($nomor_lokasi)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $data = Kib::select('jurnals.dari_lokasi', 'kibs.*')
                ->join('jurnals', 'kibs.no_key', '=', 'jurnals.no_key')
                ->where("kibs.nomor_lokasi", 'like', $nomor_lokasi . '%')
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
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Masuk 64";

        $nama_file = "Laporan Mutasi Masuk 64 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.Mutasi.exportMutasiMasuk64', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportKeluar64($nomor_lokasi)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $data = Rincian_keluar::select('jurnals.ke_lokasi', 'rincian_keluars.*')
                ->join('jurnals', 'rincian_keluars.no_key', '=', 'jurnals.no_key')
                ->where("rincian_keluars.nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->where("rincian_keluars.kode_jurnal", "202")
                ->where("rincian_keluars.tahun_spj", 2019)
                ->orderBy('rincian_keluars.id_aset', 'ASC')
                ->get()
                ->toArray();

        $nama_unit = Kamus_lokasi::select("nama_lokasi")->where("nomor_lokasi", 'like', $nomor_lokasi . "%")->first();

        if(!empty($nama_unit)) {
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

        $total_total_nilai_perolehan = 0;
        $total_total_akumulasi_penyusutan = 0;
        $total_total_beban = 0;
        $total_total_nilai_buku = 0;

        // loop khusus menjumlahkan nilai berdasarkan kesamaan kode rekening 64
        foreach($data as $value) {
            $aset = Kib::where('id_aset', $value["id_aset"])->first();
            $lokasi_tujuan = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $value["ke_lokasi"])->first();

            if(!empty($lokasi_tujuan)) {
                $nama_lokasi = $lokasi_tujuan->nama_lokasi;
            } else {
                $nama_lokasi = "";
            }
            $mm = Kamus_rekening::select("masa_manfaat")->where("kode_64", 'like', $value["kode_64"])->first();

            $saldo_kosong = false;
            $aset_induk = false;
            $aset_rehab = false;
            $kib_e = false;

            if($value["jumlah_barang"] === 0) {
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
                if(substr($aset["bidang_barang"], 0, 1) == "A") {
                    $masa_manfaat = 0;
                    $mm_induk = 0;
                } else if(substr($aset["bidang_barang"], 0, 1) == "B") {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                } else if(substr($aset["bidang_barang"], 0, 1) == "C" || substr($aset["bidang_barang"], 0, 1) == "D") {
                    $masa_manfaat = 50;
                    $mm_induk = 50;
                } else if(substr($aset["bidang_barang"], 0, 1) == "G") {
                    $masa_manfaat = 4;
                    $mm_induk = 4;
                } else if(substr($aset["bidang_barang"], 0, 1) == "E") {
                    $masa_manfaat = 5;
                    $mm_induk = 5;
                }
            }

            if($aset_induk) {
                //untuk mengambil aset rehab berdasarkan id aset induk
                $rehabs = Rehab::where("aset_induk_id", $value["id_aset"])->get();

                if(!empty($rehabs)) {
                    $detail_penyusutan = $rehabs->toArray();
                }

                $count = sizeof($detail_penyusutan);

                $status_aset = 1;

                $jumlah_barang_tmp = $value["jumlah_barang"];
                $tahun_pengadaan_tmp = intval($aset["tahun_pengadaan"]);
                $nilai_pengadaan_tmp = floatval($value["nilai_keluar"]);
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
                        "no_register" => $aset["no_register"],
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
            } else {
                $masa_terpakai = $tahun_acuan - $aset["tahun_pengadaan"] + 1;
                $masa_sisa = $masa_manfaat - $masa_terpakai;
                $nilai_perolehan_tmp = floatval($value["nilai_keluar"]);
                $status_aset = 0;
                $jumlah_barang_tmp = $value["jumlah_barang"];

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

                $aset_susut[$i++] =
                    array(
                        "nama_lokasi" => $nama_lokasi,
                        "no_register" => $aset["no_register"],
                        "kode_108" => $value["kode_108"],
                        "kode_64" => $value["kode_64"],
                        "nama_barang" => $value["nama_barang"],
                        "merk_alamat" => $value["merk_alamat"],
                        "tahun_pengadaan" => $aset["tahun_pengadaan"],
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
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Keluar 64";

        $nama_file = "Laporan Mutasi Keluar 64 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.Mutasi.exportMutasiKeluar64', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportMasukBarang($nomor_lokasi)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $data = Rincian_keluar::select('kibs.no_register','kibs.kode_108','kibs.kode_64','kamus_rekenings.uraian_64','kibs.merk_alamat','kibs.no_sertifikat','kibs.no_rangka_seri','kibs.no_mesin','kibs.nopol','kibs.bahan','kibs.konstruksi','kibs.satuan','kib_awals.saldo_barang','kib_awals.harga_total_plus_pajak_saldo','rincian_keluars.jumlah_barang','rincian_keluars.nilai_keluar','kibs.saldo_barang as saldo_barang_baru','kibs.harga_total_plus_pajak_saldo as harga_total_plus_pajak_saldo_baru','kibs.tahun_pengadaan','kibs.keterangan')
                ->join('kamus_rekenings', 'rincian_keluars.kode_64', '=', 'kamus_rekenings.kode_64')
                ->join('kib_awals','rincian_keluars.id_aset','=','kib_awals.id_aset')
                ->join('kibs','rincian_keluars.id_aset','=','kibs.id_aset')
                ->where("rincian_keluars.nomor_lokasi", 'like', $nomor_lokasi . '%')
                ->orderBy('rincian_keluars.no_register', 'ASC')
                ->get()
                ->toArray();

        $asets = array();

        $total_harga_awal = 0;
        $total_nilai_keluar = 0;
        $total_harga_akhir = 0;

        foreach ($data as $value) {
            $aset = $value;
            $no_register = (string)$value['kode_108'] . '<br>'.PHP_EOL.''.$value['kode_64']. '<br>'.PHP_EOL.''.$value['no_register'];
            $aset['no_register'] = $no_register;

            $no_setifikat_rangka = (string)$value['no_sertifikat'].'<br>'.PHP_EOL.''.$value['no_rangka_seri'];
            $aset['no_sertifikat'] = $no_setifikat_rangka;

            $no_mesin_pol = (string)$value['no_mesin'].'<br>'.PHP_EOL.''.$value['nopol'];
            $aset['no_mesin'] = $no_mesin_pol;

            $bahan_konstruksi = (string)$value['bahan'].'<br>'.PHP_EOL.''.$value['konstruksi'];
            $aset['bahan']= $bahan_konstruksi;

            array_push($asets, $aset);
        }

        $data = collect($asets);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["kode_kepemilikan"] = $kode_kepemilikan;

        $data["nama_jurnal"] = "Laporan Mutasi Masuk Barang";

        $nama_file = "Laporan Mutasi Masuk Barang " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.Mutasi.exportMutasiMasukBarang', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportKeluarBarang($nomor_lokasi)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $data = Rincian_keluar::select('rincian_keluars.id_aset','kamus_rekenings.uraian_64','kibs.merk_alamat','kibs.no_sertifikat','kibs.no_rangka_seri','kibs.no_mesin','kibs.nopol','kibs.bahan','kibs.konstruksi','kibs.satuan','kib_awals.saldo_barang','kib_awals.harga_total_plus_pajak_saldo','rincian_keluars.jumlah_barang','rincian_keluars.nilai_keluar','kibs.saldo_barang as saldo_barang_baru','kibs.harga_total_plus_pajak_saldo as harga_total_plus_pajak_saldo_baru','kibs.tahun_pengadaan','kibs.keterangan')
                ->join('kamus_rekenings', 'rincian_keluars.kode_64', '=', 'kamus_rekenings.kode_64')
                ->join('kib_awals','rincian_keluars.id_aset','=','kib_awals.id_aset')
                ->join('kibs','rincian_keluars.id_aset','=','kibs.id_aset')
                ->where("rincian_keluars.nomor_lokasi", 'like', $nomor_lokasi . '%')
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

            array_push($asets, $aset);
        }

        $data = collect($asets);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        }

        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["kode_kepemilikan"] = $kode_kepemilikan;

        $data["nama_jurnal"] = "Laporan Mutasi Barang Keluar";

        $nama_file = "Laporan Mutasi Barang Keluar " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.mutasi.exportMutasiKeluarBarang', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'nomor_lokasi'=>$nomor_lokasi]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }
}
