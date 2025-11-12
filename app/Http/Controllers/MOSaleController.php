<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::latest()->paginate(10);
        return view('pos.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('pos.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $cart = json_decode($request->input('cart'), true);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        DB::beginTransaction();
        try {
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
            $tax = $subtotal * 0.05;
            $total = $subtotal + $tax;

            $sale = Sale::create([
                'invoice_no' => 'INV-' . strtoupper(Str::random(6)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            foreach ($cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();
            return redirect()->route('pos.sales.index')->with('success', 'Sale completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
