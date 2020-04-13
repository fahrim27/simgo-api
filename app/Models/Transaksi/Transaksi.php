<?php

namespace App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_key', 'terkunci', 'kode_jurnal', 'kode_pencatat', 'kode_kegiatan', 'nomor_lokasi', 'dari_lokasi', 'bdp', 'tahun_bdp', 'otomatis', 'no_sa', 'no_ba_penerimaan', 'tgl_ba_penerimaan', 'instansi_yg_menyerahkan', 'alamat', 'tgl_update', 'operator', 'tahun_spj', 'nomor_sub_unit', 'tahun_spm', 'keterangan', 'nilai_spk', 'uraian_spk', 'no_sp2d', 'tgl_sp2d', 'nomor', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'no_spm', 'tgl_spm', 'no_ba_st', 'tgl_ba_st', 'no_bap', 'tgl_bap', 'id_kontrak', 'tutup_buku'
    ];
    
    protected $primaryKey = 'no_key';
    public $incrementing = false;
}
