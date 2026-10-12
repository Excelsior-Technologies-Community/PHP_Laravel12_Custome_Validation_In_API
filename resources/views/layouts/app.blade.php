<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product API with Custom Validation</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js, JsBarcode, and QR Scanner CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 50px;
        }
        .nav-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .validation-rule {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .api-response {
            background: #f8f9fa;
            border-radius: 5px;
            max-height: 300px;
            overflow-y: auto;
        }
        .success-badge {
            background-color: #d4edda;
            color: #155724;
        }
        .error-badge {
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark nav-gradient mb-4 sticky-top shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-shield-alt me-1"></i> Custom Validation API
            </a>
            <div class="navbar-nav ms-auto flex-row gap-3">
                <a class="nav-link" href="/#products"><i class="fas fa-boxes me-1"></i> Products</a>
                <a class="nav-link" href="/#analytics"><i class="fas fa-chart-pie me-1"></i> Analytics</a>
                <a class="nav-link" href="/#stock-radar"><i class="fas fa-heartbeat me-1"></i> Stock Radar</a>
                <a class="nav-link" href="/products/create"><i class="fas fa-plus me-1"></i> Create Product</a>
                <a class="nav-link" href="/#bulk"><i class="fas fa-layer-group me-1"></i> Bulk</a>
                <a class="nav-link" href="/labels" target="_blank"><i class="fas fa-barcode me-1"></i> Labels</a>
                <a class="nav-link" href="/#validation"><i class="fas fa-shield-alt me-1"></i> Rules</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Axios for API calls -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Set CSRF token for all Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Global API base URL
        const API_BASE = '/api';
        
        // Global error handler
        function showError(message) {

    Swal.fire({

        icon: 'error',

        title: 'Error',

        text: message

    });

}
        
        // Format JSON for display
        function formatJSON(data) {
            return JSON.stringify(data, null, 2);
        }
    </script>
    
    @yield('scripts')
</body>
</html>