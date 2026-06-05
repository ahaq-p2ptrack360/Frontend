
<link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="build/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="build/images/logo-dark.png" alt="" height="22">
                        </span>
                    </a>

                    <a href="index" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="build/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="build/images/logo-light.png" alt="" height="22">
                        </span>
                    </a>
                </div>
                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                <div class="app-search d-none d-md-inline-flex">
                    <div class="position-relative">
                        <input type="text" class="form-control border-0" placeholder="Search for anything..."
                            autocomplete="off" id="search-options" value="">
                        <span class="ti ti-search search-widget-icon"></span>
                        <span class="ti ti-x search-widget-icon search-widget-icon-close d-none"
                            id="search-close-options"></span>
                    </div>
                    <!-- <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                        <div data-simplebar style="max-height: 320px;">
                            
                            <div class="dropdown-header">
                                <h6 class="text-overflow fs-sm text-muted mb-0 text-uppercase">Recent Searches</h6>
                            </div>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a href="index" class="btn btn-subtle-secondary btn-sm btn-rounded">how to setup <i
                                        class="ti ti-search ms-1"></i></a>
                                <a href="index" class="btn btn-subtle-secondary btn-sm btn-rounded">buttons <i
                                        class="ti ti-search ms-1"></i></a>
                            </div>
                        
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow fs-sm text-muted mb-1 text-uppercase">Pages</h6>
                            </div>

                         
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                                <span>Analytics Dashboard</span>
                            </a>

                     
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                                <span>Help Center</span>
                            </a>

              
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                <span>My account settings</span>
                            </a>

                     
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow fs-sm text-muted mb-2 text-uppercase">Members</h6>
                            </div>

                            <div class="notification-list">
                            
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="build/images/users/avatar-2.jpg"
                                            class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-md m-0">Angela Bernier</h6>
                                            <span class="fs-sm mb-0 text-muted">Manager</span>
                                        </div>
                                    </div>
                                </a>
                                  <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="build/images/users/avatar-3.jpg"
                                            class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-md m-0">David Grasso</h6>
                                            <span class="fs-sm mb-0 text-muted">Web Designer</span>
                                        </div>
                                    </div>
                                </a>
                               
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="build/images/users/avatar-5.jpg" class="me-3 rounded-circle avatar-xs"
                                            alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-md m-0">Mike Bunch</h6>
                                            <span class="fs-sm mb-0 text-muted">React Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="text-center pt-3 pb-1">
                            <a href="#!" class="btn btn-primary btn-sm">View All Results <i
                                    class="ri-arrow-right-line ms-1"></i></a>
                        </div>
                    </div> -->
                </div>
                
            </div>

            <div class="d-flex align-items-center">
            <div style="display:none; align-items:center; gap: -1;" id="ticket_profilediv" >
                    <select class="form-control" data-choices name="selectPeriod" id="ticket_profile">
                        <option value="">Select Profile</option>
                        <option value="all">ALL</option>
                        <option value="createdby_me">Created By Me</option>
                        <option value="assignto_me" selected>Assigned to Me</option>
                    </select>
                </div>

                <div class="d-flex align-items-center ms-3">
                            <input type="text" class="form-control" id="dateRange" placeholder="Select Start & End Date">
                        </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <!-- <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='ti ti-category-2 fs-3xl'></i>
                    </button> -->
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-base"> Browse by Apps </h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#!" class="btn btn-sm btn-subtle-info"> View All Apps
                                        <i class="ri-arrow-right-s-line align-middle"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="build/images/brands/github.png" alt="Github">
                                        <span>GitHub</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="build/images/brands/bitbucket.png" alt="bitbucket">
                                        <span>Bitbucket</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="build/images/brands/dribbble.png" alt="dribbble">
                                        <span>Dribbble</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="build/images/brands/dropbox.png" alt="dropbox">
                                        <span>Dropbox</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="build/images/brands/mail_chimp.png" alt="mail_chimp">
                                        <span>Mail Chimp</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="build/images/brands/slack.png" alt="slack">
                                        <span>Slack</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown ms-1 topbar-head-dropdown header-item">
                    <!-- <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                     @switch(Session::get('lang'))
                        @case('ru')
                        <img src="{{ URL::asset('build/images/flags/russia.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @case('it')
                        <img src="{{ URL::asset('build/images/flags/italy.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @case('sp')
                        <img src="{{ URL::asset('build/images/flags/spain.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @case('ch')
                        <img src="{{ URL::asset('build/images/flags/china.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @case('fr')
                        <img src="{{ URL::asset('build/images/flags/french.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @case('gr')
                        <img src="{{ URL::asset('build/images/flags/germany.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @case('ae')
                        <img src="{{ URL::asset('build/images/flags/ae.svg') }}" class="rounded" alt="Header Language" height="20">
                        @break
                        @default
                        <!-- <img src="{{ URL::asset('build/images/flags/us.svg') }}" class="rounded" alt="Header Language" height="20"> -->
                        @endswitch
                    <!-- </button> -->
                    <div class="dropdown-menu dropdown-menu-end">
                
                        <!-- item-->
                        <a href="{{ url('index/en') }}" class="dropdown-item notify-item language py-2" data-lang="en" title="English">
                            <img src="{{ URL::asset('build/images/flags/us.svg') }}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">English</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/sp') }}" class="dropdown-item notify-item language" data-lang="sp" title="Spanish">
                            <img src="{{ URL::asset('build/images/flags/spain.svg') }}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">Española</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/gr') }}" class="dropdown-item notify-item language" data-lang="gr" title="German">
                            <img src="{{ URL::asset('build/images/flags/germany.svg') }}" alt="user-image" class="me-2 rounded" height="18"> <span class="align-middle">Deutsche</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/it') }}" class="dropdown-item notify-item language" data-lang="it" title="Italian">
                            <img src="{{ URL::asset('build/images/flags/italy.svg') }}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">Italiana</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/ru') }}" class="dropdown-item notify-item language" data-lang="ru" title="Russian">
                            <img src="{{ URL::asset('build/images/flags/russia.svg') }}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">русский</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/ch') }}" class="dropdown-item notify-item language" data-lang="ch" title="Chinese">
                            <img src="{{ URL::asset('build/images/flags/china.svg') }}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">中国人</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/fr') }}" class="dropdown-item notify-item language" data-lang="fr" title="French">
                            <img src="{{ URL::asset('build/images/flags/french.svg') }}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">français</span>
                        </a>
                
                        <!-- item-->
                        <a href="{{ url('index/ae') }}" class="dropdown-item notify-item language" data-lang="ar" title="Arabic">
                            <img src="{{URL::asset('build/images/flags/ae.svg')}}" alt="user-image" class="me-2 rounded" height="18">
                            <span class="align-middle">عربي</span>
                        </a>
                    </div>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <!-- <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class='ti ti-shopping-cart fs-3xl'></i>
                        <span class="position-absolute topbar-badge cartitem-badge fs-3xs translate-middle badge rounded-pill bg-info">5</span>
                    </button> -->
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 product-list" aria-labelledby="page-header-cart-dropdown">
                        <div class="p-3 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-lg fw-semibold"> My Cart <span class="badge bg-secondary fs-sm cartitem-badge ms-1">7</span></h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#!">View All</a>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 300px;">
                            <div class="p-3">
                                <div class="text-center empty-cart" id="empty-cart">
                                    <div class="avatar-md mx-auto my-3">
                                        <div class="avatar-title bg-info-subtle text-info fs-2 rounded-circle">
                                            <i class='bx bx-cart'></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-3">Your Cart is Empty!</h6>
                                    <a href="#!" class="btn btn-success w-md mb-3">Shop Now</a>
                                </div>
                                
                                <div class="d-block dropdown-item product text-wrap p-2">
                                    <div class="d-flex">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title bg-light rounded">
                                                <img src="build/images/products/img-1.png" class="avatar-xs" alt="user-pic">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fs-sm text-muted">Fashion</p>
                                            <h6 class="mt-0 mb-3 fs-md">
                                                <a href="#!" class="text-reset">Blive Printed Men Round Neck</a>
                                            </h6>
                                            <div class="text-muted fw-medium d-none">$<span class="product-price">327.49</span></div>
                                            <div class="input-step">
                                                <button type="button" class="minus">–</button>
                                                <input type="number" class="product-quantity" value="2" min="0" max="100" readonly>
                                                <button type="button" class="plus">+</button>
                                            </div>
                                        </div>
                                        <div class="ps-2 d-flex flex-column justify-content-between align-items-end">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-primary remove-cart-btn" data-bs-toggle="modal" data-bs-target="#removeCartModal"><i class="ri-close-fill fs-lg"></i></button>
                                            <h6 class="mb-0">$ <span class="product-line-price">654.98</span></h6>
                                        </div> 
                                    </div>
                                </div>

                                <div class="d-block dropdown-item product text-wrap p-2">
                                    <div class="d-flex">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title bg-light rounded">
                                                <img src="build/images/products/img-5.png" class="avatar-xs" alt="user-pic">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fs-sm text-muted">Sportwear</p>
                                            <h6 class="mt-0 mb-3 fs-md">
                                                <a href="#!" class="text-reset">Willage Volleyball Ball</a>
                                            </h6>
                                            <div class="text-muted fw-medium d-none">$<span class="product-price">49.06</span></div>
                                            <div class="input-step">
                                                <button type="button" class="minus">–</button>
                                                <input type="number" class="product-quantity" value="3" min="0" max="100" readonly>
                                                <button type="button" class="plus">+</button>
                                            </div>
                                        </div>
                                        <div class="ps-2 d-flex flex-column justify-content-between align-items-end">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-primary remove-cart-btn" data-bs-toggle="modal" data-bs-target="#removeCartModal"><i class="ri-close-fill fs-lg"></i></button>
                                            <h6 class="mb-0">$ <span class="product-line-price">147.18</span></h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item product text-wrap p-2">
                                    <div class="d-flex">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title bg-light rounded">
                                                <img src="build/images/products/img-10.png" class="avatar-xs" alt="user-pic">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fs-sm text-muted">Fashion</p>
                                            <h6 class="mt-0 mb-3 fs-md">
                                                <a href="#!" class="text-reset">Cotton collar tshirts for men</a>
                                            </h6>
                                            <div class="text-muted fw-medium d-none">$<span class="product-price">53.33</span></div>
                                            <div class="input-step">
                                                <button type="button" class="minus">–</button>
                                                <input type="number" class="product-quantity" value="3" min="0" max="100" readonly>
                                                <button type="button" class="plus">+</button>
                                            </div>
                                        </div>
                                        <div class="ps-2 d-flex flex-column justify-content-between align-items-end">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-primary remove-cart-btn" data-bs-toggle="modal" data-bs-target="#removeCartModal"><i class="ri-close-fill fs-lg"></i></button>
                                            <h6 class="mb-0">$ <span class="product-line-price">159.99</span></h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item product text-wrap p-2">
                                    <div class="d-flex">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title bg-light rounded">
                                                <img src="build/images/products/img-11.png" class="avatar-xs" alt="user-pic">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fs-sm text-muted">Fashion</p>
                                            <h6 class="mt-0 mb-3 fs-md">
                                                <a href="#!" class="text-reset">Jeans blue men boxer</a>
                                            </h6>
                                            <div class="text-muted fw-medium d-none">$<span class="product-price">164.37</span></div>
                                            <div class="input-step">
                                                <button type="button" class="minus">–</button>
                                                <input type="number" class="product-quantity" value="1" min="0" max="100" readonly>
                                                <button type="button" class="plus">+</button>
                                            </div>
                                        </div>
                                        <div class="ps-2 d-flex flex-column justify-content-between align-items-end">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-primary remove-cart-btn" data-bs-toggle="modal" data-bs-target="#removeCartModal"><i class="ri-close-fill fs-lg"></i></button>
                                            <h6 class="mb-0">$ <span class="product-line-price">164.37</span></h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item product text-wrap p-2">
                                    <div class="d-flex">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title bg-light rounded">
                                                <img src="build/images/products/img-8.png" class="avatar-xs" alt="user-pic">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fs-sm text-muted">Fashion</p>
                                            <h6 class="mt-0 mb-3 fs-md">
                                                <a href="#!" class="text-reset">Full Sleeve Solid Men Sweatshirt</a>
                                            </h6>
                                            <div class="text-muted fw-medium d-none">$<span class="product-price">180.00</span></div>
                                            <div class="input-step">
                                                <button type="button" class="minus">–</button>
                                                <input type="number" class="product-quantity" value="1" min="0" max="100" readonly>
                                                <button type="button" class="plus">+</button>
                                            </div>
                                        </div>
                                        <div class="ps-2 d-flex flex-column justify-content-between align-items-end">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-primary remove-cart-btn" data-bs-toggle="modal" data-bs-target="#removeCartModal"><i class="ri-close-fill fs-lg"></i></button>
                                            <h6 class="mb-0">$ <span class="product-line-price">180.00</span></h6>
                                        </div>
                                    </div>
                                </div>

                                <div id="count-table">
                                    <table class="table table-borderless mb-0  fw-semibold">
                                        <tbody>
                                            <tr>
                                                <td>Sub Total :</td>
                                                <td class="text-end cart-subtotal">$1306.52</td>
                                            </tr>
                                            <tr>
                                                <td>Discount <span class="text-muted">(VIXON30)</span>:</td>
                                                <td class="text-end cart-discount">- $195.98</td>
                                            </tr>
                                            <tr>
                                                <td>Shipping Charge :</td>
                                                <td class="text-end cart-shipping">$65.00</td>
                                            </tr>
                                            <tr>
                                                <td>Estimated Tax (12.5%) : </td>
                                                <td class="text-end cart-tax">$163.31</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                        <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border" id="checkout-elem">
                            <div class="d-flex justify-content-between align-items-center pb-3">
                                <h6 class="m-0 text-muted">Total:</h6>
                                <div class="px-2">
                                    <h6 class="m-0 cart-total">$1338.86</h6>
                                </div>
                            </div>

                            <a href="#!" class="btn btn-info text-center w-100">
                                Checkout
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ms-1 header-item d-none d-sm-flex">
                 <button class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasForm">
                                <i class="fa-solid fa-filter"></i>
                            </button>
                            </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" data-toggle="fullscreen">
                        <i class='ti ti-arrows-maximize fs-3xl'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" id="cameraScreenshotBtn">
        <i class="ti ti-camera fs-3xl"></i>
    </button>
