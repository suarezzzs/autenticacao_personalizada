<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;

class User extends Authenticable
{
    // atributes that are hidden for serialization

    protected $hidden = [
        'password',
        'token'
    ];


}
