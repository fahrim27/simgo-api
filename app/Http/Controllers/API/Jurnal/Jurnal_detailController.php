<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Jurnal_detail;
use App\Http\Resources\Jurnal\JurnalCollection;
use Validator;

class Jurnal_detailController extends BaseController
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
            $jurnal_detail_details = new JurnalCollection(Jurnal_detail::all());
        } else {
            $jurnal_detail_details = new JurnalCollection(Jurnal_detail::paginate($request->get('per_page')));
        }

        return $jurnal_detail_details;
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
            'no_key' => 'required',
            'id_jurnal_detail' => 'required',
            'id_aset' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $jurnal_detail = Jurnal_detail::create($input);

        return $this->sendResponse($jurnal_detail->toArray(), 'Jurnal detail created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id_jurnal_detail)
    {
        $jurnal_detail = Jurnal_detail::find($id_jurnal_detail);

        if (is_null($jurnal_detail)) {
            return $this->sendError('Jurnal detail not found.');
        }

        return $this->sendResponse($jurnal_detail->toArray(), 'Jurnal detail retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnal_detail $jurnal_detail)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_jurnal_detail' => 'required',
            'id_aset' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $jurnal_detail->update($input);

        return $this->sendResponse($jurnal_detail->toArray(), 'Jurnal detail updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurnal_detail $jurnal_detail)
    {
        $jurnal_detail->delete();

        return $this->sendResponse($jurnal_detail->toArray(), 'Jurnal detail deleted successfully.');
    }
}