<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aarsh International - Global Foodstuff Trading')</title>

    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/style.css') }}">

    @stack('styles')
</head>

<body>
    <div class="body-wrap overflow-hidden">
        <!-- Back to Top -->
        <div class="backtotop">
            <a href="#!" class="scroll">
                <i class="fas fa-arrow-up fw-bold"></i>
            </a>
        </div>

        <!-- Header Section -->
        @include('web.layouts.header')

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer Section -->
        @include('web.layouts.footer')

        <!-- Quick View Modal -->
        <div class="modal fade quick_view" id="product_quick_view" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content position-relative">
                    <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="modal-body p-0">
                        <div class="container-fluid-full product10_wrap sec_space_small"
                            style="background-image: url({{ asset('images/bg17.png') }})">
                            <div class="row justify-content-center p-5">
                                <div class="col-lg-6">
                                    <div class="product10_thumb_content d-flex flex-column">
                                        <div class="product11_slide_item">
                                            <div
                                                class="d-flex justify-content-center align-items-center position-relative overflow-hidden">
                                                <img src="{{ asset('images/product40.png') }}" alt="product">
                                            </div>
                                        </div>
                                        <div class="product10_thumb_item product11_slide_thumb">
                                            <div class="thumb_item">
                                                <a href="#!"><img src="{{ asset('images/product6.png') }}"
                                                        alt="product"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="rating_wrap d-flex justify-content-between">
                                        <div class="rating_review_cont d-flex align-items-center">
                                            <ul class="rating_star ul_li">
                                                <li class="active"><i class="fas fa-star"></i></li>
                                                <li class="active"><i class="fas fa-star"></i></li>
                                                <li class="active"><i class="fas fa-star"></i></li>
                                                <li class="active"><i class="fas fa-star"></i></li>
                                                <li><i class="far fa-star"></i></li>
                                            </ul>
                                            <a href="#!" class="review">Read 3 Reviews</a>
                                        </div>
                                    </div>
                                    <h2 class="product_detail_title">Good Fresh Products</h2>
                                    <p class="product_detail_desc py-2">Morbi eget congue lectus. Donec eleifend
                                        ultricies urna et euismod.</p>
                                    <div class="product10_quantity_btn_wrap d-flex align-items-center">
                                        <div class="quantity_input bg-white">
                                            <form action="#">
                                                <span class="input_number_decrement">–</span>
                                                <input class="input_number" value="2KG">
                                                <span class="input_number_increment">+</span>
                                            </form>
                                        </div>
                                        <a href="#">
                                            <button type="button"
                                                class="btn custom_btn rounded-pill ms-3 px-5 py-3 text-white">
                                                Order Now <i class="fas fa-long-arrow-alt-right"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="{{ asset('web-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/aos.js') }}"></script>
    <script src="{{ asset('web-assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/countdown.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/custom.js') }}"></script>

    @stack('scripts')
</body>

</html>