<?php

/*
 * type : ['online','card','check']
 *
 * status :
 * 1-> ایجاد شده توسط ادمین
 * 2- > ایجاد شده توسط کاربر
 * 3-> در انتظار پرداخت
 * 5-> پرداخت موفق
 * 6-> ناموفق
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','price','description_admin', 'description_user', 'type', 'images','status'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'images' => 'array'
    ];


    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
