<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticable
{

    use SoftDeletes;


    // atributes that are hidden for serialization

    protected $hidden = [
        'password',
        'token'
    ];




}