@if (session()->has('success'))
    <div class="alert alert-success border-1 d-flex align-items-center mb-4" role="alert" style="border-radius: 3px; background-color: #f0fdf4; border-color: #bbf7d0; color: #166534; font-size: 0.9rem; padding: 1rem 1.25rem !important; line-height: 1.5; min-height: 52px;">
        <i class="bi bi-check-circle me-3" style="font-size: 1.1rem; line-height: 1;"></i>
        <div>
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger border-1 d-flex align-items-center mb-4" role="alert" style="border-radius: 3px; background-color: #fef2f2; border-color: #fecaca; color: #991b1b; font-size: 0.9rem; padding: 1rem 1.25rem !important; line-height: 1.5; min-height: 52px;">
        <i class="bi bi-exclamation-triangle me-3" style="font-size: 1.1rem; line-height: 1;"></i>
        <div>
            {{ session('error') }}
        </div>
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning border-1 d-flex align-items-center mb-4" role="alert" style="border-radius: 3px; background-color: #fffbeb; border-color: #fde68a; color: #92400e; font-size: 0.9rem; padding: 1rem 1.25rem !important; line-height: 1.5; min-height: 52px;">
        <i class="bi bi-exclamation-circle me-3" style="font-size: 1.1rem; line-height: 1;"></i>
        <div>
            {{ session('warning') }}
        </div>
    </div>
@endif
