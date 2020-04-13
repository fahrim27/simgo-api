<?php

namespace App\Http\Controllers\API\Laporan\Mutasi;
use Validator;
use Illuminate\Http\Request;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_sub_unit;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Jurnal\Rincian_keluar;
use App\Exports\LaporanMutasiMasuk64Export;
use App\Exports\LaporanMutasiKeluar64Export;
use App\Exports\LaporanMutasiMasuk108Export;
use App\Exports\LaporanMutasiKeluar108Export;
use App\Exports\LaporanMutasiMasukBarangExport;
use App\Exports\LaporanMutasiKeluarBarangExport;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use App\Http\Controllers\API\BaseController as BaseController;

class Laporan_MutasiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportMasuk108($nomor_lokasi)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data = array();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Masuk 108";

        $nama_file = "Laporan Mutasi Masuk 108 " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new LaporanMutasiMasuk108Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportKeluar108($nomor_lokasi)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data = array();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Keluar 108";

        $nama_file = "Laporan Mutasi Keluar 108 " . $nama_lokasi;
        $format_file = ".xlsx";

        return Excel::download(new LaporanMutasiKeluar108Export($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportMasuk64($nomor_lokasi)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data = array();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Masuk 64";

        $nama_file = "Laporan Mutasi Masuk 64 " . $nama_lokasi;
        $format_file = ".xlsx";

        return Excel::download(new LaporanMutasiMasuk64Export($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportKeluar64($nomor_lokasi)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data = array();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Laporan Mutasi Keluar 64";

        $nama_file = "Laporan Mutasi Keluar 64 " . $nama_lokasi;
        $format_file = ".xlsx";

        return Excel::download(new LaporanMutasiKeluar64Export($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    // export laporan barang
    public function exportMasukBarang($nomor_lokasi)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        }
        $data = array();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["kode_kepemilikan"] = $kode_kepemilikan;

        $data["nama_jurnal"] = "Laporan Mutasi Masuk Barang";

        $nama_file = "Laporan Mutasi Masuk Barang " . $nama_lokasi;
        $format_file = ".xlsx";

        return Excel::download(new LaporanMutasiMasukBarangExport($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);

        // return $data;
    }

    public function exportKeluarBarang($nomor_lokasi)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
            $kode_kepemilikan = Rincian_keluar::select('kode_kepemilikan')->where('nomor_lokasi',$nomor_lokasi)->first()->kode_kepemilikan;
        }
        $data = array();

        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["kode_kepemilikan"] = $kode_kepemilikan;

        $data["nama_jurnal"] = "Laporan Mutasi Barang Keluar";

        $nama_file = "Laporan Mutasi Barang Keluar " . $nama_lokasi;
        $format_file = ".xlsx";

        return Excel::download(new LaporanMutasiKeluarBarangExport($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);

        // return $data;
    }
}
