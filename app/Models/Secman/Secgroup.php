<?php

namespace App\Models\Secman;
use Illuminate\Database\Eloquent\Model;

class Secgroup extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'oid', 'gname', 'genable', 'created_by', 'modified_by'
    ];

}
