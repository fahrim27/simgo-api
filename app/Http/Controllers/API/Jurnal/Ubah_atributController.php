<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Kib_awal;
use App\Http\Resources\Jurnal\KibCollection;
use Validator;

class Ubah_atributController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $nomor_lokasi, $bidang_barang)
    {
        $pagination = (int)$request->header('Pagination');
        $per_page = 10;

        if($bidang_barang == 'A') {
            $kode_108 = '1.3.1';
        } else if($bidang_barang == 'B') {
            $kode_108 = '1.3.2';
        } else if($bidang_barang == 'B1') {
            $kode_108 = '1.3.2';
        } else if($bidang_barang == 'B2') {
            $kode_108 = '1.3.2';
        } else if($bidang_barang == 'C') {
            $kode_108 = '1.3.3';
        } else if($bidang_barang == 'D') {
            $kode_108 = '1.3.4';
        } else if($bidang_barang == 'E') {
            $kode_108 = '1.3.5';
        } else if($bidang_barang == 'E1') {
            $kode_108 = '1.3.5';
        } else if($bidang_barang == 'E2') {
            $kode_108 = '1.3.5';
        } else if($bidang_barang == 'E3') {
            $kode_108 = '1.3.5';
        } else if($bidang_barang == 'G') {
            $kode_108 = '1.5.3';
        }
        
        if($pagination == 0) {
            $ubah_atributs = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'keterangan')
                                ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                ->where('kode_108', 'like', $kode_108 . '%')
                                ->where('saldo_barang', '>', 0)->get();
        } else {
            $ubah_atributs = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'keterangan')
                                ->where('nomor_lokasi', 'like', $nomor_lokasi . '%')
                                ->where('kode_108', 'like', $kode_108 . '%')
                                ->where('saldo_barang', '>', 0)->paginate($per_page);
        }

        if(!empty($ubah_atributs)) {
            $return = $ubah_atributs->toArray();
        } else {
            $return = $ubah_atributs;
        }
        
        return $this->sendResponse($return, 'List of assets retrieved successfully.');
    }

    public function getDetailAset(Request $request, $bidang_barang, $id_aset)
    {
        if($bidang_barang == 'A') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'status_tanah', 'no_sertifikat', 'tgl_sertifikat', 'atas_nama_sertifikat', 'panjang_tanah', 'lebar_tanah', 'luas_tanah', 'penggunaan')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'B') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'no_bpkb', 'nopol', 'no_rangka_seri', 'no_mesin', 'ukuran', 'bahan', 'warna', 'cc', 'no_stnk', 'tgl_stnk', 'bahan_bakar')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'B1') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'ukuran', 'bahan', 'warna')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'B2') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'no_bpkb', 'nopol', 'no_rangka_seri', 'no_mesin', 'warna', 'cc', 'no_stnk', 'tgl_stnk', 'bahan_bakar')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'C') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'luas_lantai', 'luas_bangunan', 'jumlah_lantai', 'bahan', 'konstruksi')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'D') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'nama_ruas', 'pangkal', 'ujung', 'panjang_tanah', 'lebar_tanah', 'luas_tanah', 'bahan', 'konstruksi')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'E') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'ukuran')->where('id_aset', 'like', $id_aset)->get(); 
        } else if($bidang_barang == 'E1') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan')->where('id_aset', 'like', $id_aset)->get(); 
        } else if($bidang_barang == 'E2') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'E3') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan', 'ukuran')->where('id_aset', 'like', $id_aset)->get();
        } else if($bidang_barang == 'G') {
            $aset = Kib::select('id_aset', 'no_register', 'kode_108', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'keterangan')->where('id_aset', 'like', $id_aset)->get();
        } 

        return $this->sendResponse($aset->toArray(), 'Detail aset retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $id_aset)
    {
        $input = $request->all();        
        $update = Kib::where('id_aset', $id_aset)->update($input);

        $saldo_awal = Kib_awal::where('id_aset', $id_aset)->first();
        if(!is_null($saldo_awal)) {
            Kib_awal::where('id_aset', 'id_aset')->update($input);
        }

        return $this->sendResponse($input, 'Attribut updated successfully.');
    }
}