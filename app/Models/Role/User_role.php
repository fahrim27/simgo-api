<?php

namespace App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class User_role extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'role_id'
    ];
}
