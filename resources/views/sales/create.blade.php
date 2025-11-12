@extends('layouts.app')

@section('title', 'Transaksi Penjualan Baru')

@section('styles')
<style>
    .product-selection { margin-bottom: 20px; }
    .item-list { margin-top: 20px; }
    .item-row { background: white; padding: 15px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #ddd; }
    .summary-card { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-top: 20px; }
    .summary-item { display: flex; justify-content: space-between; padding: 8px 0; }
    .summary-item.total { font-size: 20px; font-weight: bold; color: #667eea; border-top: 2px solid #ddd; padding-top: 15px; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Transaksi Penjualan</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="member_id" class="form-label">Member (Opsional)</label>
                            <select class="form-control" id="member_id" name="member_id">
                                <option value="">-- Tidak Ada Member --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->user->name }} ({{ $member->member_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">-- Pilih --</option>
                                <option value="cash">Tunai</option>
                                <option value="card">Kartu</option>
                                <option value="transfer">Transfer</option>
                            </select>
                            @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="product-selection">
                        <label class="form-label">Pilih Barang</label>
                        <div class="row">
                            <div class="col-md-8">
                                <select class="form-control" id="productSelect">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-name="{{ $product->name }}">
                                            {{ $product->code }} - {{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" id="quantity" placeholder="Qty" min="1" value="1">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100" id="addItemBtn">
                                    <i class="bi bi-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="item-list">
                        <table class="table table-sm" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" id="itemsInput" name="items" value="[]">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                            <i class="bi bi-check-circle"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-card">
            <h6 class="mb-3">Ringkasan Transaksi</h6>
            <div class="summary-item">
                <span>Subtotal:</span>
                <span id="subtotalDisplay">Rp 0</span>
            </div>
            <div class="summary-item">
                <span>Diskon:</span>
                <span id="discountDisplay">Rp 0</span>
            </div>
            <div class="summary-item total">
                <span>Total:</span>
                <span id="totalDisplay">Rp 0</span>
            </div>
            <div class="mt-3 p-2 bg-white rounded" style="font-size: 12px;">
                <div id="discountInfo" style="display:none;">
                    <p><strong>Info Diskon Member:</strong></p>
                    <p id="discountInfoText"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let items = [];
const products = @json($products->keyBy('id'));
const memberSelect = document.getElementById('member_id');

function updateSummary() {
    let subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);
    let discount = 0;

    if (memberSelect.value) {
        const discountPercentage = calculateMemberDiscount(subtotal);
        discount = (subtotal * discountPercentage) / 100;
        document.getElementById('discountInfo').style.display = 'block';
        document.getElementById('discountInfoText').innerText = 'Diskon: ' + discountPercentage + '% (Rp ' + formatNumber(discount) + ')';
    } else {
        document.getElementById('discountInfo').style.display = 'none';
    }

    const total = subtotal - discount;

    document.getElementById('subtotalDisplay').innerText = 'Rp ' + formatNumber(subtotal);
    document.getElementById('discountDisplay').innerText = 'Rp ' + formatNumber(discount);
    document.getElementById('totalDisplay').innerText = 'Rp ' + formatNumber(total);

    document.getElementById('itemsInput').value = JSON.stringify(items);
    document.getElementById('submitBtn').disabled = items.length === 0;
}

function calculateMemberDiscount(totalPurchase) {
    const discounts = @json(
        \App\Models\MemberDiscount::orderBy('minimum_purchase', 'desc')->get(['minimum_purchase', 'discount_percentage'])
    );
    
    for (let discount of discounts) {
        if (totalPurchase >= discount.minimum_purchase) {
            return discount.discount_percentage;
        }
    }
    return 0;
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(Math.round(num));
}

function renderItems() {
    const tbody = document.getElementById('itemsBody');
    tbody.innerHTML = items.map((item, index) => `
        <tr>
            <td>${item.product_name}</td>
            <td>Rp ${formatNumber(item.unit_price)}</td>
            <td>${item.quantity}</td>
            <td>Rp ${formatNumber(item.subtotal)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    updateSummary();
}

document.getElementById('addItemBtn').addEventListener('click', function() {
    const productSelect = document.getElementById('productSelect');
    const quantity = parseInt(document.getElementById('quantity').value);
    const productId = productSelect.value;

    if (!productId || quantity < 1) {
        alert('Pilih barang dan masukkan jumlah');
        return;
    }

    const product = products[productId];
    if (product.stock < quantity) {
        alert('Stok tidak mencukupi');
        return;
    }

    const existingIndex = items.findIndex(item => item.product_id === parseInt(productId));
    if (existingIndex > -1) {
        items[existingIndex].quantity += quantity;
        items[existingIndex].subtotal = items[existingIndex].quantity * items[existingIndex].unit_price;
    } else {
        items.push({
            product_id: parseInt(productId),
            product_name: product.name,
            unit_price: product.price,
            quantity: quantity,
            subtotal: product.price * quantity,
        });
    }

    productSelect.value = '';
    document.getElementById('quantity').value = '1';
    renderItems();
});

function removeItem(index) {
    items.splice(index, 1);
    renderItems();
}

memberSelect.addEventListener('change', updateSummary);
</script>
@endsection