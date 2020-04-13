<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Mapping_lokasi extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nomor_lokasi_17', 'nomor_lokasi_108', 'nama_lokasi'
    ];
}