</div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text mode-layout" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-sun align-middle fs-3xl"></i>
                    </button>
                    <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                        <a href="#!" class="dropdown-item" data-mode="light"><i class="bi bi-sun align-middle me-2"></i> Default (light mode)</a>
                        <a href="#!" class="dropdown-item" data-mode="dark"><i class="bi bi-moon align-middle me-2"></i> Dark</a>
                        <a href="#!" class="dropdown-item" data-mode="auto"><i class="bi bi-moon-stars align-middle me-2"></i> Auto (system default)</a>
                    </div>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
        <i class='ti ti-bell-ringing fs-3xl'></i>
        <span class="position-absolute topbar-badge fs-3xs translate-middle badge rounded-pill bg-danger">
            <span class="notification-badge">0</span>
            <span class="visually-hidden">unread messages</span>
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
        <div class="dropdown-head rounded-top">
            <div class="p-3 border-bottom border-bottom-dashed">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0 fs-lg fw-semibold">Notifications 
                            <span class="badge bg-danger-subtle text-danger fs-sm notification-badge">0</span>
                        </h6>
                        <p class="fs-md text-muted mt-1 mb-0">You have 
                            <span class="fw-semibold notification-unread">0</span> unread messages
                        </p>
                    </div>
                    <div class="col-auto dropdown">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" class="link-secondary fs-md" id="notificationThreeDots">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" id="notificationActionsMenu">
                            <li><a class="dropdown-item mark-all-read-btn" href="#">Mark all as read</a></li>
                            <li><a class="dropdown-item delete-all-btn text-danger" href="#">Delete All</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2 ps-2" id="notificationItemsTabContent">
            <div class="overflow-auto pe-2" style="max-height: 280px;">
                <!-- Dynamic content will load here -->
                <div class="text-center py-4">Loading notifications...</div>
            </div>
            <div class="notification-actions" id="notification-actions" style="display: none;">
                <div class="d-flex text-muted justify-content-center align-items-center py-2 border-top">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                        <label class="form-check-label small" for="selectAllCheckbox">Select All</label>
                    </div>
                    <div class="vr mx-2"></div>
                    Select <span id="select-content" class="text-body fw-semibold px-1">0</span> 
                    Result
                    <button type="button" class="btn btn-link link-danger p-0 ms-2" id="deleteSelectedBtn">Remove</button>
                </div>
            </div>
        </div>
    </div>
