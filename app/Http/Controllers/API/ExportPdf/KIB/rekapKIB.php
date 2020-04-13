<?php

namespace App\Http\Controllers\API\ExportPdf\KIB;
use PDF;
use MPDF;
use Validator;
use App\Models\Jurnal\Kib;
use App\Models\jurnal\Rehab;
use Illuminate\Http\Request;
use App\Models\Jurnal\Kib_awal;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Rincian_108;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Kamus\Sub_rincian_108;
use App\Http\Controllers\API\BaseController as BaseController;

class rekapKib extends BaseController
{
    public $nomor_lokasi;
    public $kode_kepemilikan;
    public $bidang_barang;

    public function __construct()
    {
        $this->total_nilai = 0;
        $this->tahun_sekarang = date('Y')-1;
    }

    public function exportLaporanKibUrutTahun($bidang_barang, $nomor_lokasi, $kode_kepemilikan)
    {
        if($bidang_barang == "A") {
            $data = Kib::select('kode_108', 'no_register', 'nama_barang', 'merk_alamat', 'luas_tanah', 'tahun_pengadaan', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "B") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.2%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "C") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.3%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "D") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','panjang_tanah','lebar_tanah','luas_tanah','no_imb', 'tgl_imb', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.4%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "E") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.5%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')->get()
                ->toArray();
        } else if($bidang_barang == "F") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('kode_108', 'like', '1.3.6%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "G") {
            $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','baik','kb','rb','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.5.3%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "RB") {
            if($nomor_lokasi == '12.01.35.16.445.00001.00001') {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('kode_108', 'like', '1.5.4%')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
            } else {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'perolehan_pemda', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('kode_108', 'like', '1.5.4%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
            }

        }else {
            $data = Kib::select('kode_108', 'no_register', 'nama_barang', 'merk_alamat', 'luas_tanah', 'tahun_pengadaan', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        }

        $asets = array();
        foreach ($data as $value) {
            $aset = $value;
            $no_register = (string)$value['no_register'] . ' ';
            $aset['no_register'] = $no_register;

            array_push($asets, $aset);
        }

        $data = collect($asets);
        // print_r($data->toArray());

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $nama_file = "Laporan KIB " . $bidang_barang . " " . $nama_lokasi." ".$this->tahun_sekarang ;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.laporanKib', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'bidang_barang'=>$bidang_barang]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }

    public function exportKib64($nomor_lokasi, $kode_kepemilikan)
    {
        $data = Kib::where('nomor_lokasi', 'like', $nomor_lokasi . '%')->where('kode_kepemilikan', $kode_kepemilikan)->get();

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
                        "nilai" => 0
                    ));
                } else {
                    array_push($rekap_64, array(
                        "kode_64" => $kode_64,
                        "uraian_64" => $uraian_64,
                        "nilai" => 0
                    ));
                }
            }
        }

        //loop khusus penjumlahan data
        foreach($data as $value) {
            $kode_64 = $value["kode_64"];
            $this->total_nilai += $value["harga_total_plus_pajak_saldo"];

            foreach($rekap_64 as $key => $rekap)
            {
                if($kode_64 != '' || !is_null($kode_64) || $kode_64 != 0) {
                    if ($rekap["kode_64"] == $kode_64) {
                        $rekap_64[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                        $found = true;
                        break;
                    }
                } else {
                    $rekap_64[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                    $found = true;
                    break;
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

        $nama_file = "Rekap KIB 64 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.RekapKib64', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi],[],['orientation'=>'P']);

        return $pdf->stream($nama_file.$format_file);
    }

    public function exportKib108($nomor_lokasi, $kode_kepemilikan)
    {
        $data = Kib::where('nomor_lokasi', 'like', $nomor_lokasi . '%')->where('kode_kepemilikan', $kode_kepemilikan)->get();

        $data_rehab = Rehab::where('nomor_lokasi', 'like', $nomor_lokasi . '%')->get();

        $rekap_108 = array();

        // loop khusus build map 108
        foreach($data as $value) {
            $rehab = false;
            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $rehab = true;
                        break;
                    }
                }
            }

            $kode_108 = $value["kode_108"];

            if(!is_null($kode_108)) {
                $kode_108 = substr($value["kode_108"], 0, 11);
                $uraian = Rincian_108::select("uraian_rincian")->where('rincian', $kode_108)->first();
            }

            if(!is_null($uraian)) {
                $uraian_108 = $uraian->uraian_rincian;
            }

            $found = false;
            foreach($rekap_108 as $key => $value)
            {
                if ($value["kode_108"] == $kode_108) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_108)) {
                    if(!$rehab) {
                        array_push($rekap_108, array(
                            "kode_108" => 0,
                            "uraian_108" => 0,
                            "nilai" => 0
                        ));
                    }
                } else {
                    array_push($rekap_108, array(
                        "kode_108" => $kode_108,
                        "uraian_108" => $uraian_108,
                        "nilai" => 0
                    ));
                }
            }
        }

        //untuk penjumlahan data
        foreach($data as $value) {
            $kode_108 = substr($value["kode_108"], 0, 11);
            $this->total_nilai += $value["harga_total_plus_pajak_saldo"];

            $rehab = false;
            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $kode_108_induk = Kib::select("kode_108")->where("id_aset", $value_rehab["aset_induk_id"])->first();
                        if(!empty($kode_108_induk)) {
                            $kode_108 = $kode_108_induk->kode_108;
                            $kode_108 = substr($value["kode_108"], 0, 11);
                            break;
                        }
                    }
                }
            }

            foreach($rekap_108 as $key => $rekap)
            {
                if($kode_108 != '' || !is_null($kode_108) || $kode_108 != 0) {
                    if ($rekap["kode_108"] == $kode_108) {
                        $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                        $found = true;
                        break;
                    }
                } else {
                    $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                    $found = true;
                    break;
                }
            }
        }

        array_multisort(array_column($rekap_108, 'kode_108'), SORT_ASC, $rekap_108);

        $data = collect($rekap_108);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }

        $data->toArray();

        $nama_file = "Rekap KIB 108 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.RekapKib108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi],[],['orientation'=>'P']);

        return $pdf->stream($nama_file.$format_file);
    }

    public function exportLaporanRekapRincian108($nomor_lokasi, $kode_kepemilikan)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $data = Kib::where('nomor_lokasi', 'like', $nomor_lokasi . '%')->where('kode_kepemilikan', $kode_kepemilikan)->get();

        $data_rehab = Rehab::where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')->get();

        $rekap_108 = array();

        // loop khusus build map 108
        foreach($data as $value) {
            $rehab = false;
            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $rehab = true;
                        break;
                    }
                }
            }

            $kode_108 = $value["kode_108"];

            if(!is_null($kode_108)) {
                $kode_108 = substr($value["kode_108"], 0, 11);
                $uraian = Rincian_108::select("uraian_rincian")->where('rincian', $kode_108)->first();
            }

            if(!is_null($uraian)) {
                $uraian_108 = $uraian->uraian_rincian;
            }

            $found = false;
            foreach($rekap_108 as $key => $value)
            {
                if ($value["kode_108"] == $kode_108) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_108)) {
                    if(!$rehab) {
                        array_push($rekap_108, array(
                            "kode_108" => 0,
                            "uraian_108" => 0,
                            "nilai" => 0
                        ));
                    }
                } else {
                    array_push($rekap_108, array(
                        "kode_108" => $kode_108,
                        "uraian_108" => $uraian_108,
                        "nilai" => 0
                    ));
                }
            }
        }

        //untuk penjumlahan data
        foreach($data as $value) {
            $kode_108 = substr($value["kode_108"], 0, 11);
            $this->total_nilai += $value["harga_total_plus_pajak_saldo"];

            $rehab = false;
            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $kode_108_induk = Kib::select("kode_108")->where("id_aset", $value_rehab["aset_induk_id"])->first();
                        if(!empty($kode_108_induk)) {
                            $kode_108 = $kode_108_induk->kode_108;
                            $kode_108 = substr($value["kode_108"], 0, 11);
                            break;
                        }
                    }
                }
            }

            foreach($rekap_108 as $key => $rekap)
            {
                if($kode_108 != '' || !is_null($kode_108) || $kode_108 != 0) {
                    if ($rekap["kode_108"] == $kode_108) {
                        $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                        $found = true;
                        break;
                    }
                } else {
                    $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                    $found = true;
                    break;
                }
            }
        }

        array_multisort(array_column($rekap_108, 'kode_108'), SORT_ASC, $rekap_108);

        $data = collect($rekap_108);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }

        $data->toArray();

        $nama_file = "Laporan Rekap Barang Per Rincian Permen 108 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.rekapRincian108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi], [],['orientation'=>'P']);

        return $pdf->stream($nama_file.$format_file);

        // return $data;

    }

    public function exportLaporanRekapSubRincian108($nomor_lokasi, $kode_kepemilikan)
    {
        $data = Kib::where('nomor_lokasi', 'like', $nomor_lokasi . '%')->where('kode_kepemilikan', $kode_kepemilikan)->get();

        $data_rehab = Rehab::where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')->get();

        $rekap_108 = array();

        // loop khusus build map 108
        foreach($data as $value) {
            $rehab = false;
            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $rehab = true;
                        break;
                    }
                }
            }

            $kode_108 = $value["kode_108"];

            if(!is_null($kode_108)) {
                $kode_108 = substr($value["kode_108"], 0, 14);
                $uraian = Sub_rincian_108::select("uraian_sub_rincian")->where('sub_rincian', $kode_108)->first();
            }

            if(!is_null($uraian)) {
                $uraian_108 = $uraian->uraian_sub_rincian;
            }

            $found = false;
            foreach($rekap_108 as $key => $value)
            {
                if ($value["kode_108"] == $kode_108) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if(is_null($kode_108)) {
                    if(!$rehab) {
                        array_push($rekap_108, array(
                            "kode_108" => 0,
                            "uraian_108" => 0,
                            "nilai" => 0
                        ));
                    }
                } else {
                    array_push($rekap_108, array(
                        "kode_108" => $kode_108,
                        "uraian_108" => $uraian_108,
                        "nilai" => 0
                    ));
                }
            }
        }

        foreach($data as $value) {
            $kode_108 = substr($value["kode_108"], 0, 14);
            $this->total_nilai += $value["harga_total_plus_pajak_saldo"];

            if(!empty($data_rehab)) {
                foreach ($data_rehab as $value_rehab) {
                    if($value_rehab["rehab_id"] == $value["id_aset"]) {
                        $kode_108_induk = Kib::select("kode_108")->where("id_aset", $value_rehab["aset_induk_id"])->first();
                        if(!empty($kode_108_induk)) {
                            $kode_108 = $kode_108_induk->kode_108;
                            $kode_108 = substr($value["kode_108"], 0, 14);
                            break;
                        }
                    }
                }
            }

            foreach($rekap_108 as $key => $rekap)
            {
                if($kode_108 != '' || !is_null($kode_108) || $kode_108 != 0) {
                    if ($rekap["kode_108"] == $kode_108) {
                        $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                        $found = true;
                        break;
                    }
                } else {
                    $rekap_108[$key]["nilai"] += $value["harga_total_plus_pajak_saldo"];
                    $found = true;
                    break;
                }
            }
        }

        array_multisort(array_column($rekap_108, 'kode_108'), SORT_ASC, $rekap_108);

        $data = collect($rekap_108);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }

        $data->toArray();

        $nama_file = "Laporan Rekap Barang Per Sub Rincian Permen 108 " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.rekapSubRincian108', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi],[],['orientation'=> 'P']);

        return $pdf->stream($nama_file.$format_file);
    }

    public function exportLaporanKibAwal($bidang_barang, $nomor_lokasi, $kode_kepemilikan)
    {
        ini_set("pcre.backtrack_limit", "5000000");
        if($bidang_barang == "A") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'luas_tanah', 'tahun_pengadaan', 'merk_alamat', 'status_tanah', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "B") {
            $data = Kib_awal::select('no_register', 'kode_64', 'kode_108', 'nama_barang','merk_alamat', 'tipe', 'ukuran', 'cc', 'bahan', 'tahun_pengadaan', 'baik', 'kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.2%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "C") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'baik', 'kb', 'rb', 'jumlah_lantai', 'bahan', 'konstruksi', 'luas_lantai', 'merk_alamat','tgl_sertifikat', 'no_sertifikat', 'luas_bangunan', 'status_tanah', 'no_regs_induk_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.3%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "D") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'konstruksi', 'tahun_pengadaan', 'panjang_tanah', 'lebar_tanah', 'luas_bangunan', 'merk_alamat', 'tgl_sertifikat', 'no_sertifikat', 'status_tanah', 'no_regs_induk_tanah', 'harga_total_plus_pajak_saldo', 'baik', 'kb', 'rb', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.4%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "E") {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'merk_alamat', 'tipe', 'bahan', 'ukuran', 'saldo_barang', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.5%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')->get()
                ->toArray();
        } else if($bidang_barang == "F") {
            $data = Kib_awal::select('nama_barang', 'no_register', 'konstruksi', 'jumlah_lantai', 'bahan', 'luas_bangunan', 'merk_alamat', 'tgl_sertifikat', 'no_sertifikat', 'status_tanah', 'harga_total_plus_pajak_saldo', 'nilai_lunas', 'keterangan')
                ->where('kode_108', 'like', '1.3.6%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('saldo_barang', '>', 0)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        }else {
            $data = Kib_awal::select('nama_barang', 'kode_64', 'kode_108', 'no_register', 'luas_tanah', 'tahun_pengadaan', 'merk_alamat', 'status_tanah', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kode_108', 'like', '1.3.1%')
                ->where("nomor_lokasi", 'like', $nomor_lokasi . "%")
                ->where('kode_kepemilikan', $kode_kepemilikan)
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

            if($bidang_barang == "A"){
                $kode_64 = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            } else if($bidang_barang == "B"){
                $kode_64 = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'];
                $new_no_register = $no_register.'<br>'.PHP_EOL.''.$kode_64;
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
            } else if($bidang_barang == "C"){
                $kode_64 = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'];
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

                $bahan_konstruksi = (string)$value['bahan'].'<br>'.PHP_EOL.''.$value['konstruksi'];
                $aset['bahan'] = $bahan_konstruksi;
            } else if($bidang_barang == "D"){
                $kode_64 = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;

                $konstruksi_tahun = (string)$value['konstruksi'].'<br>'.PHP_EOL.''.$value['tahun_pengadaan'];
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
            } else if($bidang_barang == 'E'){
                $kode_64 = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            } else if($bidang_barang == "F"){
                $aset['no_register'] = $no_register;

                $total_nilai_lunas_tmp = $value["nilai_lunas"];
                $total_nilai_lunas+=$total_nilai_lunas_tmp;
                $total_nilai_lunas = $total_nilai_lunas;
            } else {
                $kode_64 = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'];
                $aset['kode_64'] = $kode_64;
                $aset['no_register'] = $no_register;
            }

            $total_harga_tmp = $value["harga_total_plus_pajak_saldo"];
            $total_harga+=$total_harga_tmp;

            array_push($asets, $aset);
        }

        $data = collect($asets);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["bidang_barang"] = $bidang_barang;

        $data["nama_jurnal"] = "Saldo Awal KIB";

        $nama_file = "Saldo Awal KIB " . $bidang_barang . " " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.laporanSaldoAwalKib', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'bidang_barang'=>$bidang_barang, 'kode_kepemilikan'=>$kode_kepemilikan]);

        return $pdf->stream($nama_file.$format_file);
    }

    public function exportLaporanKibAwalInv($nomor_lokasi, $kode_kepemilikan)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        $export = array();
        $data = Kib_awal::select('kamus_lokasis.nomor_lokasi', 'kib_awals.*')
                ->join('kamus_lokasis', 'kib_awals.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                ->where("kib_awals.nomor_lokasi", 'like', $nomor_lokasi . '%')
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
                    "no_register" => $value["no_register"]."<br>".PHP_EOL.$value["kode_64"]."<br>".PHP_EOL.$value["kode_108"],
                    "nama_barang" => $value["nama_barang"],
                    "merk_alamat" => $value["merk_alamat"]."<br>".PHP_EOL.$value["tipe"],
                    "no_sertifikat" => $value["no_sertifikat"]."<br>".PHP_EOL.$value["no_rangka_seri"],
                    "no_mesin" => $value["no_mesin"]."<br>".PHP_EOL.$value["nopol"],
                    "bahan" => $value["bahan"],
                    "tahun_pengadaan" => $value["tahun_pengadaan"],
                    "konstruksi" => $value["konstruksi"]."<br>".PHP_EOL.$value["ukuran"],
                    "satuan" => $value["satuan"],
                    "baik" => $keterangan,
                    "saldo_barang" => $value["saldo_barang"],
                    "harga_total_plus_pajak_saldo" => $value["harga_total_plus_pajak_saldo"],
                    "keterangan" => $value["keterangan"],
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Buku Inventaris";

        $nama_file = "Laporan Kib Awal Saldo Inventaris " . $nama_lokasi." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        $pdf = MPDF::loadView('html.KIB.laporanSaldoAwalInv', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi, 'kode_kepemilikan'=>$kode_kepemilikan]);

        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }
}
