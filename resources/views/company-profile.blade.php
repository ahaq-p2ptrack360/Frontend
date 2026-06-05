@extends('layouts.master')
@section('title') @lang('translation.company_profile') @endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
<style>
    :root {
        --primary-color: #4a6cf7;
        --secondary-color: #6c757d;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --dark-color: #1f2937;
        --light-bg: #f8fafc;
        --card-border: #e5e7eb;
        --sidebar-width: 280px;
    }

    body {
        background: #f8fafc;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Main Layout */
    .profile-container {
        display: flex;
        min-height: calc(100vh - 70px);
    }

    /* Sidebar Navigation - Show/Hide based on role */
    .company-sidebar {
        width: var(--sidebar-width);
        background: white;
        border-right: 1px solid var(--card-border);
        position: fixed;
        height: calc(100vh - 70px);
        overflow-y: auto;
        padding: 1.5rem 0;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.02);
    }

    .company-sidebar.hidden {
        display: none;
    }

    .company-sidebar.single-company {
        width: 350px; /* Wider for single company view */
    }

    .single-company-view .company-item {
        cursor: default !important;
        background: linear-gradient(135deg, rgba(74, 108, 247, 0.1), rgba(74, 108, 247, 0.05)) !important;
        border: 2px solid var(--primary-color) !important;
    }

    .single-company-view .company-item:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    .company-list {
        padding: 0 1rem;
    }

    .company-list-header {
        padding: 0 1rem 1rem;
        border-bottom: 1px solid var(--card-border);
        margin-bottom: 1rem;
    }

    .company-list-header h5 {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
    }

    .company-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .company-item:hover {
        background: var(--light-bg);
        border-color: var(--primary-color);
    }

    .company-item.active {
        background: rgba(74, 108, 247, 0.08);
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(74, 108, 247, 0.1);
    }

    .company-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-color), #6a11cb);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    /* ✅ Image Avatar Styles */
    .company-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        margin-right: 1rem;
        flex-shrink: 0;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .company-info {
        flex: 1;
        min-width: 0;
    }

    .company-name {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .company-email {
        color: var(--secondary-color);
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .company-status {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-left: 0.5rem;
        flex-shrink: 0;
    }

    .status-active {
        background: var(--success-color);
    }

    .status-inactive {
        background: var(--danger-color);
    }

    .profile-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 2rem;
        max-width: calc(100% - var(--sidebar-width));
    }

    .profile-content.full-width {
        margin-left: 0;
        max-width: 100%;
        width: 100%;
    }

    /* Profile Header */
    .profile-header {
        background: white;
        border-radius: 16px;
        padding: 4rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--card-border);
    }

    .profile-avatar-lg {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--primary-color), #6a11cb);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 2.5rem;
        margin-right: 2rem;
        flex-shrink: 0;
    }

    /* ✅ Profile Image Avatar Styles */
    .profile-avatar-lg-img {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        margin-right: 2rem;
        flex-shrink: 0;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-info h1 {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 2rem;
    }

    .profile-industry {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .profile-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .profile-tag {
        background: var(--light-bg);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        color: var(--dark-color);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-actions {
        margin-top: 1.5rem;
        display: flex;
        gap: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--card-border);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .stat-icon.blue {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info-color);
    }

    .stat-icon.green {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .stat-icon.orange {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
    }

    .stat-icon.purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .stat-icon.teal {
        background: rgba(20, 184, 166, 0.1);
        color: #14b8a6;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: var(--secondary-color);
        font-size: 0.9rem;
    }

    /* Profile Sections */
    .profile-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--card-border);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-bg);
    }

    .section-title {
        font-weight: 700;
        color: var(--dark-color);
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--primary-color);
    }

    .section-action {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .section-action:hover {
        color: #3a5ce5;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1rem;
        background: var(--light-bg);
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }

    .info-label {
        color: var(--secondary-color);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        color: var(--dark-color);
        font-weight: 600;
        font-size: 1rem;
    }

    /* Contact Cards */
    .contact-card {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: var(--light-bg);
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .contact-card:hover {
        background: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(74, 108, 247, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .contact-info h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--dark-color);
    }

    .contact-info p {
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Subscription Card */
    .subscription-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .subscription-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }

    .subscription-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .subscription-plan {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .subscription-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .subscription-feature {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .subscription-feature i {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    /* Activity Timeline */
    .activity-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--light-bg);
    }

    .activity-item {
        position: relative;
        padding-bottom: 2rem;
        padding-left: 1.5rem;
    }

    .activity-item:last-child {
        padding-bottom: 0;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.2);
    }

    .activity-time {
        color: var(--secondary-color);
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        color: var(--dark-color);
        font-weight: 500;
    }

    .activity-user {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Map Container */
    .map-container {
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        background: var(--light-bg);
        position: sticky;
        border: 1px solid var(--card-border);
    }

    #companyMap {
        height: 100%;
        width: 100%;
        border-radius: 12px;
    }

    .map-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        flex-direction: column;
        color: var(--secondary-color);
        background: var(--light-bg);
        padding: 2rem;
        text-align: center;
    }

    .map-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: var(--secondary-color);
    }

    .map-coordinates {
        margin-top: 1rem;
        padding: 0.75rem;
        background: white;
        border-radius: 8px;
        border: 1px solid var(--card-border);
        font-size: 0.85rem;
    }

    .coordinates-info {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
    }

    .coordinate-item {
        text-align: center;
        flex: 1;
        padding: 0 0.5rem;
    }

    .coordinate-label {
        font-size: 0.75rem;
        color: var(--secondary-color);
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .coordinate-value {
        font-weight: 600;
        color: var(--dark-color);
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
    }

    /* Action Buttons */
    /* .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--card-border);
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--card-border);
        background: white;
        color: var(--secondary-color);
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        background: var(--light-bg);
        color: var(--primary-color);
        transform: translateY(-2px);
    } */

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        color: var(--secondary-color);
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .empty-subtitle {
        color: #9ca3af;
        margin-bottom: 2rem;
    }

    /* Invoices Table Styling */
    .invoice-table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--card-border);
        background: white;
    }

    .invoice-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .invoice-table thead {
        background: var(--light-bg);
    }

    .invoice-table thead th {
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid var(--card-border);
        color: var(--dark-color);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .invoice-table tbody tr {
        border-bottom: 1px solid var(--light-bg);
        transition: all 0.2s ease;
    }

    .invoice-table tbody tr:hover {
        background-color: rgba(74, 108, 247, 0.03);
    }

    .invoice-table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-bg);
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Professional Invoice Status Badges */
    .invoice-status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 90px;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .status-badge-paid {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.08));
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge-pending {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.08));
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge-overdue {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-badge-draft {
        background: linear-gradient(135deg, rgba(107, 114, 128, 0.15), rgba(107, 114, 128, 0.08));
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    /* Invoice Icons */
    .invoice-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        margin-right: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .invoice-icon-paid {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
        color: var(--success-color);
    }

    .invoice-icon-pending {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: var(--warning-color);
    }

    .invoice-icon-overdue {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        color: var(--danger-color);
    }

    .invoice-icon-draft {
        background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(107, 114, 128, 0.1));
        color: #6b7280;
    }

    /* Invoice Amount Styling */
    .invoice-amount {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark-color);
        font-family: 'Inter', sans-serif;
    }

    .invoice-amount.positive {
        color: var(--success-color);
    }

    .invoice-number {
        font-weight: 600;
        color: var(--primary-color);
        font-family: 'Courier New', monospace;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    /* Invoice Action Buttons */
    .invoice-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .invoice-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .invoice-action-btn.view {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
        color: var(--info-color);
        border-color: rgba(59, 130, 246, 0.2);
    }

    .invoice-action-btn.view:hover {
        background: rgba(59, 130, 246, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .invoice-action-btn.download {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
        color: var(--success-color);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .invoice-action-btn.download:hover {
        background: rgba(16, 185, 129, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .invoice-action-btn.print {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05));
        color: #8b5cf6;
        border-color: rgba(139, 92, 246, 0.2);
    }

    .invoice-action-btn.print:hover {
        background: rgba(139, 92, 246, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.15);
    }

    /* Invoice Date Styling */
    .invoice-date {
        color: var(--dark-color);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .invoice-due-date {
        color: var(--secondary-color);
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Invoice Loading State */
    .invoice-loading-row {
        padding: 2rem;
        text-align: center;
    }

    .invoice-loading-spinner {
        width: 40px;
        height: 40px;
        margin: 0 auto 1rem;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color), #6a11cb);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--card-border);
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%234a6cf7' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    /* Section Headers in Modal */
    .modal-section-header {
        font-weight: 600;
        color: var(--primary-color);
        border-bottom: 2px solid var(--light-bg);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    .dropdown-menu{
        cursor: pointer;
    }

    /* ✅ Edit Modal Image Preview Styles */
    .image-preview-container {
        text-align: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .image-preview {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        object-fit: cover;
        border: 3px solid var(--card-border);
        margin: 0 auto 1rem;
    }

    .preview-delete-btn {
        position: absolute;
        top: -5px;
        right: calc(50% - 60px + 5px);
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--danger-color);
        color: white;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(239, 68, 68, 0.3);
        z-index: 10;
    }

    .preview-delete-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* ✅ File Upload Button Styles */
    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .upload-btn {
        border: 2px dashed var(--primary-color);
        color: var(--primary-color);
        background-color: rgba(74, 108, 247, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .upload-btn:hover {
        background-color: rgba(74, 108, 247, 0.2);
    }

    .upload-btn-wrapper input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    /* Subscription Plans Styles */
    .subscription-plans-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .plan-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid var(--card-border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .plan-card.popular {
        border: 2px solid var(--primary-color);
        background: linear-gradient(to bottom, rgba(74, 108, 247, 0.02), white);
    }

    .plan-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .plan-price {
        font-size: 3rem;
        font-weight: 800;
        color: var(--dark-color);
        margin: 1rem 0;
    }

    .plan-price .period {
        font-size: 1rem;
        color: var(--secondary-color);
        font-weight: 400;
    }

    .plan-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .plan-description {
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
        min-height: 40px;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }

    .plan-features li {
        padding: 0.5rem 0;
        color: var(--dark-color);
        display: flex;
        align-items: center;
    }

    .plan-features li i {
        color: var(--success-color);
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    .plan-button {
        width: 100%;
        padding: 0.75rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .plan-button.primary {
        background: var(--primary-color);
        color: white;
    }

    .plan-button.primary:hover {
        background: #3a5ce5;
        transform: translateY(-2px);
    }

    .plan-button.secondary {
        background: var(--light-bg);
        color: var(--dark-color);
        border: 1px solid var(--card-border);
    }

    .plan-button.secondary:hover {
        background: #e5e7eb;
    }

    .plan-feature-disabled {
        opacity: 0.5;
    }

    /* SweetAlert Modal Customization */
    .subscription-modal-popup {
        max-width: 1000px !important;
        border-radius: 20px !important;
    }

    .subscription-modal-close {
        font-size: 1.5rem !important;
        color: var(--secondary-color) !important;
    }

    .swal2-close:hover {
        color: var(--danger-color) !important;
    }

    /* ✅ Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 250px;
        background: var(--success-color);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideInRight 0.3s ease;
        font-size: 0.9rem;
    }

    .toast-notification.error {
        background: var(--danger-color);
    }

    .toast-notification.info {
        background: var(--info-color);
    }

    .toast-notification.warning {
        background: var(--warning-color);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* ✅ Chat Auto-Login Styles */
    /* .chat-auto-login-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
    }

    .chat-auto-login-box {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        max-width: 400px;
        width: 90%;
        text-align: center;
    }

    .chat-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #4a6cf7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    } */

    /* Responsive */
    @media (max-width: 1024px) {
        .profile-container {
            flex-direction: column;
        }

        .company-sidebar {
            position: static;
            width: 100%;
            height: auto;
            margin-bottom: 2rem;
            max-height: 400px;
        }

        .profile-content {
            margin-left: 0;
            max-width: 100%;
        }

        .map-container {
            height: 250px;
        }
    }

    @media (max-width: 768px) {
        .profile-content {
            padding: 1rem;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .profile-avatar-lg,
        .profile-avatar-lg-img {
            margin-right: 0;
            margin-bottom: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .profile-section {
            padding: 1.5rem;
        }

        .invoice-table thead th,
        .invoice-table tbody td {
            padding: 1rem;
        }

        .invoice-actions {
            flex-wrap: wrap;
        }

        .invoice-action-btn {
            width: 32px;
            height: 32px;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .subscription-plans-container {
            grid-template-columns: 1fr;
        }

        .plan-card {
            padding: 1.5rem;
        }

        .plan-price {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 576px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-tags {
            flex-direction: column;
            align-items: flex-start;
        }

        .invoice-table-container {
            margin: 0 -1rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }

        .modal-dialog {
            margin: 0.25rem;
        }

        .modal-body {
            padding: 1rem;
        }
    }

.dropdown-item i.bi-eye-fill,
.dropdown-item i.bi-window {
    color: #3b82f6;
}

.dropdown-item i.bi-cloud-arrow-down-fill {
    color: #f97316;
}
.dropdown-item i.bi-file-earmark-pdf-fill {
    color: #dc2626;
}

.dropdown-item i.bi-send-fill,
.dropdown-item i.bi-bell-fill {
    color: #8b5cf6;
}

.dropdown-item i.bi-pencil-square {
    color: #f59e0b;
}

.dropdown-item i.bi-cash-stack,
.dropdown-item i.bi-credit-card {
    color: #10b981;
}

.dropdown-item i.bi-truck,
.dropdown-item i.bi-geo-alt-fill {
    color: #14b8a6;
}

.dropdown-item i.bi-x-octagon-fill {
    color: #ef4444;
}

.dropdown-item i.bi-link-45deg {
    color: #6366f1;
}

.dropdown-item i.bi-trash3-fill {
    color: #b91c1c;
}

.dropdown-item i.bi-files {
    color: #6b7280;
}

/* ===== INVOICE DROPDOWN SPECIFIC STYLES ===== */
.invoice-dropdown .dropdown-item i {
    transition: all 0.2s ease;
}

.invoice-dropdown .dropdown-item:hover i {
    color: white !important;
    transform: scale(1.1);
}

.invoice-dropdown .dropdown-item i.bi-eye-fill,
.invoice-dropdown .dropdown-item i.bi-window {
    color: #3b82f6;
}

.invoice-dropdown .dropdown-item:hover i.bi-eye-fill,
.invoice-dropdown .dropdown-item:hover i.bi-window {
    color: #93c5fd !important;
}

.invoice-dropdown .dropdown-item i.bi-cloud-arrow-down-fill {
    color: #f97316;
}

.invoice-dropdown .dropdown-item:hover i.bi-cloud-arrow-down-fill {
    color: #fed7aa !important;
}

.invoice-dropdown .dropdown-item i.bi-file-earmark-pdf-fill {
    color: #dc2626;
}

.invoice-dropdown .dropdown-item:hover i.bi-file-earmark-pdf-fill {
    color: #fecaca !important;
}

.invoice-dropdown .dropdown-item i.bi-send-fill,
.invoice-dropdown .dropdown-item i.bi-bell-fill {
    color: #8b5cf6;
}

.invoice-dropdown .dropdown-item:hover i.bi-send-fill,
.invoice-dropdown .dropdown-item:hover i.bi-bell-fill {
    color: #ddd6fe !important;
}

.invoice-dropdown .dropdown-item i.bi-pencil-square {
    color: #f59e0b;
}

.invoice-dropdown .dropdown-item:hover i.bi-pencil-square {
    color: #fde68a !important;
}

.invoice-dropdown .dropdown-item i.bi-cash-stack,
.invoice-dropdown .dropdown-item i.bi-credit-card {
    color: #10b981;
}

.invoice-dropdown .dropdown-item:hover i.bi-cash-stack,
.invoice-dropdown .dropdown-item:hover i.bi-credit-card {
    color: #a7f3d0 !important;
}

.invoice-dropdown .dropdown-item i.bi-x-octagon-fill {
    color: #ef4444;
}

.invoice-dropdown .dropdown-item:hover i.bi-x-octagon-fill {
    color: #fecaca !important;
}

.invoice-dropdown .dropdown-item i.bi-trash3-fill {
    color: #b91c1c;
}

.invoice-dropdown .dropdown-item:hover i.bi-trash3-fill {
    color: #fecaca !important;
}

/* ===== STATUS BADGE ICONS ===== */

.invoice-status-badge i.bi-check-circle {
    color: #10b981;
}

.invoice-status-badge i.bi-clock {
    color: #f59e0b;
}

.invoice-status-badge i.bi-exclamation-circle {
    color: #ef4444;
}

.invoice-status-badge i.bi-file-earmark {
    color: #6b7280;
}

/* ===== ANIMATIONS ===== */

.dropdown-item i {
    transition: all 0.2s ease;
}

.dropdown-item:hover i {
    transform: scale(1.1);
}

/* ===== VORTEXTECH PAYMENT MODAL STYLES - ENHANCED ===== */
.payment-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    backdrop-filter: blur(8px);
    animation: overlayFadeIn 0.3s ease;
}

@keyframes overlayFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.payment-modal {
    background: white;
    border-radius: 32px;
    width: 90%;
    max-width: 1100px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.3);
    animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalSlideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.payment-modal-content {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    min-height: 650px;
}

/* ===== LEFT PANEL - ENHANCED PREMIUM DESIGN ===== */
.payment-left-panel {
    background: linear-gradient(145deg, #0B1120 0%, #1A2639 100%);
    padding: 2.5rem;
    color: white;
    border-radius: 32px 0 0 32px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Abstract geometric patterns */
.payment-left-panel::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 50%);
    pointer-events: none;
}

.payment-left-panel::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 0% 100%, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

/* Floating gradient orbs */
.gradient-orb {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
}

.orb-1 {
    top: -100px;
    right: -100px;
    background: rgba(99, 102, 241, 0.3);
}

.orb-2 {
    bottom: -100px;
    left: -100px;
    background: rgba(236, 72, 153, 0.2);
}

/* Premium badge */
.premium-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 100px;
    width: fit-content;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 1;
}

.premium-badge i {
    color: #FCD34D;
    font-size: 1rem;
}

.premium-badge span {
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.9);
}

/* Plan header */
.plan-header-modern {
    position: relative;
    z-index: 1;
    margin-bottom: 2rem;
}

.plan-name {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 0.75rem;
    background: linear-gradient(135deg, #fff 0%, #94A3B8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.plan-desc {
    font-size: 1rem;
    color: #94A3B8;
    line-height: 1.6;
}

/* Price card */
.price-card-modern {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 1.5rem;
    margin: 2rem 0;
    position: relative;
    z-index: 1;
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
}

.price-row:first-child {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.price-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #E2E8F0;
    font-weight: 500;
}

.price-label i {
    color: #818CF8;
    font-size: 1.1rem;
}

.price-value {
    font-weight: 700;
    font-size: 1.25rem;
    color: white;
}

.save-badge-modern {
    background: linear-gradient(135deg, #10B981, #059669);
    padding: 0.25rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    margin-left: 0.75rem;
}

/* Feature list */
.feature-list-modern {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
    z-index: 1;
}

.feature-list-modern li {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    color: #CBD5E1;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.feature-list-modern li:last-child {
    border-bottom: none;
}

.feature-list-modern li i {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(16, 185, 129, 0.2);
    border-radius: 8px;
    color: #10B981;
    font-size: 0.9rem;
}

/* Trust indicators */
.trust-indicators {
    display: flex;
    gap: 1.5rem;
    margin-top: auto;
    padding-top: 2rem;
    position: relative;
    z-index: 1;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #94A3B8;
    font-size: 0.85rem;
}

.trust-item i {
    color: #818CF8;
    font-size: 1rem;
}

/* ===== RIGHT PANEL - ENHANCED ===== */
.payment-right-panel {
    padding: 2.5rem;
    background: white;
    border-radius: 0 32px 32px 0;
}

.payment-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.payment-header-modern h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
}

.header-badge {
    background: #EEF2FF;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.85rem;
    color: #4F46E5;
    font-weight: 600;
}

/* Input with icons */
.input-group-modern {
    position: relative;
    margin-bottom: 1.5rem;
}

.input-group-modern label {
    display: block;
    font-weight: 600;
    color: #1E293B;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.input-icon-wrapper {
    position: relative;
}

.input-icon-wrapper i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 1.1rem;
    z-index: 1;
}

.input-icon-wrapper .input-field {
    width: 100%;
    padding: 0.9rem 1rem 0.9rem 2.8rem;
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #F8FAFC;
}

.input-icon-wrapper .input-field:focus {
    border-color: #4F46E5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    outline: none;
    background: white;
}

.input-icon-wrapper .input-field::placeholder {
    color: #94A3B8;
}

/* Card details grid */
.card-grid-modern {
    display: grid;
    grid-template-columns: 1.5fr 1fr 0.8fr;
    gap: 0.75rem;
    margin: 1.5rem 0;
}

.card-field {
    position: relative;
}

.card-field i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 1rem;
    z-index: 1;
}

.card-field input {
    width: 100%;
    padding: 0.9rem 1rem 0.9rem 2.5rem;
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #F8FAFC;
}

.card-field input:focus {
    border-color: #4F46E5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    outline: none;
    background: white;
}

/* Payment methods */
.payment-methods-modern {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin: 1.5rem 0;
}

.payment-method-card {
    border: 2px solid #E2E8F0;
    border-radius: 16px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.payment-method-card:hover {
    border-color: #4F46E5;
    background: #EEF2FF;
}

.payment-method-card.selected {
    border-color: #4F46E5;
    background: #EEF2FF;
}

.payment-method-card input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #4F46E5;
}

.method-icons-modern {
    display: flex;
    gap: 0.25rem;
    margin-left: auto;
}

.method-icons-modern i {
    font-size: 1.5rem;
}

.method-icons-modern i.fa-cc-visa { color: #1A1F71; }
.method-icons-modern i.fa-cc-mastercard { color: #F79E1B; }
.method-icons-modern i.fa-paypal { color: #003087; }

/* Secure badge */
.secure-badge-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #F0FDF4;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    color: #059669;
    font-size: 0.9rem;
    margin: 1.5rem 0;
}

.secure-badge-modern i {
    font-size: 1.1rem;
}

/* Checkbox styles */
.checkbox-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #F8FAFC;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    margin: 1rem 0;
}

.checkbox-modern input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #4F46E5;
    cursor: pointer;
}

.checkbox-modern label {
    color: #1E293B;
    font-size: 0.9rem;
    cursor: pointer;
}

/* Summary card */
.summary-card-modern {
    background: #F8FAFC;
    border-radius: 20px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.summary-row-modern {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    color: #475569;
}

.summary-row-modern.total {
    border-top: 2px dashed #CBD5E1;
    margin-top: 0.5rem;
    padding-top: 1rem;
    font-weight: 700;
    color: #0F172A;
    font-size: 1.2rem;
}

/* Review button */
.review-btn-modern {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    border: none;
    border-radius: 16px;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin: 1.5rem 0;
    position: relative;
    overflow: hidden;
}

.review-btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.review-btn-modern:hover::before {
    left: 100%;
}

.review-btn-modern:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.4);
}

.review-btn-modern:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Agreement text */
.agreement-text {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: #F8FAFC;
    padding: 1rem;
    border-radius: 12px;
    font-size: 0.85rem;
    color: #475569;
    line-height: 1.5;
    border: 1px solid #E2E8F0;
}

.agreement-text i {
    color: #4F46E5;
    font-size: 1rem;
    margin-top: 2px;
}

/* Security footer */
.security-footer-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #E2E8F0;
    color: #10B981;
    font-size: 0.85rem;
    justify-content: center;
}

.security-footer-modern i {
    font-size: 1.2rem;
}

/* Loading overlay */
.payment-loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 32px;
}

.loading-spinner-modern {
    width: 50px;
    height: 50px;
    border: 3px solid #E2E8F0;
    border-top: 3px solid #4F46E5;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 968px) {
    .payment-modal-content {
        grid-template-columns: 1fr;
    }

    .payment-left-panel {
        border-radius: 32px 32px 0 0;
    }

    .payment-right-panel {
        border-radius: 0 0 32px 32px;
    }
}

@media (max-width: 576px) {
    .payment-modal {
        width: 95%;
        max-height: 95vh;
    }

    .payment-left-panel,
    .payment-right-panel {
        padding: 1.5rem;
    }

    .card-grid-modern {
        grid-template-columns: 1fr;
    }

    .payment-methods-modern {
        grid-template-columns: 1fr;
    }

    .plan-name {
        font-size: 2rem;
    }
}

/* Subscription Type Column Styling */
.project-name {
    font-weight: 500;
    color: var(--dark-color);
    font-size: 0.9rem;
}

/* Badge styling */
.badge.bg-primary.bg-opacity-10 {
    background: rgba(74, 108, 247, 0.1) !important;
    color: #4a6cf7 !important;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
    margin-left: 6px;
}

.badge.bg-info.bg-opacity-10 {
    background: rgba(59, 130, 246, 0.1) !important;
    color: #2563eb !important;
}

.badge.bg-success.bg-opacity-10 {
    background: rgba(16, 185, 129, 0.1) !important;
    color: #059669 !important;
}

/* Period text styling */
.d-block.text-muted {
    font-size: 10px;
    line-height: 1.2;
    opacity: 0.7;
    margin-top: 2px;
    width: 100%;
}

/* Flex wrap for better mobile view */
.d-flex.align-items-center.flex-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 2px;
}

</style>
@endsection

@section('content')
@php
    $user = auth()->user();
    $userRole = $user->role ?? 0;
    $userCompanyId = $user->company_id ?? null;
    $isAdmin = $userRole == 1 || $user->is_super_admin == 1;

    $canEditCompany = true;
    $canUpgradeSubscription = true;
@endphp

<div class="profile-container">
    <!-- Left Sidebar - Company List (Show/Hide based on role) -->
    <div class="company-sidebar {{ !$isAdmin ? 'single-company' : '' }}"
         id="companySidebar"
         style="{{ !$isAdmin ? 'display: none;' : '' }}">
        <div class="company-list-header">
            <h5>Companies Directory</h5>
            @if($isAdmin)
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" id="companySearch" placeholder="Search companies...">
            </div>
            @endif
        </div>

        <div class="company-list" id="companyList">
            <!-- Loading State -->
            <div class="text-center py-5" id="companiesLoading">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading companies...</p>
            </div>

            <!-- Companies will be loaded here -->
            <div class="empty-state" id="emptyCompanies" style="display: none;">
                <div class="empty-icon">
                    <i class="bi bi-building"></i>
                </div>
                <h5 class="empty-title">No Companies</h5>
                <p class="empty-subtitle">Add your first company to get started</p>
            </div>
        </div>
    </div>

    <!-- Main Content - Company Profile -->
    <div class="profile-content {{ !$isAdmin ? 'full-width' : '' }}" id="profileContent">
        <!-- Loading State -->
        <div class="text-center py-5" id="loadingState">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading company profile...</p>
        </div>

        <!-- Default Empty State (For Admin) -->
        <div class="text-center py-5" id="defaultState" style="{{ $isAdmin ? '' : 'display: none;' }}">
            <div class="empty-icon">
                <i class="bi bi-building"></i>
            </div>
            <h3 class="empty-title">Select a Company</h3>
            <p class="empty-subtitle">Choose a company from the sidebar to view its profile</p>
        </div>

        <!-- Company User View (Single Company) -->
        @if(!$isAdmin && $userCompanyId)
        <div id="singleCompanyView">
            <!-- This will be auto-loaded via JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading your company profile...</p>
            </div>
        </div>
        @endif

        <!-- Active Company Profile (Hidden by default) -->
        <div id="companyProfile" style="display: none;">
            <!-- Profile Header -->
            <div class="profile-header d-flex align-items-start">
                <!-- ✅ Profile Avatar Section -->
                <div id="companyAvatarSection">
                    <!-- Image Avatar (when image exists) -->
                    <div id="companyAvatarImgContainer" style="display: none;">
                        <div id="companyAvatarImg"></div>
                    </div>

                    <!-- Text Avatar (when no image) -->
                    <div class="profile-avatar-lg" id="companyAvatarText" style="display: none;"></div>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 id="companyName"></h1>
                            <div class="profile-industry" id="companyIndustry"></div>
                            <div class="profile-tags">
                                <span class="profile-tag">
                                    <i class="bi bi-geo-alt"></i>
                                    <span id="companyLocation"></span>
                                </span>
                                <span class="profile-tag">
                                    <i class="bi bi-calendar"></i>
                                    Member since <span id="companySince"></span>
                                </span>
                                <span class="profile-tag">
                                    <i class="bi bi-shield-check"></i>
                                    <span id="companyStatus">Active</span>
                                </span>
                            </div>
                        </div>
                        <div class="dropdown" id="actionButtons">
                            @if($canEditCompany)
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear"></i> Manage
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" onclick="editCompany()"><i class="bi bi-pencil me-2"></i>Edit Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Archive Company</a></li>
                            </ul>
                            @endif
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button class="btn btn-primary" id="sendMessageBtn">
                            <i class="bi bi-envelope me-2"></i>Send Message
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-telephone me-2"></i>Call Support
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-printer me-2"></i>Print Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <!-- Active Tickets -->
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                    <div class="stat-value" id="statTickets">0</div>
                    <div class="stat-label">Total {{ optional(session('userWithBU'))->ticket_represented ?? 'Tickets' }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="stat-value" id="statLicenses">0</div>
                    <div class="stat-label">Total Licenses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-value" id="statSubscription">Premium</div>
                    <div class="stat-label">Subscription Plan</div>
                </div>
                <!-- Business Units -->
                <div class="stat-card">
                    <div class="stat-icon teal">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-value" id="statBusinessUnits">0</div>
                    <div class="stat-label">Total Business Units</div>
                </div>
            </div>

            <!-- Company Details Section -->
            <div class="profile-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="bi bi-info-circle"></i>
                        Company Overview
                    </h3>
                    @if($canEditCompany)
                    <a class="section-action" onclick="editDetail()">
                        <i class="bi bi-pencil"></i>
                        Edit Details
                    </a>
                    @endif
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-building"></i>
                            Registration Number
                        </div>
                        <div class="info-value" id="infoRegistration">0</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-globe"></i>
                            Website
                        </div>
                        <div class="info-value" id="infoWebsite">
                            <a href="#" id="websiteLink" target="_blank">0</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-calendar"></i>
                            Established
                        </div>
                        <div class="info-value" id="infoEstablished">0</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-person"></i>
                            Contact Person
                        </div>
                        <div class="info-value" id="infoContactPerson">0</div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="mb-3" style="color: var(--dark-color); font-weight: 600;">Company Description</h6>
                    <p class="text-muted" id="companyDescription">
                    </p>
                </div>
            </div>

            <!-- Contact Information & Subscription -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="profile-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="bi bi-telephone"></i>
                                Contact Information
                            </h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="contact-card">
                                    <div class="contact-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="contact-info">
                                        <h6>Primary Email</h6>
                                        <p id="contactEmail">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-card">
                                    <div class="contact-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div class="contact-info">
                                        <h6>Phone Number</h6>
                                        <p id="contactPhone">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-card">
                                    <div class="contact-icon">
                                        <i class="bi bi-headset"></i>
                                    </div>
                                    <div class="contact-info">
                                        <h6>Support Email</h6>
                                        <p id="supportEmail">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-card">
                                    <div class="contact-icon">
                                        <i class="bi bi-chat"></i>
                                    </div>
                                    <div class="contact-info">
                                        <h6>Support Contact</h6>
                                        <p id="supportPhone">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="mt-4">
                            <h6 class="mb-3" style="color: var(--dark-color); font-weight: 600;">Company Address</h6>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-geo-alt"></i>
                                    Headquarters
                                </div>
                                <div class="info-value">
                                    <div id="companyAddress">0</div>
                                    <div id="companyCityState">0</div>
                                    <div id="companyCountry">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="bi bi-clock-history"></i>
                                Recent Activity
                            </h3>
                        </div>

                        <div class="activity-timeline" id="activityTimeline">
                            <div class="activity-item">
                                <div class="activity-time">Today, 10:30 AM</div>
                                <div class="activity-description">
                                    <span class="activity-user">John Smith</span> updated subscription plan to Premium
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-time">Yesterday, 3:45 PM</div>
                                <div class="activity-description">
                                    <span class="activity-user">Sarah Johnson</span> added 5 new employee licenses
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-time">Dec 15, 2024</div>
                                <div class="activity-description">
                                    Company profile information was updated
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="bi bi-receipt"></i>
                                Recent Invoices
                            </h3>
                            <div>
        <button class="btn btn-primary" onclick="createInvoice()">
            <i class="bi bi-plus-circle"></i>
            Create Invoice
        </button>
    </div>
                        </div>

                        <div class="invoice-table-container">
                            <table class="invoice-table">
                                <thead>
                                    <tr>
                                    <th>ID</th>
        <th>Invoice #</th>
        <th>Subscription Type</th>
        <th>Company</th>
        <th>Amount</th>
        <th>Invoice Date</th>
        <th>Due Date</th>
        <th>Status</th>
        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="invoicesTableBody">
                                    <!-- Invoices will be loaded here dynamically -->
                                    <tr id="invoicesLoading">
                                        <td colspan="6" class="invoice-loading-row">
                                            <div class="invoice-loading-spinner spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="text-muted mb-0">Loading invoices...</p>
                                        </td>
                                    </tr>
                                    <tr id="noInvoices" style="display: none;">
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-receipt" style="font-size: 3rem; opacity: 0.3;"></i>
                                            <h6 class="mt-3 mb-2">No invoices found</h6>
                                            <p class="mb-0">This company doesn't have any invoices yet.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Subscription Card -->
                    <div class="subscription-card mb-4">
                        <div class="subscription-badge">
                            <i class="bi bi-star-fill me-2"></i>
                            Current Plan
                        </div>
                        <h3 class="subscription-plan" id="subscriptionPlan">Enterprise Pro</h3>
                        <p id="subscriptionDescription">Complete access to all features and premium support</p>

                        <div class="subscription-details" id="subscriptionFeatures">
                            <!-- Features will be loaded dynamically -->
                            <div class="subscription-feature">
                                <i class="bi bi-check-circle"></i>
                                <span id="featureUsers">0 Users</span>
                            </div>
                            <div class="subscription-feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Unlimited Storage</span>
                            </div>
                            <div class="subscription-feature">
                                <i class="bi bi-check-circle"></i>
                                <span>24/7 Support</span>
                            </div>
                            <div class="subscription-feature">
                                <i class="bi bi-check-circle"></i>
                                <span>API Access</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Renewal Date</span>
                                <strong id="renewalDate">Jan 15, 2025</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Monthly Cost</span>
                                <strong id="monthlyCost">$499.99</strong>
                            </div>
                        </div>

                        @if($canUpgradeSubscription)
                        <button class="btn btn-light mt-4 w-100" onclick="upgradePlan()">
                            <i class="bi bi-arrow-up-circle me-2"></i>
                            Upgrade Plan
                        </button>

                        <button class="btn btn-light mt-4 w-100" onclick="showPaymentModal()">
    <i class="bi bi-credit-card me-2"></i>
    Pay with Card
</button>
                        @endif
                    </div>

                    <!-- Location Map Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="bi bi-map"></i>
                                Location
                            </h3>
                        </div>

                        <div class="map-container" id="locationContainer">
                            <!-- Map will be loaded here if coordinates are valid -->
                            <div class="map-placeholder" id="mapPlaceholder">
                                <i class="bi bi-geo-alt"></i>
                                <p>Location coordinates not available</p>
                                <div class="map-coordinates mt-3">
                                    <div class="coordinates-info">
                                        <div class="coordinate-item">
                                            <div class="coordinate-label">Latitude</div>
                                            <div class="coordinate-value" id="displayLat">N/A</div>
                                        </div>
                                        <div class="coordinate-item">
                                            <div class="coordinate-label">Longitude</div>
                                            <div class="coordinate-value" id="displayLng">N/A</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <!-- <div class="profile-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="bi bi-lightning"></i>
                                Quick Actions
                            </h3>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary text-start">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Generate Report
                            </button>
                            <button class="btn btn-outline-primary text-start">
                                <i class="bi bi-download me-2"></i>
                                Export Data
                            </button>
                            <button class="btn btn-outline-primary text-start">
                                <i class="bi bi-people me-2"></i>
                                Manage Users
                            </button>
                            <button class="btn btn-outline-primary text-start">
                                <i class="bi bi-credit-card me-2"></i>
                                Billing Settings
                            </button>
                        </div>
                    </div> -->

                   <!-- Invoice Statistics Graph - Updated Bar Chart -->
<div class="profile-section">
    <div class="section-header">
        <h3 class="section-title">
            <i class="bi bi-bar-chart-fill"></i>
            Monthly Invoice Analytics
        </h3>
    </div>

    <!-- Bar Chart Container -->
    <div style="height: 350px; position: relative;" id="invoiceBarChartContainer">
        <canvas id="invoiceBarChart"></canvas>
    </div>

    <!-- Optional: Ek simple summary niche rakh sakte ho, but ab tooltip mein detail milega -->
    <!-- <div class="text-center text-muted mt-2 small">Hover over bars to see monthly details.</div> -->
</div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <!-- <div class="action-buttons">
                <button class="btn-icon" title="Download PDF">
                    <i class="bi bi-file-pdf"></i>
                </button>
                <button class="btn-icon" title="Share Profile">
                    <i class="bi bi-share"></i>
                </button>
                <button class="btn-icon" title="Print">
                    <i class="bi bi-printer"></i>
                </button>
                <button class="btn-icon" title="Bookmark">
                    <i class="bi bi-bookmark"></i>
                </button>
                @if($canEditCompany)
                <div class="ms-auto">
                    <button class="btn btn-success me-2">
                        <i class="bi bi-check-circle me-2"></i>
                        Activate Account
                    </button>
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        Deactivate
                    </button>
                </div>
                @endif
            </div> -->
        </div>
    </div>
</div>

<!-- Chat Auto-Login Overlay -->
<!-- <div class="chat-auto-login-overlay" id="chatAutoLoginOverlay" style="display: none;">
    <div class="chat-auto-login-box">
        <div class="chat-spinner"></div>
        <h5>Connecting to Chat...</h5>
        <p class="text-muted">Auto-login in progress</p>
    </div>
</div> -->

<!-- Edit Company Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyModalLabel">Edit Company Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCompanyForm">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editCompanyError"></div>
                    <div class="alert alert-success d-none" id="editCompanySuccess"></div>

                    <div class="row g-3">
                        <!-- ✅ Profile Image Section -->
                        <div class="col-12">
                            <h6 class="modal-section-header">
                                <i class="bi bi-image me-2"></i>
                                Company Profile Image
                            </h6>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="image-preview-container">
                                <!-- ✅ Image Preview with Delete Button -->
                                <div id="editCompanyImageWrapper" style="display: none; position: relative;">
                                    <img id="editCompanyProfilePreview" class="image-preview" src="" alt="Profile Preview" style="cursor: pointer;">
                                    <div class="preview-delete-btn" onclick="deletePreviewImage()" title="Remove image">
                                        <i class="bi bi-x"></i>
                                    </div>
                                </div>

                                <!-- Placeholder when no image -->
                                <div id="editCompanyProfilePlaceholder" class="image-preview" style="display: flex; align-items: center; justify-content: center; background: var(--light-bg); color: var(--secondary-color);">
                                    <i class="bi bi-building" style="font-size: 3rem;"></i>
                                </div>

                                <!-- Upload Button -->
                                <div class="upload-btn-wrapper">
                                    <button type="button" class="upload-btn" id="editCompanyUploadBtn">
                                        <i class="bi bi-cloud-upload"></i>
                                        Upload Profile Image
                                    </button>
                                    <input type="file" id="editCompanyProfileImage" name="profile_image" accept="image/*" onchange="previewCompanyProfileImage(this)">
                                </div>

                                <div class="mt-2">
                                    <small class="text-muted">Recommended size: 400x400 pixels. Max size: 2MB</small>
                                </div>

                                <!-- Hidden fields -->
                                <input type="hidden" id="editCompanyExistingProfileImage" name="existing_profile_image">
                                <input type="hidden" id="shouldDeleteProfileImage" name="should_delete_profile_image" value="0">
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div class="col-md-12">
                            <label for="editName" class="form-label">Company Name *</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <!-- Domain -->
                        <div class="col-md-6">
                            <label for="editDomainId" class="form-label">Domain *</label>
                            <select class="form-control" id="editDomainId" name="domain_id" required>
                                <option value="">Select Domain</option>
                            </select>
                        </div>

                        <!-- Country -->
                        <div class="col-md-6">
                            <label for="editCountry" class="form-label">Country *</label>
                            <input type="text" class="form-control" id="editCountry" name="country_id" required>
                        </div>

                        <!-- State -->
                        <div class="col-md-6">
                            <label for="editStateId" class="form-label">State</label>
                            <input type="text" class="form-control" id="editStateId" name="state_id">
                        </div>

                        <!-- City -->
                        <div class="col-md-6">
                            <label for="editCityId" class="form-label">City</label>
                            <input type="text" class="form-control" id="editCityId" name="city_id">
                        </div>

                        <!-- Hidden Company ID -->
                        <input type="hidden" id="editCompanyId" name="id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateCompanyBtn">
                        <span id="updateCompanyBtnText">Update Company</span>
                        <span id="updateCompanyBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Details Modal -->
<div class="modal fade" id="editDetailsModal" tabindex="-1" aria-labelledby="editDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailsModalLabel">Edit Company Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDetailsForm">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editDetailsError"></div>
                    <div class="alert alert-success d-none" id="editDetailsSuccess"></div>

                    <div class="row g-3">
                        <!-- Company Overview Section -->
                        <div class="col-12">
                            <h6 class="modal-section-header">
                                <i class="bi bi-info-circle me-2"></i>
                                Company Overview
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label for="editRegistrationNumber" class="form-label">Registration Number</label>
                            <input type="text" class="form-control" id="editRegistrationNumber" name="registration_number">
                        </div>

                        <div class="col-md-6">
                            <label for="editWebsite" class="form-label">Website</label>
                            <input type="text" class="form-control" id="editWebsite" name="website" placeholder="https://example.com">
                        </div>

                        <div class="col-md-6">
                            <label for="editCreatedAt" class="form-label">Established Date</label>
                            <input type="date" class="form-control" id="editCreatedAt" name="created_at">
                        </div>

                        <div class="col-md-6">
                            <label for="editContactPerson" class="form-label">Contact Person</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="text" class="form-control" id="editFirstName" name="first_name" placeholder="First">
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" id="editMiddleName" name="middle_name" placeholder="Middle">
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" id="editLastName" name="last_name" placeholder="Last">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="editDescription" class="form-label">Company Description</label>
                            <textarea class="form-control" id="editDescription" name="comments" rows="3" placeholder="Enter company description..."></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="modal-section-header">
                                <i class="bi bi-telephone me-2"></i>
                                Contact Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label for="editPrimaryEmail" class="form-label">Primary Email *</label>
                            <input type="email" class="form-control" id="editPrimaryEmail" name="email" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="editPhone" name="telephon">
                        </div>

                        <div class="col-md-6">
                            <label for="editMobile" class="form-label">Mobile</label>
                            <input type="tel" class="form-control" id="editMobile" name="mobile">
                        </div>

                        <div class="col-md-6">
                            <label for="editSupportEmail" class="form-label">Support Email</label>
                            <input type="email" class="form-control" id="editSupportEmail" name="support_email">
                        </div>

                        <div class="col-md-6">
                            <label for="editSupportContact" class="form-label">Support Contact</label>
                            <input type="tel" class="form-control" id="editSupportContact" name="support_contact">
                        </div>

                        <div class="col-md-6">
                            <label for="editLanguage" class="form-label">Language</label>
                            <input type="text" class="form-control" id="editLanguage" name="language">
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="modal-section-header">
                                <i class="bi bi-geo-alt me-2"></i>
                                Address Information
                            </h6>
                        </div>

                        <div class="col-12">
                            <label for="editAddress1" class="form-label">Address Line 1</label>
                            <input type="text" class="form-control" id="editAddress1" name="address_1">
                        </div>

                        <div class="col-12">
                            <label for="editAddress2" class="form-label">Address Line 2</label>
                            <input type="text" class="form-control" id="editAddress2" name="address_2">
                        </div>

                        <div class="col-md-4">
                            <label for="editCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="editCity" name="city_id">
                        </div>

                        <div class="col-md-4">
                            <label for="editState" class="form-label">State</label>
                            <input type="text" class="form-control" id="editState" name="state_id">
                        </div>

                        <div class="col-md-4">
                            <label for="editCountryDetails" class="form-label">Country</label>
                            <input type="text" class="form-control" id="editCountryDetails" name="country_id">
                        </div>

                        <div class="col-md-6">
                            <label for="editZipcode" class="form-label">Zipcode</label>
                            <input type="text" class="form-control" id="editZipcode" name="zipcode">
                        </div>

                        <div class="col-md-6">
                            <label for="editRegion" class="form-label">Region</label>
                            <input type="text" class="form-control" id="editRegion" name="region">
                        </div>

                        <!-- Hidden Company ID -->
                        <input type="hidden" id="editDetailsCompanyId" name="id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateDetailsBtn">
                        <span id="updateDetailsBtnText">Update Details</span>
                        <span id="updateDetailsBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create Invoice Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="createInvoiceOffcanvas" aria-labelledby="createInvoiceOffcanvasLabel" style="width: 80%; max-width: 1200px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="createInvoiceOffcanvasLabel">
            <i class="bi bi-receipt me-2"></i>
            Create New Invoice
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr>
    <div class="offcanvas-body">
        <form id="createInvoiceForm">
            <!-- ===== SECTION 1: BASIC DETAILS ===== -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i>Basic Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Number</label>
                            <input type="text" class="form-control" id="invoiceNumber"
                                   placeholder="Auto-generated" readonly value="Auto-generated">
                            <small class="text-muted">Auto-generated</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="invoiceDate" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="dueDate" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="invoiceStatus">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                            <select class="form-select" id="clientSelect" required>
                                <option value="">Select Client</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Currency</label>
                            <select class="form-select" id="currency">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                                <option value="PKR">PKR (Rs)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Generated By</label>
                            <input type="text" class="form-control" id="generatedBy"
                                   value="{{ auth()->user()->name ?? 'Auto' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 2: CLIENT & BANK DETAILS ===== -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-person me-2"></i>Client Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Project Name</label>
                                <input type="text" class="form-control" id="projectName" placeholder="e.g., Website Development">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Billing Address</label>
                                <textarea class="form-control" id="billingAddress" rows="2"
                                          placeholder="Auto-filled from client" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-bank me-2"></i>Bank Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bank Account</label>
                                <input type="text" class="form-control" id="bankAccount"
                                       placeholder="Account Number">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Payment Details</label>
                                <textarea class="form-control" id="paymentDetails" rows="2"
                                          placeholder="Bank name, IBAN, Swift code etc."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 3: INVOICE ITEMS ===== -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-cart me-2"></i>Invoice Items</h6>
                    <button type="button" class="btn btn-sm btn-success" onclick="addNewItemSection()">
                        <i class="bi bi-plus-circle me-1"></i> Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <!-- Default first item section -->
                        <div class="item-section card mb-3 border-primary" id="itemSection_1">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0 fw-semibold">Item #1</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItemSection(1)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control item-name-field" data-section="1"
                                               placeholder="Product/Service name" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control quantity-field" data-section="1"
                                               value="1" min="0.01" step="0.01" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Unit Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control unit-price-field" data-section="1"
                                               value="0.00" min="0" step="0.01" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Tax %</label>
                                        <select class="form-select tax-field" data-section="1">
                                            <option value="0">0%</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="13">13%</option>
                                            <option value="16">16%</option>
                                            <option value="18">18%</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Amount</label>
                                        <input type="text" class="form-control amount-field" data-section="1"
                                               value="0.00" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <textarea class="form-control description-field" data-section="1" rows="2"
                                                  placeholder="Description (optional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 4: PAYMENT & SUMMARY ===== -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-cash me-2"></i>Payment Recording</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="paymentReceivedCheckbox">
                                <label class="form-check-label fw-semibold" for="paymentReceivedCheckbox">
                                    <i class="bi bi-check-circle me-2 text-success"></i>
                                    Payment Received
                                </label>
                            </div>

                            <div id="paymentFields" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Paid Amount</label>
                                        <input type="number" class="form-control" id="paidAmount" step="0.01" min="0" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Paid Date</label>
                                        <input type="date" class="form-control" id="paidDate">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Payment Method</label>
                                        <select class="form-select" id="paymentMethod">
                                            <option value="">Select Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="online">Online Payment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Transaction ID</label>
                                        <input type="text" class="form-control" id="transactionId"
                                               placeholder="Reference number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-calculator me-2"></i>Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td>Sub Total:</td>
                                        <td class="text-end fw-bold" id="summarySubTotal">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Tax Total:</td>
                                        <td class="text-end fw-bold" id="summaryTax">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Discount:</td>
                                        <td class="text-end">
                                            <input type="number" class="form-control form-control-sm text-end"
                                                   id="discountAmount" value="0" min="0" step="0.01"
                                                   style="width: 100px; display: inline-block;">
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold">Total:</td>
                                        <td class="text-end fw-bold text-primary h5" id="summaryTotal">$0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 5: NOTES & TERMS ===== -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2"></i>Notes</h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="invoiceNotes" rows="3"
                                      placeholder="Additional notes for the client..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark me-2"></i>Terms & Conditions</h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="invoiceTerms" rows="3"
                                      placeholder="Payment terms, late fees etc."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== FORM ACTIONS ===== -->
            <div class="d-flex justify-content-end gap-2 mt-4 mb-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">
                    <i class="bi bi-x-lg me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Updated Payment Modal - Left Panel Options Clickable -->
<div class="payment-modal-overlay" id="vortexPaymentModal" style="display: none;">
    <div class="payment-modal">
        <div class="payment-modal-content">
            <!-- Left Panel - Premium Design with Clickable Options -->
            <div class="payment-left-panel">
                <!-- Floating gradient orbs -->
                <div class="gradient-orb orb-1"></div>
                <div class="gradient-orb orb-2"></div>

                <!-- Premium Badge -->
                <div class="premium-badge">
                    <i class="bi bi-shield-check"></i>
                    <span>ENTERPRISE GRADE SECURITY</span>
                </div>

                <!-- Plan Header -->
                <div class="plan-header-modern">
                    <h2 class="plan-name" id="modalPlanName">Starter</h2>
                    <p class="plan-desc" id="modalPlanDesc">1-5 Teammates in 1 license.</p>
                </div>

                <div class="price-card-modern">
    <div class="price-row" style="padding: 0.75rem 0;">
        <div class="price-label">
            <i class="bi bi-calendar-month"></i>
            <span>Monthly Subscription</span>
        </div>
        <div class="price-value" id="modalMonthlyPrice">$8.99<span style="font-size: 0.9rem; color: #94A3B8;">/month</span></div>
    </div>
</div>

<!-- Hidden fields for internal use (no display) -->
<input type="hidden" id="selectedBillingCycle" value="monthly">

                <!-- Active Plan Indicator (shows which option is selected) -->
                <div class="active-plan-indicator" id="activePlanIndicator" style="margin-top: 0.5rem; text-align: right; font-size: 0.85rem; color: #10B981;">
                    <i class="bi bi-check-circle-fill"></i>
                    <span id="selectedPlanText">Monthly plan selected</span>
                </div>

                <!-- Feature List -->
                <ul class="feature-list-modern" id="modalFeatures">
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>Access to all essential features</span>
                    </li>
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>Unlimited tracking hours</span>
                    </li>
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>Customizable Dashboard</span>
                    </li>
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>5,000 project creations</span>
                    </li>
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>Data Analytics Overview</span>
                    </li>
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>24/7 Priority Support</span>
                    </li>
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>API Access Included</span>
                    </li>
                </ul>

                <!-- Trust Indicators -->
                <div class="trust-indicators">
                    <div class="trust-item">
                        <i class="bi bi-lock-fill"></i>
                        <span>256-bit SSL</span>
                    </div>
                    <div class="trust-item">
                        <i class="bi bi-shield-check"></i>
                        <span>GDPR Compliant</span>
                    </div>
                    <div class="trust-item">
                        <i class="bi bi-clock-history"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Payment Form (Unchanged) -->
            <div class="payment-right-panel">
                <div class="payment-header-modern">
                    <h3>Payment Method</h3>
                    <div class="header-badge">
                        <i class="bi bi-lock-fill me-1"></i>
                        Secure Checkout
                    </div>
                </div>

                <!-- Contact Section with Icon -->
                <div class="input-group-modern">
                    <label>Contact Information</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="text" class="input-field" id="contactInput"
                               placeholder="email@example.com or +1 (234) 567-8900">
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="payment-methods-modern">
                    <div class="payment-method-card selected" onclick="selectPaymentMethod('card')">
                        <input type="radio" name="paymentMethod" value="card" checked>
                        <span>Credit Card</span>
                        <div class="method-icons-modern">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                        </div>
                    </div>

                    <div class="payment-method-card" onclick="selectPaymentMethod('paypal')">
                        <input type="radio" name="paymentMethod" value="paypal">
                        <span>PayPal</span>
                        <div class="method-icons-modern">
                            <i class="fab fa-paypal"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Payment Form with Icons -->
                <div id="cardPaymentForm">
                    <!-- Card Number with Icon -->
                    <div class="card-field" style="margin-bottom: 0.75rem;">
                        <i class="far fa-credit-card"></i>
                        <input type="text" id="cardNumber" placeholder="Card number"
                               maxlength="19" oninput="formatCardNumber(this)">
                    </div>

                    <!-- Expiry and CVV with Icons -->
                    <div class="card-grid-modern">
                        <div class="card-field">
                            <i class="far fa-calendar-alt"></i>
                            <input type="text" id="expiryDate" placeholder="MM/YY"
                                   maxlength="5" oninput="formatExpiry(this)">
                        </div>
                        <div class="card-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="securityCode" placeholder="CVV" maxlength="3">
                        </div>
                        <div class="card-field">
                            <i class="fas fa-flag"></i>
                            <input type="text" id="zipCode" placeholder="ZIP" maxlength="5">
                        </div>
                    </div>

                    <!-- Card Holder Name with Icon -->
                    <div class="input-group-modern">
                        <div class="input-icon-wrapper">
                            <i class="far fa-user"></i>
                            <input type="text" class="input-field" id="cardHolder"
                                   placeholder="Cardholder name">
                        </div>
                    </div>
                </div>

                <!-- PayPal Form (Hidden by default) -->
                <div id="paypalPaymentForm" style="display: none;">
                    <div style="background: #F8FAFC; border-radius: 16px; padding: 2rem; text-align: center; margin: 1rem 0;">
                        <i class="fab fa-paypal" style="font-size: 3rem; color: #003087; margin-bottom: 1rem;"></i>
                        <p class="text-muted">You will be redirected to PayPal to securely complete your payment.</p>
                    </div>
                </div>

                <!-- Secure Badge -->
                <div class="secure-badge-modern">
                    <i class="bi bi-shield-check"></i>
                    <span>Your payment information is encrypted and secure</span>
                </div>

                <!-- Billing Address Checkbox -->
                <div class="checkbox-modern">
                    <input type="checkbox" id="useShippingAddress" checked>
                    <label for="useShippingAddress">Use shipping address as billing address</label>
                </div>

                <!-- Summary Section - Updates based on selection -->
                <div class="summary-card-modern">
                    <div class="summary-row-modern">
                        <span>Plan Price</span>
                        <span class="summary-amount" id="planPriceAmount">$8.99/month</span>
                    </div>
                    <div class="summary-row-modern">
                        <span>Billing Cycle</span>
                        <span class="summary-amount" id="billingCycleDisplay">Monthly</span>
                    </div>
                    <div class="summary-row-modern">
                        <span>Subtotal</span>
                        <span class="summary-amount" id="subtotalAmount">$8.99</span>
                    </div>
                    <div class="summary-row-modern">
                        <span>Estimated taxes</span>
                        <span class="summary-amount" id="taxAmount">$1.08</span>
                    </div>
                    <div class="summary-row-modern total">
                        <span>Total</span>
                        <span class="summary-amount total-amount" id="totalAmount">$10.07</span>
                    </div>
                </div>

                <!-- Review Button -->
                <button class="review-btn-modern" onclick="reviewOrder()" id="reviewOrderBtn">
                    <i class="bi bi-lock"></i>
                    Payment Save
                </button>

                <!-- Subscription Agreement -->
                <div class="agreement-text">
                    <i class="bi bi-info-circle"></i>
                    <span>By completing this purchase, you agree to our <a href="#" style="color: #4F46E5; text-decoration: none;">Terms of Service</a> and automatic subscription renewals. You can cancel anytime.</span>
                </div>

                <!-- Security Footer -->
                <div class="security-footer-modern">
                    <i class="bi bi-shield-check"></i>
                    <span>Guaranteed safe & secure checkout powered by 256-bit encryption</span>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="payment-loading" id="paymentLoading" style="display: none;">
            <div class="loading-spinner-modern"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- jsPDF and html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Leaflet JS for Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
    // ✅ GLOBAL VARIABLES
    let companies = [];
    let currentCompanyId = null;
    let invoices = [];
    let allTickets = [];
    let allBusinessUnits = [];
    let map = null;
    let marker = null;
    let domains = [];
    let domainMap = {};
    let subscriptionPackages = [];
    let currentEditingInvoiceId = null;
    let itemSectionCounter = 1;
    let invoiceBarChart = null;

    // ✅ USER INFORMATION (From Blade)
    const isAdmin = @json($isAdmin);
    const userCompanyId = @json($userCompanyId);
    const userRole = @json($userRole);
    const canEditCompany = true;
    const canUpgradeSubscription = true;

    // ✅ Chat credentials from Laravel auth
    // const chatEmail = "{{ Auth::check() ? Auth::user()->email : '' }}";
    // const chatPassword = "{{ Auth::check() ? Auth::user()->two_factor_secret : '' }}";

    $(document).ready(function() {
        console.log('=== USER INFO ===');
        console.log('User Role:', userRole);
        console.log('Is Admin:', isAdmin);
        console.log('User Company ID:', userCompanyId);
        console.log('=================');

       // Setup auto-calculation FIRST
    setupAutoCalculation();

    // Payment checkbox listener
    $('#paymentReceivedCheckbox').on('change', togglePaymentFields);

    // Make sure first item is initialized
    initializeFirstItem();

    // Initial calculation
    calculateTotal();

    // Currency change listener
    $('#currency').on('change', function() {
        calculateTotal();
    });

    // Discount change listener
    $('#discountAmount').on('input', function() {
        calculateTotal();
    });

        // send message button
        setupSendMessageButton();

        fetchDomains().then(() => {
            if (isAdmin) {
                $('#companySidebar').show();
                $('#companySearch').show();
                $('#defaultState').show();
                fetchCompanies();
            } else {
                $('#companySidebar').hide();
                $('#companySearch').hide();
                $('#defaultState').hide();
                $('#singleCompanyView').show();

                if (userCompanyId) {
                    fetchSingleCompany(userCompanyId);
                } else {
                    showToast('No company assigned to your account', 'error');
                    $('#singleCompanyView').html(`
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-building-slash"></i>
                            </div>
                            <h3 class="empty-title">No Company Assigned</h3>
                            <p class="empty-subtitle">Please contact administrator to assign a company to your account.</p>
                        </div>
                    `);
                }
            }
        });

        fetchSubscriptionPackages();
        setupEventListeners();
        fetchAllTickets();
        fetchAllBusinessUnits();

        $('#editCompanyForm').on('submit', function(e) {
            e.preventDefault();
            updateCompany();
        });

        $('#editDetailsForm').on('submit', function(e) {
            e.preventDefault();
            updateCompanyDetails();
        });

        $('#editCompanyModal').on('hidden.bs.modal', function() {
            $('#editCompanyForm')[0].reset();
            $('#editCompanyError').addClass('d-none');
            $('#editCompanySuccess').addClass('d-none');
            $('#updateCompanyBtn').prop('disabled', false);
            $('#updateCompanyBtnText').text('Update Company');
            $('#updateCompanyBtnSpinner').addClass('d-none');

            $('#editCompanyImageWrapper').hide();
            $('#editCompanyProfilePlaceholder').show();
            $('#editCompanyExistingProfileImage').val('');
            $('#editCompanyProfileImage').val('');
            $('#shouldDeleteProfileImage').val('0');
        });

        $('#editDetailsModal').on('hidden.bs.modal', function() {
            $('#editDetailsForm')[0].reset();
            $('#editDetailsError').addClass('d-none');
            $('#editDetailsSuccess').addClass('d-none');
            $('#updateDetailsBtn').prop('disabled', false);
            $('#updateDetailsBtnText').text('Update Details');
            $('#updateDetailsBtnSpinner').addClass('d-none');
        });
    });

    //  SEND MESSAGE BUTTON
function setupSendMessageButton() {
    $(document).on('click', '#sendMessageBtn', function(e) {
        e.preventDefault();

        if (!currentCompanyId) {
            showToast('Please select a company first', 'error');
            return;
        }

        const company = companies.find(c => c.id == currentCompanyId);
        if (!company) {
            showToast('Company not found', 'error');
            return;
        }

        const companyInfo = {
            id: currentCompanyId,
            name: company.name || ''
        };
        localStorage.setItem('chatCompany', JSON.stringify(companyInfo));

        const chatOffcanvas = new bootstrap.Offcanvas(document.getElementById('chatOffcanvas'));
        chatOffcanvas.show();

        // showToast(`Chat opened for ${company.name}`, 'info');
    });
}

    // ✅ FETCH SINGLE COMPANY
    function fetchSingleCompany(companyId) {
        showLoading(true);

        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + companyId,
            method: "GET",
            success: function(response) {
                let company;
                if (response && response.id) {
                    company = response;
                } else if (response && response.data && response.data.id) {
                    company = response.data;
                } else {
                    throw new Error('Invalid company data');
                }

                companies = [company];
                currentCompanyId = companyId;

                loadCompanyProfile(companyId);
                fetchInvoices();
                updateTicketCount(companyId);
                updateBusinessUnitCount(companyId);
                updateSubscriptionInfo();

                showLoading(false);
                $('#singleCompanyView').hide();
                $('#companyProfile').show();

                $('#companySidebar').hide();
                $('.profile-content').addClass('full-width');

                showToast('Company profile loaded successfully', 'success');
            },
            error: function(xhr) {
                console.error('Error fetching company:', xhr.responseText);
                showLoading(false);
                $('#singleCompanyView').html(`
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h3 class="empty-title">Error Loading Company</h3>
                        <p class="empty-subtitle">Failed to load company profile. Please try again later.</p>
                    </div>
                `);
                showToast('Failed to load company profile', 'error');
            }
        });
    }

    // ✅ FETCH COMPANIES (For Admin Only)
    function fetchCompanies() {
        if (!isAdmin) return;

        showCompaniesLoading(true);

        let apiUrl = "{{ config('app.api_url') }}companies";

        $.ajax({
            url: apiUrl,
            method: "GET",
            success: function(response) {
                if (response && Array.isArray(response)) {
                    companies = response;
                } else if (response && response.data && Array.isArray(response.data)) {
                    companies = response.data;
                } else if (response && response.companies && Array.isArray(response.companies)) {
                    companies = response.companies;
                } else {
                    companies = [];
                }

                companies.forEach(company => {
                    if (company.domain_id) {
                        company.domain_name = getDomainNameById(company.domain_id);
                    }
                });

                renderCompanyList(companies);

                if (companies.length > 0) {
                    selectCompany(companies[0].id);
                } else {
                    showEmptyState();
                }

                showCompaniesLoading(false);
            },
            error: function() {
                $.ajax({
                    url: "/api/companies",
                    method: "GET",
                    success: function(altResponse) {
                        if (altResponse && Array.isArray(altResponse)) {
                            companies = altResponse;
                        } else if (altResponse && altResponse.data) {
                            companies = altResponse.data;
                        }

                        companies.forEach(company => {
                            if (company.domain_id) {
                                company.domain_name = getDomainNameById(company.domain_id);
                            }
                        });

                        renderCompanyList(companies);
                        showCompaniesLoading(false);
                    },
                    error: function() {
                        showToast('Failed to load companies', 'error');
                        showCompaniesLoading(false);
                        $('#emptyCompanies').show();
                    }
                });
            }
        });
    }

    // ✅ RENDER COMPANY LIST (For Admin Only)
    function renderCompanyList(companyList) {
        if (!isAdmin) return;

        const container = $('#companyList');
        const emptyState = $('#emptyCompanies');
        const loadingState = $('#companiesLoading');

        loadingState.hide();

        if (!companyList || companyList.length === 0) {
            emptyState.show();
            container.find('.company-item').remove();
            return;
        }

        emptyState.hide();
        container.find('.company-item').remove();

        companyList.forEach(company => {
            const avatarText = company.name ? company.name.charAt(0).toUpperCase() : 'C';

            let avatarHtml = '';
            let hasImage = false;
            let imageUrl = '';

            if (company.profile_url || (company.profile && company.profile !== 'null' && company.profile.trim() !== '')) {
                imageUrl = company.profile_url;
                if (!imageUrl && company.profile) {
                    const filename = company.profile.split('/').pop();
                    const encodedFilename = encodeURIComponent(filename);
                    imageUrl = `{{ config('app.api_url') }}companies/profile-image/${encodedFilename}`;
                }

                if (imageUrl) {
                    hasImage = true;
                    avatarHtml = `<img src="${imageUrl}" alt="${company.name || 'Company'}" class="company-avatar-img" onerror="handleSidebarImageError(this, '${avatarText}')">`;
                }
            }

            if (!hasImage) {
                avatarHtml = `<div class="company-avatar">${avatarText}</div>`;
            }

            const statusClass = (company.active == 1 || company.status == 1) ? 'status-active' : 'status-inactive';
            const statusText = (company.active == 1 || company.status == 1) ? 'Active' : 'Inactive';
            const domainText = company.domain_name || '';

            const companyItem = $(`
                <div class="company-item" data-id="${company.id}" onclick="selectCompany(${company.id})">
                    ${avatarHtml}
                    <div class="company-info">
                        <div class="company-name">${company.name || 'Unnamed Company'}</div>
                        <div class="company-email">${company.email || 'No email'}</div>
                        ${domainText ? `<small class="text-muted d-block mt-1"><i class="bi bi-tag"></i> ${domainText}</small>` : ''}
                    </div>
                    <div class="company-status ${statusClass}" title="${statusText}"></div>
                </div>
            `);

            container.append(companyItem);
        });
    }

    // ✅ HANDLE SIDEBAR IMAGE ERROR
    function handleSidebarImageError(imgElement, fallbackText) {
        const newDiv = document.createElement('div');
        newDiv.className = 'company-avatar';
        newDiv.textContent = fallbackText;

        imgElement.parentNode.replaceChild(newDiv, imgElement);
    }

    function selectCompany(companyId) {
        if (!isAdmin) return;

        currentCompanyId = companyId;

        $('.company-item').removeClass('active');
        $(`.company-item[data-id="${companyId}"]`).addClass('active');

        loadCompanyProfile(companyId);
        fetchInvoices();
        updateTicketCount(companyId);
        updateBusinessUnitCount(companyId);
        updateSubscriptionInfo();
    }

    function updateSidebarAvatar(companyId, firstLetter, imageUrl = null) {
        if (!isAdmin) return;

        const companyItem = $(`.company-item[data-id="${companyId}"]`);
        if (companyItem.length === 0) return;

        const currentAvatar = companyItem.find('.company-avatar, .company-avatar-img');
        if (currentAvatar.length > 0) {
            currentAvatar.remove();
        }

        let newAvatar;
        if (imageUrl) {
            newAvatar = $(`
                <img src="${imageUrl}"
                     alt="Company"
                     class="company-avatar-img"
                     onerror="handleSidebarImageError(this, '${firstLetter}')">
            `);
        } else {
            newAvatar = $(`<div class="company-avatar">${firstLetter}</div>`);
        }

        companyItem.prepend(newAvatar);
    }

    // ✅ EDIT COMPANY
    function editCompany() {
        if (!currentCompanyId) {
            showToast('No company selected', 'error');
            return;
        }

        if (!canEditCompany) {
            showToast('You do not have permission to edit company', 'error');
            return;
        }

        if (domains.length === 0) {
            showToast('Loading domain information...', 'info');
            fetchDomains().then(() => {
                openEditCompanyModal();
            });
        } else {
            openEditCompanyModal();
        }
    }

    function openEditCompanyModal() {
        $('#editCompanyError').addClass('d-none');
        $('#editCompanySuccess').addClass('d-none');
        $('#updateCompanyBtn').prop('disabled', true);
        $('#updateCompanyBtnText').text('Loading...');
        $('#updateCompanyBtnSpinner').removeClass('d-none');

        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + currentCompanyId,
            method: "GET",
            success: function(response) {
                let company;
                if (response && response.id) {
                    company = response;
                } else if (response && response.data && response.data.id) {
                    company = response.data;
                } else {
                    throw new Error('Invalid company data');
                }

                $('#editCompanyId').val(company.id);
                $('#editName').val(company.name || '');
                $('#editCityId').val(company.city_id || '');
                $('#editStateId').val(company.state_id || '');
                $('#editCountry').val(company.country_id || '');

                populateDomainDropdown();
                if (company.domain_id) {
                    $('#editDomainId').val(company.domain_id);
                }

                $('#shouldDeleteProfileImage').val('0');

                if (company.profile && company.profile !== 'null' && company.profile.trim() !== '') {
                    let imageUrl = company.profile_url;
                    if (!imageUrl && company.profile) {
                        const filename = company.profile.split('/').pop();
                        const encodedFilename = encodeURIComponent(filename);
                        imageUrl = `{{ config('app.api_url') }}companies/profile-image/${encodedFilename}`;
                    }

                    $('#editCompanyProfilePreview').attr('src', imageUrl).show();
                    $('#editCompanyImageWrapper').show();
                    $('#editCompanyProfilePlaceholder').hide();
                    $('#editCompanyExistingProfileImage').val(company.profile);
                } else {
                    $('#editCompanyImageWrapper').hide();
                    $('#editCompanyProfilePlaceholder').show();
                    $('#editCompanyExistingProfileImage').val('');
                }

                const editModal = new bootstrap.Modal(document.getElementById('editCompanyModal'));
                editModal.show();

                $('#updateCompanyBtn').prop('disabled', false);
                $('#updateCompanyBtnText').text('Update Company');
                $('#updateCompanyBtnSpinner').addClass('d-none');
            },
            error: function(xhr) {
                console.error('Error fetching company details:', xhr.responseText);

                $('#updateCompanyBtn').prop('disabled', false);
                $('#updateCompanyBtnText').text('Update Company');
                $('#updateDetailsBtnSpinner').addClass('d-none');

                $('#editCompanyError')
                    .removeClass('d-none')
                    .text('Failed to load company details. Please try again.');

                const editModal = new bootstrap.Modal(document.getElementById('editCompanyModal'));
                editModal.show();
            }
        });
    }

    // ✅ UPDATE COMPANY
    function updateCompany() {
        const companyId = $('#editCompanyId').val();
        if (!companyId) {
            showToast('Company ID not found', 'error');
            return;
        }

        const formData = {
            name: $('#editName').val().trim(),
            domain_id: $('#editDomainId').val(),
            country_id: $('#editCountry').val().trim(),
            state_id: $('#editStateId').val().trim(),
            city_id: $('#editCityId').val().trim()
        };

        const requiredFields = ['name', 'domain_id', 'country_id'];
        const missingFields = [];

        requiredFields.forEach(field => {
            if (!formData[field]) {
                missingFields.push(field.replace('_', ' '));
            }
        });

        if (missingFields.length > 0) {
            $('#editCompanyError')
                .removeClass('d-none')
                .text(`Required fields are missing: ${missingFields.join(', ')}`);
            return;
        }

        $('#editCompanyError').addClass('d-none');
        $('#editCompanySuccess').addClass('d-none');
        $('#updateCompanyBtn').prop('disabled', true);
        $('#updateCompanyBtnText').text('Updating...');
        $('#updateCompanyBtnSpinner').removeClass('d-none');

        const shouldDeleteImage = $('#shouldDeleteProfileImage').val() === '1';
        const existingProfileImage = $('#editCompanyExistingProfileImage').val();
        const profileImageInput = $('#editCompanyProfileImage')[0];

        if (shouldDeleteImage) {
            formData.profile = null;
            updateCompanyWithData(companyId, formData, true);
        }
        else if (profileImageInput.files && profileImageInput.files[0]) {
            const file = profileImageInput.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                formData.profile = e.target.result;
                updateCompanyWithData(companyId, formData, false);
            };

            reader.readAsDataURL(file);
        }
        else {
            formData.profile = existingProfileImage || null;
            updateCompanyWithData(companyId, formData, false);
        }
    }

    // ✅ UPDATE COMPANY WITH DATA - FIXED SIDEBAR UPDATE
    function updateCompanyWithData(companyId, formData, isImageDeleted = false) {
        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + companyId,
            method: "GET",
            success: function(companyResponse) {

                if (companyId == {{ Auth::user()->company_id ?? 0 }}) {
    // Agar updated logo hai to sidebar image change karo
    if (response.profile_url) {
        $('.logo-light .logo-lg img').attr('src', response.profile_url);
        $('.logo-dark .logo-lg img').attr('src', response.profile_url);
    }
}
                let company;
                if (companyResponse && companyResponse.id) {
                    company = companyResponse;
                } else if (companyResponse && companyResponse.data && companyResponse.data.id) {
                    company = companyResponse.data;
                }

                const updatePayload = {
                    ...company,
                    ...formData,
                    updated_by: 1,
                    updated_at: new Date().toISOString()
                };

                $.ajax({
                    url: "{{ config('app.api_url') }}companies/" + companyId,
                    method: "PUT",
                    contentType: "application/json",
                    data: JSON.stringify(updatePayload),
                    success: function(response) {
                        $('#editCompanySuccess')
                            .removeClass('d-none')
                            .html('<i class="bi bi-check-circle me-2"></i>Company updated successfully!');

                        $('#updateCompanyBtnText').text('Update Company');
                        $('#updateCompanyBtnSpinner').addClass('d-none');

                        const companyIndex = companies.findIndex(c => c.id == companyId);
                        if (companyIndex !== -1) {
                            companies[companyIndex] = {
                                ...companies[companyIndex],
                                ...formData
                            };

                            companies[companyIndex].domain_name = getDomainNameById(formData.domain_id);

                            if (isImageDeleted) {
                                companies[companyIndex].profile = null;
                                companies[companyIndex].profile_url = null;
                            }
                        }

                        setTimeout(() => {
                            const editModal = bootstrap.Modal.getInstance(document.getElementById('editCompanyModal'));
                            editModal.hide();

                            if (isAdmin) {
                                refreshCompanyInSidebar(companyId);
                            }

                            if (companyId) {
                                setTimeout(() => {
                                    if (isAdmin) {
                                        selectCompany(companyId);
                                    } else {
                                        fetchSingleCompany(companyId);
                                    }
                                    showToast('Company updated successfully', 'success');
                                }, 500);
                            }
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.error('Error updating company:', xhr.responseText);

                        let errorMessage = 'Failed to update company. Please try again.';

                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            } else if (errorResponse.error) {
                                errorMessage = errorResponse.error;
                            }

                            if (errorResponse.errors) {
                                const errorFields = Object.keys(errorResponse.errors);
                                errorMessage = `Validation error: ${errorFields.join(', ')}`;
                            }
                        } catch (e) {}

                        $('#editCompanyError')
                            .removeClass('d-none')
                            .text(errorMessage);

                        $('#updateCompanyBtn').prop('disabled', false);
                        $('#updateCompanyBtnText').text('Update Company');
                        $('#updateCompanyBtnSpinner').addClass('d-none');
                    }
                });
            },
            error: function(xhr) {
                console.error('Error fetching company for update:', xhr.responseText);

                $('#editCompanyError')
                    .removeClass('d-none')
                    .text('Failed to load company data for update.');

                $('#updateCompanyBtn').prop('disabled', false);
                $('#updateCompanyBtnText').text('Update Company');
                $('#updateCompanyBtnSpinner').addClass('d-none');
            }
        });
    }

    // ✅ REFRESH SINGLE COMPANY IN SIDEBAR
    function refreshCompanyInSidebar(companyId) {
        if (!isAdmin) return;

        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + companyId,
            method: "GET",
            success: function(response) {
                let company;
                if (response && response.id) {
                    company = response;
                } else if (response && response.data && response.data.id) {
                    company = response.data;
                }

                if (company) {
                    const companyIndex = companies.findIndex(c => c.id == companyId);
                    if (companyIndex !== -1) {
                        companies[companyIndex] = {
                            ...companies[companyIndex],
                            ...company
                        };

                        if (company.domain_id) {
                            companies[companyIndex].domain_name = getDomainNameById(company.domain_id);
                        }
                    }

                    updateSidebarItem(companyId, company);
                }
            },
            error: function(xhr) {
                console.error('Error refreshing company in sidebar:', xhr.responseText);
            }
        });
    }

    // ✅ UPDATE SIDEBAR ITEM
    function updateSidebarItem(companyId, company) {
        const companyItem = $(`.company-item[data-id="${companyId}"]`);
        if (companyItem.length === 0) return;

        const avatarText = company.name ? company.name.charAt(0).toUpperCase() : 'C';
        const domainText = company.domain_name || '';

        companyItem.find('.company-name').text(company.name || 'Unnamed Company');
        companyItem.find('.company-email').text(company.email || 'No email');

        let domainSpan = companyItem.find('.text-muted');
        if (domainText) {
            if (domainSpan.length > 0) {
                domainSpan.html(`<i class="bi bi-tag"></i> ${domainText}`);
            } else {
                companyItem.find('.company-info').append(`<small class="text-muted d-block mt-1"><i class="bi bi-tag"></i> ${domainText}</small>`);
            }
        } else {
            domainSpan.remove();
        }

        const currentAvatar = companyItem.find('.company-avatar, .company-avatar-img');
        if (company.profile && company.profile !== 'null' && company.profile.trim() !== '') {
            let imageUrl = company.profile_url;
            if (!imageUrl && company.profile) {
                const filename = company.profile.split('/').pop();
                const encodedFilename = encodeURIComponent(filename);
                imageUrl = `{{ config('app.api_url') }}companies/profile-image/${encodedFilename}`;
            }

            if (imageUrl) {
                if (currentAvatar.hasClass('company-avatar')) {
                    currentAvatar.replaceWith(`<img src="${imageUrl}" alt="${company.name || 'Company'}" class="company-avatar-img" onerror="handleSidebarImageError(this, '${avatarText}')">`);
                } else if (currentAvatar.hasClass('company-avatar-img')) {
                    currentAvatar.attr('src', imageUrl);
                }
            }
        } else {
            if (currentAvatar.hasClass('company-avatar-img')) {
                currentAvatar.replaceWith(`<div class="company-avatar">${avatarText}</div>`);
            } else if (currentAvatar.hasClass('company-avatar')) {
                currentAvatar.text(avatarText);
            }
        }
    }

    // ✅ FETCH ALL TICKETS 
    function fetchAllTickets() {

        const apiUrl = "{{ config('app.api_url') }}tickets";

        $.ajax({
            url: apiUrl,
            method: "GET",
            success: function(response) {
                console.log('Tickets API response:', response);

                if (response && Array.isArray(response)) {
                    allTickets = response;
                } else if (response && response.data && Array.isArray(response.data)) {
                    allTickets = response.data;
                } else if (response && response.tickets && Array.isArray(response.tickets)) {
                    allTickets = response.tickets;
                } else if (response && response.success && Array.isArray(response.tickets)) {
                    allTickets = response.tickets;
                } else if (response && response.success && response.data) {
                    if (Array.isArray(response.data)) {
                        allTickets = response.data;
                    } else if (response.data.tickets && Array.isArray(response.data.tickets)) {
                        allTickets = response.data.tickets;
                    }
                } else {
                    allTickets = [];
                }

                console.log('Total tickets loaded:', allTickets.length);

                if (allTickets.length > 0) {
                    console.log('First 3 tickets:', allTickets.slice(0, 3));
                }

                if (currentCompanyId) {
                    updateTicketCount(currentCompanyId);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching tickets:', error);
                console.log('XHR Response:', xhr.responseText);
                allTickets = [];

                $.ajax({
                    url: "/api/tickets",
                    method: "GET",
                    success: function(altResponse) {
                        console.log('Alternative tickets API response:', altResponse);

                        if (altResponse && Array.isArray(altResponse)) {
                            allTickets = altResponse;
                        } else if (altResponse && altResponse.data && Array.isArray(altResponse.data)) {
                            allTickets = altResponse.data;
                        } else if (altResponse && altResponse.tickets && Array.isArray(altResponse.tickets)) {
                            allTickets = altResponse.tickets;
                        }

                        console.log('Total tickets loaded from alternative:', allTickets.length);

                        if (currentCompanyId) {
                            updateTicketCount(currentCompanyId);
                        }
                    },
                    error: function() {
                        console.log('Both API endpoints failed');
                        allTickets = [];

                        if (currentCompanyId) {
                            updateTicketCount(currentCompanyId);
                        }
                    }
                });
            }
        });
    }

    // ✅ COUNT TICKETS FOR COMPANY
    function countTicketsForCompany(companyId) {
        // console.log('Counting tickets for company ID:', companyId);

        if (!allTickets || allTickets.length === 0) {
            console.log('No tickets available in allTickets array');
            return 0;
        }

        const companyTickets = allTickets.filter(ticket => {
            if (ticket.company_id && ticket.company_id == companyId) return true;
            if (ticket.companyId && ticket.companyId == companyId) return true;
            if (ticket.company && ticket.company == companyId) return true;
            if (ticket.customer_id && ticket.customer_id == companyId) return true;
            if (ticket.customerId && ticket.customerId == companyId) return true;

            if (ticket.customer && ticket.customer.id == companyId) return true;
            if (ticket.customer && ticket.customer.company_id == companyId) return true;
            if (ticket.company && ticket.company.id == companyId) return true;

            return false;
        });

        console.log(`Found ${companyTickets.length} tickets for company ${companyId}`);

        if (companyTickets.length === 0 && allTickets.length > 0) {
            console.log('All tickets structure sample:', allTickets[0]);

            const ticketsWithCompanyId = allTickets.filter(t => t.company_id);
            console.log('Tickets with company_id field:', ticketsWithCompanyId.length);
        }

        return companyTickets.length;
    }

    // ✅ UPDATE TICKET COUNT
    function updateTicketCount(companyId) {
        const ticketCount = countTicketsForCompany(companyId);
        // console.log(`Setting ticket count for company ${companyId}: ${ticketCount}`);

        $('#statTickets').text(ticketCount);

        const company = companies.find(c => c.id == companyId);
        if (company) {
            company.ticketCount = ticketCount;
        }
    }

    function loadCompanyProfile(companyId) {
        const company = companies.find(c => c.id == companyId);
        if (!company) {
            console.log('Company not found in companies array:', companyId);
            return;
        }

        console.log('Loading profile for company:', company.name);

        $('#defaultState').hide();
        $('#singleCompanyView').hide();
        $('#companyProfile').show();
        showLoading(false);

        updateTicketCount(companyId);

        const avatarImgContainer = $('#companyAvatarImgContainer');
        const avatarText = $('#companyAvatarText');
        const avatarImg = $('#companyAvatarImg');

        avatarImgContainer.hide();
        avatarText.hide();

        if (company.profile_url || (company.profile && company.profile !== 'null' && company.profile.trim() !== '')) {
            let imageUrl = company.profile_url;

            if (!imageUrl && company.profile) {
                const filename = company.profile.split('/').pop();
                const encodedFilename = encodeURIComponent(filename);
                imageUrl = `{{ config('app.api_url') }}companies/profile-image/${encodedFilename}`;
            }

            if (imageUrl) {
                avatarImg.html(`<img src="${imageUrl}" alt="${company.name || 'Company'}" class="profile-avatar-lg-img" onerror="handleProfileImageError(this)">`);
                avatarImgContainer.show();
            } else {
                const firstLetter = company.name ? company.name.charAt(0).toUpperCase() : 'C';
                avatarText.html(firstLetter).show();
            }
        } else {
            const firstLetter = company.name ? company.name.charAt(0).toUpperCase() : 'C';
            avatarText.html(firstLetter).show();
        }

        $('#companyName').text(company.name || 'Unnamed Company');

        const domainText = company.domain_name || getDomainNameById(company.domain_id) || 'Not specified';
        $('#companyIndustry').text(domainText);

        $('#companyLocation').text(formatLocation(company));
        $('#companySince').text(company.created_at ?
            new Date(company.created_at).getFullYear() : 'N/A');
        $('#companyStatus').text((company.active == 1 || company.status == 1) ? 'Active' : 'Inactive');

        $('#statLicenses').text(company.licences || '0');
        $('#statSubscription').text(getSubscriptionName(company.subscription_id));

        updateBusinessUnitCount(companyId);

        $('#infoRegistration').text(company.registration_number || 'N/A');
        if (company.website) {
            let websiteUrl = company.website;
            if (!websiteUrl.startsWith('http')) {
                websiteUrl = 'http://' + websiteUrl;
            }
            $('#infoWebsite').html(`<a href="${websiteUrl}" target="_blank" class="text-primary">${company.website}</a>`);
        } else {
            $('#infoWebsite').text('N/A');
        }
        $('#infoEstablished').text(company.created_at ?
            formatDate(company.created_at) : 'N/A');
        $('#infoContactPerson').text(
            `${company.first_name || ''} ${company.last_name || ''}`.trim() || 'N/A'
        );

        $('#companyDescription').text(company.comments || company.description || 'No description available');

        if (company.email) {
            $('#contactEmail').html(`<a href="mailto:${company.email}" class="text-decoration-none">${company.email}</a>`);
        } else {
            $('#contactEmail').text('N/A');
        }

        if (company.mobile || company.telephon) {
            $('#contactPhone').html(`<a href="tel:${company.mobile || company.telephon}" class="text-decoration-none">${company.mobile || company.telephon}</a>`);
        } else {
            $('#contactPhone').text('N/A');
        }

        if (company.support_email) {
            $('#supportEmail').html(`<a href="mailto:${company.support_email}" class="text-decoration-none">${company.support_email}</a>`);
        } else {
            $('#supportEmail').text('N/A');
        }

        if (company.support_contact) {
            $('#supportPhone').html(`<a href="tel:${company.support_contact}" class="text-decoration-none">${company.support_contact}</a>`);
        } else {
            $('#supportPhone').text('N/A');
        }

        $('#companyAddress').text(company.address_1 || 'Address not specified');
        $('#companyCityState').text(
            `${company.city_id || ''}${company.state_id ? ', ' + company.state_id : ''}`
        );
        $('#companyCountry').text(company.country_id || '');

        updateLocationMap(company);
        updateSubscriptionInfo();
    }

    // ✅ HANDLE PROFILE IMAGE ERROR
    function handleProfileImageError(imgElement) {
        const company = companies.find(c => c.id == currentCompanyId);
        if (!company) return;

        const firstLetter = company.name ? company.name.charAt(0).toUpperCase() : 'C';

        $('#companyAvatarImgContainer').hide();
        $('#companyAvatarText').html(firstLetter).show();

        // Update sidebar if admin
        if (isAdmin) {
            updateSidebarAvatar(currentCompanyId, firstLetter);
        }
    }

    // ✅ HELPER FUNCTIONS
    function fetchDomains() {
        return new Promise((resolve) => {
            $.ajax({
                url: "{{ config('app.api_url') }}domains",
                method: "GET",
                success: function(response) {
                    if (response && Array.isArray(response)) {
                        domains = response;
                    } else if (response && response.data && Array.isArray(response.data)) {
                        domains = response.data;
                    } else if (response && response.domains && Array.isArray(response.domains)) {
                        domains = response.domains;
                    } else {
                        domains = [];
                    }

                    domains.forEach(domain => {
                        const domainId = domain.id || domain.domain_id;
                        const domainName = domain.title || domain.name || `Domain ${domainId}`;
                        if (domainId) {
                            domainMap[domainId] = domainName;
                        }
                    });

                    populateDomainDropdown();
                    resolve();
                },
                error: function() {
                    domains = [];
                    domainMap = {};
                    populateDomainDropdown();
                    resolve();
                }
            });
        });
    }

    function populateDomainDropdown() {
        const dropdown = $('#editDomainId');
        dropdown.empty();
        dropdown.append('<option value="">Select Domain</option>');

        if (domains.length === 0) {
            dropdown.append('<option value="" disabled>No domains available</option>');
            return;
        }

        domains.forEach(domain => {
            const domainName = domain.title || domain.name || `Domain ${domain.id}`;
            const domainId = domain.id || domain.domain_id;

            if (domainId && domainName) {
                dropdown.append(`<option value="${domainId}">${domainName}</option>`);
            }
        });
    }

    function getDomainNameById(domainId) {
        if (!domainId) return '';

        if (domainMap[domainId]) {
            return domainMap[domainId];
        }

        const domain = domains.find(d =>
            (d.id == domainId) ||
            (d.domain_id == domainId)
        );

        return domain ? (domain.title || domain.name || `Domain ${domainId}`) : '';
    }

    function fetchSubscriptionPackages() {
    $.ajax({
        url: "{{ config('app.api_url') }}subscription-packages",
        method: "GET",
        success: function(response) {
            if (response && Array.isArray(response)) {
                subscriptionPackages = response;
            } else if (response && response.data && Array.isArray(response.data)) {
                subscriptionPackages = response.data;
            } else {
                subscriptionPackages = [];
            }

            // Populate subscription dropdown
            populateSubscriptionDropdown();

            if (currentCompanyId) {
                updateSubscriptionInfo();
            }
        },
        error: function() {
            subscriptionPackages = [];
        }
    });
}

function populateSubscriptionDropdown() {
    const dropdown = $('#subscriptionSelect');
    dropdown.empty();
    dropdown.append('<option value="">Select Subscription</option>');

    subscriptionPackages.forEach(pkg => {
        dropdown.append(`<option value="${pkg.id}">${pkg.title} ($${pkg.price})</option>`);
    });
}

    function getSubscriptionPackageById(subscriptionId) {
        if (!subscriptionId || !subscriptionPackages || subscriptionPackages.length === 0) {
            return null;
        }

        return subscriptionPackages.find(pkg => pkg.id == subscriptionId);
    }

    function fetchAllBusinessUnits() {
        $.ajax({
            url: "{{ config('app.api_url') }}business_units",
            method: "GET",
            success: function(response) {
                if (response && Array.isArray(response)) {
                    allBusinessUnits = response;
                } else if (response && response.data && Array.isArray(response.data)) {
                    allBusinessUnits = response.data;
                } else if (response && response.business_units && Array.isArray(response.business_units)) {
                    allBusinessUnits = response.business_units;
                } else {
                    allBusinessUnits = [];
                }

                if (currentCompanyId) {
                    updateBusinessUnitCount(currentCompanyId);
                }
            },
            error: function() {
                allBusinessUnits = [];
            }
        });
    }

    function countBusinessUnitsForCompany(companyId) {
        if (!allBusinessUnits || allBusinessUnits.length === 0) {
            return 0;
        }

        const companyBusinessUnits = allBusinessUnits.filter(bu => bu.company_id == companyId);
        return companyBusinessUnits.length;
    }

    function updateBusinessUnitCount(companyId) {
        const buCount = countBusinessUnitsForCompany(companyId);
        $('#statBusinessUnits').text(buCount);

        const company = companies.find(c => c.id == companyId);
        if (company) {
            company.businessUnitCount = buCount;
        }
    }

    function setupEventListeners() {
        if (isAdmin) {
            $('#companySearch').on('keyup', function() {
                filterCompanyList($(this).val().toLowerCase());
            });
        }
    }

    function filterCompanyList(searchTerm) {
        if (!isAdmin) return;

        if (!searchTerm) {
            renderCompanyList(companies);
            return;
        }

        const filtered = companies.filter(company =>
            (company.name && company.name.toLowerCase().includes(searchTerm)) ||
            (company.email && company.email.toLowerCase().includes(searchTerm)) ||
            (company.registration_number && company.registration_number.toLowerCase().includes(searchTerm)) ||
            (company.domain_name && company.domain_name.toLowerCase().includes(searchTerm))
        );

        renderCompanyList(filtered);
    }

    function deletePreviewImage() {
        $('#editCompanyImageWrapper').hide();
        $('#editCompanyProfilePlaceholder').show();
        $('#editCompanyProfileImage').val('');
        $('#shouldDeleteProfileImage').val('1');
        $('#editCompanyExistingProfileImage').val('');
        $('#editCompanyProfilePreview').attr('src', '');

        showToast('Image removed', 'info');
    }

    function previewCompanyProfileImage(input) {
        const wrapper = document.getElementById('editCompanyImageWrapper');
        const preview = document.getElementById('editCompanyProfilePreview');
        const placeholder = document.getElementById('editCompanyProfilePlaceholder');
        const file = input.files[0];

        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                showToast('File too large (max 2MB)', 'error');
                input.value = '';
                return;
            }

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showToast('Only JPG, PNG, GIF allowed', 'error');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                wrapper.style.display = 'block';
                placeholder.style.display = 'none';

                $('#shouldDeleteProfileImage').val('0');
                $('#editCompanyExistingProfileImage').val('');
            }

            reader.readAsDataURL(file);
        } else {
            wrapper.style.display = 'none';
            placeholder.style.display = 'flex';
        }
    }

    function showToast(message, type = 'success') {
        $('.toast-notification').remove();

        const icon = type === 'success' ? 'bi-check-circle' :
                     type === 'error' ? 'bi-x-circle' :
                     type === 'info' ? 'bi-info-circle' : 'bi-exclamation-circle';

        const toast = $(`
            <div class="toast-notification ${type}">
                <i class="bi ${icon}"></i>
                <span>${message}</span>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.css('animation', 'slideOutRight 0.3s ease');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    $('#createInvoiceForm').on('submit', function(e) {
    e.preventDefault();

    if (currentEditingInvoiceId) {
        updateInvoice(currentEditingInvoiceId);
    } else {
        createNewInvoice();
    }
});

// ✅ CREATE NEW INVOICE FUNCTION
function createNewInvoice() {
    let items = [];
    $('.item-section').each(function() {
        const sectionId = $(this).attr('id').split('_')[1];
        const itemName = $(this).find('.item-name-field').val();
        const quantity = parseFloat($(this).find('.quantity-field').val()) || 0;
        const unitPrice = parseFloat($(this).find('.unit-price-field').val()) || 0;
        const tax = parseFloat($(this).find('.tax-field').val()) || 0;
        const amount = parseFloat($(this).find('.amount-field').val()) || 0;

        if (itemName && quantity > 0 && unitPrice > 0) {
            items.push({
                name: itemName,
                quantity: quantity,
                unit_price: unitPrice,
                tax: tax,
                amount: amount,
                description: $(this).find('.description-field').val()
            });
        }
    });

    const subTotal = parseFloat($('#summarySubTotal').text().replace(/[^0-9.-]+/g, '')) || 0;
    const taxTotal = parseFloat($('#summaryTax').text().replace(/[^0-9.-]+/g, '')) || 0;
    const total = parseFloat($('#summaryTotal').text().replace(/[^0-9.-]+/g, '')) || 0;
    const discount = parseFloat($('#discountAmount').val()) || 0;

    const formData = {
        invoice_date: $('#invoiceDate').val(),
        due_date: $('#dueDate').val(),
        company_id: $('#clientSelect').val(),
        subscription_id: $('#subscriptionSelect').val() || null,
        project_name: $('#projectName').val(),
        status: $('#invoiceStatus').val() || 'pending',
        currency: $('#currency').val(),
        amount: total,
        sub_total: subTotal,
        tax_amount: taxTotal,
        discount_amount: discount,
        paid_amount: parseFloat($('#paidAmount').val()) || 0,
        paid_date: $('#paidDate').val() || null,
        payment_method: $('#paymentMethod').val(),
        transaction_id: $('#transactionId').val(),
        bank_account: $('#bankAccount').val(),
        payment_details: $('#paymentDetails').val(),
        billing_address: $('#billingAddress').val(),
        items: items,
        notes: $('#invoiceNotes').val(),
        terms: $('#invoiceTerms').val(),
        generated_by: {{ auth()->id() ?? 1 }}
    };

    if (formData.invoice_date) {
        const date = new Date(formData.invoice_date);
        formData.invoice_month = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-01`;
    }

    if (!formData.company_id) {
        showToast('Please select a client', 'error');
        return;
    }

    if (!formData.invoice_date || !formData.due_date) {
        showToast('Please select invoice date and due date', 'error');
        return;
    }

    if (items.length === 0) {
        showToast('Please add at least one item', 'error');
        return;
    }

    console.log('Creating invoice with data:', formData);

    showToast('Creating invoice...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            showToast('Invoice created successfully', 'success');

            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('createInvoiceOffcanvas'));
            offcanvas.hide();

            fetchInvoices();

            resetInvoiceForm();
        },
        error: function(xhr) {
            console.error('Failed to create invoice:', xhr.responseText);
            let errorMessage = 'Failed to create invoice';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMessage = response.message || errorMessage;
            } catch (e) {}
            showToast(errorMessage, 'error');
        }
    });
}

