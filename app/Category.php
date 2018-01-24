<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title',
    ];

    /*
    *  get all category items
    */
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }

    /*
    * relationship from category to user
    */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
