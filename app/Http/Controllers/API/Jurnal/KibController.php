<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Kib;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Kamus_masa_manfaat;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Reklasifikasi;
use App\Http\Resources\Jurnal\KibCollection;
use App\Http\Resources\Kamus\Kamus_rekeningCollection;
use App\Http\Resources\Kamus\Kamus_masa_manfaatCollection;
use App\Http\Resources\Jurnal\JurnalCollection;
use App\Http\Resources\Jurnal\ReklasifikasiCollection;
use App\Exports\KibsExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use DB;

//penggantian tahun menggunakan db selector dengan implement DB class usage

class KibController extends BaseController
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
            $kibs = new KibCollection(Kib::all());
        } else {
            $kibs = new KibCollection(Kib::paginate($request->get('per_page')));
        }

        return $kibs;
    }

    public function getByLokasi(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');

        if($pagination === 0) {
            $kibs = new KibCollection(Kib::where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->get());
        } else {
            $kibs = new KibCollection(Kib::where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->paginate($request->get('per_page')));
        }

        return $kibs;
    }

    public function getListByJurnalLokasi(Request $request, $kode_jurnal, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $tahun_spj = DB::table('tahun_spj')->select('tahun')->first()->tahun;

        if($pagination === 0) {
            $kibs = new KibCollection(Kib::select('id_aset', 'no_key', 'nama_barang', 'merk_alamat', 'tipe', 'bidang_barang', 'harga_total', 'harga_total_plus_pajak_saldo')->where('kode_jurnal', $kode_jurnal)->where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->where('tahun_spj', $tahun_spj)->get());
        } else {
            $kibs = new KibCollection(Kib::select('id_aset', 'no_key', 'nama_barang', 'merk_alamat', 'tipe', 'bidang_barang', 'harga_total', 'harga_total_plus_pajak_saldo')->where('kode_jurnal', $kode_jurnal)->where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->where('tahun_spj', $tahun_spj)->paginate($request->get('per_page')));
        }

        return $this->sendResponse($kibs, 'Aset retrieved successfully.');
    }

    public function getBySpk(Request $request, $spk)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $nomor_spk = base64_decode($spk);
        
        if($pagination === 0) {
            $kibs = new KibCollection(Kib::where('no_bukti_perolehan', '=', $nomor_spk)->get());
        } else {
            $kibs = new KibCollection(Kib::where('no_bukti_perolehan', '=', $nomor_spk)->paginate($request->get('per_page')));
        }

        return $kibs;
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

        if(!array_key_exists("no_key", $input)) {
            $nomor_spk = $input["no_bukti_perolehan"];

            $jurnal = Jurnal::select('no_key','terkunci','nomor_lokasi','tahun_spj','no_ba_penerimaan','kode_jurnal','tgl_spk_sp_dokumen')->where('no_spk_sp_dokumen', 'like', $nomor_spk)->first();

            $input["no_key"] = $jurnal->no_key;
            $input["nomor_lokasi"] = $jurnal->nomor_lokasi;
            $input["terkunci"] = $jurnal->terkunci;
            $input["tahun_spj"] = $jurnal->tahun_spj;
            $input["no_ba_penerimaan"] = $jurnal->no_ba_penerimaan;
            $input["id_transaksi"] = $jurnal->kode_jurnal;
            $input["tgl_perolehan"] = $jurnal->tgl_spk_sp_dokumen;
            $input["kode_jurnal"] = $jurnal->kode_jurnal;
        }

        $temp = $input["nomor_lokasi"].".".$input["tahun_spj"].".".$input["kode_108"];
        $max_id_aset = Kib::select('id_aset')->where('id_aset', 'like', '%'.$temp.'%')->orderBy('id_aset', 'DESC')->first()->id_aset;

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

        $id_aset = $temp.".".$id_aset_index;

        $input["id_aset"] = $id_aset;
        $input["no_register"] = $id_aset;

        $kelompok = Kamus_rekening::select('kelompok')->where('kode_108', 'like', $kode_108.'%')->first()->kelompok;

        if (in_array(substr($kelompok, 0, 2), array("02", "03", "04"))) {
            $masa_manfaat = Kamus_masa_manfaat::select('masa_manfaat')->where('kelompok', 'like', $kelompok)->first()->masa_manfaat;
        } else {
            $masa_manfaat = NULL;
        } 
        
        if(!is_null($masa_manfaat)) {
            $nilai_pengadaan = (double) $input["harga_total_plus_pajak_saldo"];
            $penyusutan_per_tahun = (double) $nilai_pengadaan/$masa_manfaat;
            $akumulasi_penyusutan = $penyusutan_per_tahun;
            $nilai_buku = $nilai_pengadaan - $akumulasi_penyusutan;
            $prosen_susut = 1/$masa_manfaat*100;

            $input["waktu_susut"] = $masa_manfaat;
            $input["prosen_susut"] = $prosen_susut;
            $input["ak_penyusutan"] = $akumulasi_penyusutan;
            $input["tahun_buku"] = $input["tahun_spj"];
            $input["nilai_buku"] = $nilai_buku;
        }

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required',
            'no_ba_penerimaan' => 'required',
            'nomor_lokasi' => 'required',
            'id_transaksi' => 'required',
            'pinjam_pakai' => 'required',
            'pajak' => 'required',
            'bidang_barang' => 'required',
            'terkunci' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(($input["reklas"]) == 1) {
            $reklas = array();
            $reklas["nomor_lokasi"] = $input["nomor_lokasi"];
            $reklas["tahun_reklas"] = $input["tahun_spj"];
            $reklas["jenis_reklas"] = "kode";
            $reklas["asal"] = $input["kode_asal"];
            $reklas["tujuan"] = $input["kode_108"];

            $reklasifikasi = Reklasifikasi::create($reklas);
        }

        $kib = Kib::create($input);

        return $this->sendResponse($kib->toArray(), 'KIB created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id_aset)
    {
        $kib = Kib::find($id_aset);

        if (is_null($kib)) {
            return $this->sendError('KIB not found.');
        }

        return $this->sendResponse($kib->toArray(), 'KIB retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kib $kib)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required',
            'no_ba_penerimaan' => 'required',
            'nomor_lokasi' => 'required',
            'id_transaksi' => 'required',
            'pinjam_pakai' => 'required',
            'pajak' => 'required',
            'bidang_barang' => 'required',
            'terkunci' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kib->update($input);

        return $this->sendResponse($kib->toArray(), 'KIB updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kib $kib)
    {
        $kib->delete();

        return $this->sendResponse($kib->toArray(), 'KIB deleted successfully.');
    }

    public function export() 
    {
        return Excel::download(new KibsExport, 'kibs.xlsx');
    }
}