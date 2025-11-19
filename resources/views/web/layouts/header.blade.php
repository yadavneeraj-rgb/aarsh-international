<header class="header_section header_1">
    <div class="top_header_main d-none d-sm-block">
        <div class="container">
            <div class="header_top d-flex align-items-center justify-content-between">
                <div class="header_top_content d-flex pt-2">
                    <div class="mail_text_content d-flex">
                        <p class="mail_icon">
                            <span><i class="far fa-envelope text-white pe-2"></i></span>
                        </p>
                        <p class="mail_text">sales@aarshinternational.com</p>
                    </div>
                    <div class="address_text_content d-flex">
                        <p class="mail_icon">
                            <span><i class="fas fa-map-marker-alt text-white pe-2"></i></span>
                        </p>
                        <p class="address_text">M-01 , Building No.8, Al Aweer Fruit & Vegetable Market, Dubai, U.A.E
                        </p>
                    </div>
                </div>
                <div class="header_top_socials pt-2">
                    <ul class="list-unstyled d-flex">
                        <li><a href="#!"><i class="fab fa-facebook-f text-white pe-3"></i></a></li>
                        <li><a href="#!"><i class="fab fa-twitter text-white pe-3"></i></a></li>
                        <li><a href="#!"><i class="fab fa-instagram text-white pe-3"></i></a></li>
                        <li><a href="#!"><i class="fab fa-linkedin-in text-white"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="header_bottom_main">
        <div class="container">
            <!-- Desktop Menu -->
            <div class="webMenu d-none d-lg-block position-relative">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand position-relative" style="width: 100px;" href="{{ route('home') }}">
                        <img src="http://www.aarshinternational.com./img/logo.png" alt="Aarsh International">
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav main_menu_list m-auto after_navbar">
                            <li class="nav-item nav_item_has_child px-2">
                                <a class="nav-link" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item nav_item_has_child px-2">
                                <a class="nav-link" href="{{ route('about') }}">About Us</a>
                            </li>
                            <li class="nav-item nav_item_has_child px-2">
                                <a class="nav-link" href="{{ route('products') }}">Product</a>
                            </li>
                            <li class="nav-item nav_item_has_child px-2">
                                <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                            </li>
                        </ul>
                    </div>

                    <!-- User Icons -->
                    <div class="navbar_user me-2">
                        <div class="navbar_user_icon">
                            <ul class="list-unstyled d-flex mb-0">
                                <li class="pe-3">
                                    <button class="main_search_btn" data-bs-toggle="collapse"
                                        data-bs-target="#main_search_collapse" aria-expanded="false"
                                        aria-controls="main_search_collapse">
                                        <i class="search_icon fas fa-search"></i>
                                        <i class="search_close fas fa-times"></i>
                                    </button>
                                </li>
                                <li class="pe-2 position-relative">
                                    <a href="#collapseExample" data-bs-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="collapseExample">
                                        <i class="far fa-user"></i>
                                    </a>
                                    <div id="collapseExample" class="collapse_dropdown collapse">
                                        <div class="dropdown_content">
                                            <div class="profile_info clearfix">
                                                <div class="user_thumbnail">
                                                    <img src="{{ asset('images/meta1.png') }}" alt="user">
                                                </div>
                                                <div class="user_content">
                                                    <h4 class="user_name">Jone Doe</h4>
                                                    <span class="user_title">Seller</span>
                                                </div>
                                            </div>
                                            <ul class="settings_options ul_li_block clearfix">
                                                <li><a href="#!"><i class="fas fa-user-circle"></i> Profile</a></li>
                                                <li><a href="#!"><i class="fas fa-user-cog"></i> Settings</a></li>
                                                <li><a href="#!"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="pe-2"><a href="#!"><i class="far fa-heart"></i></a></li>
                                <li>
                                    <a href="#!">
                                        <i class="fas fa-shopping-bag position-relative" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span>
                                        </i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Mobile Menu -->
            <div class="mobileMenu d-block d-lg-none">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Aarsh International">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Mobile offcanvas menu -->
                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
                        id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close mobile_menu_close text-reset"
                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav main_menu_list m-auto">
                                <li class="nav-item pe-4">
                                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="nav-item pe-4">
                                    <a class="nav-link" href="{{ route('about') }}">About Us</a>
                                </li>
                                <li class="nav-item pe-4">
                                    <a class="nav-link" href="{{ route('products') }}">Products</a>
                                </li>
                                <li class="nav-item pe-4">
                                    <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Mobile user icons -->
                    <div class="navbar_user me-2">
                        <div class="navbar_user_icon">
                            <ul class="list-unstyled d-flex mb-0">
                                <li class="pe-3">
                                    <button class="main_search_btn" data-bs-toggle="collapse"
                                        data-bs-target="#main_search_collapse">
                                        <i class="search_icon fas fa-search"></i>
                                        <i class="search_close fas fa-times"></i>
                                    </button>
                                </li>
                                <li class="pe-2"><a href="#!"><i class="far fa-heart"></i></a></li>
                                <li>
                                    <a href="#!">
                                        <i class="fas fa-shopping-bag position-relative" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span>
                                        </i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Search Form -->
        <div class="main_search_collapse collapse" id="main_search_collapse">
            <div class="main_search_form position-relative card">
                <div class="container">
                    <form action="#">
                        <div class="form_item position-relative">
                            <input type="search" class="form-control rounded-pill py-3" name="search"
                                placeholder="Search Your Product...">
                            <button type="submit" class="submit_btn"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Sidebar Section -->
<section class="sidebar_section">
    <div class="sidebar_content_wrap">
        <div class="container">
            <div class="row">
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header align-items-center">
                        <h5 class="mb-0">Fresh Products</h5>
                        <button type="button" class="btn-close text-reset text-end" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <!-- Sidebar cart items -->
                        <div class="prdc_ctg_product_content mt-1 d-flex align-items-center">
                            <div class="prdc_ctg_product_img d-flex justify-content-center align-items-center me-3">
                                <img src="{{ asset('images/cat6.png') }}" alt="Fresh Cabbage">
                            </div>
                            <div class="prdc_ctg_product_text">
                                <div class="prdc_ctg_product_title my-2">
                                    <a href="#">
                                        <h5>Fresh Cabbage</h5>
                                    </a>
                                </div>
                                <div class="prdc_ctg_product_price mt-1 product_price">
                                    <span class="sale_price pe-1">$50.00</span>
                                    <del>$70.00</del>
                                </div>
                            </div>
                        </div>
                        <!-- Add more cart items as needed -->

                        <div class="total_price">
                            <ul class="ul_li_block mb_30 clearfix">
                                <li><span>Subtotal:</span><span>$215</span></li>
                                <li><span>Vat 5%:</span><span>$10.75</span></li>
                                <li><span>Discount 15%:</span><span>- $32.25</span></li>
                                <li><span>Total:</span><span>$191.8875</span></li>
                            </ul>
                        </div>
                        <div class="sidebar_btns">
                            <ul class="btns_group ul_li_block clearfix">
                                <li><a href="#">View Cart</a></li>
                                <li><a href="#">Checkout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>