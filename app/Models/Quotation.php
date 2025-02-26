<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuotationItem;

class Quotation extends Model
{

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'total_price',
        'status',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function calculateTotal()
    {
        $total = $this->items()->sum('subtotal');
        $this->update(['total_price' => $total]);
        return $total;
    }

    public function markAsPaid()
    {
        $this->update(['payment_status' => 'paid']);
    }
}
