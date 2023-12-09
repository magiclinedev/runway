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

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class);
    // }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
