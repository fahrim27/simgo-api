<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_kab_kota;
use App\Http\Resources\Kamus\Kamus_kab_kotaCollection;
use Validator;

class Kamus_kab_kotaController extends BaseController
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
            $kamus_kab_kotas = new Kamus_kab_kotaCollection(Kamus_kab_kota::all());
        } else {
            $kamus_kab_kotas = new Kamus_kab_kotaCollection(Kamus_kab_kota::paginate($request->get('per_page')));
        }

        return $kamus_kab_kotas;
        // $kamus_kab_kotas = kab_kota::all();
        // return $this->sendResponse($kamus_kab_kotas->toArray(), 'kamus_kab_kotas retrieved successfully.');
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
            'nomor_kab' => 'required',
            'nama_kab' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_kab_kota = Kamus_kab_kota::create($input);

        return $this->sendResponse($kamus_kab_kota->toArray(), 'Kamus kab_kota created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_kab
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_kab)
    {
        $kamus_kab_kota = Kamus_kab_kota::find($nomor_kab);

        if (is_null($kamus_kab_kota)) {
            return $this->sendError('kab_kota not found.');
        }

        return $this->sendResponse($kamus_kab_kota->toArray(), 'kab_kota retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_kab
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_kab_kota $kamus_kab_kota)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_kab' => 'required',
            'nama_kab' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_kab_kota->nomor_kab = $input['nomor_kab'];
        $kamus_kab_kota->nama_kab_kota = $input['nama_kab'];
        $kamus_kab_kota->save();

        return $this->sendResponse($kamus_kab_kota->toArray(), 'kab_kota updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_kab
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_kab_kota $kamus_kab_kota)
    {
        $kamus_kab_kota->delete();

        return $this->sendResponse($kamus_kab_kota->toArray(), 'kab_kota deleted successfully.');
    }
}