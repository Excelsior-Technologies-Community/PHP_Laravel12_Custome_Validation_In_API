@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-terminal"></i> API Testing Playground
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">API Endpoint</label>
                            <select class="form-control" id="endpointSelect" onchange="updateEndpoint()">
                                <option value="GET /api/products">GET /api/products</option>
                                <option value="POST /api/products">POST /api/products</option>
                                <option value="GET /api/products/{id}">GET /api/products/{id}</option>
                                <option value="PUT /api/products/{id}">PUT /api/products/{id}</option>
                                <option value="DELETE /api/products/{id}">DELETE /api/products/{id}</option>
                                <option value="POST /api/products/bulk">POST /api/products/bulk</option>
                                <option value="POST /api/validate-product">POST /api/validate-product</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Request Body (JSON)</label>
                            <textarea class="form-control" id="requestBody" rows="12">{
  "name": "Test Product",
  "sku": "PROD-123456",
  "price": 99.99,
  "stock": 50,
  "category": "electronics"
}</textarea>
                        </div>
                        
                        <button class="btn btn-gradient w-100" onclick="sendApiRequest()">
                            <i class="fas fa-paper-plane"></i> Send Request
                        </button>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Response</label>
                            <div class="api-response p-3" id="apiPlaygroundResponse" style="height: 400px;">
                                Response will appear here...
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showHeaders" checked>
                                    <label class="form-check-label" for="showHeaders">
                                        Show Headers
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-sm btn-outline-secondary" onclick="clearResponse()">
                                    Clear Response
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateEndpoint() {
        const endpoint = document.getElementById('endpointSelect').value;
        
        // Update request body based on endpoint
        switch(endpoint) {
            case 'GET /api/products':
                document.getElementById('requestBody').value = '{}';
                break;
            case 'POST /api/products':
                document.getElementById('requestBody').value = `{
  "name": "New Product",
  "sku": "PROD-${Math.floor(100000 + Math.random() * 900000)}",
  "price": 99.99,
  "stock": 50,
  "expiry_date": "${new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}",
  "category": "electronics",
  "description": "Product description"
}`;
                break;
            case 'POST /api/products/bulk':
                document.getElementById('requestBody').value = `{
  "products": [
    {
      "name": "Product 1",
      "sku": "PROD-${Math.floor(100000 + Math.random() * 900000)}",
      "price": 29.99,
      "category": "electronics"
    },
    {
      "name": "Product 2",
      "sku": "PROD-${Math.floor(100000 + Math.random() * 900000)}",
      "price": 49.99,
      "category": "clothing"
    }
  ]
}`;
                break;
        }
    }
    
    async function sendApiRequest() {
        const endpointSelect = document.getElementById('endpointSelect').value;
        const [method, path] = endpointSelect.split(' ');
        let url = path;
        
        // Replace {id} with actual ID if needed
        if (path.includes('{id}')) {
            const productId = prompt('Enter Product ID:', '1');
            if (!productId) return;
            url = url.replace('{id}', productId);
        }
        
        const requestBody = document.getElementById('requestBody').value;
        const showHeaders = document.getElementById('showHeaders').checked;
        
        try {
            const data = requestBody.trim() ? JSON.parse(requestBody) : {};
            
            document.getElementById('apiPlaygroundResponse').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            
            const config = {
                method: method.toLowerCase(),
                url: url,
                data: data
            };
            
            const response = await axios(config);
            
            let responseText = '';
            if (showHeaders) {
                responseText += `Status: ${response.status} ${response.statusText}\n`;
                responseText += `Headers:\n${formatJSON(response.headers)}\n\n`;
            }
            responseText += `Body:\n${formatJSON(response.data)}`;
            
            document.getElementById('apiPlaygroundResponse').innerHTML = 
                `<pre>${responseText}</pre>`;
                
        } catch (error) {
            let errorText = '';
            if (showHeaders && error.response) {
                errorText += `Status: ${error.response.status} ${error.response.statusText}\n`;
                errorText += `Headers:\n${formatJSON(error.response.headers)}\n\n`;
            }
            
            if (error.response?.data) {
                errorText += `Body:\n${formatJSON(error.response.data)}`;
            } else {
                errorText += `Error: ${error.message}`;
            }
            
            document.getElementById('apiPlaygroundResponse').innerHTML = 
                `<pre class="text-danger">${errorText}</pre>`;
        }
    }
    
    function clearResponse() {
        document.getElementById('apiPlaygroundResponse').innerHTML = 'Response will appear here...';
    }
</script>
@endsection