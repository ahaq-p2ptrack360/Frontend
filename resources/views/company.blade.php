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
@slot('title') Company @endslot
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

    .custom-file-dropzone {
        width: 100%;
        border: 2px dashed #007bff;
        padding: 80px;
        text-align: center;
        margin-top: 10px;
        color: #6c757d;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .custom-file-dropzone:hover {
        background-color: #f8f9fa;
    }

    .custom-file-input {
        display: none;
    }

    .form-check.form-switch {
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-check.form-switch .form-check-input {
    width: 3.2em;      
    height: 1.8em;     
    cursor: pointer;
}

.form-check.form-switch .form-check-input:checked {
    background-color: #5a58eb;
    border-color: #5a58eb;
}

.form-check.form-switch .form-check-input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
                <table id="company-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>No. Of Licenses</th>
                            <th>Subscription</th>
                            <th>Created At</th>
                            <th>Edit</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="table-loading" style="display: none;">Loading...</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Company</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createCompanyForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middleName" placeholder="Enter Middle Name">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mobile" placeholder="Enter Mobile Number" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="companyName" placeholder="Enter Company Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Support Email</label>
                    <input type="email" class="form-control" id="supportEmail" placeholder="Support Email">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Registration Number</label>
                    <input type="text" class="form-control" id="registrationNumber" placeholder="Registration Number">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telephone</label>
                    <input type="text" class="form-control" id="telephone" placeholder="Telephone">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Support Contact</label>
                    <input type="text" class="form-control" id="supportContact" placeholder="Support Contact">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-control" id="website" placeholder="Website">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Number of Licenses <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="licences" placeholder="Number of Licenses" required>
                </div>

                <div class="col-md-4">
                    <label for="domain" class="form-label">Domain <span class="text-danger">*</span></label>
                    <select class="form-control" id="domain" required>
                        <option value="">Select Domain</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="subscriptionPackage" class="form-label">Subscription Package <span
                            class="text-danger">*</span></label>
                    <select class="form-control" id="subscriptionPackage" required>
                        <option value="">Select Package</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" id="country_id" name="country_id"
                        placeholder="Enter Country">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State/Province</label>
                    <input type="text" class="form-control" id="state" placeholder="State/Province">
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" id="city" placeholder="City">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Address 1</label>
                    <input type="text" class="form-control" id="address1" placeholder="Address 1">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="address2" placeholder="Address 2">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" placeholder="Latitude">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" placeholder="Longitude">
                </div>
            </div>

            <!-- Map Search Input -->
            <div class="mt-4">
                <div class="map-search-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="mapSearch" placeholder="Search for a location...">
                    </div>
                </div>
                <div id="map" style="height: 400px; border-radius: 8px;"></div>
            </div>
            <div class="mb-3">
                <div class="custom-file-dropzone" id="createDropzone">
                    <input type="file" class="custom-file-input" id="fileUpload"
                        accept="image/png, image/jpeg, image/jpg">
                    <label for="fileUpload" class="dropzone-label">
                        <i class="ri-upload-cloud-2-line fs-3"></i>
                        <p>Drag and drop an image here (PNG, JPG, JPEG) or click to browse</p>
                        <div id="createPreview" class="mt-2"></div>
                    </label>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createCompany()">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Company</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editCompanyForm">
            <input type="hidden" id="editCompanyId">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editFirstName" placeholder="Enter First Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="editMiddleName" placeholder="Enter Middle Name">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editLastName" placeholder="Enter Last Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editMobile" placeholder="Enter Mobile Number" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editCompanyName" placeholder="Enter Company Name"
                        required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="editEmail" placeholder="Enter Email" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Support Email</label>
                    <input type="email" class="form-control" id="editSupportEmail" placeholder="Support Email">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Registration Number</label>
                    <input type="text" class="form-control" id="editRegistrationNumber"
                        placeholder="Registration Number">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telephone</label>
                    <input type="text" class="form-control" id="editTelephone" placeholder="Telephone">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Support Contact</label>
                    <input type="text" class="form-control" id="editSupportContact" placeholder="Support Contact">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-control" id="editWebsite" placeholder="Website">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Number of Licenses <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="editLicences" placeholder="Number of Licenses"
                        required>
                </div>

                <div class="col-md-4">
                    <label for="editDomain" class="form-label">Domain <span class="text-danger">*</span></label>
                    <select class="form-control" id="editDomain" required>
                        <option value="">Select Domain</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="editSubscriptionPackage" class="form-label">Subscription Package <span
                            class="text-danger">*</span></label>
                    <select class="form-control" id="editSubscriptionPackage" required>
                        <option value="">Select Package</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" id="editCountry_id" name="country_id">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State/Province</label>
                    <input type="text" class="form-control" id="editState" placeholder="State/Province">
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" id="editCity" placeholder="City">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Address 1</label>
                    <input type="text" class="form-control" id="editAddress1" placeholder="Address 1">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="editAddress2" placeholder="Address 2">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="editLatitude" placeholder="Latitude">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="editLongitude" placeholder="Longitude">
                </div>
            </div>

            <!-- Map Search Input -->
            <div class="mt-4">
                <div class="map-search-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="editMapSearch"
                            placeholder="Search for a location...">
                    </div>
                </div>
                <div id="editMap" style="height: 400px; border-radius: 8px;"></div>
            </div>
            <div class="mb-3">
                <div class="custom-file-dropzone" id="editDropzone">
                    <input type="file" class="custom-file-input" id="editFileUpload"
                        accept="image/png, image/jpeg, image/jpg">
                    <label for="editFileUpload" class="dropzone-label">
                        <i class="ri-upload-cloud-2-line fs-3"></i>
                        <p>Drag and drop an image here (PNG, JPG, JPEG) or click to browse</p>
                        <div id="editPreview" class="mt-2"></div>
                    </label>
                </div>
                <div id="currentImagePreview" class="mt-2"></div>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateCompany()">Update</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNyJWb04pByaU1CTmimoWNl3b86VV6qZ8&callback=initMap&libraries=places&v=weekly"
    defer></script> -->
    <script
    src="https://maps.googleapis.com/maps/api/js?key=eyJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiJwdHBAc3RhZ2FwbC5jb20iLCJzdWIiOiJwdHBAc3RhZ2FwbC5jb20iLCJpYXQiOjE3Njg0NjI5OTksImV4cCI6MTc2ODU0OTM5OX0.hCu0HQuEEmbSoBjLdeEwqOMorUhBHjveceAC1s1XjUU&callback=initMap&libraries=places&v=weekly"
    defer>
</script>

<script>
    let companyDataTable;
    let map, marker, editMap, editMarker;
    let domains = [];
    let subscriptions = [];

    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';



    $(document).ready(function() {
        initFileUploads();
        fetchDomains();
        fetchSubscriptions();

        loadPermissions(roleId)
        .then(() => {
            fetchTable(); // columns will respect loaded permissions
            applyPermissionUI()
        })
        .catch(err => {
            console.warn("Permission load failed, continuing without permissions:", err);
            fetchTable(); // still load table if permissions fail
        });
    });


    // Initialize file upload functionality
    function initFileUploads() {
        const configs = [{
                dropzone: 'createDropzone',
                input: 'fileUpload',
                preview: 'createPreview'
            },
            {
                dropzone: 'editDropzone',
                input: 'editFileUpload',
                preview: 'editPreview'
            }
        ];

        configs.forEach(({
            dropzone,
            input,
            preview
        }) => {
            const dropzoneEl = document.getElementById(dropzone);
            const inputEl = document.getElementById(input);
            const previewEl = document.getElementById(preview);

            setupFileUpload(dropzoneEl, inputEl, previewEl);

            // if (input === 'editFileUpload' && inputEl.dataset.currentImage) {
            //     document.getElementById('currentImagePreview').innerHTML = `
            //             <p>Current Image:</p>
            //             <img src="${inputEl.dataset.currentImage}" class="img-thumbnail">
            //         `;
            // }
        });
    }

    function setupFileUpload(dropzone, input, preview) {
        const handleFile = (file) => {
            if (!file.type.match('image.*')) {
                showToast('Please select an image file (PNG, JPG, JPEG)', 'danger');
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `
                        <p>Selected Image:</p>
                        <img src="${e.target.result}" class="img-thumbnail">
                    `;
                dropzone.style.borderColor = '#28a745';
            };
            reader.readAsDataURL(file);
        };

        const preventDefaults = e => {
            e.preventDefault();
            e.stopPropagation();
        };

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt =>
            dropzone.addEventListener(evt, preventDefaults)
        );

        ['dragenter', 'dragover'].forEach(evt =>
            dropzone.addEventListener(evt, () => dropzone.classList.add('highlight'))
        );

        ['dragleave', 'drop'].forEach(evt =>
            dropzone.addEventListener(evt, () => dropzone.classList.remove('highlight'))
        );

        dropzone.addEventListener('click', () => input.click());

        input.addEventListener('change', e => {
            if (e.target.files[0]) handleFile(e.target.files[0]);
        });

        dropzone.addEventListener('drop', e => {
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        });
    }

    // function fetchDomains() {
    //     $.ajax({
    //         url: "{{ config('app.api_url') }}domains/",
    //         method: "GET",
    //         success: function(data) {
    //             domains = data;
    //             populateDropdowns();
    //         },
    //         error: function() {
    //             showToast("Failed to load domains", "danger");
    //         }
    //     });
    // }
    function fetchDomains() {
    console.log('Fetching domains from:', "{{ config('app.api_url') }}domains");

    $.ajax({
        url: "{{ config('app.api_url') }}domains", // Trailing slash hata kar try karein
        method: "GET",
        success: function(response) {

            // Response structure check karein
            if (Array.isArray(response)) {
                domains = response;
            } else if (response && Array.isArray(response.data)) {
                domains = response.data;
            } else if (response && response.domains) {
                domains = response.domains;
            } else {
                console.warn('Unexpected domains response format, using empty array');
                domains = [];
            }

            if (domains.length === 0) {
                console.warn('No domains found in response');
                showToast("No domains available", "warning");
            }

            populateDropdowns();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching domains:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            showToast("Failed to load domains: " + error, "danger");
        }
    });
}

    function fetchSubscriptions() {
        $.ajax({
            url: "{{ config('app.api_url') }}subscription-packages",
            method: "GET",
            success: function(data) {
                subscriptions = data;
                populateDropdowns();
            },
            error: function() {
                showToast("Failed to load subscriptions", "danger");
            }
        });
    }

    // function populateDropdowns() {
    //     // Populate create form dropdowns
    //     const domainDropdown = $('#domain');
    //     domainDropdown.empty();
    //     domainDropdown.append('<option value="">Select Domain</option>');
    //     domains.forEach(domain => {
    //         domainDropdown.append(`<option value="${domain.id}">${domain.title}</option>`);
    //     });

    //     const subscriptionDropdown = $('#subscriptionPackage');
    //     subscriptionDropdown.empty();
    //     subscriptionDropdown.append('<option value="">Select Package</option>');
    //     subscriptions.forEach(sub => {
    //         subscriptionDropdown.append(`<option value="${sub.id}">${sub.title}</option>`);
    //     });

    //     // Populate edit form dropdowns
    //     const editDomainDropdown = $('#editDomain');
    //     editDomainDropdown.empty();
    //     editDomainDropdown.append('<option value="">Select Domain</option>');
    //     domains.forEach(domain => {
    //         editDomainDropdown.append(`<option value="${domain.id}">${domain.title}</option>`);
    //     });

    //     const editSubscriptionDropdown = $('#editSubscriptionPackage');
    //     editSubscriptionDropdown.empty();
    //     editSubscriptionDropdown.append('<option value="">Select Package</option>');
    //     subscriptions.forEach(sub => {
    //         editSubscriptionDropdown.append(`<option value="${sub.id}">${sub.title}</option>`);
    //     });
    // }

function populateDropdowns() {
    const domainDropdown = $('#domain');
    domainDropdown.empty();
    domainDropdown.append('<option value="">Select Domain</option>');
    if (domains && domains.length > 0) {
        domains.forEach(domain => {
            const domainId = domain.id || domain.domain_id || domain.value;
            const domainName = domain.title || domain.name || domain.domain_name || domain.text || 'Unknown Domain';
            if (domainId && domainName) {
                domainDropdown.append(`<option value="${domainId}">${domainName}</option>`);
            }
        });
    } else {
        domainDropdown.append('<option value="">No domains available</option>');
    }

    // Edit Domain Dropdown
    const editDomainDropdown = $('#editDomain');
    editDomainDropdown.empty();
    editDomainDropdown.append('<option value="">Select Domain</option>');
    if (domains && domains.length > 0) {
        domains.forEach(domain => {
            const domainId = domain.id || domain.domain_id || domain.value;
            const domainName = domain.title || domain.name || domain.domain_name || domain.text || 'Unknown Domain';
            if (domainId && domainName) {
                editDomainDropdown.append(`<option value="${domainId}">${domainName}</option>`);
            }
        });
    } else {
        editDomainDropdown.append('<option value="">No domains available</option>');
    }

    // Subscription Dropdowns
    const subscriptionDropdown = $('#subscriptionPackage');
    subscriptionDropdown.empty();
    subscriptionDropdown.append('<option value="">Select Package</option>');
    if (subscriptions && subscriptions.length > 0) {
        subscriptions.forEach(sub => {
            const subId = sub.id || sub.subscription_id || sub.value;
            const subName = sub.title || sub.name || sub.package_name || sub.text || 'Unknown Subscription';
            if (subId && subName) {
                subscriptionDropdown.append(`<option value="${subId}">${subName}</option>`);
            }
        });
    } else {
        console.warn('No subscriptions available for dropdown');
        subscriptionDropdown.append('<option value="">No subscriptions available</option>');
    }

    // Edit Subscription Dropdown
    const editSubscriptionDropdown = $('#editSubscriptionPackage');
    editSubscriptionDropdown.empty();
    editSubscriptionDropdown.append('<option value="">Select Package</option>');
    if (subscriptions && subscriptions.length > 0) {
        subscriptions.forEach(sub => {
            const subId = sub.id || sub.subscription_id || sub.value;
            const subName = sub.title || sub.name || sub.package_name || sub.text || 'Unknown Subscription';
            if (subId && subName) {
                editSubscriptionDropdown.append(`<option value="${subId}">${subName}</option>`);
            }
        });
    } else {
        editSubscriptionDropdown.append('<option value="">No subscriptions available</option>');
    }
}



    function applyPermissionUI() {
        // Example: hide Add New if no create permission on tickets (adjust section name if different)
        if (!hasPermission('ticket', 'create')) {
            $('#addNewBtn').hide();
        } else {
            $('#addNewBtn').show();
        }
        if (!hasPermission('ticket', 'update')) {
            // remove Edit header cell
            $('#company-table thead tr th').filter(function() {

                return $(this).text().trim() == 'Edit';
            }).remove();
            // $('#company-table thead tr th').filter(function() {

            //     return $(this).text().trim() == 'Active';
            // }).remove();
        }
    }

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

