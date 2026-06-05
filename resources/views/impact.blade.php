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
    @slot('title') Impact @endslot
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
            /* font-size: 1rem; */
        }

        #addNewBtn {
            height: 38px;
            padding: 6px 12px;
            /* font-size: 1rem; */
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

        #impact-table .form-check-input {
            width: 40px !important;
            height: 22px !important;
        }


        /* .dataTables_wrapper {
            overflow: visible !important;
            position: relative;
            z-index: 10;
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
                    <table id="impact-table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Company</th>
                                <th>Title</th>
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
            <h5 class="offcanvas-title">Create Impact</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Enter Impact Title">
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
            <h5 class="offcanvas-title">Edit Impact</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form id="editForm">
                <input type="hidden" id="editImpactId" name="editImpactId" />
                <div class="mb-3">
                    <label for="editTitle" class="form-label">Title</label>
                    <input type="text" id="editTitle" class="form-control" placeholder="Enter Impact Title" />
                </div>
                <div class="mb-3 form-check form-switch" style="display:none;">
                    <input type="checkbox" class="form-check-input" id="editActiveStatus" />
                    <label class="form-check-label" for="editActiveStatus">Active Status</label>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="updateData()">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <!-- Dependencies -->
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

    <script>
        let impactDataTable = null;
        let permissions = null;
        const roleId = '{{ Auth::user()->role }}';
        const companyId = '{{ Auth::user()->company_id }}';

        $(document).ready(function () {
            loadPermissions(roleId).then(() => {
                applyPermissionUI();
                fetchTable();
            });

            $('#offcanvasRight').on('shown.bs.offcanvas', function () {
                // clear form if needed
                $('#title').val('');
            });
        });

        // Permissions helpers
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
            if (hasPermission('impact', 'create')) {
                $('#addNewBtn').show();
            } else {
                $('#addNewBtn').hide();
            }
            if (!hasPermission('impact', 'update')) {
                // remove Edit header cell
                $('#impact-table thead tr th').filter(function () {
                    return $(this).text().trim() === 'Edit';
                }).remove();
            }
        }

        function fetchTable() {
            if (impactDataTable) {
                impactDataTable.destroy();
                $('#impact-table tbody').empty();
            }

            const cols = [
                { data: 'id', title: 'S.No' },
                { data: 'company', title: 'Company' },
                { data: 'title', title: 'Title' },
                { data: 'created_at', title: 'Created At' }
            ];

            if (hasPermission('impact', 'update')) {
                cols.push({
                    data: null,
                    title: 'Edit',
                    orderable: false,
                    render: row => `
                        <button class="btn btn-sm btn-primary" onclick="editData(${row.id})">
                            <i class="ri-pencil-fill"></i>
                        </button>`
                });
            }

            cols.push({
                data: 'active',
                title: 'Status',
                orderable: false,
                render: function (data, type, row) {
                    const checked = data == 1 ? 'checked' : '';
                    const disabled = !hasPermission('impact', 'update') ? 'disabled' : '';

                    return `
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                onchange="updatestatus(${row.id}, this)" ${checked} ${disabled}>
                        </div>`;
                }
            });

            impactDataTable = $('#impact-table').DataTable({
                ajax: {
                    url:'{{Auth::user()->company_id}}'==0? "{{ config('app.api_url') }}impacts" :"{{ config('app.api_url') }}impacts/company/{{Auth::user()->company_id}}",
                    dataSrc: '',
                    method: "GET",
                },
                responsive: true,
                lengthChange: false,
                paging: true,
                searching: true,
                ordering: true,
                autoWidth: false,
                destroy: true,
                columns: cols,
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
                initComplete: function () {
                    setTimeout(() => {
                        $('#customExportButtons').html('');
                        impactDataTable.buttons().container().appendTo('#customExportButtons');
                    }, 0);
                }
            });
        }

        function createData() {
            if (!hasPermission('impact', 'create')) {
                showToast("No permission to create", "warning");
                return;
            }
            const title = $('#title').val().trim();
            if (!title) {
                showToast("Enter a title", "warning");
                return;
            }

            const form = new FormData();
            form.append("title", title);
            form.append("company_id", companyId.toString());
            form.append("active", "1");

            $.ajax({
                url: "{{ config('app.api_url') }}impacts",
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                success: () => {
                    $('#title').val('');
                    fetchTable();
                    const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasRight"));
                    if (offcanvas) offcanvas.hide();
                    showToast("Impact created successfully", "success");
                },
                error: () => showToast("Failed to create impact", "danger")
            });
        }

        function editData(id) {
            if (!hasPermission('impact', 'update')) {
                showToast("No permission to edit", "warning");
                return;
            }
            $.ajax({
                url: `{{ config('app.api_url') }}impacts/${id}`,
                method: "GET",
                success: function (response) {
                    const data = response.data ?? response;
                    const item = Array.isArray(data) ? data[0] : data;
                    $('#editImpactId').val(item.id);
                    $('#editTitle').val(item.title);
                    $('#editActiveStatus').prop('checked', item.active == 1);
                    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById("editOffcanvas"));
                    offcanvas.show();
                },
                error: function () {
                    showToast("Unable to fetch impact details.", "danger");
                }
            });
        }

        function updateData() {
            if (!hasPermission('impact', 'update')) {
                showToast("No permission to update", "warning");
                return;
            }
            const id = $('#editImpactId').val();
            const title = $('#editTitle').val().trim();
            const isActive = $('#editActiveStatus').is(':checked') ? 1 : 0;
            if (!title) {
                showToast("Enter a title", "warning");
                return;
            }

            $.ajax({
                url: `{{ config('app.api_url') }}impactsupdate/${id}`,
                method: 'POST',
                data: {
                    title: title,
                    company_id: companyId,
                    active: isActive
                },
                success: function () {
                    fetchTable();
                    const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                    if (offcanvas) offcanvas.hide();
                    showToast("Impact updated successfully", "success");
                },
                error: function () {
                    showToast("Update failed", "danger");
                }
            });
        }

        function updatestatus(id, element) {
            const isChecked = $(element).prop('checked') ? 1 : 0;
            const form = new FormData();
            form.append("id", id);
            form.append("active", isChecked);

            $.ajax({
                url: `{{ config('app.api_url') }}updateimpactstatus`,
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                timeout: 0,
                success: function () {
                    fetchTable();
                    if (isChecked === 1) showToast("Status activated successfully", "success");
                    else showToast("Status deactivated successfully", "warning");
                },
                error: function () {
                    showToast("Failed to update status", "danger");
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
