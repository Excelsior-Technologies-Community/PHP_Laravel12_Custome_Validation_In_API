@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="fas fa-plus-circle text-success me-2"></i>Create Product Page
                </h3>
                <p class="text-muted mb-0">Dedicated screen for adding products with real-time custom API validation</p>
            </div>
            <a href="/" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>

        @include('partials.create-product')
    </div>
</div>
@endsection
