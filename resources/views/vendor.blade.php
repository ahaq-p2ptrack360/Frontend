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

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Vendor @endslot
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
    #offcanvasRightEdit {
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

/* #vendor-table .form-check.form-switch {
    padding-left: 3.5rem;
    min-height: 30px;
    display: flex;
    align-items: center;
} */

#vendor-table .form-check-input {
            width: 40px !important;
            height: 22px !important;
        }

/* #vendor-table .form-check-input:checked {
    background-color: #514fd4;
    border-color: #198754;
    cursor: pointer;
}

#vendor-table .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

#vendor-table .form-check-input::before {
    transform: scale(1.2);
} */
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <button id="addNewBtn" class="btn btn-primary py-2 px-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight">
                        Add New
                    </button>
                    <div id="customExportButtons" class="d-flex align-items-center"></div>
                </div>
            </div>
            <div class="card-body">
                <table id="vendor-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Company</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Edit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Create Vendor</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createVendorForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-control" id="type" name="vendor_type_id" required>
                        <option value="">Select Type</option>

                    </select>
                </div>
                <div class="col-md-4">
                    <label for="subType" class="form-label">Sub Type</label>
                    <select class="form-control" id="subType" name="company_id" required>
                        <option value="">Select Sub Type</option>
                        <option value="1">Sub Type</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="businessUnit" class="form-label">Business Unit</label>
                    <select class="form-control" id="businessUnit" name="bu_id" required>
                        <option value="">Select Business Unit</option>
                        <option value="165">Business Unit</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="col-md-4">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="altPhone" class="form-label">Alternate Phone</label>
                    <input type="tel" class="form-control" id="altPhone" name="alternate_phone"
                        placeholder="Enter Alternate Phone">
                </div>
                <div class="col-md-4">
                    <label for="address1" class="form-label">Address 1</label>
                    <input type="text" class="form-control" id="address1" name="address_1"
                        placeholder="Enter Address 1">
                </div>
                <div class="col-md-4">
                    <label for="address2" class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="address2" name="address_2"
                        placeholder="Enter Address 2">
                </div>
                <div class="col-md-4">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter Latitude">
                </div>
                <div class="col-md-4">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude"
                        placeholder="Enter Longitude">
                </div>
                <div class="col-md-4">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter City">
                </div>
                <div class="col-md-4">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" id="state" name="state" placeholder="Enter State">
                </div>
                <div class="col-md-4">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country">
                </div>
                <div class="col-md-4">
                    <label for="zip" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="zip" name="zipcode" placeholder="Enter Zip Code">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-dark me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="button" class="btn btn-primary" onclick="createVendor()">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRightEdit">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit Vendor</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editVendorForm">
            <input type="hidden" id="editVendorId" name="id">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="typeEdit" class="form-label">Type</label>
                    <select class="form-control" id="typeEdit" name="vendor_type_id" required>
                        <option value="">Select Type</option>
                        <option value="110">Type</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="subTypeEdit" class="form-label">Sub Type</label>
                    <select class="form-control" id="subTypeEdit" name="company_id" required>
                        <option value="">Select Sub Type</option>
                        <option value="1">Sub Type</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="businessUnitEdit" class="form-label">Business Unit</label>
                    <select class="form-control" id="businessUnitEdit" name="bu_id" required>
                        <option value="">Select Business Unit</option>
                        <option value="165">Business Unit</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="nameEdit" class="form-label">Name</label>
                    <input type="text" class="form-control" id="nameEdit" name="name" placeholder="Enter Name" required>
                </div>
                <div class="col-md-4">
                    <label for="emailEdit" class="form-label">Email</label>
                    <input type="email" class="form-control" id="emailEdit" name="email" placeholder="Enter Email"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="phoneEdit" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="phoneEdit" name="phone" placeholder="Enter Phone Number"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="altPhoneEdit" class="form-label">Alternate Phone</label>
                    <input type="tel" class="form-control" id="altPhoneEdit" name="alternate_phone"
                        placeholder="Enter Alternate Phone">
                </div>
                <div class="col-md-4">
                    <label for="address1Edit" class="form-label">Address 1</label>
                    <input type="text" class="form-control" id="address1Edit" name="address_1"
                        placeholder="Enter Address 1">
                </div>
                <div class="col-md-4">
                    <label for="address2Edit" class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="address2Edit" name="address_2"
                        placeholder="Enter Address 2">
                </div>
                <div class="col-md-4">
                    <label for="latitudeEdit" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitudeEdit" name="latitude"
                        placeholder="Enter Latitude">
                </div>
                <div class="col-md-4">
                    <label for="longitudeEdit" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitudeEdit" name="longitude"
                        placeholder="Enter Longitude">
                </div>
                <div class="col-md-4">
                    <label for="cityEdit" class="form-label">City</label>
                    <input type="text" class="form-control" id="cityEdit" name="city" placeholder="Enter City">
                </div>
                <div class="col-md-4">
                    <label for="stateEdit" class="form-label">State</label>
                    <input type="text" class="form-control" id="stateEdit" name="state" placeholder="Enter State">
                </div>
                <div class="col-md-4">
                    <label for="countryEdit" class="form-label">Country</label>
                    <input type="text" class="form-control" id="countryEdit" name="country" placeholder="Enter Country">
                </div>
                <div class="col-md-4">
                    <label for="zipEdit" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="zipEdit" name="zipcode" placeholder="Enter Zip Code">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-dark me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateData()">Update</button>
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

