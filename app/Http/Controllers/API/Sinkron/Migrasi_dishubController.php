<?php

namespace App\Http\Controllers\API\Sinkron;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Jurnal\Rincian_koreksi;
use App\Models\Sinkron\Dishub;
use App\Models\Kamus\Kamus_rekening;
use Validator;

class Migrasi_dishubController extends BaseController
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

        $asets = Dishub::get();

        foreach ($asets as $value) {
            $value = json_decode(json_encode($value), true);
            $input = $value;
            $kode_64 = $value["kode_64"];

            $kode_jurnal = "306";
            $tahun_spj = 2019;
            $nomor_lokasi = $value["nomor_lokasi"];

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

            //generator untuk Rincian Koreksi
            $detail = Kib::select('id_aset', 'bidang_barang')->where('no_register', $value["no_register"])->where('nomor_lokasi', $value["nomor_lokasi"])->first();

            if(is_null($detail)) {
                array_push($kosong, $value["no_register"]);
            } else {
                $input = $value;
                $input["kode_koreksi"] = $kode_jurnal;
                $input["no_key"] = $no_key;
                $input["id_aset"] = $detail->id_aset;
                $input["no_ba_penerimaan"] = $no_ba_penerimaan;
                $input["bidang_barang"] = $detail->bidang_barang;
                $input["tahun_koreksi"] = 2019;

                $input["kode_108_baru"] = '1.3.2.18.01.02.001';
                $input["kode_64_baru"] = '1.3.3.10.02';
                $input["nilai"] = $value["harga_total_plus_pajak_saldo"];

                unset($input["id"]);
                unset($input["tipe"]);
                unset($input["saldo_barang"]);
                unset($input["satuan"]);
                unset($input["harga_total_plus_pajak_saldo"]);
                unset($input["tahun_pengadaan"]);

                $update["kode_108"] = '1.3.2.18.01.02.001';
                $update["kode_64"] = '1.3.3.10.02';
                $update["keterangan"] = "Mengalami reklas menjadi rambu lalu lintas";

                Rincian_koreksi::create($input);
                Kib::where('id_aset', $detail->id_aset)->update($update);

                array_push($inserted, $input);
            }
        }

        if(empty($kosong)) {
            return $inserted;
        } else {
            return $kosong;
        }
    }
}