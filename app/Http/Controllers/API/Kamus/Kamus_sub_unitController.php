<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\kamus_sub_unit;
use App\Http\Resources\Kamus\kamus_sub_unitCollection;
use Validator;

class Kamus_sub_unitController extends BaseController
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
            $kamus_sub_units = new Kamus_sub_unitCollection(Kamus_sub_unit::all());
        } else {
            $kamus_sub_units = new Kamus_sub_unitCollection(Kamus_sub_unit::paginate($request->get('per_page')));
        }

        return $kamus_sub_units;
        // $kamus_sub_units = Bidang unit::all();
        // return $this->sendResponse($kamus_sub_units->toArray(), 'kamus_sub_units retrieved successfully.');
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
            'nomor_sub_unit' => 'required',
            'nama_sub_unit' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_sub_unit = Kamus_sub_unit::create($input);

        return $this->sendResponse($kamus_sub_unit->toArray(), 'Kamus sub unit created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_sub_unit)
    {
        $kamus_sub_unit = Kamus_sub_unit::find($nomor_sub_unit);

        if (is_null($kamus_sub_unit)) {
            return $this->sendError('sub unit not found.');
        }

        return $this->sendResponse($kamus_sub_unit->toArray(), 'sub unit retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_sub_unit $kamus_sub_unit)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_sub_unit' => 'required',
            'nama_sub_unit' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_sub_unit->update($input);

        return $this->sendResponse($kamus_sub_unit->toArray(), 'sub unit updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_sub_unit $kamus_sub_unit)
    {
        $kamus_sub_unit->delete();

        return $this->sendResponse($kamus_sub_unit->toArray(), 'sub unit deleted successfully.');
    }
}