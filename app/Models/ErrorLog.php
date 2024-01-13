<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErrorLog extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'pgsql_log';

    protected $fillable = ['user_id', 'ip', 'url', 'message', 'stack'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class,  'user_id');
    }

}
