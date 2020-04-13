<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_masa_manfaat;
use App\Http\Resources\Kamus\Kamus_masa_manfaatCollection;
use Validator;

class Kamus_masa_manfaatController extends BaseController
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
            $kamus_masa_manfaats = new Kamus_masa_manfaatCollection(Kamus_masa_manfaat::all());
        } else {
            $kamus_masa_manfaats = new Kamus_masa_manfaatCollection(Kamus_masa_manfaat::paginate($request->get('per_page')));
        }

        return $kamus_masa_manfaats;
        // $kamus_masa_manfaats = Bidang unit::all();
        // return $this->sendResponse($kamus_masa_manfaats->toArray(), 'Kamus_masa_manfaats retrieved successfully.');
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
            'kelompok' => 'required',
            'uraian_kelompok' => 'required',
            'masa_manfaat' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_masa_manfaat = Kamus_masa_manfaat::create($input);

        return $this->sendResponse($kamus_masa_manfaat->toArray(), 'Kamus rekening created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_sub_unit)
    {
        $kamus_masa_manfaat = Kamus_masa_manfaat::find($nomor_sub_unit);

        if (is_null($kamus_masa_manfaat)) {
            return $this->sendError('Rekening not found.');
        }

        return $this->sendResponse($kamus_masa_manfaat->toArray(), 'Rekening retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_masa_manfaat $kamus_masa_manfaat)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'kelompok' => 'required',
            'uraian_kelompok' => 'required',
            'masa_manfaat' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_masa_manfaat->update($input);

        return $this->sendResponse($kamus_masa_manfaat->toArray(), 'Rekening updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_masa_manfaat $kamus_masa_manfaat)
    {
        $kamus_masa_manfaat->delete();

        return $this->sendResponse($kamus_masa_manfaat->toArray(), 'Rekening deleted successfully.');
    }
}