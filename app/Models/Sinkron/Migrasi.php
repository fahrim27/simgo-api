<?php

namespace App\Models\Sinkron;
use Illuminate\Database\Eloquent\Model;

class Migrasi extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uraian_jurnal', 'bdp', 'tahun_bdp', 'tahun_jurnal', 'nomor_lokasi', 'pos_entri', 'no_key', 'no_register', 'jenis_aset', 'kode_64', 'nama_barang', 'merk_alamat', 'tipe', 'kode_ruangan', 'saldo_barang', 'saldo_gudang', 'satuan', 'harga_total_plus_pajak_saldo', 'tahun_pengadaan', 'cara_perolehan', 'rencana_alokasi', 'penggunaan', 'keterangan', 'kode_kepemilikan', 'baik', 'kb', 'rb', 'tgl_update', 'operator', 'bidang_barang', 'ukuran', 'no_rangka_seri', 'no_bpkb', 'nopol', 'warna', 'cc', 'no_mesin', 'nama_ruas', 'luas_lantai', 'konstruksi', 'bahan', 'no_sertifikat', 'luas_tanah', 'status_tanah'
    ];
}
