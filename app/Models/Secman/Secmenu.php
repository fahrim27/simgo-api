<?php

namespace App\Models\Secman;
use Illuminate\Database\Eloquent\Model;

class Secmenu extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mparentid','mname','mdesc','micon','muri','mseq','menable','mshow','mpass','mtype','created_by','modified_by'
    ];

}
