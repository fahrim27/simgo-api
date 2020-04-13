<?php

namespace App\Http\Controllers\API\Kamus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_spk;
use App\Models\Kamus\Kamus_spm;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Penunjang;
use App\Models\Jurnal\Rincian_masuk;
use App\Http\Resources\Kamus\Kamus_spkCollection;
use App\Http\Resources\Kamus\Kamus_spmCollection;
use App\Http\Resources\Jurnal\JurnalCollection;
use App\Http\Resources\Jurnal\Rincian_masukCollection;
use Validator;

class Kamus_spkController extends BaseController
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
            $kamus_spks = new Kamus_spkCollection(Kamus_spk::orderBy('tgl_spk_sp_dokumen', 'desc')->orderBy('no_spk_sp_dokumen', 'asc')->get());
        } else {
            $kamus_spks = new Kamus_spkCollection(Kamus_spk::orderBy('tgl_spk_sp_dokumen', 'desc')->orderBy('no_spk_sp_dokumen', 'asc')->paginate($request->get('per_page')));
        }

        return $kamus_spks;
    }

    public function getByLokasi($nomor_sub_unit)
    {
        $kamus_spks = new Kamus_spkCollection(Kamus_spk::where('nomor_sub_unit', 'like', '%'. $nomor_sub_unit .'%')->orderBy('tgl_spk_sp_dokumen', 'asc')->orderBy('no_spk_sp_dokumen', 'asc')->get());
        return $kamus_spks;
    }

    public function getNotJurnalledSpkByLokasi($nomor_sub_unit)
    {
        $kamus_spks = new Kamus_spkCollection(Kamus_spk::where('nomor_sub_unit', 'like', '%'. $nomor_sub_unit .'%')
                                                        ->whereNotIn('no_spk_sp_dokumen', Jurnal::select('no_spk_sp_dokumen')
                                                            ->where('nomor_lokasi', 'like', '%'. $nomor_sub_unit .'%')
                                                            ->where('kode_jurnal', '101')
                                                            ->get()->toArray())
                                                        ->whereNotIn('no_spk_sp_dokumen', Penunjang::select('no_spk_penunjang')
                                                            ->where('nomor_lokasi', 'like', '%'. $nomor_sub_unit .'%')
                                                            ->get()->toArray())
                                                        ->whereIn('no_spk_sp_dokumen', Kamus_spm::select('no_spk_sp_dokumen')
                                                            ->where('nomor_sub_unit', 'like', '%'. $nomor_sub_unit .'%')
                                                            ->get()->toArray())
                                                        ->orderBy('tgl_spk_sp_dokumen', 'asc')->orderBy('no_spk_sp_dokumen', 'asc')->get());

        return $kamus_spks;
    }

    public function getJurnalledSpkByLokasi($nomor_sub_unit)
    {
        $tahun = date('Y');

        $kamus_spks = new Kamus_spkCollection(Jurnal::select('no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nilai_spk', 'uraian_spk as deskripsi_spk_dokumen', 'tahun_spj', 'instansi_yg_menyerahkan as rekanan', 'alamat as alamat_rekanan')
            ->where('nomor_lokasi', 'like', '%'. $nomor_sub_unit .'%')
            ->where('kode_jurnal', '101')
            ->orderBy('tgl_spk_sp_dokumen', 'asc')
            ->orderBy('no_spk_sp_dokumen', 'asc')->get());

        foreach ($kamus_spks as $spk) {
            $nomor_spk = $spk->no_spk_sp_dokumen;
            $pembayaran = Kamus_spm::selectRaw('SUM(nilai_spm_spmu) as total_pembayaran')
                                    ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $nomor_spk)->get()->toArray();

            $realisasi = Rincian_masuk::selectRaw('SUM(harga_total_plus_pajak) as total_realisasi')
                                        ->groupBy('no_bukti_perolehan')->where('no_bukti_perolehan', '=', $nomor_spk)->get()->toArray();

            $penunjang = Penunjang::selectRaw('SUM(nilai_penunjang) as total_penunjang')
                                        ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', $nomor_spk)->get()->toArray();

            if(empty($penunjang)) {
                $nilai_penunjang = 0;
            } else {
                $nilai_penunjang = doubleval($penunjang[0]["total_penunjang"]);
            }

            if(empty($pembayaran)) {
                $total_pembayaran = 0;
            } else {
                $total_pembayaran = doubleval($pembayaran[0]["total_pembayaran"]);
            }

            if(empty($realisasi)) {
                $total_realisasi = 0;
            } else {
                $total_realisasi = doubleval($realisasi[0]["total_realisasi"]);
            }

            $total_pembayaran = $total_pembayaran + $nilai_penunjang;
            $total_realisasi = $total_realisasi;

            $spk->nilai_spk = $total_pembayaran;
            $spk->nilai_realisasi = $total_realisasi;
        }
        
        return $kamus_spks;
    }

    public function getListBy($rincian)
    {
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
            'id_kontrak' => 'required',
            'nilai_spk' => 'required',
            'nilai_add' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_spk = Kamus_spk::create($input);

        return $this->sendResponse($kamus_spk->toArray(), 'Kamus spk created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor_spk
     * @return \Illuminate\Http\Response
     */
    public function show($spk)
    {
        $id_kontrak = base64_decode($spk);
        $kamus_spk = Kamus_spk::find($id_kontrak);

        if (is_null($kamus_spk)) {
            return $this->sendError('spk not found.');
        }

        return $this->sendResponse($kamus_spk->toArray(), 'Spk retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor_spk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kamus_spk $kamus_spk)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id_kontrak' => 'required',
            'nilai_spk' => 'required',
            'nilai_add' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $kamus_spk->update($input);

        return $this->sendResponse($kamus_spk->toArray(), 'spk updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor_spk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kamus_spk $kamus_spk)
    {
        $kamus_spk->delete();

        return $this->sendResponse($kamus_spk->toArray(), 'spk deleted successfully.');
    }
}