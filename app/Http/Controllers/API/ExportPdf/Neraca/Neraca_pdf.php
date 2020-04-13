<?php

namespace App\Http\Controllers\API\ExportPdf\Neraca;
use MPDF;
use Validator;
use App\Models\Jurnal\Kib;
use App\Models\Kamus\Sub_rincian_108;
use App\Http\Controllers\API\BaseController as BaseController;

class Neraca_pdf extends BaseController
{

    public $nomor_lokasi;
	public $kode_kepemilikan;
	public $nama_lokasi;
	public $bidang_barang;
    public $nama_jurnal;
    public $kode_108;

    public function __construct()
    {
        $this->tahun_sekarang = date('Y')-1;
    }

    public function exportLaporanNeraca($bidang_barang,$kode_kepemilikan,$kode_108)
    {
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        ini_set("pcre.backtrack_limit", "1000000000");

        if($bidang_barang == "A") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.status_tanah', 'kibs.tgl_sertifikat', 'kibs.no_sertifikat', 'kibs.luas_tanah', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "B") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.tipe', 'kibs.ukuran', 'kibs.cc', 'kibs.bahan', 'kibs.tahun_pengadaan', 'kibs.no_rangka_seri', 'kibs.no_mesin', 'kibs.nopol','kibs.no_bpkb','kibs.saldo_barang','kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "C") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.panjang', 'kibs.lebar', 'kibs.luas_bangunan', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "D") {
            $data = Kib::select('kibs.kode_64','kibs.kode_108','kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.panjang', 'kibs.lebar', 'kibs.luas_bangunan', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "E") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "F") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.panjang', 'kibs.lebar', 'kibs.luas_bangunan', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else if($bidang_barang == "G") {
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo','kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        } else{
            $data = Kib::select('kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.status_tanah', 'kibs.tgl_sertifikat', 'kibs.no_sertifikat', 'kibs.luas_tanah', 'kibs.konstruksi', 'kibs.tahun_pengadaan', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.keterangan')
                ->join('kamus_lokasis','kibs.nomor_lokasi','=','kamus_lokasis.nomor_lokasi')
                ->where('kibs.bidang_barang', 'like', '%'.$bidang_barang.'%')
                ->where('kibs.kode_108', 'like', $kode_108.'%')
                ->where('kibs.kode_kepemilikan', $kode_kepemilikan)
                ->where('kibs.saldo_barang', '>', 0)
                ->orderBy('kibs.nomor_lokasi', 'ASC')
                ->orderBy('kibs.tahun_pengadaan', 'ASC')
                ->get()
                ->toArray();
        }

        $asets = array();
        $total_harga = 0;
        foreach ($data as $value) {
            $aset = $value;
            $no_register = (string)$value['no_register'] . ' ';

            if($bidang_barang == "B"){
                $merk_tipe = (string)$value['merk_alamat'].'<br>'.PHP_EOL.''.$value['tipe'];
                $aset['merk_alamat'] = $merk_tipe;

                $ukuran_cc = (string)$value['ukuran'].'<br>'.PHP_EOL.''.$value['cc'];
                $aset['ukuran'] = $ukuran_cc;

                $no_seri_mesin = (string)$value['no_rangka_seri'].'<br>'.PHP_EOL.''.$value['no_mesin'];
                $aset['no_rangka_seri'] = $no_seri_mesin;

                $no_pol_bpkb = (string)$value['nopol'].'<br>'.PHP_EOL.''.$value['no_bpkb'];
                $aset['nopol'] = $no_pol_bpkb;
            } else if($bidang_barang == "C"){
                $panjang_lebar = (string)$value['panjang'].'<br>'.PHP_EOL.''.$value['lebar'];
                $aset['panjang'] = $panjang_lebar;
            } else if($bidang_barang == "D"){
                $new_no_reg = (string)$value['kode_64'].'<br>'.PHP_EOL.''.$value['kode_108'].'<br>'.PHP_EOL.''.$value['no_register'];
                $aset['kode_64'] = $new_no_reg;

                $panjang_lebar = (string)$value['panjang'].'<br>'.PHP_EOL.''.$value['lebar'];
                $aset['panjang'] = $panjang_lebar;
            } else if($bidang_barang == "F"){
                $panjang_lebar = (string)$value['panjang'].'<br>'.PHP_EOL.''.$value['lebar'];
                $aset['panjang'] = $panjang_lebar;
            }

            $total_harga_tmp = $value["harga_total_plus_pajak_saldo"];
            $total_harga+=$total_harga_tmp;
            $total_harga = $total_harga;

            array_push($asets, $aset);
        }

        $data = collect($asets);

        $data["kode_kepemilikan"] = $kode_kepemilikan;
        $data["bidang_barang"] = $bidang_barang;
        $data["kode_108"] = $kode_108;

        $new_kode_108 = substr($kode_108,0,11);

        $uraian_sub_rincian = Sub_rincian_108::select('uraian_sub_rincian')->where('sub_rincian','LIKE', '%'.$new_kode_108.'%')->first();

        // return $uraian_sub_rincian;

        $data["uraian_sub_rincian"] = $uraian_sub_rincian['uraian_sub_rincian'];
        $data["nama_jurnal"] = "Daftar Aset Tetap";

        $nama_file = "Lampiran Neraca " . $bidang_barang." ".$this->tahun_sekarang;
        $format_file = ".pdf";

        if($bidang_barang == "E" || $bidang_barang == "G"){
            $pdf = MPDF::loadView('html.neraca.laporanNeraca', ['data'=>$data, 'bidang_barang'=>$bidang_barang, 'kode_kepemilikan'=>$kode_kepemilikan,'kode_108'=>$kode_108,'new_kode_108'=>$new_kode_108,'uraian_sub_rincian'=>$uraian_sub_rincian],[],['orientation'=>'P']);
        } else {
            $pdf = MPDF::loadView('html.neraca.laporanNeraca', ['data'=>$data, 'bidang_barang'=>$bidang_barang, 'kode_kepemilikan'=>$kode_kepemilikan,'kode_108'=>$kode_108,'new_kode_108'=>$new_kode_108,'uraian_sub_rincian'=>$uraian_sub_rincian]);
        }
        return $pdf->stream($nama_file.$format_file);

        // return $data;
    }
}
