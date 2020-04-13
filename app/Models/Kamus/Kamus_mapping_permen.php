<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_mapping_permen extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permen_1', 'kode_1', 'uraian_1', 'permen_2', 'kode_2', 'uraian_2'
    ];
}
