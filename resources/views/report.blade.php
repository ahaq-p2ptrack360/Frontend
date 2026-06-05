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

@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Reports @endslot
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
</style>


<div class="row">
    <div class="col-2">
        <div class="row">
            <div class="col-12" id="group_static_fields"
                style="margin-left: 0px; padding-left: 0px;">
            </div>
            <hr style="margin-top:3px">

            <div class="col-12" id="group_fields" style="height: 250px; overflow-y:auto;">

            </div>
            <button type="button" id="group_button" class="btn btn-dark btn-sm" style="margin-bottm: 3px; margin-left: 75px;
                           margin-bottom: 3px; width: 50%; display:none">Create
                Group</button>
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

                <button type="button" id="save_view_btn"
                    class="btn btn-soft-primary waves-effect waves-light"
                    style=" display: none; margin-top: 7px">
                    Save
                </button>

                <button type="button" id="find_view_btn"
                    class="btn btn-soft-primary waves-effect waves-light"
                    style=" margin-top: 7px; margin-left: 3px;">
                    Views
                </button>
            </div>


        </div>
        <hr>
        <div class="card">
            <!-- Button Section -->
            <div class="card-header">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" id="addNewBtn">
                        Add New
                    </button>
                </div>
                <div id="exportButtons"></div>
            </div>

            <div class="card-body">
                <table id="myTable"  class="table table-striped" style="width:100%">
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
                            <!-- placeholder for dynamic columns -->
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
<div class="modal fade" id="ticket_view_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                </h5>
                <h5 id="label">Views</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-12">
                        <ul style="list-style: none;" id="view_list">
                        </ul>

                    </div>

                    <div class="col-12 mt-3" style="text-align: right;">

                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>



