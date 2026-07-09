@extends('layouts.app')

@section('content')

<div class="container-fluid">

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

                        <button class="btn btn-primary" onclick="loadProducts()">
                            <i class="fas fa-rotate"></i>
                            Refresh
                        </button>

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

    <div class="card shadow mt-4">

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

                            <th width="60">ID</th>

                            <th>Name</th>

                            <th>SKU</th>

                            <th>Price</th>

                            <th>Stock</th>

                            <th>Category</th>

                            <th>Status</th>

                            <th width="170">Action</th>

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

    </div>

    @include('partials.create-product')
    @include('partials.bulk-create')
    @include('partials.validation-test')



    @endsection

    @section('scripts')
    <script>
        let debounceTimer;

        /*
        |--------------------------------------------------------------------------
        | Page Load
        |--------------------------------------------------------------------------
        */

        document.addEventListener("DOMContentLoaded", function() {

            loadProducts();

            loadStats();

        });


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

                        tableBody.innerHTML += `

                <tr>

                    <td>${product.id}</td>

                    <td>
                        <strong>${product.name}</strong>
                    </td>

                    <td>
                        <span class="badge bg-info">
                            ${product.sku}
                        </span>
                    </td>

                    <td>

                        $${parseFloat(product.price).toFixed(2)}

                    </td>

                    <td>

                        <span class="badge ${product.stock>0?'bg-success':'bg-danger'}">

                            ${product.stock}

                        </span>

                    </td>

                    <td>
                        <span class="badge bg-primary">
                            ${product.category}
                        </span>
                    </td>

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

                    <td>

                        <button
                            class="btn btn-sm btn-info"
                            onclick="viewProduct(${product.id})">

                            <i class="fas fa-eye"></i>

                        </button>

                        <button
                            class="btn btn-sm btn-warning"
                            onclick="editProduct(${product.id})">

                            <i class="fas fa-edit"></i>

                        </button>

                        <button
                            class="btn btn-sm btn-danger"
                            onclick="deleteProduct(${product.id})">

                            <i class="fas fa-trash"></i>

                        </button>

                    </td>

                </tr>

                `;

                    });

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