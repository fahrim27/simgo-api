<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Penunjang;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Kamus\Kamus_spk;
use App\Models\Kamus\Kamus_spm;
use App\Http\Resources\Jurnal\PenunjangCollection;
use App\Http\Resources\Jurnal\KibCollection;
use App\Http\Resources\Jurnal\Rincian_masukCollection;
use App\Http\Resources\Kamus\Kamus_spkCollection;
use Validator;

class PenunjangController extends BaseController
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
            $rehabs = new PenunjangCollection(Penunjang::all());
        } else {
            $rehabs = new PenunjangCollection(Penunjang::paginate(10));
        }

        return $rehabs;
    }

    public function getByLokasi(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        if($pagination === 0) {
            $asets = new PenunjangCollection(Penunjang::where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')->get());
        } else {
            $asets = new PenunjangCollection(Penunjang::where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')->paginate(10));
        }

        return $this->sendResponse($asets, 'Aset retrieved successfully.');
    }

    public function getSpkByLokasi($nomor_lokasi)
    {
        $jurnalled = Jurnal::select('no_spk_sp_dokumen')
                        ->where('kode_jurnal', '101')
                        ->where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')
                        ->get()->toArray();

        $indexjurnal = 0;
        $jurnalled_spks = array();

        foreach ($jurnalled as $value) {
            $jurnalled_spks[$indexjurnal] = $value["no_spk_sp_dokumen"];
            $indexjurnal++;
        }

        $kamus_spks = new Kamus_spkCollection(Kamus_spk::join('kamus_spms', 'kamus_spks.no_spk_sp_dokumen', '=', 'kamus_spms.no_spk_sp_dokumen')
                    ->select('kamus_spks.no_spk_sp_dokumen', 'kamus_spks.tgl_spk_sp_dokumen', 'kamus_spms.no_spm_spmu as no_spm_penunjang', 'kamus_spms.tgl_spm_spmu as tgl_spm_penunjang', 'kamus_spms.nilai_spm_spmu as nilai_penunjang', 'kamus_spks.nomor_sub_unit')->distinct()
                    ->where('kamus_spks.nomor_sub_unit', 'like', '%'. $nomor_lokasi .'%')
                    ->whereIn('kamus_spks.no_spk_sp_dokumen', Kamus_spm::select('no_spk_sp_dokumen')
                        ->where('nomor_sub_unit', 'like', '%'. $nomor_lokasi .'%')
                        ->get()->toArray())
                    ->whereNotIn('kamus_spks.no_spk_sp_dokumen', Penunjang::select('no_spk_penunjang as no_spk_sp_dokumen')
                        ->where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')
                        ->get()->toArray())
                    ->whereNotIn('kamus_spks.no_spk_sp_dokumen', $jurnalled_spks)
                    ->orderBy('tgl_spk_sp_dokumen', 'asc')->orderBy('no_spk_sp_dokumen', 'asc')->get());

        return $this->sendResponse($kamus_spks, 'Spk retrieved successfully.');
    }

    public function getSpmBySpk(Request $request, $no_spk)
    {
        $no_spk = base64_decode($no_spk);
        $pagination = (int)$request->header('Pagination');
        
        if($pagination == 0) {
            $penunjangs = new PenunjangCollection(Penunjang::select('no_spk_penunjang', 'no_spm_penunjang', 'tgl_spm_penunjang', 'nilai_penunjang', 'uraian')->where('no_spk_sp_dokumen', $no_spk)->get());
        } else {
            $penunjangs = new PenunjangCollection(Penunjang::select('no_spk_penunjang', 'no_spm_penunjang', 'tgl_spm_penunjang', 'nilai_penunjang', 'uraian')->where('no_spk_sp_dokumen', $no_spk)->paginate(99));
        }

        return $this->sendResponse($penunjangs, 'List penunjang retrieved successfully.');
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
        $penunjang = array();

        $no_spk_sp_dokumen = $input["no_spk_sp_dokumen"];
        $no_spk_penunjang = $input["no_spk_penunjang"];

        $data_penunjang = Kamus_spm::select('no_spm_spmu as no_spm_penunjang', 'tgl_spm_spmu as tgl_spm_penunjang', 'nilai_spm_spmu as nilai_penunjang')
            ->where('no_spk_sp_dokumen', $no_spk_penunjang)
            ->get()->toArray();

        $i = 0;
        $penunjang = array();

        if(count($data_penunjang) == 1) {
            $data_penunjang = Kamus_spm::select('no_spm_spmu as no_spm_penunjang', 'tgl_spm_spmu as tgl_spm_penunjang', 'nilai_spm_spmu as nilai_penunjang')
            ->where('no_spk_sp_dokumen', $no_spk_penunjang)->first();

            $data_jurnal = Jurnal::where('no_spk_sp_dokumen', $no_spk_sp_dokumen)->select('no_key', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nomor_lokasi', 'tahun_spj', 'nilai_spk', 'uraian_spk')->first();

            $input["no_spk_sp_dokumen"] = $no_spk_sp_dokumen;
            $input["no_spk_penunjang"] = $no_spk_penunjang;
            $input["no_key"] = $data_jurnal->no_key;
            $input["tgl_spk_sp_dokumen"] = $data_jurnal->tgl_spk_sp_dokumen;
            $input["nomor_lokasi"] = $data_jurnal->nomor_lokasi;
            $input["tahun_spj"] = $data_jurnal->tahun_spj;
            $input["nilai_penunjang"] = $data_penunjang->nilai_penunjang;
            $input["no_spm_penunjang"] = $data_penunjang->no_spm_penunjang;
            $input["tgl_spm_penunjang"] = $data_penunjang->tgl_spm_penunjang;

            if(!array_key_exists("uraian", $input)) {
                $uraian = $data_jurnal->uraian_spk;
                $input["uraian"] = "Biaya penunjang untuk " . $uraian;
            } else if ($input["uraian"] == "" || $input["uraian"] == "-" || $input["uraian"] == NULL) {
                $uraian = $data_jurnal->uraian_spk;
                $input["uraian"] = "Biaya penunjang untuk " . $uraian;
            }

            $data["nilai_spk"] = $data_jurnal->nilai_spk + $data_penunjang->nilai_penunjang;

            $validator = Validator::make($input, [
                'no_key' => 'required',
                'no_spk_penunjang' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $jurnal = Jurnal::where('no_key', $data_jurnal->no_key)->update($data);
            $penunjang = Penunjang::create($input);

            return $this->sendResponse($penunjang->toArray(), 'Aset penunjang saved successfully.');
        } else {
            $data_jurnal = Jurnal::where('no_spk_sp_dokumen', $no_spk_sp_dokumen)->select('no_key', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nomor_lokasi', 'tahun_spj', 'nilai_spk', 'uraian_spk')->first();

            $nilai_penunjang = 0 ;

            foreach ($data_penunjang as $key => $value) {
                $nilai_penunjang += $value["nilai_penunjang"];
                $no_spm_penunjang = $value["no_spm_penunjang"];
                $tgl_spm_penunjang = $value["tgl_spm_penunjang"];
            }

            $input["no_spk_sp_dokumen"] = $no_spk_sp_dokumen;
            $input["no_spk_penunjang"] = $no_spk_penunjang;
            $input["no_key"] = $data_jurnal->no_key;
            $input["tgl_spk_sp_dokumen"] = $data_jurnal->tgl_spk_sp_dokumen;
            $input["nomor_lokasi"] = $data_jurnal->nomor_lokasi;
            $input["tahun_spj"] = $data_jurnal->tahun_spj;
            $input["nilai_penunjang"] = $nilai_penunjang;
            $input["no_spm_penunjang"] = $no_spm_penunjang;
            $input["tgl_spm_penunjang"] = $tgl_spm_penunjang;

            if(!array_key_exists("uraian", $input)) {
                $uraian = $data_jurnal->uraian_spk;
                $input["uraian"] = "Biaya penunjang untuk " . $uraian;
            } else if ($input["uraian"] == "" || $input["uraian"] == "-" || $input["uraian"] == NULL) {
                $uraian = $data_jurnal->uraian_spk;
                $input["uraian"] = "Biaya penunjang untuk " . $uraian;
            }

            $data["nilai_spk"] = $data_jurnal->nilai_spk + $nilai_penunjang;

            $validator = Validator::make($input, [
                'no_key' => 'required',
                'no_spk_penunjang' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $jurnal = Jurnal::where('no_key', $data_jurnal->no_key)->update($data);
            $penunjang = Penunjang::create($input);

            return $this->sendResponse($input, 'Aset penunjang saved successfully.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function deleteBySpk($no_spk_penunjang)
    {
        $no_spk_penunjang = base64_decode($no_spk_penunjang);

        $data_penunjang = Penunjang::where('no_spk_penunjang', $no_spk_penunjang)->first();

        $data_jurnal = Jurnal::where('no_key', $data_penunjang->no_key)->select('no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nomor_lokasi', 'tahun_spj', 'nilai_spk')->first();

        $data["nilai_spk"] = $data_jurnal->nilai_spk - $data_penunjang->nilai_penunjang;

        $jurnal = Jurnal::where('no_key', $data_penunjang->no_key)->update($data);
        $deleted = Penunjang::find($no_spk_penunjang);
        $deleted->delete();

        return $this->sendResponse($deleted->toArray(), 'Data penunjang deleted successfully.');
    }
}