<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_sub_unit extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_unit', 'nomor_sub_unit', 'nama_sub_unit', 'nip_pimpinan', 'pemegang_anggaran'
    ];

    protected $primaryKey = 'nomor_sub_unit';
    public $incrementing = false;
}
