@extends('layouts.app')

@section('content')

    <!-- Dashboard Header -->
    <div class="row mb-4">

        <div class="col-12">

            <div class="card shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h2 class="fw-bold mb-1">
                                <i class="fas fa-box-open text-primary"></i>
                                Laravel Product Dashboard
                            </h2>

                            <p class="text-muted mb-0">
                                Product Management with Custom Validation API
                            </p>

                        </div>

                        <div class="d-flex gap-2">
                            <a href="/labels" target="_blank" class="btn btn-outline-dark">
                                <i class="fas fa-barcode me-1"></i> Print Labels
                            </a>
                            <button class="btn btn-dark" onclick="openBarcodeScanner('search')">
                                <i class="fas fa-camera me-1"></i> Scan Barcode
                            </button>
                            <button class="btn btn-primary" onclick="loadProducts(); loadStats(); loadAnalytics();">
                                <i class="fas fa-rotate"></i> Refresh
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Statistics Cards -->

    <div class="row">

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card bg-primary text-white shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Total Products</h6>

                            <h2 id="totalProducts">0</h2>

                        </div>

                        <div>

                            <i class="fas fa-box fa-3x"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card bg-success text-white shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Available Products</h6>

                            <h2 id="activeProducts">0</h2>

                        </div>

                        <div>

                            <i class="fas fa-check-circle fa-3x"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card bg-warning text-dark shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Total Stock</h6>

                            <h2 id="totalStock">0</h2>

                        </div>

                        <div>

                            <i class="fas fa-warehouse fa-3x"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card bg-info text-white shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6>Categories</h6>

                            <h2 id="categories">0</h2>

                        </div>

                        <div>

                            <i class="fas fa-tags fa-3x"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- 📊 VISUAL ANALYTICS STUDIO: CHART.JS & TOP ASSETS -->
    <div class="row mt-4" id="analytics">
        <!-- Category Share Donut Chart -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-chart-pie text-primary me-2"></i> Category Share
                    </h6>
                    <span class="badge bg-light text-muted">Donut</span>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-3" style="min-height: 260px;">
                    <canvas id="categoryDonutChart" style="max-height: 240px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Price Tier Histogram -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-chart-bar text-success me-2"></i> Price Tier Distribution
                    </h6>
                    <span class="badge bg-light text-muted">Histogram</span>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-3" style="min-height: 260px;">
                    <canvas id="priceTierChart" style="max-height: 240px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Valuable Stock Assets -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-crown text-warning me-2"></i> Top Valuable Assets
                    </h6>
                    <span class="badge bg-warning text-dark">Value = Price × Stock</span>
                </div>
                <div class="card-body p-2" id="topAssetsContainer">
                    <div class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary"></div> Loading Assets...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 📉 SMART INVENTORY LIFECYCLE ALERT & AUTO-RESTOCK THRESHOLD MATRIX -->
    <div class="card shadow border-0 mb-4" id="stock-radar">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">
                <i class="fas fa-heartbeat text-danger me-2"></i> Smart Inventory Lifecycle Radar & Expiry Watchdog
            </h5>
            <span class="badge bg-danger animate-pulse">Live Health Monitor</span>
        </div>
        <div class="card-body bg-light">
            <div class="row g-3">
                <!-- Stock Depletion Progress Bars -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-3 h-100">
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="fas fa-tachometer-alt me-2 text-info"></i> Stock Depletion Ratio
                        </h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-danger"><i class="fas fa-circle-exclamation me-1"></i> Critical Low (&lt; 5 units)</span>
                                <span class="fw-bold text-danger" id="criticalStockCount">0 items</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div id="criticalStockBar" class="progress-bar bg-danger" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-warning"><i class="fas fa-triangle-exclamation me-1"></i> Warning Level (5 - 20 units)</span>
                                <span class="fw-bold text-warning" id="lowStockCount">0 items</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div id="lowStockBar" class="progress-bar bg-warning" style="width: 0%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-success"><i class="fas fa-circle-check me-1"></i> Healthy Level (&gt; 20 units)</span>
                                <span class="fw-bold text-success" id="healthyStockCount">0 items</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div id="healthyStockBar" class="progress-bar bg-success" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiry Countdown Alerts -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-3 h-100">
                        <h6 class="fw-bold text-secondary mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hourglass-half me-2 text-danger"></i> Batch Expiry Countdown (Next 30 Days)</span>
                            <span class="badge bg-danger" id="expiredBadge">0 Expired</span>
                        </h6>
                        <div id="expiringSoonList" class="overflow-auto" style="max-height: 140px;">
                            <div class="text-muted small text-center py-3">No immediate batch expirations detected.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
