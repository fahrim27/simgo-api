<?php

namespace App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Role_permission extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'permission_id'
    ];
}
