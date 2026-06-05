@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')

@auth
    @php
        $user = Auth::user();
    @endphp

    <div id="welcomeMsg"
         style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999; background: #d4edda; color: #155724; padding: 8px 20px; border-radius: 50px; font-size: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: none; align-items: center; gap: 15px;">

        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-person-circle me-1"></i>
            <strong>{{ $user->name ?? $user->email }}</strong> - Logged in
        </div>

        <!-- 🔥 Logout Button -->
        <button onclick="logoutUser()"
                style="background: #dc3545; color: white; border: none; padding: 4px 12px; border-radius: 20px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </div>

    <script>
        (function() {
            var msg = document.getElementById('welcomeMsg');
            if (!msg) return;

            // URL parameter check
            const urlParams = new URLSearchParams(window.location.search);
            const isImpersonated = urlParams.get('impersonated') === '1';

            console.log('URL parameter impersonated:', isImpersonated);

            if (isImpersonated) {
                // ✅ Login as User se aaya hai - message SHOW karo
                console.log('Login as User - message will SHOW');
                msg.style.display = 'flex';

                // URL se parameter hata do
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            } else {
                // ✅ Normal login - message BILKUL SHOW MAT KARO
                console.log('Normal login - message will NOT show');
                msg.style.display = 'none';  // Direct hide kar do, 5 second nahi wait
            }
        })();

        // 🔥 LOGOUT FUNCTION (same rahega)
        function logoutUser() {
            // CSRF token le lo
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Form data banao
            const formData = new FormData();
            formData.append('_token', token);

            fetch("{{ route('logout') }}", {
                method: "POST",
                credentials: "same-origin",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Logout response:', response);

                // localStorage aur cookies clear karo
                localStorage.removeItem('login_from_impersonation');
                document.cookie = "prev_user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    window.location.href = "{{ route('login') }}";
                }
            })
            .catch(error => {
                console.error('Logout error:', error);

                localStorage.removeItem('login_from_impersonation');
                document.cookie = "prev_user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                window.location.href = "{{ route('login') }}";
            });
        }
    </script>
