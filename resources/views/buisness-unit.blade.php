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
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Business Unit @endslot
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

    #map {
        width: 100%;
        height: 300px;
        margin-top: 10px;
    }

    .pac-container {
        z-index: 9999;
    }

    #business-unit-table .form-check-input {
            width: 40px !important;
            height: 22px !important;
        }
</style>

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
                <table id="business-unit-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Company</th>
                            <th>Name</th>
                            <th>Email</th>
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
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Business Unit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createBusinessUnitForm">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Enter Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Enter Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="altPhone" class="form-label">Alternate Phone</label>
                    <input type="text" class="form-control" id="altPhone" name="alternate_phone"
                        placeholder="Enter Alternate Phone">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" id="state" name="state" placeholder="Enter State">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter City">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="address1" class="form-label">Address1</label>
                    <input type="text" class="form-control" id="address1" name="address_1" placeholder="Enter Address1">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="address2" class="form-label">Address2</label>
                    <input type="text" class="form-control" id="address2" name="address_2" placeholder="Enter Address2">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude"
                        placeholder="Enter Longitude">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter Latitude">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="marketingManager" class="form-label">Marketing Manager</label>
                    <select class="form-control" name="marketing_manager" id="marketingManager">


                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="responsible" class="form-label">Responsible</label>
                    <select class="form-control" name="reponsible_user" id="responsible">

                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="storeManager" class="form-label">Store Manager</label>
                    <select class="form-control" name="store_manager" id="storeManager">

                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="vendorRequired" class="form-label">Vendor Required</label>
                    <select class="form-control" data-choices name="vendor_r" id="vendorRequired">
                        <option value="">Select Period</option>
                        <option value=""></option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="accountRequired" class="form-label">Account Required</label>
                    <select class="form-control" data-choices name="customer_r" id="accountRequired">
                        <option value="">Select Period</option>
                        <option value=""></option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
    <label for="ticketRepresented" class="form-label">Ticket Represented</label>
    <input type="text" class="form-control" id="ticketRepresented" name="ticket_represented" placeholder="Enter Ticket Represented">
</div>

                <div class="col-md-4 mb-3">
                    <label for="parentBU" class="form-label">Parent BU</label>
                    <select class="form-control" name="parent_bu_id" id="parentBU">

                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="zipcode" class="form-label">Zip Code</label>
                    <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter Zip Code">
                </div>
                @if(Auth::user()->id == 1)
                <div class="col-md-4 mb-3">
                    <label for="editCompanyId" class="form-label">Company <span class="text-danger">*</span></label>
                    <select class="form-control" id="CompanyId" name="company_id">
                        {{-- Options will be populated dynamically --}}
                    </select>
                </div>
                @endif
            </div>

            <div class="col-md-12">
                <div class="map-search-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="mapSearch" placeholder="Search for a location...">
                    </div>
                </div>
                <div id="map" style="height: 400px; border-radius: 8px;"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Additional Fields</label>
                <div id="dynamicInputContainer"></div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-success bg-gradient mt-4" id="addMoreBtn">
                        <i class="ri-add-line"></i> Add More</button>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Business Unit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editBusinessUnitForm">
            <input type="hidden" id="editBusinessUnitId" name="id">
            <input type="hidden" id="editProperties" name="properties" value="[]">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="editName" class="form-label">Enter Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editName" name="name" placeholder="Enter Name" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editEmail" class="form-label">Enter Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="editEmail" name="email" placeholder="Enter Email"
                        required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editPhone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="editPhone" name="phone" placeholder="Enter Phone">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="editAltPhone" class="form-label">Alternate Phone</label>
                    <input type="text" class="form-control" id="editAltPhone" name="alternate_phone"
                        placeholder="Enter Alternate Phone">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editCountry" class="form-label">Country</label>
                    <input type="text" class="form-control" id="editCountry" name="country" placeholder="Enter Country">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editState" class="form-label">State</label>
                    <input type="text" class="form-control" id="editState" name="state" placeholder="Enter State">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="editCity" class="form-label">City</label>
                    <input type="text" class="form-control" id="editCity" name="city" placeholder="Enter City">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editAddress1" class="form-label">Address1</label>
                    <input type="text" class="form-control" id="editAddress1" name="address_1"
                        placeholder="Enter Address1">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editAddress2" class="form-label">Address2</label>
                    <input type="text" class="form-control" id="editAddress2" name="address_2"
                        placeholder="Enter Address2">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="editLongitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="editLongitude" name="longitude"
                        placeholder="Enter Longitude">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editLatitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="editLatitude" name="latitude"
                        placeholder="Enter Latitude">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="editMarketingManager" class="form-label">Marketing Manager</label>
                    <select class="form-control" name="marketing_manager" id="editMarketingManager">

                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="editResponsible" class="form-label">Responsible</label>
                    <select class="form-control" name="reponsible_user" id="editResponsible">

                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editStoreManager" class="form-label">Store Manager</label>
                    <select class="form-control" name="store_manager" id="editStoreManager">

                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editVendorRequired" class="form-label">Vendor Required</label>
                    <select class="form-control"  name="vendor_r" id="editVendorRequired">
                        <option value="">Select Period</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editAccountRequired" class="form-label">Account Required</label>
                    <select class="form-control"  name="customer_r" id="editAccountRequired">
                        <option value="">Select Period</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
    <label for="editTicketRepresented" class="form-label">Ticket Represented</label>
    <input type="text" class="form-control" id="editTicketRepresented" name="ticket_represented" placeholder="Enter Ticket Represented">
