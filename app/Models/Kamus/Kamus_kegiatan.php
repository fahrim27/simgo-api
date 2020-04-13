<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_kegiatan extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_kegiatan', 'kode_kegiatan', 'nama_kegiatan', 'nomor_sub_unit', 'tahun'
    ];
    
    protected $primaryKey = 'id_kegiatan';
    public $incrementing = false;
}
