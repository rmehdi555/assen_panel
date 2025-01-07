<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standards extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'title_h1', 'body', 'slug', 'images', 'priority', 'is_show', 'product_categories_id', 'tag_title', 'user_id', 'file_id',
        'seo_title', 'seo_description', 'seo_follow', 'seo_index', 'seo_canonical', 'schema'
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategories::class, 'product_categories_id', 'id');
    }
}
