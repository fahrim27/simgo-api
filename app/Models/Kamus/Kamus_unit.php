<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_unit extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_bidang_unit', 'nomor_unit', 'nama_unit', 'nip_kepala_unit'
    ];

    protected $primaryKey = 'nomor_unit';
    public $incrementing = false;
}
