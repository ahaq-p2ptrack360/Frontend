@php
    use Illuminate\Support\Facades\DB;
    
    $companyLogo = null;
    if(Auth::check() && Auth::user()->company_id) {
        try {
            $company = DB::table('company')->where('id', Auth::user()->company_id)->first();
            if($company && $company->profile) {
                // ✅ Sirf filename extract karo (folder remove karo)
                $filename = basename($company->profile);
                // ✅ Sahi URL banao
                $companyLogo = 'https://demo.p2ptrack360.com:8888/api/companies/profile-image/' . $filename;
            }
        } catch(\Exception $e) {
            // Silent fail
        }
    }
@endphp

<!-- ========== App Menu ========== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
    <a href="index" class="logo logo-dark">
    <span class="logo-sm">
        @if($companyLogo)
            <img src="{{ $companyLogo }}" alt="Company Logo" height="55" width="55" style="object-fit: contain;">
        @else
            <img src="build/images/logo-sm.png" alt="logo small" height="55">
        @endif
    </span>
    <span class="logo-lg">
        @if($companyLogo)
            <img src="{{ $companyLogo }}" alt="Company Logo" height="80" style="max-height:80px;">
        @else
            <img src="build/images/logo-dark.png" alt="Default Logo" height="55">
        @endif
    </span>
</a>
<a href="index" class="logo logo-light">
    <span class="logo-sm">
        @if($companyLogo)
            <img src="{{ $companyLogo }}" alt="Company Logo" height="55" width="55" style="object-fit: contain;">
        @else
            <img src="build/images/logo-sm.png" alt="logo small" height="55">
        @endif
    </span>
    <span class="logo-lg">
        @if($companyLogo)
            <img src="{{ $companyLogo }}" alt="Company Logo" height="80" style="max-height:80px;">
        @else
            <img src="build/images/logo-light.png" alt="Default Logo" height="55">
        @endif
    </span>
</a>
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover shadow-none"
            id="vertical-hover" aria-label="Toggle sidebar">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">@lang('translation.menu')</span></li>

                <!-- Dashboards (no permission key assumed, kept always) -->
                <!-- <li class="nav-item" data-page-group="dashboards">
                    <a class="nav-link menu-link collapsed" href="#sidebarDashboards" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ti ti-brand-google-home"></i> <span data-key="t-dashboards">@lang('translation.dashboards')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="index" class="nav-link" data-key="t-analytics" data-page="analytics">@lang('translation.analytics')</a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-ecommerce" class="nav-link" data-key="t-ecommerce" data-page="ecommerce">@lang('translation.ecommerce')</a>
                            </li>
                        </ul>
                    </div>
                </li> -->

                <li class="nav-item" data-page-group="dashboards">
    <a href="index" class="nav-link menu-link">
        <i class="ti ti-brand-google-home"></i>
        <span data-key="t-dashboards">@lang('translation.dashboards')</span>
    </a>
</li>


                <li class="menu-title"><i class="ti ti-dots"></i> <span data-key="t-apps">@lang('translation.apps')</span></li>

                <li class="nav-item" data-page="types">
                    <a href="types" class="nav-link menu-link">
                        <i class="fas fa-shapes"></i>
                        <span data-key="t-types">Types</span>
                    </a>
                </li>

                <li class="nav-item" data-page="status">
                    <a href="status" class="nav-link menu-link">
                        <i class="fas fa-arrows-rotate"></i>
                        <span data-key="t-status">Status</span>
                    </a>
                </li>

                <li class="nav-item" data-page="priority">
                    <a href="priority" class="nav-link menu-link">
                        <i class="fas fa-sort-amount-up-alt"></i>
                        <span data-key="t-priority">Priority</span>
                    </a>
                </li>

                <li class="nav-item" data-page="impact">
                    <a href="impact" class="nav-link menu-link">
                        <i class="fas fa-globe"></i>
                        <span data-key="t-impact">Impact</span>
                    </a>
                </li>

                <li class="nav-item" data-page="business_unit">
                    <a href="buisness-unit" class="nav-link menu-link">
                        <i class="fas fa-network-wired"></i>
                        <span data-key="t-business-unit">Business Unit</span>
                    </a>
                </li>

                <li class="nav-item" data-page-group="group">
                    <a href="#sidebarGroup" class="nav-link menu-link collapsed" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarGroup">
                        <i class="fas fa-people-group"></i> <span data-key="t-group">@lang('Group')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarGroup">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item" data-page="assign_group">
                                <a href="assign-group" class="nav-link" data-key="t-assign-group">@lang('Assign Group')</a>
                            </li>
                            <li class="nav-item" data-page="create_group">
                                <a href="create-group" class="nav-link" data-key="t-create-group">@lang('Create Group')</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item" data-page="vendor">
                    <a href="vendor" class="nav-link menu-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span data-key="t-vendors">Vendors</span>
                    </a>
                </li>

                <li class="nav-item" data-page="quotation">
                    <a href="qutation" class="nav-link menu-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span data-key="t-qutation">Qutation</span>
                    </a>
                </li>
                <li class="nav-item" data-page="subscription">
                    <a href="subscription" class="nav-link menu-link">
                        <i class="fas fa-credit-card"></i>
                        <span data-key="t-subscription">Subscription Package</span>
                    </a>
                </li>

                <li class="nav-item" data-page="ticket">
    <a href="ticket" class="nav-link menu-link">
    <i class="fas fa-envelope-open-text"></i>


    <span data-key="t-ticket">{{ optional(optional(session('userWithBU'))->business_unit)->ticket_represented ?? 'Ticket' }}</span>
    </a>
