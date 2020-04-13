<?php

namespace App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'level', 'parent_id', 'name'
    ];
}
