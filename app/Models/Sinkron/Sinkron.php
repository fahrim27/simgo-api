<?php

namespace App\Models\Sinkron;
use Illuminate\Database\Eloquent\Model;

class Sinkron extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'simda2018';
    protected $fillable = [
        'no_spk_sp_dokumen', 'no_spp', 'tgl_spp', 'no_spm_spmu', 'tgl_spm_spmu', 'tahun_jurnal', 'no_sp2d', 'tgl_sp2d', 'uraian_belanja', 'rk1', 'rk2', 'rk3', 'rk4', 'rk5'
    ];
    
    protected $primaryKey = 'no_key';
    public $incrementing = false;
}
