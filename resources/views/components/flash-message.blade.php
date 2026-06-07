@if (session()->has('success'))
    <div class="flash-toast-data" data-type="success" data-message="{{ session('success') }}"></div>
@endif

@if (session()->has('error'))
    <div class="flash-toast-data" data-type="error" data-message="{{ session('error') }}"></div>
@endif

@if (session()->has('warning'))
    <div class="flash-toast-data" data-type="warning" data-message="{{ session('warning') }}"></div>
@endif

<script nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        const toastDataElements = document.querySelectorAll('.flash-toast-data');
        toastDataElements.forEach(function(el) {
            const type = el.getAttribute('data-type');
            const message = el.getAttribute('data-message');
            if (type && message) {
                showPremiumToast(type, message);
            }
            el.remove(); // Clean up from DOM
        });
    });

    function showPremiumToast(type, message) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'premium-toast';
        
        let icon = 'bi-info-circle';
        let iconColor = 'var(--accent-color)';
        let borderLeftColor = 'var(--accent-color)';
        let title = 'Informasi';
        
        if (type === 'success') {
            icon = 'bi-check-circle';
            iconColor = '#16a34a'; // Green
            borderLeftColor = '#16a34a';
            title = 'Berhasil';
        } else if (type === 'error') {
            icon = 'bi-exclamation-triangle';
            iconColor = '#dc2626'; // Red
            borderLeftColor = '#dc2626';
            title = 'Kesalahan';
        } else if (type === 'warning') {
            icon = 'bi-exclamation-circle';
            iconColor = '#d97706'; // Amber
            borderLeftColor = '#d97706';
            title = 'Peringatan';
        }

        toast.style.borderLeftColor = borderLeftColor;

        toast.innerHTML = `
            <div style="color: ${iconColor}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.15rem; line-height: 1;">
                <i class="bi ${icon}"></i>
            </div>
            <div style="flex-grow: 1; padding-right: 0.5rem; line-height: 1.4;">
                <strong style="display: block; font-family: 'Zen Old Mincho', serif; color: #ffffff; margin-bottom: 2px;">
                    ${title}
                </strong>
                <span style="color: #cbd5e1; font-size: 0.8rem;">${message}</span>
            </div>
            <button type="button" class="premium-toast-close">
                <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
            </button>
            <div class="toast-progress"></div>
        `;

        container.appendChild(toast);

        // Slide in
        setTimeout(() => {
            toast.classList.add('show');
        }, 50);

        // Progress bar animation
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.backgroundColor = borderLeftColor;
        progressBar.style.transition = 'width 5s linear';
        // Force reflow
        progressBar.getBoundingClientRect();
        progressBar.style.width = '0%';

        // Auto dismiss after 5 seconds
        const dismissTimeout = setTimeout(() => {
            dismissToast(toast);
        }, 5000);

        // Manual dismiss
        toast.querySelector('.premium-toast-close').addEventListener('click', () => {
            clearTimeout(dismissTimeout);
            dismissToast(toast);
        });
    }

    function dismissToast(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 400);
    }
</script>
