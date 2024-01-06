<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use SoftDeletes;
    protected $fillable= [
        'name',
        'isexpired',
        'description',
        'exchange_id',
        'updated_by',
    ];

    public function exchange(): BelongsTo
    {
        return $this->BelongsTo(Exchanges::class, 'exchange_id', 'id');
    }

    public function sourcePaylog(): HasMany
    {
        return $this->hasMany(SourcePaylog::class);
    }
}
