@extends('layouts.master')
@section('title') @lang('translation.datatables')
@endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

<style>
    .nav-tabs {
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .nav-tabs .nav-item {
        position: relative;
        margin: 0 5px;
    }

    .nav-tabs .nav-link {
        border: none;
        padding: 12px 24px;
        font-weight: 500;
        color: #6c757d;
        background: transparent;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 4px 4px 0 0;
    }

    .nav-tabs .nav-link:hover {
        color: #5D87FF;
        background: rgba(93, 135, 255, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: #5D87FF;
        font-weight: 600;
    }

    .nav-tabs .nav-link::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 3px;
        background: #5D87FF;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-tabs .nav-link.active::after {
        transform: scaleX(1);
    }

    .nav-tabs .nav-link:focus:not(.active)::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(93, 135, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        transition: transform 0.5s ease-out;
    }

    .nav-tabs .nav-link:focus:not(.active)::before {
        transform: translate(-50%, -50%) scale(15);
        opacity: 0;
    }

    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .form-select-sm {
        padding: 8px 12px;
        border-radius: 8px;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .recent-tickets-container {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-top: 20px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .view-all {
        font-size: 0.85rem;
        color: #5D87FF;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .view-all:hover {
        color: #3a6af0;
        text-decoration: underline;
    }

    .ticket-cards {
        display: grid;
        gap: 15px;
    }

    .ticket-card {
        background: #fff;
        border-radius: 10px;
        padding: 16px;
        border-left: 4px solid;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .ticket-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    }

    .ticket-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.8rem;
    }

    .ticket-id {
        color: #6c757d;
        font-weight: 500;
    }

    .ticket-status {
        font-weight: 500;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
    }

    .status-icon {
        font-size: 0.5rem;
        margin-right: 6px;
    }

    .ticket-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .ticket-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 0.8rem;
    }

    .meta-item {
        color: #6c757d;
        display: flex;
        align-items: center;
    }

    .ticket-priority {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }

    .status-closed {
        border-left-color: #f46a6a;
    }

    .status-closed .status-icon {
        color: #f46a6a;
    }

    .status-open {
        border-left-color: #34c38f;
    }

    .status-open .status-icon {
        color: #34c38f;
    }

    .status-pending {
        border-left-color: #f1b44c;
    }

    .status-pending .status-icon {
        color: #f1b44c;
    }

    .priority-high {
        background-color: rgba(244, 106, 106, 0.1);
        color: #f46a6a;
    }

    .priority-medium {
        background-color: rgba(241, 180, 76, 0.1);
        color: #f1b44c;
    }

    .priority-low {
        background-color: rgba(52, 195, 143, 0.1);
        color: #34c38f;
    }

    #basic_polar_area {
        width: 100%;
        margin: 0 auto;
    }

    [data-bs-theme="dark"] #basic_polar_area .apexcharts-text,
    [data-bs-theme="dark"] #basic_polar_area .apexcharts-legend-text {
        fill: #e9ecef !important;
    }

    [data-bs-theme="dark"] #basic_polar_area .apexcharts-gridline {
        stroke: #495057 !important;
    }

    .offcanvas-header {
        border-bottom: 1px solid var(--bs-border-color);
    }

    .offcanvas-body {
        padding-top: 0;
    }

    .reply-editor-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 10px 15px;
        background-color: var(--bs-light);
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .reply-editor-toolbar .btn {
        padding: 5px 8px;
        font-size: 0.85rem;
    }

    .reply-editor-textarea {
        border: 1px solid var(--bs-border-color);
        border-top: none;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
        padding: 15px;
        resize: vertical;
        min-height: 150px;
        font-size: 0.9rem;
        line-height: 1.5;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .reply-editor-textarea:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }

    .reply-attachments {
        margin-top: 15px;
        padding: 10px 15px;
        background-color: var(--bs-light);
        border-radius: 6px;
    }

    .reply-message {
        margin-top: 20px;
        border-left: 4px solid #0d6efd;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        padding: 15px;
    }

    .reply-message .reply-header {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .reply-message .reply-content {
        font-size: 0.9rem;
        line-height: 1.6;
        color: var(--bs-body-color);
    }

    .upcoming-schedule-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 20px;
        font-family: Arial, sans-serif;
        color: #333;
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .schedule-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .view-more {
        text-decoration: none;
        color: #007bff;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
    }

    .view-more i {
        margin-left: 5px;
        font-size: 0.7rem;
    }

    .schedule-items {
        padding-top: 5px;
    }

    .schedule-item {
        display: flex;
        border-bottom: 1px solid #f0f0f0;
        padding: 10px 0;
    }

    .schedule-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .schedule-datetime {
        flex-basis: 90px;
        min-width: 90px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding-right: 15px;
        border-right: 1px solid #f0f0f0;
        text-align: left;
    }

    .schedule-date {
        font-size: 0.85rem;
        font-weight: 500;
        color: #555;
        margin-bottom: 3px;
    }

    .schedule-time {
        font-size: 0.95rem;
        font-weight: bold;
        color: #333;
    }

    .schedule-content {
        flex-grow: 1;
        padding-left: 15px;
    }

    .schedule-event-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        margin-top: 0;
        margin-bottom: 3px;
    }

    .schedule-event-description {
        font-size: 0.75rem;
        color: #777;
        margin: 0;
        line-height: 1.3;
    }

    .d-grid {
        display: grid;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        border-radius: .25rem;
        /* transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out; */
    }

    .me-2 {
        margin-right: .5rem !important;
    }

    .offcanvas-bottom {
        height: 85vh !important;
        max-height: 85vh !important;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .offcanvas-header {
        border-bottom: 1px solid var(--bs-border-color);
        flex-shrink: 0;
    }

    .offcanvas-body {
        overflow-y: auto;
        padding: 1rem;
    }

    @media (max-width: 576px) {
        .offcanvas-bottom {
            height: 90vh !important;
            max-height: 90vh !important;
        }
    }

    .file-link:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    #fileList .btn {
        white-space: nowrap;
    }

    #fileList .d-flex:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title')
{{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} View
@endslot
@endcomponent

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-8">
            <div class="card border-1 shadow-sm">
                <div class="card-body p-4">
                    <form>
                        <div class="mb-4">
                            <ul class="nav nav-tabs nav-justified border-bottom-0" id="ticketTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="detail-tab" data-bs-toggle="tab"
                                        data-bs-target="#detail-tab-pane" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>Detail
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#qutation-tab" type="button" role="tab">
                                        <i class="fas fa-user me-2"></i>Qutation
                                    </button>
                                </li>
                                <!-- <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#contact-tab-pane" type="button" role="tab">
                                        <i class="fas fa-user me-2"></i>My Tickets
                                    </button>
                                </li> -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab"
                                        data-bs-target="#activity-tab-pane" type="button" role="tab">
                                        <i class="fas fa-chart-line me-2"></i>Activity
                                    </button>
                                </li>

                            </ul>
                        </div>


                        <div class="tab-content" id="ticketTabContent">
                            <div class="tab-pane fade show active" id="detail-tab-pane" role="tabpanel">
                                <form id="ticketForm">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="title" class="form-label">Title*</label>
                                            <input type="text" class="form-control" id="title"
                                                placeholder="Enter ticket title" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="created_by" class="form-label">
                                            {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Created By
                                            </label>

                                            <select class="form-control" id="created_by" placeholder="This is a search placeholder">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="assigned_to" class="form-label">Task Assigned To</label>
                                            <select class="form-control" id="assigned_to" placeholder="This is a search placeholder">

                                            </select>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="reported_by" class="form-label">
                                            {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Reported By
                                            </label>
                                            <select class="form-control" id="reported_by" placeholder="This is a search placeholder">

                                            </select>

                                        </div>
                                        <div class="col-md-4">
                                            <label for="description" class="form-label">Description*</label>
                                            <textarea class="form-control" id="description" rows="1"
                                                placeholder="Describe the problem" required></textarea>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <label for="mode_of_complaint" class="form-label">Mode of Complaint</label>
                                            <select class="form-control" id="mode_of_complaint">
                                                <option value="">Select Mode</option>
                                                <option value="Email">Email</option>
                                                <option value="Phone">Phone</option>
                                                <option value="In-Person">In-Person</option>
                                                <option value="System">System</option>
                                            </select>
                                        </div> -->
                                        <div class="col-md-4">
                                            <label for="mode_of_complaint" class="form-label">Mode of Complaint</label>
                                            <input type="text" class="form-control" id="mode_of_complaint">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="sub_type_id" class="form-label">Issue Sub Type</label>
                                            <select class="form-control" id="sub_type_id" placeholder="This is a search placeholder">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="completed_by" class="form-label">
                                            {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Resolved By
                                            </label>
                                            <select class="form-control" id="completed_by" placeholder="This is a search placeholder">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="status_id" class="form-label">Status</label>

                                            <select class="form-control" id="status_id" placeholder="This is a search placeholder">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">
                                            {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Resolved Date
                                            </label>
                                            <input type="text" class="form-control" data-provider="flatpickr"
                                                data-date-format="d M, Y" id="completed_date">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="priority_id" class="form-label">Priority</label>
                                            <select class="form-control" id="priority_id" placeholder="This is a search placeholder">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="business_unit_id" class="form-label">BU/Store Name</label>
                                            <select class="form-control" id="business_unit_id" onchange="bu_wise(this.value)" placeholder="This is a search placeholder">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="customer_col" style="display: none;">
                                        <div class="mb-3 row">
                                            <label for="formrow-inputState" class="form-label">Account</label>
                                            <div class="col-md-12">
                                                <select class="form-control" id="customer" placeholder="This is a search placeholder">

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">
                                            {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Due Date
                                            </label>
                                            <input type="text" class="form-control" data-provider="flatpickr"
                                                data-date-format="d M, Y" id="due_date">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="impact_id" class="form-label">Impact</label>
                                            <select class="form-control" id="impact_id" placeholder="This is a search placeholder">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="store_contact" class="form-label">Store Contact</label>
                                            <input type="text" class="form-control" id="store_contact"
                                                placeholder="Enter store">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="company_id" class="form-label">Company</label>
                                            <input type="text" class="form-control" id="company_id"
                                                placeholder="Enter company">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="vendor_id" class="form-label">Vendor</label>
                                            <input type="text" class="form-control" id="vendor_id"
                                                placeholder="Enter vendor">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="vendor_type_id" class="form-label">Vendor Type</label>
                                            <input type="text" class="form-control" id="vendor_type_id"
                                                placeholder="Enter vendor type">
                                        </div>
                                    </div>

                                    <!-- <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="assigned_type" class="form-label">Assigned Type</label>
                                                                <input type="text" class="form-control" id="assigned_type"
                                                                    placeholder="Enter assigned type">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="profile" class="form-label">Profile</label>
                                                                <input type="text" class="form-control" id="profile"
                                                                    placeholder="Enter profile">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="customer_id" class="form-label">Customer ID</label>
                                                                <input type="text" class="form-control" id="customer_id"
                                                                    placeholder="Enter customer ID">
                                                            </div>
                                                        </div> -->

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label d-block">View Files</label>
                                            <button type="button" class="btn btn-light bg-gradient-dark mb-2 w-100"
                                                id="viewFilesBtn">
                                                <i class="bi bi-folder2-open me-2"></i>Files
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="fileListContainer" style="display: none;">
                                        <div class="col-md-12">
                                            <div id="fileList"></div>
                                        </div>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="button" class="btn btn-primary rounded-pill py-2 shadow-sm"
                                            id="createTicketBtn" onclick="update()">
                                            <i class="fas fa-save me-2"></i>Update {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }}
                                        </button>
                                    </div>
                            </div>

                            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel">
                                <div class="card mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="card-title mb-0 fw-semibold">Ticket Status Distribution</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="basic_polar_area" style="min-height: 250px;"></div>
                                    </div>
                                </div>

                                <div class="upcoming-schedule-container">
                                    <div class="schedule-header">
                                        <h5 class="schedule-title">Recent {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }}</h5>
                                        <a href="#" class="view-more">View More <i
                                                class="fas fa-chevron-right ms-1"></i></a>
                                    </div>

                                    <div class="schedule-items1">
                                        <!-- <div class="schedule-item">
                                            <div class="schedule-datetime">
                                                <span class="schedule-date">Tue, 20 Feb</span>
                                                <span class="schedule-time">09:19 PM</span>
                                            </div>
                                            <div class="schedule-content">
                                                <h6 class="schedule-event-title">Marketing Policy Meetings</h6>
                                                <p class="schedule-event-description">This is a periodic meeting
                                                    betwe...
                                                </p>
                                            </div>
                                        </div> -->


                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 shadow-sm">
                                        <i class="fas fa-save me-2"></i>Update {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }}
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="activity-tab-pane" role="tabpanel">
                                <div class="upcoming-schedule-container">
                                    <div class="schedule-header">
                                        <h5 class="schedule-title">{{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Activity Timeline</h5>
                                    </div>

                                    <div class="schedule-items2">

                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 shadow-sm">
                                        <i class="fas fa-save me-2"></i>Update {{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Tab Container -->
                            <div class="tab-pane fade" id="qutation-tab" role="tabpanel">
                                <!-- Tabs Navigation -->
                                <ul class="nav nav-tabs mb-3" id="ticketTabNav1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#allquote" type="button" role="tab" aria-controls="allquote" aria-selected="true">
                                            <i class="fas fa-chart-line me-2"></i>Qutations
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#vendors_list" type="button" role="tab" aria-controls="vendors_list" aria-selected="false">
                                            <i class="fas fa-user me-2"></i>Vendors List
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tabs Content -->
                                <div class="tab-content" id="ticketTabContent1">
                                    <!-- Quotations Tab -->
                                    <div class="tab-pane fade show active" id="allquote" role="tabpanel" aria-labelledby="activity-tab">
                                        <div class="card mb-4">

                                            <div class="card-body p-0" style="overflow-x: scroll;">
                                                <table id="quote-table" class="table wrap align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>{{ optional(session('userWithBU'))->ticket_represented ?? 'Ticket' }} Id</th>
                                                            <th>Vendor</th>
                                                            <th>Amount</th>
                                                            <th>Approve</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vendors List Tab -->
                                    <div class="tab-pane fade" id="vendors_list" role="tabpanel" aria-labelledby="contact-tab">
                                        <div class="card mb-4">

                                            <div class="card-body p-0" style="overflow-x: scroll;">
                                                <table id="types-table" class="table wrap align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Phone</th>
                                                            <th>Company</th>
                                                            <th>BU</th>
                                                            <th>Request</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="border-radius: 6px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <!-- <button class="btn btn-outline-primary btn-md px-3 py-1" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#replyOffcanvas" aria-controls="replyOffcanvas">
                            <i class="fas fa-reply me-1"></i> Reply
                        </button>
                        <button class="btn btn-outline-secondary btn-md px-3 py-1">
                            <i class="fas fa-lock me-1"></i> Closed
                        </button> -->
                    </div>
                </div>
                <div class="card-body" id="ticket-conversation">
    <h5 class="mb-3" id="ticket_title"></h5>
    <p class="text-muted mb-4">Proposed On <span id="ticket_createdat"></span></p>
    <hr>

    <!-- original message -->
    <div class="mb-4 original-message-container">
        <h6 class="mb-2" id ="ticket_reported_by"></h6>

    </div>

 <!-- replies -->
<div id="replies-container"
     class="mt-4"
     style="max-height: 400px; height:300px;background:#E5E4E2; overflow-y: auto;">
</div>

    <div class="d-flex justify-content-start mt-4">
        <button class="btn btn-outline-primary btn-md px-3 py-1 me-2" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#replyOffcanvas" aria-controls="replyOffcanvas">
            <i class="fas fa-reply me-1"></i> Reply
        </button>
        <a href="ticket" class="btn btn-outline-secondary btn-md px-3 py-1">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="replyOffcanvas" aria-labelledby="replyOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 id="replyOffcanvasLabel">Reply to Ticket</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-3">
                <span class="text-muted d-block mb-2">To: <span id="message_reported"></span></span>
                <div class="card p-0 border rounded-lg">
                    <div class="ckeditor-classic" id="replyCkeditor"></div>
                </div>
            </div>

            <!-- <div class="mb-3">
                <input type="file" id="replyAttachment" style="display: none;" multiple>
                <label for="replyAttachment" class="btn btn-link p-0 text-decoration-none">
                    <i class="fas fa-upload me-1"></i> Upload File
                </label>
                <div id="uploaded-files-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div> -->

            <div class="d-flex justify-content-start align-items-center mt-4 gap-3">
                <button type="button" class="btn btn-primary" id="submitReplyBtn">
                    <i class="fas fa-paper-plane me-1"></i> Submit
                </button>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="applyTemplateDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-magic me-1"></i> Apply Template
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="applyTemplateDropdown">
                        <li><a class="dropdown-item" href="#"
                                data-template="Thank you for contacting support. We are investigating your issue and will get back to you shortly.">General
                                Acknowledgment</a></li>
                        <li><a class="dropdown-item" href="#"
                                data-template="We have resolved your issue. Please let us know if you have any further questions.">Resolution
                                Confirmation</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="offcanvas">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            </div>
        </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var assigned, reported, marketing, manager, ticketresolved;
    var subtype, mytickets;
    var resolved, created_by, table, due_date, type1, status1, impact, priority, bu, tid, customer, upstatus;
    
    const polarAreaElement = document.querySelector("#basic_polar_area");
    let polarChart = null;
    $(document).ready(async () => {
        let urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get('id');
        let tid = urlParams.get('t');
        quotetf(id)
        vendors(tid)
        table = $('#types-table').DataTable({
            dom: 'rtip'
        });
        quotet = $('#quote-table').DataTable({
            dom: 'rtip'
        });

        await $.ajax({
            url: "{{ config('app.api_url') }}tickets/activity/" + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                response.forEach((r, index) => {
                    const html = `
                <div class="schedule-item">
                    <div class="schedule-datetime">
                        <span class="schedule-date">${formatDateTime(r.created_at)}</span>
                    </div>
                    <div class="schedule-content">
                        <h6 class="schedule-event-title">${r.remarks}</h6>
                    </div>
                </div>
            `;

                    // Append to schedule-items1 only for first 4
                    if (index < 4) {
                        $('.schedule-items1').append(html);
                    }

                    // Append all to schedule-items2
                    $('.schedule-items2').append(html);
                });
            }
        });

        await $.ajax({
            url:  "{{Auth::user()->company_id}}"==0 ?"{{ config('app.api_url') }}business_units":"{{ config('app.api_url') }}business_units/company/{{Auth::user()->company_id}}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                bu = new Choices("#business_unit_id", {
                    removeItemButton: !0,
                })
                bu.clearChoices(); // This line is causing the error
                console.log("check" + response);
                bu.setChoices(response, 'id', 'name', false);
                setTimeout(function() {
                    bu.setChoiceByValue('{{ Auth::user()->bu_id }}');
                }, 5000);

                // Move the setChoiceByValue inside the success callback
            }
        });

        await $.ajax({
            url: ("{{ Auth::user()->company_id == 0 }}" ?
                "{{ config('app.api_url') }}users/super_admin_users" :
                "{{ config('app.api_url') }}users/company/{{ Auth::user()->company_id }}"
            ),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("resspp", response);
                assigned = new Choices("#assigned_to", {
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

                reported = new Choices("#reported_by", {
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

                created_by = new Choices("#created_by", {
                    removeItemButton: !0,
                })
                created_by.clearChoices();
                console.log(response);
                created_by.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Properly formatting the label
                    })),
                    'value',
                    'label',
                    false
                );

                ticketresolved = new Choices("#completed_by", {
                    removeItemButton: !0,
                })
                ticketresolved.clearChoices();
                console.log(response);
                ticketresolved.setChoices(
                    response.map(item => ({
                        value: item.id,
                        label: `${item.u_name} - ${item.email}` // Ensuring 'u_name' is correctly displayed
                    })),
                    'value',
                    'label',
                    false
                );

                // ticketresolved.setChoiceByValue(748);

            }
        });



        await $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}status" :
            //     "{{ config('app.api_url') }}status/company/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}status",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                status1 = new Choices("#status_id", {
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

        await $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}priority" :
            //     "{{ config('app.api_url') }}priority/company/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}priority",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                priority = new Choices("#priority_id", {
                    removeItemButton: !0,
                })
                priority.clearChoices();
                console.log(response);
                priority.setChoices(response,
                    'id',
                    'title',
                    false, );
            }

        });

        await $.ajax({
            // url: ("{{ Auth::user()->company_id == 0 }}" ?
            //     "{{ config('app.api_url') }}impacts" :
            //     "{{ config('app.api_url') }}impacts/company/{{ Auth::user()->company_id }}"
            // ),
            url: "{{ config('app.api_url') }}impacts",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                impact = new Choices("#impact_id", {
                    removeItemButton: !0,
                })
                impact.clearChoices();
                console.log(response);
                impact.setChoices(response,
                    'id',
                    'title',
                    false, );


            }
        });
        subtype = new Choices("#sub_type_id", {
            removeItemButton: !0,
        })
        await $.ajax({
            // url:"{{Auth::user()->company_id}}"==0 ? "{{ config('app.api_url') }}types":"{{ config('app.api_url') }}companytype/{{Auth::user()->company_id}}",
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


        edit()
        loadReplies();

    });


    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('createTicketBtn').addEventListener('click', createTicket);

        if (typeof flatpickr !== 'undefined') {
            flatpickr("[data-provider='flatpickr']", {
                dateFormat: "d M, Y"
            });
        }
    });

    function edit() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        console.log('id:', id);

        if (!id) {
            console.error("ID is missing from URL.");
            return;
        }

        $.ajax({
            url: `{{ config('app.api_url') }}tickets/${id}`,
            method: "GET",
            success: function(data) {
                console.log('API Response:', data);
                let ticket = Array.isArray(data) ? data[0] : data.data || data.ticket || data;

                if (!ticket) {
                    console.error("Ticket not found.");
                    return;
                }


                $('#title').val(ticket.title);
                $('#ticket_title').text(ticket.title);
                $('#ticket_createdat').text(ticket.created_at);
                $('#ticket_reported_by').text(ticket.reported_by_name);
                $('#message_reported').text(ticket.reported_by_name);
                try {
                    bu.setChoiceByValue(ticket.business_unit_id);
                } catch (e) {}
                try {
                    assigned.setChoiceByValue(ticket.assigned_to);
                } catch (e) {}
                try {
                    reported.setChoiceByValue(ticket.reported_by);
                } catch (e) {}
                try {
                    ticketresolved.setChoiceByValue(ticket.completed_by);
                } catch (e) {}
                try {
                    status1.setChoiceByValue(ticket.status_id);
                } catch (e) {}
                try {
                    priority.setChoiceByValue(ticket.priority_id);
                } catch (e) {}
                try {
                    impact.setChoiceByValue(ticket.impact_id);
                } catch (e) {}
                try {
                    subtype.setChoiceByValue(ticket.sub_type_id);
                } catch (e) {}
                try {
                    created_by.setChoiceByValue(ticket.created_by);
                } catch (e) {}

                $('#created_by').val(ticket.reported_by_namme || '');
                $('#description').val(ticket.description || '');
                $('#mode_of_complaint').val(ticket.mode_of_complaint || '');
                $('#completed_date').val(ticket.completed_date || '');
                $('#due_date').val(ticket.due_date || '');
                $('#store_contact').val(ticket.store_contact || '');
                $('#vendor_id').val(ticket.vendor_id || '');
                $('#vendor_type_id').val(ticket.vendor_type_id || '');
                $('#company_id').val(ticket.company_name || '');
                renderFileCard(ticket.file)
                const requiredStatuses = ["Open", "Pending", "Closed", "Completed"];
                const statusMap = {};

                // Count actual statuses from data
                mytickets.forEach(ticket => {
                    const status = ticket.status_title || 'Unknown';
                    if (requiredStatuses.includes(status)) {
                        statusMap[status] = (statusMap[status] || 0) + 1;
                    }
                });

                // Ensure all 4 required statuses are present
                const result = requiredStatuses.map(status => ({
                    status: status,
                    count: statusMap[status] || 0
                }));
                initChartFromStatusData(result);
                console.log("status", result)

            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
                alert("Failed to load ticket. Please check console.");
            }
        });
    }


    const defaultFiles = [{
            name: "Ticket_Report_123.pdf",
            type: "PDF",
            size: "1.2 MB"
        },
        {
            name: "Issue_Image_456.png",
            type: "Image",
            size: "750 KB"
        },
        {
            name: "Resolution_Notes.docx",
            type: "Document",
            size: "980 KB"
        },
        {
            name: "System_Logs.txt",
            type: "Text",
            size: "410 KB"
        }
    ];

    const fileIcons = {
        PDF: "bi-file-earmark-pdf text-danger",
        Image: "bi-file-earmark-image text-info",
        Document: "bi-file-earmark-word text-primary",
        Text: "bi-file-earmark-text text-secondary"
    };

    document.getElementById("viewFilesBtn").addEventListener("click", function() {
        const fileList = document.getElementById("fileList");
        const container = document.getElementById("fileListContainer");

        fileList.innerHTML = "";

        defaultFiles.forEach(file => {
            const card = document.createElement("div");
            card.className = "d-flex align-items-center p-3 mb-2 border rounded shadow-sm bg-white w-100";
            card.style.transition = "0.2s";

            const icon = document.createElement("i");
            icon.className = `bi ${fileIcons[file.type]} fs-4 me-3`;

            const fileDetails = document.createElement("div");
            fileDetails.className = "flex-grow-1";
            fileDetails.innerHTML = `
                                                        <a href="#" class="fw-semibold text-decoration-none file-link">${file.name}</a><br>
                                                        <small class="text-muted">${file.size} • ${file.type}</small>
                                                      `;

            const actionBtn = document.createElement("button");
            actionBtn.className = "btn btn-sm btn-outline-primary";
            actionBtn.innerHTML = '<i class="bi bi-eye"></i> View';
            actionBtn.addEventListener("click", () => {
                alert(`Opening: ${file.name}`);
            });

            card.appendChild(icon);
            card.appendChild(fileDetails);
            card.appendChild(actionBtn);
            fileList.appendChild(card);
        });

        container.style.display = "block";
    });

    document.querySelectorAll('.nav-tabs .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            setTimeout(() => {
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.style.opacity = '0';
                    setTimeout(() => {
                        pane.style.transition = 'opacity 0.3s ease';
                        pane.style.opacity = '1';
                    }, 50);
                });
            }, 50);
        });
    });

    $.ajax({
        url: "{{ config('app.api_url') }}tickets",
        method: "GET",
        dataType: "json",
        success: function(response) {
            mytickets = response.filter(r => r.created_by == '{{ Auth::user()->id }}')
        }
    })

    document.addEventListener('DOMContentLoaded', function() {

        let urlParams = new URLSearchParams(window.location.search);
        let id = urlParams.get('id');
        let tid = urlParams.get('t');
        const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
            });
        });

        // --- Offcanvas Reply Logic ---
        const replyOffcanvas = document.getElementById('replyOffcanvas');
        const repliesContainer = document.getElementById('replies-container');
        const replyAttachmentInput = document.getElementById('replyAttachment');
        const uploadedFilesPreview = document.getElementById('uploaded-files-preview');
        const submitReplyBtn = document.getElementById('submitReplyBtn');

        const ckeditorElement = document.querySelector('#replyCkeditor');
        let editorInstance = null;

        if (ckeditorElement) {
            ClassicEditor
                .create(ckeditorElement)
                .then(editor => {
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        }

        const templateDropdownItems = document.querySelectorAll('#applyTemplateDropdown + .dropdown-menu .dropdown-item');
        templateDropdownItems.forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                const templateText = this.getAttribute('data-template');
                if (editorInstance) {
                    editorInstance.setData(templateText);
                }
            });
        });

        if (submitReplyBtn) {
    submitReplyBtn.addEventListener('click', function () {
        if (editorInstance) {
            const replyContent = editorInstance.getData().trim();

            if (replyContent) {
                const formData = new FormData();
                formData.append("ticket_id", id);
                formData.append("message", replyContent);
                formData.append("created_by", authUserId);

                fetch("{{ config('app.api_url') }}ticket_reply", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success || data.id) {
                        // format current datetime
                        const now = new Date();
                        const formattedDate = now.toLocaleDateString("en-US", {
                            day: "2-digit",
                            month: "short",
                            year: "numeric"
                        });
                        const formattedTime = now.toLocaleTimeString("en-US", {
                            hour: "2-digit",
                            minute: "2-digit",
                            hour12: true
                        });

                        // ✅ reply-message card format
                        const replyHtml = `
    <div class="d-flex justify-content-end mb-0">
        <div class="reply-message p-2 rounded shadow-sm bg-white"
             style="max-width:75%;min-width:50%; border-left: 4px solid #0d6efd; border-right: none;">

            <div class="reply-header d-flex justify-content-between align-items-center mb-2">
                <strong>You</strong>
                <span class="small text-muted">${formattedDate} ${formattedTime}</span>
            </div>

            <div class="reply-content">
                ${replyContent}
            </div>
        </div>
    </div>
`;


                        repliesContainer.insertAdjacentHTML("beforeend", replyHtml);

                        // reset editor
                        editorInstance.setData("");

                        // hide offcanvas
                        const offcanvas = bootstrap.Offcanvas.getInstance(replyOffcanvas);
                        if (offcanvas) offcanvas.hide();

                        // auto-scroll to bottom
                        repliesContainer.scrollTop = repliesContainer.scrollHeight;
                    } else {
                        alert("Failed to send reply. Please try again.");
                    }
                })
                .catch(err => {
                    console.error("Reply API error:", err);
                    alert("Something went wrong. Check console for details.");
                });

            } else {
                alert("Please enter a reply before submitting.");
            }
        }
    });
}



        if (replyAttachmentInput && uploadedFilesPreview) {
            replyAttachmentInput.addEventListener('change', function() {
                uploadedFilesPreview.innerHTML = '';
                if (this.files.length > 0) {
                    Array.from(this.files).forEach(file => {
                        const fileSpan = document.createElement('span');
                        fileSpan.className = 'badge bg-light text-dark d-flex align-items-center gap-1';
                        fileSpan.innerHTML = `<i class="fas fa-file me-1"></i> ${file.name}
                                                                <button type="button" class="btn-close btn-close-white ms-1" aria-label="Remove" data-file-name="${file.name}"></button>`;
                        uploadedFilesPreview.appendChild(fileSpan);
                    });
                }
            });

            uploadedFilesPreview.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-close')) {
                    const fileNameToRemove = event.target.getAttribute('data-file-name');
                    const dt = new DataTransfer();
                    const files = replyAttachmentInput.files;

                    for (let i = 0; i < files.length; i++) {
                        if (files[i].name !== fileNameToRemove) {
                            dt.items.add(files[i]);
                        }
                    }
                    replyAttachmentInput.files = dt.files;
                    event.target.closest('.badge').remove();
                }
            });
        }

        if (replyOffcanvas) {
            replyOffcanvas.addEventListener('hidden.bs.offcanvas', function() {
                if (editorInstance) {
                    editorInstance.setData('');
                }
                if (uploadedFilesPreview) {
                    uploadedFilesPreview.innerHTML = '';
                }
                if (replyAttachmentInput) {
                    replyAttachmentInput.value = '';
                }
            });
        }
    });

   // ========== UPDATE TICKET FUNCTION ==========
