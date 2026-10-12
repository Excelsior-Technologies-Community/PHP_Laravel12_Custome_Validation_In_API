@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Header Controls -->
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-barcode text-primary me-2"></i>Product Labels Print Studio
            </h3>
            <p class="text-muted mb-0">High-Resolution CODE128 Barcodes & QR Codes with Compliance Tags</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> Print Labels
            </button>
        </div>
    </div>

    <!-- Print Stylesheet -->
    <style>
        .label-sheet {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .barcode-card {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            page-break-inside: avoid;
            position: relative;
        }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .navbar, .d-print-none {
                display: none !important;
            }
            .label-sheet {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 15px !important;
            }
            .barcode-card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
            }
        }
    </style>

    <div class="label-sheet">
        @forelse($products as $product)
            <div class="barcode-card text-center">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-dark px-2 py-1" style="font-size: 0.75rem;">{{ strtoupper($product->category) }}</span>
                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        Stock: {{ $product->stock }}
                    </span>
                </div>

                <h5 class="fw-bold text-truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h5>
                <div class="fs-4 fw-bold text-primary mb-2">${{ number_format($product->price, 2) }}</div>

                <!-- CODE128 Barcode Canvas -->
                <div class="my-2 d-flex justify-content-center">
                    <svg class="barcode-svg" id="barcode-{{ $product->id }}" data-sku="{{ $product->sku }}"></svg>
                </div>

                <!-- QR Code Canvas & Meta -->
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <div id="qrcode-{{ $product->id }}" class="border p-1 rounded bg-light"></div>
                    <div class="text-end small text-muted">
                        <div><strong>SKU:</strong> {{ $product->sku }}</div>
                        <div><strong>Expiry:</strong> {{ $product->expiry_date ? $product->expiry_date->format('d M Y') : 'N/A' }}</div>
                        <div><strong>Status:</strong> <span class="text-uppercase">{{ $product->status }}</span></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No products found for label generation.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<!-- JsBarcode & QRCode.js libraries -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Generate Barcodes
        document.querySelectorAll('.barcode-svg').forEach(function(el) {
            const sku = el.getAttribute('data-sku');
            if (sku) {
                try {
                    JsBarcode(el, sku, {
                        format: "CODE128",
                        width: 1.6,
                        height: 48,
                        displayValue: true,
                        fontSize: 13,
                        margin: 2
                    });
                } catch(e) {
                    console.error("Barcode error:", e);
                }
            }
        });

        // Generate QR Codes
        @foreach($products as $product)
            try {
                const qrContainer = document.getElementById("qrcode-{{ $product->id }}");
                if (qrContainer) {
                    new QRCode(qrContainer, {
                        text: JSON.stringify({
                            id: {{ $product->id }},
                            name: "{{ addslashes($product->name) }}",
                            sku: "{{ $product->sku }}",
                            price: {{ $product->price }}
                        }),
                        width: 58,
                        height: 58,
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            } catch(e) {
                console.error("QR error:", e);
            }
        @endforeach
    });
</script>
@endsection
