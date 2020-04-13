<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_spm;
use App\Http\Resources\Kamus\Kamus_spmCollection;
use Validator;

class Kamus_spmController extends BaseController
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
            $kamus_spms = new Kamus_spmCollection(Kamus_spm::all());
        } else {
            $kamus_spms = new Kamus_spmCollection(Kamus_spm::paginate($request->get('per_page')));
        }

        return $kamus_spms;
    }

    public function getBySpk(Request $request, $spk)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $nomor_spk = base64_decode($spk);
        
        if($pagination === 0) {
            $kamus_spms = new Kamus_spmCollection(Kamus_spm::where('no_spk_sp_dokumen', '=', $nomor_spk)->get());
        } else {
            $kamus_spms = new Kamus_spmCollection(Kamus_spm::where('no_spk_sp_dokumen', '=', $nomor_spk)->paginate($request->get('per_page')));
        }

        return $kamus_spms;
    }

    public function getByIdKontrak(Request $request, $id_kontrak)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $id = base64_decode($id_kontrak);
        
        if($pagination === 0) {
            $kamus_spms = new Kamus_spmCollection(Kamus_spm::where('id_kontrak', $id)->get());
        } else {
            $kamus_spms = new Kamus_spmCollection(Kamus_spm::where('id_kontrak', $id)->paginate($request->get('per_page')));
        }

        return $kamus_spms;
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
            'id_spm' => 'required',
            'no_sub_unit' => 'required',
            'no_spk_sp_dokumen' => 'required',
            'kode_rek_belanja' => 'required',
            'no_spm_spmu' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_spm = Kamus_spm::create($input);

        return $this->sendResponse($kamus_spm->toArray(), 'Pembayaran created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kamus_spm = Kamus_spm::find($id);

        if (is_null($kamus_spm)) {
            return $this->sendError('Pembayaran not found.');
        }

        return $this->sendResponse($kamus_spm->toArray(), 'Pembayaran retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnal $kamus_spm)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id_spm' => 'required',
            'no_sub_unit' => 'required',
            'no_spk_sp_dokumen' => 'required',
            'kode_rek_belanja' => 'required',
            'no_spm_spmu' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_spm->update($input);

        return $this->sendResponse($kamus_spm->toArray(), 'Pembayaran updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pembayaran $kamus_spm)
    {
        $kamus_spm->delete();

        return $this->sendResponse($kamus_spm->toArray(), 'Pembayaran deleted successfully.');
    }
}