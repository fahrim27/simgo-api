<?php

namespace App\Http\Controllers\API\Sinkron;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Kib_awal;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Mapping_lokasi;
use App\Models\Sinkron\Migrasi;
use Validator;

class MigrasiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doMigrate(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $kode_64 = '';
        $nomor_lokasi_17 = '';
        $id_aset = '';

        $asets = Kib_awal::where('nomor_lokasi', 'like', '%'.'13.02'.'%')->orderBy('nomor_lokasi')->get();

        foreach ($asets as $value) {
            $value = json_decode(json_encode($value), true);
            $kode_64 = $value["kode_64"];
            $nomor_lokasi_17 = $value["nomor_lokasi"];
            $id_aset = $value["id_aset"];
            $data = $value;

            $kode_108 = Kamus_rekening::select('kode_108')->where('kode_64', $kode_64)->first();
            $nomor_lokasi = Mapping_lokasi::select('nomor_lokasi_108')->where('nomor_lokasi_17', $nomor_lokasi_17)->first();

            if(!empty($kode_108)) {
                $data["kode_108"] = $kode_108->kode_108;
            }

            if(!empty($nomor_lokasi)) {
                $data["nomor_lokasi"] = $nomor_lokasi->nomor_lokasi_108;
            }

            if(!empty($kode_108) || !empty($nomor_lokasi)) {
                $update = Kib_awal::where('id_aset', $id_aset)->update($data);
                $update = Kib::where('id_aset', $id_aset)->update($data);
            }
        }
    }

    public function generateID(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $kode_64 = '';
        $nomor_lokasi_17 = '';
        $id_aset = '';

        $kosong = array();
        $inserted = array();

        $asets = Migrasi::get();

        foreach ($asets as $value) {
            $value = json_decode(json_encode($value), true);
            $input = $value;
            $kode_64 = $value["kode_64"];

            if(array_key_exists("kode_108", $value)) {
                $kode_108 = $value["kode_108"];
            } else {
                $k108 = Kamus_rekening::select('kode_108')->where('kode_64', $kode_64)->first();

                if(!empty($k108)) {
                    $kode_108 = $k108->kode_108;
                } else {
                    if(in_array($kode_64, $kosong)) {
                        continue;
                    } else {
                        array_push($kosong, $kode_64);
                    }
                }
            }

            $uraian_jurnal = strtolower($value["uraian_jurnal"]);
            if(stristr($uraian_jurnal, 'saldo') == TRUE ) {
                $kode_jurnal = "000";
            } else if (stristr($uraian_jurnal, 'pengadaan') == TRUE) {
                $kode_jurnal = "101";
            } else if (stristr($uraian_jurnal, 'transfer') == TRUE) {
                $kode_jurnal = "102";
            } else if (stristr($uraian_jurnal, 'inventarisasi') == TRUE) {
                $kode_jurnal = "107";
            } else if (stristr($uraian_jurnal, 'hibah') == TRUE) {
                $kode_jurnal = "103";
            } else if (stristr($uraian_jurnal, 'ruislag') == TRUE) {
                $kode_jurnal = "104";
            } else if (stristr($uraian_jurnal, 'sitaan') == TRUE) {
                $kode_jurnal = "105";
            } else if (stristr($uraian_jurnal, 'bot') == TRUE) {
                $kode_jurnal = "106";
            } else if (stristr($uraian_jurnal, 'pinjam') == TRUE) {
                $kode_jurnal = "108";
            } else if (stristr($uraian_jurnal, 'kehilangan') == TRUE) {
                $kode_jurnal = "109";
            } else if (stristr($uraian_jurnal, 'pelunasan') == TRUE) {
                $kode_jurnal = "112";
            } else {
                $kode_jurnal = "999";
            }

            $tahun_spj = $value["tahun_jurnal"];
            $temp = $value["nomor_lokasi"].".".$tahun_spj.".".$kode_jurnal.".".$kode_108;

            $max_id_aset = Kib_awal::select('id_aset')->where('id_aset', 'like', '%'.$temp.'%')->orderBy('id_aset', 'DESC')->first();

            if(is_null($max_id_aset)) {
                $id_aset_index = "00001";
            } else {
                $max_id_aset = $max_id_aset->id_aset;

                if(empty($max_id_aset)) {
                    $id_aset_index = "00001";
                } else {
                    $id_aset_index = str_replace($temp, '', $max_id_aset);
                    $id_aset_index = str_replace('.', '', $id_aset_index);

                    $id_aset_index = intval($id_aset_index);
                    ++$id_aset_index;

                    $id_aset_index = strval($id_aset_index);
                    $s = strlen($id_aset_index);
                    if($s == 1) {
                        $id_aset_index = "0000".$id_aset_index;
                    } else if($s == 2) {
                        $id_aset_index = "000".$id_aset_index;
                    } else if($s == 3) {
                        $id_aset_index = "00".$id_aset_index;
                    } else if($s == 4) {
                        $id_aset_index = "0".$id_aset_index;
                    }
                }
            }

            $id_aset = $temp .".". $id_aset_index;

            $jumlah_barang = $value["saldo_barang"];
            $harga_total = $value["harga_total_plus_pajak_saldo"];
            $harga_total = $value["harga_total_plus_pajak_saldo"];
            $harga_satuan = $harga_total/$jumlah_barang;

            $input["kode_108"] = $kode_108;
            $input["id_aset"] = $id_aset;
            $input["kode_jurnal"] = $kode_jurnal;
            $input["tahun_spj"] = $tahun_spj;

            $input["harga_total"] = $value["harga_total_plus_pajak_saldo"];
            $input["harga_total_plus_pajak"] = $value["harga_total_plus_pajak_saldo"];
            $input["harga_satuan"] = $harga_total/$jumlah_barang;

            unset($input["id"]);
            unset($input["uraian_jurnal"]);
            unset($input["tahun_jurnal"]);

            if(!empty($k108) || array_key_exists("kode_108", $value)) {
                array_push($inserted, $input);
                Kib_awal::create($input);
            } 
        }

        if(empty($kosong)) {
            return $inserted;
        } else {
            return $kosong;
        }
    }
}