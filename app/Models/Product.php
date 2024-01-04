<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_reference',
        'collection_id',
        'category_id',
        'description',
        'images',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