// ✅ UPDATE INVOICE FUNCTION
function updateInvoice(invoiceId) {
    let items = [];
    $('.item-section').each(function() {
        const sectionId = $(this).attr('id').split('_')[1];
        items.push({
            name: $(this).find('.item-name-field').val(),
            quantity: parseFloat($(this).find('.quantity-field').val()) || 0,
            unit_price: parseFloat($(this).find('.unit-price-field').val()) || 0,
            tax: parseFloat($(this).find('.tax-field').val()) || 0,
            amount: parseFloat($(this).find('.amount-field').val()) || 0,
            description: $(this).find('.description-field').val()
        });
    });

    const subTotal = parseFloat($('#summarySubTotal').text().replace(/[^0-9.-]+/g, '')) || 0;
    const taxTotal = parseFloat($('#summaryTax').text().replace(/[^0-9.-]+/g, '')) || 0;
    const total = parseFloat($('#summaryTotal').text().replace(/[^0-9.-]+/g, '')) || 0;
    const discount = parseFloat($('#discountAmount').val()) || 0;

    const formData = {
        invoice_date: $('#invoiceDate').val(),
        due_date: $('#dueDate').val(),
        subscription_id: $('#subscriptionSelect').val() || null,
        project_name: $('#projectName').val(),
        status: $('#invoiceStatus').val(),
        currency: $('#currency').val(),
        amount: total,
        sub_total: subTotal,
        tax_amount: taxTotal,
        discount_amount: discount,
        paid_amount: parseFloat($('#paidAmount').val()) || 0,
        paid_date: $('#paidDate').val() || null,
        payment_method: $('#paymentMethod').val(),
        transaction_id: $('#transactionId').val(),
        bank_account: $('#bankAccount').val(),
        payment_details: $('#paymentDetails').val(),
        billing_address: $('#billingAddress').val(),
        items: items,
        notes: $('#invoiceNotes').val(),
        terms: $('#invoiceTerms').val()
    };

    if (formData.invoice_date) {
        const date = new Date(formData.invoice_date);
        formData.invoice_month = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-01`;
    }

    showToast('Updating invoice...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            showToast('Invoice updated successfully', 'success');

            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('createInvoiceOffcanvas'));
            offcanvas.hide();

            fetchInvoices();

            resetInvoiceForm();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.message || 'Failed to update invoice';
            showToast(error, 'error');
        }
    });
}

    // ✅ EDIT DETAILS (Permission Check)
    function editDetail() {
        if (!currentCompanyId) {
            showToast('No company selected', 'error');
            return;
        }

        if (!canEditCompany) {
            showToast('You do not have permission to edit company details', 'error');
            return;
        }

        $('#editDetailsError').addClass('d-none');
        $('#editDetailsSuccess').addClass('d-none');
        $('#updateDetailsBtn').prop('disabled', true);
        $('#updateDetailsBtnText').text('Loading...');
        $('#updateDetailsBtnSpinner').removeClass('d-none');

        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + currentCompanyId,
            method: "GET",
            success: function(response) {
                let company;
                if (response && response.id) {
                    company = response;
                } else if (response && response.data && response.data.id) {
                    company = response.data;
                } else {
                    throw new Error('Invalid company data');
                }

                $('#editDetailsCompanyId').val(company.id);
                $('#editRegistrationNumber').val(company.registration_number || '');
                $('#editWebsite').val(company.website || '');

                if (company.created_at) {
                    const createdDate = new Date(company.created_at);
                    $('#editCreatedAt').val(createdDate.toISOString().split('T')[0]);
                }

                $('#editFirstName').val(company.first_name || '');
                $('#editMiddleName').val(company.middle_name || '');
                $('#editLastName').val(company.last_name || '');
                $('#editDescription').val(company.comments || company.description || '');
                $('#editPrimaryEmail').val(company.email || '');
                $('#editPhone').val(company.telephon || '');
                $('#editMobile').val(company.mobile || '');
                $('#editSupportEmail').val(company.support_email || '');
                $('#editSupportContact').val(company.support_contact || '');
                $('#editLanguage').val(company.language || '');
                $('#editAddress1').val(company.address_1 || '');
                $('#editAddress2').val(company.address_2 || '');
                $('#editCity').val(company.city_id || '');
                $('#editState').val(company.state_id || '');
                $('#editCountryDetails').val(company.country_id || '');
                $('#editZipcode').val(company.zipcode || '');
                $('#editRegion').val(company.region || '');

                const editDetailsModal = new bootstrap.Modal(document.getElementById('editDetailsModal'));
                editDetailsModal.show();

                $('#updateDetailsBtn').prop('disabled', false);
                $('#updateDetailsBtnText').text('Update Details');
                $('#updateDetailsBtnSpinner').addClass('d-none');
            },
            error: function(xhr) {
                console.error('Error fetching company details for edit:', xhr.responseText);

                $('#updateDetailsBtn').prop('disabled', false);
                $('#updateDetailsBtnText').text('Update Details');
                $('#updateDetailsBtnSpinner').addClass('d-none');

                $('#editDetailsError')
                    .removeClass('d-none')
                    .text('Failed to load company details. Please try again.');

                const editDetailsModal = new bootstrap.Modal(document.getElementById('editDetailsModal'));
                editDetailsModal.show();
            }
        });
    }

    function updateCompanyDetails() {
        const companyId = $('#editDetailsCompanyId').val();
        if (!companyId) {
            showToast('Company ID not found', 'error');
            return;
        }

        const formData = {
            registration_number: $('#editRegistrationNumber').val().trim(),
            website: $('#editWebsite').val().trim(),
            created_at: $('#editCreatedAt').val(),
            first_name: $('#editFirstName').val().trim(),
            middle_name: $('#editMiddleName').val().trim(),
            last_name: $('#editLastName').val().trim(),
            comments: $('#editDescription').val().trim(),
            email: $('#editPrimaryEmail').val().trim(),
            telephon: $('#editPhone').val().trim(),
            mobile: $('#editMobile').val().trim(),
            support_email: $('#editSupportEmail').val().trim(),
            support_contact: $('#editSupportContact').val().trim(),
            language: $('#editLanguage').val().trim(),
            address_1: $('#editAddress1').val().trim(),
            address_2: $('#editAddress2').val().trim(),
            city_id: $('#editCity').val().trim(),
            state_id: $('#editState').val().trim(),
            country_id: $('#editCountryDetails').val().trim(),
            zipcode: $('#editZipcode').val().trim(),
            region: $('#editRegion').val().trim()
        };

        if (!formData.email) {
            $('#editDetailsError')
                .removeClass('d-none')
                .text('Primary Email is required');
            return;
        }

        $('#editDetailsError').addClass('d-none');
        $('#editDetailsSuccess').addClass('d-none');
        $('#updateDetailsBtn').prop('disabled', true);
        $('#updateDetailsBtnText').text('Updating...');
        $('#updateDetailsBtnSpinner').removeClass('d-none');

        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + companyId,
            method: "GET",
            success: function(companyResponse) {
                let company;
                if (companyResponse && companyResponse.id) {
                    company = companyResponse;
                } else if (companyResponse && companyResponse.data && companyResponse.data.id) {
                    company = companyResponse.data;
                }

                const updatePayload = {
                    ...company,
                    ...formData,
                    updated_by: 1,
                    updated_at: new Date().toISOString()
                };

                $.ajax({
                    url: "{{ config('app.api_url') }}companies/" + companyId,
                    method: "PUT",
                    contentType: "application/json",
                    data: JSON.stringify(updatePayload),
                    success: function(response) {
                        $('#editDetailsSuccess')
                            .removeClass('d-none')
                            .html('<i class="bi bi-check-circle me-2"></i>Company details updated successfully!');

                        $('#updateDetailsBtnText').text('Update Details');
                        $('#updateDetailsBtnSpinner').addClass('d-none');

                        const companyIndex = companies.findIndex(c => c.id == companyId);
                        if (companyIndex !== -1) {
                            companies[companyIndex] = {
                                ...companies[companyIndex],
                                ...formData
                            };
                        }

                        setTimeout(() => {
                            const editDetailsModal = bootstrap.Modal.getInstance(document.getElementById('editDetailsModal'));
                            editDetailsModal.hide();

                            if (isAdmin) {
                                refreshCompanyInSidebar(companyId);
                            }

                            if (companyId) {
                                setTimeout(() => {
                                    if (isAdmin) {
                                        selectCompany(companyId);
                                    } else {
                                        fetchSingleCompany(companyId);
                                    }
                                    showToast('Company details updated', 'success');
                                }, 500);
                            }
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.error('Error updating company details:', xhr.responseText);

                        let errorMessage = 'Failed to update company details. Please try again.';

                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            } else if (errorResponse.error) {
                                errorMessage = errorResponse.error;
                            }

                            if (errorResponse.errors) {
                                const errorFields = Object.keys(errorResponse.errors);
                                errorMessage = `Validation error: ${errorFields.join(', ')}`;
                            }
                        } catch (e) {}

                        $('#editDetailsError')
                            .removeClass('d-none')
                            .text(errorMessage);

                        $('#updateDetailsBtn').prop('disabled', false);
                        $('#updateDetailsBtnText').text('Update Details');
                        $('#updateDetailsBtnSpinner').addClass('d-none');
                    }
                });
            },
            error: function(xhr) {
                console.error('Error fetching company for update:', xhr.responseText);

                $('#editDetailsError')
                    .removeClass('d-none')
                    .text('Failed to load company data for update.');

                $('#updateDetailsBtn').prop('disabled', false);
                $('#updateDetailsBtnText').text('Update Details');
                $('#updateDetailsBtnSpinner').addClass('d-none');
            }
        });
    }

    function fetchInvoices() {
    if (!currentCompanyId) return;

    $('#invoicesLoading').show();
    $('#noInvoices').hide();

    $.ajax({
        url: "{{ config('app.api_url') }}companies/" + currentCompanyId + "/invoices",
        method: "GET",
        success: function(response) {
            $('#invoicesLoading').hide();

            let invoiceData = [];
            if (response.success && response.data) {
                invoiceData = response.data;
            } else if (Array.isArray(response)) {
                invoiceData = response;
            } else if (response.data && Array.isArray(response.data)) {
                invoiceData = response.data;
            }

            invoices = invoiceData;

            renderInvoicesTable(invoiceData);

            updateInvoiceBarChart();
        },
        error: function(xhr) {
            $('#invoicesLoading').hide();
            console.error('Failed to fetch invoices:', xhr.responseText);
            showToast('Failed to load invoices', 'error');
            renderInvoicesTable([]);
            invoices = [];
            updateInvoiceBarChart();
        }
    });
}

function renderInvoicesTable(invoiceList) {
    const tbody = $('#invoicesTableBody');
    const loadingRow = $('#invoicesLoading');
    const noInvoiceRow = $('#noInvoices');

    loadingRow.hide();
    tbody.find('tr:not(#invoicesLoading):not(#noInvoices)').remove();

    if (!invoiceList || invoiceList.length === 0) {
        noInvoiceRow.show();
        noInvoiceRow.find('td').attr('colspan', '9');
        noInvoiceRow.find('td').html(`
            <i class="bi bi-receipt" style="font-size: 3rem; opacity: 0.3;"></i>
            <h6 class="mt-3 mb-2">No Invoices Found</h6>
            <p class="mb-0">No invoices found for this company.</p>
        `);
        return;
    }

    noInvoiceRow.hide();

    const recentInvoices = invoiceList.slice(0, 5);

    recentInvoices.forEach(invoice => {
        const invoiceNumber = invoice.invoice_number || `INV-${invoice.id}`;
        const amount = parseFloat(invoice.amount || 0);
        const invoiceDate = invoice.invoice_date || invoice.created_at;
        const dueDate = invoice.due_date || '';
        const status = invoice.status || 'pending';
        const statusClass = getInvoiceStatusClass(status);
        const statusText = getInvoiceStatusText(status);

        const formattedDueDate = formatDueDate(dueDate, status);

        const dropdownItems = [
            { icon: 'bi-eye-fill', text: 'View', action: `viewInvoice(${invoice.id})`, color: 'text-primary' },
            { icon: 'bi-cloud-arrow-down-fill', text: 'Download', action: `downloadInvoice(${invoice.id})`, color: 'text-success' },
            { icon: 'bi-send-fill', text: 'Send', action: `sendInvoice(${invoice.id})`, color: 'text-info' },
            { icon: 'bi-pencil-square', text: 'Edit', action: `editInvoice(${invoice.id})`, color: 'text-warning' },
            { icon: 'bi-cash-stack', text: 'Add Payment', action: `addPayment(${invoice.id})`, color: 'text-success' },
            { icon: 'bi-x-octagon-fill', text: 'Cancel', action: `cancelInvoice(${invoice.id})`, color: 'text-danger' },
            { icon: 'bi-bell-fill', text: 'Payment Reminder', action: `paymentReminder(${invoice.id})`, color: 'text-warning' },
        ];

        let dropdownHtml = '<div class="dropdown invoice-dropdown">';
        dropdownHtml += '<button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">';
        dropdownHtml += '<i class="bi bi-three-dots-vertical"></i>';
        dropdownHtml += '</button>';
        dropdownHtml += '<ul class="dropdown-menu dropdown-menu-end">';

        dropdownItems.forEach(item => {
            dropdownHtml += `<li><a class="dropdown-item" href="#" onclick="${item.action}">`;
            dropdownHtml += `<i class="bi ${item.icon} me-2"></i>${item.text}`;
            dropdownHtml += '</a></li>';
        });

        dropdownHtml += '</ul></div>';

        // ✅ YAHAN PAR SUBSCRIPTION TYPE DYNAMIC HOGA
        const subscriptionType = getSubscriptionType(invoice);
        const subscriptionBadge = getSubscriptionBadge(invoice);
        const subscriptionPeriod = getSubscriptionPeriod(invoice);

        const row = $(`
            <tr>
                <td><span class="fw-semibold">${invoice.id}</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="invoice-icon ${statusClass}">
                            ${getInvoiceStatusIcon(status)}
                        </div>
                        <div>
                            <div class="invoice-number">${invoiceNumber}</div>
                            <small class="text-muted">${invoice.invoice_month ? new Date(invoice.invoice_month).toLocaleString('default', { month: 'long', year: 'numeric' }) : ''}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center flex-wrap">
                        <span class="project-name fw-semibold">${subscriptionType}</span>
                        ${subscriptionBadge}
                        ${subscriptionPeriod}
                    </div>
                </td>
                <td>
                    <span class="client-name">${invoice.company_name || 'N/A'}</span>
                </td>
                <td>
                    <div class="invoice-amount">$${amount.toFixed(2)}</div>
                </td>
                <td>
                    <div class="invoice-date">${invoiceDate ? formatDate(invoiceDate) : 'N/A'}</div>
                </td>
                <td>
                    <div class="invoice-due-date">${formattedDueDate}</div>
                </td>
                <td>
                    <span class="invoice-status-badge status-badge-${statusClass}">
                        <i class="bi ${getStatusIcon(status)} me-1"></i>
                        ${statusText}
                    </span>
                </td>
                <td>${dropdownHtml}</td>
            </tr>
        `);

        tbody.append(row);
    });
}

// ✅ HELPER FUNCTION 1: Subscription Type nikalne ke liye
function getSubscriptionType(invoice) {
    // Pehle check karo ke project name hai?
    if (invoice.project_name && invoice.project_name.trim() !== '') {
        return invoice.project_name;
    }

    // Agar subscription title hai to wo do
    if (invoice.subscription_title && invoice.subscription_title.trim() !== '') {
        return invoice.subscription_title;
    }

    // Amount ke hisaab se detect karo
    const amount = parseFloat(invoice.amount || 0);

    if (amount >= 1000) {
        return 'Enterprise Plan';
    } else if (amount >= 500) {
        return 'Professional Plan';
    } else if (amount >= 200) {
        return 'Business Plan';
    } else if (amount >= 50) {
        return 'Starter Plan';
    } else if (amount > 0) {
        return 'Basic Plan';
    }

    // Kuch bhi nahi to default
    return 'Standard Subscription';
}

// ✅ HELPER FUNCTION 2: Badge dikhane ke liye (VIP, Pro, New)
function getSubscriptionBadge(invoice) {
    const type = getSubscriptionType(invoice).toLowerCase();
    const amount = parseFloat(invoice.amount || 0);

    if (type.includes('enterprise') || amount >= 1000) {
        return '<span class="badge bg-primary bg-opacity-10 text-primary ms-2" style="font-size: 10px;">VIP</span>';
    }
    else if (type.includes('professional') || amount >= 500) {
        return '<span class="badge bg-info bg-opacity-10 text-info ms-2" style="font-size: 10px;">Pro</span>';
    }
    else if (type.includes('starter') || amount < 100) {
        return '<span class="badge bg-success bg-opacity-10 text-success ms-2" style="font-size: 10px;">New</span>';
    }

    return ''; // koi badge nahi
}

// ✅ HELPER FUNCTION 3: Period dikhane ke liye (Jan 2026, Feb 2026)
function getSubscriptionPeriod(invoice) {
    if (invoice.invoice_month) {
        try {
            const date = new Date(invoice.invoice_month);
            if (!isNaN(date.getTime())) {
                const month = date.toLocaleString('default', { month: 'short' });
                const year = date.getFullYear();
                return `<small class="d-block text-muted" style="font-size: 11px; margin-top: 2px;">${month} ${year}</small>`;
            }
        } catch (e) {
            console.log('Date parse error:', e);
        }
    }

    // Agar invoice_month nahi to invoice_date se month nikaalo
    if (invoice.invoice_date) {
        try {
            const date = new Date(invoice.invoice_date);
            if (!isNaN(date.getTime())) {
                const month = date.toLocaleString('default', { month: 'short' });
                const year = date.getFullYear();
                return `<small class="d-block text-muted" style="font-size: 11px; margin-top: 2px;">${month} ${year}</small>`;
            }
        } catch (e) {}
    }

    return ''; // period nahi mila
}

// ✅ CHECK IF INVOICE IS OVERDUE
function isInvoiceOverdue(dueDate, status) {
    if (!dueDate || status === 'paid' || status === 'cancelled') return false;

    try {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const due = new Date(dueDate);
        due.setHours(0, 0, 0, 0);

        return due < today;
    } catch (e) {
        return false;
    }
}

// ✅ FORMAT DUE DATE WITH OVERDUE INDICATOR
function formatDueDate(dueDate, status) {
    if (!dueDate) return 'N/A';

    try {
        const date = new Date(dueDate);
        const formatted = date.toLocaleDateString('en-US', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

        const isOverdue = isInvoiceOverdue(dueDate, status);

        if (isOverdue) {
            return `<span class="text-danger fw-bold" title="Payment overdue!">${formatted} <i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 0.8rem;"></i></span>`;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        if (date.getTime() === today.getTime()) {
            return `<span class="text-warning fw-bold" title="Due today!">${formatted} <i class="bi bi-clock-fill text-warning"></i></span>`;
        } else if (date.getTime() === tomorrow.getTime()) {
            return `<span class="text-info fw-bold" title="Due tomorrow">${formatted}</span>`;
        }

        return formatted;
    } catch (e) {
        return dueDate;
    }
}

