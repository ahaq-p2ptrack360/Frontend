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

<!-- Choices.js CSS -->
<link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Group @endslot
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

    /* Choices.js Custom Styling */
    .choices__list--dropdown {
        z-index: 9999 !important;
    }
    
    .choices__inner {
        min-height: 38px;
        padding: 6px 12px;
    }
    
    .choices__list--multiple .choices__item {
        background-color: #4a6cf7;
        border: 1px solid #4a6cf7;
        font-size: 12px;
        margin: 2px;
    }
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
                <table id="group-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
                            <th>Members</th>
                            <th>Manager</th>
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
        <h5 class="offcanvas-title">Create Group</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="createForm">
            <div class="mb-3">
                <label for="group_id" class="form-label">Groups</label>
                <select class="form-control" id="group_id" name="group_id">
                    <option value="">Select Group</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="manager_id" class="form-label">Manager</label>
                <select class="form-control" id="manager_id" name="manager_id">
                    <option value="">Select Manager</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="member_id" class="form-label text-muted">Group Members</label>
                <select class="form-control" id="member_id" name="member_id" multiple>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-dark me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="button" class="btn btn-primary" onclick="createData()">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit Group</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editForm">
            <input type="hidden" id="editGroupId" name="editGroupId" />
            <div class="mb-3">
                <label for="editgroup" class="form-label">Groups</label>
                <select class="form-control" id="editgroup" name="editgroup">
                    <option value="">Select Group</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="editMemberIds" class="form-label text-muted">Group Members</label>
                <select class="form-control" id="editMemberIds" name="editMemberIds" multiple>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="d-flex justify-content-end">
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

