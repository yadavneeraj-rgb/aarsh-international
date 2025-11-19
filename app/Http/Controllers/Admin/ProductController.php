<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPricing;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function product()
    {
        $products = Product::with(['pricing', 'category', 'priceHistory'])->get();
        return view('admin.product.product', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::mainCategories()->get();
        return view('admin.product.createProduct', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product,name',
            'description' => 'nullable|string',
            'search_tag' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'mrp_base_price' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $productData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'search_tag' => $request->search_tag,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'status' => 1,
                'is_featured' => $request->is_featured ?? 0,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $productData['image'] = $imagePath;
            }

            // Create product
            $product = Product::create($productData);

            // Create pricing
            $pricingData = [
                'product_id' => $product->id,
                'mrp_base_price' => $request->mrp_base_price,
                'tax_percentage' => $request->tax_percentage,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value ?? 0,
            ];

            $productPricing = new ProductPricing($pricingData);
            $productPricing->calculateFinalPrice();
            $productPricing->save();

            // Create initial price history
            $this->createPriceHistory($product, 0, $productPricing->final_price, 'initial_price');

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product->load(['pricing', 'category'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editProduct($id)
    {
        $product = Product::with(['pricing', 'category'])->findOrFail($id);
        $categories = Category::mainCategories()->get();

        return response()->json([
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product,name,' . $id,
            'description' => 'nullable|string',
            'search_tag' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'mrp_base_price' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $product = Product::with('pricing')->findOrFail($id);

            // Get the current price before update
            $oldPrice = $product->pricing ? $product->pricing->final_price : 0;

            $updateData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'search_tag' => $request->search_tag,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'is_featured' => $request->is_featured ?? 0,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('products', 'public');
                $updateData['image'] = $imagePath;
            }

            $product->update($updateData);

            // Update or create pricing
            $pricingData = [
                'mrp_base_price' => $request->mrp_base_price,
                'tax_percentage' => $request->tax_percentage,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value ?? 0,
            ];

            $productPricing = $product->pricing ?? new ProductPricing(['product_id' => $product->id]);
            $productPricing->fill($pricingData);
            $productPricing->calculateFinalPrice();
            $productPricing->save();

            $newPrice = $productPricing->final_price;

            // Create price history if price changed
            if ($oldPrice != $newPrice) {
                $this->createPriceHistory($product, $oldPrice, $newPrice, 'price_update');
            } else {
                // Even if price didn't change, ensure we have today's price recorded
                $this->ensureTodayPriceRecord($product, $newPrice);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product->load(['pricing', 'category'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create price history record
     */
    private function createPriceHistory(Product $product, $oldPrice, $newPrice, $changeType = 'price_update')
    {
        $priceChange = $newPrice - $oldPrice;
        $percentageChange = $oldPrice > 0 ? ($priceChange / $oldPrice) * 100 : 0;

        // Check if we already have a record for today
        $todayHistory = ProductPriceHistory::where('product_id', $product->id)
            ->whereDate('change_date', now()->toDateString())
            ->first();

        if ($todayHistory) {
            // Update existing record for today
            $todayHistory->update([
                'previous_price' => $oldPrice,
                'new_price' => $newPrice,
                'price_change' => $priceChange,
                'percentage_change' => $percentageChange,
                'change_type' => $changeType
            ]);
        } else {
            // Create new record for today
            ProductPriceHistory::create([
                'product_id' => $product->id,
                'previous_price' => $oldPrice,
                'new_price' => $newPrice,
                'price_change' => $priceChange,
                'percentage_change' => $percentageChange,
                'change_date' => now()->toDateString(),
                'change_type' => $changeType
            ]);
        }

        // Also ensure we have yesterday's price recorded for comparison
        $this->ensureYesterdayPriceRecord($product, $oldPrice);
    }

    /**
     * Ensure we have a price record for today (even if price didn't change)
     */
    private function ensureTodayPriceRecord(Product $product, $currentPrice)
    {
        $todayHistory = ProductPriceHistory::where('product_id', $product->id)
            ->whereDate('change_date', now()->toDateString())
            ->first();

        if (!$todayHistory) {
            // Get yesterday's price for comparison
            $yesterdayPrice = $this->getYesterdayPrice($product);

            ProductPriceHistory::create([
                'product_id' => $product->id,
                'previous_price' => $yesterdayPrice,
                'new_price' => $currentPrice,
                'price_change' => $currentPrice - $yesterdayPrice,
                'percentage_change' => $yesterdayPrice > 0 ? (($currentPrice - $yesterdayPrice) / $yesterdayPrice) * 100 : 0,
                'change_date' => now()->toDateString(),
                'change_type' => 'no_change'
            ]);
        }
    }

    /**
     * Ensure we have yesterday's price recorded
     */
    private function ensureYesterdayPriceRecord(Product $product, $yesterdayPrice)
    {
        $yesterday = now()->subDay()->toDateString();

        $yesterdayHistory = ProductPriceHistory::where('product_id', $product->id)
            ->whereDate('change_date', $yesterday)
            ->first();

        if (!$yesterdayHistory) {
            // Get the most recent price before yesterday
            $previousPrice = $this->getPreviousPriceBeforeDate($product, $yesterday);

            ProductPriceHistory::create([
                'product_id' => $product->id,
                'previous_price' => $previousPrice,
                'new_price' => $yesterdayPrice,
                'price_change' => $yesterdayPrice - $previousPrice,
                'percentage_change' => $previousPrice > 0 ? (($yesterdayPrice - $previousPrice) / $previousPrice) * 100 : 0,
                'change_date' => $yesterday,
                'change_type' => 'auto_generated'
            ]);
        }
    }

    /**
     * Get yesterday's price from history or current price
     */
    private function getYesterdayPrice(Product $product)
    {
        $yesterday = now()->subDay()->toDateString();

        $yesterdayHistory = ProductPriceHistory::where('product_id', $product->id)
            ->whereDate('change_date', $yesterday)
            ->first();

        if ($yesterdayHistory) {
            return $yesterdayHistory->new_price;
        }

        // If no history for yesterday, get the most recent price before today
        $recentHistory = ProductPriceHistory::where('product_id', $product->id)
            ->whereDate('change_date', '<', now()->toDateString())
            ->orderBy('change_date', 'desc')
            ->first();

        return $recentHistory ? $recentHistory->new_price : ($product->pricing ? $product->pricing->final_price : 0);
    }

    /**
     * Get the most recent price before a specific date
     */
    private function getPreviousPriceBeforeDate(Product $product, $date)
    {
        $previousHistory = ProductPriceHistory::where('product_id', $product->id)
            ->whereDate('change_date', '<', $date)
            ->orderBy('change_date', 'desc')
            ->first();

        return $previousHistory ? $previousHistory->new_price : 0;
    }

    /**
     * Initialize price history for all existing products (one-time command)
     */
    public function initializePriceHistory()
    {
        try {
            $products = Product::with('pricing')->get();
            $count = 0;

            foreach ($products as $product) {
                if ($product->pricing) {
                    // Check if product already has price history
                    $existingHistory = ProductPriceHistory::where('product_id', $product->id)->exists();

                    if (!$existingHistory) {
                        $this->createPriceHistory($product, 0, $product->pricing->final_price, 'initial_setup');
                        $count++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Price history initialized for {$count} products."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error initializing price history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get price history for a product (API endpoint)
     */
    public function getPriceHistory($id)
    {
        try {
            $product = Product::with([
                'priceHistory' => function ($query) {
                    $query->orderBy('change_date', 'desc')->limit(30);
                }
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'price_history' => $product->priceHistory,
                'product' => $product->only(['id', 'name', 'current_price', 'previous_day_price'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching price history: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete image file if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleFeatured($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Toggle the featured status
            $product->update([
                'is_featured' => !$product->is_featured
            ]);

            return response()->json([
                'success' => true,
                'message' => $product->is_featured
                    ? 'Product marked as featured!'
                    : 'Product removed from featured!',
                'is_featured' => $product->is_featured
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating featured status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update product prices with history tracking
     */
    public function bulkUpdatePrices(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:product,id',
            'products.*.mrp_base_price' => 'required|numeric|min:0',
            'products.*.tax_percentage' => 'required|numeric|min:0|max:100',
            'products.*.discount_type' => 'nullable|in:flat,percentage',
            'products.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $updatedCount = 0;

            foreach ($request->products as $productData) {
                $product = Product::with('pricing')->find($productData['id']);

                if ($product) {
                    $oldPrice = $product->pricing ? $product->pricing->final_price : 0;

                    // Update pricing
                    $pricingData = [
                        'mrp_base_price' => $productData['mrp_base_price'],
                        'tax_percentage' => $productData['tax_percentage'],
                        'discount_type' => $productData['discount_type'] ?? null,
                        'discount_value' => $productData['discount_value'] ?? 0,
                    ];

                    $productPricing = $product->pricing ?? new ProductPricing(['product_id' => $product->id]);
                    $productPricing->fill($pricingData);
                    $productPricing->calculateFinalPrice();
                    $productPricing->save();

                    $newPrice = $productPricing->final_price;

                    // Create price history if price changed
                    if ($oldPrice != $newPrice) {
                        $this->createPriceHistory($product, $oldPrice, $newPrice, 'bulk_update');
                    }

                    $updatedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Prices updated for {$updatedCount} products.",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating prices: ' . $e->getMessage()
            ], 500);
        }
    }
}