function sendInvoice(invoiceId) {
    // showToast('Loading invoice details...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}`,
        method: 'GET',
        success: function(response) {
            let invoice = response.data || response;

            let companyEmail = '';

            if (invoice.company && invoice.company.email) {
                companyEmail = invoice.company.email;
            }
            else if (invoice.company_id && companies.length > 0) {
                let company = companies.find(c => c.id == invoice.company_id);
                if (company && company.email) {
                    companyEmail = company.email;
                }
            }

            Swal.fire({
                title: 'Send Invoice',
                html: `
                    <div class="text-start">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="sendEmail" class="form-control mb-3"
                               placeholder="Enter email" value="${companyEmail}">

                        <label class="form-label fw-semibold">Message (Optional)</label>
                        <textarea id="sendMessage" class="form-control" rows="3"
                                  placeholder="Add a message...">Please find attached invoice for your reference.</textarea>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="attachPDF" checked>
                            <label class="form-check-label" for="attachPDF">
                                Attach PDF copy
                            </label>
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="sendCopy">
                            <label class="form-check-label" for="sendCopy">
                                Send me a copy (BCC)
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4a6cf7',
                width: '500px',
                preConfirm: () => {
                    const email = document.getElementById('sendEmail').value;
                    const message = document.getElementById('sendMessage').value;

                    if (!email) {
                        Swal.showValidationMessage('Email is required');
                        return false;
                    }

                    if (!isValidEmail(email)) {
                        Swal.showValidationMessage('Please enter a valid email');
                        return false;
                    }

                    return {
                        email: email,
                        message: message,
                        attach_pdf: document.getElementById('attachPDF').checked ? 1 : 0,
                        send_copy: document.getElementById('sendCopy').checked ? 1 : 0
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    sendInvoiceEmail(invoiceId, result.value);
                }
            });
        },
        error: function(xhr) {
            console.error('Failed to load invoice:', xhr.responseText);
            showToast('Failed to load invoice details', 'error');
        }
    });
}

// ✅ BACKEND KO EMAIL SEND KARNE WALA FUNCTION
function sendInvoiceEmail(invoiceId, data) {
    showToast('Sending invoice...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}/send`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            showToast('Invoice sent successfully to ' + data.email, 'success');
        },
        error: function(xhr) {
            console.error('Failed to send invoice:', xhr.responseText);

            let errorMessage = 'Failed to send invoice';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {}

            showToast(errorMessage, 'error');
        }
    });
}

