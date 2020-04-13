<?php

namespace App\Models\Sinkron;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_lokasi', 'nama_barang', 'kode_108', 'merk_alamat', 'tipe', 'bahan', 'ukuran', 'warna', 'jumlah_barang', 'saldo_barang', 'saldo_gudang', 'satuan', 'harga_satuan', 'harga_total', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'keterangan'
    ];
}
