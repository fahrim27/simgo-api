<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nomor_sub_unit', 'no_jurnal', 'no_ba_st', 'kode_rek_belanja', 'termin_ke', 'uraian_belanja', 'no_spm_spmu', 'tgl_spm_spmu', 'nilai_spm_spmu', 'tahun_spj', 'operator', 'tgl_update', 'no_sp2d', 'tgl_sp2d', 'no_sko', 'tgl_sko', 'no_spp', 'tgl_spp', 'keterangan', 'tahun_jurnal', 'no_key', 'id_spm'
    ];
}