// ✅ EMAIL VALIDATION FUNCTION
function isValidEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// ✅ EDIT INVOICE FUNCTION - WITH FIX
function editInvoice(invoiceId) {
    currentEditingInvoiceId = invoiceId;

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}`,
        method: 'GET',
        success: function(response) {
            console.log('Invoice API Response:', response);

            let invoiceData;

            if (response.success && response.data) {
                invoiceData = response.data;
            } else if (response.data) {
                invoiceData = response.data;
            } else if (response.id) {
                invoiceData = response;
            } else {
                console.error('Unexpected response structure:', response);
                showToast('Invalid response format', 'error');
                return;
            }

            console.log('Processed Invoice Data:', invoiceData);

            resetInvoiceForm();

            $('#invoiceNumber').val(invoiceData.invoice_number || '');

            if (invoiceData.invoice_date) {
                const invDate = new Date(invoiceData.invoice_date);
                if (!isNaN(invDate.getTime())) {
                    $('#invoiceDate').val(invDate.toISOString().split('T')[0]);
                }
            }

            if (invoiceData.due_date) {
                const dueDate = new Date(invoiceData.due_date);
                if (!isNaN(dueDate.getTime())) {
                    $('#dueDate').val(dueDate.toISOString().split('T')[0]);
                }
            }

            if (invoiceData.company_id) {
                $('#clientSelect').val(invoiceData.company_id);
                loadCompanyBillingAddress(invoiceData.company_id);
            }

            if (invoiceData.subscription_id) {
                $('#subscriptionSelect').val(invoiceData.subscription_id);
            }

            // ✅ FIX: Amount ko safely parse karo
            let amount = 0;
            if (invoiceData.amount) {
                if (typeof invoiceData.amount === 'string') {
                    amount = parseFloat(invoiceData.amount) || 0;
                } else if (typeof invoiceData.amount === 'number') {
                    amount = invoiceData.amount;
                }
            }

            if (amount > 0) {
                addDefaultItemWithAmount(amount);
            }

            if (invoiceData.status) {
                $('#invoiceStatus').val(invoiceData.status);
            }

            if (invoiceData.paid_amount) {
                $('#paidAmount').val(invoiceData.paid_amount);
            }

            if (invoiceData.paid_date) {
                const paidDate = new Date(invoiceData.paid_date);
                if (!isNaN(paidDate.getTime())) {
                    $('#paidDate').val(paidDate.toISOString().split('T')[0]);
                }
            }

            if (invoiceData.invoice_month) {
                const invMonth = new Date(invoiceData.invoice_month);
                if (!isNaN(invMonth.getTime())) {
                    const year = invMonth.getFullYear();
                    const month = String(invMonth.getMonth() + 1).padStart(2, '0');
                    $('#invoiceMonth').val(`${year}-${month}`);
                }
            }

            if (invoiceData.notes) {
                $('#invoiceNotes').val(invoiceData.notes);
            }

            if (invoiceData.terms) {
                $('#invoiceTerms').val(invoiceData.terms);
            }

            $('#createInvoiceOffcanvasLabel').html(`
                <i class="bi bi-pencil-square me-2"></i>
                Edit Invoice #${invoiceData.invoice_number || invoiceId}
            `);

            $('.offcanvas-footer .btn-primary').text('Update Invoice');

            const offcanvas = new bootstrap.Offcanvas(document.getElementById('createInvoiceOffcanvas'));
            offcanvas.show();

        },
        error: function(xhr) {
            console.error('Failed to load invoice:', xhr.responseText);

            let errorMessage = 'Failed to load invoice details';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {}

            showToast(errorMessage, 'error');
        }
    });
}

// ✅ HELPER: Companies fetch for dropdown
function fetchCompaniesForDropdown(callback) {
    $.ajax({
        url: "{{ config('app.api_url') }}companies",
        method: 'GET',
        success: function(response) {
            if (response && Array.isArray(response)) {
                companies = response;
            } else if (response && response.data && Array.isArray(response.data)) {
                companies = response.data;
            }

            populateClientDropdown();
            if (callback) callback();
        },
        error: function() {
            showToast('Failed to load companies', 'error');
        }
    });
}

// ✅ HELPER: Load company billing address
function loadCompanyBillingAddress(companyId) {
    const company = companies.find(c => c.id == companyId);
    if (company) {
        showBillingAddress(company);
    } else {
        $.ajax({
            url: `{{ config('app.api_url') }}companies/${companyId}`,
            method: 'GET',
            success: function(response) {
                const company = response.data || response;
                showBillingAddress(company);
            }
        });
    }
}

// ✅ HELPER: Add default item with amount
function addDefaultItemWithAmount(amount) {
    $('.item-section:not(#itemSection_1)').remove();

    // Reset first section
    $('#itemSection_1 .item-name-field').val('Subscription');
    $('#itemSection_1 .quantity-field').val('1');

    // ✅ FIX: Amount ko number mein convert karo
    let numericAmount = 0;

    if (amount) {
        if (typeof amount === 'string') {
            numericAmount = parseFloat(amount) || 0;
        }
        else if (typeof amount === 'number') {
            numericAmount = amount;
        }
        else if (typeof amount === 'object' && amount !== null) {
            numericAmount = parseFloat(amount.amount || amount.value || 0) || 0;
        }
    }

    $('#itemSection_1 .unit-price-field').val(numericAmount.toFixed(2));
    $('#itemSection_1 .amount-field').val(numericAmount.toFixed(2));

    calculateTotal();
}

function addPayment(invoiceId) {
    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}`,
        method: 'GET',
        success: function(response) {
            const invoice = response.data?.invoice || response.data || response;
            const remaining = invoice.remaining || (invoice.amount - (invoice.paid_amount || 0));

            Swal.fire({
                title: 'Add Payment',
                html: `
                    <div class="text-start">
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong>$${invoice.amount.toFixed(2)}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Paid Amount:</span>
                                <strong>$${(invoice.paid_amount || 0).toFixed(2)}</strong>
                            </div>
                            <div class="d-flex justify-content-between text-danger">
                                <span>Remaining:</span>
                                <strong>$${remaining.toFixed(2)}</strong>
                            </div>
                        </div>

                        <label class="form-label fw-semibold">Payment Amount <span class="text-danger">*</span></label>
                        <input type="number" id="paymentAmount" class="form-control mb-3"
                               placeholder="Enter amount" step="0.01" min="0.01" max="${remaining}">

                        <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" id="paymentDate" class="form-control mb-3"
                               value="${new Date().toISOString().split('T')[0]}">

                        <label class="form-label fw-semibold">Payment Method</label>
                        <select id="paymentMethod" class="form-select mb-3">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="online">Online Payment</option>
                        </select>

                        <label class="form-label fw-semibold">Transaction ID</label>
                        <input type="text" id="transactionId" class="form-control mb-3" placeholder="Enter transaction ID">

                        <label class="form-label fw-semibold">Notes</label>
                        <textarea id="paymentNotes" class="form-control" rows="2" placeholder="Add notes..."></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Add Payment',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                preConfirm: () => {
                    const amount = parseFloat(document.getElementById('paymentAmount').value);
                    const date = document.getElementById('paymentDate').value;

                    if (!amount || amount <= 0) {
                        Swal.showValidationMessage('Please enter a valid amount');
                        return false;
                    }

                    if (amount > remaining) {
                        Swal.showValidationMessage(`Amount cannot exceed remaining balance: $${remaining.toFixed(2)}`);
                        return false;
                    }

                    if (!date) {
                        Swal.showValidationMessage('Please select payment date');
                        return false;
                    }

                    return {
                        paid_amount: amount,
                        payment_date: date,
                        payment_method: document.getElementById('paymentMethod').value,
                        transaction_id: document.getElementById('transactionId').value,
                        notes: document.getElementById('paymentNotes').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitPayment(invoiceId, result.value);
                }
            });
        },
        error: function() {
            showToast('Failed to load invoice details', 'error');
        }
    });
}

// Submit payment API call
function submitPayment(invoiceId, paymentData) {
    showToast('Processing payment...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}/pay`,
        method: 'POST',
        data: paymentData,
        success: function(response) {
            showToast('Payment added successfully', 'success');
            fetchInvoices();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.message || 'Failed to add payment';
            showToast(error, 'error');
        }
    });
}

