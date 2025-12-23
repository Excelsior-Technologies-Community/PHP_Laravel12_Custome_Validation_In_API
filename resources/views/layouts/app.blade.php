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
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark nav-gradient mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-shield-alt"></i> Custom Validation API
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="#products">Products</a>
                <a class="nav-link" href="#create">Create Product</a>
                <a class="nav-link" href="#bulk">Bulk Create</a>
                <a class="nav-link" href="#validation">Validation Rules</a>
            </div>
        </div>
    </nav>

    <div class="container">
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
            alert('Error: ' + message);
        }
        
        // Format JSON for display
        function formatJSON(data) {
            return JSON.stringify(data, null, 2);
        }
    </script>
    
    @yield('scripts')
</body>
</html>