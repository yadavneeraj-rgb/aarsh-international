@extends('web.layouts.master')

@section('title', 'Aarsh International - ' . $product->name)

@section('content')

    <!-- Breadcrumb section start -->
    <section class="breadcrumb_sec_1 position-relative">
        <div class="breadcrumb_wrap sec_space_mid_small" style=" background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                                             url('{{ asset("web-assets/images/breadcrumb/breadcrumb2.jpg") }}');
                                             background-size: cover;
                                             background-position: center;">
            <div class="breadcrumb_cont text-center">
                <div class="breadcrumb_title">
                    <h2 class="text-white">Product Details</h2>
                </div>
                <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                    <li><a href="{{ route('home') }}"><i class="fas fa-home active"></i>Home</a></li>
                    <li><i class="fas fa-chevron-right"></i>Products</li>
                    <li><i class="fas fa-chevron-right"></i>{{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Breadcrumb section end -->

    <!-- product-10 section start -->
    <section class="product10_sec sec_space_small">
        <div class="product10_wrap">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 position-relative">
                        <div class="product10_thumb img_moving_anim1 position-relative d-flex justify-content-center align-items-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-100" alt="{{ $product->name }}">
                                <!-- <div class="product10_back_thumb1 position-absolute">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                </div> -->
                                <!-- <div class="product10_back_thumb2 position-absolute">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                </div> -->
                            @else
                                <img src="{{ asset('web-assets/images/product3.png') }}" class="w-100" alt="image_not_found">
                                <!-- <div class="product10_back_thumb1 position-absolute">
                                    <img src="{{ asset('web-assets/images/product3.png') }}" alt="image_not_found">
                                </div> -->
                                <!-- <div class="product10_back_thumb2 position-absolute">
                                    <img src="{{ asset('web-assets/images/product3.png') }}" alt="image_not_found">
                                </div> -->
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="rating_wrap d-flex justify-content-between">
                            <div class="rating_review_cont d-flex align-items-center">
                                <ul class="rating_star ul_li">
                                    @for($i = 1; $i <= 5; $i++)
                                        <li class="{{ $i <= 4 ? 'active' : '' }}">
                                            <i class="{{ $i <= 4 ? 'fas' : 'far' }} fa-star"></i>
                                        </li>
                                    @endfor
                                </ul>
                                <a href="#reviews" class="review">Read Reviews</a>
                            </div>
                            @if($product->category)
                            <div class="product_btn">
                                <a href="#">
                                    <button type="button" class="btn custom_btn rounded-pill px-4 text-white">
                                        {{ $product->category->name }}
                                    </button>
                                </a>
                            </div>
                            @endif
                        </div>
                        <h2 class="product_detail_title">{{ $product->name }}</h2>
                        <p class="product_detail_desc py-2">
                            {{ $product->description ?? 'No description available for this product.' }}
                        </p>
                        
                        <div class="product_price mb-4">
                            @if($product->pricing)
                                @php
                                    $basePrice = $product->pricing->final_price ?? $product->pricing->price;
                                    $basePreviousPrice = $product->previous_day_price;
                                @endphp
                                
                                <span class="sale_price h3 pe-2" id="currentPriceDisplay">₹{{ number_format($basePrice, 2) }}</span>
                                
                                @if($product->pricing->final_price)
                                    <del class="text-muted h5" id="previousPriceDisplay">₹{{ number_format($basePreviousPrice, 2) }}</del>
                                @endif
                                
                                {{-- Price Change Indicator --}}
                                @php
                                    $percentage = $product->percentage_change;
                                @endphp
                                @if($percentage !== null && $percentage != 0)
                                <div class="price-change-indicator mt-2">
                                    <span class="badge {{ $percentage > 0 ? 'bg-danger' : 'bg-success' }}">
                                        @if($percentage > 0)
                                            ↑ {{ number_format($percentage, 1) }}% Increase
                                        @else
                                            ↓ {{ number_format(abs($percentage), 1) }}% Decrease
                                        @endif
                                    </span>
                                </div>
                                @endif
                                
                                {{-- Hidden fields to store base prices --}}
                                <input type="hidden" id="basePrice" value="{{ $basePrice }}">
                                <input type="hidden" id="basePreviousPrice" value="{{ $basePreviousPrice }}">
                            @else
                                <span class="sale_price h3">Price not available</span>
                            @endif
                        </div>

                        <div class="product10_quantity_btn_wrap d-flex align-items-center">
                            <div class="quantity_input">
                                <form action="#">
                                    <span class="input_number_decrement">–</span>
                                    <input class="input_number" value="1" id="quantity" readonly>
                                    <span class="input_number_increment">+</span>
                                </form>
                                <small class="text-muted d-block mt-1">Quantity (KG)</small>
                            </div>
                            <button type="button" class="btn custom_btn rounded-pill ms-3 px-5 py-3 text-white add-to-cart"
                                    data-product-id="{{ $product->id }}">
                                Add to Cart <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                        
                        <div class="product_tags_wrap d-flex align-items-center mt-5">
                            <h6 class="product_tags_title text-uppercase">Category:</h6>
                            <div class="tags_item d-flex align-items-center">
                                @if($product->category)
                                <a href="#">{{ $product->category->name }}</a>
                                @else
                                <span class="ms-2">Uncategorized</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="product_social_links d-flex align-items-center mt-3">
                            <h6 class="product_social_title text-uppercase">share:</h6>
                            <ul class="list-unstyled d-flex mb-0">
                                <li><a href="#!"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#!"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#!"><i class="fab fa-linkedin-in"></i></a></li>
                                <li><a href="#!"><i class="fab fa-pinterest-p"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product-10 section end -->

    <!-- product-10 reviews section start -->
    <section class="product10_reviews sec_inner_bottom_80 aos-init" data-aos="fade-up" data-aos-duration="2000" id="reviews">
        <div class="product10_reviews_wrap">
            <div class="container">
                <div class="d-flex justify-content-center justify-content-lg-start align-items-center">
                    <ul class="product_tabnav_3 nav nav-pills my-5" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active shadow rounded-pill text-uppercase" id="pills-description-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-description" type="button" role="tab"
                                aria-controls="pills-description" aria-selected="true">description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link shadow rounded-pill text-uppercase" id="pills-specifications-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-specifications" type="button" role="tab"
                                aria-controls="pills-specifications" aria-selected="false">Specifications</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link shadow rounded-pill text-uppercase" id="pills-reviews-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-reviews" type="button" role="tab"
                                aria-controls="pills-reviews" aria-selected="false">reviews</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-description" role="tabpanel"
                        aria-labelledby="pills-description-tab">
                        <div class="row mb_50">
                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <div class="content_wrap">
                                    <h3 class="title_text mb_15">Product Description:</h3>
                                    <p class="mb_15">
                                        {{ $product->description ?? 'No detailed description available for this product.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="pills-specifications" role="tabpanel"
                        aria-labelledby="pills-specifications-tab">
                        <div class="row mb_50">
                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <div class="content_wrap">
                                    <h3 class="title_text mb_15">Product Specifications:</h3>
                                    <div class="specifications">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <td>{{ $product->name }}</td>
                                                </tr>
                                                @if($product->category)
                                                <tr>
                                                    <th>Category</th>
                                                    <td>{{ $product->category->name }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <th>Product ID</th>
                                                    <td>#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        <span class="badge bg-success">In Stock</span>
                                                    </td>
                                                </tr>
                                                @if($product->pricing)
                                                <tr>
                                                    <th>Price (per KG)</th>
                                                    <td>₹{{ number_format($product->pricing->final_price ?? $product->pricing->price, 2) }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="pills-reviews" role="tabpanel" aria-labelledby="pills-reviews-tab">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="review_comment2">
                                    <h3 class="title_text">Customer Reviews:</h3>
                                    <div class="alert alert-info">
                                        <p class="mb-0">No reviews yet. Be the first to review this product!</p>
                                    </div>
                                </div>

                                <div class="comment_form_area">
                                    <form action="#">
                                        <div class="row">
                                            <h4 class="comment_title">Add Your Review</h4>
                                            <div class="col-lg-6">
                                                <div class="form_item">
                                                    <input class="rounded-pill" type="text" name="name"
                                                        placeholder="Your Name*" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form_item">
                                                    <input class="rounded-pill" type="email" name="email"
                                                        placeholder="Email Address*" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form_item">
                                                    <textarea name="comment" placeholder="Your Review*" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit"
                                            class="btn custom_btn rounded-pill py-3 text-white text-uppercase">Submit Review
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products section start -->
    @if($relatedProducts->count() > 0)
    <section class="product_section sec_top_space_50 sec_inner_bottom_100 aos-init" data-aos="fade-up"
        data-aos-duration="2000">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product_sec_content text-center">
                        <div class="product_sec_sub_title d-flex align-items-center pb-2 justify-content-center">
                            <i class="far fa-circle"></i>
                            <i class="far fa-circle"></i>
                            <i class="far fa-circle"></i>
                            <span>RELATED PRODUCTS</span>
                        </div>
                        <h2 class="product_sec_title pb-3">You May Also Like</h2>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-sm-6 col-lg-3">
                    <div class="product_layout_1 overflow-hidden position-relative">
                        <div class="product_layout_content bg-white position-relative">
                            <div class="product_image_wrap">
                                <a class="product_image d-flex justify-content-center align-items-center"
                                    href="{{ route('products.show', $relatedProduct->id) }}">
                                    @if($relatedProduct->image)
                                        <img class="pic-1" src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                                        <img class="pic-2" src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                                    @else
                                        <img class="pic-1" src="{{ asset('web-assets/images/product1.png') }}" alt="image_not_found">
                                        <img class="pic-2" src="{{ asset('web-assets/images/product2.png') }}" alt="image_not_found">
                                    @endif
                                </a>
                                <ul class="product_badge_group ul_li_block">
                                    @if($relatedProduct->category)
                                    <li><span
                                            class="product_badge badge_meats position-absolute rounded-pill text-uppercase">{{ $relatedProduct->category->name }}</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="rating_wrap d-flex">
                                <ul class="rating_star ul_li">
                                    @for($i = 1; $i <= 5; $i++)
                                        <li class="{{ $i <= 4 ? 'active' : '' }}">
                                            <i class="{{ $i <= 4 ? 'fas' : 'far' }} fa-star"></i>
                                        </li>
                                    @endfor
                                </ul>
                                <span class="shop_review_text">( 4.0 )</span>
                            </div>
                            <div class="product_content">
                                <h3 class="product_title">
                                    <a href="{{ route('products.show', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a>
                                </h3>
                                <div class="product_price">
                                    @if($relatedProduct->pricing)
                                        @if($relatedProduct->pricing->final_price)
                                            <span class="sale_price pe-1">₹{{ number_format($relatedProduct->pricing->final_price, 2) }}</span>
                                            <del>₹{{ number_format($relatedProduct->previous_day_price, 2) }}</del>
                                        @else
                                            <span class="sale_price pe-1">₹{{ number_format($relatedProduct->pricing->price, 2) }}</span>
                                        @endif
                                    @else
                                        <span class="sale_price pe-1">Price not available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!-- Related Products section end -->

@endsection

@push('styles')
<style>
.quantity_input {
    position: relative;
    display: inline-block;
}

.quantity_input form {
    display: flex;
    align-items: center;
}

.input_number_decrement,
.input_number_increment {
    width: 40px;
    height: 40px;
    background: #28a745;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: bold;
    user-select: none;
}

.input_number_decrement {
    border-radius: 5px 0 0 5px;
}

.input_number_increment {
    border-radius: 0 5px 5px 0;
}

.input_number {
    width: 60px;
    height: 40px;
    text-align: center;
    border: 1px solid #ddd;
    border-left: none;
    border-right: none;
    margin: 0;
    background: white;
}

.price-update {
    transition: all 0.3s ease;
}

.price-update.updated {
    color: #28a745;
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const decrementBtn = document.querySelector('.input_number_decrement');
        const incrementBtn = document.querySelector('.input_number_increment');
        const quantityInput = document.getElementById('quantity');
        const currentPriceDisplay = document.getElementById('currentPriceDisplay');
        const previousPriceDisplay = document.getElementById('previousPriceDisplay');
        const basePriceInput = document.getElementById('basePrice');
        const basePreviousPriceInput = document.getElementById('basePreviousPrice');
        
        // Get base prices from hidden inputs
        const basePrice = parseFloat(basePriceInput.value);
        const basePreviousPrice = parseFloat(basePreviousPriceInput.value);
        
        // Function to update price display
        function updatePriceDisplay(quantity) {
            const totalPrice = basePrice * quantity;
            
            // Update current price
            currentPriceDisplay.textContent = `₹${totalPrice.toFixed(2)}`;
            
            // Update previous price if it exists
            if (previousPriceDisplay) {
                const totalPreviousPrice = basePreviousPrice * quantity;
                previousPriceDisplay.textContent = `₹${totalPreviousPrice.toFixed(2)}`;
            }
            
            // Add animation effect
            currentPriceDisplay.classList.add('price-update', 'updated');
            setTimeout(() => {
                currentPriceDisplay.classList.remove('updated');
            }, 300);
        }
        
        // Quantity increment
        incrementBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
            updatePriceDisplay(quantityInput.value);
        });
        
        // Quantity decrement
        decrementBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updatePriceDisplay(quantityInput.value);
            }
        });
        
        // Manual quantity input (if enabled)
        quantityInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1) {
                this.value = 1;
                value = 1;
            }
            updatePriceDisplay(value);
        });
        
        // Add to cart functionality
        const addToCartBtn = document.querySelector('.add-to-cart');
        addToCartBtn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantity = parseInt(quantityInput.value);
            const totalPrice = basePrice * quantity;
            
            console.log('Adding to cart:', {
                productId: productId,
                quantity: quantity,
                totalPrice: totalPrice
            });
            
            // Show success message with quantity and price
            alert(`Added ${quantity} KG to cart! Total: ₹${totalPrice.toFixed(2)}`);
        });
        
        // Initialize price display
        updatePriceDisplay(1);
    });
</script>
@endpush