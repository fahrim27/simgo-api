<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;

class Rincian_koreksi extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nomor_lokasi', 'kode_koreksi', 'kode_sb_koreksi', 'no_key', 'bidang_barang', 'id_aset', 'no_ba_penerimaan', 'no_register', 'no_label', 'nama_barang', 'merk_alamat', 'kode_108', 'kode_64', 'kode_kepemilikan', 'jumlah_barang', 'nilai', 'kode_108_baru', 'kode_64_baru', 'kode_kepemilikan_baru', 'jumlah_barang_baru', 'nilai_baru', 'keterangan', 'tahun_koreksi', 'no_register_baru', 
    ];

}
