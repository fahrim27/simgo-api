<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Sub_sub_rincian_108;
use App\Http\Resources\Kamus\Sub_sub_rincian_108Collection;
use Validator;

class Sub_sub_rincian_108Controller extends BaseController
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
            $sub_sub_rincian_108s = new Sub_sub_rincian_108Collection(Sub_sub_rincian_108::all());
        } else {
            $sub_sub_rincian_108s = new Sub_sub_rincian_108Collection(Sub_sub_rincian_108::paginate($request->get('per_page')));
        }

        return $sub_sub_rincian_108s;
        // $sub_sub_rincian_108s = lokasiinsi::all();
        // return $this->sendResponse($sub_sub_rincian_108s->toArray(), 'Kamus_Sub sub rincian_108s retrieved successfully.');
    }

    public function getListBy($sub_rincian)
    {
        $sub_sub_rincians = new Sub_sub_rincian_108Collection(Sub_sub_rincian_108::where('sub_sub_rincian', 'like', '%'. $sub_rincian .'%')->get());
        return $sub_sub_rincians;
    }

    public function getListByBidang($bidang)
    {
        if($bidang == "A") {
            $sub_rincian = "1.3.1";
        } else if($bidang == "B1") {
            $sub_rincian = "1.3.2.02";
        } else if($bidang == "B2") {
            $sub_rincian = "1.3.2";
        } else if($bidang == "C") {
            $sub_rincian = "1.3.3";
        } else if($bidang == "D") {
            $sub_rincian = "1.3.4";
        } else if($bidang == "E1") {
            $sub_rincian = "1.3.5.01";
        } else if($bidang == "E2") {
            $sub_rincian = "1.3.5.02";
        } else if($bidang == "E3") {
            $sub_rincian = "1.3.5";
        } else if($bidang == "F") {
            $sub_rincian = "1.3.6";
        } else if($bidang == "G") {
            $sub_rincian = "1.5.3";
        } else if($bidang == "R") {
            $sub_rincian = "1.5.4";
        } else if($bidang == "FC") {
            $sub_rincian = "1.3.6.01.01.01.003";
        } else if($bidang == "FD") {
            $sub_rincian = "1.3.6.01.01.01.004";
        }

        if($bidang == "B2") {
            $sub_sub_rincians = new Sub_sub_rincian_108Collection(Sub_sub_rincian_108::where('sub_sub_rincian', 'like', '%'. $sub_rincian .'%')->where('sub_sub_rincian', 'not like', '%'. "1.3.2.01" .'%')->where('sub_sub_rincian', 'not like', '%'. "1.3.2.02" .'%')->get());
        } if($bidang == "E3") {
            $sub_sub_rincians = new Sub_sub_rincian_108Collection(Sub_sub_rincian_108::where('sub_sub_rincian', 'like', '%'. $sub_rincian .'%')->where('sub_sub_rincian', 'not like', '%'. "1.3.5.01" .'%')->where('sub_sub_rincian', 'not like', '%'. "1.3.5.02" .'%')->get());
        } else {
            $sub_sub_rincians = new Sub_sub_rincian_108Collection(Sub_sub_rincian_108::where('sub_sub_rincian', 'like', '%'. $sub_rincian .'%')->get());
        }
        
        return $sub_sub_rincians;
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
            'sub_sub_rincian' => 'required',
            'uraian_sub_sub_rincian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $sub_sub_rincian_108 = Sub_sub_rincian_108::create($input);

        return $this->sendResponse($sub_sub_rincian_108->toArray(), 'Sub sub rincian 108 created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_Sub sub rincian 108
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $sub_sub_rincian_108 = Sub_sub_rincian_108::find($kode);

        if (is_null($sub_sub_rincian_108)) {
            return $this->sendError('Sub sub rincian 108 not found.');
        }

        return $this->sendResponse($sub_sub_rincian_108->toArray(), 'Sub sub rincian 108 retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_Sub sub rincian 108
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sub_sub_rincian_108 $sub_sub_rincian_108)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'sub_sub_rincian' => 'required',
            'uraian_sub_sub_rincian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $sub_sub_rincian_108->update($input);

        return $this->sendResponse($sub_sub_rincian_108->toArray(), 'Sub sub rincian 108 updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_Sub sub rincian 108
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sub_sub_rincian_108 $sub_sub_rincian_108)
    {
        $sub_sub_rincian_108->delete();

        return $this->sendResponse($sub_sub_rincian_108->toArray(), 'Sub sub rincian 108 deleted successfully.');
    }
}