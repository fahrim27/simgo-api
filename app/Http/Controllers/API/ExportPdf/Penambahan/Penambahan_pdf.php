<?php

namespace App\Http\Controllers\API\ExportPdf\Penambahan;
use PDF;
use MPDF;
use Validator;
use App\Models\Jurnal\Kib;
use Illuminate\Http\Request;
use App\Models\Kamus\Kamus_unit;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Kamus\Kamus_sub_unit;
use App\Models\Jurnal\Rincian_keluar;
use App\Http\Controllers\API\BaseController as BaseController;

class Penambahan_pdf extends BaseController
{
    public $nomor_lokasi;
    public $kode_jurnal;
    public $kode_kepemilikan;

    public function __construct()
    {
        $this->total_nilai = 0;
        $this->tahun = date('Y') - 1;
    }

    public function exportLaporanPenerimaan($kode_jurnal,$nomor_lokasi,$kode_kepemilikan)
    {
        ini_set("pcre.backtrack_limit", "5000000");

        if($kode_jurnal == '101') {
            if($nomor_lokasi == '12.01.35.16.111.00002') {
                $data = Kib::join('kamus_lokasis', 'kibs.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('kibs.nomor_lokasi', 'kamus_lokasis.nama_lokasi', 'kibs.kode_108', 'kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.baik', 'kibs.kb', 'kibs.rb', 'kibs.keterangan')
                    ->where('kibs.kode_jurnal', $kode_jurnal)
                    ->where('kibs.nomor_lokasi', 'like', $nomor_lokasi . '%')
                    ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                    ->where('kibs.tahun_pengadaan', $this->tahun)
                    ->get()
                    ->toArray();
            } else if($nomor_lokasi == '12.01.35.16.111.00001' || $nomor_lokasi == '12.01.35.16.131.00001.00001') {
                    $data = Kib::leftJoin('kamus_spks', 'kibs.no_bukti_perolehan', '=', 'kamus_spks.no_spk_sp_dokumen')
                        ->select('kibs.no_bukti_perolehan', 'kamus_spks.deskripsi_spk_dokumen', 'kibs.kode_108', 'kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.baik', 'kibs.kb', 'kibs.rb', 'kibs.keterangan')
                        ->where('kibs.kode_jurnal', $kode_jurnal)
                        ->where('kibs.nomor_lokasi', 'like', $nomor_lokasi . '%')
                        ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                        ->where('kibs.tahun_pengadaan', $this->tahun)
                        ->orderBy('kibs.no_bukti_perolehan', 'asc')
                        ->get()
                        ->toArray();
            } else {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','saldo_barang','harga_total_plus_pajak_saldo','baik','kb','rb','keterangan')
                    ->where('kode_jurnal', $kode_jurnal)
                    ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                    ->where('kode_kepemilikan', $kode_kepemilikan)
                    ->where('tahun_pengadaan', $this->tahun)
                    ->get()
                    ->toArray();
            }

            $asets = array();
            $total_jumlah_barang= 0;
            $total_harga_total = 0;
            $total_baik = 0;
            $total_kurang_baik = 0;
            $total_rusak_berat = 0;
            foreach($data as $value) {
                $aset = $value;
                $mutasi = Rincian_keluar::where('id_aset', $value['no_register'])->first();

                if(!is_null($mutasi)) {
                    $harga_total = $mutasi->nilai_keluar;
                    $jumlah_keluar = $mutasi->jumlah_barang;

                    $aset['saldo_barang'] += $jumlah_keluar;
                    $aset['harga_total_plus_pajak_saldo'] += $harga_total;
                }

                array_push($asets, $aset);
            }

            $data = collect($asets);
        } else {
            if($kode_jurnal == '103' && $nomor_lokasi == '12.01.35.16.111.00001') {
                $data = Kib::join('kamus_lokasis', 'kibs.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('kibs.nomor_lokasi', 'kamus_lokasis.nama_lokasi', 'kibs.kode_108', 'kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.baik', 'kibs.kb', 'kibs.rb', 'kibs.keterangan')
                    ->where('kibs.kode_jurnal', $kode_jurnal)
                    ->where('kibs.nomor_lokasi', 'like', $nomor_lokasi . '%')
                    ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                    ->where('kibs.tahun_spj', $this->tahun)
                    ->get();
            } else {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','saldo_barang','harga_total_plus_pajak_saldo','baik','kb','rb','keterangan')
                ->where('kode_jurnal', $kode_jurnal)
                ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                ->where('kode_kepemilikan', $kode_kepemilikan)
                ->where('tahun_spj', $this->tahun)
                ->get();
            }

            $asets = array();
            foreach ($data as $value) {
                $aset = $value;

                array_push($asets, $aset);
            }

            $data = collect($asets);
        }

        if(strlen($nomor_lokasi) <= 16) {
            $nama_lokasi = Kamus_unit::select('nama_unit')->where('nomor_unit', $nomor_lokasi)->first()->nama_unit;
        } else if(strlen($nomor_lokasi) > 16 && strlen($nomor_lokasi) <= 21) {
            $nama_lokasi = Kamus_sub_unit::select('nama_sub_unit')->where('nomor_sub_unit', $nomor_lokasi)->first()->nama_sub_unit;
        } else {
            $nama_lokasi = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', $nomor_lokasi)->first()->nama_lokasi;
        }
        $data->toArray();

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

        $data["nama_jurnal"] = $nama_jurnal;
        $tahun = $this->tahun;

        $format_file = ".pdf";
        $pdf = MPDF::loadView('html.Penerimaan.laporanPenerimaan', ['data'=>$data, 'nama_lokasi'=>$nama_lokasi,'nama_jurnal'=>$nama_jurnal,'tahun'=>$tahun,'kode_jurnal'=>$kode_jurnal,'nomor_lokasi'=>$nomor_lokasi],[],['orientation'=>'P']);

        return $pdf->stream($nama_file." ".$this->tahun.$format_file);

        // return $data;
    }
}
