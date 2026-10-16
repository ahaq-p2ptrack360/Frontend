@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/2.0.4/css/colReorder.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowgroup/1.5.1/css/rowGroup.dataTables.min.css" rel="stylesheet">

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

    .dt-buttons {
        margin-bottom: 15px;
    }

    .bootstrap-select .dropdown-toggle {
        background-color: #8a8781 !important;
        color: white !important;
        box-shadow: none !important;
    }

    .bootstrap-select .dropdown-toggle::after {
        display: none !important;
    }

    .colvis-search {
        width: 100%;
        margin: 5px 0;
        padding: 5px;
        box-sizing: border-box;
    }

    .group-count {
        background-color: #f8f9fa !important;
        font-weight: bold;
        color: #495057 !important;
    }

    /* ===== Grouped Dropdown Styles - Same as Column Visibility ===== */
    .grouped-dropdown-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 100%;
    }

    .grouped-dropdown-btn {
        background-color: #8a8781;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 38px;
    }

    .grouped-dropdown-btn:hover {
        background-color: #6c757d;
    }

    .grouped-dropdown-btn .badge-count {
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        padding: 1px 8px;
        font-size: 11px;
        margin-left: 5px;
    }

    .grouped-dropdown-menu {
        display: none;
        position: absolute;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        z-index: 10000;
        padding: 5px;
        min-width: 200px;
        max-height: 500px;
        overflow: hidden;
        top: 100%;
        left: 0;
        margin-top: 2px;
        width: 100%;
    }

    .grouped-dropdown-menu.show {
        display: block;
    }

    .grouped-dropdown-search {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 12px;
    }

    .group-container {
        margin-bottom: 8px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        overflow: hidden;
    }

    .group-title {
        padding: 6px 10px;
        background-color: #f8f9fa;
        cursor: pointer;
        font-weight: bold;
        font-size: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
    }

    .group-title:hover {
        background-color: #e9ecef;
    }

    .group-title .group-icon {
        margin-right: 5px;
    }

    .columns-container {
        padding: 5px 10px;
        display: none;
        max-height: 200px;
        overflow-y: auto;
    }

    .columns-container.open {
        display: block;
    }

    .column-item {
        display: flex;
        align-items: center;
        padding: 3px 0;
        font-size: 12px;
    }

    .column-item input[type="checkbox"] {
        margin-right: 8px;
        cursor: pointer;
    }

    .column-item label {
        cursor: pointer;
        margin: 0;
        font-size: 12px;
        flex: 1;
    }

    .column-item .check-mark {
        color: #0d6efd;
        font-weight: bold;
        font-size: 14px;
        display: none;
    }

    .column-item .check-mark.visible {
        display: inline;
    }

    .dropdown-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }

    .dropdown-actions button {
        font-size: 11px;
        padding: 4px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .btn-select-all {
        background-color: #0d6efd;
        color: white;
    }

    .btn-select-all:hover {
        background-color: #0b5ed7;
    }

    .btn-deselect-all {
        background-color: #6c757d;
        color: white;
    }

    .btn-deselect-all:hover {
        background-color: #5a6268;
    }

    .selected-count {
        font-size: 11px;
        color: #6c757d;
        padding: 4px 8px;
        background: #f8f9fa;
        border-radius: 3px;
        text-align: center;
        margin-top: 5px;
    }

    .filter-field-container {
        margin-bottom: 8px;
    }

    #static_fields,
    #group_static_fields {
        position: relative;
        width: 100%;
    }

    /* Fix for dropdown containers */
    #static_fields .grouped-dropdown-wrapper,
    #group_static_fields .grouped-dropdown-wrapper {
        width: 100%;
    }

    /* Filter dropdown ke liye specific width */
#static_fields .grouped-dropdown-wrapper {
    width: 100% !important;
    max-width: 100% !important;
}

/* Dono dropdowns ko consistent banane ke liye */
#static_fields .grouped-dropdown-btn,
#group_static_fields .grouped-dropdown-btn {
    width: 100% !important;
    min-width: 150px !important;
    max-width: 100% !important;
}

/* Dropdown menu ki width */
#static_fields .grouped-dropdown-menu,
#group_static_fields .grouped-dropdown-menu {
    width: 100% !important;
    min-width: 150px !important;
    max-width: 100% !important;
}

/* Filter dropdown ko fixed width dene ke liye */
#static_fields {
    max-width: 250px !important;  /* Group dropdown ke barabar */
    width: 100% !important;
}

#static_fields .grouped-dropdown-wrapper,
#group_static_fields .grouped-dropdown-wrapper {
    width: 100% !important;
    max-width: 100% !important;
}

#static_fields .grouped-dropdown-btn,
#group_static_fields .grouped-dropdown-btn {
    width: 100% !important;
    min-width: 150px !important;
}

#static_fields .grouped-dropdown-menu,
#group_static_fields .grouped-dropdown-menu {
    width: 100% !important;
    min-width: 150px !important;
}
</style>

@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Reports @endslot
@endcomponent

<div class="row">
    <div class="col-2">
        <div class="row">
            <div class="col-12" id="group_static_fields" style="margin-left: 0px; padding-left: 0px;">
            </div>
            <hr style="margin-top:3px">

            <div class="col-12" id="group_fields" style="height: 250px; overflow-y:auto;">
            </div>
            <button type="button" id="group_button" class="btn btn-dark btn-sm" style="margin-bottm: 3px; margin-left: 75px; margin-bottom: 3px; width: 50%; display:none">Create Group</button>
            <hr style="margin-top:3px">
            <div class="col-12" id="groups_overlay" style="height: 250px; overflow-y: auto">
            </div>
        </div>
    </div>

    <div class="col-lg-10">
        <div class="col-12" id="static_fields" style="margin-left: 0px; padding-left: 0px;">
        </div>
        <div class="col-2" id="filter_div" style="display:none;">
            <select class="form-select" aria-label="Default select example" id="table_filter">
                <option value="{{Auth::user()->id}}" name="assigned_to">Assigned To Me</option>
                <option value="{{Auth::user()->id}}" name="created_by">created By Me</option>
                <option value="all">All</option>
            </select>
        </div>
        <div id="myPieChart" class="d-none"></div>
        <hr style="margin: 8px 0px;">

        <div class="col-12" style="margin-left:20px">
            <h6 class="">Filter Fields</h6>
            <div class="row" id="filter_fields">
            </div>
            <div class="d-flex justify-content-start">
                <button type="button" id="save_view_btn" class="btn btn-soft-primary waves-effect waves-light" style="display: none; margin-top: 7px">Save</button>
                <button type="button" id="find_view_btn" class="btn btn-soft-primary waves-effect waves-light" style="margin-top: 7px; margin-left: 3px;">Views</button>
            </div>
        </div>
        <hr>
        <div class="card-header">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" id="addNewBtn">Add New</button>
        <div class="d-flex align-items-center gap-3">
            <!-- ✅ ADD THIS CHECKBOX -->
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="showSubTotalCheckbox">
                <label class="form-check-label" for="showSubTotalCheckbox" style="font-size:13px; color:#6c757d;">
                    Show Sub Total
                </label>
            </div>
            <div id="exportButtons"></div>
        </div>
    </div>
</div>

            <div class="card-body">
                <table id="myTable" class="table table-striped" style="width:100%">
                    <thead style="width: 100%;">
                        <tr id="table_records">
                            <th>S.No</th>
                            <th>Ticket No</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Ticket ID</th>
                            <th>Due Date</th>
                            <th>Business Unit</th>
                            <th>Reported By</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Impact</th>
                            <th>Description</th>
                            <th>Complaint Mode</th>
                            <th>Store Contact</th>
                            <th>Remarks</th>
                            <th>BU Email</th>
                            <th>BU Phone</th>
                            <th>BU Address</th>
                            <th>BU City</th>
                            <th>BU State</th>
                            <th>BU Country</th>
                            <th>BU Alt Phone</th>
                            <th>BU Zipcode</th>
                            <th>Cust First Name</th>
                            <th>Cust Last Name</th>
                            <th>Cust Email</th>
                            <th>Cust Phone</th>
                            <th>Cust Address</th>
                            <th>Cust City</th>
                            <th>Cust State</th>
                            <th>Cust Country</th>
                            <th>Quote Amount</th>
                            <th>Quote Approved Amount</th>
                            <th>Quote Actual Amount</th>
                            <th>Quote Description</th>
                            <th>Quote Approved By</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas Component -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Create Report</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Enter Priority Title">
            </div>
            <div class="mb-3">
                <label for="sla" class="form-label">SLA</label>
                <input type="text" class="form-control" id="sla" placeholder="Enter SLA">
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ticket_view_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <h5 id="label">Views</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <ul style="list-style: none;" id="view_list"></ul>
                    </div>
                    <div class="col-12 mt-3" style="text-align: right;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_chart_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div id="chart_div">
                        <label for="chart_select" style="font-size:13px; margin-top: 4px">Chart Type</label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="0">Pie</option>
                            <option value="1">Bar</option>
                            <option value="2">Line</option>
                        </select>
                    </div>
                    <div id="x_field" class="d-none">
                        <label for="XSelect" style="font-size:13px; margin-top: 4px">X-Axis</label>
                        <select id="XXSelect" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example"></select>
                    </div>
                    <div id="y_field" class="d-none">
                        <label for="YSelect" style="font-size:13px; margin-top: 4px">Y-Axis</label>
                        <select id="YYSelect" class="form-select form-select-sm" aria-label=".form-select-sm example"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_btn_2" class="btn btn-secondary" data-bs-dismiss="modal" onclick="get_field()">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div id="chart_div_graph">
                        <label for="chart_select_graph" style="font-size:13px; margin-top: 4px">Chart Type</label>
                        <select class="form-select" id="chart_select_graph" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="0">Pie</option>
                            <option value="1">Bar</option>
                            <option value="2">Line</option>
                        </select>
                    </div>
                    <div id="x_field_graph" class="d-none">
                        <label for="XSelect" style="font-size:13px; margin-top: 4px">X-Axis</label>
                        <select id="XSelect" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example"></select>
                    </div>
                    <div id="y_field_graph" class="d-none">
                        <label for="YSelect" style="font-size:13px; margin-top: 4px">Y-Axis</label>
                        <select id="YSelect" class="form-select form-select-sm" aria-label=".form-select-sm example"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_btn" class="btn btn-secondary" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ticket_filter_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <h5 id="label">Save View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <label for="example-text-input" class="col-auto form-label">View Name</label>
                            <input type="text" class="col-auto form-control" id="filter_name">
                        </div>
                    </div>
                    <div class="col-12 mt-3" style="text-align: right;">
                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                        <input class="btn btn-primary waves-effect waves-light" type="submit" name="app_btn" id="view_btn" onclick="save_filters()" value="Save">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js" integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js" integrity="sha512-o36qZrjup13zLM13tqxvZTaXMXs+5i4TL5UWaDCsmbp5qUcijtdCFuW9a/3qnHGfWzFHBAln8ODjf7AnUNebVg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/2.0.4/js/dataTables.colReorder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

const numericFields = [
    // Quote fields (monetary values)
    'quote_amount',
    'quote_approved_amount',
    'quote_actual_amount',

    // ID fields (numeric identifiers)
    'type_id',
    'status_id',
    'priority_id',
    'impact_id',
    'company_id',
    'business_unit_id',
    'ticket_id'
];