</div>

                <div class="col-md-4 mb-3">
                    <label for="editParentBU" class="form-label">Parent BU</label>
                    <select class="form-control" name="parent_bu_id" id="editParentBU">

                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="editZipcode" class="form-label">Zip Code</label>
                    <input type="text" class="form-control" id="editZipcode" name="zipcode"
                        placeholder="Enter Zip Code">
                </div>
                @if(Auth::user()->id == 1)
                <div class="col-md-4 mb-3">
                    <label for="editCompanyId" class="form-label">Company <span class="text-danger">*</span></label>
                    <select class="form-control" id="editCompanyId" name="company_id">
                        {{-- Options will be populated dynamically --}}
                    </select>
                </div>
                @endif

            </div>
            <div class="col-md-12">
                <div class="map-search-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="editMapSearch"
                            placeholder="Search for a location...">
                    </div>
                </div>
                <div id="editMap" style="height: 400px; border-radius: 8px;"></div>
            </div>

            <!-- <div class="mb-3">
                <label class="form-label">Additional Fields</label>
                <div id="editDynamicInputContainer"></div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-success bg-gradient mt-4" id="editAddMoreBtn">
                        <i class="ri-add-line"></i> Add More</button>
                </div>
            </div> -->

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Datatable JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<!-- Bootstrap Toggle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA34O91fqOnqRdFfypa-PruL4RwTXq9oTc&libraries=places&callback=initMap"
  async
  defer>
</script> -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=eyJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiJwdHBAc3RhZ2FwbC5jb20iLCJzdWIiOiJwdHBAc3RhZ2FwbC5jb20iLCJpYXQiOjE3Njg0NjI5OTksImV4cCI6MTc2ODU0OTM5OX0.hCu0HQuEEmbSoBjLdeEwqOMorUhBHjveceAC1s1XjUU&callback=initMap&libraries=places&v=weekly"
    defer>
</script>


