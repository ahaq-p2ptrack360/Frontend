@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<!-- Datatable CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" />
<!-- Bootstrap Toggle CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<!-- Additional CSS for export buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    body {
        font-size: 13px !important;
    }

    table th,
    .card-body table td {
        padding: 6px 8px;
        color: #6c757d;
    }

    #offcanvasRight,
    #editOffcanvas {
        width: 50% !important;
        max-width: none;
    }

    .dt-buttons {
        margin-bottom: 15px;
    }

    #customExportButtons .dt-buttons .btn {
        height: 38px;
        padding: 6px 12px;
    }

    #addNewBtn {
        height: 38px;
        padding: 6px 12px;
    }

    #customExportButtons .dt-buttons {
        margin: 0;
    }

    .dt-buttons .btn-success.dropdown-toggle::after {
        display: none !important;
    }

    div.dt-button-collection {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        min-width: 220px;
        z-index: 1050;
        animation: fadeInDropdown 0.4s ease;
        padding: 8px 0;
    }

    @keyframes fadeInDropdown {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    div.dt-button-collection .dt-button {
        padding: 12px 20px;
        background: transparent !important;
        color: #333;
        font-weight: 500;
        font-size: 13px;
        display: flex;
        align-items: center;
        margin: 0 8px;
        border-radius: 10px;
        cursor: pointer;
        user-select: none;
        transition: background 0.3s ease, color 0.3s ease, transform 0.3s ease;
    }

    div.dt-button-collection .dt-button:hover {
        background: rgba(74, 108, 247, 0.1) !important;
        color: #4a6cf7 !important;
        transform: translateX(4px);
    }

    div.dt-button-collection .dt-button i {
        margin-right: 12px;
        color: #4a6cf7;
        font-size: 14px;
    }

    /* .dataTables_wrapper {
        overflow: visible !important;
        position: relative;
        z-index: 10;
    } */

    .form-check.form-switch {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .form-check-input {
        width: 3em;
        height: 1.5em;
    }

    .form-check.form-switch .form-check-input {
    width: 3.2em;      
    height: 1.8em;     
    cursor: pointer;
}

.form-check.form-switch .form-check-input::before {
    width: 1.4em;
    height: 1.4em;
}

    #map,
    #editMap {
        width: 100%;
        height: 300px;
        margin-top: 10px;
    }

    .pac-container {
        z-index: 9999;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Customer @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <button id="addNewBtn" class="btn btn-primary py-2 px-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                        Add New
                    </button>
                    <div id="customExportButtons" class="d-flex align-items-center"></div>
                </div>
            </div>

            <div class="card-body">
                <table id="customer-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Created At</th>
                            <th>Edit</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="table-loading" style="text-align: center; padding: 20px; color: #6c757d">Loading...</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Customer</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createCustomerForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="middleName" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" placeholder="Enter Middle Name">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" placeholder="Enter Password" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="supportEmail" class="form-label">Support Email</label>
                            <input type="email" class="form-control" id="supportEmail" placeholder="Enter Support Email">
                        </div> -->
                <!-- <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="telephone" placeholder="Enter Telephone">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mobile" placeholder="Enter Mobile" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                                <label for="supportContact" class="form-label">Support Contact</label>
                                <input type="text" class="form-control" id="supportContact" placeholder="Enter Support Contact">
                            </div> -->
                <div class="col-md-6 mb-3">
                    <label for="address1" class="form-label">Address 1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address1" placeholder="Enter Address 1" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="address2" class="form-label">Address 2</label>
                            <input type="text" class="form-control" id="address2" placeholder="Enter Address 2">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="city" placeholder="Enter City" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="state" placeholder="Enter State" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="country" placeholder="Enter Country" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="zipcode" class="form-label">Zipcode</label>
                            <input type="text" class="form-control" id="zipcode" placeholder="Enter Zipcode">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="registrationNumber" class="form-label">Registration Number</label>
                            <input type="text" class="form-control" id="registrationNumber" placeholder="Enter Registration Number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control" id="website" placeholder="Enter Website URL">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="communication" class="form-label">Communication</label>
                            <input type="text" class="form-control" id="communication" placeholder="Enter Communication">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="datalines" class="form-label">Datalines</label>
                            <input type="text" class="form-control" id="datalines" placeholder="Enter Datalines">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telebox" class="form-label">Telebox</label>
                            <input type="text" class="form-control" id="telebox" placeholder="Enter Telebox">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="language" class="form-label">Language</label>
                            <input type="text" class="form-control" id="language" placeholder="Enter Language">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="licences" class="form-label">Licences</label>
                            <input type="text" class="form-control" id="licences" placeholder="Enter Licences">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subscriptionId" class="form-label">Subscription ID</label>
                            <input type="number" class="form-control" id="subscriptionId" placeholder="Enter Subscription ID">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="buisnessUnit" class="form-label">Business Unit</label>
                    <input type="text" class="form-control" id="businessUnit" placeholder="Enter Buisness Unit">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="buisnessUnit" class="form-label">File</label>
                    <input type="file" class="form-control" id="profile" >
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="map-search-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="mapSearch" placeholder="Search for a location...">
                    </div>
                </div>
                <div id="map"></div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createCustomer()">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Customer</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editCustomerForm">
            <input type="hidden" id="editCustomerId">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editFirstName" placeholder="Enter First Name" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="editMiddleName" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="editMiddleName" placeholder="Enter Middle Name">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="editLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editLastName" placeholder="Enter Last Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="editEmail" placeholder="Enter Email" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="editSupportEmail" class="form-label">Support Email</label>
                            <input type="email" class="form-control" id="editSupportEmail" placeholder="Enter Support Email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTelephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="editTelephone" placeholder="Enter Telephone">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="editMobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editMobile" placeholder="Enter Mobile" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="editSupportContact" class="form-label">Support Contact</label>
                            <input type="text" class="form-control" id="editSupportContact" placeholder="Enter Support Contact">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="editAddress1" class="form-label">Address 1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editAddress1" placeholder="Enter Address 1" required>
                </div>
                <!-- <div class="col-md-6 mb-3">
                            <label for="editAddress2" class="form-label">Address 2</label>
                            <input type="text" class="form-control" id="editAddress2" placeholder="Enter Address 2">
                        </div>-->
                <div class="col-md-6 mb-3">
                    <label for="editCity" class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editCity" placeholder="Enter City" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editState" class="form-label">State <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editState" placeholder="Enter State" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editCountry" class="form-label">Country <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editCountry" placeholder="Enter Country" required>
                </div>
                <!--<div class="col-md-6 mb-3">
                            <label for="editZipcode" class="form-label">Zipcode</label>
                            <input type="text" class="form-control" id="editZipcode" placeholder="Enter Zipcode">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editRegistrationNumber" class="form-label">Registration Number</label>
                            <input type="text" class="form-control" id="editRegistrationNumber" placeholder="Enter Registration Number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editWebsite" class="form-label">Website</label>
                            <input type="text" class="form-control" id="editWebsite" placeholder="Enter Website URL">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editCommunication" class="form-label">Communication</label>
                            <input type="text" class="form-control" id="editCommunication" placeholder="Enter Communication">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDatalines" class="form-label">Datalines</label>
                            <input type="text" class="form-control" id="editDatalines" placeholder="Enter Datalines">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTelebox" class="form-label">Telebox</label>
                            <input type="text" class="form-control" id="editTelebox" placeholder="Enter Telebox">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editLanguage" class="form-label">Language</label>
                            <input type="text" class="form-control" id="editLanguage" placeholder="Enter Language">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editLicences" class="form-label">Licences</label>
                            <input type="text" class="form-control" id="editLicences" placeholder="Enter Licences">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editSubscriptionId" class="form-label">Subscription ID</label>
                            <input type="number" class="form-control" id="editSubscriptionId" placeholder="Enter Subscription ID">
                        </div> -->
                <div class="col-md-6 mb-3">
                    <label for="editBuisnessUnit" class="form-label">Business Unit</label>
                    <input type="text" class="form-control" id="editBuisnessUnit" placeholder="Enter Buisness Unit">
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="map-search-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="editMapSearch"
                            placeholder="Search for a location...">
                    </div>
                </div>
                <div id="editMap"></div>
                <input type="hidden" id="editLatitude" name="editLatitude">
                <input type="hidden" id="editLongitude" name="editLongitude">
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateCustomer()">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!-- Datatable JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<!-- Export buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<!-- Required libraries for Excel/PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<!-- Google Maps API -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNyJWb04pByaU1CTmimoWNl3b86VV6qZ8&libraries=places&callback=initMap"
    async defer></script>

<script>
    let customerDataTable;
    let map, editMap, marker, editMarker;
    // const apiBaseUrl = "https://demo.p2ptrack360.com:8888/api";

    let permissions = null;
    const roleId = '{{ Auth::user()->role }}'; // adjust if your role field differs

    $(document).ready(function() {
        loadPermissions(roleId)
            .then(() => {
                applyCustomerPermissionUI();
                fetchCustomerTable();
            })
            .catch(err => {
                console.warn("Permissions load failed, proceeding without permission restrictions:", err);
                fetchCustomerTable();
            });
    });

    async function loadPermissions(roleId) {
        try {
            const res = await $.ajax({
                url: `{{ config('app.api_url') }}permissions/${roleId}`,
                method: 'GET',
                dataType: 'json'
            });
            permissions = Array.isArray(res) && res.length ? res[0] : res;
        } catch (e) {
            console.error("Failed to load permissions", e);
            permissions = null;
        }
    }

    function hasPermission(section, action) {
        if (!permissions || !section || !action) return false;
        try {
            const raw = permissions[section];
            if (!raw) return false;
            const parsed = typeof raw === 'object' ? raw : JSON.parse(raw);
            return parsed[action] === 1;
        } catch (e) {
            console.warn("Permission parse error", section, action, e);
            return false;
        }
    }

    function applyCustomerPermissionUI() {
        // hide Add New if no create permission
        if (!hasPermission('account', 'create')) {
            $('#addNewBtn').hide();
        } else {
            $('#addNewBtn').show();
        }
        // if no update permission, remove Edit header
        if (!hasPermission('account', 'update')) {
            $('#customer-table thead tr th').filter(function() {
                return $(this).text().trim() === 'Edit';
            }).remove();
        }
       
    }

    function fetchCustomerTable() {
        const tableEl = document.getElementById("customer-table");
        const loadingEl = document.getElementById("table-loading");

        if (!tableEl || !loadingEl) {
            console.error("#customer-table or #table-loading not found");
            return;
        }

        loadingEl.style.display = "block";

        if ($.fn.DataTable.isDataTable('#customer-table')) {
            $('#customer-table').DataTable().destroy();
            $('#customer-table tbody').empty();
        }

        $.ajax({
            url: '{{Auth::user()->company_id}}'==0? `{{ config('app.api_url') }}super_admin_customer`:`{{ config('app.api_url') }}customers/{{Auth::user()->company_id}}`,
            method: "GET",
            success: function(data) {
                const cols = [{
                        data: 'id',
                        defaultContent: '-',
                        title: 'S.No'
                    },
                    {
                        data: null,
                        title: 'Name',
                        render: function(data, type, row) {
                            return `${row.first_name || ''} ${row.last_name || ''}`.trim();
                        }
                    },
                    {
                        data: 'email',
                        defaultContent: '-',
                        title: 'Email'
                    },
                    {
                        data: 'phone_no',
                        defaultContent: '-',
                        title: 'Mobile'
                    },
                    {
                        data: 'address',
                        defaultContent: '-',
                        title: 'Address 1'
                    },
                    {
                        data: 'city',
                        defaultContent: '-',
                        title: 'City'
                    },
                    {
                        data: 'state',
                        defaultContent: '-',
                        title: 'State'
                    },
                    {
                        data: 'country',
                        defaultContent: '-',
                        title: 'Country'
                    },
                    {
                        data: 'created_at',
                        title: 'Created At',
                        render: function(data) {
                            return data ? new Date(data).toLocaleString() : '-';
                        }
                    }
                ];

                // Edit column if allowed
                if (hasPermission('account', 'update')) {
                    cols.push({
                        data: null,
                        title: 'Edit',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <a href="javascript:void(0);" 
                               class="btn btn-sm btn-primary" 
                               onclick="editCustomer(${row.id})">
                                <i class="ri-pencil-fill"></i>
                            </a>`;
                        }
                    });
                }

                // Status toggle if allowed
                
                    cols.push({
                        data: 'active',
                        title: 'Active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const checked = data == 1 ? 'checked' : '';
                            const disabled = !hasPermission('account', 'update') ? 'disabled' : '';
                            return `
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    onchange="updateCustomerStatusToggle(${row.id}, this)" ${checked} ${disabled}>
                            </div>`;
                        }
                    });
                

                customerDataTable = $('#customer-table').DataTable({
                    data: data,
                    responsive: true,
                    paging: true,
                    searching: true,
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'collection',
                        text: '<i class="ri-file-excel-2-line"></i> Export Excel',
                        className: 'btn btn-success',
                        buttons: [{
                                extend: 'copy',
                                text: '<i class="fas fa-copy"></i> Copy'
                            },
                            {
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel'
                            },
                            {
                                extend: 'csv',
                                text: '<i class="fas fa-file-csv"></i> CSV'
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="fas fa-file-pdf"></i> PDF'
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Print'
                            }
                        ]
                    }],
                    columns: cols,
                    initComplete: function() {
                        loadingEl.style.display = "none";
                        setTimeout(() => {
                            $('#customExportButtons').html('');
                            if (customerDataTable.buttons) customerDataTable.buttons().container().appendTo('#customExportButtons');
                        }, 0);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
                loadingEl.textContent = "Failed to load data.";
            }
        });
    }


    window.initMap = function() {
    const defaultLocation = { lat: 24.8607, lng: 67.0011 };

    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 13
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true
    });

        marker.addListener("dragend", () => {
            updateLocationFields(marker.getPosition(), 'create');
        });

        // Search box for create form
        const input = document.getElementById("mapSearch");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            if (!places || places.length === 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            updateLocationFields(place.geometry.location, 'create');
        });

        // Initialize edit form map
        editMap = new google.maps.Map(document.getElementById("editMap"), {
            center: defaultLocation,
            zoom: 13
        });

        editMarker = new google.maps.Marker({
            position: defaultLocation,
            map: editMap,
            draggable: true
        });

        editMarker.addListener("dragend", () => {
            updateLocationFields(editMarker.getPosition(), 'edit');
        });

        // Search box for edit form
        const editInput = document.getElementById("editMapSearch");
        const editSearchBox = new google.maps.places.SearchBox(editInput);
        editMap.controls[google.maps.ControlPosition.TOP_LEFT].push(editInput);

        editSearchBox.addListener("places_changed", () => {
            const places = editSearchBox.getPlaces();
            if (!places || places.length === 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            updateLocationFields(place.geometry.location, 'edit');
        });
    }

    function updateLocationFields(position, formType) {
        const lat = position.lat();
        const lng = position.lng();

        if (formType === 'create') {
            marker.setPosition(position);
            map.panTo(position);
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        } else {
            editMarker.setPosition(position);
            editMap.panTo(position);
            $('#editLatitude').val(lat);
            $('#editLongitude').val(lng);
        }
    }

    function updateCustomerStatusToggle(id, element) {
        const isChecked = $(element).prop('checked') ? 1 : 0;

        const form = new FormData();
        form.append("id", id);
        form.append("active", isChecked);

        $.ajax({
            url: `{{ config('app.api_url') }}updatecusstatus`,
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: function() {
                fetchCustomerTable();
                const message = isChecked ? "Customer activated successfully" : "Customer deactivated successfully";
                const type = isChecked ? "success" : "warning";
                showToast(message, type);
            },
            error: function() {
                showToast("Failed to update customer status", "danger");
                $(element).prop('checked', !isChecked);
            }
        });
    }

    function showToast(message, type = "info") {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "center",
            backgroundColor: getToastColor(type),
            close: true
        }).showToast();
    }

    function getToastColor(type) {
        switch (type) {
            case "success":
                return "#28a745";
            case "danger":
                return "#dc3545";
            case "warning":
                return "#ffc107";
            case "info":
            case "primary":
            default:
                return "#0d6efd";
        }
    }

    function createCustomer() {
    const requiredFields = {
        'firstName': $('#firstName').val().trim(),
        'lastName': $('#lastName').val().trim(),
        'email': $('#email').val().trim(),
        'mobile': $('#mobile').val().trim(),
        'address1': $('#address1').val().trim(),
        'city': $('#city').val().trim(),
        'state': $('#state').val().trim(),
        'country': $('#country').val().trim(),
        'password': $('#password').val().trim()
    };

    for (const [field, value] of Object.entries(requiredFields)) {
        if (!value) {
            showToast(`Please fill the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field`, "warning");
            return;
        }
    }

    const formData = new FormData();
    formData.append('first_name', requiredFields.firstName);
    formData.append('last_name', requiredFields.lastName);
    formData.append('email', requiredFields.email);
    formData.append('password', requiredFields.password);
    formData.append('phone_no', requiredFields.mobile);
    formData.append('address', requiredFields.address1);
    formData.append('city', requiredFields.city);
    formData.append('state', requiredFields.state);
    formData.append('country', requiredFields.country);
    formData.append('company_id', '{{Auth::user()->company_id}}');
    formData.append('bu_id', $('#businessUnit').val().trim());
    formData.append('active', 1);

    const profileFile = $('#profile')[0].files[0];
    if (profileFile) {
        formData.append('profile', profileFile);
    }

    // ✅ DIRECT URL - No variable
    $.ajax({
        url: "https://demo.p2ptrack360.com:8888/api/store_customers",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showToast("Customer created successfully", "success");
            $('#createCustomerForm')[0].reset();
            $('#offcanvasRight').offcanvas('hide');
            fetchCustomerTable();
        },
        error: function(xhr) {
            console.log("API Error:", xhr);
            let errorMsg = "Failed to create customer";
            if (xhr.responseJSON) {
                errorMsg = xhr.responseJSON.message || xhr.responseJSON.error || errorMsg;
            }
            showToast(errorMsg, "danger");
        }
    });
}


    // Similarly update your editCustomer and updateCustomer functions:
    function editCustomer(id) {
    console.log("Fetching customer with ID:", id);
    
    const apiUrl = `https://demo.p2ptrack360.com:8888/api/get_customer/${id}`;
    
    $.ajax({
        url: apiUrl,
        method: "GET",
        success: function(customer) {
            console.log("Customer data received:", customer);
            
            $('#editCustomerId').val(customer.id);
            $('#editFirstName').val(customer.first_name || '');
            $('#editLastName').val(customer.last_name || '');
            $('#editEmail').val(customer.email || '');
            $('#editMobile').val(customer.phone_no || '');
            $('#editAddress1').val(customer.address || '');
            $('#editCity').val(customer.city || '');
            $('#editState').val(customer.state || '');
            $('#editCountry').val(customer.country || '');
            
            // ✅ Yahan ID sahi karein - "editBuisnessUnit" (Buisness with 's')
            $('#editBuisnessUnit').val(customer.bu_id || '');
            
            if (customer.latitude && customer.longitude) {
                $('#editLatitude').val(customer.latitude);
                $('#editLongitude').val(customer.longitude);
                
                if (typeof editMarker !== 'undefined' && editMarker) {
                    const location = new google.maps.LatLng(
                        parseFloat(customer.latitude),
                        parseFloat(customer.longitude)
                    );
                    editMarker.setPosition(location);
                    editMap.setCenter(location);
                }
            }
            
            new bootstrap.Offcanvas('#editOffcanvas').show();
        },
        error: function(xhr) {
            console.error("Fetch error:", xhr);
            let errorMsg = "Failed to fetch customer details";
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            }
            showToast(errorMsg, "danger");
        }
    });
}

function updateCustomer() {
    const id = $('#editCustomerId').val();
    
    if (!id) {
        showToast("Customer ID not found", "danger");
        return;
    }
    
    const requiredFields = {
        'firstName': $('#editFirstName').val().trim(),
        'lastName': $('#editLastName').val().trim(),
        'email': $('#editEmail').val().trim(),
        'mobile': $('#editMobile').val().trim(),
        'address1': $('#editAddress1').val().trim(),
        'city': $('#editCity').val().trim(),
        'state': $('#editState').val().trim(),
        'country': $('#editCountry').val().trim()
    };

    for (const [field, value] of Object.entries(requiredFields)) {
        if (!value) {
            showToast(`Please fill the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field`, "warning");
            return;
        }
    }

    const formData = new FormData();
    formData.append('first_name', requiredFields.firstName);
    formData.append('last_name', requiredFields.lastName);
    formData.append('email', requiredFields.email);
    formData.append('phone_no', requiredFields.mobile);
    formData.append('address', requiredFields.address1);
    formData.append('city', requiredFields.city);
    formData.append('state', requiredFields.state);
    formData.append('country', requiredFields.country);
    formData.append('company_id', '{{Auth::user()->company_id}}');
    
    // ✅ Yahan bhi id sahi karein - "editBuisnessUnit"
    formData.append('bu_id', $('#editBuisnessUnit').val().trim() || '1');
    
    if ($('#editLatitude').val()) {
        formData.append('latitude', $('#editLatitude').val());
    }
    if ($('#editLongitude').val()) {
        formData.append('longitude', $('#editLongitude').val());
    }
    
    const profileFile = $('#editProfile')[0]?.files[0];
    if (profileFile) {
        formData.append('profile', profileFile);
    }

    const apiUrl = `https://demo.p2ptrack360.com:8888/api/update_customers/${id}`;
    
    console.log("Updating customer at URL:", apiUrl);
    console.log("Customer ID:", id);
    console.log("Business Unit Value:", $('#editBuisnessUnit').val().trim());

    $.ajax({
        url: apiUrl,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Update success:", response);
            showToast(response.message || "Customer updated successfully", "success");
            $('#editOffcanvas').offcanvas('hide');
            fetchCustomerTable();
        },
        error: function(xhr) {
            console.error("Update error:", xhr);
            let errorMsg = "Failed to update customer";
            if (xhr.responseJSON) {
                errorMsg = xhr.responseJSON.message || xhr.responseJSON.error || errorMsg;
            }
            showToast(errorMsg, "danger");
        }
    });
}
</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection