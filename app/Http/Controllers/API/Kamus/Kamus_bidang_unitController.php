<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\kamus_bidang_unit;
use App\Http\Resources\Kamus\kamus_bidang_unitCollection;
use Validator;

class Kamus_bidang_unitController extends BaseController
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
            $kamus_bidang_units = new Kamus_bidang_unitCollection(Kamus_bidang_unit::all());
        } else {
            $kamus_bidang_units = new Kamus_bidang_unitCollection(Kamus_bidang_unit::paginate($request->get('per_page')));
        }

        return $kamus_bidang_units;
        // $kamus_bidang_units = Bidang unit::all();
        // return $this->sendResponse($kamus_bidang_units->toArray(), 'kamus_bidang_units retrieved successfully.');
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
            'nomor_bidang_unit' => 'required',
            'nama_bidang_unit' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_bidang_unit = Kamus_bidang_unit::create($input);

        return $this->sendResponse($kamus_bidang_unit->toArray(), 'Kamus Bidang unit created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kamus_bidang_unit = Kamus_bidang_unit::find($id);

        if (is_null($kamus_bidang_unit)) {
            return $this->sendError('Bidang unit not found.');
        }

        return $this->sendResponse($kamus_bidang_unit->toArray(), 'Bidang unit retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_bidang_unit $kamus_bidang_unit)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_bidang_unit' => 'required',
            'nama_bidang_unit' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $kamus_bidang_unit->update($input);

        return $this->sendResponse($kamus_bidang_unit->toArray(), 'Bidang unit updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_bidang_unit $kamus_bidang_unit)
    {
        $kamus_bidang_unit->delete();

        return $this->sendResponse($kamus_bidang_unit->toArray(), 'Bidang unit deleted successfully.');
    }
}