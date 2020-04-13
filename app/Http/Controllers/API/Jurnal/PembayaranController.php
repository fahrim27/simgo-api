<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Pembayaran;
use App\Http\Resources\Jurnal\PembayaranCollection;
use Validator;

class PembayaranController extends BaseController
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
            $pembayarans = new PembayaranCollection(Pembayaran::all());
        } else {
            $pembayarans = new PembayaranCollection(Pembayaran::paginate($request->get('per_page')));
        }

        return $pembayarans;
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
            'kode_rek_belanja' => 'required',
            'no_spm_spmu' => 'required',
            'tahun_spj' => 'required',
            'no_sp2d' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $pembayaran = Pembayaran::create($input);

        return $this->sendResponse($pembayaran->toArray(), 'Pembayaran created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (is_null($pembayaran)) {
            return $this->sendError('Pembayaran not found.');
        }

        return $this->sendResponse($pembayaran->toArray(), 'Pembayaran retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnal $pembayaran)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'no_sub_unit' => 'required',
            'kode_rek_belanja' => 'required',
            'no_spm_spmu' => 'required',
            'tahun_spj' => 'required',
            'no_sp2d' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $pembayaran->update($input);

        return $this->sendResponse($pembayaran->toArray(), 'Pembayaran updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return $this->sendResponse($pembayaran->toArray(), 'Pembayaran deleted successfully.');
    }
}