<div class="card shadow border-0 mt-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">
            <i class="fas fa-sliders-h me-2"></i>
            Search & Filter Products
        </h5>

    </div>


    <div class="card-body bg-light">

        <div class="row g-3 align-items-center">


            <!-- Search -->

            <div class="col-lg-4 col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fas fa-search text-primary"></i>
                    Search Product
                </label>

                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control rounded-pill shadow-sm"
                    placeholder="Search product..."
                    onkeyup="debouncedSearch()">

            </div>


            <!-- Category -->

            <div class="col-lg-2 col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fas fa-folder text-warning"></i>
                    Category
                </label>

                <select 
                    id="categoryFilter"
                    class="form-select rounded-pill shadow-sm"
                    onchange="loadProducts()">

                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="books">Books</option>
                    <option value="home">Home</option>
                    <option value="sports">Sports</option>
                    <option value="beauty">Beauty</option>

                </select>

            </div>


            <!-- Status -->

            <div class="col-lg-2 col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fas fa-circle text-success"></i>
                    Status
                </label>

                <select 
                    id="statusFilter"
                    class="form-select rounded-pill shadow-sm"
                    onchange="loadProducts()">

                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="draft">Draft</option>
                    <option value="discontinued">Discontinued</option>

                </select>

            </div>


            <!-- Sort -->

            <div class="col-lg-2 col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fas fa-sort text-info"></i>
                    Sort By
                </label>

                <select 
                    id="sortSelect"
                    class="form-select rounded-pill shadow-sm"
                    onchange="loadProducts()">

                    <option value="created_at">Newest</option>
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="stock">Stock</option>

                </select>

            </div>


            <!-- Order -->

            <div class="col-lg-2 col-md-6">

                <label class="form-label fw-semibold">
                    <i class="fas fa-sort-amount-down text-danger"></i>
                    Order
                </label>

                <select 
                    id="sortOrder"
                    class="form-select rounded-pill shadow-sm"
                    onchange="loadProducts()">

                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>

                </select>

            </div>


        </div>

    </div>

