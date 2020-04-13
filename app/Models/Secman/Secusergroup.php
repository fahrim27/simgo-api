<?php

namespace App\Models\Secman;
use Illuminate\Database\Eloquent\Model;

class Secusergroup extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'group_id'
    ];

}