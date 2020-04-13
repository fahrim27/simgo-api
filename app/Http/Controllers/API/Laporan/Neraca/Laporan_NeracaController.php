<?php

namespace App\Http\Controllers\API\Laporan\Neraca;
use Validator;
use Illuminate\Http\Request;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Exports\LaporanNeracaExport;
use App\Models\Kamus\Kamus_sub_unit;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Sub_rincian_108;

class Laporan_NeracaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function exportLaporanNeraca($bidang_barang, $kode_kepemilikan, $kode_108)
    {
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_108"] = $kode_108;

        $new_kode_108 = substr($kode_108,0,11);

        $uraian_sub_rincian = Sub_rincian_108::select('uraian_sub_rincian')->where('sub_rincian','LIKE', '%'.$new_kode_108.'%')->first()->toArray();

        // echo $uraian_sub_rincian;

        $data["uraian_sub_rincian"] = $uraian_sub_rincian['uraian_sub_rincian'];
        $data["nama_jurnal"] = "Daftar Aset Tetap";

        $nama_file = "Lampiran Neraca - KIB " . $bidang_barang;
        $format_file = ".xlsx";

        return Excel::download(new LaporanNeracaExport($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

}