// Save shipping address API call
function saveShippingAddress(invoiceId, addressData) {
    showToast('Saving shipping address...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}/shipping-address`,
        method: 'POST',
        data: addressData,
        success: function() {
            showToast('Shipping address added successfully', 'success');
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.message || 'Failed to add shipping address';
            showToast(error, 'error');
        }
    });
}

function cancelInvoice(invoiceId) {
    Swal.fire({
        title: 'Cancel Invoice?',
        html: `
            <div class="text-start">
                <p class="mb-3">Are you sure you want to cancel this invoice?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This action cannot be undone. The invoice status will be changed to "cancelled".
                </div>
                <label class="form-label fw-semibold">Reason for cancellation</label>
                <textarea id="cancelReason" class="form-control" rows="2" placeholder="Enter reason (optional)"></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            showToast('Cancelling invoice...', 'info');

            $.ajax({
                url: `{{ config('app.api_url') }}invoices/${invoiceId}/cancel`,
                method: 'POST',
                data: {
                    reason: document.getElementById('cancelReason').value
                },
                success: function() {
                    showToast('Invoice cancelled successfully', 'success');
                    fetchInvoices(); // Refresh list
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Failed to cancel invoice';
                    showToast(error, 'error');
                }
            });
        }
    });
}

// ✅ PAYMENT REMINDER FUNCTION
function paymentReminder(invoiceId) {
    // showToast('Loading invoice details...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}`,
        method: 'GET',
        success: function(response) {
            let invoice = response.data || response;

            // Calculate overdue days
            let dueDate = new Date(invoice.due_date);
            let today = new Date();
            let diffTime = today - dueDate;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            let isOverdue = (invoice.status === 'overdue' ||
                            (invoice.status === 'pending' && dueDate < today));

            // Get company email
            let companyEmail = '';
            if (invoice.company && invoice.company.email) {
                companyEmail = invoice.company.email;
            } else if (invoice.company_id && companies.length > 0) {
                let company = companies.find(c => c.id == invoice.company_id);
                if (company && company.email) {
                    companyEmail = company.email;
                }
            }

            // Generate message based on status
            let defaultMessage = generateReminderMessage(invoice, isOverdue, diffDays);
            let subjectText = isOverdue ?
                `OVERDUE: Invoice #${invoice.invoice_number} is ${diffDays} days late` :
                `Reminder: Invoice #${invoice.invoice_number} is due soon`;

            // SweetAlert Modal
            Swal.fire({
                title: isOverdue ? '⚠️ Send Overdue Reminder' : '🔔 Send Payment Reminder',
                html: `
                    <div class="text-start">
                        <!-- Invoice Summary Card -->
                        <div class="card mb-3 p-3" style="background: ${isOverdue ? '#fee2e2' : '#fff3cd'};">
                            <h6 class="fw-bold">Invoice #${invoice.invoice_number}</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small>Amount:</small><br>
                                    <strong>$${parseFloat(invoice.amount || 0).toFixed(2)}</strong>
                                </div>
                                <div class="col-6">
                                    <small>Due Date:</small><br>
                                    <strong class="${isOverdue ? 'text-danger' : ''}">
                                        ${invoice.due_date || 'N/A'}
                                        ${isOverdue ? ` (${diffDays} days overdue)` : ''}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <label class="form-label fw-semibold mt-2">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="reminderEmail" class="form-control mb-3"
                               value="${companyEmail}">

                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" id="reminderSubject" class="form-control mb-3"
                               value="${subjectText}">

                        <label class="form-label fw-semibold">Message</label>
                        <textarea id="reminderMessage" class="form-control" rows="5">${defaultMessage}</textarea>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="includePaymentLink" checked>
                            <label class="form-check-label" for="includePaymentLink">
                                Include payment link
                            </label>
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="sendCopy" checked>
                            <label class="form-check-label" for="sendCopy">
                                Send me a copy
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: isOverdue ? 'Send Overdue Reminder' : 'Send Reminder',
                cancelButtonText: 'Cancel',
                confirmButtonColor: isOverdue ? '#ef4444' : '#f59e0b',
                width: '600px',
                preConfirm: () => {
                    const email = document.getElementById('reminderEmail').value;
                    const subject = document.getElementById('reminderSubject').value;
                    const message = document.getElementById('reminderMessage').value;

                    if (!email) {
                        Swal.showValidationMessage('Email is required');
                        return false;
                    }

                    if (!isValidEmail(email)) {
                        Swal.showValidationMessage('Invalid email format');
                        return false;
                    }

                    return {
                        email: email,
                        subject: subject,
                        message: message,
                        include_payment_link: document.getElementById('includePaymentLink').checked ? 1 : 0,
                        send_copy: document.getElementById('sendCopy').checked ? 1 : 0
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    sendPaymentReminder(invoiceId, result.value);
                }
            });
        },
        error: function(xhr) {
            showToast('Failed to load invoice details', 'error');
        }
    });
}

// ✅ GENERATE REMINDER MESSAGE
function generateReminderMessage(invoice, isOverdue, daysOverdue) {
    let companyName = invoice.company?.name || 'Customer';
    let amount = parseFloat(invoice.amount || 0).toFixed(2);
    let remaining = (invoice.amount - (invoice.paid_amount || 0)).toFixed(2);
    let dueDate = invoice.due_date || 'N/A';

    if (isOverdue) {
        return `Dear ${companyName},

This is an URGENT reminder that invoice #${invoice.invoice_number} for $${remaining} was due on ${dueDate} and is now ${daysOverdue} days OVERDUE.

Please make the payment immediately to avoid any service interruption.

Invoice Details:
- Invoice Number: ${invoice.invoice_number}
- Total Amount: $${amount}
- Paid Amount: $${invoice.paid_amount || 0}
- Remaining Amount: $${remaining}
- Due Date: ${dueDate}
- Days Overdue: ${daysOverdue}

Thank you for your immediate attention.`;
    } else {
        return `Dear ${companyName},

This is a friendly reminder that invoice #${invoice.invoice_number} for $${remaining} is due on ${dueDate}.

Please process the payment at your earliest convenience.

Invoice Details:
- Invoice Number: ${invoice.invoice_number}
- Total Amount: $${amount}
- Paid Amount: $${invoice.paid_amount || 0}
- Remaining Amount: $${remaining}
- Due Date: ${dueDate}

Thank you for your business!`;
    }
}

// ✅ SEND PAYMENT REMINDER API CALL
function sendPaymentReminder(invoiceId, data) {
    showToast('Sending reminder...', 'info');

    $.ajax({
        url: `{{ config('app.api_url') }}invoices/${invoiceId}/reminder`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            showToast('Payment reminder sent successfully to ' + data.email, 'success');
        },
        error: function(xhr) {
            let errorMessage = 'Failed to send reminder';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMessage = response.message || errorMessage;
            } catch (e) {}
            showToast(errorMessage, 'error');
        }
    });
}

// ✅ EMAIL VALIDATION FUNCTION
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}


    function getInvoiceStatusClass(status) {
        status = status.toLowerCase();
        switch(status) {
            case 'paid':
            case 'completed':
            case 'success':
                return 'paid';
            case 'pending':
            case 'processing':
            case 'unpaid':
                return 'pending';
            case 'overdue':
            case 'late':
                return 'overdue';
            case 'draft':
            case 'cancelled':
            case 'void':
                return 'draft';
            default:
                return 'pending';
        }
    }

    function getInvoiceStatusIcon(status) {
        status = status.toLowerCase();
        switch(status) {
            case 'paid':
            case 'completed':
            case 'success':
                return '<i class="bi bi-check-circle-fill"></i>';
            case 'pending':
            case 'processing':
            case 'unpaid':
                return '<i class="bi bi-clock-fill"></i>';
            case 'overdue':
            case 'late':
                return '<i class="bi bi-exclamation-circle-fill"></i>';
            case 'draft':
            case 'cancelled':
            case 'void':
                return '<i class="bi bi-file-earmark"></i>';
            default:
                return '<i class="bi bi-file-earmark-text"></i>';
        }
    }

    function getStatusIcon(status) {
        status = status.toLowerCase();
        switch(status) {
            case 'paid':
            case 'completed':
            case 'success':
                return 'bi-check-circle';
            case 'pending':
            case 'processing':
            case 'unpaid':
                return 'bi-clock';
            case 'overdue':
            case 'late':
                return 'bi-exclamation-circle';
            case 'draft':
            case 'cancelled':
            case 'void':
                return 'bi-file-earmark';
            default:
                return 'bi-file-earmark-text';
        }
    }

    function getInvoiceStatusText(status) {
        status = status.toLowerCase();
        switch(status) {
            case 'paid':
            case 'completed':
            case 'success':
                return 'Paid';
            case 'pending':
            case 'processing':
            case 'unpaid':
                return 'Pending';
            case 'overdue':
            case 'late':
                return 'Overdue';
            case 'draft':
            case 'cancelled':
            case 'void':
                return status.charAt(0).toUpperCase() + status.slice(1);
            default:
                return status || 'Pending';
        }
    }

    function viewInvoice(invoiceId) {
    showToast('Opening invoice...', 'info');

    // New route for viewing
    const viewUrl = `{{ config('app.api_url') }}invoices/${invoiceId}/view`;

    // Open in new tab
    window.open(viewUrl, '_blank');

    setTimeout(() => {
        showToast('Invoice opened in new tab', 'success');
    }, 1000);
}

// ✅ LANDSCAPE MODE PDF GENERATION
function downloadInvoice(invoiceId) {
    showToast('Generating PDF...', 'info');

    const downloadUrl = `{{ config('app.api_url') }}invoices/${invoiceId}/download-pdf`;

    fetch(downloadUrl)
        .then(response => response.text())
        .then(html => {
            // Hidden container with proper styling for landscape
            const container = document.createElement('div');
            container.innerHTML = html;
            container.style.width = '1200px';  // Landscape ke liye width badhao
            container.style.padding = '30px';
            container.style.background = 'white';
            container.style.fontFamily = 'Arial, sans-serif';
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.top = '-9999px';
            container.style.boxSizing = 'border-box';
            document.body.appendChild(container);

            // Fix styles for landscape
            const style = document.createElement('style');
            style.innerHTML = `
                .invoice-wrapper {
                    max-width: 1200px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                }
                .invoice-header {
                    background: linear-gradient(135deg, #1a237e, #0d47a1);
                    color: white;
                    padding: 30px 40px;
                }
                .company-section {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                    padding: 30px 40px;
                }
                .items-table {
                    width: calc(100% - 80px);
                    margin: 0 40px 30px 40px;
                    border-collapse: collapse;
                    font-size: 13px;
                }
                .items-table th {
                    background: #1a237e;
                    color: white;
                    padding: 10px;
                    text-align: left;
                }
                .items-table td {
                    padding: 8px;
                    border-bottom: 1px solid #ddd;
                }
                .summary-section {
                    margin: 30px 40px;
                    text-align: right;
                }
                .bank-details {
                    margin: 20px 40px;
                    padding: 15px;
                    background: #f5f5f5;
                    border-radius: 10px;
                }
                .footer {
                    background: #f8fafc;
                    padding: 20px 40px;
                    text-align: center;
                    border-top: 1px solid #e5e7eb;
                }
            `;
            container.appendChild(style);

            // Wait for rendering
            setTimeout(() => {
                html2canvas(container, {
                    scale: 2,
                    backgroundColor: '#ffffff',
                    logging: false,
                    allowTaint: false,
                    useCORS: true,
                    windowWidth: 1400
                }).then(canvas => {
                    // LANDSCAPE DIMENSIONS
                    const imgWidth = 297; // A4 landscape width in mm
                    const pageHeight = 210; // A4 landscape height in mm
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    // Calculate number of pages
                    let heightLeft = imgHeight;
                    let position = 0;

                    // Create PDF in LANDSCAPE mode
                    const pdf = new jspdf.jsPDF({
                        orientation: 'landscape', 
                        unit: 'mm',
                        format: 'a4'
                    });

                    // Add first page
                    pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    // Add additional pages if needed
                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // Save PDF
                    pdf.save(`invoice-${invoiceId}.pdf`);

                    // Cleanup
                    document.body.removeChild(container);
                    showToast('PDF downloaded successfully', 'success');
                });
            }, 1000);
        })
        .catch(error => {
            console.error('PDF generation failed:', error);
            showToast('Failed to generate PDF', 'error');
        });
}

    function upgradePlan() {
        if (!currentCompanyId) {
            showToast('No company selected', 'error');
            return;
        }

        if (!canUpgradeSubscription) {
            showToast('You do not have permission to upgrade subscription', 'error');
            return;
        }

        const currentCompany = companies.find(c => c.id == currentCompanyId);
        const currentSubscriptionId = currentCompany ? currentCompany.subscription_id : null;

        let packagesHtml = `
            <div class="subscription-plans-container">
        `;

        const sortedPackages = [...subscriptionPackages].sort((a, b) => a.price - b.price);

        sortedPackages.forEach((pkg, index) => {
            const isCurrentPlan = (pkg.id == currentSubscriptionId);
            const isPopular = (index === 2);

            packagesHtml += `
                <div class="plan-card ${isPopular ? 'popular' : ''}">
                    ${isPopular ? '<div class="plan-badge">Most Popular</div>' : ''}

                    <h3 class="plan-title">${pkg.title}</h3>
                    <p class="plan-description">${pkg.description || 'Complete subscription package'}</p>

                    <div class="plan-price">
                        $${parseFloat(pkg.price).toFixed(2)}
                        <span class="period">/month</span>
                    </div>

                    <ul class="plan-features">
                        <li><i class="bi bi-check-circle-fill"></i> ${pkg.no_of_licence || '1'} Users Included</li>
                        <li><i class="bi bi-check-circle-fill"></i> ${pkg.storage || '10GB'} Storage</li>
                        <li><i class="bi bi-check-circle-fill"></i> 24/7 Priority Support</li>
                        <li><i class="bi bi-check-circle-fill"></i> API Access</li>
                        <li><i class="bi bi-check-circle-fill"></i> Advanced Analytics</li>
                        <li><i class="bi bi-check-circle-fill"></i> Custom Integration</li>
                    </ul>

                    ${isCurrentPlan ?
                        `<button class="plan-button secondary" onclick="selectPackage(${pkg.id})">
                            <i class="bi bi-check-circle me-2"></i>
                            Current Plan
                        </button>`
                        :
                        `<button class="plan-button primary" onclick="selectPackage(${pkg.id})">
                            <i class="bi bi-arrow-up-circle me-2"></i>
                            Select Plan
                        </button>`
                    }

                    ${isCurrentPlan ?
                        `<button class="plan-button secondary mt-2" onclick="downgradePlan()">
                            <i class="bi bi-x-circle me-2"></i>
                            GET OUT OF PLAN
                        </button>`
                        : ''
                    }
                </div>
            `;
        });

        packagesHtml += '</div>';

        Swal.fire({
            title: 'Upgrade Subscription Plan',
            html: packagesHtml,
            width: '1000px',
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'subscription-modal-popup',
                closeButton: 'subscription-modal-close'
            },
            didOpen: () => {
                $('.swal2-popup').css('padding', '2rem');
                $('.swal2-close').css('font-size', '1.5rem');
            }
        });
    }

    function downgradePlan() {
        Swal.fire({
            title: 'Cancel Subscription?',
            text: 'Are you sure you want to cancel your current subscription plan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel Plan',
            cancelButtonText: 'Keep Plan',
            confirmButtonColor: '#ef4444'
        }).then((result) => {
            if (result.isConfirmed) {
                updateSubscriptionPlan(1);
            }
        });
    }

    function selectPackage(packageId) {
        if (!currentCompanyId) return;

        Swal.fire({
            title: 'Confirm Plan Change',
            text: 'Are you sure you want to switch to this subscription plan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Change Plan',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                updateSubscriptionPlan(packageId);
                Swal.close();
            }
        });
    }

    function updateSubscriptionPlan(packageId) {
        const company = companies.find(c => c.id == currentCompanyId);
        if (!company) return;

        const updatePayload = {
            ...company,
            subscription_id: packageId,
            updated_by: 1,
            updated_at: new Date().toISOString()
        };

        $.ajax({
            url: "{{ config('app.api_url') }}companies/" + currentCompanyId,
            method: "PUT",
            contentType: "application/json",
            data: JSON.stringify(updatePayload),
            success: function(response) {
                const companyIndex = companies.findIndex(c => c.id == currentCompanyId);
                if (companyIndex !== -1) {
                    companies[companyIndex].subscription_id = packageId;
                }

                updateSubscriptionInfo();

                showToast('Subscription updated successfully', 'success');
            },
            error: function(xhr) {
                console.error('Error updating subscription:', xhr.responseText);
                showToast('Failed to update subscription plan', 'error');
            }
        });
    }

    function updateLocationMap(company) {
        const lat = parseFloat(company.latitude);
        const lng = parseFloat(company.longitude);

        $('#displayLat').text(company.latitude || 'N/A');
        $('#displayLng').text(company.longitude || 'N/A');

        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            initializeMap(lat, lng, company.name);
        } else {
            showMapPlaceholder('Location coordinates are not valid or not available');
        }
    }

    function initializeMap(lat, lng, companyName) {
        if (map) {
            map.remove();
            map = null;
        }

        $('#locationContainer').html(`
            <div id="companyMap"></div>
            <div class="map-coordinates mt-3">
                <div class="coordinates-info">
                    <div class="coordinate-item">
                        <div class="coordinate-label">Latitude</div>
                        <div class="coordinate-value">${lat.toFixed(6)}</div>
                    </div>
                    <div class="coordinate-item">
                        <div class="coordinate-label">Longitude</div>
                        <div class="coordinate-value">${lng.toFixed(6)}</div>
                    </div>
                </div>
            </div>
        `);

        map = L.map('companyMap').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        const customIcon = L.divIcon({
            html: `<div style="background: linear-gradient(135deg, var(--primary-color), #6a11cb); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                    <i class="bi bi-building"></i>
                  </div>`,
            className: 'custom-map-marker',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        marker = L.marker([lat, lng], { icon: customIcon })
            .addTo(map)
            .bindPopup(`<b>${companyName || 'Company Location'}</b><br>${lat.toFixed(6)}, ${lng.toFixed(6)}`);

        marker.on('click', function() {
            this.openPopup();
        });
    }

    function showMapPlaceholder(message) {
        $('#locationContainer').html(`
            <div class="map-placeholder">
                <i class="bi bi-geo-alt"></i>
                <p>${message}</p>
                <div class="map-coordinates mt-3">
                    <div class="coordinates-info">
                        <div class="coordinate-item">
                            <div class="coordinate-label">Latitude</div>
                            <div class="coordinate-value" id="displayLat">N/A</div>
                        </div>
                        <div class="coordinate-item">
                            <div class="coordinate-label">Longitude</div>
                            <div class="coordinate-value" id="displayLng">N/A</div>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }

    function formatLocation(company) {
        const parts = [];
        if (company.city_id) parts.push(company.city_id);
        if (company.state_id) parts.push(company.state_id);
        if (company.country_id) parts.push(company.country_id);
        return parts.length > 0 ? parts.join(', ') : 'Location not specified';
    }

    function formatDate(dateString) {
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        } catch (e) {
            return 'N/A';
        }
    }

    function getSubscriptionName(subscriptionId) {
        const subscriptions = {
            1: 'Starter',
            2: 'Professional',
            3: 'Enterprise',
            4: 'Premium'
        };
        return subscriptions[subscriptionId] || 'Basic';
    }

    function updateSubscriptionInfo() {
        if (!currentCompanyId) return;

        const company = companies.find(c => c.id == currentCompanyId);
        if (!company) return;

        const subscriptionId = company.subscription_id;
        const subscriptionPackage = getSubscriptionPackageById(subscriptionId);

        if (subscriptionPackage) {
            $('#subscriptionPlan').text(subscriptionPackage.title || 'Starter');
            $('#subscriptionDescription').text(subscriptionPackage.description || 'Complete access to all features and premium support');
            $('#featureUsers').text(`${subscriptionPackage.no_of_licence || '0'} Users`);

            const price = parseFloat(subscriptionPackage.price) || 99.99;
            $('#monthlyCost').text('$' + price.toFixed(2));

            const renewalDate = new Date();
            renewalDate.setMonth(renewalDate.getMonth() + 1);
            $('#renewalDate').text(formatDate(renewalDate.toISOString()));

            $('#statSubscription').text(subscriptionPackage.title || 'Starter');
        } else {
            $('#subscriptionPlan').text(getSubscriptionName(subscriptionId));
            $('#subscriptionDescription').text('Complete access to all features and premium support');
            $('#featureUsers').text('50 Users');
            $('#monthlyCost').text('$99.99');

            const renewalDate = new Date();
            renewalDate.setMonth(renewalDate.getMonth() + 1);
            $('#renewalDate').text(formatDate(renewalDate.toISOString()));

            $('#statSubscription').text(getSubscriptionName(subscriptionId));
        }
    }

    function showCompaniesLoading(show) {
        if (show) {
            $('#companiesLoading').show();
            $('#emptyCompanies').hide();
        } else {
            $('#companiesLoading').hide();
        }
    }

    function showLoading(show) {
        if (show) {
            $('#loadingState').show();
            $('#defaultState').hide();
            $('#companyProfile').hide();
            $('#singleCompanyView').hide();
        } else {
            $('#loadingState').hide();
        }
    }

    function showEmptyState() {
        $('#defaultState').show();
        $('#companyProfile').hide();
        $('#singleCompanyView').hide();
    }

     // ✅ CREATE INVOICE FUNCTION
     function createInvoice() {
        if (!currentCompanyId) {
            showToast('Please select a company first', 'error');
            return;
        }

        populateClientDropdown();

        resetInvoiceForm();

        const today = new Date().toISOString().split('T')[0];
        $('#invoiceDate').val(today);

        const dueDate = new Date();
        dueDate.setDate(dueDate.getDate() + 15);
        $('#dueDate').val(dueDate.toISOString().split('T')[0]);

        itemSectionCounter = 1;

        const offcanvas = new bootstrap.Offcanvas(document.getElementById('createInvoiceOffcanvas'));
        offcanvas.show();
    }

    function resetInvoiceForm(preserveEditingId = false) {
    $('.item-section:not(#itemSection_1)').remove();

    $('#itemSection_1 .item-name-field').val('');
    $('#itemSection_1 .quantity-field').val('1');
    $('#itemSection_1 .unit-price-field').val('0.00');
    $('#itemSection_1 .tax-field').val('0');
    $('#itemSection_1 .amount-field').val('0.00');
    $('#itemSection_1 .description-field').val('');

    $('#invoiceNumber').val('');
    $('#invoiceDate').val('');
    $('#dueDate').val('');
    $('#invoiceStatus').val('pending');
    $('#subscriptionSelect').val('');
    $('#projectName').val('');
    $('#currency').val('USD');
    $('#bankAccount').val('');
    $('#paymentDetails').val('');
    $('#billingAddress').val('');

    $('#paymentReceivedCheckbox').prop('checked', false);
    $('#paymentFields').hide();
    $('#paidAmount').val('0');
    $('#paidDate').val('');
    $('#paymentMethod').val('');
    $('#transactionId').val('');

    $('#invoiceNotes').val('');
    $('#invoiceTerms').val('');

    $('#discountAmount').val('0');
    $('#summarySubTotal').text('$0.00');
    $('#summaryTax').text('$0.00');
    $('#summaryTotal').text('$0.00');

    itemSectionCounter = 1;

    if (!preserveEditingId) {
        currentEditingInvoiceId = null;
    }

    $('#createInvoiceOffcanvasLabel').html(`
        <i class="bi bi-receipt me-2"></i>
        Create New Invoice
    `);
}
   // ✅ POPULATE CLIENT DROPDOWN
   function populateClientDropdown() {
    const clientSelect = $('#clientSelect');
    clientSelect.empty();
    clientSelect.append('<option value="">Select Client</option>');

    if (companies && companies.length > 0) {
        companies.forEach(company => {
            clientSelect.append(`<option value="${company.id}" data-company='${JSON.stringify(company)}'>${company.name}</option>`);
        });
    }

    clientSelect.off('change').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const companyData = selectedOption.data('company');

        if (companyData) {
            // Set billing address
            let billingAddress = '';
            if (companyData.address_1) billingAddress += companyData.address_1 + '\n';
            if (companyData.address_2) billingAddress += companyData.address_2 + '\n';
            if (companyData.city_id) billingAddress += companyData.city_id;
            if (companyData.state_id) billingAddress += ', ' + companyData.state_id;
            if (companyData.country_id) billingAddress += ', ' + companyData.country_id;

            $('#billingAddress').val(billingAddress);
        }
    });
}
    // ✅ SHOW BILLING ADDRESS
    function showBillingAddress(company) {
        $('#billingAddressLine1').text(company.address_1 || 'Address not available');
        $('#billingAddressLine2').text(company.address_2 || '');

        let cityState = '';
        if (company.city_id) cityState += company.city_id;
        if (company.state_id) cityState += (cityState ? ', ' : '') + company.state_id;
        if (company.country_id) cityState += (cityState ? ', ' : '') + company.country_id;

        $('#billingAddressCityState').text(cityState || 'Location not specified');

        $('#billingAddressPlaceholder').hide();
        $('#billingAddressDisplay').show();
    }

    // ✅ TOGGLE SHIPPING ADDRESS
    function toggleShippingAddress() {
        const shippingFields = $('#shippingAddressFields');
        if (shippingFields.is(':visible')) {
            shippingFields.hide();
        } else {
            shippingFields.show();
        }
    }

    // ✅ ADD NEW ITEM SECTION - Exactly like screenshot
    function addNewItemSection() {
    itemSectionCounter++;
    const sectionId = itemSectionCounter;

    const newSection = `
        <div class="item-section card mb-3" id="itemSection_${sectionId}">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Item #${sectionId}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItemSection(${sectionId})">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control item-name-field" data-section="${sectionId}"
                               placeholder="Product/Service name">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control quantity-field" data-section="${sectionId}"
                               value="1" min="0.01" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Unit Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control unit-price-field" data-section="${sectionId}"
                               value="0.00" min="0" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tax %</label>
                        <select class="form-select tax-field" data-section="${sectionId}">
                            <option value="0">0%</option>
                            <option value="5">5%</option>
                            <option value="10">10%</option>
                            <option value="13">13%</option>
                            <option value="16">16%</option>
                            <option value="18">18%</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="text" class="form-control amount-field" data-section="${sectionId}" value="0.00" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <textarea class="form-control description-field" data-section="${sectionId}" rows="2"
                                  placeholder="Description (optional)"></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#itemsContainer').append(newSection);

    // Scroll to new section
    $(`#itemSection_${sectionId}`).get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function initializeFirstItem() {
    $('#itemSection_1 .quantity-field').val('1');
    $('#itemSection_1 .unit-price-field').val('0.00');
    $('#itemSection_1 .tax-field').val('0');
    $('#itemSection_1 .amount-field').val('0.00');
}


// ✅ REMOVE ITEM SECTION
function removeItemSection(sectionId) {
    Swal.fire({
        title: 'Delete Item?',
        text: 'Are you sure you want to delete this item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $(`#itemSection_${sectionId}`).remove();
            renumberItemSections();
            calculateTotal();
            showToast('Item deleted successfully', 'success');
        }
    });
}

    // ✅ RENUMBER ITEM SECTIONS
    function renumberItemSections() {
        $('.item-section').each(function(index) {
            const newNumber = index + 1;
            const oldId = $(this).attr('id');
            const oldSectionId = oldId.split('_')[1];

            // Update ID
            $(this).attr('id', `itemSection_${newNumber}`);

            // Update header
            $(this).find('.card-header h6').text(`Item #${newNumber}`);

            // Update data attributes
            $(this).find('[data-section]').attr('data-section', newNumber);

            // Update onchange functions
            $(this).find('.quantity-field').attr('onchange', `calculateSectionAmount(${newNumber})`);
            $(this).find('.unit-price-field').attr('onchange', `calculateSectionAmount(${newNumber})`);
            $(this).find('.tax-field').attr('onchange', `calculateSectionAmount(${newNumber})`);
            $(this).find('.btn-danger').attr('onclick', `removeItemSection(${newNumber})`);
        });

        itemSectionCounter = $('.item-section').length;
    }

    // ✅ CALCULATE SECTION AMOUNT
    function calculateSectionAmount(sectionId) {
    console.log('Calculating section:', sectionId); // Debug ke liye

    const quantity = parseFloat($(`#itemSection_${sectionId} .quantity-field`).val()) || 0;
    const unitPrice = parseFloat($(`#itemSection_${sectionId} .unit-price-field`).val()) || 0;
    const amount = quantity * unitPrice;

    $(`#itemSection_${sectionId} .amount-field`).val(amount.toFixed(2));

    // Calculate total
    calculateTotal();
}

// ✅ CALCULATE TOTAL - FIXED
function calculateTotal() {
    let subTotal = 0;
    let totalTax = 0;

    // Har section ka amount aur tax calculate karo
    $('.item-section').each(function() {
        const sectionId = $(this).attr('id').split('_')[1];
        const amount = parseFloat($(this).find('.amount-field').val()) || 0;
        const tax = parseFloat($(this).find('.tax-field').val()) || 0;

        subTotal += amount;
        totalTax += amount * (tax / 100);
    });

    const discount = parseFloat($('#discountAmount').val()) || 0;
    const total = subTotal + totalTax - discount;

    // Get currency symbol
    const currency = $('#currency').val();
    const symbol = getCurrencySymbol(currency);

    // Update summary
    $('#summarySubTotal').text(symbol + subTotal.toFixed(2));
    $('#summaryTax').text(symbol + totalTax.toFixed(2));
    $('#summaryTotal').text(symbol + total.toFixed(2));

    console.log('Total calculated:', total); // Debug ke liye
}

function setupAutoCalculation() {

    // Remove existing listeners pehle
    $(document).off('input', '.quantity-field');
    $(document).off('input', '.unit-price-field');
    $(document).off('change', '.tax-field');
    $(document).off('input', '#discountAmount');

    // Quantity field change par calculate
    $(document).on('input', '.quantity-field', function() {
        const section = $(this).closest('.item-section');
        if (section.length) {
            const sectionId = section.attr('id').split('_')[1];
            calculateSectionAmount(sectionId);
        }
    });

    // Unit price field change par calculate
    $(document).on('input', '.unit-price-field', function() {
        const section = $(this).closest('.item-section');
        if (section.length) {
            const sectionId = section.attr('id').split('_')[1];
            calculateSectionAmount(sectionId);
        }
    });

    // Tax field change par calculate
    $(document).on('change', '.tax-field', function() {
        const section = $(this).closest('.item-section');
        if (section.length) {
            const sectionId = section.attr('id').split('_')[1];
            calculateSectionAmount(sectionId);
        }
    });

    // Discount field change par calculate
    $(document).on('input', '#discountAmount', function() {
        calculateTotal();
    });

    // Currency change par calculate
    $(document).on('change', '#currency', function() {
        calculateTotal();
    });

}


    // ✅ GET CURRENCY SYMBOL
    function getCurrencySymbol(currency) {
        const symbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'PKR': 'Rs'
        };
        return symbols[currency] || '$';
    }

    // ✅ TOGGLE PAYMENT FIELDS
    function togglePaymentFields() {
    const checkbox = document.getElementById('paymentReceivedCheckbox');
    const paymentFields = document.getElementById('paymentFields');

    if (checkbox.checked) {
        paymentFields.style.display = 'block';
        // Set default paid date to today
        if (!$('#paidDate').val()) {
            const today = new Date().toISOString().split('T')[0];
            $('#paidDate').val(today);
        }
    } else {
        paymentFields.style.display = 'none';
        $('#paidAmount').val('0');
        $('#paidDate').val('');
        $('#paymentMethod').val('');
        $('#transactionId').val('');
    }
}

// ===== UPDATED VORTEXTECH PAYMENT FUNCTIONS - LEFT PANEL CLICKABLE =====

// Global variables
let selectedPlan = {
    name: 'Starter',
    monthlyPrice: 8.99,
    annualPrice: 7.64,
    isAnnual: false,
    teammates: '1-5',
    features: [
        'Access to all essential features',
        'Unlimited tracking hours',
        'Customizable Dashboard',
        '5,000 project creations',
        'Data Analytics Overview',
        '24/7 Priority Support',
        'API Access Included'
    ]
};

// ✅ NEW FUNCTION - Sirf monthly price update karega
function updateMonthlyPriceOnly() {
    const company = companies.find(c => c.id == currentCompanyId);
    const subscriptionId = company ? company.subscription_id : null;
    const subscriptionPackage = getSubscriptionPackageById(subscriptionId);

    let monthlyPrice = 8.99; // default

    if (subscriptionPackage) {
        monthlyPrice = parseFloat(subscriptionPackage.price) || 8.99;
    }

    const tax = monthlyPrice * 0.12;
    const total = monthlyPrice + tax;

    // Update left panel price
    $('#modalMonthlyPrice').html(`$${monthlyPrice.toFixed(2)}<span style="font-size: 0.9rem; color: #94A3B8;">/month</span>`);

    // Update right panel summary
    $('#planPriceAmount').text(`$${monthlyPrice.toFixed(2)}/month`);
    $('#billingCycleDisplay').text('Monthly');
    $('#subtotalAmount').text(`$${monthlyPrice.toFixed(2)}`);
    $('#taxAmount').text(`$${tax.toFixed(2)}`);
    $('#totalAmount').text(`$${total.toFixed(2)}`);

    // Update selectedPlan object
    selectedPlan.monthlyPrice = monthlyPrice;
}

// Show payment modal
function showPaymentModal() {
    // Sirf monthly set karo, selectBillingCycle call nahi karna
    selectedPlan.isAnnual = false; // Ensure monthly

    // Update plan details from current subscription
    updatePlanDetails();

    // Update price display for monthly only
    updateMonthlyPriceOnly();

    // Reset form
    resetPaymentForm();

    // Hide active indicator (agr display ho raha ho)
    $('#activePlanIndicator').hide();

    // Show modal
    $('#vortexPaymentModal').fadeIn(300);
    $('body').css('overflow', 'hidden');
}

// Close payment modal
function closeVortexPaymentModal() {
    $('#vortexPaymentModal').fadeOut(300);
    $('body').css('overflow', 'auto');
}

// Update plan details from current subscription
function updatePlanDetails() {
    const company = companies.find(c => c.id == currentCompanyId);
    const subscriptionId = company ? company.subscription_id : null;
    const subscriptionPackage = getSubscriptionPackageById(subscriptionId);

    if (subscriptionPackage) {
        $('#modalPlanName').text(subscriptionPackage.title || 'Starter');
        $('#modalPlanDesc').text(subscriptionPackage.description || 'Complete subscription package');

        // Sirf monthly price set karo
        const monthlyPrice = parseFloat(subscriptionPackage.price) || 8.99;

        selectedPlan.monthlyPrice = monthlyPrice;
        selectedPlan.isAnnual = false; // Force monthly

        // Update features if available
        if (subscriptionPackage.features && subscriptionPackage.features.length > 0) {
            let featuresHtml = '';
            subscriptionPackage.features.forEach(feature => {
                featuresHtml += `
                    <li>
                        <i class="bi bi-check-lg"></i>
                        <span>${feature}</span>
                    </li>
                `;
            });
            $('#modalFeatures').html(featuresHtml);
        }
    } else {
        // Default to Starter plan values
        $('#modalPlanName').text('Starter');
        $('#modalPlanDesc').text('1-5 Teammates in 1 license.');
        selectedPlan.monthlyPrice = 8.99;
    }

    // Price update karo
    updateMonthlyPriceOnly();
}

// Reset payment form
function resetPaymentForm() {
    $('#contactInput').val('');
    $('#cardNumber').val('');
    $('#expiryDate').val('');
    $('#securityCode').val('');
    $('#zipCode').val('');
    $('#cardHolder').val('');
    $('#useShippingAddress').prop('checked', true);
    selectPaymentMethod('card');

    // Sirf monthly set karo, clickable option nahi hai
    selectedPlan.isAnnual = false;
    updateMonthlyPriceOnly();
}

// Select payment method
function selectPaymentMethod(method) {
    $('.payment-method-card').removeClass('selected');

    if (method === 'card') {
        $('input[value="card"]').prop('checked', true);
        $('input[value="card"]').closest('.payment-method-card').addClass('selected');
        $('#cardPaymentForm').show();
        $('#paypalPaymentForm').hide();
    } else if (method === 'paypal') {
        $('input[value="paypal"]').prop('checked', true);
        $('input[value="paypal"]').closest('.payment-method-card').addClass('selected');
        $('#cardPaymentForm').hide();
        $('#paypalPaymentForm').show();
    }
}

// Format card number
function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '');
    let formattedValue = '';

    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }

    input.value = formattedValue;
}

