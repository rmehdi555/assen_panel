<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlinePayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['price','description', 'user_id', 'user_type','mobile','callbackURL','authority','refId','extraDetail','Token','RetrivalRefNo','SystemTraceNo','status'];
    protected $dates = ['deleted_at'];

}
