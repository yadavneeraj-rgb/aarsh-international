<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Price List - {{ $categoryName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2c5aa0;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin: 0 0 10px 0;
        }
        .document-title {
            font-size: 18px;
            margin: 10px 0 5px 0;
            font-weight: bold;
        }
        .category {
            font-size: 16px;
            color: #666;
            margin: 5px 0;
        }
        .date {
            font-size: 12px;
            color: #999;
            margin: 5px 0;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        .price-table th,
        .price-table td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
        }
        .price-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .price-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .product-name {
            font-weight: bold;
        }
        .category-badge {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        .current-price {
            font-weight: bold;
            color: #2c5aa0;
        }
        .previous-price {
            color: #999;
            font-size: 10px;
        }
        .price-up {
            color: #dc3545;
            font-weight: bold;
            font-size: 10px;
        }
        .price-down {
            color: #28a745;
            font-weight: bold;
            font-size: 10px;
        }
        .price-neutral {
            color: #6c757d;
            font-size: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .no-products {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .currency {
            font-weight: bold;
            color: #2c5aa0;
        }
        .change-text {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="document-title">CURRENT PRICE LIST</div>
        <div class="category">{{ $categoryName }}</div>
        <div class="date">Generated on: {{ $generatedDate }}</div>
    </div>

    @if($products->count() > 0)
        <table class="price-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Product Name</th>
                    <th style="width: 15%;">Category</th>
                    <th style="width: 15%;" class="text-right">Previous Price</th>
                    <th style="width: 15%;" class="text-right">Current Price</th>
                    <th style="width: 25%;" class="text-center">Price Change</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    @php
                        // Use the model's accessor methods
                        $currentPrice = $product->current_price;
                        $previousPrice = $product->previous_day_price;
                        $priceChange = $product->price_change;
                        $percentageChange = $product->percentage_change;
                        
                        // Determine change display with simple text
                        if ($priceChange == 0) {
                            $changeClass = 'price-neutral';
                            $changeText = 'NO CHANGE';
                            $changeDescription = 'No price change';
                        } elseif ($priceChange > 0) {
                            $changeClass = 'price-up';
                            $changeText = 'INCREASE: ' . number_format(abs($percentageChange), 1) . '%';
                            $changeDescription = 'Price increased by ' . number_format(abs($percentageChange), 1) . '%';
                        } else {
                            $changeClass = 'price-down';
                            $changeText = 'DECREASE: ' . number_format(abs($percentageChange), 1) . '%';
                            $changeDescription = 'Price decreased by ' . number_format(abs($percentageChange), 1) . '%';
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                        </td>
                        <td>
                            @if($product->category)
                                <div class="category-badge">{{ $product->category->name }}</div>
                            @else
                                <div class="category-badge">Uncategorized</div>
                            @endif
                        </td>
                        <td class="text-right previous-price">
                            INR {{ number_format($previousPrice, 2) }}
                        </td>
                        <td class="text-right current-price">
                            <span class="currency">INR {{ number_format($currentPrice, 2) }}</span>
                        </td>
                        <td class="text-center {{ $changeClass }} change-text" title="{{ $changeDescription }}">
                            {{ $changeText }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 20px; font-size: 10px; color: #666;">
            <strong>Total Products:</strong> {{ $products->count() }}
        </div>
        
        <!-- Price Change Legend -->
        <div style="margin-top: 15px; font-size: 10px; color: #666; border-top: 1px solid #eee; padding-top: 10px;">
            <strong>Legend:</strong> 
            <span style="color: #dc3545;">INCREASE</span> | 
            <span style="color: #28a745;">DECREASE</span> | 
            <span style="color: #6c757d;">NO CHANGE</span>
        </div>
    @else
        <div class="no-products">
            <h3>No Products Available</h3>
            <p>There are no products in this category at the moment.</p>
        </div>
    @endif

    <div class="footer">
        <div><strong>M-01, Building No.8, Al Aweer Fruit & Vegetable Market, Dubai, U.A.E</strong></div>
        <div>Email: sales@aarshinternational.com | Phone: +971 XX XXX XXXX</div>
        <div>Website: www.aarshinternational.com</div>
    </div>
</body>
</html>