<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $fillable = [
        'title', 'key', 'value','type','priority','status','images',
    ];
    protected $casts = [
        'images' => 'array'
    ];
    /**

     * The attributes that should be mutated to dates.

     *

     * @var array

     */
    protected $dates = ['deleted_at'];
}
