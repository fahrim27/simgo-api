<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Rehab;
use App\Models\Jurnal\Kib;
use App\Http\Resources\Jurnal\RehabCollection;
use App\Http\Resources\Jurnal\KibCollection;
use Validator;

class RehabController extends BaseController
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
            $rehabs = new RehabCollection(Rehab::all());
        } else {
            $rehabs = new RehabCollection(Rehab::paginate(10));
        }

        return $rehabs;
    }

    public function getByLokasi(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        if($pagination === 0) {
            $rehabs = new RehabCollection(Rehab::where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')->get());
        } else {
            $rehabs = new RehabCollection(Rehab::where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')->paginate(10));
        }

        return $rehabs;
    }

    //untuk mencari daftar aset rehab yang belum ditambahkan ke aset induk
    public function getListRehabByInduk(Request $request, $id_aset)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        if($pagination === 0) {
            $rehabs = new RehabCollection(Rehab::join('kibs', 'rehabs.rehab_id', '=', 'kibs.id_aset')
                    ->select('rehab_id', 'kibs.nomor_lokasi', 'kibs.kode_108', 'kibs.harga_total_plus_pajak_saldo', 'kibs.tahun_pengadaan')
                    ->where('aset_induk_id', $id_aset)
                    ->get());
        } else {
            $rehabs = new RehabCollection(Rehab::join('kibs', 'rehabs.rehab_id', '=', 'kibs.id_aset')
                    ->select('rehab_id', 'kibs.nomor_lokasi', 'kibs.kode_108', 'kibs.harga_total_plus_pajak_saldo', 'kibs.tahun_pengadaan')
                    ->where('aset_induk_id', $id_aset)
                    ->paginate(10));
        }

        return $rehabs;
    }

    //untuk mencari daftar aset rehab yang belum ditambahkan ke aset induk
    public function getFreeAsetRehab(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        // if($pagination === 0) {
        //     $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
        //                             ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
        //                             ->whereIn('bidang_barang', ['C', 'D'])
        //                             ->where(function($q) {
        //                                 $q->where('aset_rehab', '=', 1)
        //                                     ->orWhereNotNull('aset_rehab');
        //                             })
        //                             ->whereNotIn('id_aset', Rehab::select('rehab_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
        //                             ->get());
        // } else {
        //     $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
        //                             ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
        //                             ->whereIn('bidang_barang', ['C', 'D'])
        //                             ->where(function($q) {
        //                                 $q->where('aset_rehab', '=', 1)
        //                                     ->orWhereNotNull('aset_rehab');
        //                             })
        //                             ->whereNotIn('id_aset', Rehab::select('rehab_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
        //                             ->paginate(10));
        // }

        if($pagination === 0) {
            $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak', 'no_register')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->whereNotIn('id_aset', Rehab::select('rehab_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->whereNotIn('id_aset', Rehab::select('aset_induk_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->get());
        } else {
            $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak', 'no_register')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->whereNotIn('id_aset', Rehab::select('aset_induk_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->whereNotIn('id_aset', Rehab::select('rehab_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->paginate(10));
        }

        return $rehabs;
    }

    //untuk mencari daftar aset rehab yang sudah ditambahkan ke aset induk
    public function getSavedAsetRehab(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        if($pagination === 0) {
            $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->where(function($q) {
                                        $q->where('aset_rehab', '=', 1)
                                            ->orWhereNotNull('aset_rehab');
                                    })
                                    ->whereIn('id_aset', Rehab::select('rehab_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->get());
        } else {
            $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->where(function($q) {
                                        $q->where('aset_rehab', '=', 1)
                                            ->orWhereNotNull('aset_rehab');
                                    })
                                    ->whereIn('id_aset', Rehab::select('rehab_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->paginate(10));
        }

        return $rehabs;
    }

    //untuk mencari daftar aset induk yang belum ditambahkan aset rehab
    public function getFreeAsetInduk(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        if($pagination === 0) {
            $rehabs = new KibCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->where(function($q) {
                                        $q->where('aset_rehab', '=', 0)
                                            ->orWhereNull('aset_rehab');
                                    })
                                    ->whereNotIn('id_aset', Rehab::select('aset_induk_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->get());
        } else {
            $rehabs = new KibCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->where(function($q) {
                                        $q->where('aset_rehab', '=', 0)
                                            ->orWhereNull('aset_rehab');
                                    })
                                    ->whereNotIn('id_aset', Rehab::select('aset_induk_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->paginate(10));
        }

        return $rehabs;
    }

    //untuk mencari daftar aset induk yang sudah ditambahkan aset rehab
    public function getSavedAsetInduk(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        if($pagination === 0) {
            $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->where(function($q) {
                                        $q->where('aset_rehab', '=', 0)
                                            ->orWhereNull('aset_rehab');
                                    })
                                    ->whereIn('id_aset', Rehab::select('aset_induk_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->get());
        } else {
            $rehabs = new RehabCollection(Kib::select('id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak')
                                    ->where('nomor_lokasi', 'like', $nomor_lokasi .'%')
                                    ->whereIn('bidang_barang', ['C', 'D'])
                                    ->where(function($q) {
                                        $q->where('aset_rehab', '=', 0)
                                            ->orWhereNull('aset_rehab');
                                    })
                                    ->whereIn('id_aset', Rehab::select('aset_induk_id')->where('nomor_lokasi', 'like', $nomor_lokasi .'%')->get()->toArray())
                                    ->paginate(10));
        }

        return $rehabs;
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

        $induk_id = $input["induk_id"];
        $rehab_id = $input["rehab_id"];
        $tambah_manfaat = $input["tambah_manfaat"];

        $aset_induk = Kib::where('id_aset', $induk_id)->select(['id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak'])->first();
        $aset_rehab = Kib::where('id_aset', $rehab_id)->select(['id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak'])->first();

        $aset = Kib::where('id_aset', $rehab_id)->first();

        $input["nama_rehab"] = $aset_rehab->nama_barang;
        $input["tahun_rehab"] = $aset_rehab->tahun_pengadaan;
        $input["nilai_rehab"] = $aset_rehab->harga_total_plus_pajak;
        $input["kode_rek_rehab"] = $aset_rehab->kode_108;
        $input["nomor_lokasi"] = $aset_rehab->nomor_lokasi;
        $input["aset_induk_id"] = $input["induk_id"];
        $input["nama_induk"] = $aset_induk->nama_barang;
        $input["tahun_induk"] = $aset_induk->tahun_pengadaan;
        $input["nilai_induk"] = $aset_induk->harga_total_plus_pajak;
        $input["kode_rek_induk"] = $aset_induk->kode_108;

        $validator = Validator::make($input, [
            'rehab_id' => 'required',
            'aset_induk_id' => 'required',
            'tambah_manfaat' => 'required'
        ]);

        $aset->id_aset_induk = $induk_id;
        $aset->aset_rehab = 1;

        $aset = json_decode(json_encode($aset), true);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $rehab = Rehab::create($input);
        $Kib = Kib::where('id_aset', $rehab_id)->update($aset);

        return $this->sendResponse($rehab->toArray(), 'Aset rehab created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rehab = Rehab::find($id);

        if (is_null($rehab)) {
            return $this->sendError('Aset rehab not found.');
        }

        return $this->sendResponse($rehab->toArray(), 'Aset rehab retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rehab $rehab)
    {
        $input = $request->all();

        $induk_id = $input["induk_id"];
        $rehab_id = $input["rehab_id"];
        $tambah_manfaat = $input["tambah_manfaat"];

       $aset_induk = Kib::where('id_aset', $induk_id)->select(['id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak'])->first();
        $aset_rehab = Kib::where('id_aset', $rehab_id)->select(['id_aset', 'nama_barang', 'nomor_lokasi', 'kode_108', 'tahun_pengadaan', 'harga_total_plus_pajak'])->first();

        $aset = Kib::where('id_aset', $rehab_id)->first();

        $input["nama_rehab"] = $aset_rehab->nama_barang;
        $input["tahun_rehab"] = $aset_rehab->tahun_pengadaan;
        $input["nilai_rehab"] = $aset_rehab->harga_total_plus_pajak;
        $input["kode_rek_rehab"] = $aset_rehab->kode_108;
        $input["nomor_lokasi"] = $aset_rehab->nomor_lokasi;
        $input["aset_induk_id"] = $input["induk_id"];
        $input["nama_induk"] = $aset_induk->nama_barang;
        $input["tahun_induk"] = $aset_induk->tahun_pengadaan;
        $input["nilai_induk"] = $aset_induk->harga_total_plus_pajak;
        $input["kode_rek_induk"] = $aset_induk->kode_108;

        $validator = Validator::make($input, [
            'rehab_id' => 'required',
            'aset_induk_id' => 'required',
            'tambah_manfaat' => 'required'
        ]);

        $aset->id_aset_induk = $induk_id;
        $aset->aset_rehab = 1;

        $aset = json_decode(json_encode($aset), true);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $rehab = $rehab->update($aset);
        $Kib = Kib::where('id_aset', $rehab_id)->update($aset);

        return $this->sendResponse($rehab->toArray(), 'Aset rehab updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rehab $rehab)
    {
        
        $id_aset = $rehab->rehab_id;
        $aset["id_aset_induk"] = NULL;
        $aset["aset_rehab"] = 0;

        $kib = Kib::where('id_aset', $id_aset)->update($aset);
        $delete = Rehab::where('rehab_id', $id_aset)->delete();

        if($delete == 1) {
            return $this->sendResponse($rehab->toArray(), 'Aset rehab deleted successfully.');
        } else {
            return $this->sendResponse($rehab->toArray(), 'Aset rehab cannot to be deleted.');
        }
    }
}