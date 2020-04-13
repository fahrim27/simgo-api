<?php

namespace App\Http\Controllers\API\Laporan\Penambahan;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Jurnal\Kib;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use App\Exports\laporanPenerimaanExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class Laporan_penambahanController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function laporanPenerimaan($kode_jurnal, $nomor_lokasi, $kode_kepemilikan)
    {
        $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','jumlah_barang','harga_total_plus_pajak_saldo','baik','kb','rb','keterangan')
            ->where('kode_jurnal', $kode_jurnal)
            ->where('nomor_lokasi', $nomor_lokasi)
            ->where('kode_kepemilikan', $kode_kepemilikan)
            ->get();

        return $this->sendResponse($data->toArray(), 'Data laporan retrieved successfully.');
    }

    public function exportLaporanPenerimaan($kode_jurnal, $nomor_lokasi, $kode_kepemilikan)
    {
        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data = array();

        $data["kode_jurnal"] = $kode_jurnal;
        $data["nomor_lokasi"] = $nomor_lokasi;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        if($kode_jurnal == "101") {
            $nama_jurnal = "Pengadaan ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "102") {
            $nama_jurnal = "Transfer masuk ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "103") {
            $nama_jurnal = "Hibah ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "104") {
            $nama_jurnal = "Tukar guling ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "105") {
            $nama_jurnal = "Sitaan ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "106") {
            $nama_jurnal = "BOT ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "107") {
            $nama_jurnal = "Inventarisasi ";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "108") {
            $nama_jurnal = "Pinjam Pakai";
            $nama_file = $nama_jurnal .$nama_lokasi;
        } else if($kode_jurnal == "109") {
            $nama_jurnal = "Penggantian Kehilangan";
            $nama_file = $nama_jurnal .$nama_lokasi;
        }
        $tahun_sekarang = date('Y')-1;
        $data["nama_jurnal"] = $nama_jurnal;

        return Excel::download(new LaporanPenerimaanExport($data), $nama_file." ".$tahun_sekarang.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        //return Excel::download(new LaporanPenerimaanExport($data), $nama_file.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
