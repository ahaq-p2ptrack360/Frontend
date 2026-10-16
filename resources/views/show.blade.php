<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $ticket->id ?? '' }} - {{ $ticket->title ?? 'Details' }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #e8edf5 0%, #d5dce6 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1a2332;
        }
        
        /* ===== SCROLLBAR STYLING ===== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f0f2f5;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c9d6;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a0abb9;
        }
        
        /* ===== PUBLIC HEADER ===== */
        .public-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .public-header .logo {
            font-size: 22px;
            font-weight: 800;
            color: #1a2332;
            text-decoration: none;
            letter-spacing: -0.5px;
            transition: all 0.3s ease;
        }
        
        .public-header .logo:hover {
            color: #2d7aff;
            transform: scale(1.02);
        }
        
        .public-header .logo i {
            font-size: 26px;
            background: linear-gradient(135deg, #2d7aff, #6c5ce7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 10px;
        }
        
        .public-header .logo .highlight {
            color: #2d7aff;
            -webkit-text-fill-color: #2d7aff;
        }
        
        /* ===== TICKET CONTAINER ===== */
        .ticket-container {
            flex: 1;
            padding: 40px 20px 30px;
            max-width: 1140px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* ===== BREADCRUMB ===== */
        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7a8f;
            margin-bottom: 24px;
            padding: 0;
        }
        
        .breadcrumb-custom span {
            color: #1a2332;
            font-weight: 500;
        }
        
        .breadcrumb-custom .separator {
            color: #c1c9d6;
        }
        
        /* ===== TICKET CARD ===== */
        .ticket-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .ticket-card:hover {
            box-shadow: 0 12px 50px rgba(0, 0, 0, 0.08);
        }
        
        .ticket-card .card-header {
            background: linear-gradient(135deg, #f8faff 0%, #f0f4fe 100%);
            padding: 22px 32px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .ticket-card .card-header .ticket-id {
            font-weight: 700;
            font-size: 18px;
            color: #1a2332;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .ticket-card .card-header .ticket-id .id-badge {
            background: #e8edf5;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #4a5a72;
        }
        
        .ticket-card .card-header .ticket-id .id-badge i {
            margin-right: 6px;
            font-size: 12px;
        }
        
        .ticket-card .card-body {
            padding: 32px;
        }
        
        /* ===== STATUS BADGE ===== */
        .badge-status {
            padding: 7px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .badge-status i {
            font-size: 8px;
        }
        
        .badge-status.bg-success {
            background: #e6f7ed !important;
            color: #0b7e3d !important;
        }
        .badge-status.bg-warning {
            background: #fef6e6 !important;
            color: #b47d0a !important;
        }
        .badge-status.bg-primary {
            background: #e6edfe !important;
            color: #1a5fd4 !important;
        }
        .badge-status.bg-danger {
            background: #fde8e8 !important;
            color: #c0392b !important;
        }
        .badge-status.bg-info {
            background: #e6f4fa !important;
            color: #0f7ba0 !important;
        }
        .badge-status.bg-secondary {
            background: #f0f2f5 !important;
            color: #5a6a7e !important;
        }
        
        /* ===== TICKET TITLE ===== */
        .ticket-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a2332;
            margin-bottom: 20px;
            line-height: 1.3;
            letter-spacing: -0.3px;
        }
        
        /* ===== DESCRIPTION ===== */
        .description-box {
            background: #f8faff;
            padding: 20px 24px;
            border-radius: 12px;
            border-left: 4px solid #2d7aff;
            margin-bottom: 32px;
            line-height: 1.8;
            color: #2d3748;
            position: relative;
        }
        
        .description-box .label {
            font-size: 11px;
            font-weight: 700;
            color: #6b7a8f;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
            display: block;
        }
        
        .description-box .label i {
            margin-right: 6px;
        }
        
        /* ===== INFO GRID ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin-top: 8px;
        }
        
        .info-item {
            background: #f8faff;
            padding: 16px 20px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2d7aff, #6c5ce7);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .info-item:hover {
            background: #ffffff;
            border-color: #e8edf5;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(45, 122, 255, 0.08);
        }
        
        .info-item:hover::before {
            opacity: 1;
        }
        
        .info-item .label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7a8f;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .info-item .label i {
            font-size: 13px;
            color: #2d7aff;
        }
        
        .info-item .value {
            font-size: 15px;
            font-weight: 600;
            color: #1a2332;
            margin: 0;
        }
        
        .info-item .value .badge {
            font-size: 12px;
            padding: 4px 14px;
            border-radius: 50px;
            font-weight: 600;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-top: 0;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        
        .sidebar-card:hover {
            box-shadow: 0 12px 50px rgba(0, 0, 0, 0.08);
        }
        
        .sidebar-card .card-header {
            background: linear-gradient(135deg, #f8faff 0%, #f0f4fe 100%);
            padding: 16px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            font-weight: 700;
            font-size: 15px;
            color: #1a2332;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-card .card-header i {
            color: #2d7aff;
            font-size: 18px;
        }
        
        .sidebar-card .card-body {
            padding: 20px 24px;
        }
        
        .sidebar-card + .sidebar-card {
            margin-top: 20px;
        }
        
        /* ===== STATUS ITEMS ===== */
        .status-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 18px;
            background: #f8faff;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        
        .status-item:hover {
            background: #f0f4fe;
            transform: translateX(4px);
        }
        
        .status-item:last-child {
            margin-bottom: 0;
        }
        
        .status-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        
        .status-icon.bg-success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .status-icon.bg-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }
        .status-icon.bg-primary {
            background: linear-gradient(135deg, #2d7aff, #6c5ce7);
        }
        .status-icon.bg-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
        }
        .status-icon.bg-info {
            background: linear-gradient(135deg, #17a2b8, #0dcaf0);
        }
        .status-icon.bg-secondary {
            background: linear-gradient(135deg, #6c757d, #8e9eab);
        }
        
        .status-info .title {
            font-weight: 700;
            font-size: 15px;
            color: #1a2332;
            margin: 0;
        }
        
        .status-info .subtitle {
            font-size: 13px;
            color: #6b7a8f;
            margin: 0;
        }
        
        /* ===== ACTIONS ===== */
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex: 1;
            min-width: 120px;
        }
        
        .action-buttons .btn-outline-secondary {
            border-color: #e8edf5;
            color: #4a5a72;
            background: #f8faff;
        }
        
        .action-buttons .btn-outline-secondary:hover {
            background: #2d7aff;
            border-color: #2d7aff;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 122, 255, 0.25);
        }
        
        .action-buttons .btn-outline-secondary i {
            transition: transform 0.3s ease;
        }
        
        .action-buttons .btn-outline-secondary:hover i {
            transform: scale(1.1);
        }
        
        /* ===== PUBLIC FOOTER ===== */
        .public-footer {
            text-align: center;
            padding: 20px;
            color: #6b7a8f;
            font-size: 13px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            margin-top: auto;
        }
        
        .public-footer .footer-text {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .public-footer .footer-text .dot {
            color: #2d7aff;
            margin: 0 8px;
        }
        
        /* ===== HELP CARD ===== */
        .help-card .help-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #e6edfe, #f0f4fe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #2d7aff;
            margin: 0 auto 12px;
            transition: all 0.3s ease;
        }
        
        .help-card:hover .help-icon {
            transform: scale(1.05) rotate(-5deg);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .ticket-container {
                padding: 24px 16px;
            }
            
            .sidebar-card {
                margin-top: 20px !important;
            }
        }
        
        @media (max-width: 768px) {
            .ticket-card .card-header {
                padding: 16px 20px;
                flex-direction: column;
                align-items: flex-start;
            }
            
            .ticket-card .card-body {
                padding: 20px;
            }
            
            .ticket-title {
                font-size: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            
            .info-item {
                padding: 14px 16px;
            }
            
            .public-header .logo {
                font-size: 18px;
            }
            
            .action-buttons .btn {
                flex: none;
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .ticket-title {
                font-size: 18px;
            }
            
            .ticket-card .card-header .ticket-id {
                font-size: 16px;
                flex-wrap: wrap;
            }
            
            .badge-status {
                font-size: 12px;
                padding: 5px 14px;
            }
            
            .public-header {
                padding: 10px 0;
            }
            
            .public-header .logo {
                font-size: 16px;
            }
            
            .public-header .logo i {
                font-size: 20px;
            }
        }
        
        /* ===== PRINT STYLES ===== */
        @media print {
            .public-header {
                background: #ffffff !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                box-shadow: none !important;
                border-bottom: 2px solid #e8edf5 !important;
            }
            
            .public-footer {
                background: #ffffff !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                border-top: 2px solid #e8edf5 !important;
            }
            
            .action-buttons .btn {
                display: none !important;
            }
            
            .ticket-card {
                box-shadow: none !important;
                border: 1px solid #e8edf5 !important;
            }
            
            .sidebar-card {
                box-shadow: none !important;
                border: 1px solid #e8edf5 !important;
            }
            
            .info-item:hover {
                transform: none !important;
                box-shadow: none !important;
            }
            
            .status-item:hover {
                transform: none !important;
            }
            
            body {
                background: #ffffff !important;
            }
        }
    </style>
</head>
<body>

    <!-- ===== PUBLIC HEADER ===== -->
    <header class="public-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas fa-ticket-alt"></i>
                    Support <span class="highlight">Portal</span>
                </a>
            </div>
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="ticket-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <i class="fas fa-home" style="color: #2d7aff; font-size: 14px;"></i>
            <span>Dashboard</span>
            <span class="separator">/</span>
            <span>Ticket #{{ $ticket->id ?? '' }}</span>
        </div>

        <div class="row g-4">
            <!-- Main Ticket Card -->
            <div class="col-lg-8">
                <div class="ticket-card">
                    <div class="card-header">
                        <div class="ticket-id">
                            <i class="fas fa-ticket-alt" style="color: #2d7aff; font-size: 20px;"></i>
                            <span>Ticket #{{ $ticket->id ?? '' }}</span>
                            <span class="id-badge">
                                <i class="fas fa-hashtag"></i>
                                {{ $ticket->ticket_counter ?? 'N/A' }}
                            </span>
                        </div>
                        <div>
                            @php
                                $statusTitle = $ticket->status_title ?? 'Unknown';
                                $statusClass = match($statusTitle) {
                                    'Completed' => 'success',
                                    'Pending' => 'warning',
                                    'Escalated' => 'danger',
                                    'In Progress' => 'primary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge-status bg-{{ $statusClass }}">
                                <i class="fas fa-circle"></i>
                                {{ $statusTitle }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Title -->
                        <h1 class="ticket-title">
                            <i class="fas fa-tag" style="color: #2d7aff; font-size: 22px; margin-right: 12px;"></i>
                            {{ $ticket->title ?? 'No Title' }}
                        </h1>

                        <!-- Description -->
                        <div class="description-box">
                            <span class="label">
                                <i class="fas fa-align-left"></i> Description
                            </span>
                            {{ $ticket->description ?? 'No description available' }}
                        </div>

                        <!-- Info Grid -->
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-building"></i> Company
                                </span>
                                <p class="value">{{ $ticket->company_name ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-sitemap"></i> Business Unit
                                </span>
                                <p class="value">{{ $ticket->bu_name ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-flag"></i> Priority
                                </span>
                                <p class="value">
                                    @php
                                        $priorityTitle = $ticket->priority_title ?? 'N/A';
                                        $priorityClass = match($priorityTitle) {
                                            'High' => 'danger',
                                            'Medium' => 'warning',
                                            'Low' => 'success',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $priorityClass }}">
                                        <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                        {{ $priorityTitle }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-exclamation-triangle"></i> Impact
                                </span>
                                <p class="value">{{ $ticket->impact_title ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-user"></i> Reported By
                                </span>
                                <p class="value">{{ $ticket->reported_by_name ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-user-check"></i> Assigned To
                                </span>
                                <p class="value">{{ $ticket->resolved_name ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-calendar-plus"></i> Created
                                </span>
                                <p class="value">
                                    {{ isset($ticket->created_at) ? \Carbon\Carbon::parse($ticket->created_at)->format('d M, Y h:i A') : 'N/A' }}
                                </p>
                            </div>
                            
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-calendar-check"></i> Due Date
                                </span>
                                <p class="value">
                                    {{ isset($ticket->due_date) ? \Carbon\Carbon::parse($ticket->due_date)->format('d M, Y') : 'Not Set' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        Status Information
                    </div>
                    <div class="card-body">
                        <div class="status-item">
                            <div class="status-icon bg-{{ $statusClass }}">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="status-info">
                                <p class="title">{{ $statusTitle }}</p>
                                <p class="subtitle">Current Status</p>
                            </div>
                        </div>
                        
                        @if(isset($ticket->completed_date))
                        <div class="status-item">
                            <div class="status-icon bg-success">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="status-info">
                                <p class="title">{{ \Carbon\Carbon::parse($ticket->completed_date)->format('d M, Y') }}</p>
                                <p class="subtitle">Completed Date</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="fas fa-print me-2"></i> Print Ticket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="sidebar-card help-card">
                    <div class="card-header">
                        <i class="fas fa-headset"></i>
                        Need Help?
                    </div>
                    <div class="card-body text-center">
                        <div class="help-icon">
                            <i class="fas fa-life-ring"></i>
                        </div>
                        <p class="text-muted mb-3" style="font-size: 14px; line-height: 1.6;">
                            If you have any questions about this ticket,<br>
                            please contact our support team.
                        </p>
                        <a href="mailto:support@p2ptrack360.com" class="btn btn-primary w-100" style="border-radius: 12px; padding: 10px; font-weight: 600; background: linear-gradient(135deg, #2d7aff, #6c5ce7); border: none; transition: all 0.3s ease;">
                            <i class="fas fa-envelope me-2"></i> support@p2ptrack360.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ===== PUBLIC FOOTER ===== -->
    <footer class="public-footer">
        <div class="container">
            <p class="mb-0 footer-text">
                2026 <span class="dot">•</span> P2P Track 360
            </p>
        </div>
    </footer>

</body>
</html>