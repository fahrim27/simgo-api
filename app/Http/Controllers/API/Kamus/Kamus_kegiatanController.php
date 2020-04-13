<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_kegiatan;
use App\Http\Resources\Kamus\Kamus_kegiatanCollection;
use Validator;

class Kamus_kegiatanController extends BaseController
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
            $kamus_kegiatans = new Kamus_kegiatanCollection(Kamus_kegiatan::all());
        } else {
            $kamus_kegiatans = new Kamus_kegiatanCollection(Kamus_kegiatan::paginate($request->get('per_page')));
        }

        return $kamus_kegiatans;
        // $kamus_kegiatans = kegiatan::all();
        // return $this->sendResponse($kamus_kegiatans->toArray(), 'Kamus_kegiatans retrieved successfully.');
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
            'id_kegiatan' => 'required',
            'kode_kegiatan' => 'required',
            'nama_kegiatan' => 'required',
            'nomor_sub_unit' => 'required',
            'tahun' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_kegiatan = Kamus_kegiatan::create($input);

        return $this->sendResponse($kamus_kegiatan->toArray(), 'Kamus kegiatan created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_kegiatan
     * @return \Illuminate\Http\Response
     */
    public function show($id_kegiatan)
    {
        $kamus_kegiatan = Kamus_kegiatan::find($id_kegiatan);

        if (is_null($kamus_kegiatan)) {
            return $this->sendError('kegiatan not found.');
        }

        return $this->sendResponse($kamus_kegiatan->toArray(), 'Kegiatan retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_kegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_kegiatan $kamus_kegiatan)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id_kegiatan' => 'required',
            'kode_kegiatan' => 'required',
            'nama_kegiatan' => 'required',
            'nomor_sub_unit' => 'required',
            'tahun' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_kegiatan->update($input);

        return $this->sendResponse($kamus_kegiatan->toArray(), 'kegiatan updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_kegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_kegiatan $kamus_kegiatan)
    {
        $kamus_kegiatan->delete();

        return $this->sendResponse($kamus_kegiatan->toArray(), 'kegiatan deleted successfully.');
    }
}