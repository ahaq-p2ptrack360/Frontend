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
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" /> -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Assign Group @endslot
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
        width: 25% !important;
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
                <table id="group-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Company</th>
                            <th>Title</th>
                            <th>Business Unit</th>
                            <th>Created At</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="table-loading" style="text-align: center; padding: 20px; color: #6c757d">Loading...</div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Group</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createGroupForm">
            <!-- <div class="mb-3">
                                                <label for="buisnessUnit" class="form-label">Business Unit</label>
                                                <select class="form-control" data-choices name="buisnessUnit" id="buisnessUnit"
                                                    placeholder="Enter Business Unit" required>
                                                    <option value="">Select Business Unit</option>
                                                </select>
                                            </div> -->
            <div class="mb-3">
                <label for="buisnessUnit" class="form-label">Business Unit</label>
                <select class="form-control" name="buisnessUnit" id="buisnessUnit" required>
                <option value="">Select Business Unit</option>
                  
                </select>
            </div>
            <div class="mb-3">
                <label for="group" class="form-label">Group</label>
               
                <select class="form-control" name="group" id="group"
                    placeholder="Enter Group" required>
                    <option value="">Select Group</option>

                </select>
            </div>
            <div class="mb-3">
                <label for="designation" class="form-label">Designation</label>
                <select class="form-control" name="designation" id="designation"
                    placeholder="Enter Designation" required>
                    <option value="">Select Designation</option>


                </select>
            </div>
            <input type="hidden" id="company_id" name="company_id" value="1">
            <input type="hidden" id="created_by" name="created_by" value="1">
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createData()">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Group</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editGroupForm">
            <input type="hidden" id="editGroupId" name="editGroupId" />
            <input type="hidden" id="editCompanyId" name="editCompanyId" value="1" />
            <input type="hidden" id="editCreatedBy" name="editCreatedBy" />

            <!-- Replace 1 with dynamic company ID if needed -->

            <!-- <div class="mb-3">
                                <label for="editBuisnessUnit" class="form-label">Business Unit</label>
                                <select class="form-control" data-choices name="editBuisnessUnit" id="editBuisnessUnit"
                                    placeholder="Enter Business Unit" required>
                                    <option value="">Select Business Unit</option>
                                    <option value="1">Business Unit 1</option>
                                    <option value="2">Business Unit 2</option>
                                    <option value="3">Business Unit 3</option>
                                </select>
                            </div> -->
            <div class="mb-3">
                <label for="editBuisnessUnit" class="form-label">Business Unit</label>
                <select class="form-control" name="editBuisnessUnit" id="editBuisnessUnit"
                    placeholder="Enter Business Unit" required>
                    <option value="">Select Business Unit</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="editGroup" class="form-label">Group</label>
                <select class="form-control" name="editGroup" id="editGroup" placeholder="Enter Group" required>
                <option value="">Select Group</option>
                   
                </select>
            </div>
            <div class="mb-3">
                <label for="editDesignation" class="form-label">Designation</label>
                <select class="form-control" name="editDesignation" id="editDesignation"
                    placeholder="Enter Designation" required>
                    <option value="">Select Designation</option>

                    
                </select>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateGroup()">Update</button>
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
    let groupDataTable,group,editGroup;
    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';
    const companyId = '{{ Auth::user()->company_id }}';
    let designation;
    $(document).ready(function() {
        loadPermissions(roleId).then(() => {
            applyPermissionUI();
            loadBusinessUnits();
            loadGroups();
            fetchGroups();
        });

        $('#createGroupForm').on('submit', function(e) {
            e.preventDefault();
            createData();
        });
        $.ajax({
            url: ("{{ Auth::user()->company_id == 0 }}" ?
                "{{ config('app.api_url') }}designations" :
                "{{ config('app.api_url') }}designations/company/{{ Auth::user()->company_id }}"
            ),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response)
                designation = new Choices("#designation", {
                    removeItemButton: !0,
                })
                designation.clearChoices();
                console.log(response);
                designation.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: item.title // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );
                editDesignation = new Choices("#editDesignation", {
                    removeItemButton: !0,
                })
                editDesignation.clearChoices();
                console.log(response);
                editDesignation.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: item.title // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );



            }
        })
    });

    // --- permission helpers ---
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

    function applyPermissionUI() {
        // create permission
        if (!hasPermission('assign_group', 'create')) {
            $('#addNewBtn').hide();
        } else {
            $('#addNewBtn').show();
        }

        // update permission: remove Edit header if absent
        if (!hasPermission('assign_group', 'update')) {
            $('#group-table thead tr th').filter(function() {
                return $(this).text().trim() === 'Edit';
            }).remove();
        }
    }

    function fetchGroups() {
        const tableEl = document.getElementById("group-table");
        const loadingEl = document.getElementById("table-loading");

        if (!tableEl || !loadingEl) {
            console.error("#group-table or #table-loading not found");
            return;
        }

        loadingEl.style.display = "block";

        if (groupDataTable) {
            groupDataTable.destroy();
            $('#group-table tbody').empty();
        }

        $.ajax({
            url: '{{Auth::user()->company_id}}'==0?"{{ config('app.api_url') }}bu_groups" :"{{ config('app.api_url') }}bu_groups/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(data) {
                // columns adapt to update permission
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
                        data: 'name',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_at',
                        defaultContent: '-',
                        render: function(d) {
                            return d ? new Date(d).toLocaleString() : '-';
                        }
                    }
                ];

                if (hasPermission('assign_group', 'update')) {
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

                groupDataTable = $('#group-table').DataTable({
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
                            groupDataTable.buttons().container().appendTo('#customExportButtons');
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

    function createData() {
        if (!hasPermission('assign_group', 'create')) {
            showToast("No permission to create", "warning");
            return;
        }

        const buisnessUnit = $('#buisnessUnit').val();
        const group = $('#group').val();
        const designation = $('#designation').val();
        const company_id = $('#company_id').val();
        const created_by = $('#created_by').val();

        if (!buisnessUnit || !group || !designation) {
            showToast("All fields are required", "warning");
            return;
        }

        const form = new FormData();
        form.append("bu_id", buisnessUnit);
        form.append("title", group);
        form.append("designation", designation);
        form.append("company_id", company_id);
        form.append("created_by", created_by);

        $.ajax({
            url: "{{ config('app.api_url') }}bu_groups",
            method: "POST",
            processData: false,
            contentType: false,
            data: form,
            success: function() {
                showToast("Group created successfully", "success");
                $('#createGroupForm')[0].reset();
                const offcanvasEl = document.getElementById("offcanvasRight");
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                offcanvas.hide();
                setTimeout(fetchGroups, 500);
            },
            error: function() {
                showToast("Failed to create group", "danger");
            }
        });
    }

    function editData(id) {
        if (!hasPermission('assign_group', 'update')) {
            showToast("No permission to edit", "warning");
            return;
        }

        $.ajax({
            url: `{{ config('app.api_url') }}bu_groups/${id}`,
            method: "GET",
            success: function(response) {
                const group = response.data ?? response;

                $('#editGroupId').val(group[0].id);
                $('#editBuisnessUnit').val(group[0].bu_id);
                // editMarketingManager.setChoiceByValue(bu.marketing_manager || '');
                $('#editGroup').val(group[0].title);
                $('#editDesignation').val(group[0].designation);
                $('#editCompanyId').val(group[0].company_id);
                $('#editCreatedBy').val(group[0].created_by);

                const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById("editOffcanvas"));
                offcanvas.show();
            },
            error: () => showToast("Unable to fetch group details.", "danger")
        });
    }

    function updateGroup() {
        if (!hasPermission('assign_group', 'update')) {
            showToast("No permission to update", "warning");
            return;
        }

        const id = $('#editGroupId').val();
        const buisnessUnit = $('#editBuisnessUnit').val();
        const group = $('#editGroup').val();
        const designation = $('#editDesignation').val();
        const companyIdVal = '{{Auth::user()->company_id}}';
        const createdBy ='{{Auth::user()->id}}';
        const isActive =  1;

        if (!buisnessUnit || !group || !designation || !companyIdVal || !createdBy) {
            showToast("All fields are required", "warning");
            return;
        }

        $.ajax({
            url: `{{ config('app.api_url') }}bu_groupsupdate/${id}`,
            method: "POST",
            data: {
                bu_id: buisnessUnit,
                title: group,
                designation: designation,
                company_id: companyIdVal,
                created_by: createdBy,
                is_active: isActive
            },
            success: () => {
                showToast("Group updated successfully", "success");
                bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas")).hide();
                setTimeout(fetchGroups, 500);
            },
            error: () => showToast("Failed to update group", "danger")
        });
    }

    function loadBusinessUnits() {
        $.ajax({
            url:'{{Auth::user()->company_id}}'==0? "{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(response) {
                editBuisnessUnit = new Choices("#editBuisnessUnit", {
                    removeItemButton: !0,
                })
                editBuisnessUnit.clearChoices();
                console.log(response);
                editBuisnessUnit.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: item.name // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );
                BuisnessUnit = new Choices("#buisnessUnit", {
                    removeItemButton: !0,
                })
                BuisnessUnit.clearChoices();
                console.log(response);
                BuisnessUnit.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: item.name // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );
              
            },
            error: function() {
                $('#buisnessUnit, #editBuisnessUnit').html('<option value="">Failed to load units</option>');
                showToast("Error loading Business Units", "danger");
            }
        });
    }

    function loadGroups() {
        $.ajax({
            url: '{{Auth::user()->company_id}}'==0? "{{ config('app.api_url') }}bu_groups":"{{ config('app.api_url') }}bu_groups/company/{{Auth::user()->company_id}}",
            method: "GET",
            success: function(response) {

                group = new Choices("#group", {
                    removeItemButton: !0,
                })
                group.clearChoices();
                console.log(response);
                group.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: item.name // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );

                editGroup = new Choices("#editGroup", {
                    removeItemButton: !0,
                })
                editGroup.clearChoices();
                console.log(response);
                editGroup.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: item.name // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );
               
            },
            error: function() {
                $('#group, #editGroup').html('<option value="">Failed to load Groups</option>');
                showToast("Error loading Groups", "danger");
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
            default:
                return "#0d6efd";
        }
    }
</script>


<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection