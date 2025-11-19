<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all products with pricing, category, and price history
        $products = Product::with(['pricing', 'category', 'priceHistory'])
            ->whereHas('pricing')
            ->get();

        // Get categories for filtering
        $categories = Category::mainCategories()->get();

        return view('web.home', compact('products', 'categories'));
    }
}