<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id' ,'title', 'description'
    ];

    /*
    * relationship from item to user
    */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * relationship from item to category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
