@extends('layouts.master')
@section('title') @lang('translation.users') @endsection
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Users @endslot
@endcomponent

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
        width: 55% !important;
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
        background: transparent;
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
        background: rgba(74, 108, 247, 0.1);
        color: #4a6cf7;
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

    .custom-export-btn {
        padding: 3px 10px !important;
    }

#users-table .form-check-input {
    width: 3.2em;
    height: 1.8em;
    cursor: pointer;
    background-color: #cfd4da;
    border-color: #cfd4da;
}

#users-table .form-check-input:checked {
    background-color: #5a58eb;
    border-color: #5a58eb;
}

#users-table .form-check-input:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.btn-sm.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-sm.btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
}

/* Modal styling */
#viewUserModal .form-label {
    color: #495057;
    margin-bottom: 0.25rem;
}

#viewUserModal p {
    color: #212529;
    margin-bottom: 1rem;
    min-height: 24px;
}

</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <button id="addNewBtn" class="btn btn-primary py-2 px-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                        Add New User
                    </button>
                    <div id="customExportButtons" class="d-flex align-items-center"></div>
                </div>
            </div>

            <div class="card-body">
                <table id="users-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Business Unit</th>
                            <th>Designation</th>
                            <th>Company</th>
                            <th>Created At</th>
                            <th>View</th>
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
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create User</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createUserForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="firstName" name="first_name"
                        placeholder="Enter First Name" required>
                </div>
                <div class="col-md-4">
                    <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter Last Name"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="username" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="username" name="email" placeholder="Enter Email"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="primaryEmail" class="form-label">Primary Email</label>
                    <input type="email" class="form-control" id="email" name="email_verified_at"
                        placeholder="Enter Primary Email">
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter Password" required>
                </div>
                <div class="col-md-4">
                    <label for="businessUnit" class="form-label">Business Unit</label>
                    <select class="form-control" id="businessUnit" name="bu_id">
                        <option value="">Select Business Unit</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="designation" class="form-label">Designation</label>
                    <select class="form-control" id="designation" name="designation_id">
                        <option value="">Select Designation</option>
                        <option value="1">Designation</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="">Roles</label>
                    <select class="form-select" id="role">
                        <option value="">Select Role</option>
                    </select>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createUser()">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit User</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editUserForm">
            <input type="hidden" id="editUserId" name="editUserId" />
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="editFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editFirstName" name="first_name"
                        placeholder="Enter First Name" required>
                </div>
                <div class="col-md-6">
                    <label for="editLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editLastName" name="last_name"
                        placeholder="Enter Last Name" required>
                </div>
                <div class="col-md-6">
                    <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="editUsername" name="email" placeholder="Enter Email"
                        required>
                </div>
                <div class="col-md-6">
                    <label for="editPrimaryEmail" class="form-label">Primary Email</label>
                    <input type="email" class="form-control" id="editEmail" name="email_verified_at"
                        placeholder="Enter Primary Email">
                </div>
                <div class="col-md-6">
                    <label for="editPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="editPassword" name="password"
                        placeholder="Enter New Password">
                </div>
                <div class="col-md-6">
                    <label for="editBusinessUnit" class="form-label">Business Unit</label>
                    <select class="form-control" id="editBusinessUnit" name="bu_id">
                        <option value="">Select Business Unit</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="editDesignation" class="form-label">Designation</label>
                    <select class="form-control" id="editDesignation" name="designation_id">
                        <option value="">Select Designation</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="editCompany" class="form-label">Company</label>
                    <input type="text" class="form-control" id="editCompany" name="company" placeholder="Enter Company">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - View User -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="editOffcanvasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOffcanvasLabel">View User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">First Name:</label>
                        <p id="viewFirstName" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Name:</label>
                        <p id="viewLastName" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email:</label>
                        <p id="viewEmail" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Primary Email:</label>
                        <p id="viewPrimaryEmail" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Business Unit:</label>
                        <p id="viewBusinessUnit" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Designation:</label>
                        <p id="viewDesignation" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company:</label>
                        <p id="viewCompany" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status:</label>
                        <p id="viewStatus" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Created At:</label>
                        <p id="viewCreatedAt" class="form-control-plaintext border-bottom pb-2">-</p>
                    </div>
                    <input type="hidden" name="" id="hiddenpassword">
                </div>
            </div>
            <!-- <div class="modal-footer">             
                <a href="#" onclick="loginUser(event)"  class="btn btn-success"> <i class="ri-login-box-line"></i> Login as User</a>
                <form id="logout-form" action="{{ route('logout') }}"  method="POST" class="d-none">
                            @csrf
                </form>
                <button type="button" class="btn btn-primary" onclick="openEditFromView()" id="viewUpdateBtn"> <i class="ri-pencil-fill"></i> Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div> -->
            <div class="modal-footer">             
    <a href="#" 
       id="loginAsUserBtn" 
       onclick="loginUser(event)" 
       class="btn btn-success">
        <i class="ri-login-box-line"></i> Login as User
    </a>
    
    <button type="button" class="btn btn-primary" onclick="openEditFromView()" id="viewUpdateBtn">
        <i class="ri-pencil-fill"></i> Update
    </button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
        </div>
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

