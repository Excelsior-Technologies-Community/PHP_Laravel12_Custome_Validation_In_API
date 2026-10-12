<div class="row mt-4" id="create">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Create New Product
                </h5>
            </div>
            <div class="card-body">
                <form id="createProductForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">
                                    Must be at least 3 characters and not just numbers
                                </div>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>

                            <div class="mb-3">
                                <label for="sku" class="form-label d-flex justify-content-between align-items-center">
                                    <span>SKU (Stock Keeping Unit) *</span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary py-0" onclick="generateRandomSKU()">
                                            <i class="fas fa-magic me-1"></i> Auto-Generate
                                        </button>
                                        <button type="button" class="btn btn-outline-dark py-0" onclick="openBarcodeScanner('sku')">
                                            <i class="fas fa-camera me-1"></i> Scan Barcode
                                        </button>
                                    </div>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="sku" name="sku" required placeholder="PROD-123456" oninput="validateLiveSKU(this.value)">
                                    <span class="input-group-text" id="skuValidationBadge">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                </div>
                                <div class="form-text">
                                    Format: <code>PROD-</code> followed by 6 digits (e.g., PROD-123456)
                                </div>
                                <div class="invalid-feedback" id="skuError"></div>
                                <div id="liveBarcodePreview" class="mt-2 text-center" style="display: none;">
                                    <svg id="create-barcode-preview"></svg>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price ($) *</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    step="0.01" min="0.01" max="1000000" required>
                                <div class="invalid-feedback" id="priceError"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock Quantity *</label>
                                <input type="number" class="form-control" id="stock" name="stock"
                                    min="0" max="10000" required>
                                <div class="invalid-feedback" id="stockError"></div>
                            </div>

                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                <div class="form-text">
                                    Must be at least 30 days from today and not more than 5 years
                                </div>
                                <div class="invalid-feedback" id="expiry_dateError"></div>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="clothing">Clothing</option>
                                    <option value="books">Books</option>
                                    <option value="home">Home</option>
                                    <option value="sports">Sports</option>
                                    <option value="beauty">Beauty</option>
                                </select>
                                <div class="invalid-feedback" id="categoryError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                            <option value="discontinued">Discontinued</option>
                        </select>
                        <div class="invalid-feedback" id="statusError"></div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="form-text">Maximum 1000 characters</div>
                        <div class="invalid-feedback" id="descriptionError"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-save"></i> Create Product
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="fillSampleData()">
                            <i class="fas fa-magic"></i> Fill Sample Data
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <div id="formResponse"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('createProductForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        clearErrors();

        const formData = {
            name: document.getElementById('name').value,
            sku: document.getElementById('sku').value,
            price: parseFloat(document.getElementById('price').value),
            stock: parseInt(document.getElementById('stock').value),
            expiry_date: document.getElementById('expiry_date').value || null,
            category: document.getElementById('category').value,
            status: document.getElementById('status').value,
            description: document.getElementById('description').value || null
        };

        try {
            const response = await axios.post(`${API_BASE}/products`, formData);

            // Show SweetAlert success
            Swal.fire({
                icon: 'success',
                title: 'Product Created! 🎉',
                text: `${response.data.data.name} has been added successfully.`,
                timer: 2000,
                showConfirmButton: false
            });

            // Show success message container
            document.getElementById('formResponse').innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <strong>Success!</strong> Product created successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <pre class="mt-2 mb-0 bg-white p-2 rounded border small">${formatJSON(response.data)}</pre>
                </div>
            `;

            // Reset form
            resetForm();

            // Refresh data tables & charts
            if (typeof loadProducts === 'function') loadProducts();
            if (typeof loadStats === 'function') loadStats();
            if (typeof loadAnalytics === 'function') loadAnalytics();

        } catch (error) {
            console.error("Create product error:", error);
            if (error.response && error.response.status === 422) {
                // Validation errors
                const errors = error.response.data.errors;
                displayErrors(errors);

                const errorMessages = Object.values(errors).flat().join('<br>');
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Failed',
                    html: `<div class="text-start">${errorMessages}</div>`
                });

                document.getElementById('formResponse').innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Validation Errors!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <pre class="mt-2 mb-0 bg-white p-2 rounded border small text-danger">${formatJSON(error.response.data)}</pre>
                    </div>
                `;
            } else {
                const genericMsg = error.response?.data?.message || 'Failed to create product';
                showError(genericMsg);
            }
        }
    });

    function displayErrors(errors) {
        for (const field in errors) {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById(field + 'Error');

            if (input && errorDiv) {
                input.classList.add('is-invalid');
                errorDiv.textContent = errors[field][0];
            }
        }
    }

    function clearErrors() {
        const form = document.getElementById('createProductForm');
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });

        const errorDivs = form.querySelectorAll('.invalid-feedback');
        errorDivs.forEach(div => {
            div.textContent = '';
        });
    }

    function resetForm() {
        document.getElementById('createProductForm').reset();
        document.getElementById('formResponse').innerHTML = '';
        const preview = document.getElementById('liveBarcodePreview');
        if (preview) preview.style.display = 'none';
        const badge = document.getElementById('skuValidationBadge');
        if (badge) badge.innerHTML = '<i class="fas fa-barcode"></i>';
        clearErrors();
    }

    function fillSampleData() {
        const randomNum = Math.floor(100000 + Math.random() * 900000);
        document.getElementById('name').value = 'Smart Watch Pro Ultra';
        document.getElementById('sku').value = `PROD-${randomNum}`;
        document.getElementById('price').value = '299.99';
        document.getElementById('stock').value = '75';

        // Set expiry date to 1 year from now (guaranteed valid: > 30 days and < 5 years)
        const nextYear = new Date();
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        document.getElementById('expiry_date').value = nextYear.toISOString().split('T')[0];

        document.getElementById('category').value = 'electronics';
        document.getElementById('status').value = 'active';
        document.getElementById('description').value = 'Advanced smartwatch with heart rate sensor, AMOLED display, and GPS tracking.';

        validateLiveSKU(`PROD-${randomNum}`);
        clearErrors();
    }
</script>