<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Reklasifikasi;
use App\Http\Resources\Jurnal\ReklasifikasiCollection;
use Validator;

class ReklasifikasiController extends BaseController
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
            $reklasifikasis = new ReklasifikasiCollection(Reklasifikasi::all());
        } else {
            $reklasifikasis = new ReklasifikasiCollection(Reklasifikasi::paginate($request->get('per_page')));
        }

        return $reklasifikasis;
    }

    public function getByLokasi(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $nomor_unit = $nomor_lokasi;
        
        if($pagination === 0) {
            $reklasifikasis = new ReklasifikasiCollection(Reklasifikasi::where('nomor_lokasi', 'like', '%'.$nomor_unit.'%')->get());
        } else {
            $reklasifikasis = new ReklasifikasiCollection(Reklasifikasi::where('nomor_lokasi', 'like', '%'.$nomor_unit.'%')->paginate($request->get('per_page')));
        }

        return $reklasifikasis;
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
            'nomor_lokasi' => 'required',
            'id_aset' => 'required',
            'tahun_reklas' => 'required',
            'jenis_reklas' => 'required',
            'asal' => 'required',
            'tujuan' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $reklasifikasi = Reklasifikasi::create($input);

        return $this->sendResponse($reklasifikasi->toArray(), 'Jurnal created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reklasifikasi = Reklasifikasi::find($id);

        if (is_null($reklasifikasi)) {
            return $this->sendError('Jurnal not found.');
        }

        return $this->sendResponse($reklasifikasi->toArray(), 'Jurnal retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnal $reklasifikasi)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_lokasi' => 'required',
            'id_aset' => 'required',
            'tahun_reklas' => 'required',
            'jenis_reklas' => 'required',
            'asal' => 'required',
            'tujuan' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $reklasifikasi->update($input);

        return $this->sendResponse($reklasifikasi->toArray(), 'Jurnal updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurnal $reklasifikasi)
    {
        $reklasifikasi->delete();

        return $this->sendResponse($reklasifikasi->toArray(), 'Jurnal deleted successfully.');
    }
}