function update() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    // Show loading on button
    var updateBtn = document.getElementById('createTicketBtn');
    var originalText = updateBtn.innerHTML;
    updateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';
    updateBtn.disabled = true;
    
    var form = new FormData();
    form.append("title", document.getElementById("title").value);
    form.append("description", document.getElementById("description").value);
    form.append("completed_date", document.getElementById("completed_date").value);
    form.append("due_date", document.getElementById("due_date").value);
    form.append("company_id", '{{Auth::user()->company_id}}');
    form.append("completed_by", document.getElementById("completed_by").value);
    form.append("business_unit_id", document.getElementById("business_unit_id").value);
    form.append("customer_id", document.getElementById("customer").value);
    form.append("vendor_id", document.getElementById("vendor_id").value);
    form.append("vendor_type_id", document.getElementById("vendor_type_id").value);
    form.append("reported_by", document.getElementById("reported_by").value);
    form.append("assigned_to", $('#assigned_to').val());
    form.append("mode_of_complaint", document.getElementById("mode_of_complaint").value);
    form.append("sub_type_id", document.getElementById("sub_type_id").value);
    form.append("priority_id", document.getElementById("priority_id").value);
    form.append("impact_id", document.getElementById("impact_id").value);
    form.append("status_id", document.getElementById("status_id").value);
    form.append("store_contact", document.getElementById("store_contact").value);
    form.append("created_by", '{{Auth::user()->id}}');
    form.append("email_status", "0");
    form.append("profile", "");

    var settings = {
        "url": "{{ config('app.api_url') }}tickets/" + id,
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
            
            // Reset button
            updateBtn.innerHTML = originalText;
            updateBtn.disabled = false;
            
            // Show success message
            if (typeof showToast !== 'undefined') {
                showToast('Ticket Updated Successfully', 'success');
            } else {
                alert('Ticket Updated Successfully');
            }
            
            // Refresh only ticket data, NOT full page
            refreshTicketData();
        },
        error: function(xhr) {
            console.log("Update Error:", xhr);
            
            // Reset button
            updateBtn.innerHTML = originalText;
            updateBtn.disabled = false;
            
            let errorMsg = 'Ticket Not Updated';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            if (typeof showToast !== 'undefined') {
                showToast(errorMsg, 'danger');
            } else {
                alert(errorMsg);
            }
        }
    });
}

