@extends('web.layouts.master')

@section('title', 'Aarsh International - All Products')

@section('content')

    <!-- Breadcrumb section start -->
    <section class="breadcrumb_sec_1 position-relative">
        <div class="breadcrumb_wrap sec_space_mid_small" style=" background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                                             url('{{ asset("web-assets/images/breadcrumb/breadcrumb2.jpg") }}');
                                             background-size: cover;
                                             background-position: center;">
            <div class="breadcrumb_cont text-center">
                <div class="breadcrumb_title">
                    <h2 class="text-white">All Products</h2>
                </div>
                <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                    <li><a href="{{ route('home') }}"><i class="fas fa-home active"></i>Home</a></li>
                    <li><i class="fas fa-chevron-right"></i>Products</li>
                    <li><i class="fas fa-chevron-right"></i>All Products</li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Breadcrumb section end -->

    <!-- shop list section start -->
    <section class="shop_list_sec sec_space_small aos-init" data-aos="fade-up" data-aos-duration="2000">
        <div class="shop_list_wrap">
            <div class="container">
                <div class="filter_area d-flex justify-content-between align-items-center sec_space_xxs_40">
                    <ul class="nav layout_tab_nav ul_li" role="tablist">
                        <li>
                            <button class="active" data-bs-toggle="tab" data-bs-target="#grid_layout" type="button"
                                role="tab" aria-selected="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 12 12">
                                    <path id="grid" class="cls-1"
                                        d="M1784,905h2v2h-2v-2Zm5,0h2v2h-2v-2Zm5,0h2v2h-2v-2Zm-10,5h2v2h-2v-2Zm5,0h2v2h-2v-2Zm5,0h2v2h-2v-2Zm-10,5h2v2h-2v-2Zm5,0h2v2h-2v-2Zm5,0h2v2h-2v-2Z"
                                        transform="translate(-1784 -905)"></path>
                                </svg>
                            </button>
                        </li>
                        <li>
                            <button data-bs-toggle="tab" data-bs-target="#list_layout" type="button" role="tab"
                                aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 12 12">
                                    <path id="list" class="cls-1"
                                        d="M1823,905h2v2h-2v-2Zm5,0h2v2h-2v-2Zm5,0h2v2h-2v-2Zm-10,5h2v2h-2v-2Zm5,0h2v2h-2v-2Zm5,0h2v2h-2v-2Zm-10,5h2v2h-2v-2Zm5,0h2v2h-2v-2Zm5,0h2v2h-2v-2Zm-5-10h7v2h-7v-2Zm0,5h7v2h-7v-2Zm0,5h7v2h-7v-2Z"
                                        transform="translate(-1823 -905)"></path>
                                </svg>
                            </button>
                        </li>
                        <span class="show_result">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results</span>
                    </ul>
                    <form action="#">
                        <div class="sorting_from d-flex align-items-center">
                            <span class="sorting_from_title">Default Sorting:</span>
                            <div class=" clearfix">
                                <select style="display: none;">
                                    <option data-display="Select">Nothing</option>
                                    <option value="1" selected="">Popularity</option>
                                    <option value="2">Organic</option>
                                    <option value="4">Fantastic</option>
                                </select>
                                <div class="nice-select" tabindex="0"><span class="current">Popularity</span>
                                    <ul class="list">
                                        <li data-value="Nothing" data-display="Select" class="option">Nothing</li>
                                        <li data-value="1" class="option selected">Popularity</li>
                                        <li data-value="2" class="option">Organic</li>
                                        <li data-value="4" class="option">Fantastic</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="shop_product_list">
                    <div class="tab-content aos-init" id="pills-tabContent" data-aos="fade-up" data-aos-duration="2000">
                      <!-- Grid Layout -->
