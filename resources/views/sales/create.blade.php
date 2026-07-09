@extends('layouts.app')

@section('title', __('app.new_sale'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.new_sale') }} (POS)</h1>
            <p class="page-subtitle">{{ __('app.new_sale_subtitle') }}</p>
        </div>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">&larr; {{ __('app.back_to_list') }}</a>
    </div>

    @if (session('error'))
        <div style="background:#fde2e2;color:#dc3545;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div id="posError" style="display:none;background:#fde2e2;color:#dc3545;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;"></div>

    <div class="pos-layout">

        {{-- Left: Product picker --}}
        <div class="card">

            <div class="form-group">
                <label class="form-label">{{ __('app.product_search') }}</label>
                <input type="text" id="productSearch" class="form-control" placeholder="{{ __('app.search_name_sku') }}">
            </div>

            <div class="table-wrapper" style="box-shadow:none;">
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
            </div>

        </div>

        {{-- Right: Cart + Checkout --}}
        <div class="card">

            <h2 style="font-size:18px;margin-bottom:15px;color:#198754;">{{ __('app.cart') }}</h2>

            <div class="table-wrapper" style="box-shadow:none;margin-bottom:20px;">
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
            </div>

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

                <button type="submit" class="btn btn-primary btn-block" id="submitSaleBtn">{{ __('app.complete_sale') }}</button>

            </form>

        </div>

    </div>

</div>

<style>
    .pos-layout{
        display:grid;
        grid-template-columns:1.5fr 1fr;
        gap:20px;
        align-items:start;
    }
    /* চেকআউট প্যানেলটা স্ক্রল করলেও সবসময় দেখা যাবে, নিচের গ্রাহকের ডিটেইলস/বাটন
       আর স্ক্রিনের বাইরে চলে যাবে না */
    .pos-layout .card:nth-child(2){
        position:sticky;
        top:20px;
        max-height:calc(100vh - 40px);
        overflow-y:auto;
    }
    @media (max-width:900px){
        .pos-layout{
            grid-template-columns:1fr;
        }
        .pos-layout .card:nth-child(2){
            position:static;
            max-height:none;
            overflow-y:visible;
        }
    }
</style>

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
        posError.style.display = 'block';
        setTimeout(function () { posError.style.display = 'none'; }, 4000);
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
                '<td>' + p.name + '<br><small style="color:#999;">' + p.sku + '</small></td>' +
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
                    '<td class="text-right"><input type="number" min="1" max="' + item.stock + '" value="' + item.qty + '" data-qty="' + id + '" class="form-control" style="width:80px;height:36px;padding:0 8px;display:inline-block;"></td>' +
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
