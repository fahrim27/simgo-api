<?php

namespace App\Models\Sinkron;
use Illuminate\Database\Eloquent\Model;

class Dishub extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_lokasi', 'no_key', 'no_register', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'saldo_barang', 'satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan'
    ];
}
