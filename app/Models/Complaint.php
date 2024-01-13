<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;
    protected  $fillable=['name','family','email','phone','body','status'];
    protected $dates = ['deleted_at'];
}
