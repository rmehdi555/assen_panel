<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'email',
        'name',
        'family',
        'phone',
        'body',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
