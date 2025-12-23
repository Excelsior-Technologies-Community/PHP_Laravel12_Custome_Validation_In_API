@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadow">
                <div class="card-body">
                    <h1 class="card-title">
                        <i class="fas fa-api text-primary"></i> Laravel Custom Validation API
                    </h1>
                    <p class="lead">
                        Demonstration of custom validation rules in Laravel 12 API with interactive interface
                    </p>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body text-center">
                                    <h1 class="display-4" id="totalProducts">0</h1>
                                    <p>Total Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body text-center">
                                    <h1 class="display-4" id="activeProducts">0</h1>
                                    <p>Active Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body text-center">
                                    <h1 class="display-4" id="totalStock">0</h1>
                                    <p>Total Stock</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body text-center">
                                    <h1 class="display-4" id="categories">0</h1>
                                    <p>Categories</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Products Section -->
        <div class="col-md-8" id="products">
            <div class="card card-shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes"></i> Products List
                    </h5>
                    <button class="btn btn-light btn-sm" onclick="loadProducts()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search products..."
                                onkeyup="debouncedSearch()">
                        </div>
                        <div class="col-md-3">
                            <select id="categoryFilter" class="form-control" onchange="loadProducts()">
                                <option value="">All Categories</option>
                                <option value="electronics">Electronics</option>
                                <option value="clothing">Clothing</option>
                                <option value="books">Books</option>
                                <option value="home">Home</option>
                                <option value="sports">Sports</option>
                                <option value="beauty">Beauty</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="sortSelect" class="form-control" onchange="loadProducts()">
                                <option value="created_at">Sort by Date</option>
                                <option value="name">Sort by Name</option>
                                <option value="price">Sort by Price</option>
                                <option value="stock">Sort by Stock</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="sortOrder" class="form-control" onchange="loadProducts()">
                                <option value="desc">Descending</option>
                                <option value="asc">Ascending</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTable">
                                <!-- Products will be loaded here -->
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Pagination will be loaded here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card card-shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#create" class="btn btn-gradient btn-lg">
                            <i class="fas fa-plus-circle"></i> Create New Product
                        </a>
                        <button class="btn btn-outline-primary btn-lg" onclick="testValidation()">
                            <i class="fas fa-vial"></i> Test Validation
                        </button>
                        <button class="btn btn-outline-info btn-lg" onclick="viewAPIResponse()">
                            <i class="fas fa-code"></i> View API Response
                        </button>
                        <button class="btn btn-outline-warning btn-lg" onclick="loadSeedData()">
                            <i class="fas fa-seedling"></i> Load Sample Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Validation Rules Summary -->
            <div class="card card-shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check"></i> Active Validation Rules
                    </h5>
                </div>
                <div class="card-body">
                    <div class="validation-rule">
                        <strong>SKU Format:</strong> Must start with "PROD-" followed by 6 digits
                    </div>
                    <div class="validation-rule">
                        <strong>Expiry Date:</strong> Must be 30 days to 5 years from today
                    </div>
                    <div class="validation-rule">
                        <strong>Category:</strong> Must be one of: electronics, clothing, books, home, sports, beauty
                    </div>
                    <div class="validation-rule">
                        <strong>Product Name:</strong> Min 3 chars, not just numbers
                    </div>
                    <div class="validation-rule">
                        <strong>Stock Rules:</strong> Limited stock for products expiring within 6 months
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="productDetailContent">
                        Loading...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- API Response Modal -->
    <div class="modal fade" id="apiResponseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">API Response</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <pre class="api-response p-3" id="apiResponseContent"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadProducts();
            loadStats();
        });

        function debouncedSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                loadProducts();
            }, 500);
        }

        async function loadProducts(page = 1) {
            try {
                const search = document.getElementById('searchInput').value;
                const category = document.getElementById('categoryFilter').value;
                const sortBy = document.getElementById('sortSelect').value;
                const sortOrder = document.getElementById('sortOrder').value;

                let url = `${API_BASE}/products?page=${page}&per_page=5`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (category) url += `&category=${category}`;
                if (sortBy) url += `&sort_by=${sortBy}&sort_order=${sortOrder}`;

                const response = await axios.get(url);
                const products = response.data.data;

                // Update table
                const tableBody = document.getElementById('productsTable');
                tableBody.innerHTML = '';

                if (products.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                No products found
                            </td>
                        </tr>
                    `;
                } else {
                    products.data.forEach(product => {
                        const row = `
                            <tr>
                                <td>${product.id}</td>
                                <td><strong>${product.name}</strong></td>
                                <td><span class="badge bg-info">${product.sku}</span></td>
                                <td>$${parseFloat(product.price).toFixed(2)}</td>
                                <td>
                                    <span class="badge ${product.stock > 50 ? 'bg-success' : 'bg-warning'}">
                                        ${product.stock}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">${product.category}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewProduct(${product.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="editProduct(${product.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${product.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                }

                // Update pagination
                updatePagination(products);

            } catch (error) {
                console.error('Error loading products:', error);
                showError('Failed to load products');
            }
        }

        function updatePagination(products) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const currentPage = products.current_page;
            const lastPage = products.last_page;

            // Previous button
            if (products.prev_page_url) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <button class="page-link" onclick="loadProducts(${currentPage - 1})">Previous</button>
                    </li>
                `;
            }

            // Page numbers
            for (let i = 1; i <= lastPage; i++) {
                pagination.innerHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <button class="page-link" onclick="loadProducts(${i})">${i}</button>
                    </li>
                `;
            }

            // Next button
            if (products.next_page_url) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <button class="page-link" onclick="loadProducts(${currentPage + 1})">Next</button>
                    </li>
                `;
            }
        }

        async function loadStats() {
            try {
                const response = await axios.get(`${API_BASE}/products?per_page=100`);
                const products = response.data.data.data;

                // Calculate stats
                const totalProducts = products.length;
                const activeProducts = products.filter(p => p.stock > 0).length;
                const totalStock = products.reduce((sum, p) => sum + p.stock, 0);
                const categories = new Set(products.map(p => p.category)).size;

                // Update UI
                document.getElementById('totalProducts').textContent = totalProducts;
                document.getElementById('activeProducts').textContent = activeProducts;
                document.getElementById('totalStock').textContent = totalStock;
                document.getElementById('categories').textContent = categories;

            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function viewProduct(id) {
            try {
                const response = await axios.get(`${API_BASE}/products/${id}`);
                const product = response.data.data;

                const content = `
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">${product.sku}</h6>
                                    <p class="card-text">${product.description || 'No description'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Price</th>
                                    <td>$${parseFloat(product.price).toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <th>Stock</th>
                                    <td>
                                        <span class="badge ${product.stock > 50 ? 'bg-success' : 'bg-warning'}">
                                            ${product.stock} units
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td><span class="badge bg-primary">${product.category}</span></td>
                                </tr>
                                <tr>
                                    <th>Expiry Date</th>
                                    <td>
                                        ${product.expiry_date ?
                        new Date(product.expiry_date).toLocaleDateString() :
                        '<span class="text-muted">No expiry date</span>'}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td>${new Date(product.created_at).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>${new Date(product.updated_at).toLocaleString()}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;

                document.getElementById('productDetailContent').innerHTML = content;
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();

            } catch (error) {
                showError('Failed to load product details');
            }
        }

        async function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }

            try {
                await axios.delete(`${API_BASE}/products/${id}`);
                showSuccess('Product deleted successfully');
                loadProducts();
                loadStats();
            } catch (error) {
                showError('Failed to delete product');
            }
        }

        function editProduct(id) {
            // Scroll to create section with edit mode
            document.getElementById('create').scrollIntoView({ behavior: 'smooth' });
            // In a real implementation, you would load the product data into the form
            alert('Edit functionality would load product data into the form');
        }

        function testValidation() {
            document.getElementById('validation').scrollIntoView({ behavior: 'smooth' });
        }

        function viewAPIResponse() {
            document.getElementById('apiResponseContent').textContent = 'Loading API response...';
            const modal = new bootstrap.Modal(document.getElementById('apiResponseModal'));
            modal.show();

            // Get current products as example
            axios.get(`${API_BASE}/products?per_page=3`)
                .then(response => {
                    document.getElementById('apiResponseContent').textContent = formatJSON(response.data);
                })
                .catch(error => {
                    document.getElementById('apiResponseContent').textContent = formatJSON(error.response.data);
                });
        }

        async function loadSeedData() {
            try {
                const products = [
                    {
                        name: "Wireless Headphones",
                        sku: "PROD-200001",
                        price: 199.99,
                        stock: 45,
                        expiry_date: new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                        category: "electronics",
                        description: "Premium wireless headphones with noise cancellation"
                    },
                    {
                        name: "Yoga Mat",
                        sku: "PROD-200002",
                        price: 34.99,
                        stock: 120,
                        expiry_date: new Date(Date.now() + 730 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                        category: "sports",
                        description: "Non-slip yoga mat for all levels"
                    }
                ];

                for (const product of products) {
                    await axios.post(`${API_BASE}/products`, product);
                }

                showSuccess('Sample data loaded successfully');
                loadProducts();
                loadStats();

            } catch (error) {
                showError('Failed to load sample data');
            }
        }

        function showSuccess(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
            alert.style.zIndex = '1050';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
    </script>
@endsection