<script>
    let usersDataTable;
    let permissions = null;
    // const roleId = '{{ Auth::check() ? Auth::user()->role : '' }}';
    let roleSelect;
    let currentViewingUserId = null;
    let businessUnitsCache = {};
    let designationsCache = {};
    @auth
        roleId = '{{ Auth::user()->role }}';
        companyId = '{{ Auth::user()->company_id }}';  
    @else
        console.warn('User not authenticated');
    @endauth
    //  let companyId = '';  

// Load all business units at once
function loadAllBusinessUnits() {
    $.ajax({
        url: '{{Auth::user()->company_id}}' == 0 ? "{{ config('app.api_url') }}business_units" : "{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
        method: "GET",
        success: function(data) {
            data.forEach(function(unit) {
                businessUnitsCache[unit.id] = unit.name;
            });
            console.log("Business Units:", businessUnitsCache);
        }
    });
}

// Load all designations at once
function loadAllDesignations() {
    $.ajax({
        url: "{{Auth::user()->company_id}}" == 0 ? "{{ config('app.api_url') }}designations" : "{{ config('app.api_url') }}designations/company/{{Auth::user()->company_id}}",
        method: "GET",
        success: function(response) {
            const data = response.data || response;
            if (Array.isArray(data)) {
                data.forEach(function(designation) {
                    designationsCache[designation.id] = designation.title || designation.name;
                });
            }
            console.log("Designations:", designationsCache);
        }
    });
}


