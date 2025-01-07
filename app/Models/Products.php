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
        'title', 'title_h1', 'slug', 'product_categories_id', 'discount', 'type', 'description', 'body', 'price', 'price_usd', 'price_euro', 'price_old', 'size', 'standard', 'unit', 'images', 'tags', 'priority', 'is_show', 'place_of_delivery', 'updated_at', 'tag_title',
        'seo_title', 'seo_description', 'seo_follow', 'seo_index', 'seo_canonical', 'schema', 'factory_id', 'standard_id', 'size_id', 'user_id', 'file_id'
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

    public function category()
    {
        return $this->belongsTo(ProductCategories::class, 'product_categories_id', 'id');
    }

    public function factoryDetails()
    {
        return $this->belongsTo(Factories::class, 'factory_id', 'id');
    }

    public function sizeDetails()
    {
        return $this->belongsTo(Sizes::class, 'size_id', 'id');
    }

    public function standardDetails()
    {
        return $this->belongsTo(Standards::class, 'standard_id', 'id');
    }

}