<div class="modal fade" id="edit_chart_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div id="chart_div">

                        <label for="chart_select" style="font-size:13px; margin-top: 4px">
                            Chart Type
                        </label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="0">Pie</option>
                            <option value="1">Bar</option>
                            <option value="2">Line</option>
                        </select>
                    </div>

                    <div id="x_field" class=" d-none">

                        <label for="XSelect" style="font-size:13px; margin-top: 4px">
                            X-Axis
                        </label>
                        <select id="XXSelect" class="form-select form-select-lg mb-3"
                            aria-label=".form-select-lg example">
                        </select>
                    </div>
                    <div id="y_field" class=" d-none">
                        <label for="YSelect" style="font-size:13px; margin-top: 4px">
                            Y-Axis
                        </label>
                        <select id="YYSelect" class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_btn_2" class="btn btn-secondary"
                    data-bs-dismiss="modal" onclick="get_field()">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <!-- Add chart type selection here -->
                    <div id="chart_div_graph">

                        <label for="chart_select_graph" style="font-size:13px; margin-top: 4px">
                            Chart Type
                        </label>
                        <select class="form-select" id="chart_select_graph" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="0">Pie</option>
                            <option value="1">Bar</option>
                            <option value="2">Line</option>
                        </select>
                    </div>

                    <div id="x_field_graph" class=" d-none">

                        <label for="XSelect" style="font-size:13px; margin-top: 4px">
                            X-Axis
                        </label>
                        <select id="XSelect" class="form-select form-select-lg mb-3"
                            aria-label=".form-select-lg example">
                        </select>
                    </div>
                    <div id="y_field_graph" class=" d-none">
                        <label for="YSelect" style="font-size:13px; margin-top: 4px">
                            Y-Axis
                        </label>
                        <select id="YSelect" class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_btn" class="btn btn-secondary"
                    data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ticket_filter_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                </h5>
                <h5 id="label">Save View</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <label for="example-text-input" class="col-auto form-label">View
                                Name</label>

                            <input type="text" class="col-auto form-control" id="filter_name">
                        </div>
                    </div>

                    <div class="col-12 mt-3" style="text-align: right;">

                        <button type="button" class="btn btn-secondary waves-effect"
                            data-bs-dismiss="modal">Close</button>
                        <input class="btn btn-primary waves-effect waves-light" type="submit"
                            name="app_btn" id="view_btn" onclick="save_filters()" value="Save">
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js"
    integrity="sha512-o36qZrjup13zLM13tqxvZTaXMXs+5i4TL5UWaDCsmbp5qUcijtdCFuW9a/3qnHGfWzFHBAln8ODjf7AnUNebVg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/2.0.4/js/dataTables.colReorder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
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
    quote_approved_by: []
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
    "quote_approved_by": 36
    }
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
    quote_approved_by: []
    };

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
                    li.innerHTML =
                        `<input type="radio" name = "view_radio" id="${value["view_id"]}"> ${value["view_name"]}`;
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
            // console.log(response)
            const static_fields = document.getElementById('static_fields');
            const g_static_fields = document.getElementById('group_static_fields');

            let ul_list = document.createElement('ul')
            let items = ""

            created_at_max = response[0]["created_at"];
            created_at_min = response[0]["created_at"];
            completed_date_max = response[0]["completed_date"];
            completed_date_min = response[0]["completed_date"];
            due_date_max = response[0]["due_date"];
            due_date_min = response[0]["due_date"];

            // Initialize columns array WITH NEW BUSINESS UNIT COLUMNS
            columns = [
                { data: 0, title: "S.No" },
                { 
                  data: 1, 
                  title: "@if(session('userWithBU') && session('userWithBU')->ticket_represented){{ session('userWithBU')->ticket_represented }}@else Ticket @endif No"
                },
                { data: 2, title: "Title" },
                { data: 3, title: "Company" },
                { 
                  data: 4,
                  title: "@if(session('userWithBU') && session('userWithBU')->ticket_represented){{ session('userWithBU')->ticket_represented }}@else Ticket @endif ID" 
                },
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
    { data: 36, title: "Quote Approved By" }
            ];

            // Loop through the API result and extract distinct values
            response.forEach(item => {

                if (item.resolved_name && !distinctValues.resolved_name.includes(item
                        .resolved_name)) {
                    distinctValues.resolved_name.push(item.resolved_name);
                }
                if (item.company_name && !distinctValues.company_name.includes(item
                        .company_name)) {
                    distinctValues.company_name.push(item.company_name);
                }
                if (item.title && !distinctValues.title.includes(item.title)) {
                    distinctValues.title.push(item.title);
                }
                if (item.completed_date && !distinctValues.completed_date.includes(item
                        .completed_date)) {
                    distinctValues.completed_date.push(item.completed_date);
                }
                if (item.due_date && !distinctValues.due_date.includes(item.due_date)) {
                    distinctValues.due_date.push(item.due_date);
                }
                if (item.bu_name && !distinctValues.bu_name.includes(item.bu_name)) {
                    distinctValues.bu_name.push(item.bu_name);
                }
                if (item.reported_by_name && !distinctValues.reported_by_name.includes(item
                        .reported_by_name)) {
                    distinctValues.reported_by_name.push(item.reported_by_name);
                }
                if (item.type_title && !distinctValues.type_title.includes(item
                        .type_title)) {
                    distinctValues.type_title.push(item.type_title);
                }
                if (item.status_title && !distinctValues.status_title.includes(item
                        .status_title)) {
                    distinctValues.status_title.push(item.status_title);
                }
                if (item.priority_title && !distinctValues.priority_title.includes(item
                        .priority_title)) {
                    distinctValues.priority_title.push(item.priority_title);
                }
                if (item.impact_title && !distinctValues.impact_title.includes(item
                        .impact_title)) {
                    distinctValues.impact_title.push(item.impact_title);
                }
                if (item.customer && !distinctValues.customer.includes(item.customer)) {
                    distinctValues.customer.push(item.customer);
                }
                if (item.bu_field_name && !distinctValues.bu_field_name.includes(item
                        .bu_field_name)) {
                    distinctValues.bu_field_name.push(item.bu_field_name);
                }
                if (item.created_at && !distinctValues.created_at.includes(item
                        .created_at)) {
                    distinctValues.created_at.push(item.created_at);
                }

                // NEW BUSINESS UNIT FIELDS EXTRACTION
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

                if (item.created_at > created_at_max) {
                    created_at_max = item.created_at
                }
                if (item.created_at < created_at_min) {
                    created_at_min = item.created_at
                }
                if (item.completed_date > completed_date_max) {
                    completed_date_max = item.completed_date
                }
                if (item.completed_date < completed_date_min) {
                    completed_date_min = item.completed_date
                }
                if (item.due_date > due_date_max) {
                    due_date_max = item.due_date
                }
                if (item.due_date < due_date_min) {
                    due_date_min = item.due_date
                }
            });

            // generating fields
            const table_rec = document.querySelector('#table_records');