</div>

               <div class="dropdown ms-sm-3 topbar-head-dropdown dropdown-hover-end header-item topbar-user">
    <button type="button" class="btn shadow-none btn-icon" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="d-flex align-items-center">
            @if(Auth::user()->photo_url)
                <img class="rounded-circle header-profile-user" src="{{ Auth::user()->photo_url }}" alt="Header Avatar">
            @else
                <img class="rounded-circle header-profile-user" src="build/images/users/avatar-1.jpg" alt="Header Avatar">
            @endif
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <!-- item-->
        @php
            $user = Auth::user();
            $displayName = trim($user->first_name . ' ' . $user->last_name);
            if (empty($displayName)) {
                $displayName = $user->name;
            }
        @endphp
        <h6 class="dropdown-header">Welcome {{ $displayName }}!</h6>
        <a class="dropdown-item fs-sm" href="company-profile"> 
            <i class="fas fa-user-circle text-muted align-middle me-1"></i>  
            <span class="align-middle">@lang('translation.profile')</span>
        </a>
        <a class="dropdown-item fs-sm" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> 
            <i class="fas fa-sign-out-alt text-muted align-middle me-1"></i>  
            <span class="align-middle" data-key="t-logout">@lang('translation.logout')</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
            </div>
        </div>
    </div>
