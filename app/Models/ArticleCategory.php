<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'is_show',
        'priority',
        'description',
        'body',
        'file_id',
        'images',
        'seo_title',
        'seo_description',
        'seo_follow',
        'seo_index',
        'seo_canonical'

    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

}
