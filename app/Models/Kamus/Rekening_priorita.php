<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Rekening_priorita extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_rekening', 'uraian', 'kode_64', 'uraian_64', 'kode_108', 'uraian_108', 'masa_manfaat'
    ];
}