</header>
<div class="wrapper"></div>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="bi bi-trash display-4"></i>
                    </div>
                    <div class="mt-4 fs-base">
                        <h4 class="mb-1">Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- removeCartModal -->
<div id="removeCartModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-cartmodal"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="bi bi-trash display-5"></i>
                    </div>
                    <div class="mt-4">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this product ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="remove-cartproduct">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
    integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script>
$(function() {
    function openTicketFromSearch() {
        const v = $.trim($('#search-options').val() || '');
        if (v) {
            window.open('tickets-view?id=' + encodeURIComponent(v), '_blank');
        }
    }
    
    $('#search-options').on('keydown', function(e) {
        if (e.key === 'Enter' || e.which === 13) {
            e.preventDefault();
            openTicketFromSearch();
        }
    });
    
    $('.app-search').on('submit', function(e) {
        e.preventDefault();
        openTicketFromSearch();
    });
});

$(function() {
    $('#cameraScreenshotBtn').on('click', function() {
        html2canvas(document.body, {
            scale: 2,
            backgroundColor: '#ffffff',
            allowTaint: false,
            useCORS: true,
            logging: false
        }).then(function(canvas) {
            canvas.toBlob(function(blob) {
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                var now = new Date();
                var timestamp = now.getFullYear() + '-' + 
                               (now.getMonth()+1).toString().padStart(2,'0') + '-' + 
                               now.getDate().toString().padStart(2,'0') + '_' + 
                               now.getHours().toString().padStart(2,'0') + '-' + 
                               now.getMinutes().toString().padStart(2,'0') + '-' + 
                               now.getSeconds().toString().padStart(2,'0');
                a.download = 'screenshot_' + timestamp + '.png';
                a.click();
                URL.revokeObjectURL(url);
            });
        }).catch(function(error) {
            console.error('Screenshot error:', error);
            alert('Screenshot capture failed!');
        });
    });
});

