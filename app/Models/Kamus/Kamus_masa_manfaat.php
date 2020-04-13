<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_masa_manfaat extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelompok', 'uraian_kelompok', 'masa_manfaat'
    ];

    protected $primaryKey = 'nomor_rekening';
    public $incrementing = false;
}
