<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_unit;
use App\Http\Resources\Kamus\Kamus_unitCollection;
use Validator;

class Kamus_unitController extends BaseController
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
            $kamus_units = new Kamus_unitCollection(Kamus_unit::all());
        } else {
            $kamus_units = new Kamus_unitCollection(Kamus_unit::paginate($request->get('per_page')));
        }

        return $kamus_units;
        // $kamus_units = unit::all();
        // return $this->sendResponse($kamus_units->toArray(), 'kamus_units retrieved successfully.');
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
            'nomor_unit' => 'required',
            'nama_unit' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_unit = Kamus_unit::create($input);

        return $this->sendResponse($kamus_unit->toArray(), 'Kamus unit created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_unit
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_unit)
    {
        $kamus_unit = Kamus_unit::find($nomor_unit);

        if (is_null($kamus_unit)) {
            return $this->sendError('Unit not found.');
        }

        return $this->sendResponse($kamus_unit->toArray(), 'Unit retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_unit $kamus_unit)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_unit' => 'required',
            'nama_unit' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_unit->update($input);

        return $this->sendResponse($kamus_unit->toArray(), 'Unit updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_unit $kamus_unit)
    {
        $kamus_unit->delete();

        return $this->sendResponse($kamus_unit->toArray(), 'Unit deleted successfully.');
    }
}