@endauth

    <!-- DataTables CSS - Single Version -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <!-- Buttons CSS - Single Version -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

    <!-- Bootstrap Toggle CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Pickr Themes -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/classic.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/nano.min.css') }}" />

    <style>
        #basic_box .apexcharts-title-text {
            visibility: hidden;
            position: relative;
        }

        .custom-chart-title {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .fab-container {
            position: fixed;
            bottom: 25px;
            right: 85px;
            z-index: 1000;
        }

        .fab-button {
            background-color: #007bff;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-size: 24px;
            transition: transform 0.3s ease-in-out;
        }

        .fab-button.open {
            transform: rotate(45deg);
        }

        .fab-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: absolute;
            bottom: 70px;
            visibility: hidden;
            opacity: 0;
            transform: translateY(20px);
            transition: visibility 0.3s ease-in-out, opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .fab-actions.show {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        .fab-action {
            background-color: #6c757d;
            color: white;
            width: 50px;
            height: 50px;
            right: -5px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-size: 20px;
            border: none;
            position: relative;
        }

        .fab-action:hover {
            background-color: #5a6268;
        }

        .fab-action[data-tooltip]:hover::before {
            content: attr(data-tooltip);
            position: absolute;
            right: 60px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            white-space: nowrap;
            font-size: 14px;
            z-index: 1001;
        }

        .fab-actions.show .fab-action {
            transition: all 0.2s ease-out;
        }

        .fab-actions.show .fab-action:nth-child(1) {
            transition-delay: 0.05s;
        }

        #offcanvasForm {
            width: 25%;
        }

        .card-body.custom-scroll {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .card-body.custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .card-body.custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .card-body.custom-scroll::-webkit-scrollbar-thumb {
            background: #b0b0b0;
            border-radius: 10px;
        }

        .card-body.custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #888;
        }

        .card-body.custom-scroll {
            scrollbar-width: thin;
            scrollbar-color: #b0b0b0 #f1f1f1;
        }

        #dateFilterButtons {
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        #dateFilterButtons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            height: 28px;
            min-width: 60px;
            border-radius: 4px;
        }

        #dateFilterButtons .btn-primary {
            background-color: var(--tb-primary) !important;
            border-color: var(--tb-primary) !important;
            color: white !important;
            font-weight: 600;
        }

        .mx-n4 {
            margin-left: -0.5rem !important;
            margin-right: -0.5rem !important;
            overflow: hidden;
            height: 280px;
        }

        #all_tickets.apex-charts {
            height: 260px !important;
            max-height: 260px;
            min-height: 240px;
            position: relative;
            top: -10px;
        }

        .mt-4 {
            margin-top: 0.25rem !important;
        }

        @media (max-width: 767.98px) {
            .mx-n4 {
                height: 240px;
            }

            #all_tickets.apex-charts {
                height: 220px !important;
            }
        }

        .chart-compact-view {
            height: 250px !important;
        }

        .chart-compact-view .apexcharts-canvas {
            transform: scale(0.95);
            transform-origin: center;
        }

        /* Saved Views Styles */
        .saved-views-container {
            margin-top: 2rem;
            border-top: 1px solid #dee2e6;
            padding-top: 1.5rem;
        }

        .saved-view-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.75rem;
            background-color: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }

        .saved-view-item:hover {
            background-color: #e9ecef;
        }

        .saved-view-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            flex: 1;
        }

        .saved-view-name i {
            color: #0d6efd;
            font-size: 0.9rem;
        }

        .saved-view-name span {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .delete-view-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .delete-view-btn:hover {
            background-color: #dc3545;
            color: white;
        }

        .saved-views-loading {
            text-align: center;
            padding: 1rem;
            color: #6c757d;
        }
    </style>
</head>

<body class="dashboard-topbar-wrapper">
    <div class="row">
        <div class="col-xl-8">
            <div>
                <div class="row gy-4">
                    <!-- Total -->
                    <div class="col-sm-4 border-end-sm">
                        <div class="text-center">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-md">Total</p>
                            <h4 class="fw-semibold mb-3">
                                <span id="totaltcount">0</span>
                            </h4>

                            <div class="d-flex align-items-center justify-content-center gap-2" id="totalTicketsCard">
                                <h5 class="fs-xs mb-0">
                                    <i class="fs-sm align-middle percentage-icon"></i>
                                    <span class="percentage-text">0%</span>
                                </h5>
                                <p class="text-muted mb-0">than last week</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="col-sm-4 border-end-sm">
                        <div class="text-center">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-md">Pending</p>
                            <h4 class="fw-semibold mb-3">
                                <span id="pendingtcount">0</span>
                            </h4>

                            <div class="d-flex align-items-center justify-content-center gap-2" id="pendingTicketsCard">
                                <h5 class="fs-xs mb-0">
                                    <i class="fs-sm align-middle percentage-icon"></i>
                                    <span class="percentage-text">0%</span>
                                </h5>
                                <p class="text-muted mb-0">than last week</p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="col-sm-4">
                        <div class="text-center">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-md">Completed</p>
                            <h4 class="fw-semibold mb-3">
                                <span id="completetcount">0</span>
                            </h4>

                            <div class="d-flex align-items-center justify-content-center gap-2" id="completedTicketsCard">
                                <h5 class="fs-xs mb-0">
                                    <i class="fs-sm align-middle percentage-icon"></i>
                                    <span class="percentage-text">0%</span>
                                </h5>
                                <p class="text-muted mb-0">than last week</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                <div class="btn-group mb-3" role="group" id="dateFilterButtons">
    <button type="button" class="btn btn-outline-secondary date-filter-btn" data-filter="daily">Daily</button>
    <button type="button" class="btn btn-outline-secondary date-filter-btn" data-filter="weekly">Weekly</button>
    <button type="button" class="btn btn-outline-secondary date-filter-btn" data-filter="monthly">Monthly</button>
    <button type="button" class="btn btn-outline-secondary date-filter-btn" data-filter="quarterly">Quarterly</button>
    <button type="button" class="btn btn-outline-secondary date-filter-btn" data-filter="yearly">Yearly</button>
</div>

                    <div class="mx-n4">
                        <div id="all_tickets" data-colors='["--tb-primary", "--tb-warning"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xl-4">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card bg-success-subtle shadow-none rounded-0 border-0 dashboard-widgets-wrapper">
                        <div class="card-body" style="padding-top:7em;">
                            <div id="status_chart" data-colors='["--tb-primary", "--tb-info"]' class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <div class="row">
        <div class="row equal-card-row" id="priority-cards">
            <!-- High -->
            <div class="col-lg-4 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body p-3 d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-body-secondary text-secondary rounded fs-3xl">
                                    <i class="fas fa-rocket" title="High"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                High
                                <span class="badge bg-body-secondary text-secondary align-middle ms-1">
                                    <i class="ti ti-arrows-left-right"></i>
                                    <span class="percent">0%</span>
                                </span>
                            </p>
                            <h6 class="fw-semibold mb-0">
                                <span class="counter-value" data-priority="High" data-target="0">0</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medium -->
            <div class="col-lg-4 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body p-3 d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-body-secondary text-danger rounded fs-3xl">
                                    <i class="fas fa-yin-yang" title="Medium"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Medium
                                <span class="badge bg-body-secondary text-secondary align-middle ms-1">
                                    <i class="ti ti-arrows-left-right"></i>
                                    <span class="percent">0%</span>
                                </span>
                            </p>
                            <h6 class="fw-semibold mb-0">
                                <span class="counter-value" data-priority="Medium" data-target="0">0</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low -->
            <div class="col-lg-4 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body p-3 d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-body-secondary text-info rounded fs-3xl">
                                    <i class="fas fa-level-down-alt" title="Low"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Low
                                <span class="badge bg-body-secondary text-secondary align-middle ms-1">
                                    <i class="ti ti-arrows-left-right"></i>
                                    <span class="percent">0%</span>
                                </span>
                            </p>
                            <h6 class="fw-semibold mb-0">
                                <span class="counter-value" data-priority="Low" data-target="0">0</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card" id="contactList" style="padding-bottom: 1.4em;">
                    <div class="card-header align-items-center d-flex">
                    @php
    $ticketRepresented = null;
    $userWithBU = session('userWithBU');
    if ($userWithBU && isset($userWithBU->business_unit) && $userWithBU->business_unit->ticket_represented) {
        $ticketRepresented = $userWithBU->business_unit->ticket_represented;
    }
@endphp

<h4 class="card-title mb-0 flex-grow-1">
    {{ $ticketRepresented ?: 'Ticket' }}
</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card mt-0">
                            <table id="ticketsTable" class="display table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Company</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Impact</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h6 class="card-title mb-0 flex-grow-1">By Impact</h6>
                    </div>
                    <div class="card-body">
                        <div id="impact_chart" data-colors='["--tb-warning", "--tb-success", "--tb-danger"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-height-100">
                            <div class="card-body p-0">
                                <ul class="nav nav-tabs border-bottom-0 justify-content-center pt-3" id="ticketDashboardTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active py-1 px-2" style="white-space: nowrap; max-width: 150px; font-size: 0.85rem;" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcomingDeadlinesList" type="button" role="tab">
                                            Upcoming Deadlines
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link py-1 px-2" style="white-space: nowrap; max-width: 120px; font-size: 0.85rem;" id="contact-tab" data-bs-toggle="tab" data-bs-target="#ticketCreatorsList" type="button" role="tab">
                                            Top Creators
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link py-1 px-2" style="white-space: nowrap; max-width: 120px; font-size: 0.85rem;" id="activity-tab" data-bs-toggle="tab" data-bs-target="#reportedByList" type="button" role="tab">
                                            Reported By
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content pt-2 pb-3 px-3">
                                    <div class="tab-pane fade show active" id="upcomingDeadlinesList" role="tabpanel" aria-labelledby="upcoming-tab">
                                        <div class="d-flex align-items-center mb-2">
                                            <h5 class="card-title flex-grow-1 mb-0" style="font-size:12px;">
                                            Upcoming {{ $ticketRepresented ?: 'Ticket' }} Deadlines                                            </h5>
                                            <div class="flex-shrink-0">
                                                <a href="ticket" class="btn btn-subtle-info btn-sm">
                                                    View More <i class="ph-caret-right align-middle"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div id="upcoming-tickets" class="overflow-auto" style="max-height: 524px; font-size:12px;"></div>
                                    </div>

                                    <div class="tab-pane fade" id="ticketCreatorsList" role="tabpanel" aria-labelledby="contact-tab"></div>
                                    <div class="tab-pane fade" id="reportedByList" role="tabpanel" aria-labelledby="activity-tab"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    <div class="row">
        <div class="">
            <!-- Floating Button -->
            <div class="fab-container">
                <!-- <div id="fabButton" class="fab-button" onclick="toggleFabMenu()">
                    <i class="fas fa-plus"></i>
                </div> -->
                <div id="fabActions" class="fab-actions">
                    <button class="fab-action" data-tooltip="Filter Tickets" onclick="openFilterOffcanvas()">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>

            <!-- Offcanvas Filter Form with Saved Views - API Integrated -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasForm" aria-labelledby="offcanvasFormLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasFormLabel">Filter Tickets</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <form id="filterForm">
                        <!-- Select Account -->
                        <div class="mb-3">
                            <label for="selectAccount" class="form-label text-muted fw-bold">Select Account</label>
                            <select class="form-select" name="selectAccount" id="selectAccount">
                                <option value="">Select Account</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Date Range</label>
                            <input type="text" class="form-control" id="dateRange" placeholder="Select Start & End Date">
                        </div>

                        <button type="button" onclick="applyFilter()" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-funnel me-2"></i>Apply Filter
                        </button>

                        <button type="button" onclick="resetFilter()" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-repeat me-2"></i>Reset Filter
                        </button>
                    </form>

                    <!-- Saved Views Section - API Driven -->
                    <div class="saved-views-container">
                        <h6 class="text-muted fw-bold mb-3">
                            <i class="bi bi-bookmarks me-2"></i>Saved Views
                        </h6>
                        <div id="savedViewsList" class="saved-views-list">
                            <!-- Saved views will be dynamically loaded from API -->
                            <div class="text-muted text-center py-3" id="noSavedViewsMsg">
                                <i class="bi bi-info-circle me-1"></i>No saved views yet
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                <h6 class="card-title flex-grow-1 mb-0">
    {{ optional(optional(session('userWithBU'))->business_unit)->ticket_represented ?? 'Ticket' }} by Business Unit
</h6>
                    <div class="dropdown card-header-dropdown flex-shrink-0">
                        <a class="text-reset dropdown-btn fs-md" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted">This Month<i class="ti ti-chevron-down ms-1"></i></span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="issueTypesChart" data-colors='["--tb-primary", "--tb-primary-rgb, 0.75"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <!--end col-->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h6 class="card-title flex-grow-1 mb-0">Resolved Ratio</h6>
                    <div class="flex-shrink-0">
                        <div class="dropdown">
                            <button class="btn shadow-none btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots fs-md"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="completed_tickets" data-colors='["--tb-secondary"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h6 class="card-title flex-grow-1 mb-0">By Priority</h6>
                    <div class="flex-shrink-0">
                        <div class="dropdown">
                            <button class="btn shadow-none btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots fs-md"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="progress bg-light" id="priorityProgress"></div>
                    <div class="d-flex mt-2 align-items-center fs-md" id="priorityLabels"></div>
                </div>
            </div>
        </div>
        <!--end col-->

        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                    <h6 class="card-title flex-grow-1 mb-0">Trend By Types</h6>
                    <div class="dropdown card-header-dropdown flex-shrink-0">
                        <a class="text-reset dropdown-btn fs-md" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted">This Month<i class="ti ti-chevron-down ms-1"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">This Month</a>
                            <a class="dropdown-item" href="#">Last Month</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="trendbytype" data-colors='["--tb-primary", "--tb-primary-rgb, 0.75"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <!-- SAVE VIEW MODAL - API Integrated -->
    <div class="modal fade" id="saveViewModal" tabindex="-1" aria-labelledby="saveViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveViewModalLabel">
                        <i class="bi bi-bookmark-star me-2"></i> Save Current View
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="saveViewForm">
                        <!-- View Name Field -->
                        <div class="mb-3">
                            <label for="viewName" class="form-label fw-semibold">View Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="viewName" name="filter_name" placeholder="e.g., High Priority Tickets" required>
                            <div class="invalid-feedback">Please enter a view name</div>
                        </div>

                        <!-- Hidden Fields - Current Filter Values -->
                        <input type="hidden" id="save_from_date" name="from_date">
                        <input type="hidden" id="save_to_date" name="to_date">
                        <input type="hidden" id="save_timeline" name="timeline">
                        <input type="hidden" id="save_company" name="company">
                        <input type="hidden" id="save_bu" name="bu">
                        <input type="hidden" id="save_customers" name="customers">
                        <input type="hidden" id="save_assign_to" name="assign_to">
                        <input type="hidden" id="save_created_by" name="created_by">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveCurrentView()">
                        <i class="bi bi-save me-1"></i> Save View
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


    <!-- ApexCharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <!-- SweetAlert2 - For Success/Error Messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // GLOBAL VARIABLES
        let total_tickets = [];
        let currentFilteredTickets = [];
        let currentFilterType = 'assigned_to_me';
        let table;
        let chartColumnDistributedChart;
        let trendTypeChart = null;
        let ticketTypeChart = null;
        let statusChartInstance = null;
        let impactChartInstance = null;
        let currentDateFilter = null;
        let accountChoices;
        let savedViews = [];
        let selectedYear = new Date().getFullYear();


        // AUTO LOGIN SETUP
        setTimeout(function() {
            const userEmail = "{{ Auth::check() ? Auth::user()->email : '' }}";
            const userpassword = "{{ Auth::check() ? Auth::user()->two_factor_secret : '' }}";

            let formFilled = false;

            const iframe = document.querySelector('iframe');
            if (iframe) {
                try {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    const emailField = iframeDoc.getElementById('email');
                    const passwordField = iframeDoc.getElementById('password');
                    const loginBtn = iframeDoc.getElementById('loginbtn');

                    if (emailField && passwordField) {
                        emailField.value = userEmail;
                        passwordField.value = userpassword;
                        console.log('Form filled in iframe');

                        if (loginBtn) {
                            loginBtn.click();
                            formFilled = true;
                        } else {
                            const form = iframeDoc.querySelector('form');
                            if (form) {
                                form.submit();
                                formFilled = true;
                            }
                        }
                    }
                } catch (e) {
                    console.log('Cannot access iframe content:', e.message);
                }
            }

            if (!formFilled) {
                const mainEmail = document.getElementById('email');
                const mainpassword = document.getElementById('password');
                const mainLoginBtn = document.getElementById('loginbtn');

                if (mainEmail && mainpassword) {
                    mainEmail.value = userEmail;
                    mainpassword.value = userpassword;
                    console.log('Form filled in main page');

                    if (mainLoginBtn) {
                        mainLoginBtn.click();
                    } else {
                        const form = document.querySelector('form');
                        if (form) {
                            form.submit();
                        }
                    }
                }
            }
        }, 1500);

        // DOCUMENT READY
        $(document).ready(function () {

            // Initialize Choices.js for Account dropdown
            accountChoices = new Choices("#selectAccount", {
                removeItemButton: true,
                allowHTML: true,
                searchEnabled: true,
                placeholder: true,
                itemSelectText: 'Press to select',
            });

            // Initialize Flatpickr
            flatpickr("#dateRange", {
    mode: "range",
    dateFormat: "d M, Y",
    locale: {
        rangeSeparator: " to "
    },
    placeholder: "Select date range",
    onChange: function(selectedDates, dateStr, instance) {
        console.log("Date selected:", dateStr);
        
        if (selectedDates.length === 2) {
            let startDate = selectedDates[0];
            let endDate = selectedDates[1];
            endDate.setHours(23, 59, 59, 999);
            
            console.log("Filtering tickets from:", startDate, "to", endDate);
            console.log("Total tickets available:", total_tickets.length);
            
            // Filter tickets based on date range
            let filteredByDate = total_tickets.filter(ticket => {
                let ticketDate = new Date(ticket.created_at);
                return ticketDate >= startDate && ticketDate <= endDate;
            });
            
            console.log("Filtered tickets count:", filteredByDate.length);
            
            // Store globally filtered tickets
            globallyFilteredTickets = filteredByDate;
            
            if (filteredByDate.length > 0) {
                // Update all charts and table
                updateAllCharts(filteredByDate);
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Filter Applied!',
                    text: `Showing ${filteredByDate.length} tickets`,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'No Tickets Found',
                    text: 'No tickets in this date range',
                    timer: 1500,
                    showConfirmButton: false
                });
                // Show empty data
                updateAllCharts([]);
            }
        }
    }
});
            // Load initial tickets
            $('#ticket_profilediv').css('display', 'flex');
            loadInitialTickets();

            // Load Accounts by Company
            loadAccountsByCompany();

            // Date filter buttons
            setupDateFilterButtons();

            // Load Saved Views from API
            loadSavedViews();

            // View Name Input Validation Remove
            $('#viewName').on('input', function() {
                $(this).removeClass('is-invalid');
            });

            // Ticket profile change
            $('#ticket_profile').on('change', function () {
    const selected = this.value;
    let filteredtickets = total_tickets;
    const currentUserId = "{{ Auth::user()->id }}";

    console.log("Current User ID (number):", parseInt(currentUserId));

    if (selected === "createdby_me") {
        // ✅ String comparison
        filteredtickets = total_tickets.filter(t => String(t.created_by) === String(currentUserId));
        console.log("Created by me tickets:", filteredtickets.length);
    } else if (selected === "assignto_me") {
        // ✅ Number OR String comparison dono try karo
        filteredtickets = total_tickets.filter(t => {
            const ticketAssignedTo = String(t.assigned_to);
            const userId = String(currentUserId);
            const match = ticketAssignedTo === userId;
            if(match) {
                console.log("Match found - Ticket ID:", t.id, "Assigned to:", t.assigned_to);
            }
            return match;
        });
        console.log("Assigned to me tickets:", filteredtickets.length);
    } else if (selected === "reportedby_me") {
        filteredtickets = total_tickets.filter(t => String(t.reported_by) === String(currentUserId));
        console.log("Reported by me tickets:", filteredtickets.length);
    } else if (selected === "all") {
        filteredtickets = total_tickets;
        console.log("All tickets:", filteredtickets.length);
    }

    // Agar filtered tickets zero hain to warning do
    if(filteredtickets.length === 0 && selected !== "all") {
        console.warn("⚠️ No tickets found for this filter!");
        // Optional: Show message to user
        // alert(`No tickets found for "${selected}" filter`);
    }

    currentFilteredTickets = filteredtickets;
    updateAllCharts(filteredtickets);

    // Agar date filter active hai
    if (currentDateFilter) {
        let dateFilteredData = filterDataByDateRange(filteredtickets, currentDateFilter);
        updateAllCharts(dateFilteredData);
    }
});

            $('#offcanvasForm').on('shown.bs.offcanvas', function () {
                loadSavedViews();
            });
        });

        // INITIAL TICKETS LOAD