<div class="tab-pane fade show active" id="grid_layout" role="tabpanel"
    aria-labelledby="pills-grid_layout-tab">
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-sm-6 col-md-6 col-xl-3">
            <div class="product_layout_1 overflow-hidden position-relative">
                <a href="{{ route('products.show', $product->id) }}" class="product-card-link" style="display: block; text-decoration: none; color: inherit;">
                    <div class="product_layout_content bg-white position-relative">
                        <div class="product_image_wrap">
                            <div class="product_image d-flex justify-content-center align-items-center">
                                @if($product->image)
                                    <img class="pic-1" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    <img class="pic-2" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <img class="pic-1" src="{{ asset('web-assets/images/product1.png') }}" alt="image_not_found">
                                    <img class="pic-2" src="{{ asset('web-assets/images/product2.png') }}" alt="image_not_found">
                                @endif
                            </div>
                            <ul class="product_badge_group ul_li_block">
                                @if($product->category)
                                <li><span
                                        class="product_badge badge_meats position-absolute rounded-pill text-uppercase">{{ $product->category->name }}</span>
                                </li>
                                @endif
                                
                                {{-- Price Change Badge --}}
                                @php
                                    $percentage = $product->percentage_change;
                                @endphp
                                @if($percentage !== null && $percentage != 0)
                                <li><span class="product_badge badge_discount position-absolute rounded-pill"
                                        style="padding:4px 8px; font-size:12px; color:#fff; background-color: {{ $percentage > 0 ? '#ff4d4d' : '#28a745' }};">
                                        @if($percentage > 0)
                                            ↑ {{ number_format($percentage, 1) }}%
                                        @else
                                            ↓ {{ number_format(abs($percentage), 1) }}%
                                        @endif
                                    </span>
                                </li>
                                @endif
                            </ul>
                            <ul class="product_action_btns ul_li_block d-flex">
                                <li><a class="tooltips" data-placement="top" title="Search Product"
                                        href="#!"><i class="fas fa-search"></i></a></li>
                                <li><a class="tooltips" data-placement="top" title="Add To Cart"
                                        href="#product_quick_view" data-bs-toggle="modal"><i
                                            class="fas fa-shopping-bag"></i></a></li>
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
                                {{ $product->name }}
                            </h3>
                            <div class="product_price">
                                @if($product->pricing)
                                    @if($product->pricing->final_price)
                                        <span class="sale_price pe-1">₹{{ number_format($product->pricing->final_price, 2) }}</span>
                                        <del>₹{{ number_format($product->previous_day_price, 2) }}</del>
                                    @else
                                        <span class="sale_price pe-1">₹{{ number_format($product->pricing->price, 2) }}</span>
                                    @endif
                                @else
                                    <span class="sale_price pe-1">Price not available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach

        @if($products->count() == 0)
        <div class="col-12 text-center">
            <div class="no-products">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4>No Products Available</h4>
                <p class="text-muted">Check back later for our products</p>
            </div>
        </div>
        @endif
    </div>
</div>

                  <!-- List Layout -->
<div class="tab-pane fade" id="list_layout" role="tabpanel"
    aria-labelledby="pills-list_layout-tab">
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-12">
            <a href="{{ route('products.show', $product->id) }}" class="product-card-link" style="display: block; text-decoration: none; color: inherit;">
                <div class="product_list_layout bg-white p-4 rounded shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="product_image_wrap">
                                <div class="product_image d-flex justify-content-center align-items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                                    @else
                                        <img src="{{ asset('web-assets/images/product1.png') }}" alt="image_not_found" class="img-fluid">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="product_content">
                                @if($product->category)
                                <span class="product_badge badge_meats rounded-pill text-uppercase mb-2 d-inline-block">{{ $product->category->name }}</span>
                                @endif
                                
                                <h3 class="product_title mb-2">
                                    {{ $product->name }}
                                </h3>
                                
                                <div class="rating_wrap d-flex mb-2">
                                    <ul class="rating_star ul_li">
                                        @for($i = 1; $i <= 5; $i++)
                                            <li class="{{ $i <= 4 ? 'active' : '' }}">
                                                <i class="{{ $i <= 4 ? 'fas' : 'far' }} fa-star"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                    <span class="shop_review_text">( 4.0 )</span>
                                </div>
                                
                                <p class="product_description mb-2">
                                    {{ Str::limit($product->description ?? 'No description available', 150) }}
                                </p>
                                
                                {{-- Price Change Indicator --}}
                                @php
                                    $percentage = $product->percentage_change;
                                @endphp
                                @if($percentage !== null && $percentage != 0)
                                <div class="price-change-indicator mb-2">
                                    <span class="badge {{ $percentage > 0 ? 'bg-danger' : 'bg-success' }}">
                                        @if($percentage > 0)
                                            ↑ {{ number_format($percentage, 1) }}% Increase
                                        @else
                                            ↓ {{ number_format(abs($percentage), 1) }}% Decrease
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="product_price mb-3">
                                @if($product->pricing)
                                    @if($product->pricing->final_price)
                                        <span class="sale_price h4 d-block">₹{{ number_format($product->pricing->final_price, 2) }}</span>
                                        <del class="text-muted">₹{{ number_format($product->previous_day_price, 2) }}</del>
                                    @else
                                        <span class="sale_price h4">₹{{ number_format($product->pricing->price, 2) }}</span>
                                    @endif
                                @else
                                    <span class="sale_price h4">Price not available</span>
                                @endif
                            </div>
                            <div class="product_action_btns">
                                <button class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach

        @if($products->count() == 0)
        <div class="col-12 text-center">
            <div class="no-products py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4>No Products Available</h4>
                <p class="text-muted">Check back later for our products</p>
            </div>
        </div>
        @endif
    </div>