// Format expiry date
function formatExpiry(input) {
    let value = input.value.replace(/\D/g, '');

    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }

    input.value = value;
}

// ✅ reviewOrder FUNCTION
function reviewOrder() {
    // ===== 1. GET FORM VALUES =====
    const contact = $('#contactInput').val().trim();
    const paymentMethod = $('input[name="paymentMethod"]:checked').val();
    const useShippingAddress = $('#useShippingAddress').is(':checked');

    // Payment method value set karo
    let paymentMethodValue = '';
    if (paymentMethod === 'card') {
        paymentMethodValue = 'credit_card';
    } else if (paymentMethod === 'paypal') {
        paymentMethodValue = 'paypal';
    }

    // ===== 2. VALIDATION =====
    if (!contact) {
        showToast('Please enter email or phone number', 'error');
        $('#contactInput').focus();
        return;
    }

    // Card validation
    if (paymentMethod === 'card') {
        const cardNumber = $('#cardNumber').val().replace(/\s/g, '');
        const expiry = $('#expiryDate').val();
        const cvv = $('#securityCode').val();
        const zip = $('#zipCode').val();
        const cardHolder = $('#cardHolder').val().trim();

        if (!cardHolder) {
            showToast('Please enter cardholder name', 'error');
            $('#cardHolder').focus();
            return;
        }

        if (!cardNumber || cardNumber.length < 15) {
            showToast('Please enter a valid card number', 'error');
            $('#cardNumber').focus();
            return;
        }

        if (!expiry || expiry.length < 5) {
            showToast('Please enter valid expiry date (MM/YY)', 'error');
            $('#expiryDate').focus();
            return;
        }

        if (!cvv || cvv.length < 3) {
            showToast('Please enter security code', 'error');
            $('#securityCode').focus();
            return;
        }

        if (!zip || zip.length < 5) {
            showToast('Please enter ZIP code', 'error');
            $('#zipCode').focus();
            return;
        }
    }

    // ===== 3. SHOW LOADING =====
    $('#paymentLoading').show();
    $('#reviewOrderBtn').prop('disabled', true);

    // ===== 4. CALCULATE AMOUNTS =====
    const currentPrice = selectedPlan.isAnnual ? selectedPlan.annualPrice : selectedPlan.monthlyPrice;
    const tax = currentPrice * 0.12; // 12% tax
    const total = currentPrice + tax;

    // ===== 5. GET COMPANY & SUBSCRIPTION INFO =====
    const companyId = currentCompanyId;

    if (!companyId) {
        showToast('No company selected', 'error');
        $('#paymentLoading').hide();
        $('#reviewOrderBtn').prop('disabled', false);
        return;
    }

    const company = companies.find(c => c.id == companyId);
    const subscriptionPackage = company ? getSubscriptionPackageById(company.subscription_id) : null;

    // ===== 6. PREPARE ORDER DATA =====
    const companyName = companies.find(c => c.id == companyId)?.name || '';

const orderData = {
    contact: contact,
    payment_method: paymentMethodValue,
    use_shipping_address: useShippingAddress,
    company_id: companyId,
    user_id: null,
    subscription_package_id: subscriptionPackage?.id || null,
    invoice_id: null,
    plan: {
        name: $('#modalPlanName').text(),
        billing_cycle: selectedPlan.isAnnual ? 'annual' : 'monthly',
        selected_price: currentPrice,
        discount: selectedPlan.isAnnual ? (selectedPlan.monthlyPrice * 12 - currentPrice * 12) : 0
    },
    amounts: {
        subtotal: currentPrice,
        tax: tax,
        total: total
    },
    currency: 'USD',
    // ✅ UPDATED NOTES - Subscription ke hisaab se
    notes: `Subscription: ${$('#modalPlanName').text()} - ${selectedPlan.isAnnual ? 'Annual' : 'Monthly'} plan`
};

    // Agar payment method card hai to card details add karo
    if (paymentMethod === 'card') {
        orderData.card = {
            number: $('#cardNumber').val(),
            expiry: $('#expiryDate').val(),
            cvv: $('#securityCode').val(),
            zip: $('#zipCode').val(),
            holder: $('#cardHolder').val()
        };
    }

    console.log('Sending payment data:', orderData);

    // ===== 8. MAKE API CALL =====
    $.ajax({
        url: "{{ config('app.api_url') }}payments/process",
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(orderData),
        success: function(response) {
            $('#paymentLoading').hide();
            $('#reviewOrderBtn').prop('disabled', false);

            if (response.success) {
                showToast('✅ Payment of $' + total.toFixed(2) + ' completed successfully!', 'success');

                closeVortexPaymentModal();

                fetchInvoices();

                console.log('Payment saved:', response.data);
            } else {
                showToast(response.message || 'Payment failed', 'error');
            }
        },
        error: function(xhr) {
            $('#paymentLoading').hide();
            $('#reviewOrderBtn').prop('disabled', false);

            let errorMessage = 'Payment processing failed';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMessage = response.message || errorMessage;

                if (response.errors) {
                    console.error('Validation errors:', response.errors);
                    errorMessage = 'Please check your information';
                }
            } catch(e) {
                console.error('Error parsing response:', e);
            }

            showToast('❌ ' + errorMessage, 'error');
            console.error('Payment error:', xhr.responseText);
        },
        complete: function() {
            setTimeout(function() {
                $('#paymentLoading').hide();
                $('#reviewOrderBtn').prop('disabled', false);
            }, 5000);
        }
    });
}

