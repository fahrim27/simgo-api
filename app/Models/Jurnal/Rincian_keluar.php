<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;
class Rincian_keluar extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nomor_lokasi', 'no_key', 'bidang_barang', 'id_aset', 'no_ba_penerimaan', 'no_register', 'no_label', 'kode_kepemilikan', 'nama_barang', 'merk_alamat', 'kode_108', 'kode_64', 'nopol', 'jumlah_barang', 'harga_satuan', 'nilai_keluar', 'penyusutan_per_tahun', 'akumulasi_penyusutan', 'waktu_susut', 'tahun_buku', 'nilai_buku', 'jumlah_kembali', 'pajak', 'huruf', 'keterangan', 'kondisi', 'penguasaan', 'tahun_spj', 'kode_jurnal', 'baik', 'kb', 'rb', 'sendiri', 'sengketa', 'pihak_3', 'diterima'
    ];
}