</div>

    <!-- Products Table -->

    <div class="card shadow mt-4" id="products">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-boxes"></i>
                Products List
            </h5>

            <button class="btn btn-light btn-sm" onclick="loadProducts()">
                <i class="fas fa-sync"></i>
                Refresh
            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th width="50">ID</th>
                            <th>Product Info</th>
                            <th>SKU & Barcode</th>
                            <th>Price</th>
                            <th width="160">Stock & Quick Adjust</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody id="productsTable">

                        <tr>

                            <td colspan="8" class="text-center py-5">

                                <div class="spinner-border text-primary"></div>

                                <p class="mt-2">
                                    Loading Products...
                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->

            <div class="d-flex justify-content-center mt-3">

                <ul class="pagination" id="pagination">

                </ul>

            </div>

        </div>

    </div>

    <!-- Quick Actions -->

    <div class="row mt-4">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-bolt"></i>

                        Quick Actions

                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-grid gap-2">

                        <a href="#create" class="btn btn-success">

                            <i class="fas fa-plus-circle"></i>

                            Create Product

                        </a>

                        <button class="btn btn-info text-white" onclick="viewAPIResponse()">

                            <i class="fas fa-code"></i>

                            API Response

                        </button>

                        <button class="btn btn-warning" onclick="loadSeedData()">

                            <i class="fas fa-database"></i>

                            Load Sample Data

                        </button>

                        <button class="btn btn-primary" onclick="loadStats()">

                            <i class="fas fa-chart-bar"></i>

                            Refresh Dashboard

                        </button>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-info text-white">

                    <h5 class="mb-0">

                        Validation Rules

                    </h5>

                </div>

                <div class="card-body">

                    <ul class="list-group">

                        <li class="list-group-item">

                            ✔ SKU must start with <strong>PROD-</strong>

                        </li>

                        <li class="list-group-item">

                            ✔ Price must be greater than 0

                        </li>

                        <li class="list-group-item">

                            ✔ Stock cannot be negative

                        </li>

                        <li class="list-group-item">

                            ✔ Expiry date must be a future date

                        </li>

                        <li class="list-group-item">

                            ✔ Category must be valid

                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

    <!-- Product Details Modal -->

    <div class="modal fade" id="productModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Product Details

                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div id="productDetailContent">

                        Loading...

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- API Response Modal -->

    <div class="modal fade" id="apiResponseModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        API Response

                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <pre id="apiResponseContent" class="bg-dark text-white p-3 rounded"
                        style="height:400px;overflow:auto;"></pre>

                </div>

            </div>

        </div>

    <!-- 📷 BARCODE & QR CAMERA SCANNER MODAL -->
    <div class="modal fade" id="barcodeScannerModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-camera text-warning me-2"></i> Live Barcode / QR Scanner
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="stopBarcodeScanner()"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <p class="text-muted small mb-2">Align the product SKU Barcode or QR Code within the camera view.</p>
                    <div id="interactive-scanner" style="width: 100%; min-height: 250px; background: #000; border-radius: 8px; overflow: hidden;"></div>
                    <div class="mt-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                            <input type="text" id="manualScannerInput" class="form-control" placeholder="Or type/paste scanned SKU...">
                            <button class="btn btn-primary" onclick="applyScannedCode(document.getElementById('manualScannerInput').value)">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🏷️ SINGLE PRODUCT LABEL PREVIEW MODAL -->
    <div class="modal fade" id="productLabelModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-barcode me-2"></i> Product Sticker Label
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4" id="singleLabelPrintArea">
                    <div class="p-3 border rounded shadow-sm bg-white text-center">
                        <span class="badge bg-dark mb-2 text-uppercase" id="lblCategory"></span>
                        <h5 class="fw-bold mb-1" id="lblName"></h5>
                        <h4 class="text-primary fw-bold mb-3" id="lblPrice"></h4>
                        <div class="d-flex justify-content-center my-2">
                            <svg id="lblBarcodeSvg"></svg>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top small text-muted">
                            <div id="lblQrCode"></div>
                            <div class="text-end">
                                <div><strong>SKU:</strong> <span id="lblSku"></span></div>
                                <div><strong>Stock:</strong> <span id="lblStock"></span></div>
                                <div><strong>Expiry:</strong> <span id="lblExpiry"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="printSingleLabel()">
                        <i class="fas fa-print me-1"></i> Print Sticker
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.create-product')
    @include('partials.bulk-create')
    @include('partials.validation-test')

