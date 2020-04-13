<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Rincian_108 extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rincian', 'uraian_rincian'
    ];

    protected $primaryKey = 'rincian';
    public $incrementing = false;
}
