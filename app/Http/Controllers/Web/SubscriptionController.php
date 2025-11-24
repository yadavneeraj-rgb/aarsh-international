<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|max:15|min:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter a valid phone number (10-15 digits)',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Clean phone number - remove all non-numeric characters
            $phoneNumber = preg_replace('/[^0-9]/', '', $request->phone_number);

            // Additional phone number validation
            if (strlen($phoneNumber) < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter a valid phone number with at least 10 digits'
                ], 422);
            }

            // Check if already subscribed
            $subscriber = Subscriber::where('phone_number', $phoneNumber)->first();

            if (!$subscriber) {
                // Create new subscriber
                $subscriber = Subscriber::create([
                    'phone_number' => $phoneNumber,
                    'is_active' => true,
                    'download_count' => 0
                ]);

                Log::info('New subscriber created', ['phone_number' => $phoneNumber, 'id' => $subscriber->id]);
            } else {
                // Reactivate if previously unsubscribed
                if (!$subscriber->is_active) {
                    $subscriber->update(['is_active' => true]);
                }
                Log::info('Existing subscriber accessed', ['phone_number' => $phoneNumber, 'id' => $subscriber->id]);
            }

            // Store in session
            Session::put('subscriber', [
                'phone_number' => $subscriber->phone_number,
                'id' => $subscriber->id
            ]);

            // Set session lifetime (optional)
            Session::put('subscriber_expires_at', now()->addHours(24));

            return response()->json([
                'success' => true,
                'message' => 'Subscription successful! You can now download PDFs.',
                'phone_number' => $subscriber->phone_number
            ]);

        } catch (\Exception $e) {
            Log::error('Subscription error', [
                'error' => $e->getMessage(),
                'phone_number' => $request->phone_number ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during subscription. Please try again.'
            ], 500);
        }
    }

    public function checkSubscription()
    {
        try {
            $subscriber = Session::get('subscriber');
            $expiresAt = Session::get('subscriber_expires_at');

            // Check if subscription session has expired
            if ($expiresAt && now()->gt($expiresAt)) {
                Session::forget('subscriber');
                Session::forget('subscriber_expires_at');
                $subscriber = null;
            }

            if ($subscriber) {
                // Verify the subscriber still exists in database
                $dbSubscriber = Subscriber::find($subscriber['id']);

                if (!$dbSubscriber || !$dbSubscriber->is_active) {
                    Session::forget('subscriber');
                    Session::forget('subscriber_expires_at');
                    return response()->json(['subscribed' => false]);
                }

                return response()->json([
                    'subscribed' => true,
                    'phone_number' => $subscriber['phone_number']
                ]);
            }

            return response()->json(['subscribed' => false]);

        } catch (\Exception $e) {
            Log::error('Check subscription error', ['error' => $e->getMessage()]);
            return response()->json(['subscribed' => false]);
        }
    }

    /**
     * Download price list as CSV file
     */
    public function downloadPriceList($categoryId = 'all')
    {
        try {
            $subscriber = Session::get('subscriber');
            $expiresAt = Session::get('subscriber_expires_at');

            // Check if subscription session has expired
            if ($expiresAt && now()->gt($expiresAt)) {
                Session::forget('subscriber');
                Session::forget('subscriber_expires_at');
                return $this->jsonError('Please subscribe first to download files');
            }

            if (!$subscriber) {
                return $this->jsonError('Please subscribe first to download files');
            }

            // Verify the subscriber exists and is active
            $subscriberRecord = Subscriber::find($subscriber['id']);
            if (!$subscriberRecord || !$subscriberRecord->is_active) {
                Session::forget('subscriber');
                Session::forget('subscriber_expires_at');
                return $this->jsonError('Your subscription is no longer valid. Please subscribe again.');
            }

            // Get category name
            $categoryName = 'All Products';
            if ($categoryId !== 'all') {
                $category = Category::find($categoryId);
                $categoryName = $category ? $category->name : 'Category ' . $categoryId;
            }

            // Update download count
            $subscriberRecord->increment('download_count');
            $subscriberRecord->update(['last_download_at' => now()]);

            Log::info('File download requested', [
                'subscriber_id' => $subscriberRecord->id,
                'phone_number' => $subscriberRecord->phone_number,
                'category_id' => $categoryId,
                'download_count' => $subscriberRecord->download_count
            ]);

            // Generate and return file download
            return $this->generateCsvDownload($categoryId, $categoryName);

        } catch (\Exception $e) {
            Log::error('File download error', [
                'error' => $e->getMessage(),
                'subscriber' => $subscriber ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return $this->jsonError('An error occurred while generating the file. Please try again.');
        }
    }

    /**
     * Generate CSV file download
     */
    private function generateCsvDownload($categoryId, $categoryName)
    {
        try {
            // Get products based on category
            $products = $this->getProductsByCategory($categoryId);

            if ($products->isEmpty()) {
                return $this->jsonError('No products found for the selected category.');
            }

            // Generate CSV content
            $csvContent = $this->generateCsvContent($products, $categoryName);

            // Generate filename
            $filename = 'price-list-' . strtolower(str_replace(' ', '-', $categoryName)) . '-' . date('Y-m-d') . '.csv';

            // Return file download response
            return Response::make($csvContent, 200, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('CSV generation error', ['error' => $e->getMessage()]);
            
            // Fallback to simple text file
            return $this->generateTextDownload($categoryName);
        }
    }

    /**
     * Get products by category
     */
    private function getProductsByCategory($categoryId)
    {
        if ($categoryId === 'all') {
            return Product::with(['category'])
                ->where('current_price', '>', 0)
                ->orderBy('name')
                ->get();
        } else {
            return Product::with(['category'])
                ->where('category_id', $categoryId)
                ->where('current_price', '>', 0)
                ->orderBy('name')
                ->get();
        }
    }

    /**
     * Generate CSV content
     */
    private function generateCsvContent($products, $categoryName)
    {
        $output = fopen('php://output', 'w');
        
        // Enable output buffering to capture the CSV content
        ob_start();
        
        // Header information
        fputcsv($output, ['AARSH INTERNATIONAL - PRICE LIST']);
        fputcsv($output, ['Category: ' . $categoryName]);
        fputcsv($output, ['Generated on: ' . now()->format('F j, Y')]);
        fputcsv($output, ['Phone: +971 XX XXX XXXX | Email: sales@aarshinternational.com']);
        fputcsv($output, []); // Empty line
        
        // Table header
        fputcsv($output, [
            'Product Name',
            'Category', 
            'Previous Price (₹)',
            'Current Price (₹)',
            'Price Change',
            'Change %'
        ]);
        
        // Table rows
        foreach ($products as $product) {
            $changeDirection = '';
            $priceChange = $product->price_change ?? 0;
            $percentageChange = $product->percentage_change ?? 0;
            
            if ($priceChange > 0) {
                $changeDirection = '↑';
            } elseif ($priceChange < 0) {
                $changeDirection = '↓';
            } else {
                $changeDirection = '→';
            }
            
            fputcsv($output, [
                $product->name,
                $product->category ? $product->category->name : 'Uncategorized',
                number_format($product->previous_day_price ?? 0, 2),
                number_format($product->current_price ?? 0, 2),
                $changeDirection . ' ' . number_format(abs($priceChange), 2),
                $changeDirection . ' ' . number_format(abs($percentageChange), 1) . '%'
            ]);
        }
        
        fputcsv($output, []); // Empty line
        fputcsv($output, ['Notes:']);
        fputcsv($output, ['- Prices are subject to change without prior notice']);
        fputcsv($output, ['- All prices are in Indian Rupees (₹)']);
        fputcsv($output, ['- For bulk orders, please contact us directly']);
        fputcsv($output, ['- Visit: http://www.aarshinternational.com']);
        
        fclose($output);
        $csvContent = ob_get_clean();
        
        return $csvContent;
    }

    /**
     * Generate simple text file as fallback
     */
    private function generateTextDownload($categoryName)
    {
        $content = "AARSH INTERNATIONAL - PRICE LIST\n";
        $content .= "================================\n";
        $content .= "Category: " . $categoryName . "\n";
        $content .= "Date: " . now()->format('F j, Y') . "\n";
        $content .= "Contact: +971 XX XXX XXXX\n";
        $content .= "Email: sales@aarshinternational.com\n\n";
        $content .= "Please visit our website for the complete product catalog.\n\n";
        $content .= "Thank you for your interest in Aarsh International!\n";

        $filename = 'price-list-' . strtolower(str_replace(' ', '-', $categoryName)) . '-' . date('Y-m-d') . '.txt';

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Helper method for JSON error responses
     */
    private function jsonError($message, $code = 403)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}