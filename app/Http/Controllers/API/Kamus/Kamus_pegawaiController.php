<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_pegawai;
use App\Http\Resources\Kamus\Kamus_pegawaiCollection;
use Validator;

class Kamus_pegawaiController extends BaseController
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
            $kamus_pegawais = new Kamus_pegawaiCollection(Kamus_pegawai::all());
        } else {
            $kamus_pegawais = new Kamus_pegawaiCollection(Kamus_pegawai::paginate($request->get('per_page')));
        }

        return $kamus_pegawais;
        // $kamus_pegawais = Bidang unit::all();
        // return $this->sendResponse($kamus_pegawais->toArray(), 'Kamus_pegawais retrieved successfully.');
    }

    public function getByLokasi(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');

        if($pagination === 0) {
            $kamus_pegawais = new Kamus_pegawaiCollection(Kamus_pegawai::where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->get());
        } else {
            $kamus_pegawais = new Kamus_pegawaiCollection(Kamus_pegawai::where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->paginate($request->get('per_page')));
        }

        return $kamus_pegawais;
    }

    public function getData(Request $request, $nip)
    {
        $nip = base64_decode($nip);

        $pegawai = new Kamus_pegawaiCollection(Kamus_pegawai::where('nip', 'like', '%'. $nip .'%')->get());

        return $pegawai;
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

        $validator = Validator::make($input, [
            'nip' => 'required',
            'nama' => 'required',
            'nomor_lokasi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_pegawai = Kamus_pegawai::create($input);

        return $this->sendResponse($kamus_pegawai->toArray(), 'Kamus Pegawai created successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request, $nip)
    {
        $nip = base64_decode($nip);
        $kamus_pegawai = Kamus_pegawai::find($nip);
        $input = $request->all();

        $validator = Validator::make($input, [
            'nip' => 'required',
            'nama' => 'required',
            'nomor_lokasi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_pegawai->update($input);

        return $this->sendResponse($kamus_pegawai->toArray(), 'Pegawai updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_pegawai $kamus_pegawai)
    {
        $kamus_pegawai->delete();

        return $this->sendResponse($kamus_pegawai->toArray(), 'Pegawai deleted successfully.');
    }
}