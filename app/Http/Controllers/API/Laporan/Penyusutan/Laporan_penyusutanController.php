<?php

namespace App\Http\Controllers\API\Laporan\Penyusutan;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Rehab;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Kamus\Kamus_masa_manfaat_tambahan;
use App\Http\Resources\Jurnal\Rincian_masukCollection;
use App\Http\Resources\Jurnal\KibCollection;
use App\Http\Resources\Jurnal\RehabCollection;

use App\Exports\LaporanPenyusutan108Export;
use App\Exports\LaporanPenyusutan64Export;
use App\Exports\LaporanPenyusutanAk108Export;
use App\Exports\PenyusutanReklasKeluar64Export;
use App\Exports\PenyusutanReklasKeluar108Export;
use App\Exports\PenyusutanReklasMasuk64Export;
use App\Exports\PenyusutanReklasMasuk108Export;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class Laporan_penyusutanController extends BaseController
{
    public function getPenyusutan(Request $request, $nomor_lokasi, $bidang_barang)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();

        if($pagination === 0) {
            $penyusutans = new Rincian_masukCollection(Rincian_masuk::select('id_aset', 'kode_sub_kel_at', 'kode_64', 'kode_108', 'harga_satuan', 'jumlah_barang', 'saldo_barang', 'harga_total_plus_pajak_saldo', 'ak_penyusutan', 'waktu_susut', 'tahun_buku', 'nilai_buku')->where('nomor_lokasi', '=', $nomor_lokasi)->where('bidang_barang', '=', $bidang_barang)->get());
        } else {
            $penyusutans = new Rincian_masukCollection(Rincian_masuk::select('id_aset', 'kode_sub_kel_at', 'kode_64', 'kode_108', 'harga_satuan', 'jumlah_barang', 'saldo_barang', 'harga_total_plus_pajak_saldo', 'ak_penyusutan', 'waktu_susut', 'tahun_buku', 'nilai_buku')->where('nomor_lokasi', '=', $nomor_lokasi)->where('bidang_barang', '=', $bidang_barang)->paginate($request->get('per_page')));
        }

        return $penyusutans;
    }

    public function exportSusut108($nomor_lokasi, $bidang_barang, $kode_kepemilikan, $jenis_aset)
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
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan 108 KIB";

        $nama_file = "Laporan Penyusutan 108 KIB " . $bidang_barang . " " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new LaporanPenyusutan108Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportSusut64($nomor_lokasi, $bidang_barang, $kode_kepemilikan, $jenis_aset)
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
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan 64 KIB";

        $nama_file = "Laporan Penyusutan 64 KIB " . $bidang_barang . " " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new LaporanPenyusutan64Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportSusutAk108($nomor_lokasi, $bidang_barang, $kode_kepemilikan, $jenis_aset)
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
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Akumulasi Penyusutan KIB";

        $nama_file = "Laporan Akumulasi Penyusutan 108 KIB " . $bidang_barang . " " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new LaporanPenyusutanAk108Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportSusutReklasKeluar64($nomor_lokasi, $jenis_aset)
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
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Keluar 64";

        $nama_file = "Laporan Penyusutan Aset Reklas Keluar 64 " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new PenyusutanReklasKeluar64Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportSusutReklasKeluar108($nomor_lokasi, $jenis_aset)
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
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Keluar 108";

        $nama_file = "Laporan Penyusutan Aset Reklas Keluar 108 " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new PenyusutanReklasKeluar108Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportSusutReklasMasuk64($nomor_lokasi, $jenis_aset)
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
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Masuk 64";

        $nama_file = "Laporan Penyusutan Aset Reklas Masuk 64 " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new PenyusutanReklasMasuk64Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportSusutReklasMasuk108($nomor_lokasi, $jenis_aset)
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
        $data["jenis_aset"] = $jenis_aset;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Penyusutan Aset Reklas Masuk 108";

        $nama_file = "Laporan Penyusutan Aset Reklas Masuk 108 " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new PenyusutanReklasMasuk108Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }
}
