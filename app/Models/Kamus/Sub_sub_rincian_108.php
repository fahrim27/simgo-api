<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Sub_sub_rincian_108 extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_sub_rincian', 'uraian_sub_sub_rincian'
    ];

    protected $primaryKey = 'sub_sub_rincian';
    public $incrementing = false;
}
