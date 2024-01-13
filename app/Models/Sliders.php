<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sliders extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'link',
        'is_show',
        'file_id',
        'target'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
