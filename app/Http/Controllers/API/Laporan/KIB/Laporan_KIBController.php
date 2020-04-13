<?php

namespace App\Http\Controllers\API\Laporan\KIB;
use Validator;
use App\Exports\KibExport;
use App\Models\Jurnal\Kib;
use Illuminate\Http\Request;
use App\Exports\KibAwalExport;
use App\Models\Jurnal\Kib_awal;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Exports\KibUrutTahunExport;
use App\Exports\LaporanInventaris;
use App\Models\Kamus\Kamus_sub_unit;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanRekap64Export;
use App\Exports\LaporanRekap108Export;
use App\Exports\LaporanRekapRincian108Export;
use App\Exports\LaporanRekapSubRincian108Export;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use App\Http\Controllers\API\BaseController as BaseController;

class Laporan_KIBController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function laporanKibUrutTahun($bidang_barang, $nomor_lokasi, $kode_kepemilikan)
    {
        $export = array();

        if($bidang_barang == "A") {
            $aset = Kib::select('kode_108', 'no_register', 'nama_barang', 'merk_alamat', 'luas_tanah', 'tahun_pengadaan', 'tgl_sertifikat', 'no_sertifikat', 'penggunaan', 'perolehan_pemda', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.3.1%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "B") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','ukuran','cc','bahan','tahun_pengadaan','baik','kb', 'rb', 'no_rangka_seri', 'no_mesin', 'nopol', 'no_bpkb', 'perolehan_pemda', 'jumlah_barang', 'satuan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.3.2%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "C") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.3.3%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "D") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','konstruksi','bahan','panjang','lebar','luas','no_imb', 'tgl_imb', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.3.4%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "E") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.3.5%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "F") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.3.6%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "G") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('bidang_barang', 'like', '%'.$this->bidang_barang.'%')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.5.3%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        } else if($bidang_barang == "R" || $bidang_barang == "RB") {
            $aset = Kib::select('kode_108','no_register','nama_barang','merk_alamat','konstruksi','bahan','jumlah_lantai','luas_lantai','no_imb', 'tgl_imb', 'luas_bangunan', 'status_tanah', 'tahun_pengadaan', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('kode_108', 'like', '1.5.4%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->orderBy('tahun_pengadaan', 'ASC')
                ->distinct()
                ->get();
        }

        $tahunsekarang = "1";
        $tahun = "";
        $dummytahun  = 2019;
        $nilai = 0;

        $aset = $aset->toArray();
        $dummy = array();

        foreach ($aset as $i) {
            foreach ($i as $value) {
                $tahun = $i["tahun_pengadaan"];
                $nilai = $i["harga_total_plus_pajak"];
                $dummy = $i;
            }

            if($tahunsekarang == $tahun) {
                $export[$tahunsekarang]["total"] += $nilai;
                $export[$tahunsekarang]["aset"][$index] = $dummy;
            } else {
                $tahunsekarang = $tahun;
                $index = 0;
                $export[$tahunsekarang]["total"] = $nilai;
                $export[$tahunsekarang]["aset"][$index] = $dummy;
            }

            $index++;
        }

        return $this->sendResponse($export, 'Laporan KIB retrieved successfully.');
    }

    public function exportLaporanKibUrutTahun($bidang_barang, $nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["bidang_barang"] = $bidang_barang;

        $data["nama_jurnal"] = "Laporan KIB";

        $nama_file = "Laporan KIB " . $bidang_barang . " " . $nama_lokasi ;
        $format_file = ".xlsx.xlsx";

        return Excel::download(new KibUrutTahunExport($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportLaporanRekapRincian108($nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Rekap Barang Per Rincian Permen 108";

        $nama_file = "Rekap Barang Per Rincian Permen 108 " . $nama_lokasi;
        $format_file = ".xlsx.xlsx";

        return Excel::download(new LaporanRekapRincian108Export($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportLaporanRekapSubRincian108($nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Rekap Barang Per Sub Rincian Permen 108";

        $nama_file = "Rekap Barang Per Sub Rincian Permen 108 " . $nama_lokasi;
        $format_file = ".xlsx.xlsx";

        return Excel::download(new LaporanRekapSubRincian108Export($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);

        //return (new LaporanRekap108Export($data))->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);s
        //return Excel::download(new LaporanPenerimaanExport($data), $nama_file.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportLaporanRekap108($nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Rekap Barang Per Sub Sub Rincian Permen 108";

        $nama_file = "Rekap Barang Per Sub Sub Rincian Permen 108" . $nama_lokasi;
        $format_file = ".xlsx.xlsx";

        return Excel::download(new LaporanRekap108Export($data), $nama_file. $format_file, \Maatwebsite\Excel\Excel::XLSX);

        //return (new LaporanRekap108Export($data))->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);s
        //return Excel::download(new LaporanPenerimaanExport($data), $nama_file.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportLaporanRekap64($nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Rekap Barang Per Permen 64";

        $nama_file = "Rekap KIB 64 " . $nama_lokasi;
        $format_file = ".xlsx.xlsx";

        return Excel::download(new LaporanRekap64Export($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);

        //return Excel::download(new LaporanPenerimaanExport($data), $nama_file.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    // class kib saldo awal
    public function exportLaporanKibAwal($bidang_barang, $nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;
        $data["bidang_barang"] = $bidang_barang;

        $data["nama_jurnal"] = "Saldo KIB";

        $nama_file = "Saldo Awal KIB " . $bidang_barang . " " . $nama_lokasi ;
        $format_file = ".xlsx";

        return Excel::download(new KibAwalExport($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }
    // end class kib saldo awal

    // kib awal saldo inventaris
    public function exportLaporanKibAwalInv($nomor_lokasi, $kode_kepemilikan)
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
        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["nama_lokasi"] = $nama_lokasi;

        $data["nama_jurnal"] = "Buku Inventaris";

        $nama_file = "Laporan Kib Awal Saldo Inventaris" . $nama_lokasi;
        $format_file = ".xlsx";

        return Excel::download(new LaporanInventaris($data), $nama_file . $format_file, \Maatwebsite\Excel\Excel::XLSX);
    }
}
