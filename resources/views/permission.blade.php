@extends('layouts.master')
@section('title') Permissions Assignment @endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    .permission-card {
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .permission-card h5 {
        margin-bottom: 12px;
    }

    .perm-checkbox {
        margin-right: 12px;
    }

    .role-select-wrapper {
        margin-bottom: 24px;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Settings @endslot
@slot('title') Role Permissions @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card p-4">
            <div class="role-select-wrapper mb-4">
                <label for="roleSelect" class="form-label">Select Role</label>
                <select id="roleSelect" class="form-select" required>
                    <option value="">-- Choose Role --</option>

                    <!-- populate dynamically -->
                </select>
            </div>

            <div id="permissions-container" class="row">
                <!-- permission cards injected here -->
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button id="saveAllBtn" class="btn btn-primary">Save Permissions</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const PAGES = [{
            key: 'types',
            name: 'Types'
        },
        {
            key: 'status',
            name: 'Status'
        },
        {
            key: 'priority',
            name: 'Priority'
        },
        {
            key: 'impact',
            name: 'Impact'
        },
        {
            key: 'business_unit',
            name: 'Business Unit'
        },
        {
            key: 'assign_group',
            name: 'Assign Group'
        },
        {
            key: 'create_group',
            name: 'Create Group'
        },
        {
            key: 'vendor',
            name: 'Vendors'
        },
        {
            key: 'subscription',
            name: 'Subscription Package'
        },
        {
            key: 'ticket',
            name: 'Tickets'
        },
        {
            key: 'report',
            name: 'Report'
        },
        {
            key: 'domain',
            name: 'Domain'
        },
        {
            key: 'company',
            name: 'Company'
        },
        {
            key: 'user',
            name: 'User'
        },
        {
            key: 'account',
            name: 'Account'
        },
        {
            key: 'designation',
            name: 'Designation'
        }
        ,
        {
            key: 'qutation',
            name: 'Qutation'
        }
    ];

    let existingPermissions = {}; // role-specific fetched permissions

    $(document).ready(function() {
        loadRoles();
        renderPermissionCards();

        $('#roleSelect').on('change', function() {
            const roleId = $(this).val();
            if (!roleId) return;
            fetchRolePermissions(roleId);
        });

        $('#saveAllBtn').on('click', submitAllPermissions);
    });

    function loadRoles() {
        // Replace with real endpoint to get roles
        $.ajax({
            url: '{{ config('app.api_url') }}roles',
            method: 'GET',
            success: function(roles) {
                const sel = $('#roleSelect');
                sel.empty().append('<option value="">-- Choose Role --</option>');
                roles.forEach(r => {
                    sel.append(`<option value="${r.id}">${r.name}</option>`);
                });
            },
            error: function() {
                alert('Failed to load roles');
            }
        });
    }

    function fetchRolePermissions(roleId) {
        $.ajax({
            url: `{{ config('app.api_url') }}permissions/${roleId}`,
            method: 'GET',
            dataType: 'json',
            success: function(resp) {
                console.log("resp", resp)
                // expected structure: { types: { create:1, read:1,... }, ... }
                existingPermissions = resp || {};

                applyPermissionsToUI();
            },
            error: function() {
                existingPermissions = {};
                applyPermissionsToUI();
            }
        });
    }

    // function renderPermissionCards() {
    //     const container = $('#permissions-container');
    //     container.empty();
    //     PAGES.forEach(page => {
    //         const card = $(`
    //                 <div class="permission-card col-md-6">
    //                     <h5>${page.name}</h5>
    //                     <div class="d-flex flex-wrap gap-3">
    //                         <label class="form-check perm-checkbox">
    //                             <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="create">
    //                             <span class="form-check-label">Create</span>
    //                         </label>
    //                         <label class="form-check perm-checkbox">
    //                             <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="read">
    //                             <span class="form-check-label">Read</span>
    //                         </label>
    //                         <label class="form-check perm-checkbox">
    //                             <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="update">
    //                             <span class="form-check-label">Update</span>
    //                         </label>
    //                         <label class="form-check perm-checkbox">
    //                             <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="delete">
    //                             <span class="form-check-label">Delete</span>
    //                         </label>
    //                     </div>
    //                 </div>
    //             `);
    //         container.append(card);
    //     });
    // }



    function collectAllPermissions() {
        const result = [];
        PAGES.forEach(page => {
            const perms = {};
            ['create', 'read', 'update', 'delete'].forEach(act => {
                const isChecked = $(`.perm-input[data-page="${page.key}"][data-action="${act}"]`).prop(
                    'checked') ? 1 : 0;
                perms[act] = isChecked;
            });
            result.push({
                page: page.key,
                ...perms
            });
        });
        return result;
    }



    function showToast(message, type = 'info') {
        // lightweight toast
        const bg = {
            success: '#28a745',
            danger: '#dc3545',
            warning: '#ffc107',
            info: '#0d6efd'
        } [type] || '#0d6efd';
        const toast = $(
            `<div style="position:fixed;top:20px;right:20px;background:${bg};color:#fff;padding:10px 16px;border-radius:6px;z-index:2000;">${message}</div>`
        );
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, () => toast.remove()), 2500);
    }

    function renderPermissionCards() {
        const container = $('#permissions-container');
        container.empty();
        PAGES.forEach(page => {
            const card = $(`
            <div class="permission-card col-md-6">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="mb-0">${page.name}</h5>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input master-checkbox" data-page="${page.key}" id="master_${page.key}">
                        <label class="form-check-label" for="master_${page.key}" style="font-size:12px;">All</label>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3 mt-2">
                    <label class="form-check perm-checkbox">
                        <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="create">
                        <span class="form-check-label">Create</span>
                    </label>
                    <label class="form-check perm-checkbox">
                        <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="read">
                        <span class="form-check-label">Read</span>
                    </label>
                    <label class="form-check perm-checkbox">
                        <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="update">
                        <span class="form-check-label">Update</span>
                    </label>
                    <label class="form-check perm-checkbox">
                        <input type="checkbox" class="form-check-input perm-input" data-page="${page.key}" data-action="delete">
                        <span class="form-check-label">Delete</span>
                    </label>
                </div>
            </div>
        `);
            container.append(card);
        });

        // Master toggle logic
        $('.master-checkbox').off('change').on('change', function() {
            const pageKey = $(this).data('page');
            const checked = $(this).prop('checked');
            $(`.perm-input[data-page="${pageKey}"]`).prop('checked', checked);
        });

        // Individual checkbox affecting master
        $('.perm-input').off('change').on('change', function() {
            const pageKey = $(this).data('page');
            const all = $(`.perm-input[data-page="${pageKey}"]`);
            const master = $(`.master-checkbox[data-page="${pageKey}"]`);
            const allChecked = all.toArray().every(i => $(i).prop('checked'));
            master.prop('checked', allChecked);
        });
    }

    function applyPermissionsToUI() {
        // Clear all checkboxes first
        $('.perm-input, .master-checkbox').prop('checked', false);

        // Handle if existingPermissions is an array with one permission object
        const permissionObj = existingPermissions[0]; // assuming only one record per role

        console.log("Loaded permission object:", permissionObj);

        if (!permissionObj) return;

        // Loop through each key (like 'types', 'status', etc.)
        Object.entries(permissionObj).forEach(([pageKey, permValue]) => {
            // Skip system keys
            if (['id', 'role_id', 'created_at', 'updated_at'].includes(pageKey)) return;

            try {
                const perms = JSON.parse(permValue); // parse JSON string like "{\"create\":1,\"read\":1...}"
                console.log(`Parsed permissions for ${pageKey}:`, perms);

                ['create', 'read', 'update', 'delete'].forEach(action => {
                    const isChecked = perms[action] == 1;
                    $(`.perm-input[data-page="${pageKey}"][data-action="${action}"]`).prop('checked', isChecked);
                });

                // Master checkbox: if all permissions are checked
                const allChecked = ['create', 'read', 'update', 'delete'].every(action => perms[action] == 1);
                $(`.master-checkbox[data-page="${pageKey}"]`).prop('checked', allChecked);
            } catch (err) {
                console.error(`Failed to parse permissions for ${pageKey}:`, err);
            }
        });
    }


    // assume role is selected and permissions UI is built as before
    function submitAllPermissions() {
        const roleId = $('#roleSelect').val();
        if (!roleId) {
            alert('Please select a role first');
            return;
        }

        // collect per-page permissions like earlier
        const payload = collectAllPermissions(); // returns array of { page, create, read, update, delete }

        // Wrap into the expected shape
        const dataToSend = {
            role_id: roleId,
            permissions: payload
        };

        $.ajax({
            url: `{{ config('app.api_url') }}permissions`, // or '/api/role-page-permissions' depending on your route
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dataToSend),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                // If using Laravel CSRF and not exempted for API, include token:
                // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                showToast('Permissions saved successfully', 'success');
                console.log('Response:', res);
            },
            error: function(xhr) {
                console.error('Save failed:', xhr.responseText);
                showToast('Failed to save permissions', 'danger');
            }
        });
    }
</script>
@endsection