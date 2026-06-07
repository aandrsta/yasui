@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('styles')
<style>
    /* Premium Minimalist Cart Styling */
    .cart-wrapper {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .cart-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    .cart-items-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
    }

    .cart-item-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition-base);
    }

    .cart-item-row:last-child {
        border-bottom: none;
    }

    .cart-item-row:hover {
        background-color: var(--bg-subtle);
    }

    .cart-image-wrapper {
        width: 90px;
        height: 90px;
        border-radius: 3px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-fallback-icon {
        font-size: 2.25rem;
        color: var(--text-muted);
    }

    .cart-info {
        flex-grow: 1;
    }

    .cart-item-category {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        margin-bottom: 0.15rem;
    }

    .cart-item-name {
        font-size: 0.975rem;
        font-weight: 600;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition-base);
        display: block;
        margin-bottom: 0.25rem;
    }

    .cart-item-name:hover {
        color: var(--accent-color);
    }

    .cart-item-price {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .cart-qty-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cart-qty-input {
        width: 65px;
        text-align: center;
        font-weight: 600;
        border-radius: 3px;
        border: 1px solid var(--border-color);
        padding: 6px;
        font-size: 0.875rem;
        background-color: var(--bg-subtle);
    }

    .cart-qty-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: var(--bg-main);
    }

    .btn-update-qty {
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        color: var(--text-main);
        border-radius: 3px;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-base);
    }

    .btn-update-qty:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: var(--bg-main);
    }

    .cart-subtotal-price {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--primary-color);
        min-width: 120px;
        text-align: right;
    }

    .btn-cart-remove {
        background: transparent;
        border: none;
        color: var(--text-muted);
        transition: var(--transition-base);
        padding: 8px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cart-remove:hover {
        color: #ef4444;
        background-color: #fef2f2;
    }

    /* Cart Summary Card */
    .summary-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary-color);
        letter-spacing: -0.02em;
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        color: var(--text-muted);
    }

    .summary-row.total-row {
        border-top: 1px solid var(--border-color);
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--primary-color);
    }

    /* Kustom Checkbox Visual Premium (Cherry / Sakura Rose) */
    .form-check-input {
        width: 19px;
        height: 19px;
        cursor: pointer;
        border: 1.5px solid var(--text-muted) !important;
        background-color: var(--bg-subtle);
        transition: var(--transition-base);
    }
    .form-check-input:checked {
        background-color: var(--accent-color) !important;
        border-color: var(--accent-color) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fbfaf7' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e") !important;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(162, 56, 74, 0.15) !important;
        border-color: var(--accent-color) !important;
    }

    /* Dynamic deletion animations */
    .cart-item-fade-out {
        opacity: 0 !important;
        transform: translateX(-30px) !important;
        max-height: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border-bottom-width: 0 !important;
        overflow: hidden !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
</style>
@endsection

@section('content')
<!-- Back to Shopping -->
<div class="mb-4">
    <a href="{{ url('/products') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
        <i class="bi bi-chevron-left small"></i>
        <span>Kembali Belanja</span>
    </a>
</div>

<h1 class="h3 fw-bold text-dark mb-4 pb-2" style="letter-spacing: -0.03em;">Keranjang Belanja</h1>

@if($cartItems->isEmpty())
    <div class="card border border-light text-center py-5 px-4 shadow-sm animate-fade-in-up" style="border-radius: 3px;">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 bg-light text-muted mx-auto" style="width: 72px; height: 72px;">
            <i class="bi bi-bag-x fs-2"></i>
        </div>
        <h3 class="h5 fw-bold text-dark mb-2">Keranjang Belanja Anda Kosong</h3>
        <p class="text-muted small mx-auto mb-4" style="max-width: 400px;">
            Anda belum menambahkan produk apa pun ke dalam keranjang belanja. Temukan berbagai figure, model kit, dan merchandise anime favorit Anda sekarang!
        </p>
        <div>
            <a href="{{ url('/products') }}" class="btn-minimal-accent text-decoration-none px-4 py-2">Jelajahi Produk</a>
        </div>
    </div>
@else
    <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
        <div class="cart-wrapper animate-fade-in-up">
            <!-- Left Side: Cart Items List -->
            <div class="cart-items-card">
                <!-- Select All Header -->
                <div class="p-3 border-bottom d-flex align-items-center gap-3 bg-light" style="border-radius: 3px 3px 0 0;">
                    <input type="checkbox" id="select-all-cart" class="form-check-input" checked>
                    <label for="select-all-cart" class="small fw-bold text-dark mb-0" style="cursor: pointer; font-size: 0.85rem; letter-spacing: -0.01em;">PILIH SEMUA PRODUK</label>
                </div>

                @foreach($cartItems as $item)
                    <div class="cart-item-row">
                        <!-- Select Item Checkbox -->
                        <div class="d-flex align-items-center">
                            <input type="checkbox" name="items[]" value="{{ $item->id }}" class="cart-item-checkbox form-check-input" checked data-qty="{{ $item->quantity }}" data-subtotal="{{ $item->subtotal }}">
                        </div>

                        <!-- Product Image -->
                        <div class="cart-image-wrapper">
                            @if($item->product->image && file_exists(public_path($item->product->image)))
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="cart-fallback-icon">
                                    @if($item->product->category->slug === 'figures')
                                        <i class="bi bi-box-seam"></i>
                                    @elseif($item->product->category->slug === 'model-kits')
                                        <i class="bi bi-tools"></i>
                                    @elseif($item->product->category->slug === 'character-goods')
                                        <i class="bi bi-gem"></i>
                                    @else
                                        <i class="bi bi-hearts"></i>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Product Description -->
                        <div class="cart-info">
                            <span class="cart-item-category">{{ $item->product->category->name }}</span>
                            <a href="{{ url('/products/' . $item->product->slug) }}" class="cart-item-name">
                                {{ $item->product->name }}
                            </a>
                            <span class="cart-item-price">{{ 'Rp ' . number_format($item->product->price, 0, ',', '.') }}</span>
                            
                            <!-- Stock warnings if low -->
                            @if($item->product->stock < 5)
                                <div class="text-warning small mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-exclamation-triangle"></i> Sisa stok sedikit! (Sisa: {{ $item->product->stock }})
                                </div>
                            @endif
                        </div>

                        <!-- Quantity Input Trigger (Plain form components) -->
                        <div>
                            <div class="cart-qty-form">
                                <input type="number" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control cart-qty-input">
                                <button type="button" class="btn btn-update-qty shadow-sm btn-update-cart-qty" data-id="{{ $item->id }}" title="Perbarui Jumlah">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Subtotal for this Item -->
                        <div class="cart-subtotal-price">
                            {{ $item->formatted_subtotal }}
                        </div>

                        <!-- Delete Button (Plain button component) -->
                        <div>
                            <button type="button" class="btn-cart-remove shadow-sm btn-delete-cart-item" data-id="{{ $item->id }}" title="Hapus Barang">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Side: Order Summary Panel -->
            <div class="summary-card shadow-sm">
                <h3 class="summary-title">Ringkasan Belanja</h3>
                
                <div class="summary-row">
                    <span>Subtotal Barang (<span id="summary-total-qty">{{ $cartItems->sum('quantity') }}</span> unit)</span>
                    <span class="fw-semibold text-dark" id="summary-subtotal-price">{{ $formattedTotalPrice }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Estimasi Biaya Pengiriman</span>
                    <span class="text-success fw-semibold">Gratis</span>
                </div>
                
                <div class="summary-row">
                    <span>Pajak & Biaya Tambahan</span>
                    <span>Rp 0</span>
                </div>

                <div class="summary-row total-row">
                    <span>Total Harga</span>
                    <span id="summary-total-price">{{ $formattedTotalPrice }}</span>
                </div>

                <button type="submit" id="checkout-btn" class="btn-minimal-accent w-100 py-3 text-center border-0 fw-semibold shadow-sm d-inline-block">
                    Lanjutkan ke Checkout
                </button>
                
                <div class="text-center mt-3 small text-muted">
                    <i class="bi bi-shield-check text-success"></i> Transaksi dijamin aman & tepercaya
                </div>
            </div>
        </div>
    </form>
@endif

<!-- Hidden Forms for Update and Delete to avoid nested form tags -->
<form id="update-form" method="POST" style="display:none;" data-no-spinner="true">
    @csrf
    @method('PATCH')
    <input type="hidden" name="quantity" id="update-quantity">
</form>

<form id="delete-form" method="POST" style="display:none;" data-no-spinner="true">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all-cart');
        const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
        const summaryTotalQty = document.getElementById('summary-total-qty');
        const summarySubtotalPrice = document.getElementById('summary-subtotal-price');
        const summaryTotalPrice = document.getElementById('summary-total-price');
        const checkoutBtn = document.getElementById('checkout-btn');

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        function updateTotals() {
            let totalQty = 0;
            let totalPrice = 0;
            let checkedCount = 0;

            itemCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalQty += parseInt(checkbox.dataset.qty);
                    totalPrice += parseFloat(checkbox.dataset.subtotal);
                    checkedCount++;
                }
            });

            if (summaryTotalQty) summaryTotalQty.textContent = totalQty;
            if (summarySubtotalPrice) summarySubtotalPrice.textContent = formatRupiah(totalPrice);
            if (summaryTotalPrice) summaryTotalPrice.textContent = formatRupiah(totalPrice);

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = itemCheckboxes.length > 0 && checkedCount === itemCheckboxes.length;
            }

            if (checkoutBtn) {
                if (checkedCount === 0) {
                    checkoutBtn.disabled = true;
                    checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    checkoutBtn.disabled = false;
                    checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // Quantity updates without inline JavaScript handlers (CSP Safe)
        document.querySelectorAll('.btn-update-cart-qty').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const qtyInput = this.closest('.cart-qty-form').querySelector('.cart-qty-input');
                const form = document.getElementById('update-form');
                form.action = "{{ url('/cart') }}/" + itemId;
                document.getElementById('update-quantity').value = qtyInput.value;
                form.submit();
            });
        });

        // Item deletion without inline JavaScript handlers (CSP Safe)
        document.querySelectorAll('.btn-delete-cart-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    const row = this.closest('.cart-item-row');
                    if (row) {
                        row.classList.add('cart-item-fade-out');
                    }
                    setTimeout(() => {
                        const form = document.getElementById('delete-form');
                        form.action = "{{ url('/cart') }}/" + itemId;
                        form.submit();
                    }, 350);
                }
            });
        });

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateTotals();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateTotals();
            });
        });

        // Run once initially to configure button state
        updateTotals();
    });
</script>
@endsection
