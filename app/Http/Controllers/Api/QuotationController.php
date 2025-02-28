<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use App\Models\Product;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:255',
            'status' => 'required|string|in:pending,approved,rejected',
            'payment_status' => 'required|string|in:pending,paid',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.color' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Create the quotation
        $quotation = Quotation::create([
            'user_id' => $validated['user_id'] ?? null,
            'guest_name' => $validated['guest_name'] ?? null,
            'guest_email' => $validated['guest_email'] ?? null,
            'guest_phone' => $validated['guest_phone'] ?? null,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
        ]);

        // Add items to the quotation
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $item['product_id'],
                'color' => $item['color'],
                'quantity' => $item['quantity'],
                'price' => $product->price, // Set price from product
                'subtotal' => $product->price * $item['quantity'], // Calculate subtotal
            ]);
        }

        return response()->json(['message' => 'Quotation created successfully', 'quotation' => $quotation], 201);
    }
}
