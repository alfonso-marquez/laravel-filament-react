<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = ['name', 'description', 'price', 'stock', 'image', 'original_image_name', 'is_active'];
}
