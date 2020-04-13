<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_kab_kota extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_prov', 'nomor_kab', 'nama_kab'
    ];

    protected $primaryKey = 'nomor_kab';
    public $incrementing = false;
}