<script>
    let businessUnitDataTable;
    let map, marker;
    let inputCount = 0;
    let createMap, createMarker,parentBU,editbu;
    let editMap, editMarker, Manager, editCompanyId, CompanyId, storeManager, responsible, editStoreManager, editResponsible, editMarketingManager;
    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';
    const companyId = '{{ Auth::user()->company_id }}';

    $(document).ready(function() {
        loadPermissions(roleId).then(() => {
            applyBusinessUnitPermissionUI();
            fetchBusinessUnits();
            $.ajax({
                url: ("{{ Auth::user()->company_id == 0 }}" ?
                    "{{ config('app.api_url') }}users/super_admin_users" :
                    "{{ config('app.api_url') }}users/company/{{ Auth::user()->company_id }}"
                ),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    storeManager = new Choices("#storeManager", {
                        removeItemButton: !0,
                    })
                    storeManager.clearChoices();
                    console.log(response);
                    storeManager.setChoices(
                        response.map(item => ({
                            value: item.id,
                            label: item.name // Properly formatting the label
                        })),
                        'value',
                        'label',
                        false
                    );

                    Manager = new Choices("#marketingManager", {
                        removeItemButton: !0,
                    })
                    Manager.clearChoices();
                    console.log(response);
                    Manager.setChoices(
                        response.map(item => ({
                            value: item.id,
                            label: item.name // Properly formatting the label
                        })),
                        'value',
                        'label',
                        false
                    );
                    responsible = new Choices("#responsible", {
                        removeItemButton: !0,
                    })
                    responsible.clearChoices();
                    console.log(response);
                    responsible.setChoices(
                        response.map(item => ({
                            value: item.id,
                            label: item.name // Properly formatting the label
                        })),
                        'value',
                        'label',
                        false
                    );
                    editMarketingManager = new Choices("#editMarketingManager", {
                        removeItemButton: !0,
                    })
                    editMarketingManager.clearChoices();
                    console.log(response);
                    editMarketingManager.setChoices(
                        response.map(item => ({
                            value: item.id,
                            label: item.name // Properly formatting the label
                        })),
                        'value',
                        'label',
                        false
                    );
                    editResponsible = new Choices("#editResponsible", {
                        removeItemButton: !0,
                    })
                    editResponsible.clearChoices();
                    console.log(response);
                    editResponsible.setChoices(
                        response.map(item => ({
                            value: item.id,
                            label: item.name // Properly formatting the label
                        })),
                        'value',
                        'label',
                        false
                    );
                    editStoreManager = new Choices("#editStoreManager", {
                        removeItemButton: !0,
                    })
                    editStoreManager.clearChoices();
                    console.log(response);
                    editStoreManager.setChoices(
                        response.map(item => ({
                            value: item.id,
                            label: item.name // Properly formatting the label
                        })),
                        'value',
                        'label',
                        false
                    );

                }
            })

            $.ajax({
                url: '{{Auth::user()->company_id}}'==0? "{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    business_units = response;
                    editbu = new Choices("#editParentBU", {
                        removeItemButton: !0,
                    })
                    editbu.clearChoices(); // This line is causing the error
                    console.log("check" + response);
                    editbu.setChoices(response, 'id', 'name', false);
                    parentBU = new Choices("#parentBU", {
                        removeItemButton: !0,
                    })
                    parentBU.clearChoices(); // This line is causing the error
                    console.log("check" + response);
                    parentBU.setChoices(response, 'id', 'name', false);

                    // Move the setChoiceByValue inside the success callback
                }
            });

            $.ajax({
                url: "{{ config('app.api_url') }}companies",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    business_units = response;
                    editCompanyId = new Choices("#editCompanyId", {
                        removeItemButton: !0,
                    })
                    editCompanyId.clearChoices(); // This line is causing the error
                    console.log("check" + response);
                    editCompanyId.setChoices(response, 'id', 'name', false);

                    CompanyId = new Choices("#CompanyId", {
                        removeItemButton: !0,
                    })
                    CompanyId.clearChoices(); // This line is causing the error
                    console.log("check" + response);
                    CompanyId.setChoices(response, 'id', 'name', false);

                    // Move the setChoiceByValue inside the success callback
                }
            });
        });

        // Initialize form submit handlers
        $('#createBusinessUnitForm').on('submit', function(e) {
            e.preventDefault();
            createData();
        });

        $('#editBusinessUnitForm').on('submit', function(e) {
            e.preventDefault();
            updateData(e);
        });

        function addDynamicInput(targetContainer) {
            inputCount++;
            const inputGroup = `
                            <div class="row mb-2 dynamic-group" id="inputGroup-${inputCount}">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="extraField1[]" placeholder="Extra Field 1">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="extraField2[]" placeholder="Extra Field 2">
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <button type="button" class="btn btn-sm btn-danger removeBtn" data-id="${inputCount}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        `;
            $(targetContainer).append(inputGroup);
        }

        $('#addMoreBtn').on('click', function() {
            addDynamicInput('#dynamicInputContainer');
        });

        $('#editAddMoreBtn').on('click', function() {
            addDynamicInput('#editDynamicInputContainer');
        });

        $(document).on('click', '.removeBtn', function() {
            const id = $(this).data('id');
            $(`#inputGroup-${id}`).remove();
        });
    });


    async function loadPermissions(roleId) {
        try {
            const res = await $.ajax({
                url: `{{ config('app.api_url') }}permissions/${roleId}`,
                method: 'GET',
                dataType: 'json'
            });
            if (Array.isArray(res) && res.length) permissions = res[0];
            else permissions = null;
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

    function applyBusinessUnitPermissionUI() {
        // Example: toggle visibility of "Add New" if exists
        if (!hasPermission('business_unit', 'create')) {
            $('#addNewBtn').hide(); // adjust selector to your add button
        } else {
            $('#addNewBtn').show();
        }

        if (!hasPermission('business_unit', 'update')) {
            // remove Edit header if no update permission
            $('#business-unit-table thead tr th').filter(function() {
                return $(this).text().trim() === 'Edit';
            }).remove();
        }
    }

    function fetchBusinessUnits() {
        const tableEl = document.getElementById("business-unit-table");
        const loadingEl = document.getElementById("table-loading");

        if (!tableEl || !loadingEl) {
            console.error("#business-unit-table or #table-loading not found");
            return;
        }

        loadingEl.style.display = "block";

        if (businessUnitDataTable) {
            businessUnitDataTable.destroy();
            $('#business-unit-table tbody').empty();
        }

        $.ajax({
            url:'{{Auth::user()->company_id}}'==0? "{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(data) {
                // build columns dynamic based on update permission
                const cols = [{
                        data: 'id',
                        defaultContent: '-'
                    },
                    {
                        data: 'company',
                        defaultContent: '-'
                    },
                    {
                        data: 'name',
                        defaultContent: '-'
                    },
                    {
                        data: 'email',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        defaultContent: '-'
                    }
                ];

                if (hasPermission('business_unit', 'update')) {
                    cols.push({
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <a href="javascript:void(0);" 
                               class="btn btn-sm btn-primary"  data-bs-toggle="offcanvas"
                        data-bs-target="#editOffcanvas" aria-controls="offcanvasRight"
                               onclick="editBusinessUnit(${row.id})">
                               <i class="ri-pencil-fill"></i>
                            </a>`;
                        }
                    });
                }

                cols.push({
                    data: 'active',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const checked = data == 1 ? 'checked' : '';
                        // optionally block toggle if no update permission
                        const disabled = !hasPermission('business_unit', 'update') ? 'disabled' : '';
                        return `
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                onchange="updateBusinessUnitToggle(${row.id}, this)" ${checked} ${disabled}>
                        </div>`;
                    }
                });

                businessUnitDataTable = $('#business-unit-table').DataTable({
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
                            businessUnitDataTable.buttons().container().appendTo('#customExportButtons');
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

    function updateBusinessUnitToggle(id, element) {
    console.log('TOGGLE CLICKED', id);

    if (!hasPermission('business_unit', 'update')) {
        showToast("No permission to change status", "warning");
        $(element).prop('checked', !$(element).prop('checked')); // revert checkbox
        return;
    }

    const isChecked = $(element).prop('checked') ? 1 : 0;

    const form = new FormData();
    form.append("id", id);
    form.append("active", isChecked);

    $.ajax({
        url: `{{ config('app.api_url') }}updatebustatus`,
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function() {
            fetchBusinessUnits();
            const message = isChecked ? "Business Unit activated successfully" : "Business Unit deactivated successfully";
            const type = isChecked ? "success" : "warning";
            showToast(message, type);
        },
        error: function() {
            showToast("Failed to update Business Unit status", "danger");
            $(element).prop('checked', !isChecked); // revert checkbox
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

    function createData() {
        const form = new FormData(document.getElementById('createBusinessUnitForm'));

        function getValueOrDefault(id, defaultVal = "") {
            const el = document.getElementById(id);
            if (!el || typeof el.value !== "string") return defaultVal;
            let val = el.value.trim();
            if (!val || val === "undefined") return defaultVal;
            return val;
        }

        function getIntValue(id) {
            const el = document.getElementById(id);
            if (!el) return 0;
            const val = parseInt(el.value);
            return isNaN(val) ? 0 : val;
        }

        // if ($('#editBusinessUnitId').val() != "") {
            form.append("properties", "[]");
            form.append("bu_user", "1");
            form.append("status", "1");
            form.append("name", getValueOrDefault("name"));
            form.append("email", getValueOrDefault("email"));
            form.append("phone", getValueOrDefault("phone"));
            form.append("alternate_phone", getValueOrDefault("altPhone"));
            form.append("country", getValueOrDefault("country"));
            form.append("state", getValueOrDefault("state"));
            form.append("city", getValueOrDefault("city"));
            form.append("address_1", getValueOrDefault("address1"));
            form.append("address_2", getValueOrDefault("address2"));
            form.append("longitude", getValueOrDefault("longitude"));
            form.append("latitude", getValueOrDefault("latitude"));
            form.append("zipcode", getValueOrDefault("zipcode"));
            form.append("marketing_manager", getIntValue("marketingManager"));
            form.append("store_manager", getIntValue("storeManager"));
            form.append("reponsible_user", getIntValue("responsible"));
            form.append("parent_bu_id", getIntValue("parentBU"));
            form.append("vendor_r", getIntValue("vendorRequired"));
            form.append("customer_r", getIntValue("accountRequired"));
            form.append("ticket_represented", $('#ticketRepresented').val() || ''); 
            form.append("properties", "[]");
            form.append("bu_user", "1");
            form.append("responsible", getIntValue("responsibleUser"));
            form.append("company_id", $('#CompanyId').val() ?? '{{Auth::user()->company_id}}');

            $.ajax({
                url: "{{ config('app.api_url') }}business_units",
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                timeout: 60000,
                success: function(response) {
                    showToast("Business Unit created successfully", "success");

                    document.getElementById("createBusinessUnitForm").reset();

                    const offcanvasEl = document.getElementById("offcanvasRight");
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                    offcanvas.hide();

                    setTimeout(() => {
                        fetchBusinessUnits();
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", {
                        response: xhr.responseText,
                        status,
                        error
                    });
                    showToast("Failed to create Business Unit: " + error, "danger");
                }
            });
        // } else {
        //     form.append("properties", "[]");
        //     form.append("bu_user", "1");
        //     form.append("status", "1");
        //     form.append("name", getValueOrDefault("editName"));
        //     form.append("email", getValueOrDefault("editEmail"));
        //     form.append("phone", getValueOrDefault("editPhone"));
        //     form.append("alternate_phone", getValueOrDefault("editAltPhone"));
        //     form.append("country", getValueOrDefault("editCountry"));
        //     form.append("state", getValueOrDefault("editState"));
        //     form.append("city", getValueOrDefault("editCity"));
        //     form.append("address_1", getValueOrDefault("editAddress1"));
        //     form.append("address_2", getValueOrDefault("editAddress2"));
        //     form.append("longitude", getValueOrDefault("editLongitude"));
        //     form.append("latitude", getValueOrDefault("editLatitude"));
        //     form.append("zipcode", getValueOrDefault("editZipcode"));
        //     form.append("marketing_manager", getIntValue("editMarketingManager"));
        //     form.append("store_manager", getIntValue("editStoreManager"));
        //     form.append("reponsible_user", getIntValue("editResponsible"));
        //     form.append("parent_bu_id", getIntValue("editParentBU"));
        //     form.append("vendor_r", getIntValue("editVendorRequired"));
        //     form.append("customer_r", getIntValue("editAccountRequired"));
        //     form.append("properties", "[]");
        //     form.append("bu_user", "1");
        //     form.append("responsible", getIntValue("responsibleUser"));
        //     form.append("company_id", $('#editCompanyId').val() ?? '{{Auth::user()->company_id}}');

        //     $.ajax({
        //         url: "{{ config('app.api_url') }}business_units",
        //         method: "POST",
        //         data: form,
        //         processData: false,
        //         contentType: false,
        //         timeout: 60000,
        //         success: function(response) {
        //             showToast("Business Unit created successfully", "success");

        //             document.getElementById("createBusinessUnitForm").reset();

        //             const offcanvasEl = document.getElementById("offcanvasRight");
        //             const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
        //             offcanvas.hide();

        //             setTimeout(() => {
        //                 fetchBusinessUnits();
        //             }, 500);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error("AJAX Error:", {
        //                 response: xhr.responseText,
        //                 status,
        //                 error
        //             });
        //             showToast("Failed to create Business Unit: " + error, "danger");
        //         }
        //     });
        // }
    }

    function editBusinessUnit(id) {
        $.ajax({
            url: `{{ config('app.api_url') }}business_units/${id}`,
            method: "GET",
            success: function(response) {
                console.log("response", response);
                const bu = response.data?.[0] ?? response[0];

                // Set basic fields
                $('#editBusinessUnitId').val(bu.id);
                $('#editName').val(bu.name || '');
                $('#editEmail').val(bu.email || '');
                $('#editPhone').val(bu.phone || '');
                $('#editAltPhone').val(bu.alternate_phone || '');
                $('#editCountry').val(bu.country || '');
                $('#editState').val(bu.state || '');
                $('#editCity').val(bu.city || '');
                $('#editAddress1').val(bu.address_1 || '');
                $('#editAddress2').val(bu.address_2 || '');
                $('#editLongitude').val(bu.longitude || '');
                $('#editLatitude').val(bu.latitude || '');
                $('#editZipcode').val(bu.zipcode || '');
                editMarketingManager.setChoiceByValue(bu.marketing_manager || '');
                editResponsible.setChoiceByValue(bu.reponsible_user || bu.responsible || bu.bu_user || '');
                editStoreManager.setChoiceByValue(bu.store_manager || '');
                editCompanyId.setChoiceByValue(bu.company_id || 1);
                editbu.setChoiceByValue(bu.parent_bu_id  || 1);
                
                $('#editVendorRequired').val(bu.vendor_r || 'no').trigger('change');
                $('#editAccountRequired').val(bu.customer_r || 'no').trigger('change');
                $('#editTicketRepresented').val(bu.ticket_represented || '');
           
                

                // Handle properties JSON
                $('#editProperties').val(bu.properties ? JSON.stringify(bu.properties) : '[]');

                // Set active status
                const statusValue = bu.status !== undefined ? bu.status : bu.active;
                $('#editActiveStatus').prop('checked', statusValue == 1);


                // Show the edit offcanvas
                const offcanvasEl = document.getElementById("editOffcanvas");
                const instance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                instance.show();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching business unit:", error);
                showToast("Unable to fetch Business Unit details.", "danger");
            }
        });
    }

    function updateData(event) {
        // Prevent default only if event exists
        if (event && typeof event.preventDefault === 'function') {
            event.preventDefault();
        }

        const id = $('#editBusinessUnitId').val();

        const safeTrim = (value) => {
            return (typeof value === 'string' || value instanceof String) ? value.trim() : '';
        };

        if('{{Auth::user()->id}}'==1){
            const companyId = $('#editCompanyId').val();

        }else{
            const companyId = '{{ Auth::user()->company_id }}'

        }

        if (!companyId) {
            showToast("Company is required", "warning");
            return;
        }

        const data = {

            name: safeTrim($('#editName').val()),
            email: safeTrim($('#editEmail').val()),
            phone: safeTrim($('#editPhone').val()),
            alternate_phone: safeTrim($('#editAltPhone').val()),
            country: safeTrim($('#editCountry').val()),
            state: safeTrim($('#editState').val()),
            city: safeTrim($('#editCity').val()),
            address_1: safeTrim($('#editAddress1').val()),
            address_2: safeTrim($('#editAddress2').val()),
            longitude: safeTrim($('#editLongitude').val()),
            latitude: safeTrim($('#editLatitude').val()),
            marketing_manager: $('#editMarketingManager').val(),
            reponsible_user: $('#editResponsible').val(),
            responsible: $('#editResponsible').val(),
            bu_user: $('#editResponsible').val(),
            store_manager: $('#editStoreManager').val(),
            company_id: companyId,
            parent_bu_id: $('#editParentBU').val(),
            zipcode: safeTrim($('#editZipcode').val()),
            status: $('#editActiveStatus').is(':checked') ? 1 : 0,
            active: $('#editActiveStatus').is(':checked') ? 1 : 0,
            properties: $('#editProperties').val() || '[]',
            vendor_r: $('#editVendorRequired').val() || 'no',
            customer_r: $('#editAccountRequired').val() || 'no',
            ticket_represented: $('#editTicketRepresented').val() || '', 
        };

        if (!data.name || !data.email) {
            showToast("Name and Email are required", "warning");
            return;
        }

        console.log("Submitting data:", data);

        $.ajax({
            url: `{{ config('app.api_url') }}business_units/${id}`,
            method: "POST",
            data: data,
            success: function(response) {
                showToast("Business Unit updated successfully", "success");
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                offcanvas.hide();
                setTimeout(fetchBusinessUnits, 500);
            },
            error: function(xhr) {
                console.error("Update error:", xhr.responseText);
                let errorMessage = "Failed to update Business Unit";
                if (xhr.responseJSON?.message) {
                    errorMessage += ": " + xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage += ": " + xhr.statusText;
                }
                showToast(errorMessage, "danger");
            }
        });
    }

    function initMap() {
        const defaultLocation = {
            lat: 24.8607,
            lng: 67.0011
        };

        // --------- CREATE FORM MAP ---------
        const createMapElement = document.getElementById("map");
        if (createMapElement) {
            createMap = new google.maps.Map(createMapElement, {
                center: defaultLocation,
                zoom: 13
            });

            createMarker = new google.maps.Marker({
                position: defaultLocation,
                map: createMap,
                draggable: true
            });

            createMarker.addListener("dragend", () => updateLocation(createMarker.getPosition(), 'create'));

            const createInput = document.getElementById("mapSearch");
            const createSearchBox = new google.maps.places.SearchBox(createInput);
            createMap.controls[google.maps.ControlPosition.TOP_LEFT].push(createInput);

            createSearchBox.addListener("places_changed", () => {
                const places = createSearchBox.getPlaces();
                if (!places || places.length === 0) return;

                const place = places[0];
                if (!place.geometry || !place.geometry.location) return;

                updateLocation(place.geometry.location, 'create');
            });
        }

        // --------- EDIT FORM MAP ---------
        const editMapElement = document.getElementById("editMap");
        if (editMapElement) {
            editMap = new google.maps.Map(editMapElement, {
                center: defaultLocation,
                zoom: 13
            });

            editMarker = new google.maps.Marker({
                position: defaultLocation,
                map: editMap,
                draggable: true
            });

            editMarker.addListener("dragend", () => updateLocation(editMarker.getPosition(), 'edit'));

            const editInput = document.getElementById("editMapSearch");
            const editSearchBox = new google.maps.places.SearchBox(editInput);
            editMap.controls[google.maps.ControlPosition.TOP_LEFT].push(editInput);

            editSearchBox.addListener("places_changed", () => {
                const places = editSearchBox.getPlaces();
                if (!places || places.length === 0) return;

                const place = places[0];
                if (!place.geometry || !place.geometry.location) return;

                updateLocation(place.geometry.location, 'edit');
            });
        }
    }

    function updateLocation(location, type) {
        if (type === 'create') {
            createMarker.setPosition(location);
            createMap.panTo(location);
            $('#latitude').val(location.lat());
            $('#longitude').val(location.lng());
        } else if (type === 'edit') {
            editMarker.setPosition(location);
            editMap.panTo(location);
            $('#editLatitude').val(location.lat());
            $('#editLongitude').val(location.lng());
        }
    }

    window.initMap = initMap;

    // function updateBusinessUnitToggle(id, element) {
    //     if (!hasPermission('business_unit', 'update')) {
    //         showToast("No permission to change status", "warning");
    //         // revert checkbox
    //         $(element).prop('checked', !$(element).prop('checked'));
    //         return;
    //     }
    // }
</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection