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
@slot('title') Domain @endslot
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
        width: 35% !important;
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

    .dynamic-group {
        margin-bottom: 10px;
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

.form-check.form-switch .form-check-input:checked {
    background-color: #5a58eb;
    border-color: #5a58eb;
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
                <table id="domain-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
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
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Domain</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="domainForm">
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" placeholder="Enter Domain Title" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="eligible">
                <label class="form-check-label" for="eligible">Eligible For Store</label>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Additional Fields</label>
                    <button type="button" class="btn btn-success bg-gradient" id="addMoreBtn">
                        <i class="ri-add-line"></i> Add More
                    </button>
                </div>
                <div id="dynamicInputContainer"></div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createDomain()">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Domain</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editDomainForm">
            <input type="hidden" id="editDomainId">

            <div class="mb-3">
                <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editTitle" placeholder="Enter Domain Title" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="editEligible">
                <label class="form-check-label" for="editEligible">Eligible For Store</label>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Additional Fields</label>
                    <button type="button" class="btn btn-success bg-gradient" id="editAddMoreBtn">
                        <i class="ri-add-line"></i> Add More
                    </button>
                </div>
                <div id="editDynamicInputContainer"></div>
            </div>

            <div class="mb-3 form-check form-switch" style="display:none;">
                <input class="form-check-input" type="checkbox" id="editActiveStatus">
                <label class="form-check-label" for="editActiveStatus">Active Status</label>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateDomain()">Update</button>
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

<script>
    let inputCount = 0;
    let editInputCount = 0;

    let domainDataTable;
    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';

    $(document).ready(function() {
        loadPermissions(roleId)
            .then(() => {
                applyPermissionUI();
                fetchDomainTable();
                setupDynamicFields();
            })
            .catch(err => {
                console.error("Init error:", err);
                fetchDomainTable();
                setupDynamicFields();
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

    function getSectionRaw(section) {
        if (!permissions) return null;
        if (permissions[section]) return permissions[section];
        if (section.endsWith('s') && permissions[section.slice(0, -1)]) return permissions[section.slice(0, -1)];
        if (!section.endsWith('s') && permissions[section + 's']) return permissions[section + 's'];
        return null;
    }

    function hasPermission(section, action) {
        if (!permissions || !section || !action) return false;
        try {
            const raw = getSectionRaw(section);
            if (!raw) return false;
            const parsed = typeof raw === 'object' ? raw : JSON.parse(raw);
            return parsed[action] === 1;
        } catch (e) {
            console.warn("Permission parse error", section, action, e);
            return false;
        }
    }

    function applyPermissionUI() {
        // Show/hide add new (assuming there's a button with id addNewBtn for domains)
        if (!hasPermission('domain', 'create')) {
            $('#addNewBtn').hide();
        } else {
            $('#addNewBtn').show();
        }
    }

    function fetchDomainTable() {
        const tableEl = document.getElementById("domain-table");
        const loadingEl = document.getElementById("table-loading");
        const exportContainer = $('#customExportButtons');

        if (!tableEl || !loadingEl) {
            console.error("#domain-table or #table-loading not found");
            return;
        }

        loadingEl.style.display = "block";

        // determine allowed columns
        const canUpdate = hasPermission('domain', 'update');
        const canToggleStatus = hasPermission('domain', 'update_status') || hasPermission('domain', 'update'); // fallback
        const canView = hasPermission('domain', 'read');

        // rebuild header according to permissions
        const $thead = $('#domain-table thead');
        $thead.empty();
        const headers = [
            'S.No',
            'Title',
            'Created At'
        ];
        if (canUpdate) headers.push('Edit');
        headers.push('Status');
        const $tr = $('<tr>');
        headers.forEach(h => $tr.append(`<th>${h}</th>`));
        $thead.append($tr);

        if (typeof domainDataTable !== 'undefined' && domainDataTable) {
            domainDataTable.destroy();
            $('#domain-table tbody').empty();
        }

        $.ajax({
            url: "{{ config('app.api_url') }}domains",
            method: "GET",
            success: function(data) {
                // prepare columns array matching header
                const cols = [{
                        data: 'id',
                        defaultContent: '-',
                        title: 'S.No'
                    },
                    {
                        data: 'title',
                        defaultContent: '-',
                        title: 'Title'
                    },
                    {
                        data: 'created_at',
                        defaultContent: '-',
                        title: 'Created At'
                    }
                ];
                if (canUpdate) {
                    cols.push({
                        data: null,
                        orderable: false,
                        searchable: false,
                        title: 'Edit',
                        render: function(rowData) {
                            return `
                                <a href="javascript:void(0);" 
                                   class="btn btn-sm btn-primary" 
                                   onclick="editDomain(${rowData.id})">
                                   <i class="ri-pencil-fill"></i>
                                </a>`;
                        }
                    });
                }
                
                    cols.push({
                        data: 'active',
                        orderable: false,
                        searchable: false,
                        title: 'Status',
                        render: function(data, type, row) {
                            const checked = data == 1 ? 'checked' : '';
                            const disabled = !hasPermission('domain', 'update') ? 'disabled' : '';
                            return `
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        onchange="domainStatus(${row.id}, this)" ${checked} ${disabled}>
                                </div>`;
                        }
                    });
                

                domainDataTable = $('#domain-table').DataTable({
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
                            exportContainer.html('');
                            if (domainDataTable.buttons) domainDataTable.buttons().container().appendTo(exportContainer);
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


    function setupDynamicFields() {
        // For create form
        $('#addMoreBtn').on('click', function() {
            inputCount++;
            const inputGroup = `
                                <div class="row mb-2 dynamic-group" id="inputGroup-${inputCount}">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="extraField1[]" placeholder="Field Name">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="extraField2[]" placeholder="Field Value">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-danger removeBtn" data-id="${inputCount}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
            $('#dynamicInputContainer').append(inputGroup);
        });

        // For edit form
        $('#editAddMoreBtn').on('click', function() {
            editInputCount++;
            const inputGroup = `
                                <div class="row mb-2 dynamic-group" id="editInputGroup-${editInputCount}">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="editExtraField1[]" placeholder="Field Name">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="editExtraField2[]" placeholder="Field Value">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-danger removeEditBtn" data-id="${editInputCount}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
            $('#editDynamicInputContainer').append(inputGroup);
        });

        $(document).on('click', '.removeBtn', function() {
            const id = $(this).data('id');
            $(`#inputGroup-${id}`).remove();
        });

        $(document).on('click', '.removeEditBtn', function() {
            const id = $(this).data('id');
            $(`#editInputGroup-${id}`).remove();
        });
    }

    function domainStatus(id, element) {

const isChecked = element.checked ? 1 : 0;
element.disabled = true;

$.ajax({
    url: "{{ config('app.api_url') }}updatedoaminstatus",
    type: "POST",
    data: {
        id: id,
        active: isChecked
    },
    success: function (response) {

        if (response.success === true) {

            const msg = isChecked
                ? "Domain activated successfully"
                : "Domain deactivated successfully";

            showToast(msg, "success");

            // ✅ IMPORTANT: table refresh after toast
            setTimeout(() => {
                fetchDomainTable();
            }, 500);

        } else {
            element.checked = !isChecked;
            showToast("Status update failed", "danger");
        }
    },
    error: function () {
        element.checked = !isChecked;
        showToast("Server error while updating status", "danger");
    },
    complete: function () {
        element.disabled = false;
    }
});
}


    function createDomain() {
        const title = document.getElementById("title").value.trim();
        const eligible = document.getElementById("eligible").checked ? 1 : 0;

        if (!title) {
            showToast("Title is required", "warning");
            return;
        }

        const form = new FormData();
        form.append("title", title);
        form.append("eligible", eligible);
        form.append("status", "1");
        form.append("active", "1");

        const extraFields = [];
        $('.dynamic-group').each(function() {
            const fieldName = $(this).find('input[name="extraField1[]"]').val();
            const fieldValue = $(this).find('input[name="extraField2[]"]').val();
            if (fieldName && fieldValue) {
                extraFields.push({
                    name: fieldName,
                    value: fieldValue
                });
            }
        });
        form.append("properties", JSON.stringify(extraFields));

        $.ajax({
            url: "{{ config('app.api_url') }}domains",
            method: "POST",
            processData: false,
            contentType: false,
            data: form,
            success: function(response) {
                showToast("Domain created successfully", "success");
                document.getElementById("title").value = "";
                document.getElementById("eligible").checked = false;
                $("#dynamicInputContainer").empty();
                inputCount = 0;

                const offcanvasEl = document.getElementById("offcanvasRight");
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                offcanvas.hide();

                setTimeout(() => {
                    fetchDomainTable();
                }, 500);
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || "Failed to create domain";
                showToast(errorMessage, "danger");
            }
        });
    }

    function editDomain(id) {
        $.ajax({
            url: `{{ config('app.api_url') }}domains/${id}`,
            method: "GET",
            success: function(response) {
                const domain = response.data ?? response;

                $('#editDomainId').val(domain[0].id);
                $('#editTitle').val(domain[0].title);
                $('#editEligible').prop('checked', domain[0].eligible == 1);
                $('#editActiveStatus').prop('checked', domain[0].active == 1);

                $('#editDynamicInputContainer').empty();
                editInputCount = 0;

                if (domain[0].properties) {
                    try {
                        const extraFields = JSON.parse(domain[0].properties);
                        extraFields.forEach(field => {
                            editInputCount++;
                            const inputGroup = `
                                                <div class="row mb-2 dynamic-group" id="editInputGroup-${editInputCount}">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="editExtraField1[]" 
                                                            placeholder="Field Name" value="${field.name || ''}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="editExtraField2[]" 
                                                            placeholder="Field Value" value="${field.value || ''}">
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-center">
                                                        <button type="button" class="btn btn-sm btn-danger removeEditBtn" data-id="${editInputCount}">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            `;
                            $('#editDynamicInputContainer').append(inputGroup);
                        });
                    } catch (e) {
                        console.error("Error parsing extra fields:", e);
                    }
                }

                const offcanvasEl = document.getElementById("editOffcanvas");
                const instance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                instance.show();
            },
            error: function() {
                showToast("Unable to fetch domain details.", "danger");
            }
        });
    }

    function updateDomain() {
        const id = $('#editDomainId').val();
        const title = $('#editTitle').val().trim();
        const eligible = $('#editEligible').is(':checked') ? 1 : 0;
        const isActive = $('#editActiveStatus').is(':checked') ? 1 : 0;

        if (!title) {
            showToast("Title is required", "warning");
            return;
        }

        const form = new FormData();
        form.append("id", id);
        form.append("title", title);
        form.append("eligible", eligible);
        form.append("status", 1);
        form.append("active", isActive);

        const extraFields = [];
        $('#editDynamicInputContainer .dynamic-group').each(function() {
            const fieldName = $(this).find('input[name="editExtraField1[]"]').val();
            const fieldValue = $(this).find('input[name="editExtraField2[]"]').val();
            if (fieldName && fieldValue) {
                extraFields.push({
                    name: fieldName,
                    value: fieldValue
                });
            }
        });
        form.append("properties", JSON.stringify(extraFields));

        $.ajax({
            url: `{{ config('app.api_url') }}domainsupdate/${id}`,
            method: "POST",
            processData: false,
            contentType: false,
            data: form,
            success: function() {
                showToast("Domain updated successfully", "success");
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                offcanvas.hide();

                setTimeout(() => {
                    fetchDomainTable();
                }, 500);
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || "Failed to update domain";
                showToast(errorMessage, "danger");
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