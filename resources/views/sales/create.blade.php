@extends('layouts.app')

@section('title', __('app.new_sale'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
@endpush

@section('content')

<div class="page">

    <x-page-header>
        <x-slot:heading>
            <h1 class="page-title">{{ __('app.new_sale') }} (POS)</h1>
            <p class="page-subtitle">{{ __('app.new_sale_subtitle') }}</p>
        </x-slot:heading>
        <x-slot:actions>
            <x-button tag="a" href="{{ route('sales.index') }}" variant="secondary">&larr; {{ __('app.back_to_list') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('error'))
        <x-alert variant="danger">
            {{ session('error') }}
        </x-alert>
    @endif

    <div id="posError" class="alert alert-danger d-none"></div>

    <div class="pos-layout">

        {{-- Left: Product picker --}}
        <x-card>

            <div class="form-group">
                <label class="form-label">{{ __('app.product_search') }}</label>
                <input type="text" id="productSearch" class="form-control" placeholder="{{ __('app.search_name_sku') }}">
            </div>

            <x-table-wrapper class="table-wrapper-flat">
                <table class="table" id="productTable">
                    <thead>
                        <tr>
                            <th>{{ __('app.product') }}</th>
                            <th class="text-right">{{ __('app.price') }}</th>
                            <th class="text-right">{{ __('app.stock') }}</th>
                            <th class="text-right">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        {{-- JS দিয়ে ভরা হবে --}}
                    </tbody>
                </table>
            </x-table-wrapper>

        </x-card>

        {{-- Right: Cart + Checkout --}}
        <x-card>

            <h2 class="section-title text-primary">{{ __('app.cart') }}</h2>

            <x-table-wrapper class="table-wrapper-flat mb-20">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('app.product') }}</th>
                            <th class="text-right">{{ __('app.qty') }}</th>
                            <th class="text-right">{{ __('app.subtotal') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody">
                        <tr id="emptyCartRow">
                            <td colspan="4" class="table-empty">{{ __('app.cart_empty') }}</td>
                        </tr>
                    </tbody>
                </table>
            </x-table-wrapper>

            <form method="POST" action="{{ route('sales.store') }}" id="saleForm">
                @csrf

                <div id="hiddenItems"></div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.customer_name') }}</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="{{ __('app.optional') }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.subtotal') }}</label>
                        <input type="text" class="form-control" id="subtotalDisplay" value="0.00" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.discount') }}</label>
                        <input type="number" step="0.01" min="0" name="discount" id="discountInput" class="form-control" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.total') }}</label>
                        <input type="text" class="form-control" id="totalDisplay" value="0.00" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.paid_amount') }}</label>
                        <input type="number" step="0.01" min="0" name="paid_amount" id="paidInput" class="form-control" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.due') }}</label>
                    <input type="text" class="form-control" id="dueDisplay" value="0.00" disabled>
                </div>

                <x-button variant="primary" block id="submitSaleBtn">{{ __('app.complete_sale') }}</x-button>

            </form>

        </x-card>

    </div>

</div>

