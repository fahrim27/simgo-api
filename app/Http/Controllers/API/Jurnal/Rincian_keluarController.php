<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Rincian_keluar;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Jurnal;
use App\Http\Resources\Jurnal\Rincian_keluarCollection;
use App\Http\Resources\Jurnal\JurnalCollection;
use App\Http\Resources\Jurnal\KibCollection;
use Validator;

class Rincian_keluarController extends BaseController
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
            $rincian_keluars = new Rincian_keluarCollection(Rincian_keluar::all());
        } else {
            $rincian_keluars = new Rincian_keluarCollection(Rincian_keluar::paginate($request->get('per_page')));
        }

        return $rincian_keluars;
    }

    public function getByNoKey(Request $request, $no_key)
    {
        $pagination = (int)$request->header('Pagination');

        if($pagination === 0) {
            $rincian_keluars = new Rincian_keluarCollection(Rincian_keluar::where('no_key', $no_key)->get());
        } else {
            $rincian_keluars = new Rincian_keluarCollection(Rincian_keluar::where('no_key', $no_key)->paginate(10));
        }

        return $rincian_keluars;
    }

    public function getAvailableAset(Request $request, $nomor_lokasi)
    {
        $rincian_keluars = new KibCollection(Kib::select('id_aset','no_register', 'kode_108','nama_barang','harga_satuan','saldo_barang')
        ->where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')
        ->where('saldo_barang', '>', 0)
        ->get());

        return $rincian_keluars;
    }

    public function getDetailAsetKeluar(Request $request, $id_aset)
    {
        $asets = new KibCollection(Kib::select('id_aset', 'no_register', 'kode_64', 'kode_108', 'nama_barang', 'merk_alamat', 'saldo_barang', 'satuan', 'harga_satuan', 'baik', 'kb', 'rb', 'sendiri', 'sengketa', 'pihak_3')->where('id_aset', $id_aset)->get());

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
        
        $rincian_keluar = Rincian_keluar::create($input);

        return $this->sendResponse($rincian_keluar->toArray(), 'Rincian keluar created successfully.');
    }

    //untuk menyimpan sesuai dengan jurnal
    public function save(Request $request, $kode_jurnal)
    {
        $input = $request->all();

        $tahun_spj = date('Y') - 1;
        $id_aset = $input["id_aset"];

        $aset = Kib::where('id_aset', $id_aset)->first();

        $input["nomor_lokasi"] = $aset->nomor_lokasi;
        $input["no_register"] = $id_aset;
        $input["nama_barang"] = $aset->nama_barang;
        $input["merk_alamat"] = $aset->merk_alamat;
        $input["tipe"] = $aset->tipe;
        $input["no_ba_penerimaan"] = $aset->no_ba_penerimaan;
        $input["kode_kepemilikan"] = $aset->kode_kepemilikan;
        $input["bidang_barang"] = $aset->bidang_barang;
        $input["tahun_pengadaan"] = $aset->tahun_pengadaan;
        $input["tahun_perolehan"] = $aset->tahun_perolehan;
        $input["no_label"] = $aset->no_label;
        $input["kode_108"] = $aset->kode_108;
        $input["kode_64"]  = $aset->kode_64;
        $input["harga_satuan"]  = $aset->harga_satuan;
        $input["pajak"]  = $aset->pajak;
        $input["nopol"]  = $aset->nopol;
        $input["kode_jurnal"] = $kode_jurnal;
        $input["diterima"] = "0";
        $input["tahun_spj"] = $tahun_spj;
       
        $input["baik"] = 0;
        $input["kb"] = 0;
        $input["rb"] = 0;
        $input["sendiri"] = 0;
        $input["sengketa"] = 0;
        $input["pihak_3"] = 0;

        if($input["kondisi"] == "baik") {
            $input["baik"] = (int)$input["jumlah_barang"];
        } else if($input["kondisi"] == "kb") {
            $input["kb"] = (int)$input["jumlah_barang"];
        } else if($input["kondisi"] == "rb") {
            $input["rb"] = (int)$input["jumlah_barang"];
        }

        if ($input["penguasaan"] == "sendiri") {
            $input["sendiri"] = (int)$input["jumlah_barang"];
        } else if ($input["penguasaan"] == "sengketa") {
            $input["sengketa"] = (int)$input["jumlah_barang"];
        } else if ($input["penguasaan"] == "pihak_3") {
            $input["pihak_3"] = (int)$input["jumlah_barang"];
        }

        $input["nilai_keluar"] = $input["jumlah_barang"] * $input["harga_satuan"];
        $input["prosen_susut"] = $aset->prosen_susut;
        $input["akumulasi_penyusutan"] = (int)$input["jumlah_barang"] / $aset->saldo_barang * $aset->ak_penyusutan;
        $input["nilai_buku"] = $input["nilai_keluar"] - $input["akumulasi_penyusutan"];

        if($kode_jurnal != '202') {
            $sisa_barang = $aset->saldo_barang - $input["jumlah_barang"];
            $sisa_harga = $sisa_barang * $input["harga_satuan"];

            if($kode_jurnal == '208') {
                $change["rb"] = $input["jumlah_barang"];
            }

            $change["baik"] = $sisa_barang;
            $change["saldo_barang"] = $sisa_barang;
            $change["saldo_gudang"] = $sisa_barang;
            $change["harga_total_plus_pajak_saldo"] = $sisa_harga;

            Kib::where('id_aset', $id_aset)->update($change);
        }

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'nomor_lokasi' => 'required',
            'id_aset' => 'required',
            'jumlah_barang' => 'required|numeric|min:0|max:'.$aset->saldo_barang
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $rincian_keluar = Rincian_keluar::create($input);

        return $this->sendResponse($rincian_keluar->toArray(), 'Rincian keluar created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id_aset)
    {
        $rincian_keluar = Rincian_keluar::find($id_aset);

        if (is_null($rincian_keluar)) {
            return $this->sendError('Rincian keluar not found.');
        }

        return $this->sendResponse($rincian_keluar->toArray(), 'Rincian keluar retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rincian_keluar $rincian_keluar)
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
            $rincian_keluar->update($input);
            return $this->sendResponse($rincian_keluar->toArray(), 'Rincian keluar updated successfully.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rincian_keluar $rincian_keluar)
    {
        $rincian_keluar->delete();

        return $this->sendResponse($rincian_keluar->toArray(), 'Rincian keluar deleted successfully.');
    }
}