<?php
namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('pos.products.index', compact('products'));
    }

    public function create()
    {
        return view('pos.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'description'=>'nullable|string',
        ]);

        Product::create($request->only(['name','price','stock','description']));
        return redirect()->route('pos.products.index')->with('success','Product created');
    }

    public function edit(Product $product)
    {
        return view('pos.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'description'=>'nullable|string',
        ]);
        $product->update($request->only(['name','price','stock','description']));
        return redirect()->route('pos.products.index')->with('success','Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('pos.products.index')->with('success','Product deleted');
    }
}
