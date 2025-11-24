<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    use HasFactory;

    protected $table = 'product_price_history';

    protected $fillable = [
        'product_id',
        'previous_price',
        'new_price',
        'price_change',
        'percentage_change',
        'change_date'
    ];

    protected $casts = [
        'previous_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'price_change' => 'decimal:2',
        'percentage_change' => 'decimal:2',
        'change_date' => 'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}