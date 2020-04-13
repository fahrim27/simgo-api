<?php

namespace App\Http\Controllers\API\Sinkron;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Jurnal\Purehab;
use App\Models\Jurnal\Rehab108;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Jurnal\Rincian_keluar;
use App\Models\Jurnal\Kib_awal;
use App\Models\Sinkron\Penambahan_nilai;
use App\Models\Sinkron\Diknasrehab;
use App\Models\Kamus\Mapping_lokasi;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Kamus_mapping_permen;
use Validator;

class Migrasi_rehabController extends BaseController
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
        $index = 0;

        $data_simgo_lama = Penambahan_nilai::get();

        foreach ($data_simgo_lama as $value) {
            $value = json_decode(json_encode($value), true);
            
            $nomor_lokasi = Mapping_lokasi::select('nomor_lokasi_108')->where('nomor_lokasi_17', 'like', $value["subunitpn"] . '%')->first()->nomor_lokasi_108;
            $kode_64 = Kamus_mapping_permen::select('kode_2')->where('permen_2', '64')->where('kode_1', 'like', $value["kodepn"] . '%')->first();

            if(empty($kode_64)) {
                $kode_rek = $value["kodepn"];
            } else {
                $kode_64 = $kode_64->kode_2;
                $kode_rek = Kamus_rekening::select('kode_108')->where('kode_64', 'like', $kode_64 .'%')->first()->kode_108;
            }
            
            $kode_rek_rehab = $kode_rek;
            $kode_rek_induk = $kode_rek;

            $data[$index]["rehab_id"] = $value["pnid"];
            $data[$index]["nama_rehab"] = $value["namapn"];
            $data[$index]["tahun_rehab"] = $value["tahunpn"];
            $data[$index]["nilai_rehab"] = $value["nilaipn"];
            $data[$index]["kode_rek_rehab"] = $kode_rek_rehab;
            $data[$index]["nomor_lokasi"] = $nomor_lokasi;
            $data[$index]["aset_induk_id"] = $value["asetindukid"];
            $data[$index]["nama_induk"] = $value["namainduk"];
            $data[$index]["tahun_induk"] = $value["tahuninduk"];
            $data[$index]["kode_rek_induk"] = $kode_rek_induk;

            $index++;
        }

        if(!empty($data)) {
            $chunks = array_chunk($data, 200);

            foreach ($chunks as $chunk) {
                $response = DB::table('rehabs')->insert($chunk);
            }
        }

        if($response) {
            return $this->sendResponse($data, 'Data rehab syncronized successfully.');
        } else {
            return $this->sendResponse($data, 'Data rehab fail to sync.');  
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
        $asets = array();
        $kosong_rehab = array();
        $kosong_induk = array();

        $rehabs = Rehab::get();

        foreach ($rehabs as $value) {
            $value = json_decode(json_encode($value), true);
            $input = $value;
            $id_aset = $value["rehab_id"];
            $id_aset_induk = $value["aset_induk_id"];

            $new = Kib_awal::select('id_aset')->where('no_register', 'like', $id_aset.'%')->orderBy('id_aset', 'DESC')->first();
            $new_induk = Kib_awal::select('id_aset')->where('no_register', 'like', $id_aset_induk.'%')->orderBy('id_aset', 'DESC')->first();

            if(is_null($new)) {
                array_push($kosong_rehab, $id_aset);
            } else {
                $id_aset_new = $new->id_aset;
            }

            if(is_null($new_induk)) {
                array_push($kosong_induk, $id_aset_induk);
            } else {
                $id_aset_induk_new = $new_induk->id_aset;
            }

            $input["rehab_id"] = $id_aset_new;
            $input["aset_induk_id"] = $id_aset_induk_new;
            $rehab["aset_rehab"] = 1;
            $rehab["id_aset_induk"] = $id_aset_induk_new;

            if(!is_null($new) && !is_null($new_induk)) {
                array_push($asets, $input);
                Kib_awal::where("id_aset", $id_aset_new)->update($rehab);
                Rehab108::create($input);
                Rehab::where("rehab_id", $value["rehab_id"])->delete();
            }
        }

        array_push($kosong, $kosong_induk);
        array_push($kosong, $kosong_rehab);

        if(!empty($kosong_induk) || !empty($kosong_rehab)) {
            return $kosong;
        } else {
            return $input;
        }
    }

    public function purehab(Request $request)
    {
        ini_set('memory_limit', '-1');

        $kosong = array();
        $aset = array();
        $kosong_rehab = array();
        $kosong_induk = array();

        $rehabs = Purehab::get();

        foreach ($rehabs as $value) {
            $value = json_decode(json_encode($value), true);
            $input = $value;
            $no_register = $value["anak"];
            $no_register_induk = $value["induk"];

            $rehab = Kib::select('id_aset', 'tahun_pengadaan', 'nama_barang', 'harga_total_plus_pajak_saldo', 'kode_108', 'nomor_lokasi')
                    ->where('no_register', 'like', $no_register.'%')
                    ->orderBy('id_aset', 'DESC')
                    ->first();
            $induk = Kib::select('id_aset', 'nama_barang', 'tahun_pengadaan', 'harga_total_plus_pajak_saldo', 'kode_108')
                    ->where('no_register', 'like', $no_register_induk.'%')
                    ->orderBy('id_aset', 'DESC')
                    ->first();

            if(is_null($rehab)) {
                array_push($kosong_rehab, $no_register);
            } else {
                $input["rehab_id"] = $rehab->id_aset;
                $input["nama_rehab"] = $rehab->nama_barang;
                $input["tahun_rehab"] = $rehab->tahun_pengadaan;
                $input["nilai_rehab"] = $rehab->harga_total_plus_pajak_saldo;
                $input["kode_rek_rehab"] = $rehab->kode_108;
                $input["nomor_lokasi"] = $rehab->nomor_lokasi;
            }

            if(is_null($induk)) {
                array_push($kosong_induk, $no_register_induk);
            } else {
                $input["aset_induk_id"] = $induk->id_aset;
                $input["nama_induk"] = $induk->nama_barang;
                $input["tahun_induk"] = $induk->tahun_pengadaan;
                $input["nilai_induk"] = $induk->harga_total_plus_pajak_saldo;
                $input["kode_rek_induk"] = $induk->kode_108;
            }

            $input["tambah_manfaat"] = 1;
            $data["aset_rehab"] = 1;
            $data["id_aset_induk"] = $induk->id_aset;

            if(!is_null($rehab) && !is_null($induk)) {
                array_push($aset, $input);
                Kib::where("id_aset", $rehab->id_aset)->update($data);
                Rehab::create($input);
            }
        }

        array_push($kosong, $kosong_induk);
        array_push($kosong, $kosong_rehab);

        if(!empty($kosong_induk) || !empty($kosong_rehab)) {
            return $kosong;
        } else {
            return $aset;
        }
    }

    public function diknasrehab(Request $request)
    {
        ini_set('memory_limit', '-1');

        $input = array();
        $jurnal = array();
        $kosong = array();
        $aset = array();
        $migrated = array();

        $rehabs = Diknasrehab::get();

        foreach ($rehabs as $value) {
            $value = json_decode(json_encode($value), true);

            $nomor_lokasi = $value["lokasi_tujuan"];
            $tahun_spj = 2019;
            $kode_jurnal = '102';
            $kode_jurnal_keluar = '202';
            $dari_lokasi = '12.01.35.16.111.00001.00001';
            $ke_lokasi = $value["lokasi_tujuan"];

            $lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first();
            if(!is_null($lokasi)) {
                $nama_lokasi = $lokasi->nama_lokasi;
            }

            $max_no_ba_keluar = Jurnal::select('no_ba_penerimaan')
                        ->where('nomor_lokasi', $dari_lokasi)
                        ->where('tahun_spj', $tahun_spj)
                        ->where('kode_jurnal', $kode_jurnal_keluar)
                        ->orderBy('no_key', 'DESC')
                        ->first();

            if(empty($max_no_ba_keluar)) {
                $nomor = 1;
                $no_ba_pengeluaran = "0001";
            } else {
                $max_no_ba_keluar = $max_no_ba_keluar->no_ba_penerimaan;
                $no_ba_pengeluaran = intval($max_no_ba_keluar);
                ++$no_ba_pengeluaran;

                $nomor = $no_ba_pengeluaran;
                $no_ba_pengeluaran = strval($no_ba_pengeluaran);
                $s = strlen($no_ba_pengeluaran);
                if($s == 1) {
                    $no_ba_pengeluaran = "000".$no_ba_pengeluaran;
                } else if($s == 2) {
                    $no_ba_pengeluaran = "00".$no_ba_pengeluaran;
                } else if($s == 3) {
                    $no_ba_pengeluaran = "0".$no_ba_pengeluaran;
                }
            }

            $no_key_keluar = $dari_lokasi . "." . $kode_jurnal_keluar .".". $no_ba_pengeluaran . "." . $tahun_spj;
            $jurnal_keluar["no_ba_penerimaan"] = $no_ba_pengeluaran;
            $jurnal_keluar["no_key"] = $no_key_keluar;
            $jurnal_keluar["kode_jurnal"] = $kode_jurnal_keluar;
            $jurnal_keluar["nomor_lokasi"] = $dari_lokasi;
            $jurnal_keluar["ke_lokasi"] = $nomor_lokasi;
            $jurnal_keluar["terkunci"] = "1";
            $jurnal_keluar["tahun_spj"] = $tahun_spj;
            $jurnal_keluar["diterima"] = 1;

            $validator = Validator::make($jurnal_keluar, [
                'no_key' => 'required',
                'nomor_lokasi' => 'required',
                'tahun_spj' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            Jurnal::create($jurnal_keluar);

            $aset_keluar = Kib::where('id_aset', $value["id_aset"])->first();

            $data_keluar["nomor_lokasi"] = $dari_lokasi;
            $data_keluar["kode_jurnal"] = $kode_jurnal_keluar;
            $data_keluar["no_key"] = $no_key_keluar;
            $data_keluar["bidang_barang"] = $aset_keluar->bidang_barang;
            $data_keluar["id_aset"] = $aset_keluar->id_aset;
            $data_keluar["no_ba_penerimaan"] = $no_ba_pengeluaran;
            $data_keluar["no_register"] = $aset_keluar->id_aset;
            $data_keluar["kode_kepemilikan"] = $aset_keluar->kode_kepemilikan;
            $data_keluar["nama_barang"] = $aset_keluar->nama_barang;
            $data_keluar["merk_alamat"] = $aset_keluar->merk_alamat;
            $data_keluar["kode_108"] = $aset_keluar->kode_108;
            $data_keluar["kode_64"] = $aset_keluar->kode_64;
            $data_keluar["nopol"] = $aset_keluar->nopol;
            $data_keluar["jumlah_barang"] = $aset_keluar->saldo_barang;
            $data_keluar["harga_satuan"] = $aset_keluar->harga_satuan;
            $data_keluar["nilai_keluar"] = $aset_keluar->harga_total_plus_pajak_saldo;
            $data_keluar["tahun_spj"] = 2019;
            $data_keluar["baik"] = 1;
            $data_keluar["sendiri"] = 1;

            Rincian_keluar::create($data_keluar);

            $max_no_ba = Jurnal::select('no_ba_penerimaan')
                        ->where('nomor_lokasi', $nomor_lokasi)
                        ->where('tahun_spj', $tahun_spj)
                        ->where('kode_jurnal', $kode_jurnal)
                        ->orderBy('no_key', 'DESC')
                        ->first();

            if(empty($max_no_ba)) {
                $nomor = 1;
                $no_ba_penerimaan = "0001";
            } else {
                $max_no_ba = $max_no_ba->no_ba_penerimaan;
                $no_ba_penerimaan = intval($max_no_ba);
                ++$no_ba_penerimaan;

                $nomor = $no_ba_penerimaan;
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
            
            $no_key = $nomor_lokasi . "." . $kode_jurnal .".". $no_ba_penerimaan . "." . $tahun_spj;

            $jurnal["no_ba_penerimaan"] = $no_ba_penerimaan;
            $jurnal["no_key"] = $no_key;
            $jurnal["nomor_lokasi"] = $nomor_lokasi;
            $jurnal["dari_lokasi"] = '12.01.35.16.111.00001.00001';
            $jurnal["kode_jurnal"] = $kode_jurnal;
            $jurnal["terkunci"] = "1";
            $jurnal["tahun_spj"] = $tahun_spj;

            $validator = Validator::make($jurnal, [
                'no_key' => 'required',
                'nomor_lokasi' => 'required',
                'tahun_spj' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            Jurnal::create($jurnal);

            $aset = Kib::where('id_aset', $value["id_aset"])->first();
            if(!is_null($aset)) {
                $kode_108 = $aset->kode_108;
                $data_aset = json_decode(json_encode($aset), true);
                $input = $data_aset;
            } else {
                array_push($kosong, $value["id_aset"]);
            }
            
            $temp = $nomor_lokasi.".".$tahun_spj.".".$kode_jurnal.".".$kode_108;
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

            $id_aset = $temp.".".$id_aset_index;
            $input["kode_jurnal"] = $kode_jurnal;
            $input["no_key"] = $no_key;
            $input["nomor_lokasi"] = $nomor_lokasi;
            $input["id_aset"] = $id_aset;
            $input["tahun_spj"] = $tahun_spj;
            $input["tahun_perolehan"] = $tahun_spj;

            $change["saldo_barang"] = 0;
            $change["harga_total_plus_pajak_saldo"] = 0;
            $change["operator"] = 'SISFO';

            if(!is_null($lokasi)) {
                $change["keterangan"] = "Dimutasikan ke " . $nama_lokasi;
            } else {
                $change["keterangan"] = "Dimutasikan ke sekolah";
            }

            if(!is_null($aset)) {
                array_push($migrated, $input);
                Kib::where("id_aset", $data_aset["id_aset"])->update($change);
                Kib::create($input);
                Rincian_masuk::create($input);
            }
        }

        if(!empty($kosong)) {
            return $this->sendResponse($kosong, 'Migrasi data rehab Dinas Pendidikan gagal.');
        } else {
            return $this->sendResponse($migrated, 'Sukses migrasi data rehab Dinas Pendidikan.');
        }
    }
}