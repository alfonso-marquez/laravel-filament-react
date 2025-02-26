<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{

    protected $fillable = [
        'quotation_id',
        'product_id',
        'color',
        'quantity',
        'price',
        'subtotal',
    ];

    protected static function boot()
{
    parent::boot();

    static::saving(function ($item) {
        $item->subtotal = $item->price * $item->quantity;
    });
}

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