$(function() {
    function openTicketFromSearch() {
        const v = $.trim($('#search-options').val() || '');
        if (v) {
            window.open('tickets-view?id=' + encodeURIComponent(v), '_blank');
        }
    }
    
    $('#search-options').on('keydown', function(e) {
        if (e.key === 'Enter' || e.which === 13) {
            e.preventDefault();
            openTicketFromSearch();
        }
    });
    
    $('.app-search').on('submit', function(e) {
        e.preventDefault();
        openTicketFromSearch();
    });
});

$(function() {
    $('#cameraScreenshotBtn').on('click', function() {
        html2canvas(document.body, {
            scale: 2,
            backgroundColor: '#ffffff',
            allowTaint: false,
            useCORS: true,
            logging: false
        }).then(function(canvas) {
            canvas.toBlob(function(blob) {
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                var now = new Date();
                var timestamp = now.getFullYear() + '-' + 
                               (now.getMonth()+1).toString().padStart(2,'0') + '-' + 
                               now.getDate().toString().padStart(2,'0') + '_' + 
                               now.getHours().toString().padStart(2,'0') + '-' + 
                               now.getMinutes().toString().padStart(2,'0') + '-' + 
                               now.getSeconds().toString().padStart(2,'0');
                a.download = 'screenshot_' + timestamp + '.png';
                a.click();
                URL.revokeObjectURL(url);
            });
        }).catch(function(error) {
            console.error('Screenshot error:', error);
            alert('Screenshot capture failed!');
        });
    });
});