// Helper function to check if a field is numeric
function isNumericField(field) {
    return numericFields.includes(field);
}

   let distinctValues = {
    resolved_name: [],
    company_name: [],
    title: [],
    completed_date: [],
    due_date: [],
    assigned_to: [],
    bu_name: [],
    reported_by_name: [],
    type_title: [],
    status_title: [],
    priority_title: [],
    impact_title: [],
    customer: [],
    bu_field_name: [],
    created_at: [],
    bu_email: [],
    bu_phone: [],
    bu_address: [],
    bu_city: [],
    bu_state: [],
    bu_country: [],
    bu_alternate_phone: [],
    bu_zipcode: [],
    fname: [],
    lname: [],
    created_byfname: [],
    created_bylname: [],
    customer_email: [],
    customer_phone: [],
    customer_address: [],
    customer_city: [],
    customer_state: [],
    customer_country: [],
    quote_amount: [],
    quote_approved_amount: [],
    quote_actual_amount: [],
    quote_description: [],
    quote_approved_by: [],
    'type_id': [],
    'type_title': [],
    'type_active': [],
    'status_id': [],
    'status_title': [],
    'status_active': [],
    'priority_id': [],
    'priority_title': [],
    'priority_active': [],
    'priority_sla': [],
    'impact_id': [],
    'impact_title': [],
    'impact_active': [],
    'impact_created_by': [],
    'impact_updated_by': []
};
    let date_fields = ["created_at", "completed_date", "due_date"];
    let selected_groups = [];
    var col_index = {
    "title": 2,
    "company_name": 3,
    "completed_date": 4,
    "due_date": 5,
    "bu_name": 6,
    "resolved_name": 7,
    "reported_by_name": 7,
    "type_title": 8,
    "status_title": 9,
    "priority_title": 10,
    "impact_title": 11,
    "bu_email": 16,
    "bu_phone": 17,
    "bu_address": 18,
    "bu_city": 19,
    "bu_state": 20,
    "bu_country": 21,
    "bu_alternate_phone": 22,
    "bu_zipcode": 23,
    "customer_first_name": 24,
    "customer_last_name": 25,
    "customer_email": 26,
    "customer_phone": 27,
    "customer_address": 28,
    "customer_city": 29,
    "customer_state": 30,
    "customer_country": 31,
    "quote_amount": 32,
    "quote_approved_amount": 33,
    "quote_actual_amount": 34,
    "quote_description": 35,
    "quote_approved_by": 36,
    "type_id": 37,
    "type_title": 38,
    "type_active": 39,
    "status_id": 40,
    "status_title": 41,
    "status_active": 42,
    "priority_id": 43,
    "priority_title": 44,
    "priority_active": 45,
    "priority_sla": 46,
    "impact_id": 47,
    "impact_title": 48,
    "impact_active": 49,
    "impact_created_by": 50,
    "impact_updated_by": 51
};
    let XField;
    let YField;
    let fil_group_obj;
    let created_at_max = 0;
    let created_at_min = 0;
    let completed_date_max = 0;
    let completed_date_min = 0;
    let due_date_max = 0;
    let due_date_min = 0;
    let line_chart;
    let pie_chart;
    let bar_chart;
    let chart_val = "1";
    let groups = []
    var count_array = {}
    let group_no = 1
    let filtered_data_filter;
    let columns = [];
    let data = [];
    let selected_values = [];
    let showSubTotal = false;

    // ===== Store Column Visibility state =====
    let columnVisibilityState = {
        visibleColumns: [],
        initialized: false
    };

    var selectedValues = {
    resolved_name: [],
    company_name: [],
    title: [],
    completed_date: [],
    due_date: [],
    assigned_to: [],
    bu_name: [],
    reported_by_name: [],
    type_title: [],
    status_title: [],
    priority_title: [],
    impact_title: [],
    customer: [],
    bu_field_name: [],
    created_at: [],
    bu_email: [],
    bu_phone: [],
    bu_address: [],
    bu_city: [],
    bu_state: [],
    bu_country: [],
    bu_alternate_phone: [],
    bu_zipcode: [],
    customer_first_name: [],
    customer_last_name: [],
    customer_email: [],
    customer_phone: [],
    customer_address: [],
    customer_city: [],
    customer_state: [],
    customer_country: [],
    quote_amount: [],
    quote_approved_amount: [],
    quote_actual_amount: [],
    quote_description: [],
    quote_approved_by: [],
    type_id: [],
    type_title: [],
    type_active: [],
    status_id: [],
    status_title: [],
    status_active: [],
    priority_id: [],
    priority_title: [],
    priority_active: [],
    priority_sla: [],
    impact_id: [],
    impact_title: [],
    impact_active: [],
    impact_created_by: [],
    impact_updated_by: []
};

    // FIELD GROUPS CONFIGURATION
    const fieldGroupsConfig = [
    {
        name: 'Ticket Information',
        icon: '🎫',
        fields: ['company_name', 'title', 'completed_date', 'due_date', 'reported_by_name', 'type_title', 'status_title', 'priority_title', 'impact_title', 'created_at']
    },
    {
        name: 'Types',
        icon: '📋',
        fields: ['type_id', 'type_title', 'type_active']
    },
    {
        name: 'Status',
        icon: '✅',
        fields: ['status_id', 'status_title', 'status_active']
    },
    {
        name: 'Priority',
        icon: '⭐',
        fields: ['priority_id', 'priority_title', 'priority_active', 'priority_sla']
    },
    {
        name: 'Impacts',
        icon: '💥',
        fields: ['impact_id', 'impact_title', 'impact_active', 'impact_created_by', 'impact_updated_by']
    },
    {
        name: 'Business Unit',
        icon: '🏢',
        fields: ['bu_name', 'bu_email', 'bu_phone', 'bu_address', 'bu_city', 'bu_state', 'bu_country', 'bu_alternate_phone', 'bu_zipcode']
    },
    {
        name: 'Account Information',
        icon: '👤',
        fields: ['fname', 'lname', 'customer_email', 'customer_phone', 'customer_address', 'customer_city', 'customer_state', 'customer_country']
    },
    {
        name: 'Quote Information',
        icon: '💰',
        fields: ['quote_amount', 'quote_approved_amount', 'quote_actual_amount', 'quote_description', 'quote_approved_by']
    },
    {
        name: 'Dynamic Fields',
        icon: '📊',
        fields: ['bu_field_name']
    }
];

    // GROUPED DROPDOWN FUNCTIONS

    function getFieldDisplayName(fieldKey) {
        const displayNames = {
    'company_name': 'Company',
    'bu_name': 'Business Unit',
    'priority_title': 'Priority',
    'impact_title': 'Impact',
    'resolved_name': 'Resolved By',
    'reported_by_name': 'Reported By',
    'title': 'Title',
    'completed_date': 'Completed Date',
    'due_date': 'Due Date',
    'type_title': 'Type',
    'status_title': 'Status',
    'created_at': 'Created At',
    'bu_email': 'BU Email',
    'bu_phone': 'BU Phone',
    'bu_address': 'BU Address',
    'bu_city': 'BU City',
    'bu_state': 'BU State',
    'bu_country': 'BU Country',
    'bu_alternate_phone': 'BU Alt Phone',
    'bu_zipcode': 'BU Zipcode',
    'fname': 'First Name',
    'lname': 'Last Name',
    'created_byfname': 'Created By First Name',
    'created_bylname': 'Created By Last Name',
    'customer_email': 'Customer Email',
    'customer_phone': 'Customer Phone',
    'customer_address': 'Customer Address',
    'customer_city': 'Customer City',
    'customer_state': 'Customer State',
    'customer_country': 'Customer Country',
    'quote_amount': 'Quote Amount',
    'quote_approved_amount': 'Quote Approved Amount',
    'quote_actual_amount': 'Quote Actual Amount',
    'quote_description': 'Quote Description',
    'quote_approved_by': 'Quote Approved By',
    'bu_field_name': 'Dynamic Fields',
    'type_id': 'Type ID',
    'type_title': 'Type',
    'type_active': 'Type Active',
    'status_id': 'Status ID',
    'status_title': 'Status',
    'status_active': 'Status Active',
    'priority_id': 'Priority ID',
    'priority_title': 'Priority',
    'priority_active': 'Priority Active',
    'priority_sla': 'Priority SLA',
    'impact_id': 'Impact ID',
    'impact_title': 'Impact',
    'impact_active': 'Impact Active',
    'impact_created_by': 'Impact Created By',
    'impact_updated_by': 'Impact Updated By'
};
        return displayNames[fieldKey] || fieldKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function getAllFieldKeys() {
        const keys = [];
        Object.keys(distinctValues).forEach(key => {
            if (distinctValues[key] && distinctValues[key].length > 0) {
                keys.push(key);
            }
        });
        return keys;
    }

    function getGroupFields(groupConfig) {
        const allKeys = getAllFieldKeys();
        if (groupConfig.fields.includes('bu_field_name')) {
            const dynamicFields = distinctValues.bu_field_name || [];
            return dynamicFields.filter(f => allKeys.includes(f));
        }
        return groupConfig.fields.filter(f => allKeys.includes(f));
    }

    function createGroupedDropdownHTML(dropdownId, selectedKeys = []) {
        let html = '';

        html += `<input type="text" class="grouped-dropdown-search" placeholder="🔍 Search fields..." id="search_${dropdownId}">`;

        fieldGroupsConfig.forEach((group, index) => {
            const groupFields = getGroupFields(group);
            if (groupFields.length === 0) return;

            const hasSelected = groupFields.some(f => selectedKeys.includes(f));

            html += `<div class="group-container" data-group="${index}">`;
            html += `<div class="group-title" data-group="${index}">`;
            html += `<span><span class="group-icon">${group.icon}</span> ${group.name}</span>`;
            html += `<span style="display:flex;align-items:center;gap:5px;">
                        <span style="font-size:11px;color:#6c757d;">(${groupFields.length})</span>
                        <i class="ri-arrow-down-s-line" style="transition:transform 0.3s;${hasSelected ? '' : 'transform:rotate(-90deg);'}"></i>
                    </span>`;
            html += `</div>`;
            html += `<div class="columns-container ${hasSelected ? 'open' : ''}" data-group="${index}">`;

            groupFields.forEach(field => {
                const displayName = getFieldDisplayName(field);
                const isChecked = selectedKeys.includes(field) ? 'checked' : '';
                html += `<div class="column-item" data-field="${field}">`;
                html += `<input type="checkbox" class="field-checkbox" value="${field}" ${isChecked} id="chk_${dropdownId}_${field}">`;
                html += `<label for="chk_${dropdownId}_${field}">${displayName}</label>`;
                html += `<span class="check-mark ${isChecked ? 'visible' : ''}">✓</span>`;
                html += `</div>`;
            });

            html += `</div>`;
            html += `</div>`;
        });

        html += `<div class="dropdown-actions">`;
        html += `<button class="btn-select-all" data-dropdown="${dropdownId}">✓ Select All</button>`;
        html += `<button class="btn-deselect-all" data-dropdown="${dropdownId}">✕ Deselect All</button>`;
        html += `</div>`;

        html += `<div class="selected-count" id="count_${dropdownId}">Selected: ${selectedKeys.length}</div>`;

        return html;
    }

    function initGroupedDropdown(dropdownId, selectedKeys = [], isGroup = false) {
        const container = document.getElementById(dropdownId);
        if (!container) return;

        container.dataset.selectedKeys = JSON.stringify(selectedKeys);

        const html = createGroupedDropdownHTML(dropdownId, selectedKeys);
        container.innerHTML = html;

        setupGroupedDropdownEvents(dropdownId, isGroup);
    }

    function setupGroupedDropdownEvents(dropdownId, isGroup) {
        const container = document.getElementById(dropdownId);
        if (!container) return;

        container.querySelectorAll('.group-title').forEach(title => {
            title.addEventListener('click', function() {
                const columnsContainer = this.nextElementSibling;
                const icon = this.querySelector('.ri-arrow-down-s-line');
                if (columnsContainer) {
                    columnsContainer.classList.toggle('open');
                    if (icon) {
                        icon.style.transform = columnsContainer.classList.contains('open') ? 'rotate(0deg)' : 'rotate(-90deg)';
                    }
                }
            });
        });

        const searchInput = container.querySelector(`#search_${dropdownId}`);
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase().trim();
                const groups = container.querySelectorAll('.group-container');

                groups.forEach(group => {
                    const groupTitle = group.querySelector('.group-title span').textContent.toLowerCase();
                    const items = group.querySelectorAll('.column-item');
                    let hasVisible = false;

                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(query) || groupTitle.includes(query)) {
                            item.style.display = 'flex';
                            hasVisible = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    group.style.display = hasVisible || query === '' ? 'block' : 'none';

                    if (query.length > 0) {
                        const container = group.querySelector('.columns-container');
                        const icon = group.querySelector('.ri-arrow-down-s-line');
                        if (container) container.classList.add('open');
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        }

        container.querySelectorAll('.btn-select-all').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const visibleCheckboxes = container.querySelectorAll('.column-item:not([style*="display: none"]) .field-checkbox');
                visibleCheckboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        container.querySelectorAll('.btn-deselect-all').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const checkboxes = container.querySelectorAll('.field-checkbox');
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        cb.checked = false;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        container.querySelectorAll('.field-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const field = this.value;
                const isChecked = this.checked;
                const item = this.closest('.column-item');
                const checkMark = item ? item.querySelector('.check-mark') : null;

                if (checkMark) {
                    checkMark.classList.toggle('visible', isChecked);
                }

                if (isGroup) {
                    if (isChecked) {
                        if (!selected_groups.includes(field)) {
                            selected_groups.push(field);
                            update_record_box(field);
                        }
                    } else {
                        selected_groups = selected_groups.filter(f => f !== field);
                        const elem = document.querySelector(`.${field}Container`);
                        if (elem) elem.remove();
                    }
                } else {
                    if (isChecked) {
                        if (!selected_values.includes(field)) {
                            selected_values.push(field);

                            if (!selectedValues.hasOwnProperty(field)) {
                                selectedValues[field] = [];
                            }

                            if (date_fields.includes(field)) {
                                date_field_filter(field);
                            } else {
                                add_field_filter(field);
                            }
                        }
                    } else {
                        selected_values = selected_values.filter(f => f !== field);
                        remove_field_filter(field);
                    }
                }

                const countEl = container.querySelector(`#count_${dropdownId}`);
                if (countEl) {
                    const totalSelected = container.querySelectorAll('.field-checkbox:checked').length;
                    countEl.textContent = `Selected: ${totalSelected}`;
                }

                const wrapper = container.closest('.grouped-dropdown-wrapper');
                if (wrapper) {
                    const hiddenSelect = wrapper.querySelector('select');
                    if (hiddenSelect) {
                        const values = isGroup ? selected_groups : selected_values;
                        $(hiddenSelect).val(values).trigger('change');
                    }
                }

                if (!isGroup) {
                    if (selected_values.length > 0) {
                        $('#save_view_btn').css('display', 'block');
                    } else {
                        $('#save_view_btn').css('display', 'none');
                    }
                }

                updateDropdownButton(dropdownId, isGroup);
            });
        });
    }

    function updateDropdownButton(dropdownId, isGroup) {
        const wrapper = document.querySelector(`#${dropdownId}`)?.closest('.grouped-dropdown-wrapper');
        if (!wrapper) return;

        const btn = wrapper.querySelector('.grouped-dropdown-btn');
        if (!btn) return;

        const values = isGroup ? selected_groups : selected_values;
        const label = isGroup ? 'Group' : 'Filter';
        const defaultText = isGroup ? 'No Group Selected' : 'No Filter Selected';

        let text = defaultText;
        if (values.length > 0) {
            text = `${label} Selected (${values.length})`;
        }

        btn.innerHTML = `<span>${text}</span><span class="caret">▼</span>`;
    }

    function renderGroupedDropdown(selectId, isGroup = false) {
    // ✅ ADD THIS CHECK - Prevent duplicate rendering
    const wrapperId = `${selectId}_wrapper`;
    if ($(`#${wrapperId}`).length > 0) {
        console.log(`Dropdown ${selectId} already exists, skipping...`);
        return;  // ← Agar already exists, toh return karein
    }

    const $select = $(`#${selectId}`);
    if (!$select.length) {
        console.warn(`Select with id ${selectId} not found`);
        return;
    }

    const container = $select.closest('.col-12, .col-2');
    if (!container.length) {
        console.warn(`Container for ${selectId} not found`);
        return;
    }

    // Clear container first to prevent duplicates
    $(container).empty();

    // Re-append the hidden select
    $(container).append($select);

    let $wrapper = $(`#${wrapperId}`);
    if ($wrapper.length) {
        $wrapper.remove();
    }

    // Hide the original select
    $select.hide();

        let wrapperWidth = isGroup ? '' : 'style="width: 100%; max-width: 100%;"';
        let btnWidth = isGroup ? '' : 'style="width: 100% !important;"';
        // Create wrapper with custom dropdown
        $wrapper = $(`
            <div id="${wrapperId}" class="grouped-dropdown-wrapper">
                <div class="grouped-dropdown-btn" id="${selectId}_btn">
                    <span>${isGroup ? 'No Group Selected' : 'No Filter Selected'}</span>
                    <span class="caret">▼</span>
                </div>
                <div class="grouped-dropdown-menu" id="${selectId}_menu"></div>
            </div>
        `);

        // Insert wrapper after the hidden select
        $select.after($wrapper);

        const selectedValuesArray = isGroup ? selected_groups : selected_values;

        const menu = document.getElementById(`${selectId}_menu`);
        if (menu) {
            initGroupedDropdown(`${selectId}_menu`, selectedValuesArray, isGroup);
        }

        const btn = document.getElementById(`${selectId}_btn`);
        if (btn) {
            // Remove any existing event listeners to prevent duplicates
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);

            newBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = document.getElementById(`${selectId}_menu`);
                if (menu) {
                    const isOpen = menu.classList.contains('show');

                    // Close all other dropdowns
                    document.querySelectorAll('.grouped-dropdown-menu.show').forEach(m => m.classList.remove('show'));

                    if (!isOpen) {
                        menu.classList.add('show');
                        const rect = this.getBoundingClientRect();
                        menu.style.top = '100%';
                        menu.style.left = '0';
                        menu.style.width = '100%';
                        menu.style.minWidth = '200px';
                    } else {
                        menu.classList.remove('show');
                    }
                }
            });
        }

        // Close dropdown when clicking outside
        $(document).off('click.dropdown_' + wrapperId).on('click.dropdown_' + wrapperId, function(e) {
            const wrapper = document.getElementById(wrapperId);
            if (wrapper && !wrapper.contains(e.target)) {
                const menu = document.getElementById(`${selectId}_menu`);
                if (menu) menu.classList.remove('show');
            }
        });
    }

    // FIX: Preserve Column Visibility State
    function saveColumnVisibilityState() {
        if (!table) return;

        try {
            const visibleColumns = [];
            const columnCount = table.columns().count();

            for (let i = 0; i < columnCount; i++) {
                if (table.column(i).visible()) {
                    visibleColumns.push(i);
                }
            }

            columnVisibilityState.visibleColumns = visibleColumns;
            columnVisibilityState.initialized = true;

            console.log('Column visibility state saved:', visibleColumns);
        } catch (e) {
            console.warn('Could not save column visibility state:', e);
        }
    }

    function restoreColumnVisibilityState() {
        if (!table || !columnVisibilityState.initialized) return;

        try {
            const visibleColumns = columnVisibilityState.visibleColumns;

            if (visibleColumns && visibleColumns.length > 0) {
                // First hide all columns
                for (let i = 0; i < table.columns().count(); i++) {
                    table.column(i).visible(false);
                }

                // Then show the saved ones
                visibleColumns.forEach(index => {
                    if (index < table.columns().count()) {
                        table.column(index).visible(true);
                    }
                });

                console.log('Column visibility state restored:', visibleColumns);
            }
        } catch (e) {
            console.warn('Could not restore column visibility state:', e);
        }
    }

    // ORIGINAL FUNCTIONS
    $(document).ready(function() {
        $("#datepicker-range").flatpickr({
            mode: 'range',
            dateFormat: "Y-m-d"
        });

        fetch('{{ config('app.api_url') }}retrieve_views/{{Auth::user()->id}}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer your-token'
            }
        })
        .then(response => response.json())
        .then(response => {
            console.log("Fetch", response);
            let ul = document.getElementById('view_list');
            response.forEach((value) => {
                let li = document.createElement('li');
                li.innerHTML = `<input type="radio" name="view_radio" id="${value["view_id"]}"> ${value["view_name"]}`;
                ul.appendChild(li);
            });
        })
        .catch(error => console.error('Error:', error));

        $.ajax({
            url: "{{ config('app.api_url') }}tickets/company/{{Auth::user()->company_id}}",
            method: "GET",
            timeout: 0,
        }).done(function(response) {
            console.log("First item:", response[0]);
            console.log("API Response:", response);
            data = response;

            created_at_max = response[0]["created_at"];
            created_at_min = response[0]["created_at"];
            completed_date_max = response[0]["completed_date"];
            completed_date_min = response[0]["completed_date"];
            due_date_max = response[0]["due_date"];
            due_date_min = response[0]["due_date"];

            columns = [
                { data: 0, title: "S.No" },
                { data: 1, title: "@if(session('userWithBU') && session('userWithBU')->ticket_represented){{ session('userWithBU')->ticket_represented }}@else Ticket @endif No" },
                { data: 2, title: "Title" },
                { data: 3, title: "Company" },
                { data: 4, title: "@if(session('userWithBU') && session('userWithBU')->ticket_represented){{ session('userWithBU')->ticket_represented }}@else Ticket @endif ID" },
                { data: 5, title: "Due Date" },
                { data: 6, title: "Business Unit" },
                { data: 7, title: "Reported By" },
                { data: 8, title: "Type" },
                { data: 9, title: "Status" },
                { data: 10, title: "Priority" },
                { data: 11, title: "Impact" },
                { data: 12, title: "Description" },
                { data: 13, title: "Complaint Mode" },
                { data: 14, title: "Store Contact" },
                { data: 15, title: "Remarks" },
                { data: 16, title: "BU Email" },
                { data: 17, title: "BU Phone" },
                { data: 18, title: "BU Address" },
                { data: 19, title: "BU City" },
                { data: 20, title: "BU State" },
                { data: 21, title: "BU Country" },
                { data: 22, title: "BU Alt Phone" },
                { data: 23, title: "BU Zipcode" },
                { data: 24, title: "First Name" },
                { data: 25, title: "Last Name" },
                { data: 26, title: "Customer Email" },
                { data: 27, title: "Customer Phone" },
                { data: 28, title: "Customer Address" },
                { data: 29, title: "Customer City" },
                { data: 30, title: "Customer State" },
                { data: 31, title: "Customer Country" },
                { data: 32, title: "Quote Amount" },
                { data: 33, title: "Quote Approved Amount" },
                { data: 34, title: "Quote Actual Amount" },
                { data: 35, title: "Quote Description" },
                { data: 36, title: "Quote Approved By" },
                { data: 37, title: "Type ID" },
                { data: 38, title: "Type Title" },
                { data: 39, title: "Type Active" },
                { data: 40, title: "Status ID" },
                { data: 41, title: "Status Title" },
                { data: 42, title: "Status Active" },
                { data: 43, title: "Priority ID" },
                { data: 44, title: "Priority Title" },
                { data: 45, title: "Priority Active" },
                { data: 46, title: "Priority SLA" },
                { data: 47, title: "Impact ID" },
                { data: 48, title: "Impact Title" },
                { data: 49, title: "Impact Active" },
                { data: 50, title: "Impact Created By" },
                { data: 51, title: "Impact Updated By" }
            ];

            response.forEach(item => {
                if (item.resolved_name && !distinctValues.resolved_name.includes(item.resolved_name)) {
                    distinctValues.resolved_name.push(item.resolved_name);
                }
                if (item.company_name && !distinctValues.company_name.includes(item.company_name)) {
                    distinctValues.company_name.push(item.company_name);
                }
                if (item.title && !distinctValues.title.includes(item.title)) {
                    distinctValues.title.push(item.title);
                }
                if (item.completed_date && !distinctValues.completed_date.includes(item.completed_date)) {
                    distinctValues.completed_date.push(item.completed_date);
                }
                if (item.due_date && !distinctValues.due_date.includes(item.due_date)) {
                    distinctValues.due_date.push(item.due_date);
                }
                if (item.bu_name && !distinctValues.bu_name.includes(item.bu_name)) {
                    distinctValues.bu_name.push(item.bu_name);
                }
                if (item.reported_by_name && !distinctValues.reported_by_name.includes(item.reported_by_name)) {
                    distinctValues.reported_by_name.push(item.reported_by_name);
                }
                if (item.type_title && !distinctValues.type_title.includes(item.type_title)) {
                    distinctValues.type_title.push(item.type_title);
                }
                if (item.status_title && !distinctValues.status_title.includes(item.status_title)) {
                    distinctValues.status_title.push(item.status_title);
                }
                if (item.priority_title && !distinctValues.priority_title.includes(item.priority_title)) {
                    distinctValues.priority_title.push(item.priority_title);
                }
                if (item.impact_title && !distinctValues.impact_title.includes(item.impact_title)) {
                    distinctValues.impact_title.push(item.impact_title);
                }
                if (item.customer && !distinctValues.customer.includes(item.customer)) {
                    distinctValues.customer.push(item.customer);
                }
                if (item.bu_field_name && !distinctValues.bu_field_name.includes(item.bu_field_name)) {
                    distinctValues.bu_field_name.push(item.bu_field_name);
                }
                if (item.created_at && !distinctValues.created_at.includes(item.created_at)) {
                    distinctValues.created_at.push(item.created_at);
                }
                if (item.bu_email && !distinctValues.bu_email.includes(item.bu_email)) {
                    distinctValues.bu_email.push(item.bu_email);
                }
                if (item.bu_phone && !distinctValues.bu_phone.includes(item.bu_phone)) {
                    distinctValues.bu_phone.push(item.bu_phone);
                }
                if (item.bu_address && !distinctValues.bu_address.includes(item.bu_address)) {
                    distinctValues.bu_address.push(item.bu_address);
                }
                if (item.bu_city && !distinctValues.bu_city.includes(item.bu_city)) {
                    distinctValues.bu_city.push(item.bu_city);
                }
                if (item.bu_state && !distinctValues.bu_state.includes(item.bu_state)) {
                    distinctValues.bu_state.push(item.bu_state);
                }
                if (item.bu_country && !distinctValues.bu_country.includes(item.bu_country)) {
                    distinctValues.bu_country.push(item.bu_country);
                }
                if (item.bu_alternate_phone && !distinctValues.bu_alternate_phone.includes(item.bu_alternate_phone)) {
                    distinctValues.bu_alternate_phone.push(item.bu_alternate_phone);
                }
                if (item.bu_zipcode && !distinctValues.bu_zipcode.includes(item.bu_zipcode)) {
                    distinctValues.bu_zipcode.push(item.bu_zipcode);
                }
                if (item.fname && !distinctValues.fname.includes(item.fname)) {
                    distinctValues.fname.push(item.fname);
                }
                if (item.lname && !distinctValues.lname.includes(item.lname)) {
                    distinctValues.lname.push(item.lname);
                }
                if (item.created_byfname && !distinctValues.created_byfname.includes(item.created_byfname)) {
                    distinctValues.created_byfname.push(item.created_byfname);
                }
                if (item.created_bylname && !distinctValues.created_bylname.includes(item.created_bylname)) {
                    distinctValues.created_bylname.push(item.created_bylname);
                }
                if (item.customer_email && !distinctValues.customer_email.includes(item.customer_email)) {
                    distinctValues.customer_email.push(item.customer_email);
                }
                if (item.customer_phone && !distinctValues.customer_phone.includes(item.customer_phone)) {
                    distinctValues.customer_phone.push(item.customer_phone);
                }
                if (item.customer_address && !distinctValues.customer_address.includes(item.customer_address)) {
                    distinctValues.customer_address.push(item.customer_address);
                }
                if (item.customer_city && !distinctValues.customer_city.includes(item.customer_city)) {
                    distinctValues.customer_city.push(item.customer_city);
                }
                if (item.customer_state && !distinctValues.customer_state.includes(item.customer_state)) {
                    distinctValues.customer_state.push(item.customer_state);
                }
                if (item.customer_country && !distinctValues.customer_country.includes(item.customer_country)) {
                    distinctValues.customer_country.push(item.customer_country);
                }
                if (item.quote_amount && !distinctValues.quote_amount.includes(item.quote_amount)) {
                    distinctValues.quote_amount.push(item.quote_amount);
                }
                if (item.quote_approved_amount && !distinctValues.quote_approved_amount.includes(item.quote_approved_amount)) {
                    distinctValues.quote_approved_amount.push(item.quote_approved_amount);
                }
                if (item.quote_actual_amount && !distinctValues.quote_actual_amount.includes(item.quote_actual_amount)) {
                    distinctValues.quote_actual_amount.push(item.quote_actual_amount);
                }
                if (item.quote_description && !distinctValues.quote_description.includes(item.quote_description)) {
                    distinctValues.quote_description.push(item.quote_description);
                }
                if (item.quote_approved_by && !distinctValues.quote_approved_by.includes(item.quote_approved_by)) {
                    distinctValues.quote_approved_by.push(item.quote_approved_by);
                }

                if (item.type_id && !distinctValues.type_id.includes(item.type_id)) {
        distinctValues.type_id.push(item.type_id);
    }
    if (item.type_title && !distinctValues.type_title.includes(item.type_title)) {
        distinctValues.type_title.push(item.type_title);
    }
    if (item.type_active !== undefined && !distinctValues.type_active.includes(item.type_active)) {
        distinctValues.type_active.push(item.type_active);
    }

    if (item.impact_id && !distinctValues.impact_id.includes(item.impact_id)) {
    distinctValues.impact_id.push(item.impact_id);
}
if (item.impact_title && !distinctValues.impact_title.includes(item.impact_title)) {
    distinctValues.impact_title.push(item.impact_title);
}
if (item.impact_active !== undefined && !distinctValues.impact_active.includes(item.impact_active)) {
    distinctValues.impact_active.push(item.impact_active);
}
if (item.impact_created_by && !distinctValues.impact_created_by.includes(item.impact_created_by)) {
    distinctValues.impact_created_by.push(item.impact_created_by);
}
if (item.impact_updated_by && !distinctValues.impact_updated_by.includes(item.impact_updated_by)) {
    distinctValues.impact_updated_by.push(item.impact_updated_by);
}

    if (item.status_id && !distinctValues.status_id.includes(item.status_id)) {
        distinctValues.status_id.push(item.status_id);
    }
    if (item.status_title && !distinctValues.status_title.includes(item.status_title)) {
        distinctValues.status_title.push(item.status_title);
    }
    if (item.status_active !== undefined && !distinctValues.status_active.includes(item.status_active)) {
        distinctValues.status_active.push(item.status_active);
    }

    if (item.priority_id && !distinctValues.priority_id.includes(item.priority_id)) {
        distinctValues.priority_id.push(item.priority_id);
    }
    if (item.priority_title && !distinctValues.priority_title.includes(item.priority_title)) {
        distinctValues.priority_title.push(item.priority_title);
    }
    if (item.priority_active !== undefined && !distinctValues.priority_active.includes(item.priority_active)) {
        distinctValues.priority_active.push(item.priority_active);
    }
    if (item.priority_sla && !distinctValues.priority_sla.includes(item.priority_sla)) {
        distinctValues.priority_sla.push(item.priority_sla);
    }
// });

                if (item.created_at > created_at_max) created_at_max = item.created_at;
                if (item.created_at < created_at_min) created_at_min = item.created_at;
                if (item.completed_date > completed_date_max) completed_date_max = item.completed_date;
                if (item.completed_date < completed_date_min) completed_date_min = item.completed_date;
                if (item.due_date > due_date_max) due_date_max = item.due_date;
                if (item.due_date < due_date_min) due_date_min = item.due_date;
            });

            const table_rec = document.querySelector('#table_records');
            distinctValues.bu_field_name.forEach((field, index) => {
                const th = document.createElement('th');
                th.textContent = field;
                table_rec.appendChild(th);
                columns.push({ data: 37 + index, title: field });
            });

            const inputFields = [
    "company_name", "bu_name", "priority_title", "impact_title", "resolved_name",
    "reported_by_name", "title", "completed_date", "due_date", "type_title",
    "status_title", "bu_field_name", "field_type", "field_data", "created_at",
    "bu_email", "bu_phone", "bu_address", "bu_city", "bu_state", "bu_country",
    "bu_alternate_phone", "bu_zipcode", "fname", "lname", "created_byfname",
    "created_bylname", "customer_email", "customer_phone", "customer_address",
    "customer_city", "customer_state", "customer_country", "quote_amount",
    "quote_approved_amount", "quote_actual_amount", "quote_description",
    "quote_approved_by",
    "type_id", "type_title", "type_active",
    "status_id", "status_title", "status_active",
    "priority_id", "priority_title", "priority_active", "priority_sla",
    "impact_id", "impact_title", "impact_active", "impact_created_by", "impact_updated_by"
];

            // Create hidden selects
            let opt = `<select class="selectpicker" id="static_dropdown" multiple
                        title="No Filter Selected" data-selected-text-format="count > 999"
                        data-live-search="true" style="display:none;">`;
            let g_opt = `<select class="selectpicker" id="g_static_dropdown" multiple
                        title="No Group Selected" data-selected-text-format="count > 999"
                        data-live-search="true" style="display:none;">`;

            inputFields.forEach(field => {
                opt += `<option value="${field}">${field}</option>`;
                g_opt += `<option value="${field}">${field}</option>`;
            });

            opt += `</select>`;
            g_opt += `</select>`;

            document.getElementById('static_fields').innerHTML = opt;
            document.getElementById('group_static_fields').innerHTML = g_opt;

            setTimeout(function() {
                renderGroupedDropdown('static_dropdown', false);
                renderGroupedDropdown('g_static_dropdown', true);
            }, 100);

            // Define the Column Visibility action with state preservation
            const columnVisibilityAction = function(e, dt, button, config) {
                saveColumnVisibilityState();

                let container = document.createElement('div');
                container.style.padding = '10px';
                container.style.minWidth = '250px';
                container.style.maxHeight = '400px';
                container.style.overflowY = 'auto';

                let searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.placeholder = 'Search columns...';
                searchInput.style.width = '100%';
                searchInput.style.marginBottom = '10px';
                searchInput.style.padding = '5px';
                searchInput.style.boxSizing = 'border-box';

                searchInput.addEventListener('keyup', function() {
                    let query = this.value.toLowerCase();
                    let groups = container.querySelectorAll('.group-container');
                    groups.forEach(group => {
                        let groupTitle = group.querySelector('.group-title').textContent.toLowerCase();
                        let columns = group.querySelectorAll('.column-item');
                        let matchFound = false;

                        columns.forEach(col => {
                            let colText = col.textContent.toLowerCase();
                            if (colText.includes(query) || groupTitle.includes(query)) {
                                col.style.display = 'flex';
                                matchFound = true;
                            } else {
                                col.style.display = 'none';
                            }
                        });

                        let hasVisible = Array.from(columns).some(col => col.style.display !== 'none');
                        group.style.display = hasVisible ? 'block' : 'none';
                    });
                });

                container.appendChild(searchInput);

                let groups = [
    { name: '📋 Ticket Information', columns: ['S.No', 'Ticket No', 'Title', 'Company', 'Ticket ID', 'Due Date', 'Reported By', 'Type', 'Status', 'Priority', 'Impact', 'Description', 'Complaint Mode', 'Store Contact', 'Remarks'] },
    { name: '📋 Types', columns: ['Type ID', 'Type Title', 'Type Active'] },
    { name: '✅ Status', columns: ['Status ID', 'Status Title', 'Status Active'] },
    { name: '⭐ Priority', columns: ['Priority ID', 'Priority Title', 'Priority Active', 'Priority SLA'] },
    { name: '💥 Impacts', columns: ['Impact ID', 'Impact Title', 'Impact Active', 'Impact Created By', 'Impact Updated By'] },
    { name: '🏢 Business Unit', columns: ['Business Unit', 'BU Email', 'BU Phone', 'BU Address', 'BU City', 'BU State', 'BU Country', 'BU Alt Phone', 'BU Zipcode'] },
    { name: '👤 Account Information', columns: ['First Name', 'Last Name', 'Customer Email', 'Customer Phone', 'Customer Address', 'Customer City', 'Customer State', 'Customer Country'] },
    { name: '💰 Quote Information', columns: ['Quote Amount', 'Quote Approved Amount', 'Quote Actual Amount', 'Quote Description', 'Quote Approved By'] },
    { name: '📊 Dynamic Fields', columns: [] }
];

let staticTitles = ['S.No', 'Ticket No', 'Title', 'Company', 'Ticket ID', 'Due Date', 'Business Unit', 'Reported By', 'Type', 'Status', 'Priority', 'Impact', 'Description', 'Complaint Mode', 'Store Contact', 'Remarks', 'BU Email', 'BU Phone', 'BU Address', 'BU City', 'BU State', 'BU Country', 'BU Alt Phone', 'BU Zipcode', 'First Name', 'Last Name', 'Customer Email', 'Customer Phone', 'Customer Address', 'Customer City', 'Customer State', 'Customer Country', 'Quote Amount', 'Quote Approved Amount', 'Quote Actual Amount', 'Quote Description', 'Quote Approved By', 'Type ID', 'Type Title', 'Type Active', 'Status ID', 'Status Title', 'Status Active', 'Priority ID', 'Priority Title', 'Priority Active', 'Priority SLA', 'Impact ID', 'Impact Title', 'Impact Active', 'Impact Created By', 'Impact Updated By'];

                let dynamicColumns = columns.filter(col => !staticTitles.includes(col.title));
                groups[8].columns = dynamicColumns.map(col => col.title);

                groups.forEach(group => {
                    if (group.columns.length === 0) return;

                    let groupDiv = document.createElement('div');
                    groupDiv.className = 'group-container';
                    groupDiv.style.marginBottom = '10px';
                    groupDiv.style.border = '1px solid #dee2e6';
                    groupDiv.style.borderRadius = '4px';
                    groupDiv.style.overflow = 'hidden';

                    let header = document.createElement('div');
                    header.className = 'group-title';
                    header.style.padding = '8px 12px';
                    header.style.backgroundColor = '#f8f9fa';
                    header.style.cursor = 'pointer';
                    header.style.fontWeight = 'bold';
                    header.style.display = 'flex';
                    header.style.justifyContent = 'space-between';
                    header.style.alignItems = 'center';
                    header.style.fontSize = '13px';
                    header.innerHTML = `${group.name} <span style="font-size:12px;color:#6c757d;">(${group.columns.length})</span>`;

                    let columnsContainer = document.createElement('div');
                    columnsContainer.className = 'columns-container';
                    columnsContainer.style.padding = '5px 10px';
                    columnsContainer.style.display = 'none';
                    columnsContainer.style.maxHeight = '200px';
                    columnsContainer.style.overflowY = 'auto';

                    group.columns.forEach(colTitle => {
                        let colIndex = columns.findIndex(c => c.title === colTitle);
                        if (colIndex === -1) return;

                        let colItem = document.createElement('div');
                        colItem.className = 'column-item';
                        colItem.style.display = 'flex';
                        colItem.style.alignItems = 'center';
                        colItem.style.padding = '3px 0';
                        colItem.style.fontSize = '12px';

                        let checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.checked = dt.column(colIndex).visible();
                        checkbox.style.marginRight = '8px';

                        checkbox.addEventListener('change', function() {
                            dt.column(colIndex).visible(this.checked);
                            saveColumnVisibilityState();
                        });

                        let label = document.createElement('label');
                        label.textContent = colTitle;
                        label.style.cursor = 'pointer';
                        label.style.margin = '0';
                        label.style.fontSize = '12px';

                        colItem.appendChild(checkbox);
                        colItem.appendChild(label);
                        columnsContainer.appendChild(colItem);
                    });

                    header.addEventListener('click', function() {
                        let isVisible = columnsContainer.style.display !== 'none';
                        columnsContainer.style.display = isVisible ? 'none' : 'block';
                        this.style.backgroundColor = isVisible ? '#f8f9fa' : '#e9ecef';
                    });

                    groupDiv.appendChild(header);
                    groupDiv.appendChild(columnsContainer);
                    container.appendChild(groupDiv);
                });

                let actionDiv = document.createElement('div');
                actionDiv.style.marginTop = '10px';
                actionDiv.style.display = 'flex';
                actionDiv.style.gap = '10px';
                actionDiv.style.justifyContent = 'space-between';

                let selectAllBtn = document.createElement('button');
                selectAllBtn.textContent = '✓ Select All';
                selectAllBtn.style.fontSize = '11px';
                selectAllBtn.style.padding = '4px 10px';
                selectAllBtn.style.border = 'none';
                selectAllBtn.style.borderRadius = '3px';
                selectAllBtn.style.cursor = 'pointer';
                selectAllBtn.style.backgroundColor = '#0d6efd';
                selectAllBtn.style.color = 'white';

                selectAllBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dt.columns().visible(true);
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
                    saveColumnVisibilityState();
                });

                let deselectAllBtn = document.createElement('button');
                deselectAllBtn.textContent = '✕ Deselect All';
                deselectAllBtn.style.fontSize = '11px';
                deselectAllBtn.style.padding = '4px 10px';
                deselectAllBtn.style.border = 'none';
                deselectAllBtn.style.borderRadius = '3px';
                deselectAllBtn.style.cursor = 'pointer';
                deselectAllBtn.style.backgroundColor = '#6c757d';
                deselectAllBtn.style.color = 'white';

                deselectAllBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dt.columns().visible(false);
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    saveColumnVisibilityState();
                });

                actionDiv.appendChild(selectAllBtn);
                actionDiv.appendChild(deselectAllBtn);
                container.appendChild(actionDiv);

                let dropdown = document.createElement('div');
                dropdown.className = 'dt-button-collection';
                dropdown.style.position = 'fixed';
                dropdown.style.backgroundColor = 'white';
                dropdown.style.border = '1px solid #ddd';
                dropdown.style.borderRadius = '4px';
                dropdown.style.boxShadow = '0 2px 10px rgba(0,0,0,0.15)';
                dropdown.style.zIndex = '10000';
                dropdown.style.padding = '5px';
                dropdown.style.minWidth = '280px';
                dropdown.style.maxHeight = '500px';
                dropdown.style.overflow = 'hidden';

                let headerText = document.createElement('div');
                headerText.style.padding = '5px 10px';
                headerText.style.fontWeight = 'bold';
                headerText.style.borderBottom = '1px solid #dee2e6';
                headerText.style.fontSize = '14px';
                headerText.textContent = '📊 Column Visibility';
                dropdown.appendChild(headerText);
                dropdown.appendChild(container);

                let btnElement = button.node || e.target;
                if (!btnElement) btnElement = e.currentTarget || e.target;

                if (btnElement) {
                    let btnRect = btnElement.getBoundingClientRect();
                    dropdown.style.top = (btnRect.bottom + 5) + 'px';
                    dropdown.style.left = (btnRect.left) + 'px';
                } else {
                    dropdown.style.top = '50%';
                    dropdown.style.left = '50%';
                    dropdown.style.transform = 'translate(-50%, -50%)';
                }

                document.body.appendChild(dropdown);

                let closeDropdown = function(e) {
                    if (!dropdown.contains(e.target) && e.target !== btnElement && e.target !== button.node) {
                        dropdown.remove();
                        document.removeEventListener('click', closeDropdown);
                        restoreColumnVisibilityState();
                    }
                };
                setTimeout(() => {
                    document.addEventListener('click', closeDropdown);
                }, 100);
            };

            table = $('#myTable').DataTable({
                dom: 'Bfrtip',
                scrollX: true,
                columns: columns.map((col) => ({
                    ...col,
                    visible: false  // ALL columns hidden by default
                })),
                buttons: [
                    {
                        text: 'Column Visibility',
                        action: columnVisibilityAction
                    },
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                initComplete: function() {
                    setTimeout(function() {
                        columnVisibilityState.initialized = true;
                        // Do NOT show any columns by default
                        // User must manually select from Column Visibility
                        saveColumnVisibilityState();
                    }, 100);
                }
            });

            // Update table with data
            update_table(data, table);
    console.log("Distinct values:", distinctValues);

    $('#showSubTotalCheckbox').on('change', function() {
        showSubTotal = $(this).is(':checked');

        // Redraw table to apply changes
        if (table) {
            table.draw();
        }

        console.log('Sub Total visibility:', showSubTotal);  // Optional: for debugging
    });
});
});

    $(document).on('change', '#chart_div select', function() {
        let chart = $(this).val();
        chart_val = $(this).val();

        if (chart == 0) {
            $('#x_field').removeClass("d-none");
            $('#y_field').addClass("d-none");
            if (YField) {
                YField.destroy()
            }
        } else if (chart == 1 || chart == 2) {
            $('#x_field').removeClass("d-none");
            $('#y_field').removeClass("d-none");
        }
    });

    $(document).on('change', '#chart_select_graph', function() {
        let chart = $(this).val();
        chart_val = $(this).val();

        if (chart == 0) {
            $('#x_field_graph').removeClass("d-none");
            $('#y_field_graph').addClass("d-none");
            if (YField) {
                YField.destroy()
            }
        } else if (chart == 1 || chart == 2) {
            $('#x_field_graph').removeClass("d-none");
            $('#y_field_graph').removeClass("d-none");
        }
    });

    $(document).on('change', '#filter_fields select', function() {
        let fieldLabel = $(this).closest('.filter-field-container').find('label').text().trim();
        let field = fieldLabel.toLowerCase().replace(/ /g, "_");

        let selectedOptions = $(this).val() || [];
        selectedValues[field] = selectedOptions;

        console.log("Filter changed - Field:", field, "Selected values:", selectedOptions);
        console.log("Current selectedValues:", selectedValues);

        applyFilters();
    });

    $('#view_list').on('change', 'input[type = "radio"]', function() {
        let v_id = $(this).attr('id');
        var settings = {
            "url": `{{ config('app.api_url') }}retrieve_view/${v_id}`,
            "method": "GET",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": "application/json"
        };

        $.ajax({
            ...settings,
            statusCode: {
                200: function(response) {
                    let data = typeof response === "string" ? JSON.parse(response) : response;
                    let tickets = JSON.parse(data[0]["data"]);
                    console.log("Response:", tickets);
                    draw_table(tickets, table);
                    $('#ticket_view_modal').modal('hide');

                    Swal.fire('Success!', 'View Retrieved Successfully', 'success');
                },
            },
            success: function(data) {},
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr);
                Swal.fire('Server Error!', 'Filter Not Created', 'error');
            }
        });
    });

    $(document).on('change', '#table_filter', function() {
        let chart_val = $(this).find(':selected').val();
        let field_name = $(this).find(':selected').attr('name');
        if (chart_val == "all") {
            update_table(data, table);
        } else {
            let ft_data = table_filter(chart_val, field_name);
            update_table(ft_data, table);
        }
    });

    $('#group_button').on('click', function() {
    const selectedFields = [];
    let groups_con = $('#group_fields input[type="checkbox"]:checked');
    if (groups_con.length > 0) {
        groups_con.each(function() {
            // ✅ Use .val() to get the field name
            const fieldValue = $(this).val();
            selectedFields.push(fieldValue);
        });
        groups.push({
            ["group_" + group_no]: selectedFields
        });
        display_groups();
        group_no += 1;
    }
});

    $(document).on('click', '#modal_btn', function() {
        seprate_array(fil_group_obj);
    });

    $('#groups_overlay').on('change', 'input[type="radio"]', function() {
    let div = this.closest('div');
    let group = div.querySelector('label');
    let labelText = group ? group.textContent : null;
    console.log(labelText);
    order_table(labelText);
    edit_chart_xy(labelText); 
});

    $('#save_view_btn').on('click', function() {
        $('#ticket_filter_modal').modal("show");
    });

    $('#find_view_btn').on('click', function() {
        $('#ticket_view_modal').modal("show");
    });

    function order_table(group_no) {
    if (!table) {
        update_table(data);
        return;
    }
    
    groups.forEach(group => {
        if (Object.keys(group).includes(group_no)) {
            let group_obj = group[group_no];
            let col = group_obj.map(elem => {
                if (col_index[elem] !== undefined) {
                    return col_index[elem];
                }
                return null;
            }).filter(item => item !== null);
            
            console.log("col", col);
            
            // ✅ Agar col empty hai toh return
            if (col.length === 0) {
                console.warn("No valid columns found for group:", group_no);
                return;
            }
            
            // ✅ Fast grouping - table rebuild se bachein
            grouping_table(col);
        }
    });
}

    function table_filter(value, field) {
        value = parseInt(value);
        let result = data.filter(item => item[field] == value);
        return result;
    }

    function save_filters() {
        let v_id = (Math.random() * 1000);
        let v_name = document.getElementById("filter_name").value;
        filtered_data_filter.forEach((value) => {
            delete value["number"];
            value["view_id"] = v_id;
            value["view_name"] = v_name;
        });
        var settings = {
            "url": "{{ config('app.api_url') }}save_view/{{Auth::user()->id}}",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": "application/json",
            "data": JSON.stringify(filtered_data_filter)
        };
        console.log("Object before sending ", filtered_data_filter);

        $.ajax({
            ...settings,
            statusCode: {
                200: function(response) {
                    console.log(response);
                    $('#ticket_filter_modal').modal('hide');
                    Swal.fire('Success!', 'View Saved Successfully', 'success');
                },
            },
            success: function(data) {},
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr);
                Swal.fire('Server Error!', 'Filter Not Created', 'error');
            }
        });
    }

    function grouping_table(ind_arr) {
        console.log("ind_arr", ind_arr);

        saveColumnVisibilityState();

        let tableData = table.rows().data().toArray();
        table.destroy();
        $('#myTable').empty();

        let newColumns = [];
        ind_arr.forEach(colIndex => {
            newColumns.push(columns[colIndex]);
        });
        columns.forEach((col, index) => {
            if (!ind_arr.includes(index)) {
                newColumns.push(col);
            }
        });

        const columnVisibilityAction = function(e, dt, button, config) {
            saveColumnVisibilityState();

            let container = document.createElement('div');
            container.style.padding = '10px';
            container.style.minWidth = '250px';
            container.style.maxHeight = '400px';
            container.style.overflowY = 'auto';

            let searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search columns...';
            searchInput.style.width = '100%';
            searchInput.style.marginBottom = '10px';
            searchInput.style.padding = '5px';
            searchInput.style.boxSizing = 'border-box';

            searchInput.addEventListener('keyup', function() {
                let query = this.value.toLowerCase();
                let groups = container.querySelectorAll('.group-container');
                groups.forEach(group => {
                    let groupTitle = group.querySelector('.group-title').textContent.toLowerCase();
                    let columns = group.querySelectorAll('.column-item');
                    let matchFound = false;

                    columns.forEach(col => {
                        let colText = col.textContent.toLowerCase();
                        if (colText.includes(query) || groupTitle.includes(query)) {
                            col.style.display = 'flex';
                            matchFound = true;
                        } else {
                            col.style.display = 'none';
                        }
                    });

                    let hasVisible = Array.from(columns).some(col => col.style.display !== 'none');
                    group.style.display = hasVisible ? 'block' : 'none';
                });
            });

            container.appendChild(searchInput);

            let groups = [
    { name: '📋 Ticket Information', columns: ['S.No', 'Ticket No', 'Title', 'Company', 'Ticket ID', 'Due Date', 'Reported By', 'Type', 'Status', 'Priority', 'Impact', 'Description', 'Complaint Mode', 'Store Contact', 'Remarks'] },
    { name: '📋 Types', columns: ['Type ID', 'Type Title', 'Type Active'] },
    { name: '✅ Status', columns: ['Status ID', 'Status Title', 'Status Active'] },
    { name: '⭐ Priority', columns: ['Priority ID', 'Priority Title', 'Priority Active', 'Priority SLA'] },
    { name: '💥 Impacts', columns: ['Impact ID', 'Impact Title', 'Impact Active', 'Impact Created By', 'Impact Updated By'] },
    { name: '🏢 Business Unit', columns: ['Business Unit', 'BU Email', 'BU Phone', 'BU Address', 'BU City', 'BU State', 'BU Country', 'BU Alt Phone', 'BU Zipcode'] },
    { name: '👤 Account Information', columns: ['First Name', 'Last Name', 'Customer Email', 'Customer Phone', 'Customer Address', 'Customer City', 'Customer State', 'Customer Country'] },
    { name: '💰 Quote Information', columns: ['Quote Amount', 'Quote Approved Amount', 'Quote Actual Amount', 'Quote Description', 'Quote Approved By'] },
    { name: '📊 Dynamic Fields', columns: [] }
];

let staticTitles = ['S.No', 'Ticket No', 'Title', 'Company', 'Ticket ID', 'Due Date', 'Business Unit', 'Reported By', 'Type', 'Status', 'Priority', 'Impact', 'Description', 'Complaint Mode', 'Store Contact', 'Remarks', 'BU Email', 'BU Phone', 'BU Address', 'BU City', 'BU State', 'BU Country', 'BU Alt Phone', 'BU Zipcode', 'First Name', 'Last Name', 'Customer Email', 'Customer Phone', 'Customer Address', 'Customer City', 'Customer State', 'Customer Country', 'Quote Amount', 'Quote Approved Amount', 'Quote Actual Amount', 'Quote Description', 'Quote Approved By', 'Type ID', 'Type Title', 'Type Active', 'Status ID', 'Status Title', 'Status Active', 'Priority ID', 'Priority Title', 'Priority Active', 'Priority SLA', 'Impact ID', 'Impact Title', 'Impact Active', 'Impact Created By', 'Impact Updated By'];

            let dynamicColumns = columns.filter(col => !staticTitles.includes(col.title));
            groups[8].columns = dynamicColumns.map(col => col.title);

            groups.forEach(group => {
                if (group.columns.length === 0) return;

                let groupDiv = document.createElement('div');
                groupDiv.className = 'group-container';
                groupDiv.style.marginBottom = '10px';
                groupDiv.style.border = '1px solid #dee2e6';
                groupDiv.style.borderRadius = '4px';
                groupDiv.style.overflow = 'hidden';

                let header = document.createElement('div');
                header.className = 'group-title';
                header.style.padding = '8px 12px';
                header.style.backgroundColor = '#f8f9fa';
                header.style.cursor = 'pointer';
                header.style.fontWeight = 'bold';
                header.style.display = 'flex';
                header.style.justifyContent = 'space-between';
                header.style.alignItems = 'center';
                header.style.fontSize = '13px';
                header.innerHTML = `${group.name} <span style="font-size:12px;color:#6c757d;">(${group.columns.length})</span>`;

                let columnsContainer = document.createElement('div');
                columnsContainer.className = 'columns-container';
                columnsContainer.style.padding = '5px 10px';
                columnsContainer.style.display = 'none';
                columnsContainer.style.maxHeight = '200px';
                columnsContainer.style.overflowY = 'auto';

                group.columns.forEach(colTitle => {
                    let colIndex = columns.findIndex(c => c.title === colTitle);
                    if (colIndex === -1) return;

                    let colItem = document.createElement('div');
                    colItem.className = 'column-item';
                    colItem.style.display = 'flex';
                    colItem.style.alignItems = 'center';
                    colItem.style.padding = '3px 0';
                    colItem.style.fontSize = '12px';

                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.checked = dt.column(colIndex).visible();
                    checkbox.style.marginRight = '8px';

                    checkbox.addEventListener('change', function() {
                        dt.column(colIndex).visible(this.checked);
                        saveColumnVisibilityState();
                    });

                    let label = document.createElement('label');
                    label.textContent = colTitle;
                    label.style.cursor = 'pointer';
                    label.style.margin = '0';
                    label.style.fontSize = '12px';

                    colItem.appendChild(checkbox);
                    colItem.appendChild(label);
                    columnsContainer.appendChild(colItem);
                });

                header.addEventListener('click', function() {
                    let isVisible = columnsContainer.style.display !== 'none';
                    columnsContainer.style.display = isVisible ? 'none' : 'block';
                    this.style.backgroundColor = isVisible ? '#f8f9fa' : '#e9ecef';
                });

                groupDiv.appendChild(header);
                groupDiv.appendChild(columnsContainer);
                container.appendChild(groupDiv);
            });

            let actionDiv = document.createElement('div');
            actionDiv.style.marginTop = '10px';
            actionDiv.style.display = 'flex';
            actionDiv.style.gap = '10px';
            actionDiv.style.justifyContent = 'space-between';

            let selectAllBtn = document.createElement('button');
            selectAllBtn.textContent = '✓ Select All';
            selectAllBtn.style.fontSize = '11px';
            selectAllBtn.style.padding = '4px 10px';
            selectAllBtn.style.border = 'none';
            selectAllBtn.style.borderRadius = '3px';
            selectAllBtn.style.cursor = 'pointer';
            selectAllBtn.style.backgroundColor = '#0d6efd';
            selectAllBtn.style.color = 'white';

            selectAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dt.columns().visible(true);
                container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
                saveColumnVisibilityState();
            });

            let deselectAllBtn = document.createElement('button');
            deselectAllBtn.textContent = '✕ Deselect All';
            deselectAllBtn.style.fontSize = '11px';
            deselectAllBtn.style.padding = '4px 10px';
            deselectAllBtn.style.border = 'none';
            deselectAllBtn.style.borderRadius = '3px';
            deselectAllBtn.style.cursor = 'pointer';
            deselectAllBtn.style.backgroundColor = '#6c757d';
            deselectAllBtn.style.color = 'white';

            deselectAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dt.columns().visible(false);
                container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                saveColumnVisibilityState();
            });

            actionDiv.appendChild(selectAllBtn);
            actionDiv.appendChild(deselectAllBtn);
            container.appendChild(actionDiv);

            let dropdown = document.createElement('div');
            dropdown.className = 'dt-button-collection';
            dropdown.style.position = 'fixed';
            dropdown.style.backgroundColor = 'white';
            dropdown.style.border = '1px solid #ddd';
            dropdown.style.borderRadius = '4px';
            dropdown.style.boxShadow = '0 2px 10px rgba(0,0,0,0.15)';
            dropdown.style.zIndex = '10000';
            dropdown.style.padding = '5px';
            dropdown.style.minWidth = '280px';
            dropdown.style.maxHeight = '500px';
            dropdown.style.overflow = 'hidden';

            let headerText = document.createElement('div');
            headerText.style.padding = '5px 10px';
            headerText.style.fontWeight = 'bold';
            headerText.style.borderBottom = '1px solid #dee2e6';
            headerText.style.fontSize = '14px';
            headerText.textContent = '📊 Column Visibility';
            dropdown.appendChild(headerText);
            dropdown.appendChild(container);

            let btnElement = button.node || e.target;
            if (!btnElement) btnElement = e.currentTarget || e.target;

            if (btnElement) {
                let btnRect = btnElement.getBoundingClientRect();
                dropdown.style.top = (btnRect.bottom + 5) + 'px';
                dropdown.style.left = (btnRect.left) + 'px';
            } else {
                dropdown.style.top = '50%';
                dropdown.style.left = '50%';
                dropdown.style.transform = 'translate(-50%, -50%)';
            }

            document.body.appendChild(dropdown);

            let closeDropdown = function(e) {
                if (!dropdown.contains(e.target) && e.target !== btnElement && e.target !== button.node) {
                    dropdown.remove();
                    document.removeEventListener('click', closeDropdown);
                    restoreColumnVisibilityState();
                }
            };
            setTimeout(() => {
                document.addEventListener('click', closeDropdown);
            }, 100);
        };

        table = $('#myTable').DataTable({
            dom: 'Bfrtip',
            scrollX: true,
            colReorder: true,
            paging: true,
            pageLength: 10,
            info: true,
            buttons: [
                {
                    text: 'Column Visibility',
                    action: columnVisibilityAction
                },
                'copy', 'csv', 'print',
                {
                    text: 'Excel',
                    className: 'btn btn-success',
                    action: function(e, dt, node, config) {
                        exportGroupedData('excel');
                    }
                },
                {
                    text: 'PDF',
                    className: 'btn btn-danger',
                    action: function(e, dt, node, config) {
                        exportGroupedData('pdf');
                    }
                }
            ],
            data: tableData,
            columns: newColumns,
            rowGroup: {
    dataSrc: function(row) {
        return ind_arr.map(i => row[i]).join('||');
    },
    startRender: function(rows, group) {
        // ✅ ALWAYS HIDE "No Group" row - return false to prevent default behavior
        return false;
    },
    endRender: function(rows, group) {
        // ✅ Hide Sub Total row when hidden
        if (!showSubTotal) {
            return false;
        }

        let totalRows = rows.count();
        let quoteTotalSum = 0;
        let quoteTotalCount = 0;

        rows.data().each(function(rowData) {
            if (rowData[32] !== undefined && rowData[32] !== null && !isNaN(parseFloat(rowData[32]))) {
                quoteTotalSum += parseFloat(rowData[32]);
                quoteTotalCount++;
            }
        });

        let quoteAverage = quoteTotalCount > 0 ? (quoteTotalSum / quoteTotalCount).toFixed(2) : (0).toFixed(2);

        return $('<tr/>')
            .attr('style', 'background-color: #d1ecf1 !important; font-weight: bold;')
            .append(
                '<td colspan="' + (newColumns.length) +
                '" style="background-color: #d1ecf1 !important; color: #0c5460 !important; padding: 8px !important; text-align: left; border-top: 2px solid #bee5eb;">' +
                '🔹 Sub Total: ' + totalRows + '<br>' +
                '<small>Quote Sum: ' + quoteTotalSum.toFixed(2) + '</small><br>' +
                '<small>Quote Average: ' + quoteAverage + '</small>' +
                '</td>'
            );
    }
},
            createdRow: function(row, data, dataIndex) {
                if (dataIndex > 0 && tableData.length > 0) {
                    let prevData = tableData[dataIndex - 1];
                    let currKey = ind_arr.map(i => data[i]).join('||');
                    let prevKey = ind_arr.map(i => prevData[i]).join('||');

                    if (currKey === prevKey) {
                        for (let k = 0; k < ind_arr.length; k++) {
                            let td = $(row).find('td').eq(k);
                            td.html('');
                            td.css({
                                'border-top': 'none',
                                'background-color': 'transparent'
                            });
                        }
                    } else {
                        for (let k = 0; k < ind_arr.length; k++) {
                            let td = $(row).find('td').eq(k);
                            if (td.text().trim() === '') {
                                td.text(data[ind_arr[k]]);
                            }
                            td.css({
                                'border-top': '',
                                'background-color': ''
                            });
                        }
                    }
                }
            },
            drawCallback: function(settings) {
    $('.grand-total-row').remove();

    if (!showSubTotal) {
        return;
    }

    let grandQuoteSum = 0;
    let grandQuoteCount = 0;
    let currentPageCount = table.rows({ page: 'current' }).count();

    table.rows({ page: 'current' }).data().each(function(rowData) {
        if (rowData[32] !== undefined && rowData[32] !== null && !isNaN(parseFloat(rowData[32]))) {
            grandQuoteSum += parseFloat(rowData[32]);
            grandQuoteCount++;
        }
    });

    let grandQuoteAverage = grandQuoteCount > 0 ? (grandQuoteSum / grandQuoteCount).toFixed(2) : (0).toFixed(2);

    let grandTotalRow = $('<tr class="grand-total-row">')
        .attr('style', 'background-color: #f8d7da !important; font-weight: bold;')
        .append(
            '<td colspan="' + (newColumns.length) +
            '" style="background-color: #f8d7da !important; color: #721c24 !important; padding: 10px !important; text-align: left; border-top: 2px solid #f5c6cb;">' +
            'Grand Total: ' + currentPageCount + '<br>' +
            '<small>Quote Sum: ' + grandQuoteSum.toFixed(2) + '</small><br>' +
            '<small>Quote Average: ' + grandQuoteAverage + '</small>' +
            '</td>'
        );

    $('#myTable tbody').append(grandTotalRow);
},  // ← ✅ ADD THIS COMMA
initComplete: function() {
    setTimeout(function() {
        restoreColumnVisibilityState();
    }, 100);
}
        });
    }

    function exportGroupedData(type = 'excel') {
        try {
            let allData = table.rows({ search: 'applied' }).data().toArray();

            if (allData.length === 0) {
                console.warn('No data available to export.');
                return;
            }

            const currentColumns = table.settings().init().columns;
            const headers = currentColumns.map(col => col.title);
            const dataColumnCount = headers.length;

            let dataSrc = table.rowGroup().dataSrc();
            let groupColumns = [];
            let groupKeyFn = null;

            if (typeof dataSrc === 'function') {
                groupKeyFn = dataSrc;
            } else {
                if (!Array.isArray(dataSrc)) groupColumns = [dataSrc];
                else groupColumns = dataSrc;
                groupKeyFn = (row) => groupColumns.map(col => row[col]).join('||');
            }

            let grouped = {};
            allData.forEach(row => {
                const groupKey = groupKeyFn(row);
                if (!grouped[groupKey]) grouped[groupKey] = [];
                grouped[groupKey].push(row);
            });

            if (type === 'excel') {
                const XLSX = window.XLSX;
                let workbook = XLSX.utils.book_new();
                let worksheet_data = [];

                worksheet_data.push(headers);
                let totalTickets = 0;

                for (const [groupKey, rows] of Object.entries(grouped)) {
                    let previousRow = null;
                    let isFirstRowInGroup = true;

                    rows.forEach(row => {
                        const rowData = [];
                        currentColumns.forEach((col, colIndex) => {
                            const originalIndex = typeof col.data === 'number' ? col.data : currentColumns.indexOf(col);
                            let value = row[originalIndex] ?? '';

                            if (isFirstRowInGroup === false && groupColumns.includes(originalIndex)) {
                                if (previousRow && previousRow[originalIndex] === row[originalIndex]) {
                                    value = '';
                                }
                            }

                            rowData.push(value);
                        });

                        worksheet_data.push(rowData);
                        previousRow = row;
                        isFirstRowInGroup = false;
                    });

                    const subTotalRow = Array(dataColumnCount).fill('');
                    subTotalRow[0] = `🔹 Sub Total: ${rows.length}`;

                    let groupQuoteSum = 0, groupQuoteCount = 0;
                    rows.forEach(r => {
                        if (r[32] && !isNaN(parseFloat(r[32]))) {
                            groupQuoteSum += parseFloat(r[32]);
                            groupQuoteCount++;
                        }
                    });
                    const avg = groupQuoteCount > 0 ? (groupQuoteSum / groupQuoteCount).toFixed(2) : 0;
                    subTotalRow[0] += ` | Quote Sum: ${groupQuoteSum.toFixed(2)} | Quote Avg: ${avg}`;

                    worksheet_data.push(subTotalRow);
                    worksheet_data.push(Array(dataColumnCount).fill(''));
                    totalTickets += rows.length;
                }

                let grandQuoteSum = 0, grandQuoteCount = 0;
                allData.forEach(r => {
                    if (r[32] && !isNaN(parseFloat(r[32]))) {
                        grandQuoteSum += parseFloat(r[32]);
                        grandQuoteCount++;
                    }
                });
                const grandAvg = grandQuoteCount > 0 ? (grandQuoteSum / grandQuoteCount).toFixed(2) : 0;

                const grandRow = Array(dataColumnCount).fill('');
                grandRow[0] = `🔸 Grand Total: ${totalTickets} | Quote Sum: ${grandQuoteSum.toFixed(2)} | Quote Avg: ${grandAvg}`;
                worksheet_data.push(grandRow);

                const ws = XLSX.utils.aoa_to_sheet(worksheet_data);
                XLSX.utils.book_append_sheet(workbook, ws, "Grouped Report");
                XLSX.writeFile(workbook, "Grouped_Report.xlsx");
                return;
            }

            if (type === 'pdf') {
                const pdfMake = window.pdfMake;
                let docDefinition = {
                    pageOrientation: 'landscape',
                    pageSize: 'A3',
                    content: [],
                    styles: {
                        tableHeader: { bold: true, fillColor: '#f2f2f2', fontSize: 9 },
                        normal: { fontSize: 8 },
                        subTotal: { bold: true, fillColor: '#d1ecf1', color: '#0c5460', fontSize: 9 },
                        grandTotal: { bold: true, fillColor: '#f8d7da', color: '#721c24', fontSize: 10 }
                    }
                };

                let finalTableBody = [];
                finalTableBody.push(headers.map(h => ({ text: h, style: 'tableHeader', border: [true, true, true, true] })));

                let totalTickets = 0, grandQuoteSum = 0, grandQuoteCount = 0;

                for (const [groupKey, rows] of Object.entries(grouped)) {
                    let previousRow = null;
                    let isFirstRowInGroup = true;
                    let groupQuoteSum = 0, groupQuoteCount = 0;

                    rows.forEach(row => {
                        const rowData = [];
                        currentColumns.forEach((col, colIndex) => {
                            const originalIndex = typeof col.data === 'number' ? col.data : currentColumns.indexOf(col);
                            let value = row[originalIndex] ?? '';

                            if (isFirstRowInGroup === false && groupColumns.includes(originalIndex)) {
                                if (previousRow && previousRow[originalIndex] === row[originalIndex]) {
                                    value = '';
                                }
                            }

                            rowData.push({
                                text: value.toString(),
                                style: 'normal',
                                border: [true, true, true, true]
                            });
                        });

                        finalTableBody.push(rowData);

                        if (row[32] && !isNaN(parseFloat(row[32]))) {
                            groupQuoteSum += parseFloat(row[32]);
                            groupQuoteCount++;
                            grandQuoteSum += parseFloat(row[32]);
                            grandQuoteCount++;
                        }

                        previousRow = row;
                        isFirstRowInGroup = false;
                    });

                    const avg = groupQuoteCount > 0 ? (groupQuoteSum / groupQuoteCount).toFixed(2) : 0;
                    const subTotalText = `🔹 Sub Total: ${rows.length}\nQuote Sum: ${groupQuoteSum.toFixed(2)}\nQuote Avg: ${avg}`;
                    const subRow = [{ text: subTotalText, style: 'subTotal', colSpan: dataColumnCount, border: [true, true, true, true] }];
                    for (let i = 1; i < dataColumnCount; i++) subRow.push({});
                    finalTableBody.push(subRow);

                    finalTableBody.push(Array(dataColumnCount).fill({ text: '', border: [true, true, true, true] }));
                    totalTickets += rows.length;
                }

                const grandAvg = grandQuoteCount > 0 ? (grandQuoteSum / grandQuoteCount).toFixed(2) : 0;
                const grandText = `🔸 Grand Total: ${totalTickets}\nQuote Sum: ${grandQuoteSum.toFixed(2)}\nQuote Avg: ${grandAvg}`;
                const grandRow = [{ text: grandText, style: 'grandTotal', colSpan: dataColumnCount, border: [true, true, true, true] }];
                for (let i = 1; i < dataColumnCount; i++) grandRow.push({});
                finalTableBody.push(grandRow);

                docDefinition.content.push({
                    table: {
                        headerRows: 1,
                        widths: Array(dataColumnCount).fill('auto'),
                        body: finalTableBody
                    }
                });

                pdfMake.createPdf(docDefinition).download("Grouped_Report.pdf");
            }

        } catch (err) {
            console.error('Export failed:', err);
            Swal.fire('Export Error!', 'There was an error exporting the data. Please try again.', 'error');
        }
    }

    function display_groups() {
    const group_overlay = document.getElementById('groups_overlay');
    group_overlay.innerHTML = "";
    
    if (groups.length === 0) {
        group_overlay.innerHTML = '<p style="color: #6c757d; text-align: center; padding: 20px;">No groups created yet.</p>';
        $('#group_button').show();
        return;
    }
    
    let num = 1;
    // ✅ Fast loop - sirf DOM elements create karein
    groups.forEach(function(group) {
        let div = document.createElement('div');
        div.id = `group_container_${num}`;
        div.className = "d-flex justify-content-between align-items-center";
        div.style.marginBottom = "5px";
        div.style.padding = "5px";
        div.style.border = "1px solid #dee2e6";
        div.style.borderRadius = "4px";
        div.style.backgroundColor = "#f8f9fa";
        
        Object.entries(group).forEach(([groupName, values]) => {
            let div_child = document.createElement('div');
            div_child.style.display = "flex";
            div_child.style.flexWrap = "wrap";
            div_child.style.alignItems = "center";
            div_child.style.gap = "3px";
            
            // Radio button and group name
            const radioInput = document.createElement('input');
            radioInput.setAttribute('type', 'radio');
            radioInput.setAttribute('name', 'group1');
            radioInput.setAttribute('id', `radio_${num}`);
            radioInput.setAttribute('value', 'option1');
            
            const label = document.createElement('label');
            label.style.marginLeft = "3px";
            label.style.fontWeight = "bold";
            label.setAttribute('for', `radio_${num}`);
            label.setAttribute('id', `radio_label_${num}`);
            label.textContent = `${groupName}`;
            div_child.appendChild(radioInput);
            div_child.appendChild(label);
            
            // Fields display with display names
            values.forEach(val => {
                let itemDiv = document.createElement('span');
                let displayName = getFieldDisplayName(val);
                itemDiv.textContent = displayName;
                itemDiv.style.margin = "2px";
                itemDiv.style.fontSize = "11px";
                itemDiv.style.backgroundColor = "#e9ecef";
                itemDiv.style.padding = "2px 6px";
                itemDiv.style.borderRadius = "3px";
                itemDiv.style.border = "1px solid #dee2e6";
                div_child.appendChild(itemDiv);
            });
            
            // Buttons container
            let div_child_2 = document.createElement('div');
            div_child_2.classList.add('d-flex', 'justify-content-center');
            div_child_2.innerHTML = `<div style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
                <div style="display: flex; justify-content: center; gap: 5px;">
                    <button type="button" class="btn btn-danger waves-effect waves-light" onclick="remove_group('group_container_${num}', '${groupName}')" style="height:30px; font-size: 10px; padding: 2px 8px;">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="edit_chart_xy('${groupName}')" style="height:30px; font-size: 10px; padding: 2px 8px;">
                        <i class="ri-pencil-line"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-primary waves-effect waves-light make-graph-btn" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="append_xy('${groupName}')" style="height:35px; font-size: 10px; padding: 2px 8px;">
                    <i class="ri-bar-chart-line"></i> Make Graph
                </button>
            </div>`;
            
            div.appendChild(div_child);
            div.appendChild(div_child_2);
        });
        num++;
        group_overlay.appendChild(div);
    });
    
    $('#group_button').show();
}

