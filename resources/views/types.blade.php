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
@slot('title') Types @endslot
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

    .table td .form-check-input {
    width: 40px !important;
    height: 22px !important;
}

.table td .form-check-input:checked {
    background-color: #514fd4;
    border-color: #514fd4;
}
    
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <button id="addNewBtn" class="btn btn-primary py-2 px-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#createOffcanvas" aria-controls="createOffcanvas" style="display:none;">
                        Add New
                    </button>
                    <div id="customExportButtons" class="d-flex align-items-center"></div>
                </div>
            </div>

            <div class="card-body">
                <table id="types-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Title</th>
                            <th>Parent</th>
                            <th>Created At</th>
                            <th>Edit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="table-loading" style="text-align: center; padding: 20px; color: #6c757d">Loading...</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="createOffcanvas" aria-labelledby="createOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="createOffcanvasLabel">Create Type</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createTypeForm">
            <div class="mb-3">
                <label for="createTypeName" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="createTypeName" name="title" placeholder="Enter Type Title"
                    required>
            </div>
            <div class="mb-3">
                <label for="createParent" class="form-label ">Parent</label>
                <select class="form-control" id="createParent" name="parent_id">
                </select>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" id="createBtn" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Type</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editTypeForm">
            <input type="hidden" id="editTypeId" name="id">
            <div class="mb-3">
                <label for="editTypeName" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editTypeName" name="title" placeholder="Enter Type Title"
                    required>
            </div>
            <div class="mb-3">
                <label for="editParent" class="form-label">Parent</label>
                <select class="form-control" id="editParent" name="parent_id"></select>
            </div>
            <div class="mb-3 form-check form-switch" style="display:none;">
                <input type="checkbox" class="form-check-input" id="editActiveStatus" />
                <label class="form-check-label" for="editActiveStatus">Active Status</label>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" id="updateBtn" class="btn btn-primary">Update</button>
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

