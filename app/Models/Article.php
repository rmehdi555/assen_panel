<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'body',
        'images',
        'created_by',
        'is_show',
        'file_id',
        'view_count',
        'is_future',
        'seo_title',
        'seo_description',
        'seo_follow',
        'seo_index',
        'seo_canonical'

    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

}