// Click outside to close
$(document).on('click', function(e) {
    if ($(e.target).hasClass('payment-modal-overlay')) {
        closeVortexPaymentModal();
    }
});

// ESC key to close
$(document).keydown(function(e) {
    if (e.key === 'Escape' && $('#vortexPaymentModal').is(':visible')) {
        closeVortexPaymentModal();
    }
});

// Auto-focus next field
$('#cardNumber').on('input', function() {
    if ($(this).val().replace(/\s/g, '').length === 16) {
        $('#expiryDate').focus();
    }
});

$('#expiryDate').on('input', function() {
    if ($(this).val().length === 5) {
        $('#securityCode').focus();
    }
});

$('#securityCode').on('input', function() {
    if ($(this).val().length === 3) {
        $('#zipCode').focus();
    }
});

    // ✅ HANDLE FILE UPLOAD
    function handleFileUpload(input) {
        const files = input.files;
        const fileList = $('#fileList');
        fileList.empty();

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            fileList.append(`
                <div class="alert alert-info py-1 px-2 mb-1">
                    <i class="bi bi-file-earmark me-2"></i>
                    ${file.name} (${(file.size / 1024).toFixed(2)} KB)
                </div>
            `);
        }
    }

 // ✅ FUNCTION TO UPDATE MONTHLY BAR CHART - WITH STATUS AMOUNTS
