@if (session()->has('success'))
    <div class="d-flex align-items-center mb-4 px-4 animate-fade-in-up" role="alert" style="border-radius: 3px; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 0.9rem; padding: 1.25rem 1.5rem; min-height: 56px; line-height: 1.5; box-shadow: 0 4px 12px rgba(22, 101, 52, 0.02);">
        <i class="bi bi-check-circle-fill me-3 fs-5" style="color: #16a34a;"></i>
        <div class="fw-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="d-flex align-items-center mb-4 px-4 animate-fade-in-up" role="alert" style="border-radius: 3px; background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 0.9rem; padding: 1.25rem 1.5rem; min-height: 56px; line-height: 1.5; box-shadow: 0 4px 12px rgba(153, 27, 27, 0.02);">
        <i class="bi bi-exclamation-triangle-fill me-3 fs-5" style="color: #dc2626;"></i>
        <div class="fw-medium">
            {{ session('error') }}
        </div>
    </div>
@endif

@if (session()->has('warning'))
    <div class="d-flex align-items-center mb-4 px-4 animate-fade-in-up" role="alert" style="border-radius: 3px; background-color: #fffbeb; border: 1px solid #fde68a; color: #92400e; font-size: 0.9rem; padding: 1.25rem 1.5rem; min-height: 56px; line-height: 1.5; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.02);">
        <i class="bi bi-exclamation-circle-fill me-3 fs-5" style="color: #d97706;"></i>
        <div class="fw-medium">
            {{ session('warning') }}
        </div>
    </div>
@endif