<script>
    let typesDataTable;
    let parentTypesMap = {};
    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';
    const companyId = '{{ Auth::user()->company_id }}';

    $(document).ready(function() {
        
        loadPermissions(roleId)
            .then(() => {
                applyPermissionUI();
                fetchTable();
                populateParentDropdown();
            });

        $('#editTypeForm').on('submit', function(e) {
            e.preventDefault();
            if (!hasPermission('types', 'update')) {
                showToast("No permission to update", "warning");
                return;
            }
            updateData();
        });

        $('#createBtn').on('click', function() {
            if (!hasPermission('types', 'create')) {
                showToast("No permission to create", "warning");
                return;
            }
            createData();
        });
    });

    // --- Permissions helpers ---
    async function loadPermissions(roleId) {
        try {
            const res = await $.ajax({
                url: `{{ config('app.api_url') }}permissions/${roleId}`,
                method: 'GET',
                dataType: 'json'
            });
            if (Array.isArray(res) && res.length) {
                permissions = res[0];
            } else {
                permissions = null;
                console.warn('Permissions response malformed', res);
            }
        } catch (e) {
            console.error('Failed to load permissions', e);
            permissions = null;
        }
    }

    function hasPermission(section, action) {
        if (!permissions || !section || !action) return false;
        try {
            const raw = permissions[section];
            if (!raw) return false;
            const parsed = typeof raw === 'object' ? raw : JSON.parse(raw);
            return parsed[action] == 1;
        } catch (e) {
            console.warn('Permission parse error', section, action, e);
            return false;
        }
    }

    function applyPermissionUI() {
        if (hasPermission('types', 'create')) {
            $('#addNewBtn').show();
        } else {
            $('#addNewBtn').hide();
        }
    }

    function fetchTable() {
        const loadingEl = document.getElementById("table-loading");
        loadingEl.style.display = "block";

        if (typesDataTable) {
            typesDataTable.destroy();
            $('#types-table tbody').empty();
        }

        if (!hasPermission('types', 'update')) {
            $('#types-table thead tr th').filter(function() {
                return $(this).text().trim() === 'Edit';
            }).remove();
        } else {
            if ($('#types-table thead tr th').filter((i, el) => $(el).text().trim() === 'Edit').length === 0) {
                // insert it before Status (assuming Status is last)
                $('#types-table thead tr th').filter((i, el) => $(el).text().trim() === 'Status')
                    .before('<th>Edit</th>');
            }
        }

        const url = "{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}types":"{{ config('app.api_url') }}companytype/{{Auth::user()->company_id}}";

        $.ajax({
            url: url,
            method: "GET",
            success: function(data) {
                parentTypesMap = {};
                data.forEach(type => {
                    parentTypesMap[type.id] = type.title;
                });

                // Build columns array matching the rendered <th>s
                const cols = [{
                        data: 'id',
                        defaultContent: '-'
                    },
                    {
                        data: 'company',
                        defaultContent: '-'
                    },
                    {
                        data: 'title',
                        defaultContent: '-'
                    },
                    {
                        data: 'parent_id',
                        defaultContent: '-',
                        render: function(parent_id) {
                            return parent_id ? (parentTypesMap[parent_id] || '-') : '-';
                        }
                    },
                    {
                        data: 'created_at',
                        defaultContent: '-'
                    },
                ];

                if (hasPermission('types', 'update')) {
                    cols.push({
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <a href="javascript:void(0);" 
                               class="btn btn-sm btn-primary" 
                               onclick="editData(${row.id})">
                               <i class="ri-pencil-fill"></i>
                            </a>`;
                        }
                    });
                }

                cols.push({
                    data: 'active',
                    title: 'Status',
                    orderable: false,
                    render: function(data, type, row) {
                        const checked = data == 1 ? 'checked' : '';
                        return `
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                onchange="updatestatus(${row.id}, this)" ${checked}>
                        </div>`;
                    }
                });

                typesDataTable = $('#types-table').DataTable({
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
                            typesDataTable.buttons().container().appendTo('#customExportButtons');
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

    function updatestatus(id, element) {
        var isChecked = $(element).prop('checked') ? 1 : 0;
        var statusMessage = isChecked ? "Type activated successfully!" : "Type deactivated successfully!";
        var statusType = isChecked ? "success" : "warning";

        var form = new FormData();
        form.append("id", id);
        form.append("active", isChecked);

        $.ajax({
            url: "{{ config('app.api_url') }}updatetypestatus",
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            mimeType: "multipart/form-data",
            timeout: 0,
            success: function(response) {
                showToast(statusMessage, statusType);
                fetchTable();
            },
            error: function(xhr, textStatus, errorThrown) {
                $(element).prop('checked', !isChecked);
                showToast("Failed to update status", "danger");
            }
        });
    }

    function createData() {
        if (!hasPermission('types', 'create')) {
            showToast("No permission to create", "warning");
            return;
        }

        const title = document.getElementById("createTypeName").value.trim();
        const parent_id = document.getElementById("createParent").value;

        if (!title) {
            showToast("Title is required", "warning");
            return;
        }

        const form = new FormData();
        form.append("title", title);
        form.append("parent_id", parent_id);
        form.append("company_id", companyId.toString());
        form.append("active", "1");

        $.ajax({
                url: "{{ config('app.api_url') }}types",
                method: "POST",
                timeout: 0,
                processData: false,
                contentType: false,
                data: form,
            })
            .done(function(response, textStatus, xhr) {
                let statusCode = xhr.status;
                if (typeof response === "string") {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        showToast("Invalid server response", "danger");
                        return;
                    }
                }

                if (statusCode === 200 && (response.message?.toLowerCase().includes("success") || response.message === "Type created successfully")) {
                    showToast(response.message || "Type created successfully", "success");
                    $('#createTypeForm')[0].reset();
                    const offcanvasEl = document.getElementById("createOffcanvas");
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                    offcanvas.hide();
                    setTimeout(() => {
                        fetchTable();
                        populateParentDropdown();
                    }, 500);
                } else {
                    showToast(response.message || "Error creating type.", "danger");
                }
            })
            .fail(function(xhr) {
                let message = "An error occurred, please try again!";
                if (xhr.status === 409) message = "Conflict error!";
                else if (xhr.status === 404) message = "Resource not found!";
                showToast(message, "danger");
            });
    }

    function populateParentDropdown() {
        const apiURL ="{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}types":"{{ config('app.api_url') }}companytype/{{Auth::user()->company_id}}";

        fetch(apiURL)
            .then(res => res.json())
            .then(data => {
                $('#createParent, #editParent').empty().append('<option value="">Select Parent</option>');
                parentTypesMap = {};
                data.forEach(item => {
                    parentTypesMap[item.id] = item.title;
                    addOptionToParentDropdown(item.id, item.title);
                });
            })
            .catch(err => {
                console.error('Failed to load parent types', err);
            });
    }

    function addOptionToParentDropdown(id, title) {
        if (!id || !title) return;
        const $createParent = $('#createParent');
        const $editParent = $('#editParent');

        if ($createParent.find(`option[value="${id}"]`).length === 0) {
            $createParent.append(`<option value="${id}">${title}</option>`);
        }
        if ($editParent.find(`option[value="${id}"]`).length === 0) {
            $editParent.append(`<option value="${id}">${title}</option>`);
        }
    }

    function editData(id) {
        if (!hasPermission('types', 'update')) {
            showToast("No permission to edit", "warning");
            return;
        }

        $.ajax({
            url: `{{ config('app.api_url') }}types/${id}`,
            method: "GET",
            success: function(response) {
                const type = response.data ?? response;

                const item = Array.isArray(type) ? type[0] : type;
                $('#editTypeId').val(item.id);
                $('#editTypeName').val(item.title);
                $('#editActiveStatus').prop('checked', item.active == 1);

                if (item.parent_id) {
                    addOptionToParentDropdown(item.parent_id, parentTypesMap[item.parent_id] || "Parent");
                    $('#editParent').val(item.parent_id);
                } else {
                    $('#editParent').val('');
                }

                const offcanvasEl = document.getElementById("editOffcanvas");
                const instance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                instance.show();
            },
            error: function() {
                showToast("Unable to fetch type details.", "danger");
            }
        });
    }

    function updateData() {
        if (!hasPermission('types', 'update')) {
            showToast("No permission to update", "warning");
            return;
        }

        const id = $('#editTypeId').val();
        const title = $('#editTypeName').val();
        const parent_id = $('#editParent').val();
        const active = $('#editActiveStatus').prop('checked') ? '1' : '0';

        $.ajax({
            url: `{{ config('app.api_url') }}typesupdate/${id}`,
            method: "POST",
            data: {
                company_id: companyId,
                title: title,
                parent_id: parent_id,
                active: active
            },
            success: function() {
                showToast("Type updated successfully!", "success");
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                offcanvas.hide();
                setTimeout(() => {
                    fetchTable();
                    populateParentDropdown();
                }, 500);
                addOptionToParentDropdown(id, title);
            },
            error: function() {
                showToast("Failed to update type.", "danger");
            }
        });
    }
</script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection