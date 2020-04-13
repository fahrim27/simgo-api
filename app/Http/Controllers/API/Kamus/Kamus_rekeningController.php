<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Rekening_priorita;
use App\Http\Resources\Kamus\Kamus_rekeningCollection;
use Validator;

class Kamus_rekeningController extends BaseController
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
            $kamus_rekenings = new Kamus_rekeningCollection(Kamus_rekening::all());
        } else {
            $kamus_rekenings = new Kamus_rekeningCollection(Kamus_rekening::paginate($request->get('per_page')));
        }

        return $kamus_rekenings;
        // $kamus_rekenings = Bidang unit::all();
        // return $this->sendResponse($kamus_rekenings->toArray(), 'Kamus_rekenings retrieved successfully.');
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
            'nomor_rekening' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_rekening = Kamus_rekening::create($input);

        return $this->sendResponse($kamus_rekening->toArray(), 'Kamus rekening created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function show($kode_rekening)
    {
        // $kode_rekening = base64_decode($spk);
        $kamus_rekening = Kamus_rekening::find($kode_rekening);

        if (is_null($kamus_rekening)) {
            return $this->sendError('Rekening not found.');
        }

        return $this->sendResponse($kamus_rekening->toArray(), 'Rekening retrieved successfully.');
    }

    
    public function getListByBidang($bidang)
    {
        
        $lists = new Kamus_rekeningCollection(Kamus_rekening::select('kode_64', 'uraian_64', 'kode_108', 'uraian_108')->where('bidang_barang', 'like', '%'. $bidang .'%')->get());
        
        return $this->sendResponse($lists, 'Kode rekening retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_rekening $kamus_rekening)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_rekening' => 'required',
            'uraian' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_rekening->update($input);

        return $this->sendResponse($kamus_rekening->toArray(), 'Rekening updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_sub_unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_rekening $kamus_rekening)
    {
        $kamus_rekening->delete();

        return $this->sendResponse($kamus_rekening->toArray(), 'Rekening deleted successfully.');
    }

    public function getByRekening($nomor_rekening)
    {
        $kamus_rekenings = new Kamus_rekeningCollection(Kamus_rekening::where('nomor_rekening', 'like', '%'. $nomor_rekening .'%')->get());
        return $kamus_rekenings;
    }

    public function getKode64BySub($sub_sub)
    {
        $rek_108 = substr($sub_sub, 0, 11);
        $prioritas = Rekening_priorita::select('kode_64')->where('kode_108', 'like', $rek_108 . '%')->first();

        if(is_null($prioritas)) {
            $kode_64 = new Kamus_rekeningCollection(Kamus_rekening::select('kode_64', 'uraian_64')->where('kode_108', $sub_sub)->get());

            if($kode_64->isEmpty()) {
                $sub_rekening = substr($sub_sub, 0, 14);
                $kode_64 = new Kamus_rekeningCollection(Kamus_rekening::select('kode_64', 'uraian_64')->where('kode_108', $sub_rekening)->get());

                if($kode_64->isEmpty()) {
                    $rekening = substr($sub_rekening, 0, 11);
                    $kode_64 = new Kamus_rekeningCollection(Kamus_rekening::select('kode_64', 'uraian_64')->where('kode_108', $rekening)->get());
                }
            }
        } else {
            $kode_64 = new Kamus_rekeningCollection(Rekening_priorita::select('kode_64', 'uraian_64')->where('kode_108', $sub_sub)->get());

            if($kode_64->isEmpty()) {
                $sub_rekening = substr($sub_sub, 0, 14);
                $kode_64 = new Kamus_rekeningCollection(Rekening_priorita::select('kode_64', 'uraian_64')->where('kode_108', $sub_rekening)->get());

                if($kode_64->isEmpty()) {
                    $rekening = substr($sub_rekening, 0, 11);
                    $kode_64 = new Kamus_rekeningCollection(Rekening_priorita::select('kode_64', 'uraian_64')->where('kode_108', $rekening)->get());
                }
            }
        }

        return $kode_64;
    }
}