<!-- Choices.js -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    // let groupDataTable;
    // let permissions = null;
    // const roleId = '{{ Auth::user()->role}}'; // Laravel Auth role
    // let choicesInstances = {};

    // document.addEventListener("DOMContentLoaded", function() {
    //     fetchTable();
    // });

    // $(document).ready(function() {
    //     loadPermissions(roleId).then(() => {
    //         applyPermissionUI();
    //         fetchTable();
    //     });
        
    //     // Initialize dropdowns after DOM is ready
    //     initializeDropdowns();
        
    //     // Load data for dropdowns
    //     loadDropdownData();
    // });

    // function initializeDropdowns() {
    //     // Initialize all dropdowns with Choices.js
    //     const dropdownIds = ['group_id', 'manager_id', 'member_id', 'editgroup', 'editMemberIds'];
        
    //     dropdownIds.forEach(id => {
    //         const element = document.getElementById(id);
    //         if (element) {
    //             choicesInstances[id] = new Choices(element, {
    //                 removeItemButton: true,
    //                 searchEnabled: true,
    //                 allowHTML: true,
    //                 shouldSort: false
    //             });
    //         }
    //     });
    // }

    // function loadDropdownData() {
    //     // Load users for member_id, editMemberIds, and manager_id
    //     const usersApiUrl = "{{ Auth::user()->company_id == 0 ? config('app.api_url') . 'users' : config('app.api_url') . 'users/company/' . Auth::user()->company_id }}";
        
    //     $.ajax({
    //         url: usersApiUrl,
    //         method: 'GET',
    //         success: function(response) {
    //             if (Array.isArray(response)) {
    //                 const userOptions = response.map(user => ({
    //                     value: user.id,
    //                     label: `${user.first_name} ${user.last_name}`.trim()
    //                 }));
                    
    //                 // Populate member dropdowns
    //                 populateDropdown('member_id', userOptions);
    //                 populateDropdown('editMemberIds', userOptions);
                    
    //                 // Populate manager dropdown
    //                 populateDropdown('manager_id', userOptions);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error loading users:', error);
    //             showToast('Failed to load users data', 'danger');
    //         }
    //     });

    //     // Load groups for group_id and editgroup
    //     const groupsApiUrl = "{{ Auth::user()->company_id == 0 ? config('app.api_url') . 'bu_groups' : config('app.api_url') . 'bu_groups/company/' . Auth::user()->company_id }}";
        
    //     $.ajax({
    //         url: groupsApiUrl,
    //         method: 'GET',
    //         success: function(response) {
    //             if (Array.isArray(response)) {
    //                 const groupOptions = response.map(group => ({
    //                     value: group.id,
    //                     label: group.title
    //                 }));
                    
    //                 // Populate group dropdowns
    //                 populateDropdown('group_id', groupOptions);
    //                 populateDropdown('editgroup', groupOptions);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error loading groups:', error);
    //             showToast('Failed to load groups data', 'danger');
    //         }
    //     });
    // }

    // function populateDropdown(dropdownId, options) {
    //     const choicesInstance = choicesInstances[dropdownId];
    //     if (choicesInstance) {
    //         // Clear existing options
    //         choicesInstance.clearChoices();
            
    //         // Add new options
    //         choicesInstance.setChoices(options, 'value', 'label', true);
    //     }
    // }

    // async function loadPermissions(roleId) {
    //     try {
    //         const res = await $.ajax({
    //             url: `{{ config('app.api_url') }}permissions/${roleId}`,
    //             method: 'GET',
    //             dataType: 'json'
    //         });
    //         if (Array.isArray(res) && res.length) permissions = res[0];
    //     } catch (e) {
    //         console.error("Failed to load permissions", e);
    //     }
    // }

    // function hasPermission(section, action) {
    //     if (!permissions || !section || !action) return false;
    //     try {
    //         const raw = permissions[section];
    //         if (!raw) return false;
    //         const parsed = typeof raw === 'object' ? raw : JSON.parse(raw);
    //         return parsed[action] === 1;
    //     } catch (e) {
    //         return false;
    //     }
    // }

    // function applyPermissionUI() {
    //     if (!hasPermission('create_group', 'create')) {
    //         $('#addNewBtn').hide();
    //     }
    // }

    // function fetchTable() {
    //     if (groupDataTable) {
    //         groupDataTable.clear().destroy();
    //         $('#group-table').empty();
    //     }

    //     const apiUrl = "{{ Auth::user()->company_id == 0 ? config('app.api_url') . 'group-members' : config('app.api_url') . 'u_group/' . Auth::user()->company_id }}";
        
    //     groupDataTable = $('#group-table').DataTable({
    //         ajax: {
    //             url: apiUrl,
    //             dataSrc: '',
    //             method: "GET",
    //         },
    //         responsive: true,
    //         lengthChange: false,
    //         paging: true,
    //         searching: true,
    //         ordering: true,
    //         autoWidth: false,
    //         destroy: true,
    //         columns: [{
    //                 data: null,
    //                 title: 'S.No',
    //                 render: function(data, type, row, meta) {
    //                     return meta.row + 1 + meta.settings._iDisplayStart;
    //                 }
    //             },
    //             {
    //                 data: 'group_name',
    //                 title: 'Title'
    //             },
    //             {
    //                 data: 'members',
    //                 title: 'Members',
    //                 render: function(data) {
    //                     if (!data) return '';
    //                     return String(data).split(',').map(m => m.trim()).join(', ');
    //                 }
    //             },
    //             {
    //                 data: 'manager',
    //                 title: 'Manager',
    //                 render: function(data) {
    //                     if (!data) return '';
    //                     return String(data).split(',').map(m => m.trim()).join(', ');
    //                 }
    //             },
    //             ...(hasPermission('create_group', 'update') ? [{
    //                 data: null,
    //                 title: 'Edit',
    //                 orderable: false,
    //                 render: row => `
    //                 <button class="btn btn-sm btn-primary" onclick="editData(${row.id})">
    //                     <i class="ri-pencil-fill"></i>
    //                 </button>`
    //             }] : []),
    //             ...(hasPermission('create_group', 'update') ? [{
    //                 data: 'active',
    //                 title: 'Status',
    //                 orderable: false,
    //                 render: function(data, type, row) {
    //                     const checked = data == 1 ? 'checked' : '';
    //                     const disabled = !hasPermission('create_group', 'update') ? 'disabled' : '';
    //                     return `
    //                     <div class="form-check form-switch">
    //                         <input class="form-check-input" type="checkbox" role="switch"
    //                             onchange="updateStatus(${row.id}, this)" ${checked} ${disabled}>
    //                     </div>`;
    //                 }
    //             }] : [])
    //         ],
    //         buttons: [{
    //             extend: 'collection',
    //             text: '<i class="ri-file-excel-2-line"></i> Export Excel',
    //             className: 'btn btn-success',
    //             buttons: [{
    //                     extend: 'copy',
    //                     text: '<i class="fas fa-copy"></i> Copy'
    //                 },
    //                 {
    //                     extend: 'excel',
    //                     text: '<i class="fas fa-file-excel"></i> Excel'
    //                 },
    //                 {
    //                     extend: 'csv',
    //                     text: '<i class="fas fa-file-csv"></i> CSV'
    //                 },
    //                 {
    //                     extend: 'pdf',
    //                     text: '<i class="fas fa-file-pdf"></i> PDF'
    //                 },
    //                 {
    //                     extend: 'print',
    //                     text: '<i class="fas fa-print"></i> Print'
    //                 }
    //             ]
    //         }],
    //         initComplete: function() {
    //             setTimeout(() => {
    //                 $('#customExportButtons').html('');
    //                 groupDataTable.buttons().container().appendTo('#customExportButtons');
    //             }, 0);
    //         }
    //     });
    // }

    // function createData() {
    //     const groupId = $('#group_id').val();
    //     const managerId = $('#manager_id').val();
        
    //     const memberSelect = document.getElementById('member_id');
    //     const memberIds = Array.from(memberSelect.selectedOptions).map(option => option.value);

    //     console.log("Selected groupId:", groupId);
    //     console.log("Selected managerId:", managerId);
    //     console.log("Selected memberIds:", memberIds);

    //     if (!groupId || isNaN(groupId)) {
    //         return showToast("Please select a valid group", "warning");
    //     }

    //     if (!managerId || isNaN(managerId)) {
    //         return showToast("Please select a manager", "warning");
    //     }

    //     if (!memberIds.length) {
    //         return showToast("Please select at least one member", "warning");
    //     }

    //     let successCount = 0;
    //     let errorCount = 0;

    //     memberIds.forEach(memberId => {
    //         const form = new FormData();
    //         form.append("group_id", groupId);
    //         form.append("member_id", memberId);
    //         form.append("user_id", managerId);
    //         form.append("active", "1");

    //         $.ajax({
    //             url: "{{ config('app.api_url') }}group-members",
    //             method: "POST",
    //             data: form,
    //             processData: false,
    //             contentType: false,
    //             success: () => {
    //                 successCount++;
    //                 if (successCount === memberIds.length) {
    //                     // Reset form
    //                     $('#createForm')[0].reset();
    //                     choicesInstances.member_id.clearStore();
    //                     choicesInstances.group_id.clearStore();
    //                     choicesInstances.manager_id.clearStore();
                        
    //                     fetchTable();
    //                     bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasRight")).hide();
    //                     showToast("Group created successfully", "success");
    //                 }
    //             },
    //             error: () => {
    //                 errorCount++;
    //                 if (errorCount === 1) { // Show only one error message
    //                     showToast("Failed to create group", "danger");
    //                 }
    //             }
    //         });
    //     });
    // }

    // // function editData(id) {
    // //     console.log("Edit ID:", id);
    // //     $.ajax({
    // //         url: `{{ config('app.api_url') }}group-members/${id}`,
    // //         method: "GET",
    // //         success: function(response) {
    // //             const data = response.data ?? response;
    // //             const group = Array.isArray(data) ? data[0] : data;

    // //             if (!group) {
    // //                 showToast("No group data found.", "danger");
    // //                 return;
    // //             }

    // //             $('#editGroupId').val(group.id);
                
    // //             // Set group value
    // //             if (choicesInstances.editgroup) {
    // //                 choicesInstances.editgroup.setChoiceByValue(group.group_id.toString());
    // //             }
                
    // //             // Set member values
    // //             let selectedMembers = [];
    // //             if (Array.isArray(group.member_id)) {
    // //                 selectedMembers = group.member_id.map(String);
    // //             } else if (typeof group.member_id === 'string') {
    // //                 selectedMembers = group.member_id.split(',').map(s => s.trim());
    // //             } else if (group.member_id != null) {
    // //                 selectedMembers = [String(group.member_id)];
    // //             }

    // //             if (choicesInstances.editMemberIds) {
    // //                 choicesInstances.editMemberIds.removeActiveItems();
    // //                 choicesInstances.editMemberIds.setChoiceByValue(selectedMembers);
    // //             }

    // //             const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById("editOffcanvas"));
    // //             offcanvas.show();
    // //         },
    // //         error: function() {
    // //             showToast("Unable to fetch group details.", "danger");
    // //         }
    // //     });
    // // }

    // // function updateData() {
    // //     const id = $('#editGroupId').val();
    // //     const groupId = choicesInstances.editgroup.getValue(true);
    // //     const memberIds = choicesInstances.editMemberIds.getValue(true);

    // //     if (!groupId) {
    // //         showToast("Please select a group", "warning");
    // //         return;
    // //     }

    // //     if (!memberIds.length) {
    // //         showToast("Please select at least one member", "warning");
    // //         return;
    // //     }

    // //     $.ajax({
    // //         url: `{{ config('app.api_url') }}group-membersupdate/${id}`,
    // //         method: 'POST',
    // //         data: {
    // //             group_id: groupId,
    // //             member_id: memberIds.join(','),
    // //             active: 1
    // //         },
    // //         success: function() {
    // //             fetchTable();
    // //             const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
    // //             if (offcanvas) offcanvas.hide();
    // //             showToast("Group updated successfully", "success");
    // //         },
    // //         error: function() {
    // //             showToast("Update failed", "danger");
    // //         }
    // //     });
    // // }

    

    // function updateStatus(id, element) {
    //     const isChecked = $(element).prop('checked') ? 1 : 0;

    //     const form = new FormData();
    //     form.append("id", id);
    //     form.append("active", isChecked);

    //     $.ajax({
    //         url: `{{ config('app.api_url') }}group-members`,
    //         method: "POST",
    //         data: form,
    //         processData: false,
    //         contentType: false,
    //         mimeType: "multipart/form-data",
    //         timeout: 0,
    //         success: function() {
    //             fetchTable();
    //             if (isChecked === 1) {
    //                 showToast("Status activated successfully", "success");
    //             } else {
    //                 showToast("Status deactivated successfully", "warning");
    //             }
    //         },
    //         error: function() {
    //             showToast("Failed to update status", "danger");
    //         }
    //     });
    // }

    // function showToast(message, type = "info") {
    //     // Simple toast implementation - you can replace with your preferred toast library
    //     const toast = document.createElement('div');
    //     toast.className = `alert alert-${type} alert-dismissible fade show`;
    //     toast.innerHTML = `
    //         ${message}
    //         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    //     `;
    //     document.body.appendChild(toast);
        
    //     setTimeout(() => {
    //         toast.remove();
    //     }, 3000);
    // }



    let groupDataTable;
    let permissions = null;
    const roleId = '{{ Auth::user()->role}}';
    let choicesInstances = {};

    document.addEventListener("DOMContentLoaded", function() {
    fetchTable();
    });

