<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_spk_temp;
use App\Http\Resources\Kamus\Kamus_spk_tempCollection;
use Validator;

class Kamus_spk_tempController extends BaseController
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
            $kamus_spk_temps = new Kamus_spk_tempCollection(Kamus_spk_temp::all());
        } else {
            $kamus_spk_temps = new Kamus_spk_tempCollection(Kamus_spk_temp::paginate($request->get('per_page')));
        }

        return $kamus_spk_temps;
        // $kamus_spk_temps = spk::all();
        // return $this->sendResponse($kamus_spk_temps->toArray(), 'Kamus_spk_temps retrieved successfully.');
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
            'no_kontrak' => 'required',
            'nilai_spk' => 'required',
            'nilai_add' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_spk_temp = Kamus_spk_temp::create($input);

        return $this->sendResponse($kamus_spk_temp->toArray(), 'Kamus spk created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_spk
     * @return \Illuminate\Http\Response
     */
    public function show($no_kontrak)
    {
        $kamus_spk_temp = Kamus_spk_temp::find($no_kontrak);

        if (is_null($kamus_spk_temp)) {
            return $this->sendError('spk not found.');
        }

        return $this->sendResponse($kamus_spk_temp->toArray(), 'Spk retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_spk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_spk_temp $kamus_spk_temp)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'no_kontrak' => 'required',
            'nilai_spk' => 'required',
            'nilai_add' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_spk_temp->update($input);

        return $this->sendResponse($kamus_spk_temp->toArray(), 'spk updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_spk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_spk_temp $kamus_spk_temp)
    {
        $kamus_spk_temp->delete();

        return $this->sendResponse($kamus_spk_temp->toArray(), 'spk deleted successfully.');
    }
}