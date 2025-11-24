<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\Category;

class PdfController extends Controller
{
    public function downloadPriceList($categoryId = null)
    {
        try {
            // Use the EXACT same logic as HomeController but ensure we load all relationships
            $query = Product::with(['pricing', 'category', 'priceHistory'])
                ->where('status', '1');

            if ($categoryId && $categoryId !== 'all') {
                $query->where('category_id', $categoryId);
                $category = Category::find($categoryId);
                $categoryName = $category ? $category->name : 'Category ' . $categoryId;
            } else {
                $categoryName = 'All Products';
            }

            $products = $query->get();

            // Debug: Check what we're getting
            \Log::info('PDF Products Debug', [
                'total_products' => $products->count(),
                'sample_product' => $products->first() ? [
                    'name' => $products->first()->name,
                    'current_price' => $products->first()->current_price,
                    'previous_day_price' => $products->first()->previous_day_price,
                    'price_change' => $products->first()->price_change,
                    'percentage_change' => $products->first()->percentage_change,
                    'pricing_exists' => !is_null($products->first()->pricing),
                    'pricing_data' => $products->first()->pricing ? [
                        'mrp_base_price' => $products->first()->pricing->mrp_base_price,
                        'final_price' => $products->first()->pricing->final_price,
                        'tax_percentage' => $products->first()->pricing->tax_percentage,
                        'discount_type' => $products->first()->pricing->discount_type,
                        'discount_value' => $products->first()->pricing->discount_value
                    ] : 'No pricing'
                ] : 'No products found'
            ]);

            $data = [
                'products' => $products,
                'categoryName' => $categoryName,
                'companyName' => 'AARSH INTERNATIONAL',
                'generatedDate' => now()->format('F j, Y'),
            ];

            $pdf = Pdf::loadView('pdf.price-list', $data);

            $filename = 'price-list-' . strtolower(str_replace(' ', '-', $categoryName)) . '-' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}