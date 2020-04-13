<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_permen_108;
use App\Http\Resources\Kamus\Kamus_permen_108Collection;
use Validator;

class Kamus_permen_108Controller extends BaseController
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
            $kamus_permen_108s = new Kamus_permen_108Collection(Kamus_permen_108::all());
        } else {
            $kamus_permen_108s = new Kamus_permen_108Collection(Kamus_permen_108::paginate($request->get('per_page')));
        }

        return $kamus_permen_108s;
        // $kamus_permen_108s = lokasiinsi::all();
        // return $this->sendResponse($kamus_permen_108s->toArray(), 'Kamus_permen_108s retrieved successfully.');
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
            'akun' => 'required',
            'kelompok' => 'required',
            'jenis' => 'required',
            'kode' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_permen_108 = Kamus_permen_108::create($input);

        return $this->sendResponse($kamus_permen_108->toArray(), 'Kamus permen 108 created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_permen 108
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $kamus_permen_108 = Kamus_permen_108::find($kode);

        if (is_null($kamus_permen_108)) {
            return $this->sendError('Permen 108 not found.');
        }

        return $this->sendResponse($kamus_permen_108->toArray(), 'Permen 108 retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_permen 108
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_permen_108 $kamus_permen_108)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'akun' => 'required',
            'kelompok' => 'required',
            'jenis' => 'required',
            'kode' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_permen_108->update($input);

        return $this->sendResponse($kamus_permen_108->toArray(), 'Permen 108 updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_permen 108
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_permen_108 $kamus_permen_108)
    {
        $kamus_permen_108->delete();

        return $this->sendResponse($kamus_permen_108->toArray(), 'Lokasi deleted successfully.');
    }
}