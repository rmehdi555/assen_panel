<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

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
