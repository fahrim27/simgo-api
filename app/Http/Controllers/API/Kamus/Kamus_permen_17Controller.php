<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_permen_17;
use App\Http\Resources\Kamus\Kamus_permen_17Collection;
use Validator;

class Kamus_permen_17Controller extends BaseController
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
            $kamus_permen_17s = new Kamus_permen_17Collection(Kamus_permen_17::all());
        } else {
            $kamus_permen_17s = new Kamus_permen_17Collection(Kamus_permen_17::paginate($request->get('per_page')));
        }

        return $kamus_permen_17s;
        // $kamus_permen_17s = lokasiinsi::all();
        // return $this->sendResponse($kamus_permen_17s->toArray(), 'Kamus_permen_17s retrieved successfully.');
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
            'golongan' => 'required',
            'kode' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_permen_17 = Kamus_permen_17::create($input);

        return $this->sendResponse($kamus_permen_17->toArray(), 'Kamus permen 17 created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_permen 17
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $kamus_permen_17 = Kamus_permen_17::find($kode);

        if (is_null($kamus_permen_17)) {
            return $this->sendError('Permen 17 not found.');
        }

        return $this->sendResponse($kamus_permen_17->toArray(), 'Permen 17 retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_permen 17
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_permen_17 $kamus_permen_17)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'golongan' => 'required',
            'kode' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_permen_17->update($input);

        return $this->sendResponse($kamus_permen_17->toArray(), 'Permen 17 updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_permen 17
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_permen_17 $kamus_permen_17)
    {
        $kamus_permen_17->delete();

        return $this->sendResponse($kamus_permen_17->toArray(), 'Permen 17 deleted successfully.');
    }
}