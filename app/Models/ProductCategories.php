<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategories extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $fillable = [
        'title',
        'title_h1',
        'slug',
        'description',
        'body',
        'images',
        'priority',
        'is_show',
        'user_id',
        'seo_title','seo_description','seo_follow','seo_index','seo_canonical'
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



    public function products()
    {
        return $this->hasMany('App\Products'); // This only gets the products of the CURRENT category
    }
    public function activeProducts($limit='10')
    {
        return $this->hasMany('App\Products')->where('status','=','1')->orderBy('priority','desc')->limit($limit)->get(); // This only gets the products of the CURRENT category
    }
}
