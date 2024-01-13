<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sizes extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title','body','slug','images','priority','status','product_categories_id','tag_title',
        'seo_title','seo_description','seo_follow','seo_index','seo_canonical','schema'
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

//    use Sluggable;
//    /**
//     * Return the sluggable configuration array for this model.
//     *
//     * @return array
//     */
//    public function sluggable()
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }

    public function category()
    {
        return $this->hasOne('App\ProductCategories', 'id', 'product_categories_id');
    }
}