@endsection

    @section('scripts')
    <script>
        let debounceTimer;

        let categoryChartInstance = null;
        let priceTierChartInstance = null;
        let html5QrScanner = null;
        let activeScannerTargetInput = 'sku';

        /*
        |--------------------------------------------------------------------------
        | Page Load
        |--------------------------------------------------------------------------
        */

        document.addEventListener("DOMContentLoaded", function() {
            loadProducts();
            loadStats();
            loadAnalytics();
        });

        /*
        |--------------------------------------------------------------------------
        | 📊 Visual Analytics & Chart.js Loader
        |--------------------------------------------------------------------------
        */
        async function loadAnalytics() {
            try {
                const response = await axios.get(`${API_BASE}/products/analytics`);
                const data = response.data.data;

                // 1. Category Share Donut Chart
                const catLabels = data.categories.map(c => c.category.toUpperCase());
                const catCounts = data.categories.map(c => c.count);
                const ctxCat = document.getElementById('categoryDonutChart').getContext('2d');

                if (categoryChartInstance) {
                    categoryChartInstance.destroy();
                }

                categoryChartInstance = new Chart(ctxCat, {
                    type: 'doughnut',
                    data: {
                        labels: catLabels.length ? catLabels : ['Empty'],
                        datasets: [{
                            data: catCounts.length ? catCounts : [1],
                            backgroundColor: ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                            borderWidth: 2,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                        },
                        cutout: '65%'
                    }
                });

                // 2. Price Tier Distribution Histogram
                const tierLabels = Object.keys(data.price_tiers);
                const tierValues = Object.values(data.price_tiers);
                const ctxTier = document.getElementById('priceTierChart').getContext('2d');

                if (priceTierChartInstance) {
                    priceTierChartInstance.destroy();
                }

                priceTierChartInstance = new Chart(ctxTier, {
                    type: 'bar',
                    data: {
                        labels: tierLabels,
                        datasets: [{
                            label: 'Product Count',
                            data: tierValues,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            maxBarThickness: 35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { ticks: { font: { size: 10 } } }
                        }
                    }
                });

                // 3. Top Valuable Assets List
                const assetsContainer = document.getElementById('topAssetsContainer');
                if (data.top_assets && data.top_assets.length > 0) {
                    let assetsHtml = '<ul class="list-group list-group-flush">';
                    data.top_assets.forEach((item, idx) => {
                        const medal = idx === 0 ? '🥇' : (idx === 1 ? '🥈' : (idx === 2 ? '🥉' : `#${idx+1}`));
                        assetsHtml += `
                        <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-2 border-0">
                            <div>
                                <span class="me-2 fw-bold">${medal}</span>
                                <strong class="text-dark">${item.name}</strong>
                                <span class="badge bg-light text-muted ms-1 font-monospace small">${item.sku}</span>
                                <div class="small text-muted">${item.stock} in stock × $${parseFloat(item.price).toFixed(2)}</div>
                            </div>
                            <span class="badge bg-success-subtle text-success fw-bold fs-6">
                                $${parseFloat(item.inventory_value).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </span>
                        </li>`;
                    });
                    assetsHtml += '</ul>';
                    assetsContainer.innerHTML = assetsHtml;
                } else {
                    assetsContainer.innerHTML = '<div class="text-center py-4 text-muted small">No assets with active stock.</div>';
                }

                // 4. Stock Depletion Progress Bars
                const totalDep = (data.depletion.critical + data.depletion.low + data.depletion.healthy) || 1;
                const critPct = Math.round((data.depletion.critical / totalDep) * 100);
                const lowPct = Math.round((data.depletion.low / totalDep) * 100);
                const healthPct = Math.round((data.depletion.healthy / totalDep) * 100);

                document.getElementById('criticalStockCount').innerText = `${data.depletion.critical} items`;
                document.getElementById('criticalStockBar').style.width = `${critPct}%`;

                document.getElementById('lowStockCount').innerText = `${data.depletion.low} items`;
                document.getElementById('lowStockBar').style.width = `${lowPct}%`;

                document.getElementById('healthyStockCount').innerText = `${data.depletion.healthy} items`;
                document.getElementById('healthyStockBar').style.width = `${healthPct}%`;

                // 5. Expiry Countdown List
                document.getElementById('expiredBadge').innerText = `${data.expiry.expired_count} Expired`;
                const expContainer = document.getElementById('expiringSoonList');
                if (data.expiry.expiring_soon && data.expiry.expiring_soon.length > 0) {
                    let expHtml = '<div class="list-group list-group-flush">';
                    data.expiry.expiring_soon.forEach(item => {
                        const expDate = new Date(item.expiry_date);
                        const daysLeft = Math.ceil((expDate - new Date()) / (1000 * 60 * 60 * 24));
                        expHtml += `
                        <div class="list-group-item d-flex justify-content-between align-items-center px-2 py-2 border-bottom">
                            <div>
                                <strong class="text-dark small">${item.name}</strong>
                                <span class="badge bg-dark font-monospace ms-1 small" style="font-size:0.7rem;">${item.sku}</span>
                                <div class="small text-muted">Expires: ${expDate.toLocaleDateString()}</div>
                            </div>
                            <span class="badge ${daysLeft <= 7 ? 'bg-danger' : 'bg-warning text-dark'}">
                                ${daysLeft <= 0 ? 'Expired Today' : `${daysLeft} days left`}
                            </span>
                        </div>`;
                    });
                    expHtml += '</div>';
                    expContainer.innerHTML = expHtml;
                } else {
                    expContainer.innerHTML = '<div class="text-muted small text-center py-3">No batches expiring in the next 30 days.</div>';
                }

            } catch (err) {
                console.error("Analytics load error:", err);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ⚡ Quick Stock Adjustment (+ / -)
        |--------------------------------------------------------------------------
        */
        async function adjustQuickStock(productId, delta) {
            try {
                const response = await axios.patch(`${API_BASE}/products/${productId}/quick-stock`, {
                    adjustment: delta
                });

                const updated = response.data.data;
                const badgeEl = document.getElementById(`stock-val-${productId}`);
                if (badgeEl) {
                    badgeEl.innerText = updated.stock;
                    badgeEl.className = `badge ${updated.stock <= 0 ? 'bg-danger' : (updated.stock < 10 ? 'bg-warning text-dark' : 'bg-success')} px-2 py-1 fs-6`;
                }

                showSuccess(`Stock adjusted: ${updated.name} (${updated.stock} units)`);
                loadStats();
                loadAnalytics();
            } catch (error) {
                console.error(error);
                const errMsg = error.response?.data?.message || "Failed to adjust stock";
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Alert',
                    text: errMsg
                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 📷 Barcode & QR Camera Scanner Engine
        |--------------------------------------------------------------------------
        */
        function openBarcodeScanner(target = 'search') {
            activeScannerTargetInput = target;
            document.getElementById('manualScannerInput').value = '';
            const modal = new bootstrap.Modal(document.getElementById('barcodeScannerModal'));
            modal.show();

            setTimeout(() => {
                startCameraScanner();
            }, 400);
        }

        function startCameraScanner() {
            if (html5QrScanner) {
                try { html5QrScanner.clear(); } catch(e){}
            }

            html5QrScanner = new Html5Qrcode("interactive-scanner");
            const config = { fps: 10, qrbox: { width: 250, height: 180 } };

            html5QrScanner.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    applyScannedCode(decodedText);
                },
                (error) => {
                    // Scanning frame pass
                }
            ).catch(err => {
                console.warn("Camera access failed or unavailable, fallback to manual input:", err);
                document.getElementById('interactive-scanner').innerHTML = `
                    <div class="p-4 text-white text-center">
                        <i class="fas fa-video-slash fa-2x mb-2 text-warning"></i>
                        <p class="small mb-0">Camera permission denied or not available.<br>Please use the manual input or hardware scanner below.</p>
                    </div>`;
            });
        }

        function stopBarcodeScanner() {
            if (html5QrScanner) {
                try {
                    html5QrScanner.stop().then(() => {
                        html5QrScanner.clear();
                    }).catch(e => {});
                } catch(e){}
            }
        }

        function applyScannedCode(code) {
            if (!code || !code.trim()) return;
            const cleanCode = code.trim();
            stopBarcodeScanner();
            const modalEl = document.getElementById('barcodeScannerModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (activeScannerTargetInput === 'search') {
                const searchEl = document.getElementById('searchInput');
                searchEl.value = cleanCode;
                debouncedSearch();
                showSuccess(`Scanned SKU: ${cleanCode}`);
            } else if (activeScannerTargetInput === 'sku') {
                const skuEl = document.getElementById('sku');
                if (skuEl) {
                    skuEl.value = cleanCode;
                    validateLiveSKU(cleanCode);
                    showSuccess(`Applied SKU: ${cleanCode}`);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 🏷️ Single Sticker Label Generator Modal
        |--------------------------------------------------------------------------
        */
        let currentLabelQr = null;

        function openSingleLabelModal(product) {
            document.getElementById('lblCategory').innerText = product.category;
            document.getElementById('lblName').innerText = product.name;
            document.getElementById('lblPrice').innerText = `$${parseFloat(product.price).toFixed(2)}`;
            document.getElementById('lblSku').innerText = product.sku;
            document.getElementById('lblStock').innerText = product.stock;
            document.getElementById('lblExpiry').innerText = product.expiry_date ? new Date(product.expiry_date).toLocaleDateString() : 'N/A';

            // Generate Barcode SVG
            try {
                JsBarcode("#lblBarcodeSvg", product.sku, {
                    format: "CODE128",
                    width: 1.8,
                    height: 52,
                    displayValue: true,
                    fontSize: 14,
                    margin: 4
                });
            } catch(e){
                console.error(e);
            }

            // Generate QR Code
            const qrBox = document.getElementById('lblQrCode');
            qrBox.innerHTML = '';
            new QRCode(qrBox, {
                text: JSON.stringify({ id: product.id, name: product.name, sku: product.sku, price: product.price }),
                width: 64,
                height: 64,
                correctLevel: QRCode.CorrectLevel.M
            });

            const modal = new bootstrap.Modal(document.getElementById('productLabelModal'));
            modal.show();
        }

        function printSingleLabel() {
            const printContent = document.getElementById('singleLabelPrintArea').innerHTML;
            const printWindow = window.open('', '_blank', 'width=600,height=500');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Product Sticker Label</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; text-align: center; background: white; }
                        @media print { body { padding: 0; } }
                    </style>
                </head>
                <body>
                    <div style="max-width: 320px; margin: 0 auto;">
                        ${printContent}
                    </div>
                    <script>
                        window.onload = function() { window.print(); window.close(); }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        /*
        |--------------------------------------------------------------------------
        | ⚡ Helper: Auto-Generate Valid SKU & Live Validation
        |--------------------------------------------------------------------------
        */
        function generateRandomSKU() {
            const randomDigits = Math.floor(100000 + Math.random() * 900000);
            const generated = `PROD-${randomDigits}`;
            const skuEl = document.getElementById('sku');
            if (skuEl) {
                skuEl.value = generated;
                validateLiveSKU(generated);
            }
        }

        function validateLiveSKU(val) {
            const pattern = /^PROD-\d{6}$/;
            const badge = document.getElementById('skuValidationBadge');
            const previewBox = document.getElementById('liveBarcodePreview');
            const svg = document.getElementById('create-barcode-preview');

            if (pattern.test(val)) {
                if (badge) badge.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
                if (previewBox && svg) {
                    previewBox.style.display = 'block';
                    try {
                        JsBarcode(svg, val, {
                            format: "CODE128",
                            width: 1.4,
                            height: 38,
                            displayValue: true,
                            fontSize: 12
                        });
                    } catch(e){}
                }
            } else {
                if (badge) badge.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
                if (previewBox) previewBox.style.display = 'none';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        function debouncedSearch() {

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {

                loadProducts();

            }, 500);

        }


        /*
        |--------------------------------------------------------------------------
        | Load Products
        |--------------------------------------------------------------------------
        */

        async function loadProducts(page = 1) {

            try {

                const search = document.getElementById("searchInput").value;

                const category = document.getElementById("categoryFilter").value;

                const status = document.getElementById("statusFilter").value;

                const sortBy = document.getElementById("sortSelect").value;

                const sortOrder = document.getElementById("sortOrder").value;

                let url = `${API_BASE}/products?page=${page}&per_page=5`;

                if (search !== "") {

                    url += `&search=${encodeURIComponent(search)}`;

                }

                if (category !== "") {

                    url += `&category=${category}`;

                }

                if (status !== "") {
                    url += `&status=${status}`;
                }

                url += `&sort_by=${sortBy}`;

                url += `&sort_order=${sortOrder}`;

                const response = await axios.get(url);

                const products = response.data.data;

                const tableBody = document.getElementById("productsTable");

                tableBody.innerHTML = "";

                if (products.data.length === 0) {

                    tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        No Products Found
                    </td>
                </tr>
            `;

                } else {

                    products.data.forEach(product => {
                        const stockBadgeClass = product.stock <= 0 ? 'bg-danger' : (product.stock < 10 ? 'bg-warning text-dark' : 'bg-success');
                        const statusBadgeClass = product.status === 'active' ? 'bg-success' : (product.status === 'inactive' ? 'bg-warning text-dark' : (product.status === 'draft' ? 'bg-secondary' : 'bg-danger'));

                        tableBody.innerHTML += `
                        <tr>
                            <td class="text-muted fw-bold">${product.id}</td>
                            <td>
                                <strong class="d-block text-dark">${product.name}</strong>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i> ${product.expiry_date ? new Date(product.expiry_date).toLocaleDateString() : 'No Expiry'}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-dark mb-1 font-monospace">${product.sku}</span>
                                <div class="barcode-container mt-1">
                                    <svg class="tbl-barcode-svg" id="tbl-barcode-${product.id}" data-sku="${product.sku}"></svg>
                                </div>
                            </td>
                            <td class="fw-bold text-primary">
                                $${parseFloat(product.price).toFixed(2)}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <button class="btn btn-outline-danger btn-sm px-2 py-0 fw-bold" title="Decrease Stock" onclick="adjustQuickStock(${product.id}, -1)">-</button>
                                    <span class="badge ${stockBadgeClass} px-2 py-1 fs-6" id="stock-val-${product.id}">${product.stock}</span>
                                    <button class="btn btn-outline-success btn-sm px-2 py-0 fw-bold" title="Increase Stock" onclick="adjustQuickStock(${product.id}, 1)">+</button>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary text-uppercase">${product.category}</span>
                            </td>
                            <td>
                                <span class="badge ${statusBadgeClass} text-uppercase">${product.status}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-dark" title="Print Sticker Label" onclick='openSingleLabelModal(${JSON.stringify(product)})'>
                                        <i class="fas fa-barcode"></i>
                                    </button>
                                    <button class="btn btn-outline-info" title="View Details" onclick="viewProduct(${product.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Edit" onclick="editProduct(${product.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteProduct(${product.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        `;
                    });

                    // Render CODE128 on table barcodes
                    setTimeout(() => {
                        document.querySelectorAll('.tbl-barcode-svg').forEach(el => {
                            const sku = el.getAttribute('data-sku');
                            if (sku) {
                                try {
                                    JsBarcode(el, sku, {
                                        format: "CODE128",
                                        width: 1.2,
                                        height: 28,
                                        displayValue: false,
                                        margin: 0
                                    });
                                } catch(e){}
                            }
                        });
                    }, 50);

                }

                updatePagination(products);

            } catch (error) {

                console.log(error);

                showError("Unable to load products.");

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        function updatePagination(products) {

            const pagination = document.getElementById("pagination");

            pagination.innerHTML = "";

            if (products.prev_page_url) {

                pagination.innerHTML += `

        <li class="page-item">

            <button
                class="page-link"
                onclick="loadProducts(${products.current_page-1})">

                Previous

            </button>

        </li>

        `;

            }

            for (let i = 1; i <= products.last_page; i++) {

                pagination.innerHTML += `

        <li class="page-item ${i==products.current_page?'active':''}">

            <button
                class="page-link"
                onclick="loadProducts(${i})">

                ${i}

            </button>

        </li>

        `;

            }

            if (products.next_page_url) {

                pagination.innerHTML += `

        <li class="page-item">

            <button
                class="page-link"
                onclick="loadProducts(${products.current_page+1})">

                Next

            </button>

        </li>

        `;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        async function loadStats() {

            try {

                const response = await axios.get(`${API_BASE}/products/statistics`);

                const stats = response.data.data;

                document.getElementById("totalProducts").innerText =
                    stats.total_products;

                document.getElementById("activeProducts").innerText =
                    stats.available_products;

                document.getElementById("totalStock").innerText =
                    stats.total_stock;

                document.getElementById("categories").innerText =
                    stats.categories;

            } catch (error) {

                console.error(error);

                showError("Unable to load dashboard statistics.");

            }

        }

        /*
        |--------------------------------------------------------------------------
        | View Product Details
        |--------------------------------------------------------------------------
        */

        async function viewProduct(id) {

            try {

                const response = await axios.get(`${API_BASE}/products/${id}`);

                const product = response.data.data;

                const expiry = product.expiry_date ?
                    new Date(product.expiry_date).toLocaleDateString() :
                    "N/A";

                const created = new Date(product.created_at).toLocaleString();

                const updated = new Date(product.updated_at).toLocaleString();

                const html = `

            <div class="row">

                <div class="col-md-4">

                    <div class="card border-primary">

                        <div class="card-body text-center">

                            <i class="fas fa-box fa-5x text-primary mb-3"></i>

                            <h4>${product.name}</h4>

                            <span class="badge bg-info fs-6">
                                ${product.sku}
                            </span>

                        </div>

                    </div>

                </div>

                <div class="col-md-8">

                    <table class="table table-bordered">

                        <tr>
                            <th width="35%">Product Name</th>
                            <td>${product.name}</td>
                        </tr>

                        <tr>
                            <th>SKU</th>
                            <td>${product.sku}</td>
                        </tr>

                        <tr>
                            <th>Price</th>
                            <td>$${parseFloat(product.price).toFixed(2)}</td>
                        </tr>

                        <tr>
                            <th>Stock</th>
                            <td>

                                <span class="badge ${product.stock > 0 ? 'bg-success' : 'bg-danger'}">

                                    ${product.stock}

                                </span>

                            </td>
                        </tr>

                        <tr>
                            <th>Category</th>
                            <td>

                                <span class="badge bg-primary">

                                    ${product.category}

                                </span>

                            </td>
                        </tr>

                        <tr>
    <th>Status</th>

    <td>

        <span class="badge
            ${
                product.status === 'active'
                    ? 'bg-success'
                    : product.status === 'inactive'
                    ? 'bg-warning text-dark'
                    : product.status === 'draft'
                    ? 'bg-secondary'
                    : 'bg-danger'
            }">

            ${product.status}

        </span>

    </td>
</tr>

                        <tr>
                            <th>Expiry Date</th>
                            <td>${expiry}</td>
                        </tr>

                        <tr>
                            <th>Description</th>
                            <td>${product.description ?? 'No Description'}</td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td>${created}</td>
                        </tr>

                        <tr>
                            <th>Updated At</th>
                            <td>${updated}</td>
                        </tr>

                    </table>

                </div>

            </div>

        `;

                document.getElementById("productDetailContent").innerHTML = html;

                const modal = new bootstrap.Modal(
                    document.getElementById("productModal")
                );

                modal.show();

            } catch (error) {

                console.error(error);

                showError("Unable to load product details.");

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Product - SweetAlert2
        |--------------------------------------------------------------------------
        */

        async function deleteProduct(id) {

            const result = await Swal.fire({

                title: 'Delete Product?',

                text: "You won't be able to recover it.",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#3085d6',

                confirmButtonText: 'Yes, Delete',

                cancelButtonText: 'Cancel'

            });

            if (!result.isConfirmed) {

                return;

            }

            try {

                await axios.delete(`${API_BASE}/products/${id}`);

                Swal.fire({

                    icon: 'success',

                    title: 'Deleted!',

                    text: 'Product deleted successfully.',

                    timer: 1500,

                    showConfirmButton: false

                });

                loadProducts();

                loadStats();

            } catch (error) {

                console.error(error);

                Swal.fire({

                    icon: 'error',

                    title: 'Oops!',

                    text: 'Unable to delete product.'

                });

            }

        }

        /*
        |--------------------------------------------------------------------------
        | 📦 Load Sample Seed Data
        |--------------------------------------------------------------------------
        */
        async function loadSeedData() {
            try {
                Swal.fire({
                    title: 'Seeding Sample Data...',
                    text: 'Please wait while sample products are being generated.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                await axios.get('/seed-products');

                Swal.fire({
                    icon: 'success',
                    title: 'Seeded Successfully! 🎉',
                    text: '14 high-quality sample products added across all categories.',
                    timer: 2000,
                    showConfirmButton: false
                });

                loadProducts();
                loadStats();
                loadAnalytics();
            } catch (err) {
                console.error("Seeding error:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Failed to populate sample data.'
                });
            }
        }

        function showSuccess(message) {

            Swal.fire({

                toast: true,

                position: 'top-end',

                icon: 'success',

                title: message,

                showConfirmButton: false,

                timer: 2000

            });

        }
    </script>

    @endsection