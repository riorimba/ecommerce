<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $query = Product::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('sort_by_price')) {
            $query->orderBy('price', $request->sort_by_price);
        }

        $products = $query->get();

        return response()->json($products);
    }
}