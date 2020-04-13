<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_permen_64;
use App\Http\Resources\Kamus\Kamus_permen_64Collection;
use Validator;

class Kamus_permen_64Controller extends BaseController
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
            $kamus_permen_64s = new Kamus_permen_64Collection(Kamus_permen_64::all());
        } else {
            $kamus_permen_64s = new Kamus_permen_64Collection(Kamus_permen_64::paginate($request->get('per_page')));
        }

        return $kamus_permen_64s;
        // $kamus_permen_64s = lokasiinsi::all();
        // return $this->sendResponse($kamus_permen_64s->toArray(), 'Kamus_permen_64s retrieved successfully.');
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

        $kamus_permen_64 = Kamus_permen_64::create($input);

        return $this->sendResponse($kamus_permen_64->toArray(), 'Kamus permen 64 created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_permen 64
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $kamus_permen_64 = Kamus_permen_64::find($kode);

        if (is_null($kamus_permen_64)) {
            return $this->sendError('Permen 64 not found.');
        }

        return $this->sendResponse($kamus_permen_64->toArray(), 'Permen 64 retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_permen 64
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_permen_64 $kamus_permen_64)
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

        $kamus_permen_64->update($input);

        return $this->sendResponse($kamus_permen_64->toArray(), 'Permen 64 updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_permen 64
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_permen_64 $kamus_permen_64)
    {
        $kamus_permen_64->delete();

        return $this->sendResponse($kamus_permen_64->toArray(), 'Permen 64 deleted successfully.');
    }
}