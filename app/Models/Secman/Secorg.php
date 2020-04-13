<?php

namespace App\Models\Secman;
use Illuminate\Database\Eloquent\Model;

class Secorg extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'oparentid', 'oname', 'onomorunit', 'olevel', 'opath', 'oext', 'oenable', 'created_by', 'modified_by'
    ];

}