function updateInvoiceBarChart() {
    if (!currentCompanyId) {
        console.log('No company selected, cannot update chart.');
        return;
    }

    const ctx = document.getElementById('invoiceBarChart');
    if (!ctx) {
        console.error('invoiceBarChart canvas not found!');
        return;
    }

    if (invoiceBarChart) {
        invoiceBarChart.destroy();
    }

    // --- Months Data Prepare ---
    const months = [];
    const today = new Date();
    for (let i = 11; i >= 0; i--) {
        const d = new Date(today.getFullYear(), today.getMonth() - i, 1);
        const monthName = d.toLocaleString('default', { month: 'short' });
        const yearShort = d.getFullYear().toString().slice(-2);
        months.push({
            label: `${monthName} ${yearShort}`,
            key: `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`,
            monthIndex: d.getMonth(),
            year: d.getFullYear()
        });
    }

    // ✅ Monthly totals with status-wise count AND amount
    const monthlyTotals = months.map(m => ({
        ...m,
        totalCount: 0,
        totalAmount: 0,
        statusData: {
            paid: { count: 0, amount: 0 },
            pending: { count: 0, amount: 0 },
            overdue: { count: 0, amount: 0 },
            draft: { count: 0, amount: 0 },
            cancelled: { count: 0, amount: 0 }
        }
    }));

    // ✅ Process invoices
    if (invoices && invoices.length > 0) {
        invoices.forEach(invoice => {
            let invoiceDate = null;
            if (invoice.invoice_month) {
                invoiceDate = new Date(invoice.invoice_month + '-01');
            } else if (invoice.invoice_date) {
                invoiceDate = new Date(invoice.invoice_date);
            } else {
                return;
            }

            if (isNaN(invoiceDate.getTime())) return;

            const invoiceYear = invoiceDate.getFullYear();
            const invoiceMonth = invoiceDate.getMonth();
            const invoiceKey = `${invoiceYear}-${String(invoiceMonth + 1).padStart(2, '0')}`;

            const monthData = monthlyTotals.find(m => m.key === invoiceKey);
            if (monthData) {
                let amount = 0;
                if (invoice.amount) {
                    if (typeof invoice.amount === 'string') {
                        amount = parseFloat(invoice.amount) || 0;
                    } else if (typeof invoice.amount === 'number') {
                        amount = invoice.amount;
                    }
                }

                monthData.totalCount++;
                monthData.totalAmount += amount;

                const status = (invoice.status || 'draft').toLowerCase();
                let statusKey = 'draft';

                if (status === 'paid' || status === 'completed' || status === 'success') {
                    statusKey = 'paid';
                } else if (status === 'pending' || status === 'processing' || status === 'unpaid') {
                    statusKey = 'pending';
                } else if (status === 'overdue' || status === 'late') {
                    statusKey = 'overdue';
                } else if (status === 'draft') {
                    statusKey = 'draft';
                } else if (status === 'cancelled' || status === 'void') {
                    statusKey = 'cancelled';
                }

                monthData.statusData[statusKey].count++;
                monthData.statusData[statusKey].amount += amount;
            }
        });
    }

    const chartLabels = monthlyTotals.map(m => m.label);
    const chartDataCounts = monthlyTotals.map(m => m.totalCount);

    // --- Create Bar Chart ---
    invoiceBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Total Invoices',
                data: chartDataCounts,
                backgroundColor: 'rgba(74, 108, 247, 0.7)',
                borderColor: 'rgba(74, 108, 247, 1)',
                borderWidth: 1,
                borderRadius: 6,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        // ✅ UPDATED TOOLTIP WITH AMOUNTS
                        label: function(context) {
    const monthIndex = context.dataIndex;
    const month = monthlyTotals[monthIndex];

    const lines = [
        `📊 Total Invoices: ${month.totalCount}`,
        `💵 Total Paid Amount: $${month.statusData.paid.amount.toFixed(2)}`, 
        `─────────────────`
    ];

    // Status lines
    const statuses = [
        { key: 'paid', label: 'Paid', icon: '✅' },
        { key: 'pending', label: 'Pending', icon: '⏳' },
        { key: 'overdue', label: 'Overdue', icon: '⚠️' },
        { key: 'draft', label: 'Draft', icon: '📝' },
        { key: 'cancelled', label: 'Cancelled', icon: '❌' }
    ];

    statuses.forEach(s => {
        const data = month.statusData[s.key];
        if (data.count > 0) {
            const plural = data.count !== 1 ? 's' : '';
            lines.push(
                `  ${s.icon} ${s.label}: ${data.count} invoice${plural} - $${data.amount.toFixed(2)}`
            );
        }
    });

    if (month.totalCount === 0) {
        lines.push(`  No invoices for this month`);
    }

    return lines;
}
                    },
                    backgroundColor: '#1f2937',
                    titleColor: '#f3f4f6',
                    bodyColor: '#d1d5db',
                    borderColor: '#4b5563',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Invoices',
                        color: '#6b7280'
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (Math.floor(value) === value) return value;
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { maxRotation: 45, minRotation: 30 }
                }
            },
            onClick: (event, item) => {
                if (item.length > 0) {
                    const monthIndex = item[0].dataIndex;
                    const month = monthlyTotals[monthIndex];
                    // Optional: Yahan par month ke invoices dikha sakte ho
                    console.log('Clicked on:', month);
                }
            }
        }
    });
}



    // Auto refresh intervals
    if (isAdmin) {
        // setInterval(fetchCompanies, 5 * 60 * 1000);
    }
    setInterval(fetchAllTickets, 10 * 60 * 1000);
    setInterval(fetchAllBusinessUnits, 10 * 60 * 1000);
    setInterval(fetchDomains, 15 * 60 * 1000);
    setInterval(fetchSubscriptionPackages, 15 * 60 * 1000);
</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
