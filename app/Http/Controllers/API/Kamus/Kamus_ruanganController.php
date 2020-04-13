<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_ruangan;
use App\Http\Resources\Kamus\Kamus_ruanganCollection;
use Validator;

class Kamus_ruanganController extends BaseController
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
            $kamus_ruangans = new Kamus_ruanganCollection(Kamus_ruangan::all());
        } else {
            $kamus_ruangans = new Kamus_ruanganCollection(Kamus_ruangan::paginate($request->get('per_page')));
        }

        return $kamus_ruangans;
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
            'nomor_ruangan' => 'required',
            'nama_ruangan' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_ruangan = Kamus_ruangan::create($input);

        return $this->sendResponse($kamus_ruangan->toArray(), 'Kamus Ruangan created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_ruangan
     * @return \Illuminate\Http\Response
     */
    public function show($nomor_ruangan)
    {
        $kamus_ruangan = Kamus_ruangan::find($nomor_ruangan);

        if (is_null($kamus_ruangan)) {
            return $this->sendError('Ruangan not found.');
        }

        return $this->sendResponse($kamus_ruangan->toArray(), 'Ruangan retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_ruangan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_ruangan $kamus_ruangan)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nomor_ruangan' => 'required',
            'nama_ruangan' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_ruangan->update($input);

        return $this->sendResponse($kamus_ruangan->toArray(), 'Ruangan updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_ruangan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_ruangan $kamus_ruangan)
    {
        $kamus_ruangan->delete();

        return $this->sendResponse($kamus_ruangan->toArray(), 'Ruangan deleted successfully.');
    }
}