</div>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                <ul class="pagination_nav mt-5 list-unstyled d-flex justify-content-center text-uppercase clearfix">
                    {{-- Previous Page Link --}}
                    @if($products->onFirstPage())
                        <li class="disabled"><span><i class="fas fa-arrow-left"></i></span></li>
                    @else
                        <li><a href="{{ $products->previousPageUrl() }}"><i class="fas fa-arrow-left"></i></a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if($page == $products->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($products->hasMorePages())
                        <li><a href="{{ $products->nextPageUrl() }}"><i class="fas fa-arrow-right"></i></a></li>
                    @else
                        <li class="disabled"><span><i class="fas fa-arrow-right"></i></span></li>
                    @endif
                </ul>
                @endif
            </div>
        </div>
    </section>
    <!-- shop list section end -->

    <!-- shop testimonial section start -->
    <div class="shop_testimonial_sec sec_inner_bottom_100">
        <div class="shop_testimonial_wrap">
            <div class="container-sm">
                <div class="offer_top_content shop_testimonial_cont text-center">
                    <div class="offer_sub_title d-flex align-items-center justify-content-center pb-2">
                        <i class="far fa-circle"></i>
                        <i class="far fa-circle"></i>
                        <i class="far fa-circle"></i>
                        <span class="text-uppercase px-3">testimonial</span>
                        <i class="far fa-circle"></i>
                        <i class="far fa-circle"></i>
                        <i class="far fa-circle"></i>
                    </div>
                    <div class="offer_title pb-5">
                        <h2>What Our Customers Saying</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="shop_testimonial_text aos-init" data-aos="fade-right" data-aos-duration="2000">
                            <span class="qoute_icon"><i class="fas fa-quote-left"></i></span>
                            <p class="testimonial_desc py-3">"Aarsh International provides the freshest and highest quality food products. Their global sourcing ensures we get the best ingredients for our business."</p>
                            <ul class="rating_star ul_li my-3">
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                            </ul>
                            <h4 class="testimonial_author">Alina Parker <font>Restaurant Owner</font></h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="shop_testimonial_text aos-init" data-aos="fade-left" data-aos-duration="2000">
                            <span class="qoute_icon"><i class="fas fa-quote-left"></i></span>
                            <p class="testimonial_desc py-3">"The consistency in product quality and reliable delivery makes Aarsh International our preferred foodstuff trading partner. Highly recommended!"</p>
                            <ul class="rating_star ul_li my-3">
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                            </ul>
                            <h4 class="testimonial_author">Kevin Andrew <font>Hotel Manager</font></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .product_list_layout {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .product_list_layout:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .price-change-indicator .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .no-products {
        padding: 3rem 1rem;
    }
    
    .no-products i {
        opacity: 0.5;
    }
    
    .pagination_nav li {
        margin: 0 5px;
    }
    
    .pagination_nav li a,
    .pagination_nav li span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #495057;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .pagination_nav li.active span {
        background: #28a745;
        color: white;
    }
    
    .pagination_nav li a:hover {
        background: #e9ecef;
    }
</style>
@endpush