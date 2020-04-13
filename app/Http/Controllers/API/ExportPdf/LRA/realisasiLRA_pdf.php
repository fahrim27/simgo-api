<?php

namespace App\Http\Controllers\API\ExportPdf\LRA;
use PDF;
use MPDF;
use Validator;
use App\Models\Jurnal\Kib;
use App\Models\Kamus\Kamus_spk;
use App\Models\Kamus\Kamus_spm;
use App\Models\Jurnal\Penunjang;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_sub_unit;
use App\Http\Controllers\API\BaseController as BaseController;

class realisasiLRA_pdf extends BaseController
{

    public function __construct()
    {
        $this->total_nilai = 0;
        $this->tahun_sekarang = date('Y')-1;
    }

    public function realisasiLRA()
    {
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "5000000");

        $data = Kib::select('kamus_lokasis.nomor_lokasi','kamus_lokasis.nama_lokasi','kibs.no_bukti_perolehan', 'kibs.harga_total_plus_pajak_saldo')
                ->join('kamus_lokasis', 'kibs.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                ->where('kibs.kode_jurnal', '=', '101')
                ->where('kibs.tahun_spj', '=', 2019)
                ->where('kibs.nomor_lokasi', 'not like', '12.01.35.16.111.00002%')
                ->orderBy('kibs.nomor_lokasi', 'asc')
                ->orderBy('kibs.no_bukti_perolehan', 'asc')
                ->get();

        $exported = array();
        $i = 0;

        foreach ($data as $value) {
            $found = false;

            $found = false;
            foreach($exported as $key => $s)
            {
                if ($s["no_spk"] == $value["no_bukti_perolehan"]) {
                    $found = true;
                    break;
                }
            }

            if($found) {
                continue;
            } else {
                $spk = Kamus_spk::select('nilai_spk')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])->first();
                $nilai_spk = $spk->nilai_spk;

                $total_penunjang = 0;
                $total_realisasi = 0;

                $penunjang = Penunjang::selectRaw('SUM(nilai_penunjang) as total_penunjang')
                                ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($penunjang)) {
                    $total_penunjang = doubleval($penunjang[0]["total_penunjang"]);
                }

                $nilai_spk_plus_penunjang = $nilai_spk + $total_penunjang;

                $realisasi = Kib::selectRaw('SUM(harga_total_plus_pajak) as total_realisasi')
                                ->groupBy('no_bukti_perolehan')->where('no_bukti_perolehan', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($realisasi)) {
                    $total_realisasi = doubleval($realisasi[0]["total_realisasi"]);
                } else {
                    $total_realisasi = 0;
                }

                $exported[$i++] = array(
                    "nomor_lokasi" => $value["nomor_lokasi"],
                    "nama_lokasi" => $value["nama_lokasi"],
                    "no_spk" => $value["no_bukti_perolehan"],
                    "nilai_spk_plus_penunjang" => $nilai_spk_plus_penunjang,
                    "total_realisasi" => $total_realisasi
                );
            }
        }

        $data = collect($exported);

        $data->toArray();

        $data["nama_jurnal"] = "Rekap Realisasi LRA";

        $nama_file = "Rekap Realisasi LRA ".$this->tahun_sekarang;
        $format_file = ".pdf";

        // $mpdf = new MPDF(['mode' => 'utf-8', 'format' => 'A4-L']);
        $pdf = MPDF::loadView('html.LRA.laporanRealisasi',['data'=>$data], [],['orientation'=>'P']);
        return $pdf->stream($nama_file.$format_file);
    }

    public function realisasiLRAOPD($nomor_lokasi)
    {
        $data = Kib::select('nomor_lokasi', 'no_bukti_perolehan', 'harga_total_plus_pajak_saldo')
                ->where('kibs.nomor_lokasi', 'like', $nomor_lokasi . '%')
                ->where('kibs.kode_jurnal', '=', '101')
                ->where('kibs.tahun_spj', '=', 2019)
                ->orderBy('kibs.no_bukti_perolehan', 'asc')
                ->get();

        $exported = array();
        $i = 0;

        $total_nilai_spk_penunjang = 0;
        $total_nilai_realisasi = 0;

        foreach ($data as $value) {
            $found = false;

            $found = false;
            foreach($exported as $key => $s)
            {
                if ($s["no_spk"] == $value["no_bukti_perolehan"]) {
                    $found = true;
                    break;
                }
            }

            if($found) {
                continue;
            } else {
                $spk = Kamus_spk::select('nilai_spk')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])->first();
                $nilai_spk = $spk->nilai_spk;

                $total_penunjang = 0;
                $total_realisasi = 0;
                $total_spm = 0;

                $spm = Kamus_spm::selectRaw('SUM(nilai_spm_spmu) as total_spm')
                                ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($spm)) {
                    $total_spm = doubleval($spm[0]["total_spm"]);
                }

                $penunjang = Penunjang::selectRaw('SUM(nilai_penunjang) as total_penunjang')
                                ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($penunjang)) {
                    $total_penunjang = doubleval($penunjang[0]["total_penunjang"]);

                    $daftar_penunjang = Penunjang::select('no_spk_penunjang', 'nilai_penunjang')
                                                ->where('no_spk_sp_dokumen', 'like', $value['no_bukti_perolehan'])
                                                ->get()
                                                ->toArray();
                }

                $nilai_spk_plus_penunjang = $total_spm + $total_penunjang;

                $realisasi = Kib::selectRaw('SUM(harga_total_plus_pajak) as total_realisasi')
                                ->groupBy('no_bukti_perolehan')->where('no_bukti_perolehan', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($realisasi)) {
                    $total_realisasi = doubleval($realisasi[0]["total_realisasi"]);
                } else {
                    $total_realisasi = 0;
                }

                $exported[$i++] = array(
                    "nomor_lokasi" => $value["nomor_lokasi"],
                    "nama_lokasi" => $value["nama_lokasi"],
                    "no_spk" => $value["no_bukti_perolehan"],
                    "nilai_spk_plus_penunjang" => $nilai_spk_plus_penunjang,
                    "total_realisasi" => $total_realisasi
                );

                if(!empty($penunjang)) {
                    foreach ($daftar_penunjang as $p) {

                        $exported[$i++] = array(
                            "nomor_lokasi" => null,
                            "nama_lokasi" => null,
                            "no_spk" => $p['no_spk_penunjang'],
                            "nilai_spk_plus_penunjang" => $p['nilai_penunjang'],
                            "total_realisasi" => null
                        );
                    }
                }
            }
        }

        $data = collect($exported);

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $nama_file = "Rekap Realisasi LRA " . $nama_lokasi . " ".$this->tahun_sekarang;
        $data["nama_jurnal"] = $nama_file;
        $format_file = ".pdf";

        // $mpdf = new MPDF(['mode' => 'utf-8', 'format' => 'A4-L']);
        $pdf = MPDF::loadView('html.LRA.laporanRealisasiOPD',['data'=>$data,'nomor_lokasi'=>$nomor_lokasi,'nama_lokasi'=>$nama_lokasi], [],['orientation'=>'P']);
        return $pdf->stream($nama_file.$format_file);
    }
}
