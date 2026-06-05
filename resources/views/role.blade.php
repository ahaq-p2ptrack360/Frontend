@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection

@section('css')
<!-- Datatable CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<!-- Bootstrap Toggle CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Profile @endslot
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

    #customExportButtons .dt-buttons .btn,
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

    .dataTables_wrapper {
        overflow: visible !important;
        position: relative;
        z-index: 10;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <button id="addNewBtn" class="btn btn-primary py-2 px-3" type="button" data-bs-toggle="modal" data-bs-target="#myModal">
                        Add New
                    </button>
                    <div id="customExportButtons" class="d-flex align-items-center"></div>
                </div>
            </div>
            <div class="card-body">
                <table id="myTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Company</th>
                            <th>Title</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- populated via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Create Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" required>
                    </div>
                    <!-- add company_id or other fields if needed -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" id="button" onclick="createRole()" name="button"
                    class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div id="myModalup" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <input type="hidden" name="" id="updateid">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="titleedit" required>
                    </div>
                    <!-- add company_id or other fields if needed -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" id="button" onclick="updateRole()" name="button"
                    class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- Edit Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRightEdit">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit Profile</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editRoleForm" onsubmit="return false;">
            <input type="hidden" id="editRoleId" name="id">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="titleEdit" class="form-label">Title</label>
                    <input type="text" class="form-control" id="titleEdit" name="title" placeholder="Enter Title" required>
                </div>
                <!-- add other editable fields as necessary -->
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-dark me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="button" class="btn btn-primary" id="updateRoleBtn">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Datatable JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>

<!-- Export buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

<!-- Excel/PDF support -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Bootstrap Toggle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<script>
    let table;

    // Setup CSRF token header if needed (Laravel default). Remove if your API doesn't use it.
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // uncomment if you have meta tag
        }
    });

    $(document).ready(function() {
        table = $('#myTable').DataTable({
            dom: 'rtip'
            // you can extend with buttons here if desired
        });
        fetchTable();

        $('#createRoleBtn').on('click', function() {
            createRole();
        });

        $('#updateRoleBtn').on('click', function() {
            updateRole();
        });
    });

    function fetchTable() {
        $.ajax({
            url: '{{Auth::user()->company_id}}'==0?"{{ config('app.api_url') }}roles":"{{ config('app.api_url') }}roles/comapny/{{Auth::user()->company_id}}",
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                table.clear();
                $.each(response, function(index, data) {
                    table.row.add([
                        index + 1,
                        "-", // placeholder for Company if not available
                        data.name || data.title || '',
                        `<button type="button" onclick="openEdit(${data.id})" class="btn btn-warning waves-effect waves-light">
                            <i class="ri-pencil-fill font-size-16 align-middle"></i>
                        </button>`
                    ]);
                });
                table.draw(false);
            },
            error: function() {
                console.error('Failed to fetch roles');
            }
        });
    }

    function createRole() {
        const title = $('#title').val().trim();
        if (!title) {
            alert('Title is required');
            return;
        }

        const form = new FormData();
        form.append('title', title);
        form.append('company_id', 0); // adjust if needed
        form.append('active', 1);

        $.ajax({
            url: "{{ config('app.api_url') }}roles",
            method: 'POST',
            processData: false,
            contentType: false,
            data: form,
            success: function() {
                // close canvas
                
                $('#myModal').modal('hide')
                fetchTable();
                showToast('Role created successfully', "success");

                $('#title').val('');
            },
            error: function(xhr, status, err) {
                console.log(xhr)
                showToast('Failed to create role', "error");
            }
        });
    }

    function openEdit(id) {
        // Prefill edit form
        $.ajax({
            url: `{{ config('app.api_url') }}roles/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const item = Array.isArray(response) ? response[0] : response;
                $('#updateid').val(item.id);
                $('#titleedit').val(item.name || '');
                // show offcanvas
                $('#myModalup').modal('show');
                // const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRightEdit'));
                // offcanvas.show();
            },
            error: function() {
                Swal.fire('Server Error!', 'Could not load role data', 'error');
            }
        });
    }

    function updateRole() {
        const id = $('#updateid').val();
        const title = $('#titleedit').val().trim();
        if (!id || !title) {
            alert('All fields are required');
            return;
        }

        const payload = {
            title,
            company_id: '{{ Auth::user()->company_id }}',
            active: 1,

        };

        $.ajax({
            url: `{{ config('app.api_url') }}roles/${id}`,
            method: 'post',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function() {
                $('#myModalup').modal('hide')
                fetchTable();
                showToast('Role updated successfully', "success");
          
            },
            error: function() {
                showToast('Failed to update role', "error");

                
            }
        });
    }

    function deleteData(id) {
        $.ajax({
            url: `{{ config('app.api_url') }}roles/${id}`,
            method: 'DELETE',
            success: function() {
                fetchTable();
                showToast('Role delete successfully', "success");

               
            },
            error: function() {
                showToast('Failed to delete role', "error");

               
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