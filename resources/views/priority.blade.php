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
    @slot('title') Priority @endslot
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

    .form-check.form-switch {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .form-check-input {
        width: 40px !important;
        height: 22px !important;
        cursor: pointer;
    }

    .form-check-input:checked {
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
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            Add New
                        </button>
                        <div id="customExportButtons" class="d-flex align-items-center"></div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="priority-table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Company</th>
                                <th>Title</th>
                                <th>SLA</th>
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
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Priority</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form>
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" placeholder="Enter Priority Title" required>
                </div>
                <div class="mb-3">
                    <label for="sla" class="form-label">SLA</label>
                    <input type="text" class="form-control" id="sla" placeholder="Enter SLA">
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createData()">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Priority</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="editPriorityForm">
                <input type="hidden" id="editPriorityId" name="editPriorityId" />
                <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="editTitle" name="editTitle" class="form-control" placeholder="Enter Priority Title"
                        required />
                </div>
                <div class="mb-3">
                    <label for="editSla" class="form-label">SLA</label>
                    <input type="text" id="editSla" name="editSla" class="form-control" placeholder="Enter SLA" />
                </div>
                <div class="mb-3 form-check form-switch" style="display: none;">
                    <input class="form-check-input" type="checkbox" id="editActiveStatus">
                    <label class="form-check-label" for="editActiveStatus">Active Status</label>
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Cancel</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    @section('script')
    <!-- Datatable & deps -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <script>
        let priorityDataTable;
        let permissions = null;
        const roleId = '{{ Auth::user()->role }}';
        const companyId = '{{ Auth::user()->company_id }}';

        $(document).ready(function () {
            loadPermissions(roleId).then(() => {
                applyPermissionUI();
                fetchTable();
            });

            $('#createBtn').on('click', function () {
                if (!hasPermission('priority', 'create')) {
                    showToast("No permission to create", "warning");
                    return;
                }
                createData();
            });
        });

        // --- Permission helpers ---
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
                return parsed[action] === 1;
            } catch (e) {
                console.warn('Permission parse error', section, action, e);
                return false;
            }
        }

        function applyPermissionUI() {
            if (hasPermission('priority', 'create')) {
                $('#addNewBtn').show();
            } else {
                $('#addNewBtn').hide();
            }

            if (!hasPermission('priority', 'update')) {
                // remove Edit header cell if no update permission
                $('#priority-table thead tr th').filter(function () {
                    return $(this).text().trim() === 'Edit';
                }).remove();
            }
        }

        function fetchTable() {
            const loadingEl = $('#table-loading');
            loadingEl.show();

            if (priorityDataTable) {
                priorityDataTable.destroy();
                $('#priority-table tbody').empty();
            }

            $.ajax({
                url: '{{Auth::user()->company_id}}'==0?"{{ config('app.api_url') }}priority":"{{ config('app.api_url') }}priority/company/{{Auth::user()->company_id}}",
                method: "GET",
                success: function (data) {
                    // Build columns matching header based on update permission
                    const cols = [
                        { data: 'id', defaultContent: '-' },
                        { data: 'company', defaultContent: '-' },
                        { data: 'title', defaultContent: '-' },
                        { data: 'sla', defaultContent: '-' },
                        { data: 'created_at', defaultContent: '-' }
                    ];

                    if (hasPermission('priority', 'update')) {
                        cols.push({
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `
                                    <a href="javascript:void(0);" 
                                       class="btn btn-sm btn-primary" 
                                       onclick="editPriority(${row.id})">
                                       <i class="ri-pencil-fill"></i>
                                    </a>`;
                            }
                        });
                    }

                    cols.push({
                        data: 'active',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            const checked = data == 1 ? 'checked' : '';
                    const disabled = !hasPermission('priority', 'update') ? 'disabled' : '';

                            return `
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        onchange="updateStatus(${row.id}, this)" ${checked} ${disabled}>
                                </div>`;
                        }
                    });

                    priorityDataTable = $('#priority-table').DataTable({
                        data: data,
                        responsive: true,
                        paging: true,
                        searching: true,
                        dom: 'Bfrtip',
                        buttons: [
                            {
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
                            }
                        ],
                        columns: cols,
                        initComplete: function () {
                            loadingEl.hide();
                            setTimeout(() => {
                                $('#customExportButtons').html('');
                                priorityDataTable.buttons().container().appendTo('#customExportButtons');
                            }, 0);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data:", error);
                    $('#table-loading').text("Failed to load data.");
                }
            });
        }

        function updateStatus(id, element) {
            const isChecked = $(element).prop('checked') ? 1 : 0;
            const form = new FormData();
            form.append("id", id);
            form.append("active", isChecked);

            $.ajax({
                url: `{{ config('app.api_url') }}updateprioritystatus`,
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                success: function () {
                    fetchTable();
                    const message = isChecked ? "Priority activated successfully" : "Priority deactivated successfully";
                    const type = isChecked ? "success" : "warning";
                    showToast(message, type);
                },
                error: function () {
                    showToast("Failed to update priority status", "danger");
                    $(element).prop('checked', !isChecked);
                }
            });
        }

        function createData() {
            if (!hasPermission('priority', 'create')) {
                showToast("No permission to create", "warning");
                return;
            }
            const title = $('#title').val().trim();
            const sla = $('#sla').val().trim();
            if (!title) {
                showToast("Title is required", "warning");
                return;
            }

            const form = new FormData();
            form.append("title", title);
            form.append("sla", sla);
            form.append("company_id", companyId.toString());
            form.append("active", "1");

            $.ajax({
                url: "{{ config('app.api_url') }}priority",
                method: "POST",
                processData: false,
                contentType: false,
                data: form,
                success: function () {
                    showToast("Priority created successfully", "success");
                    $('#title').val('');
                    $('#sla').val('');
                    const offcanvasEl = document.getElementById("offcanvasRight");
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                    offcanvas.hide();
                    setTimeout(fetchTable, 500);
                },
                error: function () {
                    showToast("Failed to create priority", "danger");
                }
            });
        }

        function editPriority(id) {
            if (!hasPermission('priority', 'update')) {
                showToast("No permission to edit", "warning");
                return;
            }
            $.ajax({
                url: `{{ config('app.api_url') }}priority/${id}`,
                method: "GET",
                success: function (response) {
                    const priority = response.data ?? response;
                    const item = Array.isArray(priority) ? priority[0] : priority;
                    $('#editPriorityId').val(item.id);
                    $('#editTitle').val(item.title);
                    $('#editSla').val(item.sla);
                    $('#editActiveStatus').prop('checked', item.active == 1);

                    const offcanvasEl = document.getElementById("editOffcanvas");
                    const instance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                    instance.show();

                    // bind update button inside edit form
                    $('#updateBtn').off('click').on('click', function () {
                        updateData();
                    });
                },
                error: function () {
                    showToast("Unable to fetch priority details.", "danger");
                }
            });
        }

        function updateData() {
            if (!hasPermission('priority', 'update')) {
                showToast("No permission to update", "warning");
                return;
            }
            const id = $('#editPriorityId').val();
            const title = $('#editTitle').val().trim();
            const sla = $('#editSla').val().trim();
            const isActive = $('#editActiveStatus').is(':checked') ? 1 : 0;
            if (!title) {
                showToast("Title is required", "warning");
                return;
            }

            $.ajax({
                url: `{{ config('app.api_url') }}priorityupdate/${id}`,
                method: "POST",
                data: {
                    company_id: id,
                    title: title,
                    sla: sla,
                    active: isActive
                },
                success: function () {
                    showToast("Priority updated successfully", "success");
                    const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                    offcanvas.hide();
                    setTimeout(fetchTable, 500);
                },
                error: function () {
                    showToast("Failed to update priority", "danger");
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
                case "success": return "#28a745";
                case "danger": return "#dc3545";
                case "warning": return "#ffc107";
                default: return "#0d6efd";
            }
        }
    </script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection


    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection