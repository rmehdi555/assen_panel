<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $fillable = [
        'title', 'slug','product_categories_id','discount','type','description','body','price','price_usd','price_euro','price_old','size','standard','unit','images','tags','priority','status','place_of_delivery','updated_at','tag_title',
        'seo_title','seo_description','seo_follow','seo_index','seo_canonical','schema','factory_id','standard_id','size_id'
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

    public function factoryDetails()
    {
        return $this->hasOne(Factories::class, 'id', 'factory_id');
    }

    public function sizeDetails()
    {
        return $this->hasOne(Sizes::class, 'id', 'size_id');
    }

    public function standardDetails()
    {
        return $this->hasOne(Standards::class, 'id', 'standard_id');
    }

}
