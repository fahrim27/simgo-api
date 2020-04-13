<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_mapping_permen;
use App\Http\Resources\Kamus\Kamus_mapping_permenCollection;
use Validator;

class Kamus_mapping_permenController extends BaseController
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
            $kamus_mapping_permens = new Kamus_mapping_permenCollection(Kamus_mapping_permen::all());
        } else {
            $kamus_mapping_permens = new Kamus_mapping_permenCollection(Kamus_mapping_permen::paginate($request->get('per_page')));
        }

        return $kamus_mapping_permens;
        // $kamus_mapping_permens = mapping_permen::all();
        // return $this->sendResponse($kamus_mapping_permens->toArray(), 'Kamus_mapping_permens retrieved successfully.');
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
            'permen_1' => 'required',
            'kode_1' => 'required',
            'permen_2' => 'required',
            'kode_2' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_mapping_permen = Kamus_mapping_permen::create($input);

        return $this->sendResponse($kamus_mapping_permen->toArray(), 'Kamus mapping_permen created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_mapping_permen
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_mapping_permen)
    {
        $kamus_mapping_permen = Kamus_mapping_permen::find($nomor_mapping_permen);

        if (is_null($kamus_mapping_permen)) {
            return $this->sendError('mapping_permen not found.');
        }

        return $this->sendResponse($kamus_mapping_permen->toArray(), 'mapping_permen retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_mapping_permen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_mapping_permen $kamus_mapping_permen)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'permen_1' => 'required',
            'kode_1' => 'required',
            'permen_2' => 'required',
            'kode_2' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_mapping_permen->update($input);

        return $this->sendResponse($kamus_mapping_permen->toArray(), 'mapping_permen updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_mapping_permen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_mapping_permen $kamus_mapping_permen)
    {
        $kamus_mapping_permen->delete();

        return $this->sendResponse($kamus_mapping_permen->toArray(), 'mapping_permen deleted successfully.');
    }
}