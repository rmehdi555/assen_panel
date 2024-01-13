<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $fillable = [
        'title', 'slug', 'link','menu_categories_id','parent_id','icon','priority','status',
    ];

    /**

     * The attributes that should be mutated to dates.

     *

     * @var array

     */
    protected $dates = ['deleted_at'];
    use Sluggable;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function parent()
    {
        return $this->belongsTo('App\Menu','parent_id')->where('parent_id',0);
    }

    public function children()
    {
        return $this->hasMany('App\Menu','parent_id');
    }
    public function menus()
    {
        return $this->hasMany('App\Menu'); // This only gets the menus of the CURRENT category
    }
}
