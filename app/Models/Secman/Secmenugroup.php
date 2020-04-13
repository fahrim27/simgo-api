<?php

namespace App\Models\Secman;
use Illuminate\Database\Eloquent\Model;

class Secmenugroup extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id','menu_id','mgcreate','mgread','mgdelete','mgdelete'
    ];

}
