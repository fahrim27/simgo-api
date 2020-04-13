<?php

namespace App\Http\Controllers\API\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Transaksi\Transaksi;
use App\Http\Resources\Transaksi\TransaksiCollection;
use Validator;

class TransaksiController extends BaseController
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
            $transaksis = new TransaksiCollection(Transaksi::all());
        } else {
            $transaksis = new TransaksiCollection(Transaksi::paginate($request->get('per_page')));
        }

        return $transaksis;
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
            'terkunci' => 'required',
            'nomor_lokasi' => 'required',
            'no_ba_penerimaan' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $transaksi = Transaksi::create($input);

        return $this->sendResponse($transaksi->toArray(), 'Transaksi created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($no_key)
    {
        $transaksi = Transaksi::find($no_key);

        if (is_null($transaksi)) {
            return $this->sendError('Transaksi not found.');
        }

        return $this->sendResponse($transaksi->toArray(), 'Transaksi retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'terkunci' => 'required',
            'nomor_lokasi' => 'required',
            'no_ba_penerimaan' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $transaksi->update($input);

        return $this->sendResponse($transaksi->toArray(), 'Transaksi updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return $this->sendResponse($transaksi->toArray(), 'Transaksi deleted successfully.');
    }
}