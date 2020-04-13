<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Penunjang extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_spk_penunjang',  'no_spk_sp_dokumen', 'nomor_lokasi', 'no_spm_penunjang', 'tgl_spm_penunjang', 'tgl_spk_sp_dokumen', 'no_key', 'nilai_penunjang', 'tahun_spj', 'uraian'
    ];

    protected $primaryKey = 'no_spk_penunjang';
    public $incrementing = false;
}
