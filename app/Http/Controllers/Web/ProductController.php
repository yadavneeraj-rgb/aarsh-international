<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product()
    {
        $products = Product::with(['pricing', 'category', 'priceHistory'])
            ->whereHas('pricing')
            ->where('status', '1')
            ->latest()
            ->paginate(12);
        $categories = Category::mainCategories()->get();

        return view("web.product.product", compact('products', 'categories'));
    }

    // Add this method for single product page
    public function show($id)
    {
        $product = Product::with(['pricing', 'category', 'priceHistory'])
            ->where('id', $id)
            ->where('status', '1')
            ->firstOrFail();

        // Get related products (products from same category)
        $relatedProducts = Product::with(['pricing', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', '1')
            ->take(4)
            ->get();

        return view('web.product.single-product', compact('product', 'relatedProducts'));
    }
}