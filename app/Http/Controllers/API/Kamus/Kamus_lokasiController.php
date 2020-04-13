<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_spk;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use Validator;

class Kamus_lokasiController extends BaseController
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
            $kamus_lokasis = new Kamus_lokasiCollection(Kamus_lokasi::all());
        } else {
            $kamus_lokasis = new Kamus_lokasiCollection(Kamus_lokasi::paginate($request->get('per_page')));
        }
        
        return $kamus_lokasis;
        // $kamus_lokasis = lokasiinsi::all();
        // return $this->sendResponse($kamus_lokasis->toArray(), 'kamus_lokasis retrieved successfully.');
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
            'nama_lokasi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_lokasi = Kamus_lokasi::create($input);

        return $this->sendResponse($kamus_lokasi->toArray(), 'Kamus lokasi created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_lokasi
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_lokasi)
    {
        $kamus_lokasi = Kamus_lokasi::find($nomor_lokasi);

        if (is_null($kamus_lokasi)) {
            return $this->sendError('lokasi not found.');
        }

        return $this->sendResponse($kamus_lokasi->toArray(), 'Lokasi retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_lokasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_lokasi $kamus_lokasi)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_lokasi' => 'required',
            'nama_lokasi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_lokasi->update($input);

        return $this->sendResponse($kamus_lokasi->toArray(), 'Lokasi updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_lokasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_lokasi $kamus_lokasi)
    {
        $kamus_lokasi->delete();

        return $this->sendResponse($kamus_lokasi->toArray(), 'Lokasi deleted successfully.');
    }
}