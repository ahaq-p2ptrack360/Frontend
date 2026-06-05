@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<style>
body {
     font-size: 13px !important; 
}
table th,
 .card-body table td {
    padding: 6px 8px; 
    color: #6c757d; 
}

#offcanvasRight {
    width: 25% !important;
    max-width: none; 
}
#customExportButtons .dt-buttons .btn, #addNewBtn {
    height: 38px; 
    padding: 6px 12px; 
}
.dt-buttons {
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
        opacity: 1; transform: translateY(0); }

}
div.dt-button-collection .dt-button {
    padding: 12px 20px;
    background: transparent !important; 
    color: #333;
    font-weight: 500;
    font-size: 13px;
    display: flex;
    align-items: center;
    margin: 0;
    border-radius: 10px;
    cursor: pointer;
    user-select: none;
    transition: background 0.3s ease, color 0.3s ease, transform 0.3s ease;
}

div.dt-button-collection .dt-button:hover {
    background: rgba(74, 108, 247, 0.1) !important; 
    color: #4a6cf7 !important;
    transform: translateX(4px);    
}

div.dt-button-collection .dt-button i {
    margin-right: 12px;
    color: #4a6cf7;
    font-size: 14px; 
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Designation @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button id="addNewBtn" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">Add New</button>
                    <div id="customExportButtons"></div>
                </div>
            </div>

            <div class="card-body">
                <table id="designation-table" class="table table-striped nowrap align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasRightLabel">Create Designation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <input type="hidden" id="designation_id">
        <div class="mb-3">
            <label class="form-label">Enter Title</label>
            <input type="text" class="form-control" id="title" placeholder="Enter Title">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="status1" checked>
            <label class="form-check-label">Active / Inactive</label>
        </div>
        <div class="text-end">
            <button class="btn btn-primary" id="saveBtn">Save</button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let table;
let editMode = false;
const roleId = '{{ Auth::user()->role }}';
const companyId = '{{ Auth::user()->company_id }}';
let permissions = null;

$(document).ready(function() {

    loadPermissions(roleId).then(() => {
        applyPermissionUI();
        initTable();
    });

    $('#addNewBtn').on('click', function() {
        editMode = false;
        $('#designation_id').val('');
        $('#title').val('');
        $('#status1').prop('checked', true);
        $('#offcanvasRightLabel').text('Create Designation');
    });

    $('#saveBtn').on('click', function() {
        const title = $('#title').val().trim();
        const active = $('#status1').is(':checked') ? 1 : 0;

        if (!title) { showToast("Title is required","warning"); return; }

        if (!editMode) {
            $.post("{{ config('app.api_url') }}designations", {title, active, company_id: companyId})
            .done(()=> { 
                showToast("Designation created successfully","success");
                bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasRight')).hide();
                table.ajax.reload();
            });
        } else {
            const id = $('#designation_id').val();
            $.post(`{{ config('app.api_url') }}designationsupdate/${id}`, {title, active, company_id: companyId})
            .done(()=> { 
                showToast("Designation updated successfully","success");
                bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasRight')).hide();
                table.ajax.reload();
                editMode = false;
            });
        }
    });
});

/* DATATABLE */
function initTable() {
    table = $('#designation-table').DataTable({
        ajax: {
            url: companyId == 0 
                ? "{{ config('app.api_url') }}designations" 
                : "{{ config('app.api_url') }}designations/company/{{ Auth::user()->company_id }}",
            dataSrc: ''
        },
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
        columns: [
            { data: null, render: (d, type, row, meta) => meta.row + 1 },
            { data: 'title', defaultContent: '-' },
            { data: 'created_at', defaultContent: '-' },
            { data: null, render: function(data){
                return `<button class="btn btn-sm btn-primary" onclick="editDesignation(${data.id},'${data.title}',${data.active})">
                    <i class="ri-pencil-fill"></i>
                </button>`;
            }}
        ],
        initComplete: function() {
            $('#customExportButtons').html('');
            table.buttons().container().appendTo('#customExportButtons');
        }
    });
}

function editDesignation(id, title, active){
    editMode = true;
    $('#designation_id').val(id);
    $('#title').val(title);
    $('#status1').prop('checked', active==1);
    $('#offcanvasRightLabel').text('Update Designation');
    bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvasRight')).show();
}

/* PERMISSIONS */
async function loadPermissions(roleId){
    try{
        const res = await $.get(`{{ config('app.api_url') }}permissions/${roleId}`);
        permissions = Array.isArray(res)? res[0] : res;
    }catch(e){ permissions=null; console.error(e); }
}

function hasPermission(section, action){
    if(!permissions) return false;
    const parsed = typeof permissions[section]==='object'? permissions[section]: JSON.parse(permissions[section]||'{}');
    return parsed[action]===1;
}

function applyPermissionUI(){
    if(!hasPermission('status','create')) $('#addNewBtn').hide();
}

/* TOAST */
function showToast(msg,type="info"){
    Toastify({text:msg,duration:3000,gravity:"top",position:"center",
        backgroundColor: type=="success"? "#28a745" : type=="danger"? "#dc3545" : type=="warning"? "#ffc107" : "#0d6efd", close:true}).showToast();
}
</script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection