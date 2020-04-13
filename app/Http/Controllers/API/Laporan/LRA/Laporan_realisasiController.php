<?php

namespace App\Http\Controllers\API\Laporan\LRA;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Jurnal\Kib;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use App\Exports\RealisasiLRAExport;
use App\Exports\RealisasiLRALokasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class Laporan_realisasiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function realisasiLRA()
    {
        ini_set('memory_limit', '-1');
        $data = array();

        $tahun_sekarang = date('Y')-1;
        $data["nama_jurnal"] = "Rekap Realisasi LRA";

        $nama_file = "Rekap Realisasi LRA ".$tahun_sekarang;
        $format_file = ".xlsx";

        return Excel::download(new RealisasiLRAExport($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function realisasiLRAOPD($nomor_lokasi)
    {
        ini_set('memory_limit', '-1');

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data = array();

        $tahun_sekarang = date('Y')-1;

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $nama_file = "Rekap Realisasi LRA " . $nama_lokasi;
        $data["nama_jurnal"] = $nama_file;
        $format_file = " ".$tahun_sekarang.".xlsx";

        return Excel::download(new RealisasiLRALokasiExport($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }
}
