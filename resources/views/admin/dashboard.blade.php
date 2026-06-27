@extends('layouts.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --baps-saffron: #f97316;
        --baps-saffron-dark: #ea580c;
        --baps-red: #dc2626;
        --baps-blue: #2563eb;
        --baps-green: #16a34a;
        --baps-purple: #9333ea;
        --baps-gold: #fbbf24;
        --baps-bg: #f8fafc;
        --baps-text: #334155;
        --baps-border: #e2e8f0;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-shadow: 0 10px 30px 0 rgba(31, 38, 135, 0.07);
    }

    body {
        background-color: var(--baps-bg) !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
        color: var(--baps-text);
    }

    /* Bulletproof Bootstrap Fallbacks for Offline/Blocked CDN */
    .d-flex { display: flex !important; }
    .flex-column { flex-direction: column !important; }
    .flex-row { flex-direction: row !important; }
    .flex-wrap { flex-wrap: wrap !important; }
    .align-items-center { align-items: center !important; }
    .justify-content-between { justify-content: space-between !important; }
    .justify-content-center { justify-content: center !important; }
    .gap-2 { gap: 0.5rem !important; }
    .gap-3 { gap: 1rem !important; }
    .gap-4 { gap: 1.5rem !important; }
    .text-center { text-align: center !important; }
    .w-100 { width: 100% !important; }
    .h-100 { height: 100% !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .rounded-circle { border-radius: 50% !important; }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; }
    .fw-bold { font-weight: 700 !important; }
    .mb-1 { margin-bottom: 0.25rem !important; }
    .mb-3 { margin-bottom: 1rem !important; }
    .mb-4 { margin-bottom: 1.5rem !important; }
    .mb-5 { margin-bottom: 3rem !important; }
    .mt-4 { margin-top: 1.5rem !important; }
    .mt-5 { margin-top: 3rem !important; }
    .pt-4 { padding-top: 1.5rem !important; }
    .pe-4 { padding-right: 1.5rem !important; }
    .ps-4 { padding-left: 1.5rem !important; }
    .text-muted { color: #6c757d !important; }
    .small { font-size: 0.875em !important; }
    .fs-5 { font-size: 1.25rem !important; }
    .position-relative { position: relative !important; }
    .position-absolute { position: absolute !important; }
    .bottom-0 { bottom: 0 !important; }
    .end-0 { right: 0 !important; }
    
    /* Grid Fallbacks */
    .row { display: flex !important; flex-wrap: wrap !important; margin-left: -0.75rem; margin-right: -0.75rem; }
    .row > * { padding-left: 0.75rem; padding-right: 0.75rem; width: 100%; max-width: 100%; box-sizing: border-box; }
    @media (min-width: 576px) {
        .col-sm-6 { flex: 0 0 auto !important; width: 50% !important; }
    }
    @media (min-width: 768px) {
        .col-md-3 { flex: 0 0 auto !important; width: 25% !important; }
        .col-md-4 { flex: 0 0 auto !important; width: 33.333333% !important; }
        .col-md-6 { flex: 0 0 auto !important; width: 50% !important; }
        .col-md-8 { flex: 0 0 auto !important; width: 66.666667% !important; }
    }
    @media (min-width: 992px) {
        .col-lg-2 { flex: 0 0 auto !important; width: 16.666667% !important; }
        .col-lg-3 { flex: 0 0 auto !important; width: 25% !important; }
        .col-lg-4 { flex: 0 0 auto !important; width: 33.333333% !important; }
        .col-lg-8 { flex: 0 0 auto !important; width: 66.666667% !important; }
        .flex-lg-row { flex-direction: row !important; }
        .text-lg-start { text-align: left !important; }
        .justify-content-lg-end { justify-content: flex-end !important; }
    }
    @media (min-width: 576px) {
        .flex-sm-row { flex-direction: row !important; }
        .text-sm-end { text-align: right !important; }
        .justify-content-sm-start { justify-content: flex-start !important; }
    }

    /* Tab Panes & Modals Fallback */
    .tab-pane { display: none; }
    .tab-pane.active.show { display: block !important; }
    
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055; overflow-x: hidden; overflow-y: auto; outline: 0; background: rgba(0,0,0,0.5); }
    .modal.show { display: block !important; }
    .modal-dialog { position: relative; width: auto; margin: 1.75rem auto; max-width: 800px; pointer-events: none; }
    .modal-dialog-centered { display: flex; align-items: center; min-height: calc(100% - 3.5rem); }
    .modal-content { position: relative; display: flex; flex-direction: column; width: 100%; pointer-events: auto; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0,0,0,.2); border-radius: 1rem; outline: 0; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15); }
    .modal-header { display: flex; flex-shrink: 0; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #dee2e6; border-top-left-radius: calc(1rem - 1px); border-top-right-radius: calc(1rem - 1px); }
    .modal-body { position: relative; flex: 1 1 auto; padding: 1.5rem; }
    .modal-footer { display: flex; flex-shrink: 0; flex-wrap: wrap; align-items: center; justify-content: flex-end; padding: 1.5rem; border-top: 1px solid #dee2e6; border-bottom-right-radius: calc(1rem - 1px); border-bottom-left-radius: calc(1rem - 1px); }
    .btn-close { box-sizing: content-box; width: 1em; height: 1em; padding: .25em .25em; color: #000; background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat; border: 0; border-radius: .375rem; opacity: .5; cursor: pointer; }
    .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }

    /* Professional Responsive Header */
    .baps-header-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 28px 24px;
        margin-bottom: 28px;
        box-shadow: var(--glass-shadow);
        border: 1px solid var(--baps-border);
        border-top: 5px solid var(--baps-saffron);
        transition: all 0.3s ease;
    }

    .baps-logo-wrapper {
        background: #ffffff;
        border-radius: 14px;
        padding: 12px;
        border: 1px solid var(--baps-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 85px;
        height: 85px;
        flex-shrink: 0;
    }
    .baps-logo-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Crisp & Scrollable 10 Tabs Navigation */
    .baps-nav-pills {
        background: #ffffff;
        padding: 10px;
        border-radius: 14px;
        box-shadow: var(--glass-shadow);
        border: 1px solid var(--baps-border);
        display: flex;
        gap: 6px;
        overflow-x: auto;
        flex-wrap: nowrap;
        margin-bottom: 28px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    .baps-nav-pills::-webkit-scrollbar {
        height: 6px;
    }
    .baps-nav-pills::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .baps-nav-pills::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .baps-nav-pills::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .baps-tab-btn {
        background: transparent !important;
        color: #64748b !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 12px 20px !important;
        font-weight: 600 !important;
        font-size: 0.92rem !important;
        white-space: nowrap;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .baps-tab-btn:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        transform: translateY(-1px);
    }
    .baps-tab-btn.active {
        background: var(--baps-saffron) !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(249, 115, 22, 0.3) !important;
        transform: translateY(-1px);
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 24px;
        border: 1px solid var(--baps-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
    .stat-icon.primary { background: #eff6ff; color: #3b82f6; }
    .stat-icon.success { background: #f0fdf4; color: #22c55e; }
    .stat-icon.warning { background: #fffbeb; color: #f59e0b; }
    .stat-icon.danger  { background: #fef2f2; color: #ef4444; }
    .stat-icon.purple  { background: #fdf4ff; color: #a855f7; }
    
    .stat-number { font-size: 2rem; font-weight: 800; color: #0f172a; line-height: 1.1; margin-bottom: 6px; }
    .stat-label { font-size: 0.85rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Section Cards */
    .content-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--baps-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        padding: 28px;
        margin-bottom: 28px;
        transition: box-shadow 0.3s ease;
    }
    .content-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    .content-card-header {
        border-bottom: 1px solid var(--baps-border);
        padding-bottom: 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .content-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        text-decoration: none;
        border: 1px solid var(--baps-border);
        background: #ffffff;
        color: var(--baps-text);
        width: 100%;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .action-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: var(--baps-saffron);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.05);
    }
    .action-btn-primary {
        background: var(--baps-saffron);
        color: white !important;
        border-color: var(--baps-saffron);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2);
    }
    .action-btn-primary:hover {
        background: var(--baps-saffron-dark);
        border-color: var(--baps-saffron-dark);
        color: white !important;
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid var(--baps-border);
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--baps-saffron);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
        outline: none;
    }

    .badge-role {
        background: #f1f5f9;
        color: #1e293b;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        border: 1px solid #cbd5e1;
        display: inline-block;
    }
    
    .table-responsive {
        border-radius: 12px;
        border: 1px solid var(--baps-border);
        overflow: hidden;
    }
    .table { margin-bottom: 0; width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color: #475569; padding: 14px 18px; border-bottom: 1px solid var(--baps-border); text-align: left; }
    .table td { padding: 14px 18px; vertical-align: middle; border-bottom: 1px solid var(--baps-border); font-size: 0.95rem; }

    /* Next-Gen Innovation Cards */
    .innovation-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--baps-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        padding: 28px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .innovation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
    }
    .innovation-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 5px;
    }
    .innovation-card.accent-red::before { background: linear-gradient(90deg, #ef4444, #f97316); }
    .innovation-card.accent-blue::before { background: linear-gradient(90deg, #3b82f6, #06b6d4); }
    .innovation-card.accent-green::before { background: linear-gradient(90deg, #10b981, #14b8a6); }
    .innovation-card.accent-purple::before { background: linear-gradient(90deg, #a855f7, #ec4899); }

    .innovation-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }
    .innovation-card:hover .innovation-icon { transform: scale(1.15) rotate(5deg); }
    .innovation-card.accent-red .innovation-icon { background: #fef2f2; color: #ef4444; }
    .innovation-card.accent-blue .innovation-icon { background: #eff6ff; color: #3b82f6; }
    .innovation-card.accent-green .innovation-icon { background: #f0fdf4; color: #10b981; }
    .innovation-card.accent-purple .innovation-icon { background: #fdf4ff; color: #a855f7; }

    .innovation-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
    .innovation-desc { font-size: 0.9rem; color: #64748b; margin-bottom: 24px; flex-grow: 1; line-height: 1.5; }

    .innovation-btn {
        width: 100%;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .innovation-card.accent-red .innovation-btn { background: #fef2f2; color: #ef4444; }
    .innovation-card.accent-red .innovation-btn:hover { background: #fee2e2; color: #dc2626; }
    .innovation-card.accent-blue .innovation-btn { background: #eff6ff; color: #3b82f6; }
    .innovation-card.accent-blue .innovation-btn:hover { background: #dbeafe; color: #2563eb; }
    .innovation-card.accent-green .innovation-btn { background: #f0fdf4; color: #10b981; }
    .innovation-card.accent-green .innovation-btn:hover { background: #dcfce7; color: #16a34a; }
    .innovation-card.accent-purple .innovation-btn { background: #fdf4ff; color: #a855f7; }
    .innovation-card.accent-purple .innovation-btn:hover { background: #fae8ff; color: #9333ea; }

    /* BAPS-e.learn Dark Mode Administrative Styling Engine */
    body.dark-mode {
        background-color: #0f172a !important;
        color: #f8fafc !important;
    }
    body.dark-mode .baps-header-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
    }
    body.dark-mode .baps-header-card h3,
    body.dark-mode .baps-header-card .text-dark,
    body.dark-mode .baps-header-card h4 {
        color: #ffffff !important;
    }
    body.dark-mode .baps-header-card .text-muted {
        color: #94a3b8 !important;
    }
    body.dark-mode .badge-role {
        background: #334155 !important;
        color: #f8fafc !important;
        border-color: #475569 !important;
    }
    body.dark-mode .baps-logo-wrapper {
        background: #334155 !important;
        border-color: #475569 !important;
    }
    body.dark-mode .baps-nav-pills {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    body.dark-mode .baps-tab-btn {
        color: #94a3b8 !important;
    }
    body.dark-mode .baps-tab-btn:hover {
        background: #334155 !important;
        color: #f8fafc !important;
    }
    body.dark-mode .baps-tab-btn.active {
        background: var(--baps-saffron) !important;
        color: #ffffff !important;
    }
    body.dark-mode .stat-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }
    body.dark-mode .stat-card:hover {
        border-color: #475569 !important;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3) !important;
    }
    body.dark-mode .stat-number {
        color: #ffffff !important;
    }
    body.dark-mode .stat-label {
        color: #94a3b8 !important;
    }
    body.dark-mode .content-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }
    body.dark-mode .content-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3) !important;
    }
    body.dark-mode .content-card-header {
        border-bottom-color: #334155 !important;
        background-color: #1e293b !important;
    }
    body.dark-mode .content-card-title,
    body.dark-mode .card-title,
    body.dark-mode .modal-title {
        color: #ffffff !important;
    }
    body.dark-mode .action-btn {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    body.dark-mode .action-btn:hover {
        background: #334155 !important;
        border-color: #475569 !important;
        color: var(--baps-saffron) !important;
    }
    body.dark-mode .action-btn-primary {
        background: var(--baps-saffron) !important;
        border-color: var(--baps-saffron) !important;
        color: white !important;
    }
    body.dark-mode .action-btn-primary:hover {
        background: var(--baps-saffron-dark) !important;
        border-color: var(--baps-saffron-dark) !important;
    }
    body.dark-mode .innovation-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }
    body.dark-mode .innovation-card:hover {
        border-color: #475569 !important;
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.3) !important;
    }
    body.dark-mode .innovation-title {
        color: #ffffff !important;
    }
    body.dark-mode .innovation-desc {
        color: #94a3b8 !important;
    }
    body.dark-mode .table-responsive {
        border-color: #334155 !important;
    }
    body.dark-mode .table th {
        background: #1e293b !important;
        color: #f8fafc !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .table td {
        color: #cbd5e1 !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    body.dark-mode .form-control, 
    body.dark-mode .form-select,
    body.dark-mode textarea {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    body.dark-mode .form-control::placeholder {
        color: #64748b !important;
    }
    body.dark-mode .form-control:focus, 
    body.dark-mode .form-select:focus {
        border-color: var(--baps-saffron) !important;
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25) !important;
    }
    body.dark-mode .card {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    body.dark-mode .card-header {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    body.dark-mode .card-body {
        color: #cbd5e1 !important;
    }
    body.dark-mode .text-dark {
        color: #ffffff !important;
    }
    body.dark-mode .text-muted {
        color: #94a3b8 !important;
    }
    body.dark-mode .nav-pills {
        background-color: #1e293b !important;
    }
    body.dark-mode .nav-pills .nav-link:not(.active) {
        color: #cbd5e1 !important;
    }
    body.dark-mode .table-light {
        --bs-table-bg: #1e293b !important;
        --bs-table-color: #ffffff !important;
        --bs-table-border-color: #334155 !important;
    }
    body.dark-mode .table-light th {
        background-color: #1e293b !important;
        color: #ffffff !important;
    }
    body.dark-mode div[style*="background-color: #fafbfc"],
    body.dark-mode div[style*="background-color: rgb(250, 251, 252)"],
    body.dark-mode .bg-light {
        background-color: #1a202c !important;
        border-color: #2d3748 !important;
    }
    body.dark-mode .list-group-item.bg-light {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    body.dark-mode code {
        background-color: #0f172a !important;
        color: var(--baps-saffron) !important;
    }
    body.dark-mode .modal-content {
        background-color: #1e293b !important;
        color: #ffffff !important;
    }
    body.dark-mode .modal-header {
        border-bottom: 1px solid #334155 !important;
    }
    body.dark-mode .modal-footer {
        background-color: #1e293b !important;
        border-top: 1px solid #334155 !important;
    }
</style>

<!-- Professional Responsive Header -->
<div class="baps-header-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-4 text-center text-lg-start">
        <div class="d-flex flex-column flex-sm-row align-items-center gap-4">
            <div class="baps-logo-wrapper">
                <img src="/img/baps_logo.png" alt="BAPS Logo" onerror="this.src='https://placehold.co/80x80/f97316/white?text=BAPS'">
            </div>
            <div>
                <h3 class="mb-1 fw-bold text-dark d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                    BAPS Staff Admin
                    @if(session('user_role') == 'admin')
                    <a href="/admin/master-data" class="text-decoration-none text-muted" style="font-size:0.6rem; opacity:0.2;" title="Secret System Settings"><i class="fas fa-circle"></i></a>
                    @endif
                </h3>
                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 1.5px;">Global Institution Portal</div>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center justify-content-lg-end align-items-center gap-3">
            @php
                $staffId = session('staff_id');
                $staff = \App\Models\Staff::find($staffId);
                $role = session('user_role');
                $roleLabel = ucfirst($role);
                if (in_array($role, ['admin', 'dean'])) { 
                    if($role=='dean') $roleLabel="Dean (Department Head)"; 
                    if($role=='admin') $roleLabel="Administrator";
                    $roleLabel .= " - 200% Access";
                }
                elseif ($role == 'hod') { $roleLabel = 'HOD (Head of Dept)'; }
                elseif ($role == 'office-assistant') { $roleLabel = 'Office Assistant - 175% Access (Behalf of Dean)'; }
                elseif (in_array($role, ['faculty', 'faculty-lecturer-lab'])) { $roleLabel = 'Faculty - Lecturer & Lab'; }
                elseif ($role == 'coordinator') { $roleLabel = 'Coordinator'; }
                elseif ($role == 'faculty-lecturer-coordinator') { $roleLabel = 'Faculty - Lecturer & Coordinator'; }
                elseif ($role == 'cr') { $roleLabel = 'Class Representative'; }
            @endphp
            
            <div class="text-center text-sm-end">
                <div class="mb-1">
                    <span class="badge-role shadow-sm">
                        {{ session('staff_name') ?? 'BHAVIKKUMAR PATEL' }}
                    </span>
                </div>
                <div class="small fw-bold text-muted">
                    <i class="fas fa-shield-alt me-1" style="color: var(--baps-saffron);"></i> {{ $roleLabel }}
                </div>
            </div>

            {{-- Admin Profile Photo --}}
            <div class="position-relative flex-shrink-0" style="width: 62px; height: 62px;">
                <img id="adminProfileAvatar" 
                     src="{{ ($staff && ($staff->profile_photo_data || $staff->profile_photo)) ? url('/profile/photo/staff/' . $staff->id) : 'https://ui-avatars.com/api/?name=' . urlencode(session('staff_name') ?? 'BHAVIKKUMAR PATEL') . '&background=f97316&color=fff&size=120' }}" 
                     class="rounded-4 shadow-sm border border-2 border-white w-100 h-100" 
                     style="object-fit: cover; cursor: pointer;" 
                     title="Click to change photo">
                <input type="file" id="adminPhotoInput" accept="image/*" style="display:none;">
                <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-2 border-white shadow-sm" style="width: 22px; height: 22px; transform: translate(20%, 20%); cursor: pointer;" onclick="document.getElementById('adminPhotoInput').click();">
                    <i class="fas fa-camera" style="font-size: 10px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 10 TABS NAVIGATION (Perfectly Consolidated & Error-Free) -->
<ul class="nav nav-pills baps-nav-pills" id="adminDashboardTabs" role="tablist">
    <li class="nav-item" role="presentation" data-tab-id="tab-overview">
        <button class="nav-link active baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-overview" type="button" role="tab">
            <i class="fas fa-chart-pie text-primary"></i> 1. Overview
        </button>
    </li>
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator']))
    <li class="nav-item" role="presentation" data-tab-id="tab-academic">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-academic" type="button" role="tab">
            <i class="fas fa-graduation-cap text-success"></i> 2. Academic
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'office-assistant']) || session('staff_name') == 'Rajunakum Sir')
    <li class="nav-item" role="presentation" data-tab-id="tab-exams">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-exams" type="button" role="tab">
            <i class="fas fa-file-alt text-danger"></i> 3. Exams
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'cr']))
    <li class="nav-item" role="presentation" data-tab-id="tab-directory">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-directory" type="button" role="tab">
            <i class="fas fa-users text-info"></i> 4. Directory
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'office-assistant']) || session('staff_name') == 'Rajunakum Sir')
    <li class="nav-item" role="presentation" data-tab-id="tab-approvals">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-approvals" type="button" role="tab">
            <i class="fas fa-check-circle text-warning"></i> 5. Approvals
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'cr', 'coordinator', 'faculty-lecturer-coordinator']) || session('staff_name') == 'Rajunakum Sir')
    <li class="nav-item" role="presentation" data-tab-id="tab-operations">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-operations" type="button" role="tab">
            <i class="fas fa-tasks text-purple" style="color: #9333ea;"></i> 6. Operations & Campus
        </button>
    </li>
    @endif
    <li class="nav-item" role="presentation" data-tab-id="tab-hostel">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-hostel" type="button" role="tab">
            <i class="fas fa-bed text-secondary"></i> 7. Hostel Mgmt
        </button>
    </li>
    <li class="nav-item" role="presentation" data-tab-id="tab-ipdc">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-ipdc" type="button" role="tab">
            <i class="fas fa-hand-holding-heart text-saffron" style="color: var(--baps-saffron);"></i> 8. IPDC Vault
        </button>
    </li>
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant']))
    <li class="nav-item" role="presentation" data-tab-id="tab-reports">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-reports" type="button" role="tab">
            <i class="fas fa-chart-line text-success"></i> 9. Reports
        </button>
    </li>
    @endif
    <li class="nav-item" role="presentation" data-tab-id="tab-system">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-system" type="button" role="tab">
            <i class="fas fa-cog text-dark"></i> 10. System & AI Hub
        </button>
    </li>
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
    <li class="nav-item" role="presentation" data-tab-id="tab-oa-coordination">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-oa-coordination" type="button" role="tab">
            <i class="fas fa-handshake text-primary"></i> 11. OA Coordination
        </button>
    </li>
    @endif
    <li class="nav-item" role="presentation" data-tab-id="tab-official-documents">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-official-documents" type="button" role="tab">
            <i class="fas fa-file-contract text-danger"></i> 12. Document Giving Vault
        </button>
    </li>
    <li class="nav-item" role="presentation" data-tab-id="tab-circulars">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-circulars" type="button" role="tab">
            <i class="fas fa-bullhorn text-warning"></i> 17. Circulars & Official Works
        </button>
    </li>
    <li class="nav-item" role="presentation" data-tab-id="tab-synergy-circle">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-synergy-circle" type="button" role="tab">
            <i class="fas fa-circle-nodes text-indigo" style="color: #6366f1 !important;"></i> 18. Synergy Circle
        </button>
    </li>
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
    <li class="nav-item" role="presentation" data-tab-id="tab-volunteer">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-volunteer" type="button" role="tab">
            <i class="fas fa-hands-helping text-teal" style="color: #0d9488 !important;"></i> 13. Volunteer Service Log
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean']))
    <li class="nav-item" role="presentation" data-tab-id="tab-role-settings">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-role-settings" type="button" role="tab">
            <i class="fas fa-user-shield text-dark" style="color: #0f172a !important;"></i> 14. Role Tab Setting
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
    <li class="nav-item" role="presentation" data-tab-id="tab-payroll">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-payroll" type="button" role="tab">
            <i class="fas fa-file-invoice-dollar text-indigo" style="color: #4f46e5 !important;"></i> 15. Payroll Tab
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean']))
    <li class="nav-item" role="presentation" data-tab-id="tab-settings">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-settings" type="button" role="tab">
            <i class="fas fa-sliders-h text-secondary" style="color: #64748b !important;"></i> 16. Settings Tab
        </button>
    </li>
    @endif
    @if(session('user_role') === 'admin')
    <li class="nav-item" role="presentation" data-tab-id="tab-maintenance">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-maintenance" type="button" role="tab">
            <i class="fas fa-tools text-danger"></i> 21. Maintenance
        </button>
    </li>
    @endif
    <li class="nav-item" role="presentation" data-tab-id="tab-student-queries">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-student-queries" type="button" role="tab">
            <i class="fas fa-question-circle text-info"></i> 19. Student Queries
        </button>
    </li>
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator']))
    <li class="nav-item" role="presentation" data-tab-id="tab-special-courses">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-special-courses" type="button" role="tab">
            <i class="fas fa-puzzle-piece text-danger" style="color: #ef4444 !important;"></i> 20. Special Courses
        </button>
    </li>
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator']))
    <li class="nav-item" role="presentation" data-tab-id="tab-admin-ptm">
        <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-admin-ptm" type="button" role="tab">
            <i class="fas fa-comments text-danger"></i> 22. PTM Hub
        </button>
    </li>
    @endif

    @php
        $customTabsFile = storage_path('app/custom_tabs.json');
        $customTabs = [];
        if (file_exists($customTabsFile)) {
            $customTabs = json_decode(file_get_contents($customTabsFile), true) ?? [];
        }
        $currentUserRole = session('user_role');
    @endphp

    @foreach($customTabs as $ct)
        @if(in_array($currentUserRole, $ct['roles']))
            <li class="nav-item" role="presentation" data-tab-id="{{ $ct['id'] }}">
                <button class="nav-link baps-tab-btn" data-bs-toggle="tab" data-bs-target="#{{ $ct['id'] }}" type="button" role="tab">
                    <i class="{{ $ct['icon'] }} text-primary"></i> {{ $ct['title'] }}
                </button>
            </li>
        @endif
    @endforeach
</ul>

<div class="tab-content" id="adminDashboardTabsContent">
    @include('admin.partials.tab_overview')
    @include('admin.partials.tab_academic')
    @include('admin.partials.tab_exams')
    @include('admin.partials.tab_directory')
    @include('admin.partials.tab_approvals')
    @include('admin.partials.tab_operations')
    @include('admin.partials.tab_hostel')
    @include('admin.partials.tab_ipdc')
    @include('admin.partials.tab_reports')
    @include('admin.partials.tab_system')
    @include('admin.partials.tab_oa_coordination')
    @include('admin.partials.tab_official_documents')
    @include('admin.partials.tab_circulars')
    @include('admin.partials.tab_synergy_circle')

    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
        @include('admin.partials.tab_volunteer')
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean']))
        @include('admin.partials.tab_role_settings')
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
        @include('admin.partials.tab_payroll')
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean']))
        @include('admin.partials.tab_settings')
    @endif
    @if(session('user_role') === 'admin')
        @include('admin.partials.tab_maintenance')
    @endif
    <div class="tab-pane fade" id="tab-student-queries" role="tabpanel">
        @include('admin.partials.tab_student_queries')
    </div>
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator']))
        @include('admin.partials.tab_special_courses')
    @endif
    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator']))
        @include('admin.partials.tab_admin_ptm')
    @endif

    @foreach($customTabs as $ct)
        @if(in_array($currentUserRole, $ct['roles']))
            <div class="tab-pane fade" id="{{ $ct['id'] }}" role="tabpanel">
                @include('admin.partials.' . $ct['filename'])
            </div>
        @endif
    @endforeach
</div>

<!-- Modals -->
<div class="modal fade" id="accessMatrixModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2 text-warning"></i> Institutional Access Matrix</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light sticky-top"><tr><th width="20%" class="ps-4">Role</th><th width="15%" class="text-center">Privilege</th><th width="65%" class="pe-4">Access Rights</th></tr></thead>
                    <tbody>
                        <tr><td class="fw-bold text-success ps-4">Administrator</td><td class="text-center fw-bold fs-5 text-success">200%</td><td class="small text-muted pe-4">God Mode. Unrestricted access to master data, GUI injects, and deep settings.</td></tr>
                        <tr><td class="fw-bold text-success ps-4">Dean</td><td class="text-center fw-bold fs-5 text-success">200%</td><td class="small text-muted pe-4">Executive control, global reports, and broad visibility.</td></tr>
                        <tr><td class="fw-bold text-warning ps-4">HOD</td><td class="text-center fw-bold fs-5 text-warning">175%</td><td class="small text-muted pe-4">Departmental authority. Approves enrollments and staff for their sector.</td></tr>
                        <tr><td class="fw-bold text-primary ps-4">Coordinator / CC</td><td class="text-center fw-bold fs-5 text-primary">125%</td><td class="small text-muted pe-4">Logistics focus. Bulk enrollment, Talent Hub, mapping students.</td></tr>
                        <tr><td class="fw-bold text-secondary ps-4">CR</td><td class="text-center fw-bold fs-5 text-secondary">125%</td><td class="small text-muted pe-4">Student rep. Assist in coordination, view peer enrollments.</td></tr>
                        <tr><td class="fw-bold ps-4" style="color:#d946ef;">Fac-Coordinator</td><td class="text-center fw-bold fs-5" style="color:#d946ef;">75%</td><td class="small text-muted pe-4">Hybrid. Teaching + logistics (bulk enroll, basic approvals).</td></tr>
                        <tr><td class="fw-bold text-info ps-4">Fac-Lab</td><td class="text-center fw-bold fs-5 text-info">50%</td><td class="small text-muted pe-4">Academic execution. Courses, quizzes, grading.</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-top-0 bg-light p-3"><button type="button" class="btn btn-dark fw-bold px-5 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant']))
<div class="modal fade" id="configMatrixModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form action="/admin/config/module-access" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-sliders-h me-2 text-info"></i> Security Policy Configurator</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-hover mb-0 text-center align-middle">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="text-start ps-4">Module Policy</th>
                            <th>Admin</th><th>Dean</th><th>HOD</th><th>Fac-Crd</th><th>Coord</th><th>Fac-Lab</th><th>CR</th><th class="pe-4">Student</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $modules = [
                                'Reports' => ['admin', 'dean'],
                                'Bulk Enroll' => ['admin', 'dean', 'hod', 'faculty-lecturer-coordinator', 'coordinator', 'cr'],
                                'Exam Center' => ['admin', 'dean', 'hod', 'faculty-lecturer-coordinator', 'faculty-lecturer-lab', 'cr'],
                                'Approvals' => ['admin', 'dean', 'hod', 'cr']
                            ];
                            $roles = ['admin', 'dean', 'hod', 'faculty-lecturer-coordinator', 'coordinator', 'faculty-lecturer-lab', 'cr', 'student'];
                        @endphp
                        @foreach($modules as $moduleName => $defaultAccess)
                        <tr>
                            <td class="text-start fw-bold text-dark small ps-4"><i class="fas fa-lock text-secondary me-2"></i> {{ $moduleName }}</td>
                            @foreach($roles as $index => $r)
                            <td class="{{ $index == 7 ? 'pe-4' : '' }}">
                                <input class="form-check-input border-secondary shadow-sm" type="checkbox" name="permissions[{{ str_replace(' ', '_', $moduleName) }}][]" value="{{ $r }}" {{ in_array($r, $defaultAccess) ? 'checked' : '' }} {{ in_array($r, ['admin', 'dean']) && in_array($r, $defaultAccess) ? 'disabled' : '' }}>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-top-0 bg-light p-4">
                <button type="button" class="btn btn-light px-5 py-2 fw-bold border rounded-pill me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark px-5 py-2 fw-bold rounded-pill shadow-sm"><i class="fas fa-save me-2"></i> Apply Policy</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-alt me-2 text-info"></i> Document Preview Engine</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh; background: #e2e8f0;">
                <iframe id="previewIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Office Assistant Executive Hub Modal -->
@include('admin.partials.modal_office_assistant')

<script>
    $('#lesson_type').change(function() {
        if ($(this).val() == 'youtube') {
            $('#file_input_div').addClass('d-none'); $('#url_input_div').removeClass('d-none');
            $('#file_field').prop('required', false); $('#url_field').prop('required', true);
        } else {
            $('#file_input_div').removeClass('d-none'); $('#url_input_div').addClass('d-none');
            $('#file_field').prop('required', true); $('#url_field').prop('required', false);
        }
    });
    function launchVideo(id) { window.open('https://www.youtube.com/watch?v=' + id, '_blank'); }
    function previewFile(url) { document.getElementById('previewIframe').src = url; new bootstrap.Modal(document.getElementById('filePreviewModal')).show(); }

    function startZeroxTask(btn) {
        let row = btn.closest('tr');
        row.cells[5].innerHTML = '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Printing...</span>';
        btn.classList.remove('btn-primary'); btn.classList.add('btn-success');
        btn.innerHTML = '<i class="fas fa-check"></i>'; btn.title = "Finalize Task";
        btn.setAttribute('onclick', 'finalizeZeroxTask(this)');
        let input = row.querySelector('input[type="number"]');
        if(input) { input.classList.remove('border-primary'); input.classList.add('border-success'); input.focus(); }
    }

    function finalizeZeroxTask(btn) {
        let row = btn.closest('tr'); let input = row.querySelector('input[type="number"]');
        if (!input || !input.value) { alert("Please enter the Billed Amount (₹) before finalizing!"); if(input) input.focus(); return; }
        row.cells[5].innerHTML = '<span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">Completed</span>';
        let val = parseFloat(input.value).toFixed(2);
        row.cells[4].innerHTML = '<span class="fw-bold text-success fs-6">₹ ' + val + '</span>';
        btn.remove();
    }
</script>

<script>
// Admin Profile Photo Upload Engine
document.getElementById('adminProfileAvatar')?.addEventListener('click', () => document.getElementById('adminPhotoInput').click());
document.getElementById('adminPhotoInput')?.addEventListener('change', function () {
    const file = this.files[0]; if (!file) return;
    if (file.size > 2 * 1024 * 1024) { if (typeof showBapsToast === 'function') showBapsToast('File too large! Max 2MB.', 'error'); return; }
    const avatar = document.getElementById('adminProfileAvatar'); const previousSrc = avatar.src;
    const reader = new FileReader(); reader.onload = e => { avatar.src = e.target.result; avatar.style.opacity = '0.5'; }; reader.readAsDataURL(file);
    const formData = new FormData(); formData.append('photo', file); formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content);

    fetch('/profile/upload-photo', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(async r => {
        const data = await r.json(); avatar.style.opacity = '1';
        if (data.success) { avatar.src = data.url; if (typeof showBapsToast === 'function') showBapsToast('Admin Photo Updated! 📷', 'success'); } 
        else { avatar.src = previousSrc; if (typeof showBapsToast === 'function') showBapsToast('❌ ' + (data.error || 'Upload failed'), 'error'); }
    })
    .catch(err => { avatar.style.opacity = '1'; avatar.src = previousSrc; if (typeof showBapsToast === 'function') showBapsToast('Network error during upload.', 'error'); });
});
</script>

<script>
const CURRENT_USER_ROLE = "{{ session('user_role') }}";

document.addEventListener('DOMContentLoaded', function() {
    // 1. Define roles and default access lists for fallbacks
    const defaultAccess = {
        'tab-overview': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
        'tab-academic': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator'],
        'tab-exams': ['admin', 'cr', 'hod', 'dean', 'office-assistant'],
        'tab-directory': ['admin', 'dean', 'office-assistant', 'hod', 'cr'],
        'tab-approvals': ['admin', 'cr', 'hod', 'dean', 'office-assistant'],
        'tab-operations': ['admin', 'cr', 'coordinator', 'faculty-lecturer-coordinator'],
        'tab-hostel': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
        'tab-ipdc': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
        'tab-reports': ['admin', 'dean', 'office-assistant'],
        'tab-system': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
        'tab-oa-coordination': ['admin', 'dean', 'office-assistant', 'hod'],
        'tab-official-documents': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
        'tab-circulars': ['admin', 'dean', 'office-assistant', 'hod', 'coordinator', 'faculty', 'cr'],
        'tab-volunteer': ['admin', 'dean', 'office-assistant', 'hod'],
        'tab-role-settings': ['admin', 'dean'],
        'tab-payroll': ['admin', 'dean', 'office-assistant', 'hod'],
        'tab-settings': ['admin', 'dean'],
        'tab-maintenance': ['admin'],
        'tab-admin-ptm': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator'],
        'tab-synergy-circle': ['admin', 'dean', 'office-assistant', 'hod', 'faculty'],
        'tab-student-queries': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator'],
        'tab-special-courses': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator']
    };

    // 2. Fetch configured tab access or initialize if empty
    let tabAccess = localStorage.getItem('tab_access_config');
    if (!tabAccess) {
        tabAccess = defaultAccess;
        localStorage.setItem('tab_access_config', JSON.stringify(tabAccess));
    } else {
        tabAccess = JSON.parse(tabAccess);
        let updated = false;
        Object.keys(defaultAccess).forEach(key => {
            if (!tabAccess.hasOwnProperty(key)) {
                tabAccess[key] = defaultAccess[key];
                updated = true;
            } else {
                // Sync any new default roles (like CR) into the cached config
                defaultAccess[key].forEach(role => {
                    if (!tabAccess[key].includes(role)) {
                        tabAccess[key].push(role);
                        updated = true;
                    }
                });
            }
        });
        if (updated) {
            localStorage.setItem('tab_access_config', JSON.stringify(tabAccess));
        }
    }

    // 3. Apply visibility rules to standard tabs
    Object.keys(tabAccess).forEach(tabId => {
        let allowedRoles = tabAccess[tabId] || [];
        
        // Force CR to have access to default CR tabs to override stale client-side cache
        if (CURRENT_USER_ROLE === 'cr') {
            const crTabs = ['tab-overview', 'tab-academic', 'tab-exams', 'tab-directory', 'tab-approvals', 'tab-operations', 'tab-hostel', 'tab-ipdc', 'tab-system', 'tab-official-documents', 'tab-circulars', 'tab-special-courses'];
            if (crTabs.includes(tabId) && !allowedRoles.includes('cr')) {
                allowedRoles.push('cr');
            }
        }

        const isAllowed = allowedRoles.includes(CURRENT_USER_ROLE);
        
        const navItem = document.querySelector(`[data-tab-id="${tabId}"]`);
        const tabPane = document.getElementById(tabId);
        
        if (!isAllowed) {
            if (navItem) navItem.style.display = 'none';
            if (tabPane) tabPane.classList.remove('active', 'show');
        } else {
            if (navItem) navItem.style.display = 'block';
        }
    });



    // Ensure first visible tab is active if current active is hidden
    setTimeout(() => {
        const activeBtn = document.querySelector('#adminDashboardTabs .nav-link.active');
        if (activeBtn && activeBtn.closest('.nav-item').style.display === 'none') {
            activeBtn.classList.remove('active');
            const firstVisibleBtn = Array.from(document.querySelectorAll('#adminDashboardTabs .nav-link')).find(btn => btn.closest('.nav-item').style.display !== 'none');
            if (firstVisibleBtn) {
                firstVisibleBtn.classList.add('active');
                const targetId = firstVisibleBtn.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetId);
                if (targetPane) targetPane.classList.add('active', 'show');
            }
        }
    }, 100);
});
</script>

@endsection
