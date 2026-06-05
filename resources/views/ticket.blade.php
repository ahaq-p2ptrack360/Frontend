@extends('layouts.master')
@section('title') @lang('translation.datatables') @endsection
@section('css')
<!-- Datatable CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Additional CSS for export buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" />

@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title')
    {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} List
@endslot

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

    #offcanvasRight.offcanvas-end {
        width: 55% !important;
    }


    .dropzone {
        background: white;
        border-radius: 5px;
        border: 2px dashed rgb(0, 135, 247);
        border-image: none;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .custom-card {
        min-width: 180px;
    }

    .card-spacing {
        margin: 0 10px;
    }

    .row {
        margin-bottom: 10px;
    }

    @media (min-width: 768px) {
        .custom-col {
            flex: 0 0 auto;
            width: 20%;
        }
    }

    .col-md-3 {
        margin-left: 10px;
    }

    .offcanvasFilter1 {
        width: 22% !important;
    }

    .pink-card1 {
        background-color: rgba(249, 85, 76, 1);
    }

    .pink-card2 {
        background-color: rgba(90, 88, 235, 1);
    }

    .pink-card3 {
        background-color: rgba(45, 203, 115, 1);
    }

    .pink-card4 {
        background-color: rgba(23, 159, 170, 1);
    }


    .pink-card5 {
        background-color: rgba(233, 188, 24, 1);
    }

    .custom-card {
        border-radius: 10px;
        border: none;
        transition: all 0.3s ease;
    }

    .pink-card1 {
        background-color: white;
        border-bottom: 4px solid rgba(249, 85, 76, 1);
        outline: 1px solid rgba(249, 85, 76, 1);
    }

    .pink-card2 {
        background-color: white;
        border-bottom: 4px solid rgba(90, 88, 235, 1);
        outline: 1px solid rgba(90, 88, 235, 1);
    }

    .pink-card3 {
        background-color: white;
        border-bottom: 4px solid rgba(45, 203, 115, 1);
        outline: 1px solid rgba(45, 203, 115, 1);
    }

    .pink-card4 {
        background-color: white;
        border-bottom: 4px solid rgba(23, 159, 170, 1);
        outline: 1px solid rgba(23, 159, 170, 1);
    }

    .pink-card5 {
        background-color: white;
        border-bottom: 4px solid rgba(233, 188, 24, 1);
        outline: 1px solid rgba(233, 188, 24, 1);
    }

    .card1:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .card-spacing {
        margin-bottom: 20px;
    }

    .custom-col {
        padding: 0 10px;
    }

    h6 {
        color: #6c757d;
        font-weight: 600;
    }

    .text-primary {
        color: #0d6efd;
        font-weight: 700;
    }

    .fs-4 {
        font-size: 1.5rem;
        margin-top: 10px;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .custom-col {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 576px) {
        .custom-col {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    .btn-excel {
        background-color: #1d6f42;
        color: white;
        border: none;
    }

    .btn-excel:hover {
        background-color: #166534;
        color: white;
    }

    /* Date Range Picker */
    .selected-datepicker {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .selected-datepicker:hover {
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    }

    .selected-datepicker .form-control {
        border: none;
        background: rgba(255, 255, 255, 0.8);
        padding: 12px 15px;
        font-weight: 500;
    }

    .selected-datepicker .input-group-text {
        border: none;
        background: transparent;
        padding: 0 15px;
    }

    .btn-clear {
        background: transparent;
        border: none;
        color: #999;
        transition: all 0.2s;
        padding: 0 15px;
    }

    .btn-clear:hover {
        color: #ff4757;
        transform: scale(1.1);
    }

    .text-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 600;
    }

    .daterangepicker {
        border: none;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .daterangepicker td.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .daterangepicker .ranges li:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border-radius: 6px;
    }

    .daterangepicker .ranges li.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-export.dropdown-toggle::after {
        display: none !important;
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

    .dropdown-menu-export {
        position: absolute !important;
        top: 40% !important;
        left: 0;
        margin-top: 6px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        min-width: 220px;
        z-index: 1050;
        padding: 8px 0;
        border: none;
        overflow: hidden;
        transition: opacity 0.3s ease, transform 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
        visibility: hidden;
    }

    .dropdown-menu-export {
        opacity: 1;
        visibility: visible;
    }

    .dropdown-item-export {
        padding: 12px 20px;
        background: transparent;
        color: #333;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        margin: 0 8px;
        border-radius: 10px;
        cursor: pointer;
        user-select: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .dropdown-item-export:hover {
        background: linear-gradient(135deg, #e0e7ff, #f0f4ff);
        color: #4a6cf7;
        transform: translateX(4px);
        box-shadow: 0 3px 10px rgba(74, 108, 247, 0.1);
    }

    .dropdown-item-export i {
        margin-right: 12px;
        color: #4a6cf7;
        font-size: 14px;
        transition: color 0.3s ease;
    }

    .dropdown-item-export:hover i {
        color: #3b5bff;
    }

    #types-table {
        display: none;
        /* Initially hidden until data loads */
    }

    #table-loading {
        text-align: center;
        padding: 20px;
        color: #6c757d;
        font-size: 14px;
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
    /* Create Offcanvas Width */
#createOffcanvas {
    width: 50% !important;
    max-width: none;
}

#createOffcanvas.offcanvas-end {
    width: 50% !important;
}

/* Edit Offcanvas Width */
#editOffcanvas {
    width: 50% !important;
    max-width: none;
}

#editOffcanvas.offcanvas-end {
    width: 50% !important;
}
</style>

<div class="container mt-5">
    <div class="row mb-3 align-items-end">
        <div class="col-md-4 col-lg-3 mb-2 mb-md-0">
            <label for="selectedDateRange" class="form-label fw-500 text-gradient">Select Date</label>
            <div class="input-group selected-datepicker">
                <span class="input-group-text bg-transparent">
                    <i class="fas fa-calendar-alt text-primary"></i>
                </span>
                <input type="text" id="selectedDateRange" class="form-control shadow-sm"
                    placeholder="Select date range">
                <button class="btn btn-clear" type="button" id="clearDateRange">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="col-md-8 col-lg-9 d-flex justify-content-end gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary" type="button" id="statusDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Status
                </button>
                <ul class="dropdown-menu" style="cursor: pointer;" aria-labelledby="statusDropdown">
                    <li><a class="dropdown-item" onclick="status('all_tickets')">All {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</a></li>
                    <li><a class="dropdown-item" onclick="status('open_tickets')">Open {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</a></li>
                    <li><a class="dropdown-item" onclick="status('resolved_tickets')">Resolved {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</a></li>
                    <li><a class="dropdown-item" onclick="status('pending_tickets')">Pending {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</a></li>
                    <li><a class="dropdown-item" onclick="status('closed_tickets')">Closed {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</a></li>
                </ul>
            </div>


            <!-- Filter Button -->
            <button class="btn btn-outline-secondary d-flex align-items-center gap-1" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter1" aria-controls="offcanvasFilter1">
                <i class="fa-solid fa-filter"></i>
                <span>Filters</span>
            </button>
        </div>
    </div>
</div>

<!-- Offcanvas Filter Form -->
<div class="offcanvas offcanvas-end offcanvasFilter1" tabindex="-1" id="offcanvasFilter1"
    aria-labelledby="offcanvasFilterLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasFilterLabel">Filter {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="filterForm">


            <div class="mb-3">
                <label for="agent" class="form-label">Agent</label>
                <select class="form-select" data-choices name="agent" id="agent">
                    <option value="">Select Agent</option>
                    <option value="Created_By_Me">Created By Me</option>
                    <option value="assign_to_me">Asssined To Me</option>
                    <option value="assign_by_me">Assign By Me</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="Priority" class="form-label">Priority</label>

                <select class="form-control" id="Priorityfil" placeholder="This is a search placeholder">

                </select>
            </div>

            <!-- <div class="mb-3">
                <label for="channelName" class="form-label">Channel Name</label>
                <select class="form-select" data-choices name="channelName" id="channelName">
                    <option value="">Select Channel</option>
                    <option value="email">Email</option>
                    <option value="phone">Phone</option>
                    <option value="chat">Chat</option>
                </select>
            </div> -->

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="typefil" placeholder="This is a search placeholder">

                </select>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Impact</label>
                <select class="form-control" id="impactfil" placeholder="This is a search placeholder">

                </select>
            </div>



            <div class="d-flex justify-content-end gap-2">
                <button type="button" id="applyfilt" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </div>
</div>

<div class="container py-5">
    <!-- Ticket Status Cards -->
    <div class="row justify-content-center text-center">
        <div class="col-md-2 custom-col">
            <div class="card custom-card card-spacing shadow-sm p-3 card1 pink-card1">
                <h6>Total {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</h6>
                <p class="text-primary fs-4" id="total_tickets">0</p>
            </div>
        </div>
        <div class="col-md-2 custom-col">
            <div class="card custom-card card-spacing shadow-sm p-3 card1 pink-card2">
                <h6>Closed {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</h6>
                <p class="text-primary fs-4" id="closed_tickets">0</p>
            </div>
        </div>
        <div class="col-md-2 custom-col">
            <div class="card custom-card card-spacing shadow-sm p-3 card1 pink-card3">
                <h6>Open {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</h6>
                <p class="text-primary fs-4" id="open_tickets">0</p>
            </div>
        </div>
        <div class="col-md-2 custom-col">
            <div class="card custom-card card-spacing shadow-sm p-3 card1 pink-card4">
                <h6>Pending {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</h6>
                <p class="text-primary fs-4" id="pending_tickets">0</p>
            </div>
        </div>
        <div class="col-md-2 custom-col">
            <div class="card custom-card card-spacing shadow-sm p-3 card1 pink-card5">
                <h6>Resolved {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</h6>
                <p class="text-primary fs-4" id="completed_count">0</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <!-- Button Section -->
            <div class="card-header">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#createOffcanvas" aria-controls="createOffcanvas" id="addNewBtn">
                        Add New
                    </button>
                    <div id="customExportButtons" class="d-flex align-items-center"></div>
                </div>
            </div>

            <div class="card-body">
                <div style="overflow-x: auto; width: 100%;">

                    <table id="types-table" class="table wrap align-middle" style="min-width: 1600px;">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Ticket NO</th>
                                <th>Title</th>
                                <th>Issue Type</th>
                                <th>Reported On</th>
                                <th>Company Id</th>
                                <th>Company Name</th>
                                <th>Complete Date</th>
                                <th>Due Date</th>
                                <th>Store Content</th>
                                <th>Business Unit</th>
                                <th>Priority</th>
                                <th>Impact</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>View</th>
                                <th>Edit</th>
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
</div>

<!-- Create Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="createOffcanvas" aria-labelledby="createOffcanvasLabel">
    <div class="offcanvas-header">
    <h5 id="offcanvasRightLabel">
    Create {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }}
</h5>

        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr>
    <div class="offcanvas-body" id="modalbody">
        <div class="row">
            <div class="col-6">
                <div class="mb-3 row">
                    <input type="hidden" name="" id="update_id">
                    <label for="formrow-inputState" class="form-label">
                    {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} #
                    </label>
                    <input class="form-control " type="text" name="ticket_n" id="ticket_no" data-model="post"
                        disabled="">
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3 row">
                    <label for="formrow-inputState" class="form-label">
                    {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Created By
                    </label>
                    <div class="col-md-12">
                        <select class="form-control" name="choices-single-default" id="created_by"
                            placeholder="This is a search placeholder" value="Admin" data-model="post" disabled="">
                            <option value="0" selected>Admin</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="square-switch">
                <input type="checkbox" id="square-switch1" switch="none" checked="" onclick="myFunction()">
                <label for="square-switch1" data-on-label="Indivisual" data-off-label="Group"
                    style="width: 100px;"></label>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Task Assigned</label>
                        <div class="col-md-12">
                            <select class="form-control" id="assigned" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">
                        {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Reported By
                        </label>
                        <div class="col-md-12">
                            <select class="form-control" id="reported" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="formrow-inputCity" class="form-label">Problem</label>
                        <input type="text" class="form-control" placeholder="Enter Problem" id="title">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="formrow-inputCity" class="form-label">Mode of Complaint</label>
                        <input type="text" class="form-control" placeholder="Mode of Complaint" id="moc">
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Issue Type</label>
                        <div class="col-md-12">
                            <select class="form-control" id="issuetype" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">
                        {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Resolved By
                        </label>
                        <div class="col-md-12">
                            <select class="form-control" id="ticketresolvedby"
                                placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <!-- <div class="row"> -->
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Issue Sub Type</label>
                        <div class="col-md-12">
                            <select class="form-control" id="subtype" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">
                        {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Resolved Date
                        </label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Resolved Date" id="resolved">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Status</label>
                        <div class="col-md-12">
                            <select class="form-control" id="status1">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Priority</label>
                        <div class="col-md-12">
                            <select class="form-control" id="priority" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label" id="bu_label">BU/Store Name</label>
                        <div class="col-md-12">
                            <select class="form-control" id="business" placeholder="This is a search placeholder"
                                onchange="addtional_fields(this.value)">


                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6" id="customer_col" style="display: none;">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Account</label>
                        <div class="col-md-12">
                            <select class="form-control" id="customer" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6" id="vendor_col" style="display: none;">
                    <div class="mb-3 row">
                        <label for="vendor" class="form-label">Vendors</label>
                        <div class="col-md-12">
                            <select class="form-control" id="vendor" placeholder="Select a vendor">
                                <!-- Options will be added dynamically -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">
                        {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Due Date
                        </label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Due Date" id="due_date">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Store Info</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Enter Store Info" id="store_info">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Impact</label>
                        <div class="col-md-12">
                            <select class="form-control" id="impact" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-6" id="store_mdiv" style="display:none;">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Store Manager </label>
                        <div class="col-md-12">
                            <select class="form-control" id="manager" placeholder="This is a search placeholder">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6" id="marketing_mdiv" style="display:none;">
                    <div class="mb-3 row">
                        <label for="formrow-inputState" class="form-label">Marketing Manager </label>
                        <div class="col-md-12">
                            <select class="form-control" id="marketing" placeholder="This is a search placeholder">

                            </select>


                        </div>
                    </div>
                </div> -->
                <div>
                    <div class="row" id="newfields">
                    </div>

                </div>
                <div class="col-12">
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Allow Qutation</label>
                        <div class="col-md-10">
                            <input type="checkbox" name="quote" id="quote" class="form-check">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Description</label>
                        <div class="col-md-10">
                            <textarea class="form-control" id="description" name="description" spellcheck="false">
                                    </textarea>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        {{-- <form action="#" class="dropzone dz-clickable">

                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted mdi mdi-cloud-upload"></i>
                                    </div>

                                    <h4>Click to Upload</h4>
                                </div>
                            </form> --}}
                        <DIV id="dropzone">
                            <FORM class="dropzone needsclick" id="demo-upload" action="/upload">
                                <DIV class="dz-message needsclick" style="    display: flex
;
    align-items: center;
    justify-content: center;
    font-size: medium;">
                                    Drop files here<BR>

                                </DIV>
                            </FORM>
                        </DIV>
                        <DIV id="preview-template" style="display: none;">
                            <DIV class="dz-preview dz-file-preview">
                                <DIV class="dz-image"><IMG data-dz-thumbnail=""></DIV>
                                <DIV class="dz-details">
                                    <DIV class="dz-size"><SPAN data-dz-size=""></SPAN></DIV>
                                    <DIV class="dz-filename"><SPAN data-dz-name=""></SPAN></DIV>
                                </DIV>
                                <DIV class="dz-progress"><SPAN class="dz-upload" data-dz-uploadprogress=""></SPAN>
                                </DIV>
                                <DIV class="dz-error-message"><SPAN data-dz-errormessage=""></SPAN></DIV>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                {{-- <label for="example-text-input" class="col-md-2 col-form-label">Domain Members</label> --}}
                                <div class="col-md-6">
                                    <div id="fields">
                                        <input type="text" name="hidden" hidden id="hidden" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-10 col-form-label"></label>
                                <div class="col-md-2">

                                    <button type="button" onclick="submit()"
                                        class="btn btn-primary waves-effect waves-light">Save</button>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="editOffcanvasLabel">Edit Ticket</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form>
            <div class="row mb-3">
                <input type="hidden" id="editTicketId">
                <div class="col-md-6">
                    <label for="editTicketNumber" class="form-label">Ticket#</label>
                    <input type="text" class="form-control" id="editTicketNumber" placeholder="Enter Ticket#"
                        value="TICKET-001">
                </div>
                <div class="col-md-6">
                    <label for="editTicketCreatedBy" class="form-label">Ticket Created By</label>
                    <input type="text" class="form-control" id="editTicketCreatedBy" placeholder="Enter creator name"
                        value="John Doe">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editTaskAssigned" class="form-label">Task Assigned</label>
                    <input type="text" class="form-control" id="editTaskAssigned" placeholder="Enter task assigned"
                        value="Investigate network issue">
                </div>
                <div class="col-md-6">
                    <label for="editTicketReportedBy" class="form-label">Ticket Reported By</label>
                    <input type="text" class="form-control" id="editTicketReportedBy" placeholder="Enter reporter name"
                        value="Jane Smith">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editProblem" class="form-label">Problem</label>
                    <input type="text" class="form-control" id="editProblem" placeholder="Describe the problem"
                        value="Slow internet connectivity in main office">
                </div>
                <div class="col-md-6">
                    <label for="editModeOfComplaint" class="form-label">Mode of Complaint</label>
                    <input type="text" class="form-control" id="editModeOfComplaint" placeholder="Enter complaint mode"
                        value="Email">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editIssueType" class="form-label">Issue Type</label>
                    <input type="text" class="form-control" id="editIssueType" placeholder="Enter issue type"
                        value="Network">
                </div>
                <div class="col-md-6">
                    <label for="editTicketResolvedBy" class="form-label">Ticket Resolved By</label>
                    <input type="text" class="form-control" id="editTicketResolvedBy" placeholder="Enter resolver name"
                        value="Peter Jones">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editIssueSubType" class="form-label">Issue Sub Type</label>
                    <input type="text" class="form-control" id="editIssueSubType" placeholder="Enter issue sub type"
                        value="Bandwidth">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ticket Resolved Date</label>
                    <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                        value="15 Jul, 2025">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editStatus1" class="form-label">Status</label>
                    <input type="text" class="form-control" id="editStatus1" placeholder="Enter Status"
                        value="Resolved">
                </div>
                <div class="col-md-6">
                    <label for="editPriority" class="form-label">Priority</label>
                    <input type="text" class="form-control" id="editPriority" placeholder="Enter priority" value="High">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editBuStoreName" class="form-label">BU/Store Name</label>
                    <input type="text" class="form-control" id="editBuStoreName" placeholder="Enter BU or Store Name"
                        value="Head Office">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ticket Due Date</label>
                    <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                        value="20 Jul, 2025">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="editStoreInfoImpact" class="form-label">Store Info</label>
                    <input type="text" class="form-control" id="editStoreInfoImpact" placeholder="Enter impact info"
                        value="Affects 50+ users">
                </div>
                <div class="col-md-6">
                    <label for="editImpact" class="form-label">Impact</label>
                    <input type="text" class="form-control" id="editImpact" placeholder="Enter impact"
                        value="Business Critical">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="editDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="editDescription" rows="3"
                        placeholder="Enter description">Users are experiencing very slow internet speeds, leading to difficulties accessing cloud-based applications and general web Browse.</textarea>
                </div>
            </div>

            <div class="mb-3">
                <label class="custom-file-dropzone" for="editFileUpload">
                    Drop files here or click to upload. (Existing files: report.pdf, screenshot.png)
                </label>
                <input class="custom-file-input" type="file" id="editFileUpload" multiple>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary" onclick="updateData()">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
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
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.mi    n.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"
    integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

const ticketLabel = @json(optional(session('userWithBU'))->ticket_represented ?? 'Ticket');

    var assigned, reported, marketing, manager, ticketresolve,vendor;
    var subtype;
    var table, business_units;
    var resolved, due_date, type1, status1, impact, priority, bu, tid, customer, upstatus;
    var allrecord, typefil, Priorityfil, impactfil;
    let subscriptionDataTable = null; // reuse name if same table, else adjust
    let permissions = null;
    const roleId = '{{ Auth::user()->role }}';

    $(document).ready(function() {

        loadPermissions(roleId)
            .then(() => {
                fetchTable();
                applyPermissionUI();
            })
            .catch(err => {
                console.error("Initialization error:", err);
                fetchTable(); // still try to load if permissions fail
            });
        $.ajax({
            url: "{{ config('app.api_url') }}companies/{{ Auth::user()->company_id }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $.ajax({
                    url: "{{ config('app.api_url') }}domains/" +
                        response[0]['domain_id'] + "",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {

                        if (response[0]['eligible'] == 0) {
                            $('#bu_label').text('Buisness Unit');
                            $('#store_mdiv').css('display', 'none');
                            $('#marketing_mdiv').css('display', 'none');
                        } else {
                            $('#bu_label').text('BU/Store Name');
                            $('#store_mdiv').css('display', 'block');
                            $('#marketing_mdiv').css('display', 'block');

                        }
                    }

                });

            }

        });
        var table = $('#types-table').DataTable({
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
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'dropdown-item-export',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'dropdown-item-export',
                        filename: 'Tickets_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'dropdown-item-export',
                        filename: 'Tickets_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'dropdown-item-export',
                        filename: 'Tickets_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'dropdown-item-export',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            }]
        });





        customer = new Choices("#customer", {
            removeItemButton: !0,
        })
        bu = new Choices("#business", {
            removeItemButton: !0,
        })
        $.ajax({
            url: "{{Auth::user()->company_id}}"==0 ?"{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                business_units = response;
                bu.clearChoices(); // This line is causing the error
                console.log("check" + response);
                bu.setChoices(response, 'id', 'name', false);
                setTimeout(function() {
                    bu.setChoiceByValue('{{ Auth::user()->bu_id }}');
                }, 5000);

                // Move the setChoiceByValue inside the success callback
            }
        });
        // Date range picker initialization
        let filteredData = allrecord; // original data backup
        $('#selectedDateRange').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                format: 'MMM D, YYYY',
                cancelLabel: 'Clear',
                applyLabel: 'Apply'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ],
                'This Quarter': [moment().startOf('quarter'), moment().endOf('quarter')]
            }
        });

        $('#selectedDateRange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format(
                'MMM D, YYYY'));

            let start = picker.startDate.startOf('day');
            let end = picker.endDate.endOf('day');

            let filtered = allrecord.filter(item => {
                let createdAt = moment(item.created_at, 'YYYY-MM-DD HH:mm:ss');
                return createdAt.isBetween(start, end, null, '[]');
            });

            console.log('Filtered data:', filtered);
            const statusCounts = {
                Open: 0,
                Closed: 0,
                Pending: 0,
                Completed: 0
            };
            filtered.forEach(ticket => {
                const status = ticket.status_title?.toLowerCase();
                if (status === 'open') statusCounts.Open++;
                else if (status === 'closed') statusCounts.Closed++;
                else if (status === 'pending') statusCounts.Pending++;
                else if (status === 'completed') statusCounts.Completed++;
            });

            const totalTickets = filtered.length;

            // ✅ Update status count in HTML (adjust IDs according to your HTML)
            $('#open_tickets').text(statusCounts.Open);
            $('#closed_tickets').text(statusCounts.Closed);
            $('#pending_tickets').text(statusCounts.Pending);
            $('#completed_count').text(statusCounts.Completed);
            $('#total_tickets').text(totalTickets);

            const table = $('#types-table').DataTable();
            table.clear().rows.add(filtered).draw();
        });

        $('#clearDateRange').on('click', function() {
            $('#selectedDateRange').val('');
            const statusCounts = {
                Open: 0,
                Closed: 0,
                Pending: 0,
                Completed: 0
            };
            allrecord.forEach(ticket => {
                const status = ticket.status_title?.toLowerCase();
                if (status === 'open') statusCounts.Open++;
                else if (status === 'closed') statusCounts.Closed++;
                else if (status === 'pending') statusCounts.Pending++;
                else if (status === 'completed') statusCounts.Completed++;
            });

            const totalTickets = allrecord.length;

            // ✅ Update status count in HTML (adjust IDs according to your HTML)
            $('#open_tickets').text(statusCounts.Open);
            $('#closed_tickets').text(statusCounts.Closed);
            $('#pending_tickets').text(statusCounts.Pending);
            $('#completed_count').text(statusCounts.Completed);
            $('#total_tickets').text(totalTickets);
            const table = $('#types-table').DataTable();
            table.clear().rows.add(allrecord).draw();
        });

        resolved = flatpickr('#resolved', {});
        due_date = flatpickr('#due_date', {});
        // $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        //     var dateRange = $('#selectedDateRange').val();
        //     if (!dateRange) return true;

        //     try {
        //         var parts = dateRange.split(' - ');
        //         if (parts.length !== 2) return true;

        //         var min = moment(parts[0], 'MMM D, YYYY');
        //         var max = moment(parts[1], 'MMM D, YYYY');

        //         var dateStr = data[4];
        //         if (!dateStr) return false;

        //         var date = moment(dateStr, 'YYYY-MM-DD');
        //         if (!date.isValid()) return false;

        //         return date.isBetween(min, max, null, '[]');
        //     } catch (e) {
        //         console.error("Date filter error:", e);
        //         return true;
        //     }
        // });

        $("[data-provider='flatpickr']").flatpickr({
            dateFormat: 'd M, Y'
        });

        $.ajax({
            url: ("{{ Auth::user()->company_id == 0 }}" ?
                "{{ config('app.api_url') }}users/super_admin_users" :
                "{{ config('app.api_url') }}users/company/{{ Auth::user()->company_id }}"
            ),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("resspp", response);

                ticketresolve = new Choices("#ticketresolvedby", {
                    removeItemButton: !0,
                })
                ticketresolve.clearChoices();
                console.log(response);
                ticketresolve.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Ensuring 'u_name' is correctly displayed
                    })),
                    'value',
                    'label',
                    false
                );

                assigned = new Choices("#assigned", {
                    removeItemButton: !0,
                })
                assigned.clearChoices();
                console.log(response);
                assigned.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );

                reported = new Choices("#reported", {
                    removeItemButton: !0,
                })
                reported.clearChoices();
                console.log(response);
                reported.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );


                marketing = new Choices("#marketing", {
                    removeItemButton: !0,
                })
                marketing.clearChoices();
                console.log(response);
                marketing.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Formatting as "u_name (email)"
                    })),
                    'value',
                    'label',
                    false
                );


                manager = new Choices("#manager", {
                    removeItemButton: !0,
                })
                manager.clearChoices();
                console.log(response);
                manager.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Ensures 'u_name' is displayed correctly
                    })),
                    'value',
                    'label',
                    false
                );




                // ticketresolved.setChoiceByValue(748);

            }
        });

        $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}types" :
            //     "{{ config('app.api_url') }}companytype/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}types",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                type1 = new Choices("#issuetype", {
                    removeItemButton: !0,
                })
                type1.clearChoices();
                console.log(response);
                type1.setChoices(response,
                    'id',
                    'title',
                    false, );

                typefil = new Choices("#typefil", {
                    removeItemButton: !0,
                })
                typefil.clearChoices();
                console.log(response);
                typefil.setChoices(response,
                    'id',
                    'title',
                    false, );


            }
        });

        $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}status" :
            //     "{{ config('app.api_url') }}status/company/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}status",  
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                status1 = new Choices("#status1", {
                    removeItemButton: !0,
                })
                status1.clearChoices();
                console.log(response);
                status1.setChoices(response,
                    'id',
                    'title',
                    false, );
            }
        });

        $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}priority" :
            //     "{{ config('app.api_url') }}priority/company/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}priority",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                priority = new Choices("#priority", {
                    removeItemButton: !0,
                })
                priority.clearChoices();
                console.log(response);
                priority.setChoices(response,
                    'id',
                    'title',
                    false, );
                Priorityfil = new Choices("#Priorityfil", {
                    removeItemButton: !0,
                })
                Priorityfil.clearChoices();
                console.log(response);
                Priorityfil.setChoices(response,
                    'id',
                    'title',
                    false, );
            }
        });

        $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}impacts" :
            //     "{{ config('app.api_url') }}impacts/company/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}impacts", 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                impact = new Choices("#impact", {
                    removeItemButton: !0,
                })
                impact.clearChoices();
                console.log(response);
                impact.setChoices(response,
                    'id',
                    'title',
                    false, );

                impactfil = new Choices("#impactfil", {
                    removeItemButton: !0,
                })
                impactfil.clearChoices();
                console.log(response);
                impactfil.setChoices(response,
                    'id',
                    'title',
                    false, );
            }
        });
        subtype = new Choices("#subtype", {
            removeItemButton: !0,
        })
        $.ajax({
            // url: "{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}types":"{{ config('app.api_url') }}companytype/{{Auth::user()->company_id}}",
            url: "{{ config('app.api_url') }}types",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                subtype.clearChoices();
                console.log(response);
                subtype.setChoices(response,
                    'id',
                    'title',
                    false, );
            }
        });
    });

    const profileDropzone = new Dropzone('#demo-upload', {
        previewTemplate: document.querySelector('#preview-template').innerHTML,
        parallelUploads: 2,
        thumbnailHeight: 120,
        thumbnailWidth: 120,
        maxFilesize: 30,
        maxFiles: 1,
        filesizeBase: 1000,
        url: "{{ config('app.api_url') }}tickets", // API endpoint URL
        paramName: "profile", // Name of the file parameter
        autoProcessQueue: true,
        thumbnail: function(file, dataUrl) {
            if (file.previewElement) {
                file.previewElement.classList.remove("dz-file-preview");
                var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                for (var i = 0; i < images.length; i++) {
                    var thumbnailElement = images[i];
                    thumbnailElement.alt = file.name;
                    thumbnailElement.src = dataUrl;
                }
                setTimeout(function() {
                    file.previewElement.classList.add("dz-image-preview");
                }, 1);
            }
        }



    });




    // Now fake the file upload, since GitHub does not handle file uploads
    // and returns a 404

    var minSteps = 6,
        maxSteps = 60,
        timeBetweenSteps = 100,
        bytesPerStep = 100000;

    dropzone.uploadFiles = function(files) {
        var self = this;

        for (var i = 0; i < files.length; i++) {
P
            var file = files[i];
            totalSteps = Math.round(Math.min(maxSteps, Math.max(minSteps, file.size / bytesPerStep)));

            for (var step = 0; step < totalSteps; step++) {
                var duration = timeBetweenSteps * (step + 1);       
                setTimeout(function(file, totalSteps, step) {
                    return function() {
                        file.upload = {
                            progress: 100 * (step + 1) / totalSteps,
                            total: file.size,
                            bytesSent: (step + 1) * file.size / totalSteps
                        };

                        self.emit('uploadprogress', file, file.upload.progress, file.upload.bytesSent);
                        if (file.upload.progress == 100) {
                            file.status = Dropzone.SUCCESS;
                            self.emit("success", file, 'success', null);
                            self.emit("complete", file);
                            self.processQueue();
                            //document.getElementsByClassName("dz-success-mark").style.opacity = "1";
                        }
                    };
                }(file, totalSteps, step), duration);
            }
        }
    }


    async function loadPermissions(roleId) {
        try {
            const res = await $.ajax({
                url: `{{ config('app.api_url') }}permissions/${roleId}`,
                method: 'GET',
                dataType: 'json'
            });
            permissions = Array.isArray(res) && res.length ? res[0] : res;
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
        // Example: hide Add New if no create permission on tickets (adjust section name if different)
        if (!hasPermission('ticket', 'create')) {
            $('#addNewBtn').hide();
        } else {
            $('#addNewBtn').show();
        }
        if (!hasPermission('ticket', 'update')) {
            // remove Edit header cell
            $('#types-table thead tr th').filter(function() {

                return $(this).text().trim() == 'Edit';
            }).remove();
        }
    }

    function fetchTable() {
        
        const tableSelector = '#types-table';
        const loadingEl = $('#table-loading');
        const exportContainer = $('#customExportButtons');

        loadingEl.show();
        $(tableSelector).hide();

        if ($.fn.DataTable.isDataTable(tableSelector)) {
            $(tableSelector).DataTable().clear().destroy();
            $(`${tableSelector} tbody`).empty();
        }

        $.ajax({
            // url: "{{ config('app.api_url') }}tickets",
            url: "{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}tickets":"{{ config('app.api_url') }}dashboard/get_all_tickets/{{Auth::user()->id}}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                allrecord= response

                const data = Array.isArray(response) ? response : [response];

                // status counts
                const statusCounts = {
                    Open: 0,
                    Closed: 0,
                    Pending: 0,
                    Completed: 0
                };
                data.forEach(ticket => {
                    const status = (ticket.status_title || '').toLowerCase();
                    if (status === 'open') statusCounts.Open++;
                    else if (status === 'closed') statusCounts.Closed++;
                    else if (status === 'pending') statusCounts.Pending++;
                    else if (status === 'completed') statusCounts.Completed++;
                });

                $('#open_tickets').text(statusCounts.Open);
                $('#closed_tickets').text(statusCounts.Closed);
                $('#pending_tickets').text(statusCounts.Pending);
                $('#completed_count').text(statusCounts.Completed);
                $('#total_tickets').text(data.length);

                // build column definitions with permission gating
                const cols = [{
                        data: 'id',
                        title: 'S.No',
                        render: function(_, __, ___, meta) {
                            return meta.row + 1;
                        }
                    },
                    
                    {
                        data: 'ticketID',
                        title: ticketLabel + ' NO ',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'title',
                        title: 'Title',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'type_title',
                        title: 'Issue Type',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'created_at',
                        title: 'Reported On',
                        render: d => d ? moment(d).format('DD MMM, YYYY') : 'N/A'
                    },
                    {
                        data: 'company_id',
                        title: 'Company Id',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'company_name',
                        title: 'Company Name',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'completed_date',
                        title: 'Complete Date',
                        render: d => d ? moment(d).format('DD MMM, YYYY') : 'N/A'
                    },
                    {
                        data: 'due_date',
                        title: 'Due Date',
                        render: d => d ? moment(d).format('DD MMM, YYYY') : 'N/A'
                    },
                    {
                        data: 'store_contact',
                        title: 'Store Content',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'bu_name',
                        title: 'Business Unit',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'priority_title',
                        title: 'Priority',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'impact_title',
                        title: 'Impact',
                        render: d => d ? d : 'N/A'
                    },
                    {
                        data: 'description',
                        title: 'Description',
                        render: d => {
                            if (!d) return 'N/A';
                            return d.length > 50 ? d.substring(0, 50) + '...' : d;
                        }
                    },
                    {
                        data: 'status_title',
                        title: 'Status',
                        render: d => d ? d : 'N/A'
                    }
                ];

                // View column if read permitted
                if (hasPermission('ticket', 'read')) {
                    cols.push({
                        data: null,
                        title: 'View',
                        orderable: false,
                        render: function(rowData) {
                            return `<a href="tickets-view?id=${rowData.id}&t=${rowData.sub_type_id}" class="btn btn-sm btn-info">View</a>`;
                        }
                    });
                }

                // Edit column if update permitted
                if (hasPermission('ticket', 'update')) {
                    cols.push({
                        data: null,
                        title: 'Edit',
                        orderable: false,
                        render: function(rowData) {
                            return `<button class="btn btn-sm btn-primary" onclick="view(${rowData.id})">Edit</button>`;
                        }
                    });
                }

                // initialize DataTable
                const dt = $(tableSelector).DataTable({
                    data: data,
                    responsive: true,
                    paging: true,
                    searching: true,
                    dom: 'Bftip',
                    buttons: hasPermission('ticket', 'read') ? [{
                        extend: 'collection',
                        text: '<i class="ri-file-excel-2-line"></i> Export Excel',
                        className: 'btn btn-success',
                        buttons: [{
                                extend: 'copy',
                                text: '<i class="fas fa-copy"></i> Copy',
                                className: 'dropdown-item-export'
                            },
                            {
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'dropdown-item-export',
                                filename: 'Tickets_' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'csv',
                                text: '<i class="fas fa-file-csv"></i> CSV',
                                className: 'dropdown-item-export',
                                filename: 'Tickets_' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'dropdown-item-export',
                                filename: 'Tickets_' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Print',
                                className: 'dropdown-item-export'
                            }
                        ]
                    }] : [],
                    columns: cols,
                    initComplete: function() {
                        loadingEl.hide();
                        $(tableSelector).show();

                        setTimeout(() => {
                            exportContainer.html('');
                            if (dt.buttons) dt.buttons().container().appendTo(exportContainer);

                            $('.dt-button-collection').addClass('dropdown-menu-export');
                            $('.buttons-collection').addClass('dropdown-toggle');
                            $('.dt-button-collection .dt-button').addClass('dropdown-item-export');
                        }, 0);
                    },
                    language: {
                        emptyTable: 'No tickets found',
                        zeroRecords: 'No matching tickets found'
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching tickets:", error);
                loadingEl.html(`
                    <div class="alert alert-danger">
                        Failed to load ticket data. 
                        <button class="btn btn-sm btn-link" onclick="fetchTable()">Try Again</button>
                    </div>
                `);
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

    function createData() {
        const requiredFields = [{
                id: 'ticketNumber',
                name: 'Ticket Number'
            },
            {
                id: 'description',
                name: 'Description'
            },
            {
                id: 'ticketReportedBy',
                name: 'Reported By'
            }
        ];

        let isValid = true;
        requiredFields.forEach(field => {
            const value = $(`#${field.id}`).val().trim();
            if (!value) {
                showToast(`${field.name} is required`, "warning");
                isValid = false;
            }
        });

        if (!isValid) return;

        const formData = new FormData();
        formData.append("description", $("#description").val());
        formData.append("completed_date", $("[data-provider='flatpickr']").eq(0).val());
        formData.append("due_date", $("[data-provider='flatpickr']").eq(1).val());
        formData.append("company_id", 1);
        formData.append("completed_by", 1);
        formData.append("business_unit_id", 1);
        formData.append("customer_id", 1);
        formData.append("vendor_id", "0");
        formData.append("vendor_type_id", "0");
        formData.append("reported_by", $("#ticketReportedBy").val());
        formData.append("assigned_to", $("#taskAssigned").val);
        formData.append("mode_of_complaint", $("#modeOfComplaint").val());
        formData.append("sub_type_id", $("#issueSubType").val());
        formData.append("priority_id", $("#priority").val());
        formData.append("impact_id", $("#impact").val());
        formData.append("status_id", $("#status1").val());
        formData.append("store_contact", $("#storeInfoImpact").val());
        formData.append("created_by", 1);
        formData.append("email_status", "0");
        formData.append("assigned_type", "Individual");
        formData.append("profile", $('#file')[0].file[0]);

        // const fileInput = document.getElementById("fileUpload");
        // if (fileInput.files.length > 0) {
        //     for (let i = 0; i < fileInput.files.length; i++) {
        //         formData.append("files[]", fileInput.files[i]);
        //     }
        // }

        const submitBtn = document.querySelector("#createOffcanvas .btn-primary");

        if (!submitBtn) {
            console.error("Submit button not found!");
            showToast("System error: Could not find submit button", "danger");
            return;
        }

        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';
        submitBtn.disabled = true;

        $.ajax({
            url: "{{ config('app.api_url') }}tickets",
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                showToast("Ticket created successfully!", "success");
                $("#createOffcanvas form")[0].reset();
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("createOffcanvas"));
                offcanvas.hide();
                fetchTable();
            },
            error: function(xhr, status, error) {
                let errorMessage = "Failed to create ticket. Please try again.";
                console.log(xhr)
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage += ` (${xhr.statusText})`;
                }
                showToast(errorMessage, "danger");
                console.error("Error creating ticket:", error);
            },
            complete: function() {
                if (submitBtn) {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            }
        });
    }

    function editData(id) {
        $.ajax({
            url: `{{ config('app.api_url') }}tickets/${id}`,
            method: "GET",
            success: function(response) {
                const ticket = response.data || response;

                $("#editTicketNumber").val(ticket[0].title);
                $("#editDescription").val(ticket[0].description);
                $("#editAccountId").val(ticket[0].company_id);
                $("#editBusinessUnit").val(ticket[0].business_unit_id);
                $("#editModeOfComplaint").val(ticket[0].mode_of_complaint);
                $("#editIssueSubType").val(ticket[0].sub_type_id);
                $("#editPriority").val(ticket[0].priority_id);
                $("#editImpact").val(ticket[0].impact_id);
                $("#editStatus1").val(ticket[0].status_id);
                $("#editStoreInfoImpact").val(ticket[0].store_contact);
                $("#editTicketReportedBy").val(ticket[0].reported_by);
                $("#editTaskAssigned").val(ticket[0].assigned_to);

                $("[data-provider='flatpickr']", "#editOffcanvas").eq(0).val(ticket[0].completed_date);
                $("[data-provider='flatpickr']", "#editOffcanvas").eq(1).val(ticket[0].due_date);

                $("#editTicketId").val(ticket.id);

                const offcanvas = new bootstrap.Offcanvas(document.getElementById("editOffcanvas"));
                offcanvas.show();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching ticket:", error);
                alert("Failed to load ticket details. Please try again.");
            }
        });
    }

    function updateData() {
        const id = $("#editTicketId").val();
        const formData = new FormData();

        const requiredFields = [{
                id: 'editTicketNumber',
                name: 'Ticket Number'
            },
            {
                id: 'editDescription',
                name: 'Description'
            },
            {
                id: 'editTicketReportedBy',
                name: 'Reported By'
            }
        ];

        let isValid = true;
        requiredFields.forEach(field => {
            const value = $(`#${field.id}`).val().trim();
            if (!value) {
                showToast(`${field.name} is required`, "warning");
                isValid = false;
            }
        });

        if (!isValid) return;

        formData.append("title", $("#editTicketNumber").val());
        formData.append("description", $("#editDescription").val());
        formData.append("completed_date", $("[data-provider='flatpickr']", "#editOffcanvas").eq(0).val());
        formData.append("due_date", $("[data-provider='flatpickr']", "#editOffcanvas").eq(1).val());
        formData.append("company_id", $("#editAccountId").val());
        formData.append("business_unit_id", $("#editBusinessUnit").val());
        formData.append("mode_of_complaint", $("#editModeOfComplaint").val());
        formData.append("sub_type_id", $("#editIssueSubType").val());
        formData.append("priority_id", $("#editPriority").val());
        formData.append("impact_id", $("#editImpact").val());
        formData.append("status_id", $("#editStatus1").val());
        formData.append("store_contact", $("#editStoreInfoImpact").val());
        formData.append("reported_by", $("#editTicketReportedBy").val());
        formData.append("assigned_to", $("#editTaskAssigned").val());

        const fileInput = document.getElementById("editFileUpload");
        if (fileInput.files.length > 0) {
            formData.append("file", fileInput.files[0]);
        }

        const submitBtn = document.querySelector("#editOffcanvas .btn-primary");
        if (submitBtn) {
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            submitBtn.disabled = true;
        }

        $.ajax({
            url: `{{ config('app.api_url') }}tickets/${id}`,
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast("Ticket updated successfully!", "success");

                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("editOffcanvas"));
                offcanvas.hide();
                fetchTable();
            },
            error: function(xhr, status, error) {
                let errorMessage = "Failed to update ticket. Please try again.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage += ` (${xhr.statusText})`;
                }

                showToast(errorMessage, "danger");
                console.error("Error updating ticket:", error);
            },
            complete: function() {
                if (submitBtn) {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            }
        });
    }


    function createticket1() {

        var form = new FormData();
        form.append("title", document.getElementById("title").value);
        form.append("description", document.getElementById("description").value);
        form.append("completed_date", document.getElementById("resolved").value);
        form.append("due_date", document.getElementById("due_date").value);
        form.append("company_id", 1);
        form.append("completed_by", document.getElementById("ticketresolved").value);
        form.append("business_unit_id", document.getElementById("business").value);
        form.append("customer_id", document.getElementById("customer").value);
        form.append("vendor_id", "0");
        form.append("vendor_type_id", "0");
        form.append("reported_by", document.getElementById("reported").value);
        form.append("assigned_to", 1);
        form.append("mode_of_complaint", document.getElementById("moc").value);
        form.append("sub_type_id", document.getElementById("subtype").value);
        form.append("priority_id", document.getElementById("priority").value);
        form.append("impact_id", document.getElementById("impact").value);
        form.append("status_id", document.getElementById("status1").value);
        form.append("store_contact", document.getElementById("store_info").value);
        form.append("created_by", 1);
        form.append("email_status", "0");
        form.append("allow_quot", $('#quote').prop('checked') ? 1 : 0);
        var checkBox1 = document.getElementById("square-switch1");
        console.log("SoMI" + profileDropzone.files[0]);

        if (checkBox1.checked == true) {
            // alert('checked');
            form.append("assigned_type", "Individual");
        } else {
            // alert('unchecked')
            form.append("assigned_type", "Group");

        }

        if (profileDropzone.files.length > 0) {
            form.append("profile", profileDropzone.files[0]);
        } else {
            // alert("Samad---"+profileDropzone.files.length);
        }


        // console.log(form);

        var settings = {
            "url": "{{ config('app.api_url') }}tickets",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };


        $.ajax({
            ...settings,
            statusCode: {
                200: function(response) {
                    console.log(response);
                    // $('#myModal').modal('hide');

                    // console.log("Request was successful");
                    // if ($('#newfields').children().length > 0) {
                    //     // The div has child elements
                    //     console.log("Div has children.");
                    //     var responseObject = JSON.parse(response);

                    //     // Access the "id" property
                    //     tid = responseObject.id;

                    //     // Iterate through input elements with class 'data-input'
                    //     $('.multi_text').each(function() {
                    //         var inputVal = $(this).val();
                    //         var dataId = $(this).data('id');
                    //         console.log('data-id ' + dataId);
                    //         var formdata = new FormData();
                    //         formdata.append("field_id", dataId);
                    //         formdata.append("bu_id", document.getElementById(
                    //             "business").value);
                    //         formdata.append("Ticket_id", tid);
                    //         formdata.append("field_data", inputVal);
                    //         var settings = {
                    //             "url": "api/bu_fields_data",
                    //             "method": "POST",
                    //             "timeout": 0,
                    //             "processData": false,
                    //             "mimeType": "multipart/form-data",
                    //             "contentType": false,
                    //             "data": formdata
                    //         };

                    //         $.ajax(settings).done(function(response) {
                    //             console.log(response);
                    //         }); // Get the value of the current input
                    //     });
                    //     document.getElementById('title')
                    //         .value = "";
                    //     document.getElementById('hidden').value = "";
                    // } else {
                    //     // The div has no child elements
                    //     console.log("Div has no children.");
                    // }

                    // Do something with the collected data

                    // getg_id();

                    Swal.fire(
                        'Success!',
                        'Ticket Created Successfully',
                        'success'
                    )


                    $("#offcanvasRight :input").val("");


                },
                // Add more status code handlers as needed
            },
            success: function(data) {
                // Additional success handling if needed

            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr)
                console.log(errorThrown)
                console.log(textStatus)
                Swal.fire(
                    'Server Error!',
                    'Ticket Not Created',
                    'error'
                )

                // console.log("Request failed with status code: " + xhr.status);
            }
        });
    }

    function submit() {
    var bu_id = $('#business').val();
    var assign_val = $('#assigned').val();
    var assign;
    update_id = $('#update_id').val()
    
    if (update_id == "") {
        // ==================== CREATE NEW TICKET ====================
        if (assign_val == null) {
            // Case 1: No assigned user selected - get responsible user from BU
            var settings = {
                "url": "{{ config('app.api_url') }}business_units_r/{{ Auth::user()->company_id }}/" + bu_id,
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                assign = response[0]['reponsible_user'];
                var form = new FormData();
                form.append("title", document.getElementById("title").value);
                form.append("description", document.getElementById("description").value);
                form.append("completed_date", document.getElementById("resolved").value);
                form.append("due_date", document.getElementById("due_date").value);
                form.append("company_id", '{{Auth::user()->company_id}}');
                form.append("completed_by", document.getElementById("ticketresolvedby").value);
                form.append("business_unit_id", document.getElementById("business").value);
                form.append("customer_id", document.getElementById("customer").value);
                form.append("vendor_id", $('#vendor').val());
                form.append("vendor_type_id", "0");
                form.append("reported_by", document.getElementById("reported").value);
                form.append("assigned_to", assign);
                form.append("mode_of_complaint", document.getElementById("moc").value);
                form.append("sub_type_id", document.getElementById("subtype").value);
                form.append("priority_id", document.getElementById("priority").value);
                form.append("impact_id", document.getElementById("impact").value);
                form.append("status_id", document.getElementById("status1").value);
                form.append("store_contact", document.getElementById("store_info").value);
                form.append("created_by", '{{Auth::user()->id}}');
                form.append("allow_quot", $('#quote').prop('checked') ? 1 : 0);
                form.append("email_status", "0");
                var checkBox1 = document.getElementById("square-switch1");

                if (checkBox1.checked == true) {
                    form.append("assigned_type", "Individual");
                } else {
                    form.append("assigned_type", "Group");
                }

                if (profileDropzone.files.length > 0) {
                    form.append("profile", profileDropzone.files[0]);
                }

                var settings = {
                    "url": "{{ config('app.api_url') }}tickets",
                    "method": "POST",
                    "timeout": 0,
                    "processData": false,
                    "mimeType": "multipart/form-data",
                    "contentType": false,
                    "data": form
                };

                $.ajax({
                    ...settings,
                    success: function(response) {
                        console.log("Success:", response);
                        
                        // Handle additional fields if any
                        if ($('#newfields').children().length > 0) {
                            console.log("Div has children.");
                            var responseObject = typeof response === 'string' ? JSON.parse(response) : response;
                            var tid = responseObject.id;
                            
                            $('.multi_text').each(function() {
                                var inputVal = $(this).val();
                                var dataId = $(this).data('id');
                                var formdata = new FormData();
                                formdata.append("field_id", dataId);
                                formdata.append("bu_id", document.getElementById("business").value);
                                formdata.append("Ticket_id", tid);
                                formdata.append("field_data", inputVal);
                                var fieldSettings = {
                                    "url": "{{ config('app.api_url') }}bu_fields_data",
                                    "method": "POST",
                                    "timeout": 0,
                                    "processData": false,
                                    "mimeType": "multipart/form-data",
                                    "contentType": false,
                                    "data": formdata
                                };
                                $.ajax(fieldSettings).done(function(fieldResponse) {
                                    console.log(fieldResponse);
                                });
                            });
                            document.getElementById('title').value = "";
                            document.getElementById('hidden').value = "";
                        }
                        
                        getg_id();
                        
                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Ticket Created Successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close offcanvas
                                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("createOffcanvas"));
                                if (offcanvas) offcanvas.hide();
                                
                                // Reset form
                                $("#createOffcanvas :input").val("");
                                $("#createOffcanvas textarea").val("");
                                
                                // Refresh table
                                fetchTable();
                            }
                        });
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr);
                        let errorMsg = 'Ticket Not Created';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                var errorResponse = JSON.parse(xhr.responseText);
                                errorMsg = errorResponse.message || errorMsg;
                            } catch(e) {}
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMsg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        } else {
            // Case 2: Assigned user selected
            assign = document.getElementById("assigned").value;
            var form = new FormData();
            form.append("title", document.getElementById("title").value);
            form.append("description", document.getElementById("description").value);
            form.append("completed_date", document.getElementById("resolved").value);
            form.append("due_date", document.getElementById("due_date").value);
            form.append("company_id", '{{Auth::user()->company_id}}');
            form.append("completed_by", document.getElementById("ticketresolvedby").value);
            form.append("business_unit_id", document.getElementById("business").value);
            form.append("customer_id", document.getElementById("customer").value);
            form.append("vendor_id", $('#vendor').val());
            form.append("vendor_type_id", "0");
            form.append("reported_by", document.getElementById("reported").value);
            form.append("assigned_to", assign);
            form.append("mode_of_complaint", document.getElementById("moc").value);
            form.append("sub_type_id", document.getElementById("subtype").value);
            form.append("priority_id", document.getElementById("priority").value);
            form.append("impact_id", document.getElementById("impact").value);
            form.append("status_id", document.getElementById("status1").value);
            form.append("store_contact", document.getElementById("store_info").value);
            form.append("created_by", '{{Auth::user()->id}}');
            form.append("allow_quot", $('#quote').prop('checked') ? 1 : 0);
            form.append("email_status", "0");
            var checkBox1 = document.getElementById("square-switch1");

            if (checkBox1.checked == true) {
                form.append("assigned_type", "Individual");
            } else {
                form.append("assigned_type", "Group");
            }

            if (profileDropzone.files.length > 0) {
                form.append("profile", profileDropzone.files[0]);
            }

            var settings = {
                "url": "{{ config('app.api_url') }}tickets",
                "method": "POST",
                "timeout": 0,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form
            };

            $.ajax({
                ...settings,
                success: function(response) {
                    console.log("Success:", response);
                    
                    var responseObject = typeof response === 'string' ? JSON.parse(response) : response;
                    var tid = responseObject.id;
                    
                    // Handle group assignment email notifications
                    var checkBox1 = document.getElementById("square-switch1");
                    if (responseObject.assign != 'Individual') {
                        assign = document.getElementById("assigned").value;
                        var groupSettings = {
                            "url": "{{ config('app.api_url') }}show_group/" + assign,
                            "method": "GET",
                            "timeout": 0,
                        };
                        $.ajax(groupSettings).done(function(groupResponse) {
                            var u_id = groupResponse.length
                            for (var i = 0; i < u_id; i++) {
                                var notifySettings = {
                                    "url": "{{ config('app.api_url') }}dashboard/notifyemail/" +
                                        groupResponse[i]['id'] + "/" + tid + "",
                                    "method": "GET",
                                    "timeout": 0,
                                };
                                $.ajax(notifySettings).done(function(notifyResponse) {
                                    // console.log(response);
                                });
                            }
                        });
                    }
                    
                    // Handle additional fields if any
                    if ($('#newfields').children().length > 0) {
                        console.log("Div has children.");
                        $('.multi_text').each(function() {
                            var inputVal = $(this).val();
                            var dataId = $(this).data('id');
                            var formdata = new FormData();
                            formdata.append("field_id", dataId);
                            formdata.append("bu_id", document.getElementById("business").value);
                            formdata.append("Ticket_id", tid);
                            formdata.append("field_data", inputVal);
                            var fieldSettings = {
                                "url": "{{ config('app.api_url') }}bu_fields_data",
                                "method": "POST",
                                "timeout": 0,
                                "processData": false,
                                "mimeType": "multipart/form-data",
                                "contentType": false,
                                "data": formdata
                            };
                            $.ajax(fieldSettings).done(function(fieldResponse) {
                                console.log(fieldResponse);
                            });
                        });
                        document.getElementById('title').value = "";
                        document.getElementById('hidden').value = "";
                    }
                    
                    getg_id();
                    
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: 'Ticket Created Successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close offcanvas
                            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("createOffcanvas"));
                            if (offcanvas) offcanvas.hide();
                            
                            // Reset form
                            $("#createOffcanvas :input").val("");
                            $("#createOffcanvas textarea").val("");
                            
                            // Refresh table
                            fetchTable();
                        }
                    });
                },
                error: function(xhr) {
                    console.log("Error:", xhr);
                    let errorMsg = 'Ticket Not Created';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            var errorResponse = JSON.parse(xhr.responseText);
                            errorMsg = errorResponse.message || errorMsg;
                        } catch(e) {}
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    } else {
        // ==================== UPDATE EXISTING TICKET ====================
        var form = new FormData();
        form.append("title", document.getElementById("title").value);
        form.append("description", document.getElementById("description").value);
        form.append("completed_date", document.getElementById("resolved").value);
        form.append("due_date", document.getElementById("due_date").value);
        form.append("company_id", '{{Auth::user()->company_id}}');
        form.append("completed_by", document.getElementById("ticketresolvedby").value);
        form.append("business_unit_id", document.getElementById("business").value);
        form.append("customer_id", document.getElementById("customer").value);
        form.append("vendor_id", $('#vendor').val());
        form.append("vendor_type_id", "0");
        form.append("reported_by", document.getElementById("reported").value);
        form.append("assigned_to", $('#assigned').val());
        form.append("mode_of_complaint", document.getElementById("moc").value);
        form.append("sub_type_id", document.getElementById("subtype").value);
        form.append("priority_id", document.getElementById("priority").value);
        form.append("impact_id", document.getElementById("impact").value);
        form.append("status_id", document.getElementById("status1").value);
        form.append("store_contact", document.getElementById("store_info").value);
        form.append("created_by", '{{Auth::user()->id}}');
        form.append("allow_quot", $('#quote').prop('checked') ? 1 : 0);
        form.append("email_status", "0");
        var checkBox1 = document.getElementById("square-switch1");

        if (checkBox1.checked == true) {
            form.append("assigned_type", "Individual");
        } else {
            form.append("assigned_type", "Group");
        }

        if (profileDropzone.files.length > 0) {
            form.append("profile", profileDropzone.files[0]);
        }

        var settings = {
            "url": "{{ config('app.api_url') }}tickets/" + update_id,
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };

        $.ajax({
            ...settings,
            success: function(response) {
                console.log("Update Success:", response);
                
                Swal.fire({
                    title: 'Success!',
                    text: 'Ticket Updated Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Close offcanvas
                        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("createOffcanvas"));
                        if (offcanvas) offcanvas.hide();
                        
                        // Refresh table
                        fetchTable();
                    }
                });
            },
            error: function(xhr) {
                console.log("Update Error:", xhr);
                let errorMsg = 'Ticket Not Updated';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        errorMsg = errorResponse.message || errorMsg;
                    } catch(e) {}
                }
                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
}

    // function bu_wise(id) {
    //     $.ajax({
    //         url: "{{ config('app.api_url') }}bu_wise_cus/" + id,
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(response) {

    //             if (response.length === 0) {
    //                 $('#customer_col').css('display', "none");
    //             } else {
    //                 $('#customer_col').css('display', "block");
    //                 customer.clearChoices();
    //                 console.log(response);
    //                 customer.setChoices(response, 'id', 'first_name', false);
    //             }
    //         }
    //     });
    //     alert("heloo")
    //     var bu = business_units.find(f => f.id == id);
    //     console.log("bu",bu)
    //     if (bu['vendor_r'] == 'yes') {
    //         get_vendor()
    //     }
    //     else{
    //     $('#vendor_col').css('display', 'none');
    // }
    // }

    function bu_wise(id) {
    console.log("bu_wise called with id:", id);
    
    if(!id || id === "") {
        console.log("No BU ID provided");
        $('#customer_col').hide();
        return;
    }
    
    // Fix: Check if business_units array exists before accessing
    if(typeof business_units !== 'undefined' && business_units && business_units.length > 0) {
        var bu = business_units.find(f => f.id == id);
        console.log("Found BU:", bu);
        
        if(bu && bu.vendor_r == 'yes') {
            get_vendor();
        } else {
            $('#vendor_col').hide();
        }
    } else {
        console.log("Business units not loaded yet");
        $('#vendor_col').hide();
    }
    
    // Fix CORS issue by ensuring URL is correct
    var apiUrl = "{{ config('app.api_url') }}bu_wise_cus/" + id;
    console.log("Customer API URL:", apiUrl);
    
    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Customer response:", response);
            if(response && response.length > 0) {
                $('#customer_col').css('display', "block");
                if(window.customer && typeof window.customer.destroy === 'function') {
                    window.customer.destroy();
                }
                window.customer = new Choices("#customer", {
                    removeItemButton: true,
                    allowHTML: true
                });
                window.customer.clearChoices();
                window.customer.setChoices(response, 'id', 'first_name', false);
            } else {
                $('#customer_col').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error("Customer API error:", error);
            $('#customer_col').hide();
        }
    });
}

    // function get_vendor() {
    //     $('#vendor_col').show();
    //     var sid = $('#subtype').val();
    //     alert(sid)
    //     $.ajax({
    //         url: "{{Auth::user()->company_id}}"==0 ? '{{ config('app.api_url') }}vendors': '{{ config('app.api_url') }}vendorbytype/' + sid,
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(response) {
    //             console.log("vendors",response)
    //             vendor = new Choices("#vendor", {
    //                 removeItemButton: !0,
    //             })
    //             vendor.clearChoices();
    //             console.log(response);
    //             vendor.setChoices(response,
    //                 'id',
    //                 'name',
    //                 false, );
    //         }
    //     });
    // }

    function get_vendor() {
    console.log("========== GET VENDOR START ==========");
    
    var sid = $('#subtype').val();
    console.log("Subtype ID:", sid);
    
    if(!sid || sid === "") {
        console.log("No subtype selected, hiding vendor field");
        $('#vendor_col').hide();
        return;
    }
    
    $('#vendor_col').show();
    
    var companyId = "{{Auth::user()->company_id}}";
    var apiUrl = (companyId == 0) ? 
        "{{ config('app.api_url') }}vendors" : 
        "{{ config('app.api_url') }}vendorbytype/" + sid;
    
    console.log("API URL:", apiUrl);
    
    // CRITICAL: Destroy existing Choices instance before making AJAX call
    if(window.vendor && typeof window.vendor.destroy === 'function') {
        try {
            window.vendor.destroy();
            console.log("Destroyed existing Choices instance");
        } catch(e) {
            console.warn("Error destroying Choices:", e);
        }
        window.vendor = null;
    }
    
    // Clear and reset the select element
    var $vendorSelect = $('#vendor');
    $vendorSelect.empty();
    $vendorSelect.append('<option value="">Loading vendors...</option>');
    // Remove any Choices styling
    $vendorSelect.removeClass('choices__input');
    
    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log("Vendor Response:", response);
            
            // Clear select element
            $vendorSelect.empty();
            
            if(!response || response.length === 0) {
                console.warn("No vendors found");
                $vendorSelect.append('<option value="">No vendors available for this subtype</option>');
                return;
            }
            
            console.log("Found", response.length, "vendors");
            
            // Add default option
            $vendorSelect.append('<option value="">Select a vendor</option>');
            
            // Add vendor options
            response.forEach(function(vendor) {
                $vendorSelect.append('<option value="' + vendor.id + '">' + vendor.name + '</option>');
            });
            
            // CRITICAL: Re-initialize Choices with proper configuration
            try {
                if(typeof Choices !== 'undefined') {
                    window.vendor = new Choices("#vendor", {
                        removeItemButton: true,
                        searchEnabled: true,
                        itemSelectText: '',
                        placeholderValue: 'Select a vendor',
                        allowHTML: true,
                        shouldSort: false,
                        // Don't add loading state
                        loadingText: 'Loading...',
                        noResultsText: 'No vendors found',
                        noChoicesText: 'No vendors available'
                    });
                    console.log("Choices initialized successfully");
                } else {
                    console.warn("Choices.js not loaded");
                    $vendorSelect.css('display', 'block');
                }
            } catch(e) {
                console.error("Choices initialization failed:", e);
                $vendorSelect.css('display', 'block');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            $vendorSelect.empty();
            $vendorSelect.append('<option value="">Error loading vendors</option>');
        }
    });
}


    function myFunction() {
        var checkBox = document.getElementById("square-switch1");
        var text = document.getElementById("text");
        if (checkBox.checked == true) {
            // alert("Indivisual")
            $.ajax({
                url: "{{Auth::user()->company_id}}"==0 ?"{{ config('app.api_url') }}users/super_admin_users": "{{ config('app.api_url') }}users/company/{{ Auth::user()->company_id }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    assigned.clearChoices();
                    console.log(response);
                    assigned.setChoices(response,
                        'id',
                        'name',
                        false, );
                }
            });

        } else {
            $.ajax({
                url: "{{Auth::user()->company_id}}"==0 ?"{{ config('app.api_url') }}users/super_admin_users":"{{ config('app.api_url') }}u_group_ct/{{ Auth::user()->company_id }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    assigned.clearChoices();
                    console.log(response);
                    assigned.setChoices(response,
                        'id',
                        'title',
                        false, );
                }
            });
        }
    }



    if ("{{ Auth::user()->company_id == 0 }}") {
        function assing_record(id) {

            var settings = {
                "url": "{{ config('app.api_url') }}dashboard/created_by/{{Auth::user()->id}}/" +
                    id,
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                total_data = response;
                table.clear();
                $.each(response, function(index, data) {

                    table.row.add([
                        index + 1,
                        '<a href="Tickets/' + data.id + '">' + '000' +
                        data
                        .company_id + '-000' + data.business_unit_id + '-' +
                        data.id +
                        '</a>',
                        data.title,
                        data.type_title,
                        data.created_at,
                        (data.cus_id == null ? "-" : "AA" + data.cus_id),
                        (data.fname == null ? "-" : data.fname) + ' ' + (data.lname == null ? "" :
                            data.lname),
                        data.completed_date,
                        data.due_date,
                        data.store_contact,
                        data.bu_name,
                        data.priority_title,
                        data.impact_title,
                        data.description,
                        data.status_title,
                        '<div class="dropdown"><a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="mdi mdi-dots-horizontal"></i> </a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" data-id=' +
                        data.id + ' href="Tickets/' + data.id + '">View</a>' +
                        '<a class="dropdown-item notify" onclick="copyToClipboard(' +
                        data
                        .id +
                        ')">Share</a></div></div>',
                        '<button type="button" id="edit" name="edit"  onclick="view(' +
                        data.id +
                        ')"  class="btn btn-soft-warning waves-effect waves-light"><i class="bx bx-edit-alt font-size-16 align-middle" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"></i></button>',

                        `<label class="switch">
    <input type="checkbox" ${data.active == 1 ? "checked" : ""} onchange="updatestatus(${data.id}, this)">
    <span class="slider round"></span>
</label>`,
                        // '<a type="button"id="edit" name="edit"  href="Tickets/' + data
                        // .id +
                        // '" target="_blank" class="btn btn-soft-success waves-effect waves-light"><i class="bx bxs-show font-size-16 align-middle"></i></a>',
                        // '<button type="button"id="edit" name="edit"  onclick="editData(' +
                        // data.id +
                        // ')"  class="btn btn-soft-warning waves-effect waves-light"><i class="bx bx-edit-alt font-size-16 align-middle"></i></button>',
                        // '<button type="button" id="delete" name="delete" onclick="deleteData(' +
                        // data.id +
                        // ')" class="btn btn-soft-danger waves-effect waves-light"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>'
                    ]).draw(false);
                });

            });
        }

        function getBu() {
            $.ajax({
                url: "{{ config('app.api_url') }}business_units",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    bu.clearChoices(); // This line is causing the error
                    console.log("check" + response);
                    bu.setChoices(response, 'id', 'name', false);
                    setTimeout(function() {
                        bu.setChoiceByValue('{{ Auth::user()->bu_id }}');
                    }, 5000);

                    // Move the setChoiceByValue inside the success callback
                }
            });


        }

        function getg_id() {
            var settings = {
                "url": "{{ config('app.api_url') }}dashboard/super_admin_tickets",
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                table.clear().draw();
                $.each(response, function(index, data) {
                    table.row.add([
                        index + 1,
                        '<a href="Tickets/' + data.id + '">' + '000' +
                        data
                        .company_id + '-000' + data.business_unit_id + '-' +
                        data.id +
                        '</a>',
                        data.title,
                        data.type_title,
                        data.created_at,
                        (data.cus_id == null ? "-" : "AA" + data.cus_id),
                        (data.fname == null ? "-" : data.fname) + ' ' + (data.lname == null ? "" :
                            data.lname),
                        data.completed_date,
                        data.due_date,
                        data.store_contact,
                        data.bu_name,
                        data.priority_title,
                        data.impact_title,
                        data.description,
                        data.status_title,
                        '<div class="dropdown"><a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="mdi mdi-dots-horizontal"></i> </a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" data-id=' +
                        data.id + ' href="Tickets/' + data.id + '">View</a>' +
                        '<a class="dropdown-item notify" onclick="copyToClipboard(' +
                        data
                        .id +
                        ')">Share</a></div></div>',
                        '<button type="button" id="edit" name="edit"  onclick="view(' +
                        data.id +
                        ')"  class="btn btn-soft-warning waves-effect waves-light"><i class="bx bx-edit-alt font-size-16 align-middle" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"></i></button>',
                        `<label class="switch">
    <input type="checkbox" ${data.active == 1 ? "checked" : ""} onchange="updatestatus(${data.id}, this)">
    <span class="slider round"></span>
</label>`,

                        // '<a type="button"id="edit" name="edit"  href="Tickets/' + data
                        // .id +
                        // '" target="_blank" class="btn btn-soft-success waves-effect waves-light"><i class="bx bxs-show font-size-16 align-middle"></i></a>',
                        // '<button type="button"id="edit" name="edit"  onclick="editData(' +
                        // data.id +
                        // ')"  class="btn btn-soft-warning waves-effect waves-light"><i class="bx bx-edit-alt font-size-16 align-middle"></i></button>',
                        // '<button type="button" id="delete" name="delete" onclick="deleteData(' +
                        // data.id +
                        // ')" class="btn btn-soft-danger waves-effect waves-light"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>'
                    ]).draw(false);
                });

            });

        }
    } else if ("{{ Auth::user()->company_id != 0 }}") {
        function getg_id() {
            var settings = {
                "url": "{{ config('app.api_url') }}users/{{ Auth::user()->bu_id }}/{{ Auth::user()->designation_id }}",
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                g_id = response[0]['group_id'];
                getgname(g_id);


            })

        }

        function getgname(id) {
            var settings = {
                "url": "{{ config('app.api_url') }}users_bu_group/" + id,
                "method": "GET",
                "timeout": 0,
            };
            $.ajax(settings).done(function(response) {

                designationTitle = response[0]['title'];
                // console.log("api/dashboard/index/{{ Auth::user()->company_id }}/" + designationTitle +
                //     "/{{ Auth::user()->bu_id }}/{{ Auth::user()->id }}")
                var settings = {
                    "url": "{{ config('app.api_url') }}tickets/company/{{ Auth::user()->company_id }}",
                    "method": "GET",
                    "timeout": 0,
                };

                $.ajax(settings).done(function(response) {
                    table.clear().draw();
                    $.each(response, function(index, data) {
                        table.row.add([
                            index + 1,
                            '<a href="Tickets/' + data.id + '">' + '000' +
                            data
                            .company_id + '-000' + data.business_unit_id + '-' +
                            data.id +
                            '</a>',
                            data.title,
                            data.type_title,
                            data.created_at,
                            (data.cus_id == null ? "-" : "AA" + data.cus_id),
                            (data.fname == null ? "-" : data.fname) + ' ' + (data.lname ==
                                null ? "" :
                                data.lname),
                            data.completed_date,
                            data.due_date,
                            data.type_title,
                            data.priority_title,
                            data.impact_title,
                            data.description,
                            data.status_title,
                            '<div class="dropdown"><a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="mdi mdi-dots-horizontal"></i> </a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" data-id=' +
                            data.id + ' href="Tickets/' + data.id + '">View</a>' +
                            '<a class="dropdown-item notify" onclick="copyToClipboard(' +
                            data
                            .id +
                            ')">Share</a></div></div>',
                            '<button type="button" id="edit" name="edit"  onclick="view(' +
                            data.id +
                            ')"  class="btn btn-soft-warning waves-effect waves-light"><i class="bx bx-edit-alt font-size-16 align-middle" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"></i></button>',
                            `<label class="switch">
    <input type="checkbox" ${data.active == 1 ? "checked" : ""} onchange="updatestatus(${data.id}, this)">
    <span class="slider round"></span>
</label>`,
                            // '<a type="button"id="edit" name="edit"  href="Tickets/' + data
                            // .id +
                            // '" target="_blank" class="btn btn-soft-success waves-effect waves-light"><i class="bx bxs-show font-size-16 align-middle"></i></a>',
                            // '<button type="button" id="delete" name="delete" onclick="deleteData(' +
                            // data.id +
                            // ')" class="btn btn-soft-danger waves-effect waves-light"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>'
                        ]).draw(false);
                    });

                });

            })

        }


        function getBu() {
            var settings = {
                "url": "{{ config('app.api_url') }}users/{{ Auth::user()->bu_id }}/{{ Auth::user()->designation_id }}",
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                g_id = response[0]['group_id'];
                var settings = {
                    "url": "{{ config('app.api_url') }}users_bu_group/" + g_id,
                    "method": "GET",
                    "timeout": 0,
                };
                $.ajax(settings).done(function(response) {
                    designationTitle = response[0]['title'];

                    $.ajax({
                        url: "{{ config('app.api_url') }}business_units/parent_bu/{{ Auth::user()->company_id }}/{{ Auth::user()->bu_id }}/" +
                            designationTitle + "",
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            bu.clearChoices(); // This line is causing the error
                            console.log("check" + response);
                            bu.setChoices(response, 'id', 'name', false);
                            setTimeout(function() {
                                bu.setChoiceByValue('{{ Auth::user()->bu_id }}');
                            }, 5000);
                            // Move the setChoiceByValue inside the success callback
                        }
                    });
                });
            });




        }

    } else {

        function getg_id() {
            var settings = {
                "url": "{{ config('app.api_url') }}users/{{ Auth::user()->bu_id }}/{{ Auth::user()->designation_id }}",
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                g_id = response[0]['group_id'];
                getgname(g_id);


            })

        }

        function getgname(id) {
            var settings = {
                "url": "{{ config('app.api_url') }}users_bu_group/" + id,
                "method": "GET",
                "timeout": 0,
            };
            $.ajax(settings).done(function(response) {

                designationTitle = response[0]['title'];
                // console.log("api/dashboard/index/{{ Auth::user()->company_id }}/" + designationTitle +
                //     "/{{ Auth::user()->bu_id }}/{{ Auth::user()->id }}")
                var settings = {
                    "url": "{{ config('app.api_url') }}dashboard/index/{{ Auth::user()->company_id }}/" +
                        designationTitle +
                        "/{{ Auth::user()->bu_id }}/{{ Auth::user()->id }}",
                    "method": "GET",
                    "timeout": 0,
                };

                $.ajax(settings).done(function(response) {
                    table.clear().draw();
                    $.each(response, function(index, data) {
                        table.row.add([
                            index + 1,
                            '<a href="Tickets/' + data.id + '">' + '000' +
                            data.company_id + '-000' + data.business_unit_id + '-' +
                            data.id +
                            '</a>',
                            data.title,
                            data.type_title,
                            data.created_at,
                            (data.cus_id == null ? "-" : "AA" + data.cus_id),
                            (data.fname == null ? "-" : data.fname) + ' ' + (data.lname ==
                                null ? "" :
                                data.lname),
                            data.completed_date,
                            data.due_date,
                            data.store_contact,
                            data.bu_name,
                            data.priority_title,
                            data.impact_title,
                            data.description,
                            data.status_title,
                            '<div class="dropdown"><a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="mdi mdi-dots-horizontal"></i> </a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" data-id=' +
                            data.id + ' href="Tickets/' + data.id + '">View</a>' +
                            '<a class="dropdown-item notify" onclick="copyToClipboard(' +
                            data
                            .id +
                            ')">Share</a></div></div>',
                            '<button type="button" id="edit" name="edit"  onclick="view(' +
                            data.id +
                            ')"  class="btn btn-soft-warning waves-effect waves-light"><i class="bx bx-edit-alt font-size-16 align-middle" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"></i></button>',
                            `<label class="switch">
    <input type="checkbox" ${data.active == 1 ? "checked" : ""} onchange="updatestatus(${data.id}, this)">
    <span class="slider round"></span>
</label>`,

                            // '<a type="button"id="edit" name="edit"  href="Tickets/' + data
                            // .id +
                            // '" target="_blank" class="btn btn-soft-success waves-effect waves-light"><i class="bx bxs-show font-size-16 align-middle"></i></a>',


                            // '<button type="button" id="delete" name="delete" onclick="deleteData(' +
                            // data.id +
                            // ')" class="btn btn-soft-danger waves-effect waves-light"><i class="bx bx-trash-alt font-size-16 align-middle"></i></button>'
                        ]).draw(false);
                    });

                });

            })

        }



        function getBu() {
            var settings = {
                "url": "{{ config('app.api_url') }}users/{{ Auth::user()->bu_id }}/{{ Auth::user()->designation_id }}",
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                g_id = response[0]['group_id'];
                var settings = {
                    "url": "{{ config('app.api_url') }}users_bu_group/" + g_id,
                    "method": "GET",
                    "timeout": 0,
                };
                $.ajax(settings).done(function(response) {
                    designationTitle = response[0]['title'];


                    $.ajax({
                        url: "{{ config('app.api_url') }}business_units/parent_bu/{{ Auth::user()->company_id }}/{{ Auth::user()->bu_id }}/" +
                            designationTitle + "",
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {

                            bu.clearChoices(); // This line is causing the error
                            console.log("check" + response);
                            bu.setChoices(response, 'id', 'name', false);
                            setTimeout(function() {
                                bu.setChoiceByValue('{{ Auth::user()->bu_id }}');
                            }, 5000);
                        }



                    });




                })



            })


        }


    }







    // function addtional_fields(id) {
    //     bu_wise(id)
    //     $('#newfields').empty()
    //     console.log("id" + id);
    //     var settings = {
    //         "url": "{{ config('app.api_url') }}tickets/bu_fields/" + id + "",
    //         "method": "GET",
    //         "timeout": 0,
    //     };

    //     $.ajax(settings).done(function(response) {
    //         console.log(response);
    //         console.log("id" + id);
    //         // alert("id" + id)
    //         for (i = 0; i < response.length; i++) {
    //             console.log(response[i]['name']);
    //             var field_type;
    //             if (response[i]['type'] == "Text") {
    //                 field_type = "text";
    //                 var newrow =
    //                     '<div class="col-6">' +
    //                     ' <label for="formrow-inputState" class="form-label">' + response[i][
    //                         'name'
    //                     ] +
    //                     '</label>' +
    //                     ' <input name="name2[]" class="form-control multi_text" id="' + i +
    //                     '" data-id="' +
    //                     response[i]
    //                     ['id'] + '" type="' + field_type + '" placeholder="' + response[i][
    //                         'name'
    //                     ] +
    //                     '"></div>';

    //             } else if (response[i]['type'] == "String") {
    //                 field_type = "text";
    //                 var newrow =
    //                     '<div class="col-6">' +
    //                     ' <label for="formrow-inputState" class="form-label">' + response[i][
    //                         'name'
    //                     ] +
    //                     '</label>' +
    //                     ' <input name="name2[]" class="form-control multi_text" id="' + i +
    //                     '" data-id="' +
    //                     response[i]
    //                     ['id'] + '" type="' + field_type + '" placeholder="' + response[i][
    //                         'name'
    //                     ] +
    //                     '"></div>';

    //             } else if (response[i]['type'] == "Number") {
    //                 field_type = "number";
    //                 var newrow =
    //                     '<div class="col-6">' +
    //                     ' <label for="formrow-inputState" class="form-label">' + response[i][
    //                         'name'
    //                     ] +
    //                     '</label>' +
    //                     ' <input name="name2[]" class="form-control multi_text" id="' + i +
    //                     '" data-id="' +
    //                     response[i]
    //                     ['id'] + '"  type="' + field_type + '" placeholder="' + response[i][
    //                         'name'
    //                     ] +
    //                     '"></div>';

    //             } else if (response[i]['type'] == "Boolean") {
    //                 field_type = "Select";
    //                 var newrow =
    //                     '<div class="col-6">' +
    //                     '<label for="formrow-inputState" class="form-label">' + response[i][
    //                         'name'
    //                     ] +
    //                     '</label>' +
    //                     '<select name="name2[]" id="' + i + '" data-id="' + response[i]['id'] +
    //                     '" class="form-control multi_text" placeholder="' + response[i][
    //                         'name'
    //                     ] + '">' +
    //                     '<option value="1">Yes</option>' +
    //                     '<option value="0">No</option>' +
    //                     '</select></div>'
    //             } else {
    //                 field_type = "text";
    //                 var newrow =
    //                     '<div class="col-6">' +
    //                     ' <label for="formrow-inputState" class="form-label">' + response[i][
    //                         'name'
    //                     ] +
    //                     '</label>' +
    //                     ' <input name="name2[]" class="form-control multi_text" id="' + i +
    //                     '" data-id="' +
    //                     response[i]
    //                     ['id'] + '"  type="' + field_type + '" placeholder="' + response[i][
    //                         'name'
    //                     ] +
    //                     '"></div>';

    //             }
    //             //   document.getElementById('modalbody').innerHTML(newrow);
    //             $('#newfields').append(newrow)
    //         }
    //     });
    // }

    function addtional_fields(id) {
    console.log("addtional_fields called with id:", id);
    
    if(!id || id === "") {
        console.log("No BU ID provided");
        return;
    }
    
    bu_wise(id);
    $('#newfields').empty();
    
    var settings = {
        "url": "{{ config('app.api_url') }}tickets/bu_fields/" + id,
        "method": "GET",
        "timeout": 0,
    };
    
    $.ajax(settings).done(function(response) {
        console.log("BU Fields response:", response);
        
        if(response && response.length > 0) {
            for(var i = 0; i < response.length; i++) {
                var field_type;
                var newrow = '';
                
                if(response[i]['type'] == "Text" || response[i]['type'] == "String") {
                    newrow = '<div class="col-6">' +
                        '<label class="form-label">' + response[i]['name'] + '</label>' +
                        '<input name="name2[]" class="form-control multi_text" id="field_' + i + 
                        '" data-id="' + response[i]['id'] + '" type="text" placeholder="' + 
                        response[i]['name'] + '">' +
                        '</div>';
                } else if(response[i]['type'] == "Number") {
                    newrow = '<div class="col-6">' +
                        '<label class="form-label">' + response[i]['name'] + '</label>' +
                        '<input name="name2[]" class="form-control multi_text" id="field_' + i + 
                        '" data-id="' + response[i]['id'] + '" type="number" placeholder="' + 
                        response[i]['name'] + '">' +
                        '</div>';
                } else if(response[i]['type'] == "Boolean") {
                    newrow = '<div class="col-6">' +
                        '<label class="form-label">' + response[i]['name'] + '</label>' +
                        '<select name="name2[]" id="field_' + i + '" data-id="' + response[i]['id'] + 
                        '" class="form-control multi_text">' +
                        '<option value="1">Yes</option>' +
                        '<option value="0">No</option>' +
                        '</select></div>';
                }
                
                $('#newfields').append(newrow);
            }
        }
    }).fail(function(error) {
        console.error("BU Fields API error:", error);
    });
}


    function view(id) {
        $.ajax({
            url: '{{ config('app.api_url') }}tickets/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("resp", response);
                $('#update_id').val(response[0]['id']);
                assigned.setChoiceByValue(response[0]['assigned_to']);
                reported.setChoiceByValue(response[0]['reported_by']);
                $('#ticket_no').val(response[0]['id']);
                $('#title').val(response[0]['title']);
                $('#moc').val(response[0]['mode_of_complaint']);
                type1.setChoiceByValue(response[0]['id']);
                ticketresolve.setChoiceByValue(response[0]['completed_by']);
                subtype.setChoiceByValue(response[0]['sub_type_id']);
                $('#resolved').val(response[0]['completed_date']);
                status1.setChoiceByValue(response[0]['status_id']);
                priority.setChoiceByValue(response[0]['priority_id']);
                bu.setChoiceByValue(response[0]['business_unit_id']);
                customer.setChoiceByValue(response[0]['customer_id'])
                $('#due_date').val(response[0]['due_date']);
                $('#store_info').val(response[0]['store_contact']);
                impact.setChoiceByValue(response[0]['impact_id']);
                $('#description').val(response[0]['description']);
                const offcanvas = new bootstrap.Offcanvas(document.getElementById("createOffcanvas"));
                offcanvas.show();

            }
        })
    }

    function copyToClipboard(text) {
        // Create a temporary textarea element
        var $tempTextarea = $('<textarea>');
        var pageUrl = window.location.href;
        var urlBeforeFirstSlash = pageUrl.split('/')[2];
        // Set the value of the textarea
        $tempTextarea.val(urlBeforeFirstSlash + '/Tickets/' + text);

        // Append the textarea to the body
        $('body').append($tempTextarea);

        // Select the text in the textarea
        $tempTextarea.select();

        // Copy the selected text to the clipboard
        document.execCommand('copy');

        // Remove the temporary textarea
        $tempTextarea.remove();

        alert('Link Copied!');
    }

    function updatestatus(id) {
        $('#updatestatuss').modal('show')
        var settings = {
            "url": "{{ Auth::user()->company_id == 0 }}" ?
                "{{ config('app.api_url') }}status" : "{{ config('app.api_url') }}status/" + id,
            "method": "GET",
            "timeout": 0,
        };

        $.ajax(settings).done(function(response) {})

    }


    function updatestatus(id, element) {
        var isChecked = $(element).prop('checked') ? 1 : 0;

        var form = new FormData();
        form.append("id", id);
        form.append("active", isChecked);

        $.ajax({
            url: "{{ config('app.api_url') }}updatetickstatus",
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            mimeType: "multipart/form-data",
            timeout: 0,
            success: function(response) {
                Swal.fire(
                    'Success!',
                    'Status updated successfully',
                    'success'
                );
                getg_id();


            },
            error: function(xhr, textStatus, errorThrown) {
                Swal.fire(
                    'Server Error!',
                    'Failed to update status',
                    'error'
                );
                // console.error("Request failed:", xhr.status, textStatus, errorThrown);
            }
        });
    }


    $('#applyfilt').click(function() {
        const priority = $('#Priorityfil').val();
        const impact = $('#impactfil').val();
        const type = $('#typefil').val();
        const assign = $('#agent').val();
        let filtered = [];

        const userId = '{{ Auth::user()->id }}';

        if (assign === 'Created_By_Me') {
            filtered = allrecord.filter(ticket =>
                (!priority || ticket.priority_id == priority) &&
                (!impact || ticket.impact_id == impact) &&
                (!type || ticket.vendor_type_id == type) &&
                (ticket.created_by == userId)
            );
        } else if (assign === 'assign_to_me') {
            filtered = allrecord.filter(ticket =>
                (!priority || ticket.priority_id == priority) &&
                (!impact || ticket.impact_id == impact) &&
                (!type || ticket.vendor_type_id == type) &&
                (ticket.assigned_to == userId)
            );
        } else if (assign === 'assign_by_me') {
            filtered = allrecord.filter(ticket =>
                (!priority || ticket.priority_id == priority) &&
                (!impact || ticket.impact_id == impact) &&
                (!type || ticket.vendor_type_id == type) &&
                (ticket.reported_by == userId)
            );
        } else {
            filtered = allrecord.filter(ticket =>
                (!priority || ticket.priority_id == priority) &&
                (!impact || ticket.impact_id == impact) &&
                (!type || ticket.vendor_type_id == type)
            );
        }

        // ✅ Count status wise
        const statusCounts = {
            Open: 0,
            Closed: 0,
            Pending: 0,
            Completed: 0
        };
        filtered.forEach(ticket => {
            const status = ticket.status_title?.toLowerCase();
            if (status === 'open') statusCounts.Open++;
            else if (status === 'closed') statusCounts.Closed++;
            else if (status === 'pending') statusCounts.Pending++;
            else if (status === 'completed') statusCounts.Completed++;
        });

        const totalTickets = filtered.length;

        // ✅ Update status count in HTML (adjust IDs according to your HTML)
        $('#open_tickets').text(statusCounts.Open);
        $('#closed_tickets').text(statusCounts.Closed);
        $('#pending_tickets').text(statusCounts.Pending);
        $('#completed_count').text(statusCounts.Completed);
        $('#total_tickets').text(totalTickets);

        // ✅ Update existing DataTable rows without reinitializing
        const table = $('#types-table').DataTable();
        table.clear().rows.add(filtered).draw();


    });


    function renderResults(data) {
        let html = '';
        if (data.length === 0) {
            html = '<p>No matching records.</p>';
        } else {
            html = '<ul>';
            data.forEach(ticket => {
                html +=
                    `<li><strong>${ticket.title}</strong> - ${ticket.priority_title} - ${ticket.impact_title} - Assigned to ${ticket.assigned_to}</li>`;
            });
            html += '</ul>';
        }
        $('#result').html(html);
    }


    function status(filterType) {
        const statusMap = {
            open_tickets: 'open',
            resolved_tickets: 'completed',
            pending_tickets: 'pending',
            closed_tickets: 'closed',
            all_tickets: 'all'
        };

        const normalizedType = statusMap[filterType];
        let filtered = allrecord;
        if (normalizedType !== 'all') {
            filtered = allrecord.filter(ticket => ticket.status_title?.toLowerCase() === normalizedType);
        }

        const statusCounts = {
            open: 0,
            closed: 0,
            pending: 0,
            completed: 0
        };

        allrecord.forEach(ticket => {
            const status = ticket.status_title?.toLowerCase();
            if (statusCounts.hasOwnProperty(status)) {
                statusCounts[status]++;
            }
        });

        if (normalizedType === 'all') {
            // Show full counts
            $('#open_tickets').text(statusCounts.open);
            $('#closed_tickets').text(statusCounts.closed);
            $('#pending_tickets').text(statusCounts.pending);
            $('#completed_count').text(statusCounts.completed);
            $('#total_tickets').text(allrecord.length);
        } else {
            // Show only selected count, others zero
            $('#open_tickets').text(normalizedType === 'open' ? statusCounts.open : 0);
            $('#closed_tickets').text(normalizedType === 'closed' ? statusCounts.closed : 0);
            $('#pending_tickets').text(normalizedType === 'pending' ? statusCounts.pending : 0);
            $('#completed_count').text(normalizedType === 'completed' ? statusCounts.completed : 0);
            $('#total_tickets').text(filtered.length);
        }

        // Reload DataTable
        const table = $('#types-table').DataTable();
        table.clear().rows.add(filtered).draw();
    }
</script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection