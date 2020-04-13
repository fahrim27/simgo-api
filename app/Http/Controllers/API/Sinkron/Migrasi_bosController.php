<?php

namespace App\Http\Controllers\API\Sinkron;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Sinkron\Pendidikan;
use App\Models\Kamus\Kamus_rekening;
use Validator;

class Migrasi_bosController extends BaseController
{
    public function generateID(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $kode_64 = '';
        $nomor_lokasi_17 = '';
        $id_aset = '';

        $kosong = array();
        $inserted = array();

        $asets = Pendidikan::get();

        foreach ($asets as $value) {
            $value = json_decode(json_encode($value), true);
            $input = $value;
            $kode_108 = $value["kode_108"];

            $bidang = substr($kode_108, 0, 5);

            $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', $input["kode_108"])->first();

            if(is_null($kode_64)) {
                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 14)))->first();
                
                if(is_null($kode_64)) {
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 11)))->first();
                } 
            }

            if(!is_null($kode_64)) {
                $kode_64 = $kode_64->kode_64;
            } else {
                if(in_array($kode_108, $kosong)) {
                    continue;
                } else {
                    array_push($kosong, $kode_108);
                }
            }

            $kode_jurnal = "101";
            $tahun_spj = 2019;
            $nomor_lokasi = $input["nomor_lokasi"];

            $max_no_ba = Jurnal::select('no_ba_penerimaan')->where([
                ['nomor_lokasi', '=', $nomor_lokasi],
                ['tahun_spj', '=', $tahun_spj],
                ['kode_jurnal', '=', $kode_jurnal],
            ])->orderBy('no_key', 'DESC')->first();

            if(is_null($max_no_ba)) {
                $no_ba_penerimaan = "0001";
            } else {
                $max_no_ba = $max_no_ba->no_ba_penerimaan;
                $no_ba_penerimaan = intval($max_no_ba);
                ++$no_ba_penerimaan;

                $no_ba_penerimaan = strval($no_ba_penerimaan);
                $s = strlen($no_ba_penerimaan);
                if($s == 1) {
                    $no_ba_penerimaan = "000".$no_ba_penerimaan;
                } else if($s == 2) {
                    $no_ba_penerimaan = "00".$no_ba_penerimaan;
                } else if($s == 3) {
                    $no_ba_penerimaan = "0".$no_ba_penerimaan;
                }
            }

            //generator untuk JURNAL
            $no_key = $nomor_lokasi . "." . $kode_jurnal . "." . $no_ba_penerimaan . "." . $tahun_spj;
            $terkunci = "1";

            $jurnal["no_key"] = $no_key;
            $jurnal["nomor_lokasi"] = $nomor_lokasi;
            $jurnal["kode_jurnal"] = $kode_jurnal;
            $jurnal["tahun_spj"] = $tahun_spj;
            $jurnal["no_ba_penerimaan"] = $no_ba_penerimaan;
            $jurnal["terkunci"] = "1";
            $jurnal["operator"] = "SISFO";

            $validator1 = Validator::make($jurnal, [
                'no_key' => 'required',
                'nomor_lokasi' => 'required',
                'tahun_spj' => 'required'
            ]);

            if($validator1->fails()){
                return $this->sendError('Validation Error.', $validator1->errors());       
            }

            $created_jurnal = Jurnal::create($jurnal);

            //generator untuk KIB
            $temp = $nomor_lokasi .".". $tahun_spj .".". $kode_jurnal .".". $kode_108;
            $max_id_aset = Kib::select('id_aset')->where('id_aset', 'like', '%'.$temp.'%')->orderBy('id_aset', 'DESC')->first();

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

            if($bidang == "1.3.1") {
                $input["bidang_barang"] = "A";
            } else if($bidang == "1.3.2") {
                $input["bidang_barang"] = "B";
            } else if($bidang == "1.3.3") {
                $input["bidang_barang"] = "C";
            } else if($bidang == "1.3.4") {
                $input["bidang_barang"] = "D";
            } else if($bidang == "1.3.5") {
                $input["bidang_barang"] = "E";
            } else if($bidang == "1.3.6") {
                $input["bidang_barang"] = "F";
            } else if($bidang == "1.5.3") {
                $input["bidang_barang"] = "G";
            } else {
                $input["bidang_barang"] = "0";
            }

            $input["pos_entri"] = "BYSISFO";
            $input["jenis_aset"] = "A";
            $input["no_key"] = $no_key;
            $input["kode_64"] = $kode_64;
            $input["id_aset"] = $id_aset;
            $input["no_register"] = $id_aset;
            $input["kode_jurnal"] = $kode_jurnal;
            $input["tahun_spj"] = $tahun_spj;
            $input["tahun_pengadaan"] = $tahun_spj;
            $input["tahun_perolehan"] = $tahun_spj;
            $input["baik"] = $input["jumlah_barang"];
            $input["sendiri"] = $input["jumlah_barang"];
            $input["kode_kepemilikan"] = "12";
            $input["operator"] = "SISFO";
            $input["pajak"] = "1";

            unset($input["id"]);

            if(array_key_exists("kode_108", $value)) {
                array_push($inserted, $input);
                Kib::create($input);
                Rincian_masuk::create($input);
            } 
        }

        if(empty($kosong)) {
            return $inserted;
        } else {
            return $kosong;
        }
    }

    public function generateBansosID(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $kode_64 = '';
        $nomor_lokasi_17 = '';
        $id_aset = '';

        $kosong = array();
        $inserted = array();

        $asets = Pendidikan::get();

        foreach ($asets as $value) {
            $value = json_decode(json_encode($value), true);
            $input = $value;
            $kode_108 = $value["kode_108"];

            $bidang = substr($kode_108, 0, 5);

            $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', $input["kode_108"])->first();

            if(is_null($kode_64)) {
                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 14)))->first();
                
                if(is_null($kode_64)) {
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 11)))->first();
                } 
            }

            if(!is_null($kode_64)) {
                $kode_64 = $kode_64->kode_64;
            } else {
                if(in_array($kode_108, $kosong)) {
                    continue;
                } else {
                    array_push($kosong, $kode_108);
                }
            }

            $kode_jurnal = "103";
            $tahun_spj = 2019;
            $nomor_lokasi = $input["nomor_lokasi"];

            $max_no_ba = Jurnal::select('no_ba_penerimaan')->where([
                ['nomor_lokasi', '=', $nomor_lokasi],
                ['tahun_spj', '=', $tahun_spj],
                ['kode_jurnal', '=', $kode_jurnal],
            ])->orderBy('no_key', 'DESC')->first();

            if(is_null($max_no_ba)) {
                $no_ba_penerimaan = "0001";
            } else {
                $max_no_ba = $max_no_ba->no_ba_penerimaan;
                $no_ba_penerimaan = intval($max_no_ba);
                ++$no_ba_penerimaan;

                $no_ba_penerimaan = strval($no_ba_penerimaan);
                $s = strlen($no_ba_penerimaan);
                if($s == 1) {
                    $no_ba_penerimaan = "000".$no_ba_penerimaan;
                } else if($s == 2) {
                    $no_ba_penerimaan = "00".$no_ba_penerimaan;
                } else if($s == 3) {
                    $no_ba_penerimaan = "0".$no_ba_penerimaan;
                }
            }

            //generator untuk JURNAL
            $no_key = $nomor_lokasi . "." . $kode_jurnal . "." . $no_ba_penerimaan . "." . $tahun_spj;
            $terkunci = "1";

            $jurnal["no_key"] = $no_key;
            $jurnal["nomor_lokasi"] = $nomor_lokasi;
            $jurnal["kode_jurnal"] = $kode_jurnal;
            $jurnal["tahun_spj"] = $tahun_spj;
            $jurnal["no_ba_penerimaan"] = $no_ba_penerimaan;
            $jurnal["terkunci"] = "1";
            $jurnal["operator"] = "SISFO";

            $validator1 = Validator::make($jurnal, [
                'no_key' => 'required',
                'nomor_lokasi' => 'required',
                'tahun_spj' => 'required'
            ]);

            if($validator1->fails()){
                return $this->sendError('Validation Error.', $validator1->errors());       
            }

            $created_jurnal = Jurnal::create($jurnal);

            //generator untuk KIB
            $temp = $nomor_lokasi .".". $tahun_spj .".". $kode_jurnal .".". $kode_108;
            $max_id_aset = Kib::select('id_aset')->where('id_aset', 'like', '%'.$temp.'%')->orderBy('id_aset', 'DESC')->first();

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

            if($bidang == "1.3.1") {
                $input["bidang_barang"] = "A";
            } else if($bidang == "1.3.2") {
                $input["bidang_barang"] = "B";
            } else if($bidang == "1.3.3") {
                $input["bidang_barang"] = "C";
            } else if($bidang == "1.3.4") {
                $input["bidang_barang"] = "D";
            } else if($bidang == "1.3.5") {
                $input["bidang_barang"] = "E";
            } else if($bidang == "1.3.6") {
                $input["bidang_barang"] = "F";
            } else if($bidang == "1.5.3") {
                $input["bidang_barang"] = "G";
            } else {
                $input["bidang_barang"] = "0";
            }

            $input["pos_entri"] = "BYSISFO";
            $input["jenis_aset"] = "A";
            $input["no_key"] = $no_key;
            $input["kode_64"] = $kode_64;
            $input["id_aset"] = $id_aset;
            $input["no_register"] = $id_aset;
            $input["kode_jurnal"] = $kode_jurnal;
            $input["tahun_spj"] = $tahun_spj;
            $input["tahun_pengadaan"] = $tahun_spj;
            $input["tahun_perolehan"] = $tahun_spj;
            $input["baik"] = $input["jumlah_barang"];
            $input["sendiri"] = $input["jumlah_barang"];
            $input["kode_kepemilikan"] = "12";
            $input["operator"] = "SISFO";
            $input["pajak"] = "1";

            unset($input["id"]);

            if(array_key_exists("kode_108", $value)) {
                array_push($inserted, $input);
                Kib::create($input);
                Rincian_masuk::create($input);
            } 
        }

        if(empty($kosong)) {
            return $inserted;
        } else {
            return $kosong;
        }
    }
}