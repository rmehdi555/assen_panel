<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingSite extends Model
{
    use SoftDeletes;

    protected $table = 'shoping_sites';

    protected $fillable = [
        'region_id',
        'site',
        'path',
        'url',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
