<?php
namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
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
        $sales = Sale::with('items.product')->latest()->paginate(20);
           
        return view('pos.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('pos.sales.create', compact('products'));
    }

    public function store1(Request $request)
    {
        // expecting JSON cart posted as "cart" (array of {productId, price, qty, subtotal})
        $cart = $request->input('cart');
        if (!$cart || !is_array($cart) || count($cart) === 0) {
            return back()->with('error','Cart empty');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }
            $tax = 0;
            $total = round($subtotal + $tax, 2);

            $sale = Sale::create([
                'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $request->payment_method ?? 'Cash',
            ]);

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['productId']);
                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return back()->with('error', 'Not enough stock for ' . $product->name);
                }
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
                // decrement stock
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            // If request expects JSON (AJAX) return JSON receipt
            if ($request->wantsJson()) {
                $sale->load('items.product');
                return response()->json(['status'=>'success','sale'=>$sale]);
            }

            return redirect()->route('pos.sales.index')->with('success','Sale recorded');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','Error: '.$e->getMessage());
        }
    }
// public function store2(Request $request)
// {
//     // decode the JSON string from the hidden input
//     $cart = json_decode($request->input('cart'), true);

//     if (!$cart || !is_array($cart) || count($cart) === 0) {
//         return back()->with('error','Cart empty');
//     }

//     DB::beginTransaction();
//     try {
//         $subtotal = 0;
//         foreach ($cart as $item) {
//             $subtotal += $item['price'] * $item['qty'];
//         }

//         $tax = 0;
//         $total = round($subtotal + $tax, 2);

//         $sale = Sale::create([
//             'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
//             'subtotal' => $subtotal,
//             'tax' => $tax,
//             'total' => $total,
//             'payment_method' => $request->payment_method ?? 'Cash',
//         ]);

//         foreach ($cart as $item) {
//             $product = Product::findOrFail($item['productId']);
//             if ($product->stock < $item['qty']) {
//                 DB::rollBack();
//                 return back()->with('error', 'Not enough stock for ' . $product->name);
//             }
//             SaleItem::create([
//                 'sale_id' => $sale->id,
//                 'product_id' => $product->id,
//                 'quantity' => $item['qty'],
//                 'price' => $item['price'],
//                 'subtotal' => $item['price'] * $item['qty'],
//             ]);
//             $product->decrement('stock', $item['qty']);
//         }

//         DB::commit();

//         return redirect()->route('pos.sales.index')->with('success','Sale recorded');
//     } catch (\Throwable $e) {
//         DB::rollBack();
//         return back()->with('error','Error: '.$e->getMessage());
//     }
// }

public function store(Request $request)
{
    // Handle both: HTML form (string) and AJAX (array)
    $cart = $request->input('cart');
    if (is_string($cart)) {
        $cart = json_decode($cart, true);
    }

    if (!$cart || !is_array($cart) || count($cart) === 0) {
        if ($request->wantsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Cart empty'], 422);
        }
        return back()->with('error', 'Cart empty');
    }

    DB::beginTransaction();
    try {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        // $tax = round($subtotal * 0.05, 2);
        $tax = 0;
        $total = round($subtotal + $tax, 2);

        $sale = Sale::create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $request->payment_method ?? 'Cash',
        ]);

        foreach ($cart as $item) {
            $product = Product::findOrFail($item['productId']);
            if ($product->stock < $item['qty']) {
                DB::rollBack();
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Not enough stock for ' . $product->name], 400);
                }
                return back()->with('error', 'Not enough stock for ' . $product->name);
            }

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);

            $product->decrement('stock', $item['qty']);
        }

        DB::commit();

        // Respond based on request type
        if ($request->wantsJson()) {
            $sale->load('items.product');
            return response()->json(['status' => 'success', 'sale' => $sale]);
        }

        return redirect()->route('pos.sales.index')->with('success', 'Sale recorded');
    } catch (\Throwable $e) {
        DB::rollBack();
        if ($request->wantsJson()) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function show(Sale $sale)
    {
        $sale->load('items.product');
        return view('pos.sales.show', compact('sale'));
    }
        public function print(Sale $sale)
    {
        // Load your receipt view
        return view('pos.sales.print', compact('sale'));
    }
        public function destroy(Sale $sale)
    {
        $sale->items()->delete(); // delete sale items
        $sale->delete();          // delete sale itself

        return redirect()->route('pos.sales.index')->with('success', 'Sale deleted successfully.');
    }
}
