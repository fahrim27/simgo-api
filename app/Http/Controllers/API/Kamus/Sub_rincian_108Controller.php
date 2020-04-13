<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Sub_rincian_108;
use App\Http\Resources\Kamus\Sub_rincian_108Collection;
use Validator;

class Sub_rincian_108Controller extends BaseController
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
            $sub_rincian_108s = new Sub_rincian_108Collection(Sub_rincian_108::all());
        } else {
            $sub_rincian_108s = new Sub_rincian_108Collection(Sub_rincian_108::paginate($request->get('per_page')));
        }

        return $sub_rincian_108s;
        // $sub_rincian_108s = lokasiinsi::all();
        // return $this->sendResponse($sub_rincian_108s->toArray(), 'Kamus_Sub sub rincian_108s retrieved successfully.');
    }

    public function getListBy($rincian)
    {  
        if(strlen($rincian) == 18) {
            $rincian = substr($rincian, 0, 14);
        }

        $sub_rincians = new Sub_rincian_108Collection(Sub_rincian_108::where('sub_rincian', 'like', '%'. $rincian .'%')->get());

        return $sub_rincians;
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
            'sub_rincian' => 'required',
            'uraian_sub_rincian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $sub_rincian_108 = Sub_rincian_108::create($input);

        return $this->sendResponse($sub_rincian_108->toArray(), 'Sub sub rincian 108 created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_Sub sub rincian 108
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $sub_rincian_108 = Sub_rincian_108::find($kode);

        if (is_null($sub_rincian_108)) {
            return $this->sendError('Sub sub rincian 108 not found.');
        }

        return $this->sendResponse($sub_rincian_108->toArray(), 'Sub sub rincian 108 retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_Sub sub rincian 108
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sub_rincian_108 $sub_rincian_108)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'sub_rincian' => 'required',
            'uraian_sub_rincian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $sub_rincian_108->update($input);

        return $this->sendResponse($sub_rincian_108->toArray(), 'Sub sub rincian 108 updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_Sub sub rincian 108
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sub_rincian_108 $sub_rincian_108)
    {
        $sub_rincian_108->delete();

        return $this->sendResponse($sub_rincian_108->toArray(), 'Sub sub rincian 108 deleted successfully.');
    }
}