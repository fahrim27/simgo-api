<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_permen_13;
use App\Http\Resources\Kamus\Kamus_permen_13Collection;
use Validator;

class Kamus_permen_13Controller extends BaseController
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
            $kamus_permen_13s = new Kamus_permen_13Collection(Kamus_permen_13::all());
        } else {
            $kamus_permen_13s = new Kamus_permen_13Collection(Kamus_permen_13::paginate($request->get('per_page')));
        }

        return $kamus_permen_13s;
        // $kamus_permen_13s = lokasiinsi::all();
        // return $this->sendResponse($kamus_permen_13s->toArray(), 'Kamus_permen_13s retrieved successfully.');
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
            'bidang' => 'required',
            'kode' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_permen_13 = Kamus_permen_13::create($input);

        return $this->sendResponse($kamus_permen_13->toArray(), 'Kamus permen 13 created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_permen 13
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $kamus_permen_13 = Kamus_permen_13::find($kode);

        if (is_null($kamus_permen_13)) {
            return $this->sendError('Permen 13 not found.');
        }

        return $this->sendResponse($kamus_permen_13->toArray(), 'Permen 13 retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_permen 13
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_permen_13 $kamus_permen_13)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'bidang' => 'required',
            'kode' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_permen_13->update($input);

        return $this->sendResponse($kamus_permen_13->toArray(), 'Permen 13 updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_permen 13
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_permen_13 $kamus_permen_13)
    {
        $kamus_permen_13->delete();

        return $this->sendResponse($kamus_permen_13->toArray(), 'Permen 13 deleted successfully.');
    }
}