$(document).ready(function() {
    let API_BASE_URL = '{{ config('app.api_url') }}';
    API_BASE_URL = API_BASE_URL.replace(/\/$/, '');
    
    let API_URL;
    if (API_BASE_URL.includes('/api')) {
        API_URL = API_BASE_URL;
    } else {
        API_URL = API_BASE_URL + '/api';
    }
    
    let currentUserId = {{ Auth::check() ? Auth::id() : 0 }};
    
    
    // DELETE CONFIRMATION MODAL 
    function showDeleteConfirmation(ids, isMultiple = false) {
        pendingDeleteIds = ids;
        
        if ($('#deleteConfirmModal').length === 0) {
            const modalHtml = `
                <div class="modal fade zoomIn" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-md-5 pt-0">
                                <div class="text-center">
                                    <div class="text-danger">
                                        <i class="bi bi-trash display-4"></i>
                                    </div>
                                    <div class="mt-4 fs-base">
                                        <h4 class="mb-1">Are you sure ?</h4>
                                        <p class="text-muted mx-4 mb-0" id="deleteConfirmMessage">
                                            Are you sure you want to remove this Notification ?
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn w-sm btn-danger" id="confirmDeleteBtn">Yes, Delete It!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(modalHtml);
        }
        
        if (isMultiple) {
            $('#deleteConfirmMessage').text(`Are you sure you want to delete ${ids.length} notifications ?`);
        } else {
            $('#deleteConfirmMessage').text('Are you sure you want to remove this Notification ?');
        }
        
        $('#deleteConfirmModal').modal('show');
    }
    
    // TIME AGO FUNCTION 
    function getTimeAgo(dateStr) {
        if (!dateStr) return 'Just now';
        
        const date = new Date(dateStr);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) {
            if (minutes === 1) return '1 min ago';
            return minutes + ' mins ago';
        }
        const hours = Math.floor(minutes / 60);
        if (hours < 24) {
            if (hours === 1) return '1 hour ago';
            return hours + ' hours ago';
        }
        const days = Math.floor(hours / 24);
        if (days < 7) {
            if (days === 1) return 'Yesterday';
            return days + ' days ago';
        }
        return date.toLocaleDateString('en-GB');
    }
    
    //  FORMAT FULL DATE TIME 
    function getFullDateTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
   // LOAD UNREAD COUNT
function loadUnreadCount() {
    if (!currentUserId) {
        console.log('No user logged in');
        return;
    }
    
    let url = API_URL + '/notifications/unread-count?user_id=' + currentUserId;
    console.log('📊 Fetching unread count from:', url);
    
    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            console.log('📊 Unread Count Response:', response);
            
            let count = 0;
            if (response.success && typeof response.count !== 'undefined') {
                count = response.count;
            }
            
            $('.notification-badge').text(count);
            $('.notification-unread').text(count);
            
            if (count >= 0) { 
                $('.notification-badge').show();  
            }
            
            // Agar 0 hide karna hai to yeh rakho:
            // if (count > 0) {
            //     $('.notification-badge').show();
            // } else {
            //     $('.notification-badge').hide();
            // }
        },
        error: function(xhr) {
            console.error('❌ Unread Count Error:', xhr);
            $('.notification-badge').text('0');
            $('.notification-unread').text('0');
            $('.notification-badge').show(); 
        }
    });
}
    
    //  LOAD ALL NOTIFICATIONS 
    function loadNotifications() {
        if (!currentUserId) {
            console.log('No user logged in');
            return;
        }
        
        let url = API_URL + '/notifications?user_id=' + currentUserId;
        
        const $container = $('#notificationItemsTabContent .pe-2');
        $container.html('<div class="text-center py-4"><i class="ti ti-loader"></i> Loading...</div>');
        
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                console.log('📋 Notifications Response:', response);
                
                let notifications = [];
                if (response.success && response.data) {
                    notifications = response.data;
                } else if (Array.isArray(response)) {
                    notifications = response;
                }
                
                currentNotifications = notifications;
                displayNotifications(currentNotifications);
                loadUnreadCount();
            },
            error: function(xhr) {
                console.error('❌ Load Notifications Error:', xhr);
                $container.html('<div class="text-center py-4 text-danger">⚠️ Failed to load notifications</div>');
            }
        });
    }
    
    // MARK AS READ
    function markAsRead(notificationId, $element) {
        if (!currentUserId) return;
        
        let url = API_URL + '/notifications/' + notificationId + '/read?user_id=' + currentUserId;
        console.log('✓ Marking as read:', notificationId);
        
        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $element.removeClass('unread-message');
                    $element.removeClass('bg-light');
                    loadUnreadCount();
                    if ($('#page-header-notifications-dropdown').attr('aria-expanded') === 'true') {
                        loadNotifications();
                    }
                }
            },
            error: function(xhr) {
                console.error('❌ Mark as read error:', xhr);
            }
        });
    }
    
    // MARK ALL AS READ
    function markAllAsRead() {
        if (!currentUserId) return;
        
        let url = API_URL + '/notifications/mark-all-read?user_id=' + currentUserId;
        console.log('✓ Marking all as read');
        
        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    loadUnreadCount();
                    if ($('#page-header-notifications-dropdown').attr('aria-expanded') === 'true') {
                        loadNotifications();
                    }
                    updateSelectedCount(0);
                    $('#selectAllCheckbox').prop('checked', false);
                }
            },
            error: function(xhr) {
                console.error('❌ Mark all read error:', xhr);
            }
        });
    }
    
    // DELETE NOTIFICATIONS 
    function deleteNotifications(ids) {
        if (!currentUserId) return;
        
        let url = API_URL + '/notifications/delete?user_id=' + currentUserId;
        console.log('🗑️ Deleting notifications:', ids);
        
        $.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({ ids: ids, user_id: currentUserId }),
            success: function(response) {
                if (response.success) {
                    $('#deleteConfirmModal').modal('hide');
                    loadUnreadCount();
                    if ($('#page-header-notifications-dropdown').attr('aria-expanded') === 'true') {
                        loadNotifications();
                    }
                    updateSelectedCount(0);
                    $('#selectAllCheckbox').prop('checked', false);
                }
            },
            error: function(xhr) {
                console.error('❌ Delete error:', xhr);
                $('#deleteConfirmModal').modal('hide');
            }
        });
    }
    
    // DISPLAY NOTIFICATIONS
    function displayNotifications(notifications) {
        const $container = $('#notificationItemsTabContent .pe-2');
        $container.empty();
        
        if (!notifications || notifications.length === 0) {
            $container.html('<div class="text-center py-4">✨ No notifications found</div>');
            return;
        }
        
        notifications.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        
        let newNotifs = notifications.filter(n => !n.is_read);
        let oldNotifs = notifications.filter(n => n.is_read);
        
        if (newNotifs.length > 0) {
            $container.append(`<div class="notification-section"><h6 class="text-overflow text-muted fs-sm my-2 text-uppercase">📌 New (${newNotifs.length})</h6></div>`);
            newNotifs.forEach(n => $container.append(createNotificationHTML(n, true)));
        }
        
        if (oldNotifs.length > 0) {
            $container.append(`<div class="notification-section"><h6 class="text-overflow text-muted fs-sm my-2 text-uppercase">📖 Read Before</h6></div>`);
            oldNotifs.forEach(n => $container.append(createNotificationHTML(n, false)));
        }
        
        attachEvents();
    }
    
    // CREATE NOTIFICATION HTML
    function createNotificationHTML(notification, isNew) {
        const unreadClass = isNew ? 'unread-message bg-light' : '';
        
        let iconClass = 'bg-primary-subtle text-primary';
        let iconText = 'ti ti-bell';
        
        if (notification.type === 'ticket_created') {
            iconClass = 'bg-success-subtle text-success';
            iconText = 'ti ti-plus';
        } else if (notification.type === 'ticket_updated') {
            iconClass = 'bg-info-subtle text-info';
            iconText = 'ti ti-edit';
        }
        
        let userName = notification.title || 'Someone';
        let message = notification.message || '';
        
        const timeAgo = getTimeAgo(notification.created_at);
        const fullDateTime = getFullDateTime(notification.created_at);
        
        return `
            <div class="text-reset notification-item d-block dropdown-item position-relative ${unreadClass}" data-id="${notification.id}" data-ticket-id="${notification.ticket_id}">
                <div class="d-flex align-items-start">
                    <div class="avatar-xs me-3 flex-shrink-0">
                        <span class="avatar-title ${iconClass} rounded-circle fs-lg">
                            <i class='${iconText}'></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <a href="javascript:void(0);" class="notification-link text-reset text-decoration-none flex-grow-1">
                                <h6 class="mt-0 fs-md mb-1 lh-base">
                                    <strong>${escapeHtml(userName)}</strong>
                                    <span class="fw-normal">${escapeHtml(message)}</span>
                                </h6>
                                <p class="mb-0 fs-sm text-muted">Ticket #${notification.ticket_id}</p>
                            </a>
                            <div class="form-check notification-check">
                                <input class="form-check-input notification-checkbox" type="checkbox" value="${notification.id}" data-id="${notification.id}">
                            </div>
                        </div>
                        <p class="mb-0 fs-xs fw-medium text-muted mt-1" title="${fullDateTime}">
                            <i class="ti ti-clock-hour-4 me-1"></i> ${timeAgo}
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
    
    // ESCAPE HTML 
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // ATTACH EVENTS 
    function attachEvents() {
        selectedCount = 0;
        
        // Checkbox change
        $('.notification-checkbox').off('change').on('change', function() {
            const $parent = $(this).closest('.notification-item');
            const notificationId = $(this).val();
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                selectedCount++;
                if ($parent.hasClass('unread-message')) {
                    markAsRead(notificationId, $parent);
                }
            } else {
                selectedCount--;
            }
            updateSelectedCount(selectedCount);
            
            const totalCheckboxes = $('.notification-checkbox').length;
            const checkedCheckboxes = $('.notification-checkbox:checked').length;
            $('#selectAllCheckbox').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
        });
        
        // Link click - sirf read
        $('.notification-link').off('click').on('click', function(e) {
            e.preventDefault();
            const $parent = $(this).closest('.notification-item');
            const notificationId = $parent.data('id');
            
            if ($parent.hasClass('unread-message')) {
                markAsRead(notificationId, $parent);
            }
        });
        
        // Select All checkbox
        $('#selectAllCheckbox').off('change').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.notification-checkbox').each(function() {
                const $checkbox = $(this);
                const $parent = $checkbox.closest('.notification-item');
                const notificationId = $checkbox.val();
                
                if (isChecked && !$checkbox.is(':checked')) {
                    $checkbox.prop('checked', true);
                    selectedCount++;
                    if ($parent.hasClass('unread-message')) {
                        markAsRead(notificationId, $parent);
                    }
                } else if (!isChecked && $checkbox.is(':checked')) {
                    $checkbox.prop('checked', false);
                    selectedCount--;
                }
            });
            updateSelectedCount(selectedCount);
        });
    }
    
    function updateSelectedCount(count) {
        $('#select-content').text(count);
        if (count > 0) {
            $('#notification-actions').slideDown();
        } else {
            $('#notification-actions').slideUp();
        }
    }
    
    // DROPDOWN ACTIONS 
    function initDropdownActions() {
        // Mark all as read
        $('.mark-all-read-btn').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (confirm('📖 Mark all notifications as read?')) {
                markAllAsRead();
            }
        });
        
        // Delete All
        $('.delete-all-btn').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let allIds = [];
            $('.notification-checkbox').each(function() {
                allIds.push($(this).val());
            });
            if (allIds.length > 0) {
                showDeleteConfirmation(allIds, true);
            } else {
                alert('No notifications to delete');
            }
        });
        
        // Delete Selected button
        $('#deleteSelectedBtn').off('click').on('click', function() {
            let selectedIds = [];
            $('.notification-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            if (selectedIds.length > 0) {
                showDeleteConfirmation(selectedIds, true);
            } else {
                alert('Please select at least one notification');
            }
        });
        
        // Confirm delete button
        $(document).off('click', '#confirmDeleteBtn').on('click', '#confirmDeleteBtn', function() {
            if (pendingDeleteIds && pendingDeleteIds.length > 0) {
                deleteNotifications(pendingDeleteIds);
                pendingDeleteIds = [];
            }
        });
        
        $('#notification-actions').hide();
    }
    
    // INITIALIZE
    function init() {
        
        initDropdownActions();
        loadUnreadCount();
        
        $('#page-header-notifications-dropdown').on('click', function() {
            loadNotifications();
        });
        
        // setInterval(function() {
        //     loadUnreadCount();
        //     if ($('#page-header-notifications-dropdown').attr('aria-expanded') === 'true') {
        //         loadNotifications();
        //     }
        // }, 60000);
    }
    
    init();
});

// Debug helper
window.testNotifications = function() {
    let base = '{{ config('app.api_url') }}'.replace(/\/$/, '');
    if (base.includes('/api')) {
        var url = base + '/notifications';
    } else {
        var url = base + '/api/notifications';
    }
    console.log('Testing URL:', url);
    $.ajax({
        url: url,
        method: 'GET',
        success: function(r) { console.log('Success:', r); },
        error: function(e) { console.error('Error:', e); }
    });
};
</script>