$(document).ready(function() {
    // Pehle caches load karo
    loadAllBusinessUnits();
    loadAllDesignations();

    loadPermissions(roleId)
        .then(() => {
            applyUserPermissionUI();
            fetchUsersTable();
            loadRoles()
        })
        .catch(err => {
            console.warn("Permission load failed, proceeding without permissions:", err);
            fetchUsersTable();
        });

    loadRoles();
    loadBusinessUnits('#businessUnit');
    loadDesignations('#designation');
    loadBusinessUnits('#editBusinessUnit');
    loadDesignations('#editDesignation');
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

    function applyUserPermissionUI() {
        if (!hasPermission('user', 'create')) {
            $('#addNewBtn').hide();
        } else {
            $('#addNewBtn').show();
        }
        if (!hasPermission('user', 'update')) {
            $('#users-table thead tr th').filter(function() {

                return $(this).text().trim() == 'Edit';
            }).remove();
        }

    }

    function fetchUsersTable() {
        const tableEl = document.getElementById("users-table");
        const loadingEl = document.getElementById("table-loading");

        if (!tableEl || !loadingEl) {
            console.error("#users-table or #table-loading not found");
            return;
        }

        loadingEl.style.display = "block";

        if ($.fn.DataTable.isDataTable('#users-table')) {
            $('#users-table').DataTable().destroy();
            $('#users-table tbody').empty();
        }

        $.ajax({
            url: companyId == 0 ? "{{ config('app.api_url') }}users/super_admin_users" : "{{ config('app.api_url') }}users/company/" + companyId,
            // url: "{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}users/super_admin_users":"{{ config('app.api_url') }}users/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(data) {
                const cols = [{
                        data: 'id',
                        defaultContent: '-',
                        title: 'S.No'
                    },
                    {
                        data: 'first_name',
                        defaultContent: '-',
                        title: 'First Name'
                    },
                    {
                        data: 'last_name',
                        defaultContent: '-',
                        title: 'Last Name'
                    },
                    {
                        data: 'email',
                        defaultContent: '-',
                        title: 'Email'
                    },
                    {
                        data: 'bu_id',
                        defaultContent: '-',
                        title: 'BU ID'
                    },
                    {
                        data: 'designation_id',
                        defaultContent: '-',
                        title: 'Designation ID'
                    },
                    {
                        data: 'company',
                        defaultContent: '-',
                        title: 'Company'
                    },
                    {
                        data: 'created_at',
                        defaultContent: '-',
                        title: 'Created At'
                    }
                ];

                cols.push({
                data: null,
                orderable: false,
                searchable: false,
                title: 'View',
                render: function(data, type, row) {
                    return `
                        <a href="javascript:void(0);"
                           class="btn btn-sm btn-info"
                           onclick="viewUser(${row.id})">
                            <i class="ri-eye-fill"></i>
                        </a>`;
                }
            });

                // Edit column if update permitted
                if (hasPermission('user', 'update')) {
                    cols.push({
                        data: null,
                        orderable: false,
                        searchable: false,
                        title: 'Edit',
                        render: function(data, type, row) {
                            return `
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-primary"
                               onclick="editUser(${row.id})">
                                <i class="ri-pencil-fill"></i>
                            </a>`;
                        }
                    });
                }

                // Status column if allowed (either update_status or update)

                cols.push({
                    data: 'active',
                    orderable: false,
                    searchable: false,
                    title: 'Status',
                    render: function(data, type, row) {
                        const checked = data == 1 ? 'checked' : '';
                        const disabled = !hasPermission('user', 'update') ? 'disabled' : '';

                        return `
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    onchange="updateUserStatus(${row.id}, this)" ${checked} ${disabled}>
                            </div>`;
                    }
                });


                usersDataTable = $('#users-table').DataTable({
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
                            if (usersDataTable.buttons) usersDataTable.buttons().container().appendTo('#customExportButtons');
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

    function updateUserStatus(id, element) {

const isChecked = element.checked ? 1 : 0;
element.disabled = true;

const form = new FormData();
form.append("id", id);
form.append("active", isChecked);

$.ajax({
    url: `{{ config('app.api_url') }}updateuserstatus`,
    method: "POST",
    data: form,
    processData: false,
    contentType: false,
    success: function (response) {

        if (response.success === true) {

            const message = isChecked
                ? "User activated successfully"
                : "User deactivated successfully";

            showToast(message, "success");

            setTimeout(() => {
                fetchUsersTable();
            }, 500);

        } else {
            element.checked = !isChecked;
            showToast(response.message || "Status update failed", "danger");
        }
    },
    error: function (xhr) {
        element.checked = !isChecked;
        showToast("Failed to update user status", "danger");
        console.error(xhr.responseText);
    },
    complete: function () {
        element.disabled = false;
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

    function createUser() {
        const firstName = document.getElementById("firstName").value.trim();
        const lastName = document.getElementById("lastName").value.trim();
        const email = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();
        const primaryEmail = document.getElementById("email").value.trim(); // Optional

        // Validate required fields
        if (!firstName || !lastName || !email || !password) {
            showToast("All required fields must be filled", "warning");
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showToast("Please enter a valid email address", "warning");
            return;
        }

        // If primary email is provided, validate it too
        if (primaryEmail && !emailRegex.test(primaryEmail)) {
            showToast("Primary Email must be valid if provided", "warning");
            return;
        }


        const form = new FormData();
        form.append("first_name", firstName);
        form.append("last_name", lastName);
        // form.append("email", email);
        form.append("company_id", '{{Auth::user()->company_id}}');
        form.append("username", email);
        form.append("about", primaryEmail);
        form.append("designation_id", document.getElementById("designation").value);
        form.append("bu_id", document.getElementById("businessUnit").value);
        form.append("password", password);
        form.append("role", $('#role').val());

        showToast("Creating user, please wait...", "info");
        const submitBtn = document.querySelector("#offcanvasRight .btn-primary");
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

        $.ajax({
            url: "{{ config('app.api_url') }}users",
            method: "POST",
            processData: false,
            contentType: false,
            data: form,
            timeout: 60000,
            success: function(response) {
                showToast("User created successfully", "success");
                document.getElementById("createUserForm").reset();
                const offcanvasEl = document.getElementById("offcanvasRight");
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                offcanvas.hide();
                setTimeout(() => fetchUsersTable(), 500);
            },
            error: function(xhr, status, error) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save';

                if (status === "timeout") {
                    showToast("Request timed out. Please try again or check your connection.", "danger");
                } else {
                    const errorMessage = xhr.responseJSON?.message || "Failed to create user";
                    showToast(errorMessage, "danger");
                }
                console.error("Error details:", {
                    status,
                    error,
                    response: xhr.responseText
                });
            }
        });
    }

    function editUser(id) {
        $.ajax({
            url: `{{ config('app.api_url') }}users/${id}`,
            method: "GET",
            success: function(response) {
                const user = response.data ?? response;

                $('#editUserId').val(user[0].id);
                $('#editFirstName').val(user[0].first_name);
                $('#editLastName').val(user[0].last_name);
                $('#editUsername').val(user[0].email);
                $('#editPassword').val(user[0].password);
                $('#editEmail').val(user[0].email_verified_at);
                $('#editBusinessUnit').val(user[0].bu_id);
                $('#editDesignation').val(user[0].designation_id);
                $('#editCompany').val(user[0].company);
                $('#editCompanyId').val(user[0].company_id);
                $('#editActiveStatus').prop('checked', user[0].active == 1);

                loadBusinessUnits('#editBusinessUnit', user[0].bu_id);
                loadDesignations('#editDesignation', user[0].designation_id);

                const offcanvasEl = document.getElementById("editOffcanvas");
                const instance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                instance.show();
            },
            error: function() {
                showToast("Unable to fetch user details.", "danger");
            }
        });
    }

    function viewUser(id) {
    currentViewingUserId = id;

    $('#viewFirstName, #viewLastName, #viewEmail, #viewPrimaryEmail, #viewBusinessUnit, #viewDesignation, #viewCompany, #viewStatus, #viewCreatedAt').text('-');

    const table = $('#users-table').DataTable();
    const rowData = table.rows().data().toArray().find(row => row.id == id);

    if (rowData) {
        $('#viewFirstName').text(rowData.first_name || '-');
        $('#viewLastName').text(rowData.last_name || '-');
        $('#viewEmail').text(rowData.email || '-');
        $('#viewPrimaryEmail').text(rowData.email_verified_at || '-');
        $('#viewCompany').text(rowData.company || '-');
        $('#viewStatus').text(rowData.active == 1 ? 'Active' : 'Inactive');

        if (rowData.business_unit && typeof rowData.business_unit === 'object') {
            $('#viewBusinessUnit').text(rowData.business_unit.name || '-');
        }
        else if (rowData.bu_id) {
            if (typeof businessUnitsCache !== 'undefined' && businessUnitsCache[rowData.bu_id]) {
                $('#viewBusinessUnit').text(businessUnitsCache[rowData.bu_id]);
            } 
           
        } else {
            $('#viewBusinessUnit').text('-');
        }

        if (rowData.designation && typeof rowData.designation === 'object') {
            $('#viewDesignation').text(rowData.designation.title || rowData.designation.name || '-');
        }
        else if (rowData.designation_id) {
            if (typeof designationsCache !== 'undefined' && designationsCache[rowData.designation_id]) {
                $('#viewDesignation').text(designationsCache[rowData.designation_id]);
            } 
        } else {
            $('#viewDesignation').text('-');
        }

        if (rowData.created_at) {
            const date = new Date(rowData.created_at);
            $('#viewCreatedAt').text(date.toLocaleDateString() + ' ' + date.toLocaleTimeString());
        }
        $('#hiddenpassword').val(rowData.two_factor_secret)

        const modalEl = document.getElementById('viewUserModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    } 
}

function openEditFromView() {
    if (!currentViewingUserId) {
        showToast("No user selected", "warning");
        return;
    }

    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewUserModal'));
    if (viewModal) {
        viewModal.hide();
    }

    setTimeout(() => {
        editUser(currentViewingUserId);
    }, 200);
}

$('#viewUserModal').on('hidden.bs.modal', function () {
});

    function loadBusinessUnits(selector, selectedId = null) {
        $.ajax({
            url: '{{Auth::user()->company_id}}' == 0? "{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(data) {
                const dropdown = $(selector);
                dropdown.empty();
                dropdown.append('<option value="">Select Business Unit</option>');

                data.forEach(function(unit) {
                    const selected = (selectedId && unit.id == selectedId) ? 'selected' : '';
                    dropdown.append(`<option value="${unit.id}" ${selected}>${unit.name}</option>`);
                });
            },
            error: function() {
                showToast("Failed to load business units", "danger");
            }
        });
    }

    function loadDesignations(selector, selectedId = null) {
        $.ajax({
            url: "{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}designations":"{{ config('app.api_url') }}designations/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(response) {
                console.log("Designations API Response:", response);

                const dropdown = $(selector);
                dropdown.empty();
                dropdown.append('<option value="">Select Designation</option>');

                const data = response.data || response;

                if (Array.isArray(data)) {
                    data.forEach(function(designation) {
                        const selected = (selectedId && designation.id == selectedId) ? 'selected' : '';
                        dropdown.append(`<option value="${designation.id}" ${selected}>${designation.title}</option>`);
                    });
                } else {
                    console.error("Designations data is not an array:", data);
                }
            },
            error: function(xhr, status, error) {
                console.error("Designations API Error:", status, error);
                showToast("Failed to load designations", "danger");
            }
        });
    }

    function updateUser() {
        const id = $('#editUserId').val();
        const firstName = $('#editFirstName').val().trim();
        const lastName = $('#editLastName').val().trim();
        const email = $('#editUsername').val().trim();
        const password = $('#editPassword').val().trim();
        const isActive = $('#editActiveStatus').is(':checked') ? 1 : 0;

        if (!firstName || !lastName || !email) {
            showToast("Required fields must be filled", "warning");
            return;
        }

        const formData = new FormData();
        formData.append("id", id);
        formData.append("first_name", firstName);
        formData.append("last_name", lastName);
        formData.append("email", email);
        formData.append("email_verified_at", $('#editEmail').val().trim());
        formData.append("bu_id", $('#editBusinessUnit').val());
        formData.append("designation_id", $('#editDesignation').val());
        formData.append("company", $('#editCompany').val().trim());
        formData.append("company_id", $('#editCompanyId').val());
        formData.append("active", isActive);

        if (password) {
            formData.append("password", password);
        }

        $.ajax({
            url: `{{config('app.api_url') }}users/${id}`,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                showToast("User updated successfully", "success");
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                offcanvas.hide();

                setTimeout(() => {
                    fetchUsersTable();
                }, 500);
            },
            error: function() {
                showToast("Failed to update user", "danger");
            }
        });
    }

    function loadRoles() {
        console.log("{{Auth::user()->company_id}}"==0 ? '{{ config('app.api_url') }}roles':'{{ config('app.api_url') }}roles/company/{{Auth::user()->company_id}}',)
    $.ajax({
        url:  "{{Auth::user()->company_id}}"==0 ? '{{ config('app.api_url') }}roles':'{{ config('app.api_url') }}roles/company/{{Auth::user()->company_id}}',
        method: 'GET',
        success: function (roles) {
            console.log('roles', roles);

            let $roleSelect = $('#role');
            $roleSelect.empty();
            $roleSelect.append('<option value="">Select Role</option>');

            roles.forEach(function (item) {
                $roleSelect.append(
                    `<option value="${item.id}">${item.name}</option>`
                );
            });
        },
        error: function () {
            alert('Failed to load roles');
        }
    });
}

async function loginUser(e) {
    e.preventDefault();

    try {
        const email = $('#viewEmail').text().trim();
        const password = document.getElementById('hiddenpassword').value;

        let token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');
            
        const formData1 = new FormData();
        formData1.append('newUser', email);

        /* ================= LOGOUT ================= */
        await fetch("{{ route('logout') }}", {
            method: "POST",
            credentials: "same-origin",
            body: formData1,
            headers: {
                "X-CSRF-TOKEN": token
            }
        });

        /* ============ GET NEW CSRF TOKEN ============ */
        const csrfRes = await fetch('/public/csrf-token', {
            credentials: 'same-origin'
        });

        const csrfData = await csrfRes.json();
        token = csrfData.token;

        document
            .querySelector('meta[name="csrf-token"]')
            .setAttribute('content', token);

        /* ================= LOGIN ================= */
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('email', email);
        formData.append('password', password);
        
        // 🔥 IMPORTANT: Check karein ke "Login as User" button se aaya hai ya nahi
        const isLoginAsUser = e.target.id === 'loginAsUserBtn' || 
                             (e.target.closest && e.target.closest('#loginAsUserBtn'));
        
        // Agar Login as User button se hai, to flag add karein
        if (isLoginAsUser) {
            formData.append('login_as_user', '1');
            console.log('Login as User mode - will store previous user');
        } else {
            console.log('Normal login mode - will NOT store previous user');
        }

        const response = await fetch("{{ route('login') }}", {
            method: "POST",
            credentials: "same-origin",
            body: formData
        });

        if (response.redirected) {
            let redirectUrl = response.url;
            
            if (isLoginAsUser) {
                console.log('Login as User - adding impersonated parameter');
                if (redirectUrl.includes('?')) {
                    redirectUrl += '&impersonated=1';
                } else {
                    redirectUrl += '?impersonated=1';
                }
            } else {
                console.log('Normal login - no parameter');
            }
            
            window.location.href = redirectUrl;
            return;
        }

        console.log(await response.text());

    } catch (err) {
        console.error(err);
        alert('Login failed');
    }
}


</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