distinctValues.bu_field_name.forEach((field, index) => {
    const th = document.createElement('th');
    th.textContent = field;
    table_rec.appendChild(th);

    // Update dynamic columns to start from 32 (after customer fields)
    columns.push({ data: 37 + index, title: field });
});

          // Initialize DataTable with proper column configuration
table = $('#myTable').DataTable({
    dom: 'Bfrtip',
    scrollX: true,
    columns: columns.map(col => ({
        ...col,
        visible: false
    })),
    buttons: [{
            extend: 'colvis',
            text: 'Column Visibility',
            action: function(e, dt, button, config) {
                $.fn.dataTable.ext.buttons.collection.action.call(this,
                    e, dt, button, config);

                // ✅ Add search box inside Column Visibility dropdown
                if (!$('.colvis-search').length) {
                    var $searchInput = $('<input>', {
                        type: 'text',
                        class: 'colvis-search',
                        placeholder: 'Search columns...',
                        style: 'width: 100%; margin: 5px 0; padding: 5px; box-sizing: border-box;'
                    }).on('keyup', function() {
                        var query = $(this).val().toLowerCase();
                        $('.dt-button-collection div[role="menu"] button')
                            .each(function() {
                                var buttonText = $(this).text().toLowerCase();
                                var $button = $(this);

                                if (buttonText.includes(query)) {
                                    $button.show();
                                } else {
                                    $button.hide();
                                }
                            });
                    });

                    $searchInput.on('click', function(e) {
                        e.stopPropagation(); // Prevent menu from closing when typing
                    });

                    $('.dt-button-collection').prepend($searchInput);
                }
            }
        },
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});


            console.log("Distinct values:", distinctValues);

            // Update inputFields with NEW BUSINESS UNIT FIELDS
            const inputFields = [
                "company_name",
                "bu_name",
                "priority_title",
                "impact_title",
                "resolved_name",
                "reported_by_name",
                "title",
                "completed_date",
                "due_date",
                "type_title",
                "status_title",
                "bu_field_name",
                "field_type",
                "field_data",
                "created_at",
                "bu_email",
                "bu_phone",
                "bu_address",
                "bu_city",
                "bu_state",
                "bu_country",
                "bu_alternate_phone",
                "bu_zipcode",
                "fname",
    "lname",
    "created_byfname",
    "created_bylname",
    "customer_email",
    "customer_phone",
    "customer_address",
    "customer_city",
    "customer_state",
    "customer_country",
    "quote_amount",
    "quote_approved_amount",
    "quote_actual_amount",
    "quote_description",
    "quote_approved_by"

            ];

            let opt = `<select class="selectpicker"
                id="static_dropdown"
                multiple
                title="No Filter Selected"
                data-selected-text-format="count > 999"
                data-live-search="true">`;

            let g_opt = `<select class="selectpicker"
                id="g_static_dropdown"
                multiple
                title="No Group Selected"
                data-selected-text-format="count > 999"
                data-live-search="true">`;

            inputFields.forEach(field => {
                opt += `<option value="${field}">${field}</option>`;
                g_opt += `<option value="${field}">${field}</option>`;
            });

            opt += `</select>`;
            g_opt += `</select>`;

            static_fields.innerHTML = opt;
            g_static_fields.innerHTML = g_opt;

            $('#static_dropdown').selectpicker('render');
            $('#g_static_dropdown').selectpicker('render');

            update_table(data, table);
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

    // Add change event for chart type in Graph modal
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
        let v_id = $(this).attr('id')
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

                    Swal.fire(
                        'Success!',
                        'View Retrieved Successfully',
                        'success'
                    )
                },
            },
            success: function(data) {
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr)
                Swal.fire(
                    'Server Error!',
                    'Filter Not Created',
                    'error'
                )
            }
        });
    })

    $(document).on('change', '#table_filter', function() {
        let chart_val = $(this).find(':selected').val();
        let field_name = $(this).find(':selected').attr('name');
        if (chart_val == "all") {
            update_table(data, table);
        } else {
            let ft_data = table_filter(chart_val, field_name)
            update_table(ft_data, table);
        }
    });

    $('#group_button').on('click', function() {
        const selectedLabels = [];
        let groups_con = $('#group_fields input[type="checkbox"]:checked');
        if (groups_con.length > 0) {
            groups_con.each(function() {
                const labelText = $(this).next('label').text();
                selectedLabels.push(labelText);
            });
            groups.push({
                ["group_" + group_no]: selectedLabels
            })

            display_groups()
            group_no += 1
        }
    });

    $(document).on('click', '#modal_btn', function() {
        seprate_array(fil_group_obj);
    });

    $('#groups_overlay').on('change', 'input[type="radio"]', function() {
        let div = this.closest('div')
        let group = div.querySelector('label');
        let labelText = group ? group.textContent : null;
        console.log(labelText);
        order_table(labelText)
        append_xy(labelText)
    });

    $('#save_view_btn').on('click', function() {
        $('#ticket_filter_modal').modal("show");
    })

    $('#find_view_btn').on('click', function() {
        $('#ticket_view_modal').modal("show");
    })

    let selected_values = []

    $(document).on('change', '#static_dropdown', function() {
        const selectedFields = $(this).find('option:selected').map(function() {
            return $(this).val().replace(/ /g, "_");
        }).get();

        selectedFields.forEach(field => {
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
        });

        selected_values = selected_values.filter(field => {
            if (!selectedFields.includes(field)) {
                remove_field_filter(field);
                return false;
            }
            return true;
        });

        $('#save_view_btn').css('display', 'block');

        const button = $('#static_dropdown').parent().find('button.dropdown-toggle');
        if (selectedFields.length > 0) {
            button.attr('title', 'Filter Selected').find('.filter-option-inner-inner').text(
                'Filter Selected');
        } else {
            button.attr('title', 'No Filter Selected').find('.filter-option-inner-inner').text(
                'No Filter Selected');
        }
    });

    $(document).on('change', '#g_static_dropdown', function() {
        const selectedFields = $(this).find('option:selected').map(function() {
            return $(this).val().replace(/ /g, "_");
        }).get();

        selectedFields.forEach(field => {
            if (!selected_groups.includes(field)) {
                selected_groups.push(field);
                update_record_box(field);
            }
        });

        selected_groups = selected_groups.filter(field => {
            if (!selectedFields.includes(field)) {
                let id = `.${field}Container`;
                let elem = document.querySelector(id);
                if (elem) elem.remove();
                return false;
            }
            return true;
        });

        const button = $('#g_static_dropdown').parent().find('button.dropdown-toggle');
        if (selectedFields.length > 0) {
            button.attr('title', 'Group Selected').find('.filter-option-inner-inner').text(
                'Group Selected');
        } else {
            button.attr('title', 'No Group Selected').find('.filter-option-inner-inner').text(
                'No Group Selected');
        }
    });

    function order_table(group_no) {
        if (!table) update_table(data);

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
                grouping_table(col);
            }
        });
    }

    function table_filter(value, field) {
        value = parseInt(value)
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
        })
        var settings = {
            "url": "{{ config('app.api_url') }}save_view/{{Auth::user()->id}}",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": "application/json",
            "data": JSON.stringify(filtered_data_filter)
        };
        console.log("Object before sending ", filtered_data_filter)

        $.ajax({
            ...settings,
            statusCode: {
                200: function(response) {
                    console.log(response);
                    $('#ticket_filter_modal').modal('hide');
                    Swal.fire(
                        'Success!',
                        'View Saved Successfully',
                        'success'
                    )
                },
            },
            success: function(data) {
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr)
                Swal.fire(
                    'Server Error!',
                    'Filter Not Created',
                    'error'
                )
            }
        });
    }

    function grouping_table(ind_arr) {
    console.log("ind_arr", ind_arr);
    // backup of original table data (array of rows)
    let tableData = table.rows().data().toArray();

    // destroy old datatable and empty tbody
    table.destroy();
    $('#myTable').empty();

    // prepare new columns: selected group columns first, then the rest
    let newColumns = [];
    ind_arr.forEach(colIndex => {
        newColumns.push(columns[colIndex]);
    });
    columns.forEach((col, index) => {
        if (!ind_arr.includes(index)) {
            newColumns.push(col);
        }
    });

    // initialize DataTable again
    table = $('#myTable').DataTable({
        dom: 'Bfrtp',
        scrollX: true,
        colReorder: true,
        paging: true,
        pageLength: 10,
        info: true,
        buttons: [
            'colvis', 'copy', 'csv', 'print',
            {
                text: 'Excel',
                className: 'btn btn-success',
                action: function (e, dt, node, config) {
                    exportGroupedData('excel');
                }
            },
            {
                text: 'PDF',
                className: 'btn btn-danger',
                action: function (e, dt, node, config) {
                    exportGroupedData('pdf');
                }
            }
        ],
        data: tableData,
        columns: newColumns,

        /***** IMPORTANT: Use a single-level grouping key *****/
        rowGroup: {
            // Provide a function that returns a single combined key for grouping.
            // This prevents DataTables from creating multiple nested group levels.
            dataSrc: function (row) {
                // row is array of columns; create combined key using selected indices
                return ind_arr.map(i => row[i]).join('||'); // '||' as safe separator
            },
            startRender: null, // we don't need a header row for each group
            endRender: function (rows, group) {
                // rows = group rows; group = combined key string
                let totalRows = rows.count();
                let quoteTotalSum = 0;
                let quoteTotalCount = 0;

                // Calculate sum & average for the group's Quote column (index 32 in original data)
                rows.data().each(function (rowData) {
                    if (rowData[32] !== undefined && rowData[32] !== null && !isNaN(parseFloat(rowData[32]))) {
                        quoteTotalSum += parseFloat(rowData[32]);
                        quoteTotalCount++;
                    }
                });

                let quoteAverage = quoteTotalCount > 0 ? (quoteTotalSum / quoteTotalCount).toFixed(2) : (0).toFixed(2);

                // Return single Sub Total row for this group
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

        // createdRow: hide repeated group values in the first columns (clean look)
        createdRow: function (row, data, dataIndex) {
            // Compare this row's combined key with previous row's combined key.
            if (dataIndex > 0 && tableData.length > 0) {
                let prevData = tableData[dataIndex - 1];
                // Build keys (same logic as dataSrc)
                let currKey = ind_arr.map(i => data[i]).join('||');
                let prevKey = ind_arr.map(i => prevData[i]).join('||');

                if (currKey === prevKey) {
                    // Hide the displayed group columns (they are the first columns because we moved them to the front)
                    for (let k = 0; k < ind_arr.length; k++) {
                        let td = $(row).find('td').eq(k);
                        td.html(''); // remove repeated value
                        td.css({
                            'border-top': 'none',
                            'background-color': 'transparent'
                        });
                    }
                } else {
                    // Ensure they are visible (in case paginating or redraw)
                    for (let k = 0; k < ind_arr.length; k++) {
                        let td = $(row).find('td').eq(k);
                        // If cell is empty (rare), put the value back
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

        // drawCallback: append one Grand Total row per current page and compute sums for visible rows
        drawCallback: function (settings) {
            // Remove existing grand total row(s)
            $('.grand-total-row').remove();

            // Compute grand totals for current page
            let grandQuoteSum = 0;
            let grandQuoteCount = 0;
            let currentPageCount = table.rows({ page: 'current' }).count();

            table.rows({ page: 'current' }).data().each(function (rowData) {
                if (rowData[32] !== undefined && rowData[32] !== null && !isNaN(parseFloat(rowData[32]))) {
                    grandQuoteSum += parseFloat(rowData[32]);
                    grandQuoteCount++;
                }
            });

            let grandQuoteAverage = grandQuoteCount > 0 ? (grandQuoteSum / grandQuoteCount).toFixed(2) : (0).toFixed(2);

            // Append grand total row at bottom of tbody
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
        }
    });
}


function order_table(group_no) {
    if (!table) update_table(data);

    groups.forEach(group => {
        if (Object.keys(group).includes(group_no)) {
            let group_obj = group[group_no];

            let col = group_obj.map(elem => {
                if (col_index[elem] !== undefined) {
                    return col_index[elem];
                }
                return null;
            }).filter(item => item !== null);

            console.log("Group columns:", col);
            grouping_table(col);
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

        // ✅ Get the grouping definition (function or array)
        let dataSrc = table.rowGroup().dataSrc();
        let groupColumns = [];
        let groupKeyFn = null;

        if (typeof dataSrc === 'function') {
            // Table is grouped by combined key (function)
            groupKeyFn = dataSrc;
        } else {
            // Array-based grouping
            if (!Array.isArray(dataSrc)) groupColumns = [dataSrc];
            else groupColumns = dataSrc;
            groupKeyFn = (row) => groupColumns.map(col => row[col]).join('||');
        }

        // ✅ Build grouped data using same logic as frontend
        let grouped = {};
        allData.forEach(row => {
            const groupKey = groupKeyFn(row);
            if (!grouped[groupKey]) grouped[groupKey] = [];
            grouped[groupKey].push(row);
        });

        // =====================================================================================
        // ✅ EXCEL EXPORT
        // =====================================================================================
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

                        // hide repeated group values (like frontend)
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

                // ✅ Sub Total row
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
                worksheet_data.push(Array(dataColumnCount).fill('')); // empty spacer row
                totalTickets += rows.length;
            }

            // ✅ Grand Total
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

        // =====================================================================================
        // ✅ PDF EXPORT
        // =====================================================================================
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

                // ✅ Sub Total row
                const avg = groupQuoteCount > 0 ? (groupQuoteSum / groupQuoteCount).toFixed(2) : 0;
                const subTotalText = `🔹 Sub Total: ${rows.length}\nQuote Sum: ${groupQuoteSum.toFixed(2)}\nQuote Avg: ${avg}`;
                const subRow = [{ text: subTotalText, style: 'subTotal', colSpan: dataColumnCount, border: [true, true, true, true] }];
                for (let i = 1; i < dataColumnCount; i++) subRow.push({});
                finalTableBody.push(subRow);

                // spacer
                finalTableBody.push(Array(dataColumnCount).fill({ text: '', border: [true, true, true, true] }));
                totalTickets += rows.length;
            }

            // ✅ Grand Total
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
        Swal.fire(
            'Export Error!',
            'There was an error exporting the data. Please try again.',
            'error'
        );
    }
}



    function display_groups() {
        const group_overlay = document.getElementById('groups_overlay');
        group_overlay.innerHTML = "";
        let num = 1;
        groups.forEach(function(group) {
            let div = document.createElement('div');
            div.id = `group_container_${num}`;
            div.className = "d-flex justify-content-between"
            let hr = document.createElement('hr')
            console.log(groups)
            Object.entries(group).forEach(([groupName, values]) => {
                let div_child = document.createElement('div');
                let div_child_2 = document.createElement('div');
                div_child_2.classList.add('d-flex', 'justify-content-center');
              div_child_2.innerHTML = `<div style="display: flex; flex-direction: column; align-items: center; gap: 5px;">

<div style="display: flex; justify-content: center; gap: 5px;">
  <button type="button"
          class="btn btn-danger waves-effect waves-light"
          id="container_${num}"
          onclick="remove_group('group_container_${num}', '${groupName}')"
          style="height:30px; font-size: 10px;">
      <i class="ri-delete-bin-line"></i>
  </button>

  <button type="button"
          class="btn btn-success waves-effect waves-light"
          id="edit_chart_${num}"
          onclick="edit_chart_xy('${groupName}')"
          style="height:30px; font-size: 10px;">
      <i class="ri-pencil-line"></i>
  </button>
</div>

<button type="button"
        class="btn btn-primary waves-effect waves-light make-graph-btn"
        data-bs-toggle="modal"
        data-bs-target="#exampleModal"
        style="height:40px; font-size: 10px;">
    <i class="ri-bar-chart-line"></i> Make Graph
</button>

</div>

`
                const radioInput = document.createElement('input');
                radioInput.setAttribute('type', 'radio');
                radioInput.setAttribute('name', 'group1');
                radioInput.setAttribute('id', `radio_${num}`);
                radioInput.setAttribute('value', 'option1');

                const label = document.createElement('label');
                label.style.marginLeft = "3px";
                label.setAttribute('for', `radio_${num}`);
                label.setAttribute('id', `radio_label_${num}`);
                label.textContent = `${groupName}`;
                div_child.appendChild(radioInput);
                div_child.appendChild(label);

                values.forEach(val => {
                    let itemDiv = document.createElement('label');
                    itemDiv.textContent = val;
                    itemDiv.style.margin = "3px";
                    div_child.appendChild(itemDiv);
                    div_child.appendChild(hr);
                });
                div.appendChild(div_child)
                div.appendChild(div_child_2)
            });
            num++;
            group_overlay.appendChild(div);
        });
    }

    function remove_group(elem, g_k) {
        table.destroy();
        table = $('#myTable').DataTable({
            dom: 'Bfrtip',
            scrollX: true,
            colReorder: true,
            buttons: ['colvis', 'copy', 'csv', 'excel', 'pdf', 'print'],
            columns: columns
        });

        // Update table with original data
        update_table(data, table);

        const div = $(`#${elem}`);
        div.remove();
        groups = groups.filter(group => {
            const groupKey = Object.keys(group)[0];
            return groupKey !== g_k;
        });
        console.log(groups)
    }

    function date_field_filter(field) {
        const filter_cont = document.getElementById('filter_fields');
        let div = document.createElement('div');
        let id = `${field}Range`
        let min = new Date(eval(`${field}_min`));
        let max = new Date(eval(`${field}_max`));
        div.className = "filter-field-container col-3";
        div.setAttribute("data-field", field);

        div.innerHTML = `
        <label for="${field}Select" style="font-size:13px; margin-top: 4px">
            ${field.charAt(0).toUpperCase() + field.slice(1).toLowerCase().replace(/_/g, " ")}
        </label>
        <input type="text" class="form-control flatpickr-input" id="${id}"
                                            readonly="readonly">    `;
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
            cont.removeChild(div)
            delete selectedValues[field];
            console.log("After removal - selectedValues:", selectedValues)
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

        let content = `<input type="checkbox" style="margin-right: 3px"><label>${ref}</label><br>`;
        div.innerHTML = content + `<hr style = "margin: 5px 0px">`;
        $('#group_button').show()
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
        if (XField) {
            XField.destroy()
        }
        if (YField) {
            YField.destroy()
        }

        XField = new Choices(`#XXSelect`, {
            removeItemButton: true,
        });
        XField.clearChoices();

        YField = new Choices(`#YYSelect`, {
            removeItemButton: true,
        });
        YField.clearChoices();

        console.log(typeof(group_no))
        fil_group_obj = groups.filter((group) => {
            return Object.keys(group)[0] === group_no;
        });

        let formattedChoices = fil_group_obj[0][group_no].map(item => ({
            value: item,
            label: item
        }));

        XField.setChoices(formattedChoices, 'value', 'label', false);
        YField.setChoices(formattedChoices, 'value', 'label', false);
        $('#edit_chart_modal').modal('show')
    };

    function draw_pie_chart(field) {
        let labels = distinctValues[field];
        let count = {};

        distinctValues[field].forEach((value) => {
            let field_results = data.filter((item) => {
                return item[field] == value;
            })
            count[value] = field_results.length;
        })

        console.log("Pie x ccounts ", count);
        if (line_chart) {
            line_chart.destroy()
        } else if (pie_chart) {
            pie_chart.destroy();
        } else if (bar_chart) {
            bar_chart.destroy();
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
            let val = XField.getValue(true)
            let val_y = YField.getValue(true)
            seprate_array(val_y);
        } else {
            let val = XField.getValue(true)
            draw_pie_chart(val);
        }
    }

    function append_xy(group_no) {
        if (XField) {
            XField.destroy()
        }

        XField = new Choices(`#XSelect`, {
            removeItemButton: true,
        });
        XField.clearChoices();

        if (YField) {
            YField.destroy()
        }

        YField = new Choices(`#YSelect`, {
            removeItemButton: true,
        });
        YField.clearChoices();

        console.log(typeof(group_no))
        fil_group_obj = groups.filter((group) => {
            return Object.keys(group)[0] === group_no;
        });

        let formattedChoices = fil_group_obj[0][group_no].map(item => ({
            value: item,
            label: item
        }));

        XField.setChoices(formattedChoices, 'value', 'label', false);
        YField.setChoices(formattedChoices, 'value', 'label', false);
        // $('#exampleModal').modal("show")
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
                    }
                    else {
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

            update_table(filteredData, table)
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
        const filter_field = document.getElementById('filter_box')
        const tr = filter_field.querySelector('tr') || document.createElement('tr')
        tr.innerHTML = ""
        selected_values.forEach(field => {
            let td = document.createElement('td')
            td.innerHTML =
                `<td>${field.charAt(0).toUpperCase() + field.slice(1).toLowerCase().replace(/_/g, " ")},</td>`
            tr.appendChild(td)
        });
        filter_field.appendChild(tr)
    }

    function seprate_array(group_obj) {
        if (!XField || !YField) {
            console.error("XField or YField is not initialized.");
            return;
        }

        let x_field_obj = filterByDistinctValues(data, XField.getValue(true));
        let y_field_obj = countByPriority(x_field_obj, YField.getValue(true));
        console.log("y count  count", y_field_obj)
        let array = {};
        distinctValues[YField.getValue(true)].forEach((field) => {
            let arr = extractPriorityCounts(y_field_obj, field);
            array[field] = arr;
        });

        console.log("x response ", x_field_obj);
        let x_cat = Object.keys(x_field_obj);
        console.log("Counts for indi Y : ", array);
        console.log("X categories", Object.keys(x_field_obj));
        if (chart_val === "1") {
            display_chart(x_cat, array, YField.getValue(true));
        } else if (chart_val === "2") {
            display_chart_line(x_cat, array, YField.getValue(true));
        }
    }

    function filterByDistinctValues(data, key) {
        let filteredData = {};
        console.log("Key : ", key)
        let arr = distinctValues[key];
        console.log("Dist Values ", distinctValues);
        distinctValues[key].forEach((value) => {
            filteredData[value] = data.filter((item) => item[key] === value);
        });

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
        distinctValues[y_field].forEach(field => {
            let obj = {
                name: field,
                data: y_counts[field]
            };
            series_s.push(obj);
        })
        console.log("Series : ", series_s);
        if (line_chart) {
            line_chart.destroy()
        } else if (pie_chart) {
            pie_chart.destroy();
        } else if (bar_chart) {
            bar_chart.destroy();
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
        let colors_s = ["#FF1654", "#247BA0", "#f58002"];
        let y_axis = [];
        let i = 0;
        distinctValues[y_field].forEach(field => {
            let obj = {
                name: field,
                data: y_counts[field]
            };
            series_s.push(obj);
            let axis_bool = i > 0 ? true : false;
            let clr = colors_s[i];

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
                    text: field,
                    style: {
                        color: clr
                    }
                }
            }

            y_axis.push(ind_axis);
            i++;
        })
        console.log("Series : ", series_s);
        console.log("Y axes  : ", y_axis);

        if (line_chart) {
            line_chart.destroy()
        } else if (pie_chart) {
            pie_chart.destroy();
        } else if (bar_chart) {
            bar_chart.destroy();
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
            colors: colors_s,
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
