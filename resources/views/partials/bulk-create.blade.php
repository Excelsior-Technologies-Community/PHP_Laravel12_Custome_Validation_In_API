<div class="row mt-4" id="bulk">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group"></i> Bulk Create Products
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Create multiple products at once (up to 10 products per request).
                    All products will be validated with the same custom rules.
                </p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">JSON Input</label>
                            <textarea class="form-control" id="bulkJsonInput" rows="10">
{
  "products": [
    {
      "name": "Wireless Mouse",
      "sku": "PROD-400001",
      "price": 29.99,
      "category": "electronics"
    },
    {
      "name": "Notebook",
      "sku": "PROD-400002",
      "price": 12.99,
      "category": "books",
      "stock": 200,
      "description": "Premium quality notebook"
    }
  ]
}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Validation Rules for Bulk</label>
                            <div class="validation-rule">
                                <strong>Array Limit:</strong> 1-10 products per request
                            </div>
                            <div class="validation-rule">
                                <strong>Required Fields:</strong> name, sku, price, category
                            </div>
                            <div class="validation-rule">
                                <strong>SKU Validation:</strong> Custom SKU format applied to all
                            </div>
                            <div class="validation-rule">
                                <strong>Category Validation:</strong> Must be from allowed list
                            </div>
                        </div>
                        
                        <button class="btn btn-warning w-100 mb-2" onclick="validateBulkJson()">
                            <i class="fas fa-check-circle"></i> Validate JSON
                        </button>
                        
                        <button class="btn btn-gradient w-100" onclick="submitBulkCreate()">
                            <i class="fas fa-rocket"></i> Submit Bulk Create
                        </button>
                    </div>
                </div>
                
                <div id="bulkResponse" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    async function validateBulkJson() {
        const jsonInput = document.getElementById('bulkJsonInput').value;
        
        try {
            const data = JSON.parse(jsonInput);
            
            // Basic validation
            if (!data.products || !Array.isArray(data.products)) {
                throw new Error('JSON must contain "products" array');
            }
            
            if (data.products.length === 0 || data.products.length > 10) {
                throw new Error('Products array must contain 1-10 items');
            }
            
            document.getElementById('bulkResponse').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> JSON is valid!
                    <p class="mb-0 mt-2">Found ${data.products.length} products to create.</p>
                </div>
            `;
            
        } catch (error) {
            document.getElementById('bulkResponse').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Invalid JSON: ${error.message}
                </div>
            `;
        }
    }
    
    async function submitBulkCreate() {
        const jsonInput = document.getElementById('bulkJsonInput').value;
        
        try {
            const data = JSON.parse(jsonInput);
            
            document.getElementById('bulkResponse').innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-spinner fa-spin"></i> Creating products...
                </div>
            `;
            
            const response = await axios.post(`${API_BASE}/products/bulk`, data);
            
            document.getElementById('bulkResponse').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Bulk creation successful!
                    <details class="mt-2">
                        <summary>View Response</summary>
                        <pre class="mt-2 mb-0">${formatJSON(response.data)}</pre>
                    </details>
                </div>
            `;
            
            // Refresh data
            loadProducts();
            loadStats();
            
        } catch (error) {
            let errorMessage = 'Failed to create products';
            if (error.response && error.response.data) {
                errorMessage = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${error.response.data.message || 'Validation failed'}
                        <pre class="mt-2 mb-0">${formatJSON(error.response.data)}</pre>
                    </div>
                `;
            }
            
            document.getElementById('bulkResponse').innerHTML = errorMessage;
        }
    }
</script>