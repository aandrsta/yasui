@if (session()->has('success'))
    <div class="mb-4 animate-fade-in-up" role="alert" style="border-radius: 3px; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 0.9rem; display: flex !important; align-items: center !important; padding: 1rem 1.25rem !important; line-height: 1.4; width: 100%; box-sizing: border-box; position: relative; z-index: 99;">
        <i class="bi bi-check-circle me-2.5" style="font-size: 1.15rem; flex-shrink: 0; line-height: 1; display: inline-flex; align-items: center;"></i>
        <span style="display: inline-block;">{{ session('success') }}</span>
    </div>
@endif

@if (session()->has('error'))
    <div class="mb-4 animate-fade-in-up" role="alert" style="border-radius: 3px; background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 0.9rem; display: flex !important; align-items: center !important; padding: 1rem 1.25rem !important; line-height: 1.4; width: 100%; box-sizing: border-box; position: relative; z-index: 99;">
        <i class="bi bi-exclamation-triangle me-2.5" style="font-size: 1.15rem; flex-shrink: 0; line-height: 1; display: inline-flex; align-items: center;"></i>
        <span style="display: inline-block;">{{ session('error') }}</span>
    </div>
@endif

@if (session()->has('warning'))
    <div class="mb-4 animate-fade-in-up" role="alert" style="border-radius: 3px; background-color: #fffbeb; border: 1px solid #fde68a; color: #92400e; font-size: 0.9rem; display: flex !important; align-items: center !important; padding: 1rem 1.25rem !important; line-height: 1.4; width: 100%; box-sizing: border-box; position: relative; z-index: 99;">
        <i class="bi bi-exclamation-circle me-2.5" style="font-size: 1.15rem; flex-shrink: 0; line-height: 1; display: inline-flex; align-items: center;"></i>
        <span style="display: inline-block;">{{ session('warning') }}</span>
    </div>
@endif