</li>

<li class="nav-item" data-page="report">
    <a href="report" class="nav-link menu-link">
        <i class="fas fa-chart-bar"></i>
        <span data-key="t-report">Report</span>
    </a>
</li>


                <li class="nav-item" data-page="domain">
                    <a href="domain" class="nav-link menu-link">
                        <i class="fas fa-shield-alt"></i>
                        <span data-key="t-domain">Domain</span>
                    </a>
                </li>

                <li class="nav-item" data-page="company">
                    <a href="company" class="nav-link menu-link">
                    <i class="fas fa-briefcase"></i>
                        <span data-key="t-company">Company</span>
                    </a>
                </li>

                <!-- <li class="nav-item">
    <a href="company-profile" class="nav-link menu-link">
        <i class="fas fa-id-card"></i>
        <span>Company Profile</span>
    </a>
</li> -->


                @if(Auth::user()->type =='Super Admin' )
                <li class="nav-item" >
                    <a href="roles" class="nav-link menu-link">
                    <i class="fas fa-user-shield"></i>
                        <span >Profile</span>
                    </a>
                </li>
                <li class="nav-item" >
                    <a href="permission" class="nav-link menu-link">
                        <i class="fas fa-key"></i>
                        <span >Permission</span>
                    </a>
                </li>
                @endif
                <li class="nav-item" data-page-group="user_management">
                    <a class="nav-link menu-link collapsed" href="#sidebarUI2" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarUI2">
                        <i class="fas fa-users-gear"></i><span data-key="t-pages">@lang('User Management')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarUI2">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item" data-page="user">
                                <a href="user" class="nav-link" data-key="t-user">@lang('User')</a>
                            </li>
                            <li class="nav-item" data-page="account">
                                <a href="customer" class="nav-link" data-key="t-account">@lang('Account')</a>
                            </li>
                            <li class="nav-item" data-page="designation">
                                <a href="designation" class="nav-link" data-key="t-designation">@lang('Designation')</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- Permission-driven visibility logic -->
<!-- Permission-driven visibility logic -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    (function() {
        const roleId = "{{ Auth::user()->role }}"; // ensure this gives the correct numeric role_id

        if (!roleId) {
            console.warn('No role ID available; sidebar not filtered.');
            return;
        }

        function normalize(key) {
            return key.replace(/-/g, '_').toLowerCase();
        }

        $.ajax({
            url: `{{ config('app.api_url') }}permissions/${roleId}`,
            method: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (!Array.isArray(resp) || resp.length === 0) {
                    console.warn('Permissions response empty or invalid', resp);
                    return;
                }

                const raw = resp[0];
                const parsed = {};

                // ✅ FIXED: properly closed forEach block
                Object.keys(raw).forEach(k => {
                    if (['id', 'role_id', 'created_at', 'updated_at'].includes(k)) return;
                    try {
                        parsed[k] = JSON.parse(raw[k]);
                    } catch (e) {
                        parsed[k] = raw[k];
                    }
                });

                // Hide elements based on permissions
                $('[data-page]').each(function() {
                    const $navItem = $(this);
                    const rawPage = $navItem.data('page');
                    const pageKey = normalize(rawPage);
                    const perms = parsed[pageKey];

                    if (!(perms && parseInt(perms.read) === 1)) {
                        $navItem.remove();
                    }
                });

                // Remove parent groups if empty
                $('[data-page-group]').each(function() {
                    const $group = $(this);
                    const collapseToggle = $group.find('[data-bs-toggle="collapse"]');
                    const targetSelector = collapseToggle.attr('href');
                    if (targetSelector) {
                        const $menuDropdown = $(targetSelector);
                        if ($menuDropdown.find('.nav-item').length === 0) {
                            $group.remove();
                        }
                    }
                });
            },
            error: function(xhr) {
                console.error('Failed to load permissions:', xhr.responseText);
            }
        });
    })();
</script>