<!-- Export buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

<!-- Required libraries for Excel/PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Bootstrap Toggle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<script>
    let vendorDataTable = null;
    let companies = [];
    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';

    document.addEventListener("DOMContentLoaded", function() {
        loadPermissions(roleId)
            .then(() => {

                applyPermissionUI();
                fetchVendorTable();
            })
            .catch(err => console.error("Initialization error:", err));
             $.ajax({
            url: ("{{ Auth::user()->company_id == 0 }}" ?
                "{{ config('app.api_url') }}types" :
                "{{ config('app.api_url') }}companytype/{{ Auth::user()->company_id }}"
            ),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                type1 = new Choices("#type", {
                    removeItemButton: !0,
                })
                type1.clearChoices();
                console.log(response);
                type1.setChoices(response,
                    'id',
                    'title',
                    false, );

            typee = new Choices("#typeEdit", {
                    removeItemButton: !0,
                })
                typee.clearChoices();
                console.log(response);
                typee.setChoices(response,
                    'id',
                    'title',
                    false, );

            subTypeE = new Choices("#subTypeEdit", {
                    removeItemButton: !0,
                })
                subTypeE.clearChoices();
                console.log(response);
                subTypeE.setChoices(response,
                    'id',
                    'title',
                    false, );

            subTypeE = new Choices("#subType", {
                    removeItemButton: !0,
                })
                subTypeE.clearChoices();
                console.log(response);
                subTypeE.setChoices(response,
                    'id',
                    'title',
                    false, );

        }
        });

        $.ajax({
            url: "{{Auth::user()->company_id}}"==0 ?"{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                bu = new Choices("#businessUnit", {
                    removeItemButton: !0,
                })
                bu.clearChoices(); // This line is causing the error
                console.log("check" + response);
                bu.setChoices(response, 'id', 'name', false);


                bu = new Choices("#businessUnitEdit", {
                    removeItemButton: !0,
                })
                bu.clearChoices(); // This line is causing the error
                console.log("check" + response);
                bu.setChoices(response, 'id', 'name', false);

                // Move the setChoiceByValue inside the success callback
            }
        });

    });

    // Fetch Companies

    async function company() {
        try {
            const res = await $.ajax({
                url: `{{ config('app.api_url') }}companies`,
                method: 'GET',
                dataType: 'json'
            });
            companies =res;
            console.log('companies',companies)
        } catch (e) {
            console.error("Failed to load permissions", e);
        }
    }

    // Load Permissions
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
        }
    }

    // Check Permission
    function hasPermission(section, action) {
        if (!permissions || !section || !action) return false;
        try {
            const raw = permissions[section];
            if (!raw) return false;
            const parsed = typeof raw === 'object' ? raw : JSON.parse(raw);
            return parsed[action] === 1;
        } catch (e) {
            return false;
        }
    }

    // Apply UI Permissions

    function applyPermissionUI() {
        if (hasPermission('vendor', 'create')) {
            $('#addNewBtn').show();
        } else {
            $('#addNewBtn').hide();
        }

        if (!hasPermission('vendor', 'update')) {
            // remove Edit header cell if no update permission
            $('#vendor-table thead tr th').filter(function() {
                return $(this).text().trim() === 'Edit';
            }).remove();
        }
    }

    // Fetch Vendor Table
   async function fetchVendorTable() {
   await company()
        console.log(companies)
        if (vendorDataTable) {
            vendorDataTable.destroy();
            $('#vendor-table').empty();
        }

        let cols = [{
                data: 'id',
                title: 'S.No'
            },
            {
                data: 'company_id',
                title: 'Company',
                render: function(data) {
                    console.log(data)
                    const company = companies.find(c => c.id == String(data));
                    console.log("company",company)

                    return company ? company.name : 'N/A';
                }
            },
            {
                data: 'name',
                title: 'Name'
            },
            {
                data: 'email',
                title: 'Email'
            },
            {
                data: 'phone',
                title: 'Phone'
            },
            {
                data: 'created_at',
                title: 'Created At'
            }
        ];

        // Add Edit column only if allowed
        if (hasPermission('vendor', 'update')) {
            cols.push({
                data: null,
                title: 'Edit',
                orderable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-sm btn-primary" onclick="editData(${row.id})">
                            <i class="ri-pencil-fill"></i>
                        </button>`;
                }
            });
        }

        cols.push({
            data: 'active',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                const checked = data == 1 ? 'checked' : '';
                const disabled = !hasPermission('vendor', 'update') ? 'disabled' : '';

                return `
                <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                onchange="updateVendorStatus(${row.id}, this)" ${checked} ${disabled}>
                        </div>`;
            }
        });
        vendorDataTable = $('#vendor-table').DataTable({
            ajax: {
                url: "{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}vendors":"{{ config('app.api_url') }}vendors/company/{{Auth::user()->company_id}}",
                dataSrc: '',
                method: "GET",
            },
            responsive: true,
            lengthChange: false,
            searching: false,
            ordering: true,
            paging: true,
            autoWidth: false,
            destroy: true,
            columns: cols,
            buttons: [{
                extend: 'collection',
                text: '<i class="ri-file-excel-2-line"></i> Export',
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
            initComplete: function() {
                $('#customExportButtons').html('');
                vendorDataTable.buttons().container().appendTo('#customExportButtons');
            }
        });
    }



    function createVendor() {
        const form = $('#createVendorForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        console.log('Company ID:', $('#businessUnit').val());
        console.log('Vendor Type ID:', $('#type').val());
        console.log('Sub Type ID:', $('#subType').val());

        const formData = new FormData(form);
        formData.append('active', '1');
        formData.append('bu_id', $('#businessUnit').val());
        formData.append('vendor_type_id', $('#type').val());
        formData.append('company_id', '{{Auth::user()->company_id}}');
        formData.append('status', '1');

        $.ajax({
            url: "{{ config('app.api_url') }}vendors",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#createVendorForm')[0].reset();
                fetchVendorTable();
                bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasRight")).hide();
                showToast("Vendor created successfully", "success");
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                showToast("Failed to create vendor: " + xhr.responseText, "danger");
            }
        });
    }

    // function editData(id) {
    //     $.ajax({
    //         url: `{{ config('app.api_url') }}vendors/${id}`,
    //         method: "GET",
    //         success: function(response) {
    //             const vendor = response.data || response;
    //             console.log("Vendor data for editing:", vendor);

    //             if (!vendor) {
    //                 showToast("No vendor data found.", "danger");
    //                 return;
    //             }

    //             $('#editVendorId').val(vendor[0]['id'] || '');
    //             $('#typeEdit').val(vendor[0]['vendor_type_id'] || '').trigger('change');
    //             $('#subTypeEdit').val(vendor[0]['sub_type_id'] || '').trigger('change');
    //             $('#businessUnitEdit').val(vendor[0]['bu_id'] || '').trigger('change');
    //             $('#nameEdit').val(vendor[0]['name'] || '');
    //             $('#emailEdit').val(vendor[0]['email'] || '');
    //             $('#phoneEdit').val(vendor[0]['phone'] || '');
    //             $('#altPhoneEdit').val(vendor[0]['alternate_phone'] || '');
    //             $('#address1Edit').val(vendor[0]['address_1'] || '');
    //             $('#address2Edit').val(vendor[0]['address_2'] || '');
    //             $('#latitudeEdit').val(vendor[0]['latitude'] || '');
    //             $('#longitudeEdit').val(vendor[0]['longitude'] || '');
    //             $('#cityEdit').val(vendor[0]['city'] || '');
    //             $('#stateEdit').val(vendor[0]['state'] || '');
    //             $('#countryEdit').val(vendor[0]['country'] || '');
    //             $('#zipEdit').val(vendor[0]['zipcode'] || vendor[0]['zip'] || '');


    //             const offcanvas = new bootstrap.Offcanvas(document.getElementById("offcanvasRightEdit"));
    //             offcanvas.show();
    //         },
    //         error: function(xhr) {
    //             console.error("Error fetching vendor:", xhr.responseText);
    //             showToast("Unable to fetch vendor details.", "danger");
    //         }
    //     });
    // }

    function editData(id) {
    $.ajax({
        url: `{{ config('app.api_url') }}vendors/${id}`,
        method: "GET",
        success: function(response) {
            const vendor = response.data || response;
            console.log("Vendor data for editing:", vendor);

            if (!vendor || vendor.length === 0) {
                showToast("No vendor data found.", "danger");
                return;
            }

            $('#editVendorId').val(vendor[0]['id'] || '');
            $('#typeEdit').val(vendor[0]['vendor_type_id'] || '').trigger('change');

            // YEH LINE THEEK KARO - company_id ko set karo
            $('#subTypeEdit').val(vendor[0]['company_id'] || '').trigger('change');

            $('#businessUnitEdit').val(vendor[0]['bu_id'] || '').trigger('change');
            $('#nameEdit').val(vendor[0]['name'] || '');
            $('#emailEdit').val(vendor[0]['email'] || '');
            $('#phoneEdit').val(vendor[0]['phone'] || '');
            $('#altPhoneEdit').val(vendor[0]['alternate_phone'] || '');
            $('#address1Edit').val(vendor[0]['address_1'] || '');
            $('#address2Edit').val(vendor[0]['address_2'] || '');
            $('#latitudeEdit').val(vendor[0]['latitude'] || '');
            $('#longitudeEdit').val(vendor[0]['longitude'] || '');
            $('#cityEdit').val(vendor[0]['city'] || '');
            $('#stateEdit').val(vendor[0]['state'] || '');
            $('#countryEdit').val(vendor[0]['country'] || '');
            $('#zipEdit').val(vendor[0]['zipcode'] || '');

            console.log("Setting company_id:", vendor[0]['company_id']);

            const offcanvas = new bootstrap.Offcanvas(document.getElementById("offcanvasRightEdit"));
            offcanvas.show();
        },
        error: function(xhr) {
            console.error("Error fetching vendor:", xhr.responseText);
            showToast("Unable to fetch vendor details.", "danger");
        }
    });
}

    // function updateData() {
    //     const form = $('#editVendorForm')[0];
    //     if (!form.checkValidity()) {
    //         form.reportValidity();
    //         return;
    //     }

    //     const id = $('#editVendorId').val();
    //     const formData = new FormData(form);

    //     formData.append('_method', 'PUT');
    //     formData.append('bu_id', $('#businessUnitEdit').val());
    //     formData.append('vendor_type_id', $('#typeEdit').val());
    //     formData.append('company_id', $('#subTypeEdit').val());
    //     formData.append('active', '1');

    //     $.ajax({
    //         url: `{{ config('app.api_url') }}vendorsupdate/${id}`,
    //         method: "PUT",
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function(response) {
    //             console.log("Update success:", response);
    //             fetchVendorTable();
    //             bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasRightEdit")).hide();
    //             showToast("Vendor updated successfully", "success");
    //         },
    //         error: function(xhr) {
    //             console.error("Update error:", xhr.responseText);
    //             showToast("Failed to update vendor: " + xhr.responseText, "danger");
    //         }
    //     });
    // }
    function updateData() {
    const id = $('#editVendorId').val();
    const formData = new FormData();

    // YEH SAB FIELDS MANUALLY APPEND KARO
    formData.append('id', id);
    formData.append('vendor_type_id', $('#typeEdit').val());
    formData.append('company_id', $('#subTypeEdit').val()); // YEH LINE IMPORTANT HAI
    formData.append('bu_id', $('#businessUnitEdit').val());
    formData.append('name', $('#nameEdit').val());
    formData.append('email', $('#emailEdit').val());
    formData.append('phone', $('#phoneEdit').val());
    formData.append('alternate_phone', $('#altPhoneEdit').val());
    formData.append('address_1', $('#address1Edit').val());
    formData.append('address_2', $('#address2Edit').val());
    formData.append('latitude', $('#latitudeEdit').val());
    formData.append('longitude', $('#longitudeEdit').val());
    formData.append('city', $('#cityEdit').val());
    formData.append('state', $('#stateEdit').val());
    formData.append('country', $('#countryEdit').val());
    formData.append('zipcode', $('#zipEdit').val());
    formData.append('status', '1');
    formData.append('active', '1');
    formData.append('_method', 'PUT');

    console.log("Sending data for vendor update:");
    console.log("Company ID:", $('#subTypeEdit').val());
    console.log("Vendor Type ID:", $('#typeEdit').val());
    console.log("BU ID:", $('#businessUnitEdit').val());

    $.ajax({
        url: `{{ config('app.api_url') }}vendorsupdate/${id}`,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Update success:", response);
            fetchVendorTable();
            bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasRightEdit")).hide();
            showToast("Vendor updated successfully", "success");
        },
        error: function(xhr) {
            console.error("Update error:", xhr.responseText);
            showToast("Failed to update vendor: " + xhr.responseText, "danger");
        }
    });
}

    function updateVendorStatus(id, element) {
        const isChecked = $(element).prop('checked') ? 1 : 0;

        const formData = new FormData();
        formData.append("id", id);
        formData.append("active", isChecked);

        $.ajax({
            url: `{{ config('app.api_url') }}updatevendorstatus`,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                fetchVendorTable();
                showToast(`Vendor ${isChecked ? 'activated' : 'deactivated'} successfully`,
                    isChecked ? "success" : "warning");
            },
            error: function() {
                showToast("Failed to update vendor status", "danger");
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
</script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
