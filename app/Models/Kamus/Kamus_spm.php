<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_spm extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_spm', 'id_kontrak', 'nomor_sub_unit', 'no_spk_sp_dokumen', 'no_jurnal', 'no_ba_st', 'kode_rek_belanja', 'termin_ke', 'uraian_belanja', 'no_spm_spmu', 'tgl_spm_spmu', 'nilai_spm_spmu', 'tahun_spj', 'operator', 'tgl_update', 'no_sp2d', 'tgl_sp2d', 'no_sko', 'tgl_sko', 'no_spp', 'tgl_spp', 'keterangan', 'tahun_jurnal', 'no_key'
    ];

    protected $primaryKey = 'id_spm';
    public $incrementing = false;
}