// ========== REFRESH TICKET DATA FUNCTION ==========
function refreshTicketData() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    if (!id) {
        console.error("ID is missing from URL.");
        return;
    }
    
    // Show loading indicator
    $('.card-body').addClass('opacity-50');
    
    $.ajax({
        url: `{{ config('app.api_url') }}tickets/${id}`,
        method: "GET",
        success: function(data) {
            console.log('Refresh Data:', data);
            let ticket = Array.isArray(data) ? data[0] : data.data || data.ticket || data;
            
            if (!ticket) {
                console.error("Ticket not found.");
                $('.card-body').removeClass('opacity-50');
                return;
            }
            
            // Update all form fields with new data
            $('#title').val(ticket.title);
            $('#ticket_title').text(ticket.title);
            $('#ticket_createdat').text(ticket.created_at);
            $('#ticket_reported_by').text(ticket.reported_by_name);
            $('#message_reported').text(ticket.reported_by_name);
            
            // Update Choices.js dropdowns
            try { if (bu) bu.setChoiceByValue(ticket.business_unit_id); } catch(e) {}
            try { if (assigned) assigned.setChoiceByValue(ticket.assigned_to); } catch(e) {}
            try { if (reported) reported.setChoiceByValue(ticket.reported_by); } catch(e) {}
            try { if (ticketresolved) ticketresolved.setChoiceByValue(ticket.completed_by); } catch(e) {}
            try { if (status1) status1.setChoiceByValue(ticket.status_id); } catch(e) {}
            try { if (priority) priority.setChoiceByValue(ticket.priority_id); } catch(e) {}
            try { if (impact) impact.setChoiceByValue(ticket.impact_id); } catch(e) {}
            try { if (subtype) subtype.setChoiceByValue(ticket.sub_type_id); } catch(e) {}
            try { if (created_by) created_by.setChoiceByValue(ticket.created_by); } catch(e) {}
            
            // Update regular inputs
            $('#description').val(ticket.description || '');
            $('#mode_of_complaint').val(ticket.mode_of_complaint || '');
            $('#completed_date').val(ticket.completed_date || '');
            $('#due_date').val(ticket.due_date || '');
            $('#store_contact').val(ticket.store_contact || '');
            $('#vendor_id').val(ticket.vendor_id || '');
            $('#vendor_type_id').val(ticket.vendor_type_id || '');
            $('#company_id').val(ticket.company_name || '');
            
            // Refresh file display
            if (ticket.file) {
                renderFileCard(ticket.file);
            }
            
            // Refresh replies
            loadReplies();
            
            // Remove loading indicator
            $('.card-body').removeClass('opacity-50');
        },
        error: function(xhr, status, error) {
            console.error("Error refreshing data:", error);
            $('.card-body').removeClass('opacity-50');
            
            if (typeof showToast !== 'undefined') {
                showToast('Failed to refresh data', 'danger');
            }
        }
    });
}

    function bu_wise(id) {
        $.ajax({
            url: "{{ config('app.api_url') }}bu_wise_cus/" + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                alert("hello")
                if (response.length === 0) {
                    $('#customer_col').css('display', "none");
                } else {
                    $('#customer_col').css('display', "block");
                    customer.clearChoices();
                    console.log(response);
                    customer.setChoices(response, 'id', 'first_name', false);
                }
            }
        });
    }



    function renderFileCard(filename) {
        const baseURL = "{{ config('app.api_url') }}profile/";

        const fileList = document.getElementById("fileList");
        const container = document.getElementById("fileListContainer");

        // 🔹 Remove "documents/" if it exists
        const cleanFileName = filename.replace(/^documents\//, '');
        const fileExtension = cleanFileName.split('.').pop().toLowerCase();
        const displayName = cleanFileName;

        // 🔹 File icons by type
        const fileIcons = {
            'pdf': 'bi-file-earmark-pdf',
            'doc': 'bi-file-earmark-word',
            'docx': 'bi-file-earmark-word',
            'jpg': 'bi-file-earmark-image',
            'jpeg': 'bi-file-earmark-image',
            'png': 'bi-file-earmark-image',
            'xlsx': 'bi-file-earmark-excel',
            'xls': 'bi-file-earmark-excel',
            'txt': 'bi-file-earmark-text',
            'default': 'bi-file-earmark'
        };

        const iconClass = fileIcons[fileExtension] || fileIcons['default'];

        // 🔹 Create card elements
        const card = document.createElement("div");
        card.className = "d-flex align-items-center p-3 mb-2 border rounded shadow-sm bg-white w-100";

        const icon = document.createElement("i");
        icon.className = `bi ${iconClass} fs-4 me-3`;

        const fileDetails = document.createElement("div");
        fileDetails.className = "flex-grow-1";
        fileDetails.innerHTML = `
        <span class="fw-semibold text-dark">${displayName}</span><br>
        <small class="text-muted">.${fileExtension}</small>
    `;

        const actionBtn = document.createElement("button");
        actionBtn.className = "btn btn-sm btn-outline-primary";
        actionBtn.innerHTML = '<i class="bi bi-eye"></i> View';
        actionBtn.addEventListener("click", () => {
            const fileURL = baseURL + cleanFileName;
            window.open(fileURL, "_blank");
        });

        // 🔹 Append to card and container
        card.appendChild(icon);
        card.appendChild(fileDetails);
        card.appendChild(actionBtn);
        fileList.appendChild(card);

        // 🔹 Show container if hidden
        container.style.display = "block";
    }


    function initChartFromStatusData(statusData) {
        if (polarChart) {
            polarChart.destroy();
        }

        const labels = statusData.map(item => item.status);
        const series = statusData.map(item => item.count);

        const bodyStyle = getComputedStyle(document.body);
        const colors = [
            bodyStyle.getPropertyValue('--tb-primary') || '#5D87FF',
            bodyStyle.getPropertyValue('--tb-success') || '#34c38f',
            bodyStyle.getPropertyValue('--tb-warning') || '#f1b44c',
            bodyStyle.getPropertyValue('--tb-danger') || '#f46a6a',
            bodyStyle.getPropertyValue('--tb-info') || '#50cd89',
            bodyStyle.getPropertyValue('--tb-dark') || '#212529',
            bodyStyle.getPropertyValue('--tb-secondary') || '#6c757d'
        ].filter(Boolean);

        const options = {
            series: series,
            chart: {
                type: 'polarArea',
                height: 250,
                background: 'transparent',
                animations: {
                    enabled: false
                },
                toolbar: {
                    show: false
                },
                redrawOnParentResize: true,
                redrawOnWindowResize: true
            },
            stroke: {
                colors: [bodyStyle.getPropertyValue('--bs-body-bg') || '#ffffff'],
                width: 1
            },
            fill: {
                opacity: 0.8
            },
            labels: labels,
            legend: {
                position: 'bottom',
                labels: {
                    colors: bodyStyle.getPropertyValue('--bs-body-color') || '#212529',
                    useSeriesColors: false
                }
            },
            colors: colors,
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        polarChart = new ApexCharts(document.querySelector("#basic_polar_area"), options);
        polarChart.render();
    }


    function formatDateTime(datetimeStr) {
        const date = new Date(datetimeStr.replace(" ", "T"));

        const datePart = date.toLocaleDateString('en-US', {
            weekday: 'short',
            day: '2-digit',
            month: 'short'
        });

        const timePart = date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        return `${datePart}\n${timePart}`;
    }


    function quotetf(id) {
        var settings = {
            "url": "{{ config('app.api_url') }}qutationsbyticket/" + id,
            "method": "GET",
            "timeout": 0,
        };

        $.ajax(settings).done(function(response) {
            quotet.clear().draw();
            $.each(response, function(index, data) {
                quotet.row.add([
                    index + 1,
                    data.ticket_id,
                    data.vendor,
                    data.amount,
                    `<button class="btn btn-primary" onclick="storequote(${data.id})">Approve</button>`
                ]).draw(false);
            });
        })
    }

    function vendors(id) {
        var settings = {
            "url": "{{ config('app.api_url') }}vendorsbytype/" + id,
            "method": "GET",
            "timeout": 0,
        };

        $.ajax(settings).done(function(response) {
            table.clear().draw();
            $.each(response, function(index, data) {
                table.row.add([
                    index + 1,
                    data.name,
                    data.email,
                    data.phone,
                    data.company,
                    data.business_units,
                    `<button class="btn btn-primary" onclick="storequote(${data.id})">Send</button>`
                ]).draw(false);
            });
        })
    }

    function storeQuote(vendorId) {
        const ticketId = new URLSearchParams(window.location.search).get('id');

        const formData = new FormData();
        formData.append("ticket_id", ticketId);
        formData.append("vendor_id", vendorId);
        formData.append("status", 1);

        $.ajax({
            url: "{{ config('app.api_url') }}storevendor_tickets",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showToast("Quote request stored successfully", "success");


                sendEmailToVendor(vendorId, ticketId);

            },
            error: function() {
                showToast("Failed to store quote request", "danger");
            }
        });
    }

    function sendEmailToVendor(vendorId, ticketId) {
        $.ajax({
            url: `{{ config('app.api_url') }}sendemailtovendor/${vendorId}/${ticketId}`,
            method: "GET",
            success: function(response) {
                // showToast("Email sent to vendor successfully", "success");
            },
            error: function() {
                // showToast("Failed to send email to vendor", "danger");
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

    const authUserId = {{ Auth::user()->id }}; // Blade variable (Laravel)

    function loadReplies() {
    let urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get('id');
    $.get(`{{ config('app.api_url') }}get_ticket_reply/${id}`, function (replies) {
        let html = "";
        replies.forEach(r => {
            const isMine = r.created_by === authUserId;

            html += `
                <div class="d-flex ${isMine ? "justify-content-end" : "justify-content-start"} mb-0">
                    <div class="reply-message p-2 rounded shadow-sm bg-white"
                         style="max-width:75%;min-width:50%; border-${isMine ? "left" : "right"}: 4px solid #0d6efd; border-${isMine ? "right" : "left"}: none;">

                        <div class="reply-header d-flex justify-content-between align-items-center mb-1">
                            <strong>${isMine ? "You" : (r.name || "User")}</strong>
                            <span class="small text-muted">
                                ${new Date(r.created_at).toLocaleString()}
                            </span>
                        </div>

                        <div class="reply-content">
                            ${r.message}
                        </div>
                    </div>
                </div>
            `;
        });

        $("#replies-container").html(html);

        // Scroll to bottom
        const repliesDiv = document.getElementById("replies-container");
        repliesDiv.scrollTop = repliesDiv.scrollHeight;
    });
}

</script>
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
@endsection
