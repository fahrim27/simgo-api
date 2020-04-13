<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_provinsi;
use App\Http\Resources\Kamus\Kamus_provinsiCollection;
use Validator;

class Kamus_provinsiController extends BaseController
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
            $kamus_provinsis = new Kamus_provinsiCollection(Kamus_provinsi::all());
        } else {
            $kamus_provinsis = new Kamus_provinsiCollection(Kamus_provinsi::paginate($request->get('per_page')));
        }

        return $kamus_provinsis;
        // $kamus_provinsis = Provinsi::all();
        // return $this->sendResponse($kamus_provinsis->toArray(), 'kamus_provinsis retrieved successfully.');
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
            'nomor_prov' => 'required',
            'nama_provinsi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_provinsi = Kamus_provinsi::create($input);

        return $this->sendResponse($kamus_provinsi->toArray(), 'Kamus provinsi created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kamus_provinsi = Kamus_provinsi::find($id);

        if (is_null($kamus_provinsi)) {
            return $this->sendError('Provinsi not found.');
        }

        return $this->sendResponse($kamus_provinsi->toArray(), 'Provinsi retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_provinsi $kamus_provinsi)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_prov' => 'required',
            'nama_provinsi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_provinsi->nomor_prov = $input['nomor_prov'];
        $kamus_provinsi->nama_provinsi = $input['nama_provinsi'];
        $kamus_provinsi->save();

        return $this->sendResponse($kamus_provinsi->toArray(), 'Provinsi updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_provinsi $kamus_provinsi)
    {
        $kamus_provinsi->delete();

        return $this->sendResponse($kamus_provinsi->toArray(), 'Provinsi deleted successfully.');
    }
}