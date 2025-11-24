<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'search_tag',
        'status',
        'image',
        'regular_price',
        'sale_price',
        'is_featured',
        'quantity',
        'category_id'
    ];

    protected $attributes = [
        'status' => 1,
        'is_featured' => 0,
        'quantity' => 0
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'quantity' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pricing()
    {
        return $this->hasOne(ProductPricing::class);
    }

    public function priceHistory()
    {
        return $this->hasMany(ProductPriceHistory::class)->orderBy('change_date', 'desc');
    }

    /**
     * Get yesterday's price from history
     */
    public function getPreviousDayPriceAttribute()
    {
        $yesterday = now()->subDay()->toDateString();

        // Try to get yesterday's price
        $yesterdayHistory = $this->priceHistory()
            ->where('change_date', $yesterday)
            ->first();

        if ($yesterdayHistory) {
            return $yesterdayHistory->new_price;
        }

        // If no history, return current price (no change)
        return $this->current_price;
    }
    /**
     * Get current price from pricing
     */
    public function getCurrentPriceAttribute()
    {
        return $this->pricing ? $this->pricing->final_price : 0;
    }

    /**
     * Calculate price change
     */
    public function getPriceChangeAttribute()
    {
        return $this->current_price - $this->previous_day_price;
    }

    /**
     * Calculate percentage change
     */
    public function getPercentageChangeAttribute()
    {
        $previousPrice = $this->previous_day_price;
        if ($previousPrice == 0)
            return 0;

        return ($this->price_change / $previousPrice) * 100;
    }

    /**
     * Get formatted price change display
     */
    public function getPriceChangeDisplayAttribute()
    {
        $change = $this->price_change;
        $percentage = $this->percentage_change;

        if ($change == 0) {
            return '<span class="text-muted">-</span>';
        }

        $direction = $change > 0 ? '↑' : '↓';
        $changeAbs = number_format(abs($change), 2);
        $percentageAbs = number_format(abs($percentage), 1);

        $colorClass = $change > 0 ? 'text-danger' : 'text-success';
        $text = $change > 0 ? 'Increase' : 'Decrease';

        return "<span class='{$colorClass}' title='{$text}'>{$direction} {$percentageAbs}% (₹{$changeAbs})</span>";
    }

    /**
     * Get formatted final price
     */
    public function getFormattedFinalPriceAttribute()
    {
        return '₹' . number_format($this->current_price, 2);
    }

    /**
     * Get formatted previous day price
     */
    public function getFormattedPreviousDayPriceAttribute()
    {
        return '₹' . number_format($this->previous_day_price, 2);
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity > 10) {
            return 'In Stock';
        } elseif ($this->quantity > 0) {
            return 'Low Stock';
        } else {
            return 'Out of Stock';
        }
    }

    /**
     * Get stock status badge class
     */
    public function getStockStatusBadgeAttribute()
    {
        if ($this->quantity > 10) {
            return 'success';
        } elseif ($this->quantity > 0) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}