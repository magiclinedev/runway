<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categories',
        'image',
        'activeStatus',
    ];
    protected $casts = [
        'categories' => 'array',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCategoriesAttribute()
    {
        // Assuming category IDs are stored in the 'categories' field as a JSON array
        $categoryIds = json_decode($this->attributes['categories'], true);

        // Return the related categories based on the IDs
         // Assuming category IDs are stored in the 'categories' field as a JSON array
         return json_decode($this->attributes['categories'], true);
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