function fetchTable() {
    const tableEl = document.getElementById("company-table");
    const loadingEl = document.getElementById("table-loading");
    const exportContainer = $('#customExportButtons');

    if (!tableEl || !loadingEl) {
        console.error("#company-table or #table-loading not found");
        return;
    }

    loadingEl.style.display = "block";

    if ($.fn.DataTable.isDataTable('#company-table')) {
        $('#company-table').DataTable().destroy();
        $('#company-table tbody').empty();
    }

    $.ajax({
        url: "{{ config('app.api_url') }}companies",
        method: "GET",
        success: function (data) {
            // base columns
            const cols = [
                { data: 'id', defaultContent: '-', title: 'S.No' },
                { data: 'name', defaultContent: '-', title: 'Name' },
                { data: 'email', defaultContent: '-', title: 'Email' },
                { data: 'mobile', defaultContent: '-', title: 'Mobile' },
                { data: 'licences', defaultContent: '-', title: 'Licences' },
                {
                    data: 'subscription_id',
                    title: 'Subscription',
                    render: function (d) {
                        const sub = (typeof subscriptions !== 'undefined' && subscriptions) ? subscriptions.find(s => s.id == d) : null;
                        return sub ? sub.title : '-';
                    }
                },
                { data: 'created_at', defaultContent: '-', title: 'Created At' }
            ];

            // Edit column if allowed
            if (hasPermission('company', 'update')) {
                cols.push({
                    data: null,
                    orderable: false,
                    searchable: false,
                    title: 'Edit',
                    render: function (rowData) {
                        return `
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-primary"
                               onclick="editCompany(${rowData.id})">
                               <i class="ri-pencil-fill"></i>
                            </a>`;
                    }
                });
            }

            // Active/status toggle if allowed

                cols.push({
                    data: 'active',
                    orderable: false,
                    searchable: false,
                    title: 'Active',
                    render: function (data, type, row) {
                        const checked = data == 1 ? 'checked' : '';
                        const disabled = !hasPermission('company', 'update') ? 'disabled' : '';
                        return `
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    onchange="updateStatus(${row.id}, this)" ${checked} ${disabled}>
                            </div>`;
                    }
                });


            companyDataTable = $('#company-table').DataTable({
                data: data,
                responsive: true,
                paging: true,
                searching: true,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'collection',
                    text: '<i class="ri-file-excel-2-line"></i> Export Excel',
                    className: 'btn btn-success',
                    buttons: [
                        { extend: 'copy', text: '<i class="fas fa-copy"></i> Copy' },
                        { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel' },
                        { extend: 'csv', text: '<i class="fas fa-file-csv"></i> CSV' },
                        { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> PDF' },
                        { extend: 'print', text: '<i class="fas fa-print"></i> Print' }
                    ]
                }],
                columns: cols,
                initComplete: function () {
                    loadingEl.style.display = "none";
                    setTimeout(() => {
                        exportContainer.html('');
                        if (companyDataTable.buttons) companyDataTable.buttons().container().appendTo(exportContainer);
                    }, 0);
                }
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
            loadingEl.textContent = "Failed to load data.";
        }
    });
}




    function initMap() {
        const defaultLocation = {
            lat: 24.8607,
            lng: 67.0011
        };

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
            const position = marker.getPosition();
            $('#latitude').val(position.lat());
            $('#longitude').val(position.lng());
        });

        const input = document.getElementById("mapSearch");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            if (!places || places.length === 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            updateMapLocation(place.geometry.location, map, marker);
            $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());
        });

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
            const position = editMarker.getPosition();
            $('#editLatitude').val(position.lat());
            $('#editLongitude').val(position.lng());
        });

        const editInput = document.getElementById("editMapSearch");
        const editSearchBox = new google.maps.places.SearchBox(editInput);
        editMap.controls[google.maps.ControlPosition.TOP_LEFT].push(editInput);

        editSearchBox.addListener("places_changed", () => {
            const places = editSearchBox.getPlaces();
            if (!places || places.length === 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            updateMapLocation(place.geometry.location, editMap, editMarker);
            $('#editLatitude').val(place.geometry.location.lat());
            $('#editLongitude').val(place.geometry.location.lng());
        });
    }

    function updateMapLocation(location, map, marker) {
        marker.setPosition(location);
        map.panTo(location);
    }

    // function createCompany() {
    //     const formData = new FormData();
    //     formData.append('country_id', $('#country_id').val());
    //     formData.append('status', 1);
    //     formData.append('first_name', $('#firstName').val());
    //     formData.append('middle_name', $('#middleName').val());
    //     formData.append('last_name', $('#lastName').val());
    //     formData.append('mobile', $('#mobile').val());
    //     formData.append('name', $('#companyName').val());
    //     formData.append('email', $('#email').val());
    //     formData.append('support_email', $('#supportEmail').val());
    //     formData.append('registration_number', $('#registrationNumber').val());
    //     formData.append('telephon', $('#telephone').val());
    //     formData.append('support_contact', $('#supportContact').val());
    //     formData.append('website', $('#website').val());
    //     formData.append('licences', $('#licences').val());
    //     formData.append('domain_id', $('#domain').val());
    //     formData.append('subscription_id', $('#subscriptionPackage').val());
    //     formData.append('state_id', $('#state').val());
    //     formData.append('city_id', $('#city').val());
    //     formData.append('address_1', $('#address1').val());
    //     formData.append('address_2', $('#address2').val());
    //     formData.append('latitude', $('#latitude').val());
    //     formData.append('longitude', $('#longitude').val());
    //     formData.append('datalines', $('#datalines').val() || '');
    //     formData.append('communication', $('#communication').val() || '');
    //     formData.append('telebox', $('#telebox').val() || '');
    //     formData.append('zipcode', $('#zipcode').val() || '');


    //     const fileInput = $('#fileUpload')[0];
    //     if (fileInput.files.length > 0) {
    //         formData.append('profile', fileInput.files[0]);
    //     }

    //     $.ajax({
    //         url: "{{ config('app.api_url') }}companies",
    //         method: "POST",
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function(response) {
    //             showToast("Company created successfully", "success");
    //             $('#createCompanyForm')[0].reset();
    //             const offcanvasEl = document.getElementById("offcanvasRight");
    //             const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
    //             offcanvas.hide();

    //             setTimeout(() => {
    //                 fetchTable();
    //             }, 500);
    //         },
    //         error: function(xhr) {
    //             const errorMessage = xhr.responseJSON?.message || "Failed to create company";
    //             showToast(errorMessage, "danger");
    //         }
    //     });
    // }

    function createCompany() {
    const formData = new FormData();

    // Debugging ke liye console log
    console.log('Create Form Values:');
    console.log('Country:', $('#country_id').val());
    console.log('State:', $('#state').val());
    console.log('City:', $('#city').val());

    // Country, State, City values ko string mein convert karein
    const countryId = $('#country_id').val();
    const stateId = $('#state').val();
    const cityId = $('#city').val();

    formData.append('country_id', countryId ? countryId.toString() : '');
    formData.append('state_id', stateId ? stateId.toString() : '');
    formData.append('city_id', cityId ? cityId.toString() : '');

    formData.append('status', 1);
    formData.append('first_name', $('#firstName').val());
    formData.append('middle_name', $('#middleName').val());
    formData.append('last_name', $('#lastName').val());
    formData.append('mobile', $('#mobile').val());
    formData.append('name', $('#companyName').val());
    formData.append('email', $('#email').val());
    formData.append('support_email', $('#supportEmail').val());
    formData.append('registration_number', $('#registrationNumber').val());
    formData.append('telephon', $('#telephone').val());
    formData.append('support_contact', $('#supportContact').val());
    formData.append('website', $('#website').val());
    formData.append('licences', $('#licences').val());
    formData.append('domain_id', $('#domain').val());
    formData.append('subscription_id', $('#subscriptionPackage').val());
    formData.append('address_1', $('#address1').val());
    formData.append('address_2', $('#address2').val());
    formData.append('latitude', $('#latitude').val());
    formData.append('longitude', $('#longitude').val());
    formData.append('datalines', $('#datalines').val() || '');
    formData.append('communication', $('#communication').val() || '');
    formData.append('telebox', $('#telebox').val() || '');
    formData.append('zipcode', $('#zipcode').val() || '');

    const fileInput = $('#fileUpload')[0];
    if (fileInput.files.length > 0) {
        formData.append('profile', fileInput.files[0]);
    }

    // FormData content check karein
    console.log('Create FormData Contents:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }

    $.ajax({
        url: "{{ config('app.api_url') }}companies",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showToast("Company created successfully", "success");
            $('#createCompanyForm')[0].reset();
            const offcanvasEl = document.getElementById("offcanvasRight");
            const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
            offcanvas.hide();

            setTimeout(() => {
                fetchTable();
            }, 500);
        },
        error: function(xhr) {
            console.error('Create Error:', xhr);
            console.error('Response Text:', xhr.responseText);
            const errorMessage = xhr.responseJSON?.message || "Failed to create company";
            showToast(errorMessage, "danger");
        }
    });
}

    function editCompany(id) {
    console.log('Editing company with ID:', id);

    $.ajax({
        url: `{{ config('app.api_url') }}companies/${id}`,
        method: "GET",
        success: function(response) {
            console.log('Company API Full Response:', response);

            let company;

            if (Array.isArray(response) && response.length > 0) {
                company = response[0];
            } else if (response && typeof response === 'object') {
                company = response;
            } else if (response && response.data) {
                company = response.data;
            } else {
                console.error('Unexpected response format:', response);
                showToast("Invalid company data format", "danger");
                return;
            }

            if (!company) {
                showToast("Company data not found", "danger");
                return;
            }

            console.log('Company data to edit:', company);

            // ✅ FORM FIELDS PROPERLY SET KAREIN
            $('#editCompanyId').val(company.id || '');
            $('#editFirstName').val(company.first_name || '');
            $('#editMiddleName').val(company.middle_name || '');
            $('#editLastName').val(company.last_name || '');
            $('#editMobile').val(company.mobile || '');
            $('#editCompanyName').val(company.name || '');
            $('#editEmail').val(company.email || '');
            $('#editSupportEmail').val(company.support_email || '');
            $('#editRegistrationNumber').val(company.registration_number || '');
            $('#editTelephone').val(company.telephon || '');
            $('#editSupportContact').val(company.support_contact || '');

            $('#editCountry_id').val(company.country_id && company.country_id !== "0" ? company.country_id : '');
            $('#editState').val(company.state_id && company.state_id !== "0" ? company.state_id : '');
            $('#editCity').val(company.city_id && company.city_id !== "0" ? company.city_id : '');

            $('#editWebsite').val(company.website || '');
            $('#editLicences').val(company.licences || '');
            $('#editDomain').val(company.domain_id || '');
            $('#editSubscriptionPackage').val(company.subscription_id || '');
            $('#editAddress1').val(company.address_1 || '');
            $('#editAddress2').val(company.address_2 || '');
            $('#editLatitude').val(company.latitude || '');
            $('#editLongitude').val(company.longitude || '');
            $('#editZipcode').val(company.zipcode || '');

            if (company.active !== undefined) {
                $('#editActiveStatus').prop('checked', company.active == 1);
            }

            setTimeout(() => {
                if (company.domain_id && company.domain_id !== "0") {
                    $('#editDomain').val(company.domain_id).trigger('change');
                }
                if (company.subscription_id && company.subscription_id !== "0") {
                    $('#editSubscriptionPackage').val(company.subscription_id).trigger('change');
                }
            }, 100);

            const imagePreview = $('#currentImagePreview');
            imagePreview.empty();
            // if (company.profile && company.profile !== "null" && company.profile !== "") {
            //     const imageUrl = company.profile.startsWith('http') ? company.profile : `/storage/${company.profile}`;
            //     imagePreview.html(`
            //         <p><strong>Current Image:</strong></p>
            //         <img src="${imageUrl}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
            //     `);
            // }

            $('#editPreview').empty();
            $('#editFileUpload').val('');
            document.getElementById('editDropzone').style.borderColor = '#007bff';

            if (company.latitude && company.longitude && company.latitude !== "" && company.longitude !== "") {
                try {
                    const location = new google.maps.LatLng(
                        parseFloat(company.latitude),
                        parseFloat(company.longitude)
                    );
                    if (editMap && editMarker) {
                        updateMapLocation(location, editMap, editMarker);
                    }

                    setTimeout(() => {
                        $('#editMapSearch').val(company.address_1 || '');
                    }, 500);
                } catch (error) {
                    console.error('Error updating map location:', error);
                }
            }

            const offcanvasEl = document.getElementById("editOffcanvas");
            const instance = new bootstrap.Offcanvas(offcanvasEl);
            instance.show();

            console.log('Edit form populated successfully');

        },
        error: function(xhr, status, error) {
            console.error('Error fetching company details:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            showToast("Unable to fetch company details.", "danger");
        }
    });
}

    function updateCompany() {
    const companyId = $('#editCompanyId').val();
    const formData = new FormData();

    formData.append('_method', 'PUT');
    formData.append('id', companyId);

    const firstName = $('#editFirstName').val();
    const lastName = $('#editLastName').val();
    const companyName = $('#editCompanyName').val();
    const email = $('#editEmail').val();
    const mobile = $('#editMobile').val();
    const licences = $('#editLicences').val();
    const domainId = $('#editDomain').val();
    const subscriptionId = $('#editSubscriptionPackage').val();

    if (!firstName || !lastName || !companyName || !email || !mobile || !licences || !domainId || !subscriptionId) {
        showToast("Please fill all required fields", "danger");
        return;
    }

    formData.append('first_name', firstName);
    formData.append('middle_name', $('#editMiddleName').val() || '');
    formData.append('last_name', lastName);
    formData.append('mobile', mobile);
    formData.append('name', companyName);
    formData.append('email', email);
    formData.append('support_email', $('#editSupportEmail').val() || '');
    formData.append('registration_number', $('#editRegistrationNumber').val() || '');
    formData.append('telephon', $('#editTelephone').val() || '');
    formData.append('support_contact', $('#editSupportContact').val() || '');

    const countryId = $('#editCountry_id').val();
    const stateId = $('#editState').val();
    const cityId = $('#editCity').val();

    formData.append('country_id', countryId && countryId !== "" ? countryId : 0);
    formData.append('state_id', stateId && stateId !== "" ? stateId : 0);
    formData.append('city_id', cityId && cityId !== "" ? cityId : 0);

    formData.append('website', $('#editWebsite').val() || '');
    formData.append('licences', licences);
    formData.append('domain_id', domainId);
    formData.append('subscription_id', subscriptionId);
    formData.append('address_1', $('#editAddress1').val() || '');
    formData.append('address_2', $('#editAddress2').val() || '');
    formData.append('latitude', $('#editLatitude').val() || '');
    formData.append('longitude', $('#editLongitude').val() || '');
    formData.append('communication', 'COM');
    formData.append('datalines', 'datalines');
    formData.append('telebox', 'telebox');
    formData.append('active', $('#editActiveStatus').is(':checked') ? 1 : 0);
    formData.append('status', 1);
    formData.append('zipcode', $('#editZipcode').val() || '321');

    const fileInput = $('#editFileUpload')[0];
    if (fileInput.files.length > 0) {
        formData.append('profile', fileInput.files[0]);
    }

    console.log('FormData Contents:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }

    $.ajax({
        url: `{{ config('app.api_url') }}companies/${companyId}`,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showToast("Company updated successfully", "success");
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
            offcanvas.hide();
            setTimeout(() => {
                fetchTable();
            }, 500);
        },
        error: function(xhr) {
            console.error('Update Error:', xhr);
            console.error('Response Text:', xhr.responseText);
            const errorMessage = xhr.responseJSON?.message || "Failed to update company";
            showToast(errorMessage, "danger");
        }
    });
}


function updateStatus(id, element) {

const isChecked = element.checked ? 1 : 0;

// UI safety
element.disabled = true;

$.ajax({
    url: `{{ config('app.api_url') }}companies/${id}/status`,
    type: "POST",
    data: {
        id: id,
        active: isChecked,
        _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function (response) {

        if (response.success === true) {

            const msg = isChecked
                ? "Company activated successfully"
                : "Company deactivated successfully";

            showToast(msg, "success");

            // ✅ TABLE REFRESH AFTER POPUP
            setTimeout(() => {
                fetchTable();
            }, 500);

        } else {
            element.checked = !isChecked;
            showToast(response.message || "Status update failed", "danger");
        }
    },
    error: function (xhr) {
        element.checked = !isChecked;
        showToast("Server error while updating status", "danger");
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

    window.initMap = initMap;
</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
