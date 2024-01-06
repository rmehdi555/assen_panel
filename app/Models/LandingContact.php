<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandingContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'landing_id',
        'cell_number',
        'name',
        'email',
    ];

    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class, 'landing_id', 'id');
    }
}