$(document).ready(function() {
    loadPermissions(roleId).then(() => {
        applyPermissionUI();
        fetchTable();
    });
    
    initializeDropdowns();
    loadDropdownData();
});

function initializeDropdowns() {
    const dropdownIds = ['group_id', 'manager_id', 'member_id', 'editgroup', 'editMemberIds'];
    
    dropdownIds.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            choicesInstances[id] = new Choices(element, {
                removeItemButton: true,
                searchEnabled: true,
                allowHTML: true,
                shouldSort: false
            });
        }
    });
}

function loadDropdownData() {
    const usersApiUrl = "{{ Auth::user()->company_id == 0 ? config('app.api_url') . 'users' : config('app.api_url') . 'users/company/' . Auth::user()->company_id }}";
    
    $.ajax({
        url: usersApiUrl,
        method: 'GET',
        success: function(response) {
            if (Array.isArray(response)) {
                const userOptions = response.map(user => ({
                    value: user.id,
                    label: `${user.first_name} ${user.last_name}`.trim()
                }));
                
                populateDropdown('member_id', userOptions);
                populateDropdown('editMemberIds', userOptions);
                populateDropdown('manager_id', userOptions);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading users:', error);
            showToast('Failed to load users data', 'danger');
        }
    });

    const groupsApiUrl = "{{ Auth::user()->company_id == 0 ? config('app.api_url') . 'bu_groups' : config('app.api_url') . 'bu_groups/company/' . Auth::user()->company_id }}";
    
    $.ajax({
        url: groupsApiUrl,
        method: 'GET',
        success: function(response) {
            if (Array.isArray(response)) {
                const groupOptions = response.map(group => ({
                    value: group.id,
                    label: group.title
                }));
                
                populateDropdown('group_id', groupOptions);
                populateDropdown('editgroup', groupOptions);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading groups:', error);
            showToast('Failed to load groups data', 'danger');
        }
    });
}

function populateDropdown(dropdownId, options) {
    const choicesInstance = choicesInstances[dropdownId];
    if (choicesInstance) {
        choicesInstance.clearChoices();
        choicesInstance.setChoices(options, 'value', 'label', true);
    }
}

async function loadPermissions(roleId) {
    try {
        const res = await $.ajax({
            url: `{{ config('app.api_url') }}permissions/${roleId}`,
            method: 'GET',
            dataType: 'json'
        });
        if (Array.isArray(res) && res.length) permissions = res[0];
    } catch (e) {
        console.error("Failed to load permissions", e);
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
        return false;
    }
}

function applyPermissionUI() {
    if (!hasPermission('create_group', 'create')) {
        $('#addNewBtn').hide();
    }
}

function fetchTable() {
    if (groupDataTable) {
        groupDataTable.clear().destroy();
        $('#group-table').empty();
    }

    const apiUrl = "{{ Auth::user()->company_id == 0 ? config('app.api_url') . 'group-members' : config('app.api_url') . 'u_group/' . Auth::user()->company_id }}";
    
    groupDataTable = $('#group-table').DataTable({
        ajax: {
    url: apiUrl,
    method: "GET",
    dataSrc: function (data) {
        console.log("API Response:", data);
        return data;
    }
},

        responsive: true,
        lengthChange: false,
        paging: true,
        searching: true,
        ordering: true,
        autoWidth: false,
        destroy: true,
        columns: [
            {
                data: null,
                title: 'S.No',
                render: function(data, type, row, meta) {
                    return meta.row + 1 + meta.settings._iDisplayStart;
                }
            },
            {
                data: 'title',
                title: 'Title'
            },
            {
                data: 'members',
                title: 'Members',
                render: function(data) {
                    if (!data) return '';
                    return String(data).split(',').map(m => m.trim()).join(', ');
                }
            },
            {
                data: 'manager',
                title: 'Manager',
                render: function(data) {
                    if (!data) return '';
                    return String(data).split(',').map(m => m.trim()).join(', ');
                }
            },
            ...(hasPermission('create_group', 'update') ? [{
                data: null,
                title: 'Edit',
                orderable: false,
                // FIX: Use the actual group_id from your API response
                render: function(data, type, row) {
                    // Use group_id instead of id if that's what your API returns
                    const editId = row.group_id || row.id;
                    return `
                    <button class="btn btn-sm btn-primary" onclick="editData(${editId})">
                        <i class="ri-pencil-fill"></i>
                    </button>`;
                }
            }] : []),
            ...(hasPermission('create_group', 'update') ? [{
                data: 'active',
                title: 'Status',
                orderable: false,
                render: function(data, type, row) {
                    const checked = data == 1 ? 'checked' : '';
                    const disabled = !hasPermission('create_group', 'update') ? 'disabled' : '';
                    // FIX: Use the actual ID from your API response
                    const statusId = row.group_id || row.id;
                    return `
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                            onchange="updateStatus(${statusId}, this)" ${checked} ${disabled}>
                    </div>`;
                }
            }] : [])
        ],
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
        initComplete: function() {
            setTimeout(() => {
                $('#customExportButtons').html('');
                groupDataTable.buttons().container().appendTo('#customExportButtons');
            }, 0);
        }
    });
}

function createData() {
    const groupId = $('#group_id').val();
    const managerId = $('#manager_id').val();
    
    const memberSelect = document.getElementById('member_id');
    const memberIds = Array.from(memberSelect.selectedOptions).map(option => option.value);

    if (!groupId || isNaN(groupId)) {
        return showToast("Please select a valid group", "warning");
    }

    if (!managerId || isNaN(managerId)) {
        return showToast("Please select a manager", "warning");
    }

    if (!memberIds.length) {
        return showToast("Please select at least one member", "warning");
    }

    let successCount = 0;
    let errorCount = 0;

    memberIds.forEach(memberId => {
        const form = new FormData();
        form.append("group_id", groupId);
        form.append("member_id", memberId);
        form.append("user_id", managerId);
        form.append("active", "1");

        $.ajax({
            url: "{{ config('app.api_url') }}group-members",
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: () => {
                successCount++;
                if (successCount === memberIds.length) {
                    $('#createForm')[0].reset();
                    choicesInstances.member_id.clearStore();
                    choicesInstances.group_id.clearStore();
                    choicesInstances.manager_id.clearStore();
                    
                    fetchTable();
                    bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasRight")).hide();
                    showToast("Group created successfully", "success");
                }
            },
            error: () => {
                errorCount++;
                if (errorCount === 1) {
                    showToast("Failed to create group", "danger");
                }
            }
        });
    });
}

function editData(id) {
    console.log("Edit ID:", id);
    
    if (!id) {
        showToast("Invalid group ID", "danger");
        return;
    }

    // First, let's check what data we have in the table for this group
    const tableData = groupDataTable.data();
    const rowData = tableData.toArray().find(row => (row.group_id || row.id) == id);
    
    if (rowData) {
        console.log("Found row data:", rowData);
        populateEditForm(rowData, id);
    } else {
        // If not found in table, try API call
        $.ajax({
            url: `{{ config('app.api_url') }}group-members/group/${id}`,
            method: "GET",
            success: function(response) {
                console.log("API Response:", response);
                
                if (!response || (Array.isArray(response) && response.length === 0)) {
                    showToast("No group data found for this ID", "warning");
                    return;
                }

                const groupData = Array.isArray(response) ? response[0] : response;
                populateEditForm(groupData, id);
            },
            error: function(xhr, status, error) {
                console.error("API Error:", error);
                showToast("Unable to fetch group details", "danger");
            }
        });
    }
}

function populateEditForm(groupData, id) {
    console.log("Populating form with:", groupData);
    
    $('#editGroupId').val(id);

    // Set group value
    if (choicesInstances.editgroup && groupData.group_id) {
        choicesInstances.editgroup.setChoiceByValue(groupData.group_id.toString());
    }
    
    // Handle members data - this depends on your API response structure
    let selectedMembers = [];
    
    // Try different possible formats for members data
    if (groupData.member_id) {
        // If member_id is directly available
        if (Array.isArray(groupData.member_id)) {
            selectedMembers = groupData.member_id.map(String);
        } else if (typeof groupData.member_id === 'string') {
            selectedMembers = groupData.member_id.split(',').map(s => s.trim());
        } else {
            selectedMembers = [String(groupData.member_id)];
        }
    } else if (groupData.members) {
        // If we have members names, we need to map them back to IDs
        // This would require additional API call to get user IDs from names
        console.warn("Only member names available, need IDs for proper editing");
    }

    console.log("Selected members:", selectedMembers);

    if (choicesInstances.editMemberIds && selectedMembers.length > 0) {
        choicesInstances.editMemberIds.removeActiveItems();
        setTimeout(() => {
            selectedMembers.forEach(memberId => {
                choicesInstances.editMemberIds.setChoiceByValue(memberId);
            });
        }, 100);
    }

    const offcanvas = new bootstrap.Offcanvas(document.getElementById("editOffcanvas"));
    offcanvas.show();
}

function updateData() {
    const id = $('#editGroupId').val();
    const groupId = choicesInstances.editgroup.getValue(true);
    const memberIds = choicesInstances.editMemberIds.getValue(true);

    if (!groupId) {
        showToast("Please select a group", "warning");
        return;
    }

    if (!memberIds.length) {
        showToast("Please select at least one member", "warning");
        return;
    }

    // For now, we'll update one member at a time
    // You might need to adjust this based on your requirements
    const formData = new FormData();
    formData.append("group_id", groupId);
    formData.append("member_id", memberIds[0]); // Take first member for now
    formData.append("user_id", 1); // You might need to get this from somewhere
    formData.append("_method", "POST");

    $.ajax({
        url: `{{ config('app.api_url') }}group-members/${id}`,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Update successful:", response);
            fetchTable();
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
            if (offcanvas) offcanvas.hide();
            showToast("Group updated successfully", "success");
        },
        error: function(xhr, status, error) {
            console.error("Update failed:", error);
            showToast("Update failed", "danger");
        }
    });
}

function updateStatus(id, element) {
    const isChecked = $(element).prop('checked') ? 1 : 0;

    const form = new FormData();
    form.append("id", id);
    form.append("active", isChecked);

    $.ajax({
        url: `{{ config('app.api_url') }}group-members`,
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        mimeType: "multipart/form-data",
        timeout: 0,
        success: function() {
            fetchTable();
            if (isChecked === 1) {
                showToast("Status activated successfully", "success");
            } else {
                showToast("Status deactivated successfully", "warning");
            }
        },
        error: function() {
            showToast("Failed to update status", "danger");
        }
    });
}

function showToast(message, type = "info") {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection