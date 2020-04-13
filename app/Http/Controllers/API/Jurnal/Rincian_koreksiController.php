<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Rincian_koreksi;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Jurnal\Kib_awal;
use App\Models\Jurnal\Jurnal;
use App\Http\Resources\Jurnal\Rincian_koreksiCollection;
use App\Http\Resources\Jurnal\JurnalCollection;
use App\Http\Resources\Jurnal\KibCollection;
use Validator;

class Rincian_koreksiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = (int)$request->header('Pagination');
        
        if($pagination === 0) {
            $rincian_koreksis = new Rincian_koreksiCollection(Rincian_koreksi::all());
        } else {
            $rincian_koreksis = new Rincian_koreksiCollection(Rincian_koreksi::paginate($request->get('per_page')));
        }

        return $rincian_koreksis;
    }

    public function getByNoKey(Request $request, $no_key)
    {
        $pagination = (int)$request->header('Pagination');

        if($pagination === 0) {
            $rincian_koreksis = new Rincian_koreksiCollection(Rincian_koreksi::where('no_key', $no_key)->get());
        } else {
            $rincian_koreksis = new Rincian_koreksiCollection(Rincian_koreksi::where('no_key', $no_key)->paginate($request->get('per_page')));
        }

        return $rincian_koreksis;
    }

    public function getAvailableAset(Request $request, $nomor_lokasi)
    {
        $tahun_spj = date('Y') - 1;

        $rincian_koreksis = new KibCollection(Kib::select('id_aset','kode_108','nama_barang','harga_satuan','saldo_barang', 'no_register')
        ->where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')
        ->where('tahun_pengadaan', '<', $tahun_spj)
        ->where('saldo_barang', '>', 0)
        ->get());

        return $rincian_koreksis;
    }

    public function getAvailableAsetBerjalan(Request $request, $nomor_lokasi)
    {
        $tahun_spj = date('Y') - 1;

        $rincian_koreksis = new KibCollection(Kib::select('id_aset','kode_108','nama_barang','harga_satuan','saldo_barang', 'no_register')
        ->where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')
        ->where('tahun_pengadaan', $tahun_spj)
        ->where('saldo_barang', '>', 0)
        ->get());

        return $rincian_koreksis;
    }

    public function getDetailAsetKoreksi(Request $request, $id_aset)
    {
        $asets = new KibCollection(Kib::select('id_aset', 'bidang_barang', 'no_register', 'kode_64', 'kode_108', 'nama_barang', 'merk_alamat', 'saldo_barang as jumlah_barang', 'harga_total_plus_pajak_saldo', 'kode_kepemilikan')->where('id_aset', $id_aset)->get());

        return $asets;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $kode_108 = substr($input["kode_108"], 0, 11);
        $nomor_lokasi = $input["nomor_lokasi"];
        $tahun_spj = date('Y') - 1;

        $input["tahun_spj"] = $tahun_spj;
        
        $rincian_koreksi = Rincian_koreksi::create($input);

        return $this->sendResponse($rincian_koreksi->toArray(), 'Rincian koreksi created successfully.');
    }

    //untuk menyimpan sesuai dengan jurnal
    public function save(Request $request, $kode_jurnal)
    {
        $input = $request->all();

        $tahun_spj = date('Y') - 1;
        $id_aset = $input["id_aset"];
        $nomor_lokasi = $input["nomor_lokasi"];
        $no_key = $input["no_key"];
        $jurnal_koreksi = Jurnal::where('no_key', $input["no_key"])->first();

        $kode_64 = null;
        $kode_108 = null;
        $nilai = null;
        $nilai_baru = null;
        $kode_64_baru = null;
        $kode_108_baru = null;
        $kepemilikan = null;
        $kepemilikan_baru = null;
        $date = date("Y-m-d H:i:s");  

        $kode_koreksi = $kode_jurnal;
        $validate = true;
        $error_message = '';

        $aset = Kib::where('id_aset', $id_aset)->first();
        $data = json_decode(json_encode($aset), true);

        $temp = $nomor_lokasi . "." . $tahun_spj . "." . $kode_jurnal . "." . $data["kode_108"];

        $max_id_aset = Kib::select('id_aset')->where('id_aset', 'like', '%' . $temp . '%')->orderBy('id_aset', 'DESC')->first();

        if (is_null($max_id_aset)) {
            $id_aset_index = "00001";
        } else {
            $max_id_aset = $max_id_aset->id_aset;

            if (empty($max_id_aset)) {
                $id_aset_index = "00001";
            } else {
                $id_aset_index = str_replace($temp, '', $max_id_aset);
                $id_aset_index = str_replace('.', '', $id_aset_index);

                $id_aset_index = intval($id_aset_index);
                ++$id_aset_index;
                $id = $id_aset_index;

                $id_aset_index = strval($id_aset_index);
                $s = strlen($id_aset_index);
                if ($s == 1) {
                    $id_aset_index = "0000" . $id_aset_index;
                } else if ($s == 2) {
                    $id_aset_index = "000" . $id_aset_index;
                } else if ($s == 3) {
                    $id_aset_index = "00" . $id_aset_index;
                } else if ($s == 4) {
                    $id_aset_index = "0" . $id_aset_index;
                }
            }
        }

        $id_aset_baru = $temp . "." . $id_aset_index;

        if($kode_jurnal == "302" || $kode_jurnal == "332") {
            if($input["jumlah_barang_baru"] == $data["saldo_barang"]) {
                $data["saldo_barang"] = 0;
                $data["saldo_gudang"] = 0;
                $nilai_baru = 0;
                $data["harga_total"] = 0;
                $data["harga_total_plus_pajak"] = 0;
                $data["harga_total_plus_pajak_saldo"] = 0;
                $data["updated_at"] = $date;
            } else {
                $sisa = $data["saldo_barang"] - $input["jumlah_barang_baru"];

                $data["keterangan"] = "Mengalami Penghapusan Sebagian" . $data["keterangan"];
                $data["saldo_barang"] = $sisa;
                $data["saldo_gudang"] = $sisa;
                $nilai_baru = $sisa * $data["harga_satuan"];
                $data["harga_total"] = $nilai_baru;
                $data["harga_total_plus_pajak"] = $nilai_baru;
                $data["harga_total_plus_pajak_saldo"] = $nilai_baru;

                $data["baik"] = $sisa;
                $data["kb"] = 0;
                $data["rb"] = 0;
                $data["sendiri"] = $sisa;
                $data["pihak_3"] = 0;
                $data["sengketa"] = 0;
                $data["updated_at"] = $date;
            }
        } else if($kode_jurnal == "303" || $kode_jurnal == "333") {
            $nilai = $data["harga_total_plus_pajak_saldo"];
            $nilai_baru = $input["nilai_baru"];
            $data["harga_total"] = $input["nilai_baru"];
            $data["harga_total_plus_pajak"] = $input["nilai_baru"];
            $data["harga_total_plus_pajak_saldo"] = $input["nilai_baru"];
        } else if($kode_jurnal == "307" || $kode_jurnal == "337") {
            if($input["jumlah_barang_baru"] == $data["saldo_barang"]) {
                $kepemilikan = $data["kode_kepemilikan"];
                $kepemilikan_baru = $input["kode_kepemilikan_baru"];

                $data["kode_kepemilikan"] = $input["kode_kepemilikan_baru"];
                $data["updated_at"] = $date;
            } else {
                $sisa = $data["saldo_barang"] - $input["jumlah_barang_baru"];
                $kepemilikan = $data["kode_kepemilikan"];
                $kepemilikan_baru = $input["kode_kepemilikan_baru"];

                if($kepemilikan == $kepemilikan_baru) {
                    $validate = false;
                    $return_data = $input;
                    $error_message = "kode kepemilikan tidak boleh sama dengan kode kepemilikan lama.";
                }

                $data_baru = $data;
                $sisa = $data["saldo_barang"] - $input["jumlah_barang_baru"];

                $data["saldo_barang"] = $sisa;
                $data["saldo_gudang"] = $sisa;
                $nilai_baru = $sisa * $data["harga_satuan"];
                $data["harga_total"] = $nilai_baru;
                $data["harga_total_plus_pajak"] = $nilai_baru;
                $data["harga_total_plus_pajak_saldo"] = $nilai_baru;
                $data["sendiri"] = $sisa;
                $data["baik"] = $sisa;
                $data["updated_at"] = $date;

                $data_baru = $data;
                $data_baru["kode_jurnal"] = $kode_koreksi;
                $data_baru["no_key"] = $no_key;
                $data_baru["id_aset"] = $id_aset_baru;
                $data_baru["no_ba_penerimaan"] = $id_aset_index;
                $data_baru["id_transaksi"] = "REKLAS";
                $data_baru["cara_perolehan"] = "REKLAS";
                $data_baru["keterangan"] = "Reklas kepemilikan sebagian dari aset " . $data["id_aset"];
                $data_baru["saldo_barang"] = $input["jumlah_barang_baru"];
                $data_baru["saldo_gudang"] = $input["jumlah_barang_baru"];
                $data_baru["sendiri"] = $input["jumlah_barang_baru"];
                $data_baru["baik"] = $input["jumlah_barang_baru"];

                $nilai_aset_baru = $input["jumlah_barang_baru"] * $data["harga_satuan"];
                $data_baru["harga_total"] = $nilai_aset_baru;
                $data_baru["harga_total_plus_pajak"] = $nilai_aset_baru;
                $data_baru["harga_total_plus_pajak_saldo"] = $nilai_aset_baru;
                $data_baru["sendiri"] = $input["jumlah_barang_baru"];

                $data_baru["kode_kepemilikan"] = $kepemilikan_baru;
                $data_baru["created_at"] = $date;
                $data_baru["updated_at"] = $date;

                Rincian_masuk::create($data_baru);
                Kib::create($data_baru);
            }
        } else if($kode_jurnal == "306" || $kode_jurnal == "336") {
            if((int)$input["jumlah_barang_baru"] == $data["saldo_barang"]) {
                $kode_108 = $data["kode_108"];
                $nilai = $data["harga_total_plus_pajak_saldo"];
                $data["kode_108"] = $input["kode_108_baru"];

                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', $input["kode_108"])->first();

                if(is_null($kode_64)) {
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 14)))->first();
                    
                    if(is_null($kode_64)) {
                        $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 11)))->first();
                    }            
                }

                $kode_64_baru = Kamus_rekening::select('kode_64')->where('kode_108', $input["kode_108_baru"])->first();

                if(is_null($kode_64_baru)) {
                    $kode_64_baru = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108_baru"], 0, 14)))->first();
                    
                    if(is_null($kode_64_baru)) {
                        $kode_64_baru = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108_baru"], 0, 11)))->first();
                    }            
                }
                
                $kode_64 = $kode_64->kode_64;
                $kode_64_baru = $kode_64_baru->kode_64;
                $data["kode_64"] = $kode_64_baru;

                $cek = substr($kode_64_baru, 0, 5);
                if($cek == "1.3.1") {
                    $bidang_barang = "A";
                } else if($cek == "1.3.2") {
                    $bidang_barang = "B";
                } else if($cek == "1.3.3") {
                    $bidang_barang = "C";
                } else if($cek == "1.3.4") {
                    $bidang_barang = "D";
                } else if($cek == "1.3.5") {
                    $bidang_barang = "E";
                } else if($cek == "1.3.6") {
                    $bidang_barang = "F";
                } else if($cek == "1.5.3") {
                    $bidang_barang = "G";
                } else {
                    $bidang_barang = "RB";
                }

                $data["bidang_barang"] = $bidang_barang;

                if($kode_64_baru == "1.5.4.01.01") {
                    $data["baik"] = 0;
                    $data["kb"] = 0;
                    $data["rb"] = $input["jumlah_barang_baru"];
                } else {
                    $data["baik"] = $input["jumlah_barang"];
                    $data["kb"] = 0;
                    $data["rb"] = 0;
                }
            } else {             
                $kode_108 = $data["kode_108"];
                $kode_108_baru = $input["kode_108_baru"];

                if($kode_108 == $kode_108_baru) {
                    $validate = false;
                    $return_data = $input;
                    $error_message = "kode 108 baru tidak boleh sama dengan kode 108 lama.";
                }

                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', $input["kode_108"])->first();

                if(is_null($kode_64)) {
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 14)))->first();
                    
                    if(is_null($kode_64)) {
                        $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108"], 0, 11)))->first();
                    }            
                }

                $kode_64_baru = Kamus_rekening::select('kode_64')->where('kode_108', $input["kode_108_baru"])->first();

                if(is_null($kode_64_baru)) {
                    $kode_64_baru = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108_baru"], 0, 14)))->first();
                    
                    if(is_null($kode_64_baru)) {
                        $kode_64_baru = Kamus_rekening::select('kode_64')->where('kode_108', (substr($input["kode_108_baru"], 0, 11)))->first();
                    }            
                }
                
                $kode_64 = $kode_64->kode_64;
                $kode_64_baru = $kode_64_baru->kode_64;

                $cek = substr($kode_64_baru, 0, 5);
                if($cek == "1.3.1") {
                    $bidang_barang = "A";
                } else if($cek == "1.3.2") {
                    $bidang_barang = "B";
                } else if($cek == "1.3.3") {
                    $bidang_barang = "C";
                } else if($cek == "1.3.4") {
                    $bidang_barang = "D";
                } else if($cek == "1.3.5") {
                    $bidang_barang = "E";
                } else if($cek == "1.3.6") {
                    $bidang_barang = "F";
                } else if($cek == "1.5.3") {
                    $bidang_barang = "G";
                } else {
                    $bidang_barang = "RB";
                }

                $data_baru = $data;
                $nilai = $input["jumlah_barang_baru"] * $data["harga_satuan"];
                $sisa = $data["saldo_barang"] - $input["jumlah_barang_baru"];

                $data["saldo_barang"] = $sisa;
                $data["saldo_gudang"] = $sisa;
                $nilai_sisa = $sisa * $data["harga_satuan"];
                $data["harga_total"] = $nilai_sisa;
                $data["harga_total_plus_pajak"] = $nilai_sisa;
                $data["harga_total_plus_pajak_saldo"] = $nilai_sisa;
                $data["sendiri"] = $sisa;
                $data["updated_at"] = $date;

                $data_baru = $data;
                $data_baru["kode_jurnal"] = $kode_koreksi;
                $data_baru["no_key"] = $no_key;
                $data_baru["id_aset"] = $id_aset_baru;
                $data_baru["no_ba_penerimaan"] = $id_aset_index;
                $data_baru["id_transaksi"] = "REKLAS";
                $data_baru["cara_perolehan"] = "REKLAS";
                $data_baru["keterangan"] = "Reklas kode sebagian dari aset " . $data["id_aset"];
                $data_baru["saldo_barang"] = $input["jumlah_barang_baru"];
                $data_baru["saldo_gudang"] = $input["jumlah_barang_baru"];
                $data_baru["sendiri"] = $input["jumlah_barang_baru"];
                $data_baru["bidang_barang"] = $bidang_barang;

                if($kode_64_baru == "1.5.4.01.01") {
                    $data["baik"] = $sisa;
                    $data["kb"] = 0;
                    $data["rb"] = 0;
                    $data_baru["baik"] = 0;
                    $data_baru["kb"] = 0;
                    $data_baru["rb"] = $input["jumlah_barang_baru"];
                    $data_baru["jenis_aset"] = "R";
                } else {
                    $data["baik"] = $sisa;
                    $data["kb"] = 0;
                    $data["rb"] = 0;
                    $data_baru["baik"] = $input["jumlah_barang_baru"];
                    $data_baru["kb"] = 0;
                    $data_baru["rb"] = 0;
                }

                $nilai_aset_baru = $input["jumlah_barang_baru"] * $data["harga_satuan"];
                $data_baru["harga_total"] = $nilai_aset_baru;
                $data_baru["harga_total_plus_pajak"] = $nilai_aset_baru;
                $data_baru["harga_total_plus_pajak_saldo"] = $nilai_aset_baru;
                $data_baru["sendiri"] = $input["jumlah_barang_baru"];

                $data_baru["kode_108"] = $kode_108_baru;
                $data_baru["kode_64"] = $kode_64_baru;

                $date = date("Y-m-d H:i:s");  

                $data_baru["created_at"] = $date;
                $data_baru["updated_at"] = $date;

                Rincian_masuk::create($data_baru);
                Kib::create($data_baru);
            }
        }

        $input["kode_koreksi"] = $kode_koreksi;
        $input["nomor_lokasi"] = $data["nomor_lokasi"];
        $input["bidang_barang"] = $data["bidang_barang"];
        $input["nama_barang"] = $data["nama_barang"];
        $input["no_ba_penerimaan"] = $data["no_ba_penerimaan"];
        $input["no_register"] = $data["no_register"];
        $input["no_label"] = $data["no_label"];
        $input["nama_barang"] = $data["nama_barang"];
        $input["merk_alamat"] = $data["merk_alamat"];
        $input["kode_108"] = $input["kode_108"];
        $input["kode_108_baru"] = $input["kode_108_baru"];
        $input["kode_64"] = $kode_64;
        $input["kode_64_baru"] = $kode_64_baru;
        $input["kode_kepemilikan"] = $kepemilikan;
        $input["kode_kepemilikan_baru"] = $kepemilikan_baru;
        $input["nilai"] = $nilai;
        $input["nilai_baru"] = $nilai_baru;
        $input["jumlah_barang_baru"] = $data["jumlah_barang"];
        $input["tahun_koreksi"]  = $tahun_spj;

        if(!array_key_exists("kode_koreksi", $input)) {
            $input["kode_koreksi"] = $kode_koreksi;
        }

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required',
            'kode_koreksi' => 'required'
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(!$validate) {
            return $this->sendError('Validation Error.', $error_message);
        }

        $rincian_koreksi = Rincian_koreksi::create($input);

        $kib = Kib::where('id_aset', $id_aset)->update($data);
        $kib = Kib_awal::where('id_aset', $id_aset)->update($data);

        return $this->sendResponse($rincian_koreksi->toArray(), 'Rincian koreksi created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id_aset)
    {
        $rincian_koreksi = Rincian_koreksi::find($id_aset);

        if (is_null($rincian_koreksi)) {
            return $this->sendError('Rincian keluar not found.');
        }

        return $this->sendResponse($rincian_koreksi->toArray(), 'Rincian keluar retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rincian_koreksi $rincian_koreksi)
    {
        $input = $request->all();

        if (!array_key_exists("nomor_lokasi", $input)) {
            $input["nomor_lokasi"] = substr($input["no_key"], 0, -14);
        }

        $aset = Kib::where('id_aset', $input["id_aset"])->first();

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required',
            'jumlah_barang' => 'required|numeric|min:0|max:'. $aset->saldo_barang
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } else {
            $rincian_koreksi->update($input);
            return $this->sendResponse($rincian_koreksi->toArray(), 'Rincian keluar updated successfully.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Rincian_koreksi $rincian_koreksis)
    {   
        $rincian_koreksis->toArray();

        $updateNeraca["kode_108"] = $rincian_koreksis["kode_108"];
        $updateNeraca["kode_64"] = $rincian_koreksis["kode_64"];

        $updateNilai["harga_total_plus_pajak_saldo"] = $rincian_koreksis["nilai"];

        $updateKoreksi["kode_kepemilikan"] = $rincian_koreksis["kode_kepemilikan"];

        //kode ..6
        if ($rincian_koreksis["kode_koreksi"] == 306 || $rincian_koreksis["kode_koreksi"] == 336) {

            if (Rincian_keluar::select('no_key')->where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
                $no_key = Rincian_keluar::select('no_key')->where('id_aset', $rincian_koreksis["id_aset"])->first()->no_key;

                if (isset($no_key)) {
                    $diterima = Jurnal::select('diterima')->where('no_key', $no_key)->first()->diterima;

                    if ($diterima == 1) {
                        $check_kibs = Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->first();

                        if (isset($check_kibs)) {
                            Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateNeraca);
                            Rincian_masuk::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateNeraca);
                        }
                    }
                    else{
                        Kib::where('no_register', $rincian_koreksis["id_aset"])->update($updateNeraca);
                    }
                }
            }

            if (Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
                Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->update($updateNeraca);
            }
            else{
                Rincian_masuk::where('no_register', $rincian_koreksis["id_aset"])->update($updateNeraca);
            }
        }

        //kode ..3
        elseif($rincian_koreksis["kode_koreksi"] == 303 || $rincian_koreksis["kode_koreksi"] == 333) {

            if (Rincian_keluar::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
                $no_key = Rincian_keluar::select('no_key')->where('id_aset', $rincian_koreksis["id_aset"])->first()->no_key;

                if (isset($no_key)) {
                    $diterima = Jurnal::select('diterima')->where('no_key', $no_key)->first()->diterima;

                    if ($diterima == 1) {
                        $check_kibs = Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->first();

                        if (isset($check_kibs)) {
                            Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateNilai);
                            Rincian_masuk::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateNilai);
                        }
                    }
                    else{
                        Kib::where('no_register', $rincian_koreksis["id_aset"])->update($updateNilai);
                    }
                }
            }

            if (Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
               Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->update($updateNilai);
            }
            else{
                Rincian_masuk::where('id_aset', $rincian_koreksis["id_aset"])->update($updateNilai);
            }
        }

        //kode 002
        elseif($rincian_koreksis["kode_koreksi"] == 302 || $rincian_koreksis["kode_koreksi"] == 332) {          

            if (Rincian_keluar::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
                $no_key = Rincian_keluar::select('no_key')->where('id_aset', $rincian_koreksis["id_aset"])->first()->no_key;

                if(isset($no_key)) {
                    $diterima = Jurnal::select('diterima')->where('no_key', $no_key)->first()->diterima;

                    if ($diterima == 1) {
                        $check_kibs = Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->first();

                        if (isset($check_kibs)) {
                            Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateNilai);
                            Rincian_masuk::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateNilai);
                        }
                    }
                    else{
                        Kib::where('no_register', $rincian_koreksis["id_aset"])->update($updateNilai);
                    }
                }
            }

            if (Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
               Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->update($updateNilai);
            }
            else{
                Rincian_masuk::where('id_aset', $rincian_koreksis["id_aset"])->update($updateNilai);
            }
        }

        //kode 007
        elseif($rincian_koreksis["kode_koreksi"] == 307 || $rincian_koreksis["kode_koreksi"] == 337) {          

            if (Rincian_keluar::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
                $no_key = Rincian_keluar::select('no_key')->where('id_aset', $rincian_koreksis["id_aset"])->first()->no_key;

                if(isset($no_key)) {
                    $diterima = Jurnal::select('diterima')->where('no_key', $no_key)->first()->diterima;

                    if ($diterima == 1) {
                        $check_kibs = Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->first();

                        if (isset($check_kibs)) {
                            Kib::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateKoreksi);
                            Rincian_masuk::where('no_register', $rincian_koreksis["id_aset"])->where('kode_jurnal', 102)->update($updateKoreksi);
                        }
                    }
                    else{
                        Kib::where('id_aset', $rincian_koreksis["id_aset"])->update($updateKoreksi);
                    }
                }
            }

            if (Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->exists()) {
               Kib_awal::where('id_aset', $rincian_koreksis["id_aset"])->update($updateKoreksi);
            }
            else{
                Rincian_masuk::where('id_aset', $rincian_koreksis["id_aset"])->update($updateKoreksi);
            }
        }

        Rincian_koreksi::where('id_aset', $rincian_koreksis["id_aset"])->where('kode_koreksi', $rincian_koreksis["kode_koreksi"])->delete();
        
        return $this->sendResponse($rincian_koreksi->toArray(), 'Rincian koreksi deleted successfully.');
    }
}