<script>
(function () {
    const PRODUCTS = {!! $productsForJs->toJson() !!};

    // POS এর জাভাস্ক্রিপ্ট UI টেক্সট (বাংলা/ইংলিশ) - সার্ভার থেকে বর্তমান ভাষা অনুযায়ী পাঠানো হচ্ছে
    const I18N = {!! json_encode([
        'no_products_found' => __('app.no_products_found'),
        'cart_empty' => __('app.cart_empty'),
        'add' => __('app.add'),
        'out_of_stock' => __('app.out_of_stock'),
        'no_stock' => __('app.no_stock_error'),
        'insufficient_stock' => __('app.insufficient_stock_error'),
        'current_stock' => __('app.current_stock'),
        'cart_is_empty_error' => __('app.cart_is_empty_error'),
    ], JSON_UNESCAPED_UNICODE) !!};

    let cart = {}; // product_id -> { id, name, price, qty, stock }
    let paidManuallyEdited = false;

    const productTableBody = document.getElementById('productTableBody');
    const cartTableBody = document.getElementById('cartTableBody');
    const productSearch = document.getElementById('productSearch');
    const posError = document.getElementById('posError');
    const discountInput = document.getElementById('discountInput');
    const paidInput = document.getElementById('paidInput');

    function money(n) {
        return Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function showError(msg) {
        posError.textContent = msg;
        posError.classList.remove('d-none');
        setTimeout(function () { posError.classList.add('d-none'); }, 4000);
    }

    function recalcTotals() {
        let subtotal = 0;
        Object.values(cart).forEach(function (item) {
            subtotal += item.price * item.qty;
        });

        const discount = parseFloat(discountInput.value) || 0;
        const total = Math.max(subtotal - discount, 0);

        // যতক্ষণ ইউজার নিজে Paid Amount পরিবর্তন না করে, ততক্ষণ এটা Total এর সমান রাখা হবে
        if (!paidManuallyEdited) {
            paidInput.value = total.toFixed(2);
        }

        const paid = parseFloat(paidInput.value) || 0;
        const due = Math.max(total - paid, 0);

        document.getElementById('subtotalDisplay').value = money(subtotal);
        document.getElementById('totalDisplay').value = money(total);
        document.getElementById('dueDisplay').value = money(due);
    }

    function renderProducts(filter) {
        filter = (filter || '').toLowerCase();
        productTableBody.innerHTML = '';

        const filtered = PRODUCTS.filter(function (p) {
            return p.name.toLowerCase().includes(filter) || p.sku.toLowerCase().includes(filter);
        });

        if (filtered.length === 0) {
            productTableBody.innerHTML = '<tr><td colspan="4" class="table-empty">' + I18N.no_products_found + '</td></tr>';
            return;
        }

        filtered.forEach(function (p) {
            const tr = document.createElement('tr');
            const outOfStock = p.stock <= 0;

            tr.innerHTML =
                '<td>' + p.name + '<br><small class="text-muted-note">' + p.sku + '</small></td>' +
                '<td class="text-right">৳ ' + money(p.price) + '</td>' +
                '<td class="text-right">' + p.stock + ' ' + p.unit + '</td>' +
                '<td class="text-right"><button type="button" class="btn btn-secondary btn-sm" data-add="' + p.id + '"' + (outOfStock ? ' disabled' : '') + '>' + I18N.add + '</button></td>';

            productTableBody.appendChild(tr);
        });
    }

    function renderCart() {
        const ids = Object.keys(cart);
        cartTableBody.innerHTML = '';

        if (ids.length === 0) {
            cartTableBody.innerHTML = '<tr id="emptyCartRow"><td colspan="4" class="table-empty">' + I18N.cart_empty + '</td></tr>';
        } else {
            ids.forEach(function (id) {
                const item = cart[id];
                const subtotal = item.price * item.qty;

                const tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + item.name + '</td>' +
                    '<td class="text-right"><input type="number" min="1" max="' + item.stock + '" value="' + item.qty + '" data-qty="' + id + '" class="form-control input-qty-cart"></td>' +
                    '<td class="text-right">৳ ' + money(subtotal) + '</td>' +
                    '<td class="text-right"><button type="button" class="btn btn-danger btn-sm" data-remove="' + id + '">&times;</button></td>';

                cartTableBody.appendChild(tr);
            });
        }

        recalcTotals();
    }

    function addToCart(id) {
        const product = PRODUCTS.find(function (p) { return p.id === id; });
        if (!product) return;

        if (product.stock <= 0) {
            showError(I18N.no_stock);
            return;
        }

        if (cart[id]) {
            if (cart[id].qty + 1 > product.stock) {
                showError(I18N.insufficient_stock + ' ' + I18N.current_stock + 'ঃ ' + product.stock);
                return;
            }
            cart[id].qty += 1;
        } else {
            cart[id] = {
                id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock,
                qty: 1,
            };
        }

        renderCart();
    }

    productTableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-add]');
        if (!btn) return;
        addToCart(parseInt(btn.getAttribute('data-add'), 10));
    });

    cartTableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-remove]');
        if (!btn) return;
        delete cart[btn.getAttribute('data-remove')];
        renderCart();
    });

    cartTableBody.addEventListener('input', function (e) {
        const input = e.target.closest('[data-qty]');
        if (!input) return;

        const id = input.getAttribute('data-qty');
        const item = cart[id];
        if (!item) return;

        let qty = parseInt(input.value, 10) || 1;

        if (qty > item.stock) {
            showError(I18N.insufficient_stock + ' ' + I18N.current_stock + 'ঃ ' + item.stock);
            qty = item.stock;
            input.value = qty;
        }

        if (qty < 1) {
            qty = 1;
            input.value = qty;
        }

        item.qty = qty;
        recalcTotals();

        // শুধু সেই row এর subtotal cell আপডেট করা হচ্ছে (পুরো re-render না করে, যাতে focus না হারায়)
        const subtotalCell = input.closest('tr').querySelector('td:nth-child(3)');
        subtotalCell.textContent = '৳ ' + money(item.price * item.qty);
    });

    document.getElementById('discountInput').addEventListener('input', recalcTotals);
    document.getElementById('paidInput').addEventListener('input', function () {
        paidManuallyEdited = true;
        recalcTotals();
    });
    productSearch.addEventListener('input', function () { renderProducts(productSearch.value); });

    document.getElementById('saleForm').addEventListener('submit', function (e) {
        const ids = Object.keys(cart);

        if (ids.length === 0) {
            e.preventDefault();
            showError(I18N.cart_is_empty_error);
            return;
        }

        const hiddenItems = document.getElementById('hiddenItems');
        hiddenItems.innerHTML = '';

        ids.forEach(function (id, index) {
            const item = cart[id];

            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'items[' + index + '][product_id]';
            productInput.value = item.id;
            hiddenItems.appendChild(productInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'items[' + index + '][quantity]';
            qtyInput.value = item.qty;
            hiddenItems.appendChild(qtyInput);
        });
    });

    // প্রাথমিক রেন্ডার
    renderProducts('');
    renderCart();
})();
</script>

@endsection