function remove_group(elem, g_k) {
    // ✅ 1. Group ko UI se turant remove karein
    const div = $(`#${elem}`);
    if (div.length > 0) {
        div.remove();
    }
    
    // ✅ 2. groups array se group delete karein (Turant)
    groups = groups.filter(group => {
        const groupKey = Object.keys(group)[0];
        return groupKey !== g_k;
    });
    
    console.log("Remaining groups:", groups);
    
    // ✅ 3. Agar koi group nahi bacha toh UI update karein
    if (groups.length === 0) {
        const overlay = document.getElementById('groups_overlay');
        if (overlay) {
            overlay.innerHTML = '<p style="color: #6c757d; text-align: center; padding: 20px;">No groups created yet.</p>';
        }
        $('#group_button').show();
    } else {
        // ✅ Groups ko dobara display karein (UI refresh) - Yeh fast hai kyunki sirf UI render ho raha hai
        display_groups();
        $('#group_button').show();
    }
    
    // ✅ 4. Success message show karein (Fast)
    Swal.fire({
        title: 'Deleted!',
        text: 'Group removed successfully.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

    function date_field_filter(field) {
        const filter_cont = document.getElementById('filter_fields');
        let div = document.createElement('div');
        let id = `${field}Range`;
        let min = new Date(eval(`${field}_min`));
        let max = new Date(eval(`${field}_max`));
        div.className = "filter-field-container col-3";
        div.setAttribute("data-field", field);

        div.innerHTML = `
            <label for="${field}Select" style="font-size:13px; margin-top: 4px">
                ${field.charAt(0).toUpperCase() + field.slice(1).toLowerCase().replace(/_/g, " ")}
            </label>
            <input type="text" class="form-control flatpickr-input" id="${id}" readonly="readonly">
        `;
        filter_cont.appendChild(div);
        $(`#${id}`).flatpickr({
            mode: "range",
            dateFormat: "YYYY-MM-DD HH:MM",
            minDate: min,
            maxDate: max,
            onClose: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const fromDate = selectedDates[0].toISOString().split('T')[0];
                    const toDate = selectedDates[1].toISOString().split('T')[0];

                    selectedValues[field] = [fromDate, toDate];
                    update_record_box(field);
                    filter_date(field, fromDate, toDate);
                }
            }
        });
    }

    function add_field_filter(field) {
        const filter_cont = document.getElementById('filter_fields');
        let div = document.createElement('div');
        div.className = "filter-field-container col-3";
        div.setAttribute("data-field", field);
        div.innerHTML = `
            <label for="${field}Select" style="font-size:13px; margin-top: 4px">
                ${field.charAt(0).toUpperCase() + field.slice(1).toLowerCase().replace(/_/g, " ")}
            </label>
            <select id="${field}Select" name="${field}Select" class="form-select" multiple></select>
        `;
        filter_cont.appendChild(div);
        append_choices(field);
    }

    function remove_field_filter(field) {
        const filter_cont = document.getElementById('filter_fields');
        const fieldElement = filter_cont.querySelector(`[data-field="${field}"]`);
        if (fieldElement) {
            filter_cont.removeChild(fieldElement);
        }
        const div = document.querySelector(`.${field}Container`);
        if (div) {
            const cont = document.getElementById('group_fields');
            cont.removeChild(div);
            delete selectedValues[field];
            console.log("After removal - selectedValues:", selectedValues);
        }
    }

    function update_record_box(ref) {
    const group_field = document.getElementById('group_fields');
    let div = document.querySelector(`.${ref}Container`);

    if (!div) {
        div = document.createElement('div');
        div.className = `${ref}Container`;
        group_field.appendChild(div);
    }

    let displayName = getFieldDisplayName(ref);
    // ✅ ADD value attribute with ref
    let content = `<input type="checkbox" style="margin-right: 3px" value="${ref}"><label>${displayName}</label><br>`;
    div.innerHTML = content + `<hr style="margin: 5px 0px">`;
    $('#group_button').show();
}

    function append_choices(field) {
        let choicesField = new Choices(`#${field}Select`, {
            removeItemButton: true,
        });

        choicesField.clearChoices();

        let formattedChoices = distinctValues[field].map(item => ({
            value: item,
            label: item
        }));

        choicesField.setChoices(formattedChoices, 'value', 'label', false);
    }

    function edit_chart_xy(group_no) {
    // ✅ Pehle se existing choices destroy karein
    if (XField) {
        try { XField.destroy(); } catch(e) {}
        XField = null;
    }
    if (YField) {
        try { YField.destroy(); } catch(e) {}
        YField = null;
    }

    // ✅ Group find karein
    fil_group_obj = groups.filter((group) => {
        return Object.keys(group)[0] === group_no;
    });

    if (!fil_group_obj || fil_group_obj.length === 0) {
        console.error("Group not found:", group_no);
        Swal.fire('Error!', 'Group not found. Please try again.', 'error');
        return;
    }

    let allFields = fil_group_obj[0][group_no];

    if (!allFields || allFields.length === 0) {
        console.error("No fields found in group:", group_no);
        Swal.fire('Error!', 'No fields found in this group.', 'error');
        return;
    }

    // ✅ Fast - Sirf choices create karein, koi heavy operation nahi
    XField = new Choices(`#XXSelect`, {
        removeItemButton: true,
        searchEnabled: true,
        itemSelectText: '',
    });
    XField.clearChoices();

    YField = new Choices(`#YYSelect`, {
        removeItemButton: true,
        searchEnabled: true,
        itemSelectText: '',
    });
    YField.clearChoices();

    // ✅ X-axis choices
    let xChoices = allFields.map(item => ({
        value: item,
        label: getFieldDisplayName(item)
    }));
    XField.setChoices(xChoices, 'value', 'label', false);

    // ✅ Y-axis choices
    let yChoices = allFields.map(item => ({
        value: item,
        label: getFieldDisplayName(item)
    }));
    YField.setChoices(yChoices, 'value', 'label', false);

    // ✅ Modal ko turant show karein
    $('#edit_chart_modal').modal('show');
}

    function draw_pie_chart(field) {
    let count = {};

    // ✅ Sirf wohi values jo data mein mojood hain
    let uniqueValues = [...new Set(data.filter(item => item[field] !== null && item[field] !== undefined && item[field] !== '').map(item => item[field]))];

    uniqueValues.forEach((value) => {
        let field_results = data.filter((item) => {
            return item[field] == value;
        });
        count[value] = field_results.length;
    });

    console.log("📊 Pie counts:", count);

    // ✅ Check if data exists
    let totalCount = Object.values(count).reduce((sum, val) => sum + val, 0);
    if (totalCount === 0) {
        Swal.fire('No Data!', 'No data available for the selected field.', 'warning');
        return;
    }

    // ✅ Cleanup old charts
    if (line_chart) {
        line_chart.destroy();
        line_chart = null;
    }
    if (pie_chart) {
        pie_chart.destroy();
        pie_chart = null;
    }
    if (bar_chart) {
        bar_chart.destroy();
        bar_chart = null;
    }

    var options = {
        series: Object.values(count),
        chart: {
            width: 450,
            type: 'pie',
        },
        labels: Object.keys(count),
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 400
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    pie_chart = new ApexCharts(document.querySelector("#myPieChart"), options);
    pie_chart.render();
    $("#myPieChart").removeClass("d-none");
}

    function get_field() {
        if (typeof(XField.getValue(true)) == typeof(YField.getValue(true))) {
            let val_y = YField.getValue(true);
            seprate_array(val_y);
        } else {
            let val = XField.getValue(true);
            draw_pie_chart(val);
        }
    }

    function append_xy(group_no) {
    // ✅ Pehle se existing choices destroy karein
    if (XField) {
        try { XField.destroy(); } catch(e) {}
        XField = null;
    }
    if (YField) {
        try { YField.destroy(); } catch(e) {}
        YField = null;
    }

    // ✅ Group find karein
    fil_group_obj = groups.filter((group) => {
        return Object.keys(group)[0] === group_no;
    });

    if (!fil_group_obj || fil_group_obj.length === 0) {
        console.error("Group not found:", group_no);
        Swal.fire('Error!', 'Group not found. Please try again.', 'error');
        return;
    }

    let allFields = fil_group_obj[0][group_no];

    if (!allFields || allFields.length === 0) {
        console.error("No fields found in group:", group_no);
        Swal.fire('Error!', 'No fields found in this group.', 'error');
        return;
    }

    // ✅ Fast - Sirf choices create karein
    XField = new Choices(`#XSelect`, {
        removeItemButton: true,
        searchEnabled: true,
        itemSelectText: '',
    });
    XField.clearChoices();

    YField = new Choices(`#YSelect`, {
        removeItemButton: true,
        searchEnabled: true,
        itemSelectText: '',
    });
    YField.clearChoices();

    // ✅ X-axis choices
    let xChoices = allFields.map(item => ({
        value: item,
        label: getFieldDisplayName(item)
    }));
    XField.setChoices(xChoices, 'value', 'label', false);

    // ✅ Y-axis choices
    let yChoices = allFields.map(item => ({
        value: item,
        label: getFieldDisplayName(item)
    }));
    YField.setChoices(yChoices, 'value', 'label', false);

    // ✅ Modal ko turant show karein
    $('#edit_chart_modal').modal('show');
}

    function applyFilters() {
        console.log("Applying filters with selectedValues:", selectedValues);

        let filteredData = data;

        Object.keys(selectedValues).forEach(field => {
            if (selectedValues[field] && selectedValues[field].length > 0) {
                console.log(`Filtering by ${field}:`, selectedValues[field]);

                filteredData = filteredData.filter(item => {
                    if (date_fields.includes(field) && Array.isArray(selectedValues[field])) {
                        const [fromDate, toDate] = selectedValues[field];
                        const itemDate = item[field];
                        return itemDate >= fromDate && itemDate <= toDate;
                    } else {
                        return selectedValues[field].includes(item[field]);
                    }
                });

                console.log(`After filtering by ${field}, records:`, filteredData.length);
            }
        });

        filtered_data_filter = filteredData;
        console.log("Final filtered data:", filtered_data_filter);
        update_table(filtered_data_filter, table);
    }

    function filter_date(field, from, to) {
        filtered_data_filter = data.filter(function(tick) {
            if (tick[field] >= from && tick[field] <= to) {
                return true;
            }
            return false;
        });
        update_table(filtered_data_filter, table);
    }

    function filterDataByGroups(data_l, groups) {
        const filteredDataByGroups = [];
        groups.forEach(group => {
            const [groupName, groupValues] = Object.entries(group)[0];

            const filteredData = data_l.filter(ticket => {
                return groupValues.some(groupValue =>
                    Object.values(ticket).includes(groupValue)
                );
            });

            update_table(filteredData, table);
        });
    }

    function update_table(tdata, table_t) {
    table_t.clear().draw();
    $.each(tdata, function(index, item) {
        if (!item) {
            item = {};
        }

        let dynamicValues = [];

        distinctValues.bu_field_name.forEach(field => {
            const fieldValue = item[field] || '';
            dynamicValues.push(fieldValue);
        });

        table_t.row.add([
            index + 1,
            '000' + (item.company_id || '') + '-000' + (item.business_unit_id || '') + '-' + (item.ticket_id || item.id || ''),
            item.title || '',
            item.company_name || '',
            item.ticket_id || item.id || '',
            item.due_date || '',
            item.bu_name || '',
            item.reported_by_name || '',
            item.type_title || '',
            item.status_title || '',
            item.priority_title || '',
            item.impact_title || '',
            item.description || '',
            item.mode_of_complaint || '',
            item.store_contact || '',
            item.vendor_r || '',
            item.bu_email || '',
            item.bu_phone || '',
            item.bu_address || '',
            item.bu_city || '',
            item.bu_state || '',
            item.bu_country || '',
            item.bu_alternate_phone || '',
            item.bu_zipcode || '',
            item.fname || '',
            item.lname || '',
            item.customer_email || '',
            item.customer_phone || '',
            item.customer_address || '',
            item.customer_city || '',
            item.customer_state || '',
            item.customer_country || '',
            item.quote_amount || '',
            item.quote_approved_amount || '',
            item.quote_actual_amount || '',
            item.quote_description || '',
            item.quote_approved_by || '',

            // ===== NEW FIELDS =====
            // Types Table
            item.type_id || '',
            item.type_title || '',
            item.type_active || '',

            // Status Table
            item.status_id || '',
            item.status_title || '',
            item.status_active || '',

            // Priority Table
            item.priority_id || '',
            item.priority_title || '',
            item.priority_active || '',
            item.priority_sla || '',
            item.impact_id || '',
            item.impact_title || '',
            item.impact_active || '',
            item.impact_created_by || '',
            item.impact_updated_by || '',

            ...dynamicValues
        ]).draw(false);
    });
}

    function draw_table(tdata, table_t) {
        table_t.clear().draw();
        $.each(tdata, function(index, data) {
            table_t.row.add([
                index + 1,
                '000' + data.company_id + '-000' + data.business_unit_id + '-' + data.id,
                data.title,
                data.company_name,
                data.id,
                data.due_date,
                data.bu_name,
                data.reported_by_name,
                data.type_title,
                data.status_title,
                data.priority_title,
                data.impact_title,
                data.description,
                data.mode_of_complaint,
                data.store_contact,
                data.vendor_r,
                data.Import,
                data.F1,
                data.Produced,
            ]).draw(false);
        });
    }

    function update_filter_box() {
        const filter_field = document.getElementById('filter_box');
        const tr = filter_field.querySelector('tr') || document.createElement('tr');
        tr.innerHTML = "";
        selected_values.forEach(field => {
            let td = document.createElement('td');
            td.innerHTML = `<td>${field.charAt(0).toUpperCase() + field.slice(1).toLowerCase().replace(/_/g, " ")},</td>`;
            tr.appendChild(td);
        });
        filter_field.appendChild(tr);
    }

    function seprate_array(group_obj) {
    if (!XField || !YField) {
        console.error("❌ XField or YField is not initialized.");
        return;
    }

    // Get selected X and Y axis values
    let xFieldValue = XField.getValue(true);
    let yFieldValue = YField.getValue(true);

    if (!xFieldValue || !yFieldValue) {
        console.error("❌ Please select both X and Y fields.");
        Swal.fire('Error!', 'Please select both X-Axis and Y-Axis fields.', 'error');
        return;
    }

    console.log(`📊 X-Axis: ${xFieldValue}, Y-Axis: ${yFieldValue}`);

    // ✅ MODIFICATION: Check if Y-axis is numeric
    if (isNumericField(yFieldValue)) {
        // ✅ NUMERIC-BASED GRAPH
        console.log("📈 Numeric field detected. Generating VALUE-BASED graph.");

        // Group data by X-axis values
        let x_field_obj = filterBySelectedValues(data, xFieldValue);
        let categories = Object.keys(x_field_obj);

        // Calculate SUM for each category
        let sumData = {};
        categories.forEach(category => {
            let filteredData = x_field_obj[category];
            let sum = filteredData.reduce((total, item) => {
                let val = parseFloat(item[yFieldValue]) || 0;
                return total + val;
            }, 0);
            // Only include categories with data > 0
            if (sum > 0) {
                sumData[category] = sum;
            }
        });

        console.log("📊 Sum data:", sumData);

        // Check if any data exists
        if (Object.keys(sumData).length === 0) {
            Swal.fire('No Data!', 'No numeric data available for the selected fields.', 'warning');
            return;
        }

        // Display numeric chart based on chart type
        if (chart_val === "1") {
            display_chart_numeric(Object.keys(sumData), sumData, yFieldValue);
        } else if (chart_val === "2") {
            display_chart_line_numeric(Object.keys(sumData), sumData, yFieldValue);
        } else {
            // Pie chart for numeric fields (shows distribution of values)
            draw_pie_chart_numeric(sumData, yFieldValue);
        }

    } else {
        // ✅ COUNT-BASED GRAPH
        console.log("📊 Non-numeric field detected. Generating COUNT-BASED graph.");

        // Existing count-based logic (UNCHANGED)
        let x_field_obj = filterBySelectedValues(data, xFieldValue);
        let y_field_obj = countByPriority(x_field_obj, yFieldValue);

        console.log("📈 Y counts:", y_field_obj);

        let array = {};
        let yUniqueValues = [...new Set(data.filter(item =>
            item[yFieldValue] !== null &&
            item[yFieldValue] !== undefined &&
            item[yFieldValue] !== ''
        ).map(item => item[yFieldValue]))];

        yUniqueValues.forEach((field) => {
            let arr = extractPriorityCounts(y_field_obj, field);
            array[field] = arr;
        });

        console.log("📊 X categories:", Object.keys(x_field_obj));
        console.log("📊 Y categories:", Object.keys(array));

        // Display count-based chart (EXISTING BEHAVIOR)
        if (chart_val === "1") {
            display_chart(Object.keys(x_field_obj), array, yFieldValue);
        } else if (chart_val === "2") {
            display_chart_line(Object.keys(x_field_obj), array, yFieldValue);
        } else {
            draw_pie_chart(yFieldValue);
        }
    }
}

function draw_pie_chart_numeric(sumData, y_field) {
    if (line_chart) {
        line_chart.destroy();
        line_chart = null;
    }
    if (pie_chart) {
        pie_chart.destroy();
        pie_chart = null;
    }
    if (bar_chart) {
        bar_chart.destroy();
        bar_chart = null;
    }

    let categories = Object.keys(sumData);
    let values = Object.values(sumData);

    let total = values.reduce((sum, val) => sum + val, 0);
    if (total === 0) {
        Swal.fire('No Data!', 'No data available for the selected field.', 'warning');
        return;
    }

    let fieldDisplayName = getFieldDisplayName(y_field);

    var options = {
        series: values,
        chart: {
            width: 450,
            type: 'pie',
        },
        labels: categories,
        title: {
            text: `${fieldDisplayName} Distribution`,
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    // ✅ REMOVED ₹ SYMBOL - Only numbers
                    return value.toFixed(2);
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 400
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    pie_chart = new ApexCharts(document.querySelector("#myPieChart"), options);
    pie_chart.render();
    $("#myPieChart").removeClass("d-none");
}

// ✅ STEP 3: NEW NUMERIC BAR CHART FUNCTION
function display_chart_numeric(x_cat, y_data, y_field) {
    let series_s = [];

    let fieldDisplayName = getFieldDisplayName(y_field);

    series_s.push({
        name: `Total ${fieldDisplayName}`,
        data: Object.values(y_data)
    });

    console.log("📊 Numeric Series:", series_s);

    if (line_chart) {
        line_chart.destroy();
        line_chart = null;
    }
    if (pie_chart) {
        pie_chart.destroy();
        pie_chart = null;
    }
    if (bar_chart) {
        bar_chart.destroy();
        bar_chart = null;
    }

    var options = {
        series: series_s,
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%',
                borderRadius: 4,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                // ✅ REMOVED ₹ SYMBOL - Only numbers
                return value.toFixed(2);
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                fontWeight: 'bold'
            }
        },
        xaxis: {
            categories: x_cat,
            title: {
                text: 'Categories',
                style: {
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            }
        },
        yaxis: {
            title: {
                text: `Total ${fieldDisplayName}`,
                style: {
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            },
            labels: {
                formatter: function(value) {
                    // ✅ REMOVED ₹ SYMBOL - Only numbers
                    return value.toFixed(2);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    // ✅ REMOVED ₹ SYMBOL - Only numbers
                    return value.toFixed(2);
                }
            }
        },
        title: {
            text: `${fieldDisplayName} by Category`,
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        },
        colors: ['#0d6efd']
    };

    line_chart = new ApexCharts(document.querySelector("#myPieChart"), options);
    line_chart.render();
    $("#myPieChart").removeClass("d-none");
}

// ✅ STEP 4: NEW NUMERIC LINE CHART FUNCTION
function display_chart_line_numeric(x_cat, y_data, y_field) {
    let series_s = [];

    let fieldDisplayName = getFieldDisplayName(y_field);

    series_s.push({
        name: `Total ${fieldDisplayName}`,
        data: Object.values(y_data)
    });

    console.log("📈 Numeric Line Series:", series_s);

    if (line_chart) {
        line_chart.destroy();
        line_chart = null;
    }
    if (pie_chart) {
        pie_chart.destroy();
        pie_chart = null;
    }
    if (bar_chart) {
        bar_chart.destroy();
        bar_chart = null;
    }

    var options = {
        series: series_s,
        chart: {
            height: 350,
            type: "line",
            toolbar: {
                show: true
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                // ✅ REMOVED ₹ SYMBOL - Only numbers
                return value.toFixed(2);
            },
            style: {
                fontSize: '12px',
                fontWeight: 'bold'
            }
        },
        stroke: {
            width: 3,
            curve: 'smooth'
        },
        markers: {
            size: 6
        },
        xaxis: {
            categories: x_cat,
            title: {
                text: 'Categories',
                style: {
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            }
        },
        yaxis: {
            title: {
                text: `Total ${fieldDisplayName}`,
                style: {
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            },
            labels: {
                formatter: function(value) {
                    // ✅ REMOVED ₹ SYMBOL - Only numbers
                    return value.toFixed(2);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    // ✅ REMOVED ₹ SYMBOL - Only numbers
                    return value.toFixed(2);
                }
            }
        },
        title: {
            text: `${fieldDisplayName} Trend by Category`,
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        },
        colors: ['#0d6efd']
    };

    bar_chart = new ApexCharts(document.querySelector("#myPieChart"), options);
    bar_chart.render();
    $("#myPieChart").removeClass("d-none");
}

    function filterBySelectedValues(data, key) {
    let filteredData = {};

    // ✅ Filtered data se unique values nikalna
    let uniqueValues = [...new Set(data.filter(item => item[key] !== null && item[key] !== undefined && item[key] !== '').map(item => item[key]))];

    // ✅ Sirf wohi values jo actual data mein mojood hain
    uniqueValues.forEach((value) => {
        filteredData[value] = data.filter((item) => item[key] === value);
    });

    console.log(`🔍 Filtered values for ${key}:`, uniqueValues);
    return filteredData;
}

    function countByPriority(filteredData, priorityKey) {
        let counts = {};

        for (let bu in filteredData) {
            counts[bu] = {
                total: filteredData[bu].length,
                priorityCounts: {}
            };

            distinctValues[priorityKey].forEach((priority) => {
                counts[bu].priorityCounts[priority] = 0;
            });

            filteredData[bu].forEach((item) => {
                let priority = item[priorityKey];
                if (priority in counts[bu].priorityCounts) {
                    counts[bu].priorityCounts[priority]++;
                }
            });
        }

        return counts;
    }

    function extractPriorityCounts(counts, priorityTitle) {
        let result = [];

        for (let bu in counts) {
            let buData = counts[bu];
            let priorityCount = buData.priorityCounts[priorityTitle] || 0;
            result.push(priorityCount);
        }

        return result;
    }

    function display_chart(x_cat, y_counts, y_field) {
    let series_s = [];

    let yUniqueValues = Object.keys(y_counts);

    if (yUniqueValues.length === 0) {
        Swal.fire('No Data!', 'No data available for the selected fields.', 'warning');
        return;
    }

    yUniqueValues.forEach(field => {
        let displayName = getFieldDisplayName(field);
        let obj = {
            name: displayName,
            data: y_counts[field] || []
        };
        series_s.push(obj);
    });

    console.log("📊 Series:", series_s);

    if (line_chart) {
        line_chart.destroy();
        line_chart = null;
    }
    if (pie_chart) {
        pie_chart.destroy();
        pie_chart = null;
    }
    if (bar_chart) {
        bar_chart.destroy();
        bar_chart = null;
    }

    let hasData = series_s.some(series => series.data && series.data.some(val => val > 0));
    if (!hasData) {
        Swal.fire('No Data!', 'No data available for the selected fields.', 'warning');
        return;
    }

    var options = {
        series: series_s,
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 10,
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'last',
                columnWidth: '40%',
                dataLabels: {
                    total: {
                        enabled: true,
                        style: {
                            fontSize: '13px',
                            fontWeight: 900
                        }
                    }
                }
            },
        },
        xaxis: {
            categories: x_cat,
        },
        legend: {
            position: 'right',
            offsetY: 40
        },
        fill: {
            opacity: 1
        }
    };

    line_chart = new ApexCharts(document.querySelector("#myPieChart"), options);
    line_chart.render();
    $("#myPieChart").removeClass("d-none");
}

function display_chart_line(x_cat, y_counts, y_field) {
    let series_s = [];
    let colors_s = ["#FF1654", "#247BA0", "#f58002", "#00E396", "#FEB019"];
    let y_axis = [];
    let i = 0;

    let yUniqueValues = Object.keys(y_counts);

    if (yUniqueValues.length === 0) {
        Swal.fire('No Data!', 'No data available for the selected fields.', 'warning');
        return;
    }

    yUniqueValues.forEach(field => {
        let displayName = getFieldDisplayName(field);
        let obj = {
            name: displayName,
            data: y_counts[field] || []
        };
        series_s.push(obj);

        let axis_bool = i > 0 ? true : false;
        let clr = colors_s[i % colors_s.length];

        let ind_axis = {
            opposite: axis_bool,
            axisTicks: {
                show: true
            },
            axisBorder: {
                show: true,
                color: clr
            },
            labels: {
                style: {
                    colors: clr
                }
            },
            title: {
                text: displayName,
                style: {
                    color: clr
                }
            }
        };

        y_axis.push(ind_axis);
        i++;
    });

    console.log("📈 Series:", series_s);
    console.log("📊 Y axes:", y_axis);

    if (line_chart) {
        line_chart.destroy();
        line_chart = null;
    }
    if (pie_chart) {
        pie_chart.destroy();
        pie_chart = null;
    }
    if (bar_chart) {
        bar_chart.destroy();
        bar_chart = null;
    }

    let hasData = series_s.some(series => series.data && series.data.some(val => val > 0));
    if (!hasData) {
        Swal.fire('No Data!', 'No data available for the selected fields.', 'warning');
        return;
    }

    var options = {
        series: series_s,
        chart: {
            height: 350,
            type: "line",
            stacked: false
        },
        dataLabels: {
            enabled: false
        },
        colors: colors_s.slice(0, yUniqueValues.length),
        stroke: {
            width: [4, 4]
        },
        markers: {
            size: [4, 5]
        },
        plotOptions: {
            bar: {
                columnWidth: "20%"
            }
        },
        xaxis: {
            categories: x_cat
        },
        yaxis: y_axis,
        tooltip: {
            shared: false,
            intersect: true,
            x: {
                show: false
            }
        },
        legend: {
            horizontalAlign: "left",
            offsetX: 40
        }
    };
    bar_chart = new ApexCharts(document.querySelector("#myPieChart"), options);
    bar_chart.render();
    $("#myPieChart").removeClass("d-none");
}

</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
