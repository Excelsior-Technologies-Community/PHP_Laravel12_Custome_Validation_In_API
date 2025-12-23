<div class="row mt-4" id="validation">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-vial"></i> Test Validation Rules
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Test individual validation rules with different inputs to see how they work.
                </p>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">SKU Validation Test</h6>
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control mb-2" id="testSku" 
                                       placeholder="Enter SKU (e.g., PROD-123456)">
                                <button class="btn btn-sm btn-info w-100" onclick="testSkuValidation()">
                                    Test SKU
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Expiry Date Test</h6>
                            </div>
                            <div class="card-body">
                                <input type="date" class="form-control mb-2" id="testExpiryDate">
                                <button class="btn btn-sm btn-success w-100" onclick="testExpiryValidation()">
                                    Test Expiry Date
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Category Test</h6>
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control mb-2" id="testCategory" 
                                       placeholder="Enter category">
                                <button class="btn btn-sm btn-primary w-100" onclick="testCategoryValidation()">
                                    Test Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">Test Scenarios</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-danger w-100 mb-2" 
                                                onclick="runTestScenario('invalidSku')">
                                            Invalid SKU
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-warning w-100 mb-2" 
                                                onclick="runTestScenario('nearExpiry')">
                                            Near Expiry
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-info w-100 mb-2" 
                                                onclick="runTestScenario('invalidCategory')">
                                            Wrong Category
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-success w-100 mb-2" 
                                                onclick="runTestScenario('validData')">
                                            All Valid
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Validation Results:</h6>
                    <div id="testResults" class="api-response p-3" style="min-height: 200px;">
                        Test results will appear here...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function testSkuValidation() {
        const sku = document.getElementById('testSku').value;
        
        try {
            const response = await axios.post(`${API_BASE}/validate-product`, {
                sku: sku,
                name: 'Test Product',
                stock: 10,
                category: 'electronics'
            });
            
            document.getElementById('testResults').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> SKU "${sku}" is valid!
                    <pre class="mt-2 mb-0">${formatJSON(response.data)}</pre>
                </div>
            `;
            
        } catch (error) {
            document.getElementById('testResults').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> SKU "${sku}" is invalid!
                    <pre class="mt-2 mb-0">${formatJSON(error.response?.data || error.message)}</pre>
                </div>
            `;
        }
    }
    
    async function testExpiryValidation() {
        const expiryDate = document.getElementById('testExpiryDate').value;
        
        try {
            const response = await axios.post(`${API_BASE}/products`, {
                name: 'Test Product',
                sku: 'PROD-999999',
                price: 99.99,
                stock: 50,
                expiry_date: expiryDate,
                category: 'electronics'
            });
            
            document.getElementById('testResults').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Expiry date "${expiryDate}" is valid!
                </div>
            `;
            
        } catch (error) {
            document.getElementById('testResults').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Expiry date validation failed!
                    <pre class="mt-2 mb-0">${formatJSON(error.response?.data || error.message)}</pre>
                </div>
            `;
        }
    }
    
    async function testCategoryValidation() {
        const category = document.getElementById('testCategory').value;
        
        try {
            const response = await axios.post(`${API_BASE}/validate-product`, {
                name: 'Test Product',
                sku: 'PROD-888888',
                stock: 10,
                category: category
            });
            
            document.getElementById('testResults').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Category "${category}" is valid!
                </div>
            `;
            
        } catch (error) {
            document.getElementById('testResults').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Category "${category}" is invalid!
                    <pre class="mt-2 mb-0">${formatJSON(error.response?.data || error.message)}</pre>
                </div>
            `;
        }
    }
    
    function runTestScenario(scenario) {
        const scenarios = {
            invalidSku: {
                name: 'Test Product',
                sku: 'INVALID-123',
                price: 50,
                stock: 100,
                expiry_date: new Date(Date.now() + 60 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                category: 'electronics'
            },
            nearExpiry: {
                name: 'Test Product',
                sku: 'PROD-123456',
                price: 50,
                stock: 1000, // High stock with near expiry
                expiry_date: new Date(Date.now() + 20 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                category: 'electronics'
            },
            invalidCategory: {
                name: 'Test Product',
                sku: 'PROD-123456',
                price: 50,
                stock: 100,
                category: 'invalid-category'
            },
            validData: {
                name: 'Premium Product',
                sku: 'PROD-654321',
                price: 199.99,
                stock: 50,
                expiry_date: new Date(Date.now() + 400 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                category: 'electronics',
                description: 'This is a valid product with all correct data'
            }
        };
        
        // Fill the form with scenario data
        const data = scenarios[scenario];
        document.getElementById('name').value = data.name;
        document.getElementById('sku').value = data.sku;
        document.getElementById('price').value = data.price;
        document.getElementById('stock').value = data.stock;
        document.getElementById('expiry_date').value = data.expiry_date;
        document.getElementById('category').value = data.category;
        document.getElementById('description').value = data.description || '';
        
        // Scroll to form
        document.getElementById('create').scrollIntoView({ behavior: 'smooth' });
        
        // Show message
        document.getElementById('formResponse').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Test scenario "${scenario}" loaded. Submit the form to test validation.
            </div>
        `;
        
        clearErrors();
    }
</script>