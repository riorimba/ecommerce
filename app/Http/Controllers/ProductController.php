<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('images')->get();
        return view('products.index', compact('products'));
    }

    public function create(){
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request){
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $product = Product::create($request->all());
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('product_images'), $fileName);
    
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'product_images/' . $fileName,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show($id){
        $product = Product::with('images')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id){
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $product->update($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('product_images'), $fileName);
    
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'product_images/' . $fileName,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product){
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}