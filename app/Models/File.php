<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caption',
        'path',
        'extensions',
        'hash',
        'original_name',
        'size',
        'user_id',
        'file_category_id',
    ];
}