function loadInitialTickets() {
    let url = '{{Auth::user()->company_id}}' == 0
        ? `{{ config('app.api_url') }}tickets`
        : `{{ config('app.api_url') }}dashboard/get_all_tickets/{{Auth::user()->id}}`;

    console.log("Loading from URL:", url);

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            total_tickets = response;
            console.log("Total tickets loaded:", total_tickets.length);

            // Check assigned_to values
            if(total_tickets.length > 0) {
                console.log("First ticket assigned_to:", total_tickets[0].assigned_to);
                console.log("Current user ID:", "{{ Auth::user()->id }}");
            }

            let assignedToMe = total_tickets.filter(t => String(t.assigned_to) === String("{{ Auth::user()->id }}"));
            console.log("Assigned to me tickets count:", assignedToMe.length);

            currentFilteredTickets = assignedToMe.length > 0 ? assignedToMe : total_tickets;

            // Table force refresh karo
            if ($.fn.DataTable.isDataTable('#ticketsTable')) {
                $('#ticketsTable').DataTable().destroy();
            }

            updateAllCharts(currentFilteredTickets);

            setTimeout(function() {
                const profileDropdown = document.getElementById('ticket_profile');
                if (profileDropdown) {
                    profileDropdown.value = 'assignto_me';
                }
            }, 100);
        },
        error: function(xhr) {
            console.error("Error loading tickets:", xhr);
            console.log("Response text:", xhr.responseText);
        }
    });
}

        // LOAD ACCOUNTS BY COMPANY
        function loadAccountsByCompany() {
            const userCompanyId = "{{ Auth::user()->company_id }}";

            if (!userCompanyId || userCompanyId === '0' || userCompanyId === '') {
                console.log("Super Admin detected - Loading all accounts");
                loadAllAccounts();
                return;
            }

            const apiUrl = `https://demo.p2ptrack360.com:8888/api/customers/${userCompanyId}`;

            console.log("Loading accounts for company ID:", userCompanyId);

            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    let accountsData = response;

                    if (!Array.isArray(accountsData)) {
                        accountsData = [accountsData];
                    }

                    let choices = accountsData.map(account => ({
                        value: account.id.toString(),
                        label: `${account.first_name || ''} ${account.last_name || ''} ${account.email ? '- ' + account.email : ''}`.trim()
                    }));

                    accountChoices.clearChoices();
                    accountChoices.setChoices(choices, 'value', 'label', true);

                    if (accountsData.length === 0) {
                        console.log("No accounts found for this company");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading accounts:", error);

                    accountChoices.clearChoices();
                    accountChoices.setChoices([
                    ], 'value', 'label', true);
                }
            });
        }

        // LOAD ALL ACCOUNTS (Super Admin)
        function loadAllAccounts() {
            const apiUrl = "https://demo.p2ptrack360.com:8888/api/customers";

            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("All accounts loaded:", response.length);

                    let choices = response.map(account => ({
                        value: account.id.toString(),
                        label: `${account.first_name || ''} ${account.last_name || ''} - Company: ${account.company_id || 'N/A'}`
                    }));

                    accountChoices.clearChoices();
                    accountChoices.setChoices(choices, 'value', 'label', true);
                },
                error: function(xhr) {
                    console.error("Error loading all accounts:", xhr);
                    accountChoices.clearChoices();
                    accountChoices.setChoices([
                    ], 'value', 'label', true);
                }
            });
        }

        // HELPER FUNCTION - FORMAT DATE
        function formatDate(date) {
            if (!date) return '';
            let d = new Date(date);
            let year = d.getFullYear();
            let month = String(d.getMonth() + 1).padStart(2, '0');
            let day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // APPLY FILTER (Opens Save Modal First)
        window.applyFilter = function(skipModal = false) {
            console.log("Applying filters...");

            let accountId = $('#selectAccount').val();
            let dateRange = $('#dateRange').val();

            let startDate = null, endDate = null;
            if (dateRange) {
                let dates = dateRange.split(" to ");
                if (dates.length >= 1) {
                    startDate = new Date(dates[0]);
                    endDate = dates.length > 1 ? new Date(dates[1]) : new Date(dates[0]);
                    startDate.setHours(0, 0, 0, 0);
                    endDate.setHours(23, 59, 59, 999);
                }
            }

            let filtered = total_tickets.filter(ticket => {
                let isValid = true;

                // Account filter
                if (isValid && accountId && accountId !== '') {
                    if (ticket.reported_by && ticket.reported_by.toString() !== accountId.toString()) {
                        isValid = false;
                    }
                }

                // Date range filter
                if (isValid && startDate && endDate) {
                    let ticketDate = new Date(ticket.created_at);
                    if (ticketDate < startDate || ticketDate > endDate) {
                        isValid = false;
                    }
                }

                return isValid;
            });

            console.log(`Found ${filtered.length} tickets after filtering`);

            if (filtered.length > 0) {
                // Save current filter values to hidden fields
                $('#save_from_date').val(startDate ? formatDate(startDate) : '');
                $('#save_to_date').val(endDate ? formatDate(endDate) : '');
                $('#save_timeline').val(currentDateFilter || '');
                $('#save_company').val("{{ Auth::user()->company_id }}");
                $('#save_bu').val('');
                $('#save_customers').val(accountId || '');
                $('#save_assign_to').val('');
                $('#save_created_by').val("{{ Auth::user()->id }}");

                // Only show save modal if not skipped (for applying saved views)
                if (!skipModal) {
                    closeOffcanvas();
                    // toggleFabMenu();

                    // Show save view modal before applying filter
                    setTimeout(function() {
                        var saveModal = new bootstrap.Modal(document.getElementById('saveViewModal'));
                        saveModal.show();
                    }, 500);
                } else {
                    // Directly apply filter
                    updateAllCharts(filtered);
                    closeOffcanvas();
                    // toggleFabMenu();
                }
            } else {
                alert("No tickets found in selected criteria.");
            }
        };

        // SAVE CURRENT VIEW
        window.saveCurrentView = function() {
            let viewName = $('#viewName').val().trim();

            if (!viewName) {
                $('#viewName').addClass('is-invalid');
                return;
            } else {
                $('#viewName').removeClass('is-invalid');
            }

            // Collect form data
            let formData = $('#saveViewForm').serialize();

            // Show loading state
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // API call to save filter
            $.ajax({
                url: '{{ config("app.api_url") }}dashboard/save_filters',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.close();

                    // Hide modal
                    var saveModal = bootstrap.Modal.getInstance(document.getElementById('saveViewModal'));
                    saveModal.hide();

                    // Reset form
                    $('#saveViewForm')[0].reset();
                    $('#viewName').val('');

                    // Reload saved views
                    loadSavedViews();

                    // Apply the filter after saving
                    let accountId = $('#selectAccount').val();
                    let dateRange = $('#dateRange').val();

                    let startDate = null, endDate = null;
                    if (dateRange) {
                        let dates = dateRange.split(" to ");
                        if (dates.length >= 1) {
                            startDate = new Date(dates[0]);
                            endDate = dates.length > 1 ? new Date(dates[1]) : new Date(dates[0]);
                        }
                    }

                    let filtered = total_tickets.filter(ticket => {
                        let isValid = true;
                        if (accountId && accountId !== '') {
                            if (ticket.reported_by && ticket.reported_by.toString() !== accountId.toString()) {
                                isValid = false;
                            }
                        }
                        if (startDate && endDate) {
                            let ticketDate = new Date(ticket.created_at);
                            if (ticketDate < startDate || ticketDate > endDate) {
                                isValid = false;
                            }
                        }
                        return isValid;
                    });

                    updateAllCharts(filtered);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'View saved successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    console.error("Error saving view:", xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to save view. Please try again.',
                    });
                }
            });
        };

        // LOAD SAVED VIEWS FROM API
        function loadSavedViews() {
            const userId = "{{ Auth::user()->id }}";
            const apiUrl = `{{ config('app.api_url') }}dashboard/get_filter/${userId}`;

            // Show loading state
            const container = document.getElementById('savedViewsList');
            if (container) {
                container.innerHTML = '<div class="saved-views-loading"><i class="bi bi-arrow-repeat me-1"></i>Loading saved views...</div>';
            }

            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    savedViews = response;
                    renderSavedViews();
                },
                error: function(xhr) {
                    console.error("Error loading saved views:", xhr);
                    savedViews = [];

                    if (container) {
                        container.innerHTML = '<div class="text-muted text-center py-3"><i class="bi bi-exclamation-circle me-1"></i>Failed to load saved views</div>';
                    }
                }
            });
        }

        // RENDER SAVED VIEWS IN OFFCANVAS
        function renderSavedViews() {
            const container = document.getElementById('savedViewsList');
            if (!container) return;

            if (!savedViews || savedViews.length === 0) {
                container.innerHTML = '<div class="text-muted text-center py-3" id="noSavedViewsMsg"><i class="bi bi-info-circle me-1"></i>No saved views yet</div>';
                return;
            }

            let html = '';
            savedViews.forEach(view => {
                html += `
                    <div class="saved-view-item" data-view-id="${view.id}">
                        <div class="saved-view-name" onclick="applySavedView('${view.id}')">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>${escapeHtml(view.filter_name)}</span>
                        </div>
                        <button class="delete-view-btn" onclick="deleteSavedView('${view.id}', event)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // APPLY SAVED VIEW
        window.applySavedView = function(viewId) {
            // Show loading
            Swal.fire({
                title: 'Loading...',
                text: 'Applying view',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const apiUrl = `{{ config('app.api_url') }}dashboard/view_filter/${viewId}`;

            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(view) {
                    Swal.close();

                    // Set form values
                    if (accountChoices && view.customers) {
                        accountChoices.setChoiceByValue(view.customers.toString());
                    }

                    // Format date range for display
                    if (view.from_date && view.to_date) {
                        let fromDate = new Date(view.from_date);
                        let toDate = new Date(view.to_date);

                        let formattedFrom = fromDate.toLocaleDateString('en-GB', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        }).replace(/ /g, ' ');

                        let formattedTo = toDate.toLocaleDateString('en-GB', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        }).replace(/ /g, ' ');

                        $('#dateRange').val(`${formattedFrom} to ${formattedTo}`);
                    } else {
                        $('#dateRange').val('');
                    }

                    // Set hidden fields
                    $('#save_from_date').val(view.from_date || '');
                    $('#save_to_date').val(view.to_date || '');
                    $('#save_timeline').val(view.timeline || '');
                    $('#save_customers').val(view.customers || '');

                    // Apply date filter if timeline exists
                    if (view.timeline) {
                        currentDateFilter = view.timeline;
                        document.querySelectorAll('.date-filter-btn').forEach(btn => {
                            if (btn.dataset.filter === view.timeline) {
                                btn.classList.remove('btn-outline-secondary');
                                btn.classList.add('btn-primary');
                            } else {
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-outline-secondary');
                            }
                        });
                    }

                    // Apply filter without showing save modal
                    window.applyFilter(true);

                    Swal.fire({
                        icon: 'success',
                        title: 'Applied!',
                        text: `View "${view.filter_name}" applied successfully`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    console.error("Error applying view:", xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to apply view',
                    });
                }
            });
        };

        // DELETE SAVED VIEW
        window.deleteSavedView = function(viewId, event) {
            event.stopPropagation();

            Swal.fire({
                title: 'Delete View?',
                text: "Are you sure you want to delete this saved view?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const apiUrl = `{{ config('app.api_url') }}dashboard/delete_filter/${viewId}`;

                    $.ajax({
                        url: apiUrl,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            loadSavedViews();

                            Swal.fire(
                                'Deleted!',
                                response.message || 'View has been deleted.',
                                'success'
                            );
                        },
                        error: function(xhr) {
                            console.error("Error deleting view:", xhr);
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Failed to delete view',
                                'error'
                            );
                        }
                    });
                }
            });
        };

        // ESCAPE HTML HELPER
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // RESET FILTER
        window.resetFilter = function() {
    // Reset account
    if (accountChoices) {
        accountChoices.setChoiceByValue('');
    }

    // Reset date range
    $('#dateRange').val('');

    // Reset hidden fields
    $('#save_from_date').val('');
    $('#save_to_date').val('');
    $('#save_timeline').val('');
    $('#save_customers').val('');

    // Reset date filter buttons
    currentDateFilter = null;
    document.querySelectorAll('.date-filter-btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-secondary');
    });

    // ✅ Reset to "Assigned to Me" default
    let assignedtome = total_tickets.filter(t => t.assigned_to == '{{Auth::user()->id}}');
    currentFilteredTickets = assignedtome.length > 0 ? assignedtome : total_tickets;

    // ✅ Complete update karo - charts aur table dono
    updateAllCharts(currentFilteredTickets);

    // Dropdown reset
    setTimeout(function() {
        const profileDropdown = document.getElementById('ticket_profile');
        if (profileDropdown) {
            profileDropdown.value = 'assignto_me';
        }
    }, 100);

    closeOffcanvas();
};

        // OFF CANVAS FUNCTIONS
        // window.toggleFabMenu = function() {
        //     const fabButton = document.getElementById('fabButton');
        //     const fabActions = document.getElementById('fabActions');
        //     if (fabButton && fabActions) {
        //         fabButton.classList.toggle('open');
        //         fabActions.classList.toggle('show');
        //     }
        // };

        window.openFilterOffcanvas = function() {
            let offcanvasEl = document.getElementById('offcanvasForm');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
            offcanvas.show();
            // toggleFabMenu();
        };

        function closeOffcanvas() {
            let offcanvasEl = document.getElementById('offcanvasForm');
            let offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
            if (offcanvas) {
                offcanvas.hide();
            }
        }

        function filterDataByDateRange(data, filterType) {
    let filteredData = [...data];
    const now = new Date();

    if (filterType === 'daily') {
        // ✅ SELECTED YEAR KE MONTH KA DATA FILTER KARO
        const year = selectedYear;
        const month = now.getMonth();

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0, 23, 59, 59);

        filteredData = data.filter(ticket => {
            const createdDate = new Date(ticket.created_at);
            return createdDate >= firstDayOfMonth && createdDate <= lastDayOfMonth;
        });
    }
    else if (filterType === 'weekly') {
        const year = selectedYear;
        const firstDayOfYear = new Date(year, 0, 1);
        const lastDayOfYear = new Date(year, 11, 31, 23, 59, 59);

        filteredData = data.filter(ticket => {
            const createdDate = new Date(ticket.created_at);
            return createdDate >= firstDayOfYear && createdDate <= lastDayOfYear;
        });
    }
    else if (filterType === 'monthly') {
        const year = selectedYear;
        const firstDayOfYear = new Date(year, 0, 1);
        const lastDayOfYear = new Date(year, 11, 31, 23, 59, 59);

        filteredData = data.filter(ticket => {
            const createdDate = new Date(ticket.created_at);
            return createdDate >= firstDayOfYear && createdDate <= lastDayOfYear;
        });
    }
    else if (filterType === 'quarterly') {
        // Quarterly mein bhi selected year ka data do
        const year = selectedYear;
        const firstDayOfYear = new Date(year, 0, 1);
        const lastDayOfYear = new Date(year, 11, 31, 23, 59, 59);

        filteredData = data.filter(ticket => {
            const createdDate = new Date(ticket.created_at);
            return createdDate >= firstDayOfYear && createdDate <= lastDayOfYear;
        });
    }

    else if (filterType === 'yearly') {
        // Yearly mein koi filter nahi - saara data do
        filteredData = data;
    }

    return filteredData;
}

function updateGraphWithFilter(filterType) {
    currentDateFilter = filterType;

    if (filterType !== 'yearly' && window.lastSelectedYear) {
        selectedYear = window.lastSelectedYear;
        console.log("Using selected year:", selectedYear);
    }

    let filteredData = filterDataByDateRange(currentFilteredTickets, filterType);

    // Graph update karo
    alltickets(filteredData);

    // Button active styles
    document.querySelectorAll('.date-filter-btn').forEach(btn => {
        if (btn.dataset.filter === filterType) {
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-primary');
        } else {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        }
    });
}

function resetDateFilter() {
    currentDateFilter = null;
    document.querySelectorAll('.date-filter-btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-secondary');
    });

    // ✅ Current filtered tickets ka graph update karo
    alltickets(currentFilteredTickets);
}

        function setupDateFilterButtons() {
    document.querySelectorAll('.date-filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            if (currentDateFilter === filterType) {
                resetDateFilter();
            } else {
                updateGraphWithFilter(filterType);
            }
        });
    });
}

        // UPDATE ALL CHARTS (Rest of your chart functions remain exactly the same)
        function updateAllCharts(filteredtickets) {
            currentFilteredTickets = filteredtickets;
            statuschart(filteredtickets);
            impactchart(filteredtickets);
            semiRadialChart(filteredtickets);
            updateTicketCounters(filteredtickets);
            alltickets(filteredtickets);
            updatePriorityCounts(filteredtickets);
            fetchtickets(filteredtickets);
            renderTicketTypeChart(filteredtickets);
            renderUpcomingTickets(filteredtickets, "upcoming-tickets");
            renderTopCreators(filteredtickets);
            renderReportedBy(filteredtickets);
            renderTypeChart(filteredtickets);
            renderPriorityProgress(filteredtickets);
        }

        // STATUS CHART
        function statuschart(total_tickets) {
            const statusCounts = {};
            total_tickets.forEach(item => {
                const status = item.status_title  || 'Unknown';
                statusCounts[status] = (statusCounts[status] || 0) + 1;
            });

            const categories = Object.keys(statusCounts);
            const counts = Object.values(statusCounts);
            const ticketLabel = @json(optional(session('userWithBU'))->ticket_represented ?? 'Ticket');

            if (window.statusChartInstance) {
                window.statusChartInstance.destroy();
            }

            var options = {
                series: [{
                    name: 'Tickets',
                    data: counts
                }],
                chart: {
                    height: 380,
                    type: 'bar',
                    toolbar: { show: false },
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            const selectedStatus = categories[config.dataPointIndex];
                            const filteredTickets = total_tickets.filter(ticket => ticket.status_title === selectedStatus);
                            updateAllCharts(filteredTickets);
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '50%',
                    }
                },
                dataLabels: { enabled: true },
                xaxis: {
                    categories: categories,
                    labels: { rotate: -45 },
                    tickPlacement: 'on'
                },
                yaxis: {
                    title: { text: 'Number of ' + ticketLabel },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.25,
                        opacityFrom: 0.85,
                        opacityTo: 0.85,
                        stops: [50, 0, 100]
                    },
                }
            };

            document.querySelector("#status_chart").innerHTML = "";
            window.statusChartInstance = new ApexCharts(document.querySelector("#status_chart"), options);
            window.statusChartInstance.render();
        }

        // IMPACT CHART
        function impactchart(data) {
            const impactCounts = {};
            data.forEach(item => {
                const impact = item.impact_title  || 'Unknown';
                impactCounts[impact] = (impactCounts[impact] || 0) + 1;
            });

            const labels = Object.keys(impactCounts);
            const counts = Object.values(impactCounts);

            if (window.impactChartInstance) {
                window.impactChartInstance.destroy();
            }

            var options = {
                series: counts,
                chart: {
                    width: 300,
                    type: 'pie',
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            const selectedImpact = labels[config.dataPointIndex];
                            const filteredTickets = data.filter(ticket => ticket.impact_title === selectedImpact);
                            updateAllCharts(filteredTickets);
                        }
                    }
                },
                labels: labels,
                legend: { position: 'bottom' },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return " (" + counts[opts.seriesIndex] + ")";
                    }
                },
                colors: [
                    "#4682B4", "#00CED1", "#7CB9E8", "#38BDF8",
                    "#0EA5E9", "#0284C7", "#005A9C", "#0000CD", "#000080"
                ]
            };

            document.querySelector("#impact_chart").innerHTML = "";
            window.impactChartInstance = new ApexCharts(document.querySelector("#impact_chart"), options);
            window.impactChartInstance.render();
        }

        // TICKET TYPE CHART (BU Chart)
        function renderTicketTypeChart(tickets) {
            const buCounts = {};
            tickets.forEach(ticket => {
                const buName = ticket.bu_name  || "Unknown Business Unit";
                buCounts[buName] = (buCounts[buName] || 0) + 1;
            });

            const sortedEntries = Object.entries(buCounts).sort((a, b) => b[1] - a[1]);
            const categories = sortedEntries.map(entry => entry[0]);
            const counts = sortedEntries.map(entry => entry[1]);

            const chartConfig = {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: { show: false },
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            const selectedBU = categories[config.dataPointIndex];
                            const filteredTickets = tickets.filter(ticket => ticket.bu_name === selectedBU);
                            updateAllCharts(filteredTickets);
                        }
                    },
                },
                series: [{
                    name: 'Tickets',
                    data: counts
                }],
                colors: ['var(--tb-primary)'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return val; },
                    style: { fontSize: '13px', fontWeight: '600', colors: ['#fff'] }
                },
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { rotate: -45, style: { fontSize: '11px' } }
                },
                yaxis: {
                    show: true,
                    title: { text: 'Number of {{ session("userWithBU")->ticket_represented ?? "Ticket" }}' }
                },
                grid: { borderColor: 'rgba(0,0,0,0.1)' },
                legend: { position: 'bottom', horizontalAlign: 'center' },
                fill: { opacity: 1 },
                tooltip: {
                    y: {
                        formatter: function(val, { dataPointIndex }) {
                            const buName = categories[dataPointIndex];
                            return `<b>${buName}</b><br>${val} tickets`;
                        }
                    }
                }
            };

            const chartElement = document.getElementById('issueTypesChart');
            if (chartElement) {
                if (ticketTypeChart) {
                    ticketTypeChart.destroy();
                }
                ticketTypeChart = new ApexCharts(chartElement, chartConfig);
                ticketTypeChart.render();
            }
        }

        // TREND BY TYPE CHART
        function renderTypeChart(jsonData) {
            if (!Array.isArray(jsonData)) return;

            const types = ["Hardware", "Software", "IT Services", "Maintenance", "Consulting",
                "Support", "Logistics", "Operations", "Retail", "Wholesale"];

            const counts = {};
            types.forEach(type => {
                counts[type] = jsonData.filter(ticket => ticket.type_title === type).length;
            });

            const sortedEntries = Object.entries(counts).sort((a, b) => b[1] - a[1]);
            const sortedCategories = sortedEntries.map(entry => entry[0]);
            const sortedValues = sortedEntries.map(entry => entry[1]);

            const colorPalette = ["#4682B4", "#00CED1", "#7CB9E8", "#38BDF8", "#0EA5E9",
                "#0284C7", "#005A9C", "#0000CD", "#000080", "#191970"];

            const sortedColors = sortedCategories.map((category, index) => colorPalette[index % colorPalette.length]);

            var options = {
                series: [{
                    name: "Tickets",
                    data: sortedValues
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            const selectedType = sortedCategories[config.dataPointIndex];
                            const filteredTickets = jsonData.filter(ticket => ticket.type_title === selectedType);
                            updateAllCharts(filteredTickets);
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                        distributed: true,
                        dataLabels: { position: 'center', maxItems: 10 }
                    }
                },
                colors: sortedColors,
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return val; },
                    style: { fontSize: "14px", fontWeight: "600", colors: ["#fff"] },
                    offsetX: 0
                },
                xaxis: {
                    categories: sortedCategories,
                    labels: { show: true, style: { fontSize: '12px', fontWeight: 500 } },
                    min: 0
                },
                yaxis: {
                    labels: { show: true, style: { fontSize: '13px', fontWeight: 500 } }
                },
                tooltip: {
                    y: {
                        formatter: function (val, opts) {
                            return sortedCategories[opts.dataPointIndex] + ": " + val + " tickets";
                        }
                    }
                },
                legend: { show: false },
                grid: {
                    borderColor: '#e7e7e7',
                    row: { colors: ['#f3f3f3', 'transparent'], opacity: 0.5 }
                },
                states: {
                    hover: {
                        filter: { type: 'lighten', value: 0.1 }
                    }
                }
            };

            const chartElement = document.querySelector("#trendbytype");
            if (chartElement) {
                if (trendTypeChart) {
                    trendTypeChart.destroy();
                }
                trendTypeChart = new ApexCharts(chartElement, options);
                trendTypeChart.render();
            }
        }

        // SEMI RADIAL CHART (Resolved Ratio)
        function semiRadialChart(data) {
            const totalTickets = data.length;
            const completedTickets = data.filter(item => item.status_id === 'Completed').length;
            const percentage = totalTickets > 0 ? Math.round((completedTickets / totalTickets) * 100) : 0;

            var options = {
                series: [percentage],
                chart: {
                    type: 'radialBar',
                    offsetY: -20,
                    sparkline: { enabled: true }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5,
                            dropShadow: { enabled: true, top: 2, left: 0, color: '#444', opacity: 1, blur: 2 }
                        },
                        dataLabels: {
                            name: { show: false },
                            value: { offsetY: -2, fontSize: '22px' }
                        }
                    }
                },
                grid: { padding: { top: -10 } },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                    },
                },
                labels: ['Completed %'],
            };

            var chart = new ApexCharts(document.querySelector("#completed_tickets"), options);
            chart.render();
        }

        // Helper function to get week number (1-52/53)
function getWeekNumber(date) {
    const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
    const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
    return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
}

function getQuarterNumber(date) {
    const month = date.getMonth();
    return Math.floor(month / 3) + 1;
}

        // ALL TICKETS CHART
function alltickets(ticketData) {
    let createdCounts = [];
    let completedCounts = [];
    let categories = [];

    const ticketLabels = @json(optional(session('userWithBU'))->ticket_represented ?? 'Ticket');
    window.originalTicketData = ticketData;

    // ✅ DAILY - SELECTED YEAR USE KARO
    if (currentDateFilter === 'daily') {
        const now = new Date();
        const year = selectedYear;
        const month = now.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Arrays initialize
        createdCounts = Array(daysInMonth).fill(0);
        completedCounts = Array(daysInMonth).fill(0);

        // Categories array - SELECTED YEAR KE SAATH
        categories = [];
        for (let day = 1; day <= daysInMonth; day++) {
            const formattedDate = `${day}-${month + 1}-${year}`;
            categories.push(formattedDate);
        }

        // Count tickets - SIRF SELECTED YEAR KE
        ticketData.forEach(ticket => {
            const ticketDate = new Date(ticket.created_at);
            if (ticketDate.getMonth() === month && ticketDate.getFullYear() === year) {
                const day = ticketDate.getDate() - 1;
                if (day >= 0 && day < daysInMonth) {
                    createdCounts[day]++;
                }
            }

            if (ticket.completed_date) {
                const completedDate = new Date(ticket.completed_date);
                if (completedDate.getMonth() === month && completedDate.getFullYear() === year) {
                    const day = completedDate.getDate() - 1;
                    if (day >= 0 && day < daysInMonth) {
                        completedCounts[day]++;
                    }
                }
            }
        });
    }

    // ✅ WEEKLY - SELECTED YEAR USE KARO
    else if (currentDateFilter === 'weekly') {
        const year = selectedYear;

        const weeklyCreatedData = {};
        const weeklyCompletedData = {};

        ticketData.forEach(ticket => {
            const createdDate = new Date(ticket.created_at);
            if (createdDate.getFullYear() === year) {
                const weekNumber = getWeekNumber(createdDate);
                if (!weeklyCreatedData[weekNumber]) {
                    weeklyCreatedData[weekNumber] = 0;
                }
                weeklyCreatedData[weekNumber]++;
            }

            if (ticket.completed_date) {
                const completedDate = new Date(ticket.completed_date);
                if (completedDate.getFullYear() === year) {
                    const weekNumber = getWeekNumber(completedDate);
                    if (!weeklyCompletedData[weekNumber]) {
                        weeklyCompletedData[weekNumber] = 0;
                    }
                    weeklyCompletedData[weekNumber]++;
                }
            }
        });

        // Unhi weeks ko filter karo jahan data ho
        const allWeeksWithData = new Set();

        Object.keys(weeklyCreatedData).forEach(week => {
            allWeeksWithData.add(parseInt(week));
        });

        Object.keys(weeklyCompletedData).forEach(week => {
            allWeeksWithData.add(parseInt(week));
        });

        const sortedWeeks = Array.from(allWeeksWithData).sort((a, b) => a - b);

        createdCounts = Array(sortedWeeks.length).fill(0);
        completedCounts = Array(sortedWeeks.length).fill(0);

        // ✅ Categories - SELECTED YEAR KE SAATH
        categories = [];
        sortedWeeks.forEach((week, index) => {
            categories.push(`Week ${week}, ${year}`);

            if (weeklyCreatedData[week]) {
                createdCounts[index] = weeklyCreatedData[week];
            }
            if (weeklyCompletedData[week]) {
                completedCounts[index] = weeklyCompletedData[week];
            }
        });
    }

    // ✅ MONTHLY - SELECTED YEAR USE KARO
    else if (currentDateFilter === 'monthly') {
        const year = selectedYear;

        // Arrays initialize - 12 months
        createdCounts = Array(12).fill(0);
        completedCounts = Array(12).fill(0);

        // ✅ Categories - SELECTED YEAR KE SAATH
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        categories = monthNames.map(month => `${month} ${year}`);

        ticketData.forEach(ticket => {
            const createdDate = new Date(ticket.created_at);
            if (createdDate.getFullYear() === year) {
                const month = createdDate.getMonth();
                createdCounts[month]++;
            }

            if (ticket.completed_date) {
                const completedDate = new Date(ticket.completed_date);
                if (completedDate.getFullYear() === year) {
                    const month = completedDate.getMonth();
                    completedCounts[month]++;
                }
            }
        });
    }

    else if (currentDateFilter === 'quarterly') {
    const year = selectedYear;

    // Sirf 3 quarters ke liye arrays (Q1, Q2, Q3)
    createdCounts = Array(3).fill(0);  // 3 quarters
    completedCounts = Array(3).fill(0); // 3 quarters

    // Sirf 3 quarters ke categories
    categories = [`Q1 (Jan-Mar) ${year}`, `Q2 (Apr-Jun) ${year}`, `Q3 (Jul-Sep) ${year}`];

    ticketData.forEach(ticket => {
        const createdDate = new Date(ticket.created_at);
        if (createdDate.getFullYear() === year) {
            const quarter = getQuarterNumber(createdDate) - 1; // 0-based index

            // Sirf Q1, Q2, Q3 ko count karo (quarter 0,1,2)
            if (quarter >= 0 && quarter <= 2) {
                createdCounts[quarter]++;
            }
        }

        if (ticket.completed_date) {
            const completedDate = new Date(ticket.completed_date);
            if (completedDate.getFullYear() === year) {
                const quarter = getQuarterNumber(completedDate) - 1;
                // Sirf Q1, Q2, Q3 ko count karo
                if (quarter >= 0 && quarter <= 2) {
                    completedCounts[quarter]++;
                }
            }
        }
    });
}

    // ✅ YEARLY
    else if (currentDateFilter === 'yearly') {
        const yearsSet = new Set();

        ticketData.forEach(ticket => {
            if (ticket.created_at) {
                const year = new Date(ticket.created_at).getFullYear();
                yearsSet.add(year);
            }
        });

        // Sort years
        const sortedYears = Array.from(yearsSet).sort((a, b) => a - b);

        // Arrays initialize
        createdCounts = Array(sortedYears.length).fill(0);
        completedCounts = Array(sortedYears.length).fill(0);

        ticketData.forEach(ticket => {
            if (ticket.created_at) {
                const year = new Date(ticket.created_at).getFullYear();
                const yearIndex = sortedYears.indexOf(year);
                if (yearIndex !== -1) {
                    createdCounts[yearIndex]++;
                }
            }

            if (ticket.completed_date) {
                const year = new Date(ticket.completed_date).getFullYear();
                const yearIndex = sortedYears.indexOf(year);
                if (yearIndex !== -1) {
                    completedCounts[yearIndex]++;
                }
            }
        });

        categories = sortedYears.map(year => year.toString());
    }

    // ✅ DEFAULT (No filter) - Monthly view with ALL data (no year filter)
    else {
        createdCounts = Array(12).fill(0);
        completedCounts = Array(12).fill(0);

        ticketData.forEach(ticket => {
            if (ticket.created_at) {
                let month = new Date(ticket.created_at).getMonth();
                createdCounts[month]++;
            }
            if (ticket.completed_date) {
                let month = new Date(ticket.completed_date).getMonth();
                completedCounts[month]++;
            }
        });

        categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    }

    // ✅ CHART RENDER - YEH PART SAME RAHEGA
    let chartColumnDistributedColors = ["#0d6efd", "#ffc107"];

    var options = {
        series: [{
            name: ticketLabels + ' ' + 'Created',
            type: 'column',
            data: createdCounts
        }, {
            name: ticketLabels + ' ' + 'Completed',
            type: 'line',
            data: completedCounts
        }],
        chart: {
            height: 300,
            type: 'line',
            toolbar: { show: false },
            events: {
                dataPointSelection: function (event, chartContext, config) {
                    const clickedIndex = config.dataPointIndex;

                    // ✅ AGAR YEARLY FILTER HAI TO SELECTED YEAR SAVE KARO
                    if (currentDateFilter === 'yearly') {
                        const selectedYearFromChart = parseInt(categories[clickedIndex]);
                        selectedYear = selectedYearFromChart;
                        window.lastSelectedYear = selectedYearFromChart;
                        console.log("Year selected:", selectedYearFromChart); // Debug
                    }

                    const filteredTickets = window.originalTicketData.filter(ticket => {
                        const ticketDate = new Date(ticket.created_at);

                        if (currentDateFilter === 'daily') {
                            const year = ticketDate.getFullYear();
                            const month = ticketDate.getMonth();
                            const day = ticketDate.getDate();
                            const selectedDate = categories[clickedIndex]; // "13-2-2026"
                            const [selectedDay, selectedMonth, selectedYear] = selectedDate.split('-').map(Number);
                            return day === selectedDay && month === selectedMonth - 1 && year === selectedYear;
                        }
                        else if (currentDateFilter === 'weekly') {
                            const weekNumber = getWeekNumber(ticketDate);
                            const selectedWeek = parseInt(categories[clickedIndex].split(' ')[1].replace(',', ''));
                            return weekNumber === selectedWeek;
                        }
                        else if (currentDateFilter === 'monthly') {
                            const month = ticketDate.getMonth();
                            return month === clickedIndex;
                        }
                        else if (currentDateFilter === 'yearly') {
                            const year = ticketDate.getFullYear();
                            const selectedYear = parseInt(categories[clickedIndex]);
                            return year === selectedYear;
                        }
                        return false;
                    });

                    if (filteredTickets.length > 0) {
                        updateAllCharts(filteredTickets);
                    }
                }
            }
        },
        stroke: { width: [0, 2], curve: 'smooth' },
        plotOptions: {
            bar: {
                columnWidth: '70%',
                borderRadius: 4,
            },
        },
        colors: chartColumnDistributedColors,
        dataLabels: { enabled: false },
        legend: { show: true, position: 'top' },
        grid: {
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
        },
        yaxis: { title: { text: "Number of " + ticketLabels } },
        xaxis: {
            categories: categories,
            labels: {
                rotate: (currentDateFilter === 'daily' || currentDateFilter === 'weekly') ? -45 : 0,
                style: {
                    fontSize: (currentDateFilter === 'daily' || currentDateFilter === 'weekly') ? '10px' : '11px'
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val, { seriesIndex, dataPointIndex }) {
                    if (seriesIndex === 0) {
                        return val + ' tickets created';
                    } else {
                        return val + ' tickets completed';
                    }
                }
            }
        }
    };

    if (chartColumnDistributedChart) {
        chartColumnDistributedChart.destroy();
    }

    chartColumnDistributedChart = new ApexCharts(
        document.querySelector("#all_tickets"),
        options
    );
    chartColumnDistributedChart.render();
}

        // TICKET COUNTERS
        function updateTicketCounters(tickets) {
            const total = tickets.length;
            const pending = tickets.filter(t => t.status_title?.toLowerCase() === "pending").length;
            const completed = tickets.filter(t => t.status_title?.toLowerCase() === "completed").length;

            document.getElementById("totaltcount").textContent = total;
            document.getElementById("pendingtcount").textContent = pending;
            document.getElementById("completetcount").textContent = completed;

            const totalPercent = 100;
            const pendingPercent = total > 0 ? ((pending / total) * 100).toFixed(2) : 0;
            const completedPercent = total > 0 ? ((completed / total) * 100).toFixed(2) : 0;

            function setCardUI(cardId, value) {
                const card = document.getElementById(cardId);
                const percentText = card.querySelector(".percentage-text");
                const icon = card.querySelector(".percentage-icon");

                percentText.textContent = value + "%";

                if (value > 50) {
                    percentText.className = "percentage-text text-success";
                    icon.className = "ri-arrow-up-line fs-sm align-middle text-success percentage-icon";
                } else if (value > 0 && value <= 50) {
                    percentText.className = "percentage-text text-warning";
                    icon.className = "ri-arrow-right-up-line fs-sm align-middle text-warning percentage-icon";
                } else {
                    percentText.className = "percentage-text text-danger";
                    icon.className = "ri-arrow-down-line fs-sm align-middle text-danger percentage-icon";
                }
            }

            setCardUI("totalTicketsCard", totalPercent);
            setCardUI("pendingTicketsCard", pendingPercent);
            setCardUI("completedTicketsCard", completedPercent);
        }

        // PRIORITY COUNTS
        function updatePriorityCounts(ticketData) {
            let counts = { High: 0, Medium: 0, Low: 0 };

            ticketData.forEach(ticket => {
                if (ticket.priority_title && counts.hasOwnProperty(ticket.priority_title)) {
                    counts[ticket.priority_title]++;
                }
            });

            document.querySelector('[data-priority="High"]').setAttribute('data-target', counts.High);
            document.querySelector('[data-priority="Medium"]').setAttribute('data-target', counts.Medium);
            document.querySelector('[data-priority="Low"]').setAttribute('data-target', counts.Low);

            document.querySelectorAll('.counter-value').forEach(el => {
                el.innerText = el.getAttribute('data-target');
            });

            if (window.PriorityCards) {
                window.PriorityCards.recalc();
            }
        }

        // PRIORITY PROGRESS
        function renderPriorityProgress(jsonData) {
            if (!Array.isArray(jsonData)) return;

            const counts = {};
            jsonData.forEach(item => {
                const key = item.priority_id || "Unknown";
                counts[key] = (counts[key] || 0) + 1;
            });
            const total = jsonData.length;

            const progressDiv = document.getElementById("priorityProgress");
            const labelsDiv = document.getElementById("priorityLabels");
            progressDiv.innerHTML = "";
            labelsDiv.innerHTML = "";

            const colorPalette = ["#0d6efd", "#1e90ff", "#00bfff", "#5bc0de", "#007bff", "#339af0"];

            const priorities = Object.keys(counts);
            const colorMap = {};
            priorities.forEach((priority, index) => {
                colorMap[priority] = colorPalette[index % colorPalette.length];
            });

            progressDiv.className = "progress";
            progressDiv.style.height = "25px";

            Object.entries(counts).forEach(([priority, count]) => {
                const percent = ((count / total) * 100).toFixed(1);

                const bar = document.createElement("div");
                bar.className = "progress-bar progress-bar-animated progress-bar-striped";
                bar.role = "progressbar";
                bar.style.width = percent + "%";
                bar.style.backgroundColor = colorMap[priority];
                bar.style.color = "#fff";
                bar.style.fontWeight = "bold";
                bar.style.fontSize = "12px";
                bar.style.display = "flex";
                bar.style.alignItems = "center";
                bar.style.justifyContent = "center";
                bar.setAttribute("aria-valuenow", percent);
                bar.setAttribute("aria-valuemin", "0");
                bar.setAttribute("aria-valuemax", "100");
                bar.textContent = percent + "%";
                progressDiv.appendChild(bar);

                const label = document.createElement("p");
                label.className = "mb-0 me-3 d-flex align-items-center";
                label.innerHTML = `<span style="display:inline-block;width:12px;height:12px;background:${colorMap[priority]};border-radius:2px;margin-right:6px;"></span> ${priority} (${count})`;
                labelsDiv.appendChild(label);
            });
        }

        // DATA TABLE
        function fetchtickets(ticketData) {
    console.log("fetchtickets called with:", ticketData.length, "tickets"); // Debug line

    if (!$.fn.DataTable.isDataTable('#ticketsTable')) {
        table = $('#ticketsTable').DataTable({
            responsive: true,
            paging: true,
            searching: false,
            dom: 'frtip',  
            data: ticketData,
            columns: [
                { data: 'id' },
                { data: 'company_name' },
                { data: 'title' },
                { data: 'description' },
                { data: 'status_title' },
                { data: 'priority_title' },
                { data: 'impact_title' },
                { data: 'created_at' }
            ]
        });
    } else {
        table.clear();
        table.rows.add(ticketData);
        table.draw();
        console.log("Table refreshed with", ticketData.length, "rows"); // Debug line
    }
}

        // UPCOMING TICKETS
        function renderUpcomingTickets(ticketList, containerId) {
            const container = document.getElementById(containerId);
            const today = new Date();

            const upcoming = ticketList.filter(t => {
                const d = new Date(t.due_date);
                return (
                    d >= today &&
                    d.getMonth() === today.getMonth() &&
                    d.getFullYear() === today.getFullYear()
                );
            });

            container.innerHTML = "";

            if (upcoming.length === 0) {
                container.innerHTML = `<p class="text-muted">No upcoming tickets this month ✅</p>`;
                return;
            }

            upcoming.forEach(ticket => {
                const d = new Date(ticket.due_date);
                const day = d.toLocaleDateString("en-US", { weekday: "short" });
                const date = d.toLocaleDateString("en-US", { day: "2-digit", month: "short" });

                container.innerHTML += `
                    <div class="d-flex bg-body-secondary rounded mb-2">
                        <div class="flex-shrink-0 w-md py-2 px-3 text-center border-end">
                            <p class="mb-1 text-muted fs-sm">${date}</p>
                            <h6 class="mb-0">${day}</h6>
                        </div>
                        <div class="flex-grow-1 px-3 py-2 overflow-hidden">
                            <h6>${ticket.title} - #${ticket.id}</h6>
                            <p class="text-muted fs-sm text-truncate mb-0">${ticket.description || "--"}</p>
                        </div>
                    </div>
                `;
            });
        }

        // TOP CREATORS
        function renderTopCreators(data) {
            const counts = {};
            data.forEach(ticket => {
                const name = `${ticket.created_byfname || ''} ${ticket.created_bylname || ''}`.trim();
                if (!counts[name]) {
                    counts[name] = { count: 0, id: ticket.created_by, name: name };
                }
                counts[name].count++;
            });

            const sorted = Object.values(counts).sort((a, b) => b.count - a.count);
            const top7 = sorted.slice(0, 7);
            const container = document.getElementById("ticketCreatorsList");
            container.innerHTML = "";
            const ticketLabel = "{{ session('userWithBU')->ticket_represented ?? 'Ticket' }}";

            top7.forEach(user => {
                container.innerHTML += `
                    <div class="d-flex gap-2 mb-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-body-secondary rounded">
                                <img src="build/images/brands/user.png" alt="" class="avatar-xxs">
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6>${user.count} <small class="text-muted fw-normal">/${ticketLabel}</small></h6>
                            <p class="text-muted fs-md mb-0">${user.name}</p>
                        </div>
                    </div>
                `;
            });
        }

        // REPORTED BY
        function renderReportedBy(data) {
            const counts = {};
            data.forEach(ticket => {
                const name = ticket.reported_by_name || `${ticket.reported_byfname || ''} ${ticket.reported_bylname || ''}`.trim();

                if (!counts[name]) {
                    counts[name] = { count: 0, id: ticket.reported_by, name: name };
                }
                counts[name].count++;
            });

            const sorted = Object.values(counts).sort((a, b) => b.count - a.count);
            const top7 = sorted.slice(0, 5);
            const container = document.getElementById("reportedByList");
            container.innerHTML = "";

            top7.forEach(user => {
                container.innerHTML += `
                    <div class="d-flex gap-2 mb-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-body-secondary rounded">
                                <img src="build/images/brands/user.png" alt="" class="avatar-xxs">
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6>${user.count} <small class="text-muted fw-normal">/{{ $ticketRepresented ?: 'Ticket' }}</small></h6>
                            <p class="text-muted fs-md mb-0">${user.name}</p>
                        </div>
                    </div>
                `;
            });
        }

        // PRIORITY CARDS OBSERVER
        (function () {
            const PRIORITIES = ["High", "Medium", "Low"];

            function readValue(el) {
                const dt = el.getAttribute("data-target");
                if (dt !== null && dt !== undefined && dt !== "") {
                    const n = Number(dt);
                    return Number.isFinite(n) ? n : 0;
                }
                const t = el.textContent.trim().replace(/[^\d.-]/g, "");
                const n = Number(t);
                return Number.isFinite(n) ? n : 0;
            }

            function setBadgeState(badge, icon, state) {
                const classMap = {
                    up: { badge: "badge bg-success-subtle text-success", icon: "ti ti-arrow-up-right" },
                    down: { badge: "badge bg-danger-subtle text-danger", icon: "ti ti-arrow-down-right" },
                    flat: { badge: "badge bg-info-subtle text-info", icon: "ti ti-arrows-left-right" },
                    neutral: { badge: "badge bg-body-secondary text-secondary", icon: "ti ti-arrows-left-right" },
                };
                const cfg = classMap[state] || classMap.neutral;
                badge.className = cfg.badge;
                icon.className = cfg.icon;
            }

            function recalc() {
                const values = {};
                PRIORITIES.forEach(p => {
                    const counter = document.querySelector(`.counter-value[data-priority="${p}"]`);
                    values[p] = counter ? readValue(counter) : 0;
                });

                const total = PRIORITIES.reduce((acc, p) => acc + (values[p] || 0), 0);

                PRIORITIES.forEach(p => {
                    const counter = document.querySelector(`.counter-value[data-priority="${p}"]`);
                    if (!counter) return;

                    const cardBody = counter.closest(".card-body");
                    const percentSpan = cardBody.querySelector(".percent");
                    const badge = cardBody.querySelector(".badge");
                    const icon = badge.querySelector("i");

                    const val = values[p] || 0;
                    const percent = total > 0 ? (val / total) * 100 : 0;
                    if (percentSpan) percentSpan.textContent = percent.toFixed(2) + "%";

                    const prevValStr = counter.getAttribute("data-prev-value");
                    const prevVal = prevValStr == null ? null : Number(prevValStr);

                    if (total === 0) {
                        setBadgeState(badge, icon, "neutral");
                    } else if (prevVal == null) {
                        setBadgeState(badge, icon, "flat");
                    } else if (val > prevVal) {
                        setBadgeState(badge, icon, "up");
                    } else if (val < prevVal) {
                        setBadgeState(badge, icon, "down");
                    } else {
                        setBadgeState(badge, icon, "flat");
                    }

                    counter.setAttribute("data-prev-value", String(val));
                });
            }

            const observer = new MutationObserver((mutations) => {
                if (observer._t) clearTimeout(observer._t);
                observer._t = setTimeout(recalc, 30);
            });

            PRIORITIES.forEach(p => {
                const node = document.querySelector(`.counter-value[data-priority="${p}"]`);
                if (node) {
                    observer.observe(node, {
                        characterData: true,
                        subtree: true,
                        childList: true,
                        attributes: true,
                        attributeFilter: ["data-target"]
                    });
                }
            });

            window.PriorityCards = { recalc, set: function(values) {
                PRIORITIES.forEach(p => {
                    if (values.hasOwnProperty(p)) {
                        const counter = document.querySelector(`.counter-value[data-priority="${p}"]`);
                        if (!counter) return;
                        const v = Number(values[p]) || 0;
                        counter.setAttribute("data-target", String(v));
                        counter.textContent = String(v);
                    }
                });
                recalc();
            }};
        })();

        // COMPATIBILITY FUNCTIONS
        window.accounts = loadAccountsByCompany;
        window.filterallTickets = window.applyFilter;
    </script>
@endsection
