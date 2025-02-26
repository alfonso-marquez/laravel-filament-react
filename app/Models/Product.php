<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes; // 👈 Enable soft deletes
    protected $fillable = ['name', 'description', 'price', 'stock', 'image', 'original_image_name', 'is_active'];

    public function quotations()
        {
            return $this->hasManyThrough(Quotation::class, QuotationItem::class);
        }

}
