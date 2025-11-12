<x-app-layout>
    <style>
@media print {
    body * {
        visibility: hidden;
    }
    #receipt, #receipt * {
        visibility: visible;
    }
    #receipt {
        width: 80mm;
        margin: 0;
        padding: 0;
    }
}
</style>

    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">🛒 New POS Sale</h2>

            <form id="posForm" method="POST" action="{{ route('pos.sales.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm">Product</label>
                        <select id="productSelect" class="w-full border rounded p-2">
                            <option value="">-- Select Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                    {{ $product->name }} — Rs. {{ number_format($product->price,2) }} (Stock: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm">Price</label>
                        <input id="price" class="w-full border rounded p-2" readonly>
                    </div>

                    <div>
                        <label class="block text-sm">Qty</label>
                        <input id="quantity" type="number" min="1" value="1" class="w-full border rounded p-2">
                    </div>

                    <div class="flex items-end">
                        <button id="addToCart" type="button" class="w-full bg-indigo-600 text-white rounded p-2">➕ Add</button>
                    </div>
                </div>

                <table class="w-full border mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Product</th>
                            <th class="px-3 py-2 text-right">Price</th>
                            <th class="px-3 py-2 text-right">Qty</th>
                            <th class="px-3 py-2 text-right">Subtotal</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody"></tbody>
                </table>

                <div class="flex justify-end mb-4">
                    <div class="w-full md:w-1/3 bg-gray-50 p-3 rounded">
                        <div class="flex justify-between"><span>Subtotal</span><strong id="subtotal">0.00</strong></div>
                        <div class="flex justify-between"><span>Tax (0%)</span><strong id="tax">0.00</strong></div>
                        <div class="flex justify-between text-lg font-semibold"><span>Total</span><strong id="total">0.00</strong></div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div>
                        <label class="block text-sm">Payment Method</label>
                        <select name="payment_method" id="paymentMethod" class="border rounded p-2">
                            <option value="Cash">Cash</option>
                            <option value="EasyPaisa">EasyPaisa</option>
                            <option value="JazzCash">JazzCash</option>
                            <option value="Card">Card</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button id="clearCart" type="button" class="px-4 py-2 bg-gray-400 text-white rounded">Clear</button>
                        <button id="submitSale" type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Complete & Print</button>
                    </div>
                </div>
            </form>
            <div id="receipt" class="hidden print:block p-4 text-sm font-mono">
    <h2 class="text-center font-bold text-lg mb-2">🏋️ Gym POS Receipt</h2>
    <div id="receipt-body"></div>
    <p class="text-center mt-2">Thank you!</p>
</div>

        </div>
    </div>

    <!-- Receipt modal omitted for brevity; you can reuse previous receipt modal -->
    <script>
        const productSelect = document.getElementById('productSelect');
        const price = document.getElementById('price');
        const qty = document.getElementById('quantity');
        const addToCart = document.getElementById('addToCart');
        const cartBody = document.getElementById('cartBody');
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');
        const clearCart = document.getElementById('clearCart');
        const posForm = document.getElementById('posForm');

        let cart = [];

        productSelect.addEventListener('change', () => {
            const opt = productSelect.selectedOptions[0];
            price.value = opt ? opt.dataset.price : '';
        });

        addToCart.addEventListener('click', () => {
            const opt = productSelect.selectedOptions[0];
            if (!opt) return alert('Select product');
            const productId = parseInt(opt.value);
            const name = opt.text.split(' — ')[0];
            const p = parseFloat(opt.dataset.price);
            const stock = parseInt(opt.dataset.stock || 0);
            const q = parseInt(qty.value || 1);
            if (q < 1) return alert('Invalid quantity');
            if (q > stock) return alert('Not enough stock');

            const existing = cart.find(i => i.productId === productId);
            if (existing) {
                existing.qty += q;
                existing.subtotal = existing.price * existing.qty;
            } else {
                cart.push({ productId, name, price: p, qty: q, subtotal: p * q });
            }
            renderCart();
        });

        function renderCart() {
            cartBody.innerHTML = '';
            let sub = 0;
            cart.forEach((it, idx) => {
                sub += it.subtotal;
                cartBody.innerHTML += `
                <tr class="border-t">
                    <td class="px-3 py-2">${it.name}</td>
                    <td class="px-3 py-2 text-right">${it.price.toFixed(2)}</td>
                    <td class="px-3 py-2 text-right">${it.qty}</td>
                    <td class="px-3 py-2 text-right">${it.subtotal.toFixed(2)}</td>
                    <td class="px-3 py-2 text-center"><button onclick="removeItem(${idx})" class="text-red-500">x</button></td>
                </tr>`;
            });
            const tax = 0;
            const total = +(sub + tax).toFixed(2);
            subtotalEl.textContent = sub.toFixed(2);
            taxEl.textContent = tax.toFixed(2);
            totalEl.textContent = total.toFixed(2);
        }

        window.removeItem = function(index) {
            cart.splice(index,1);
            renderCart();
        }

        clearCart.addEventListener('click', () => {
            cart = [];
            renderCart();
        });

        // submit: add hidden input 'cart' and let controller handle
posForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    if (cart.length === 0) return alert('Cart empty');

    const csrfToken = document.querySelector('input[name="_token"]').value;
    const paymentMethod = document.getElementById('paymentMethod').value;

    try {
        const res = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                payment_method: paymentMethod,
                cart: cart
            })
        });

        const data = await res.json();

        if (data.status === 'success') {
            // 🧾 Show receipt and print
            showReceipt(data.sale);
            // clear cart
            cart = [];
            renderCart();
        } else {
            alert('❌ Error: ' + (data.message || 'Failed to save sale'));
        }
    } catch (err) {
        console.error(err);
        alert('❌ Something went wrong');
    }
});
    </script>
    <script>
function showReceipt(sale) {
    const receipt = document.getElementById('receipt');
    const body = document.getElementById('receipt-body');

    let html = `
        <div>Date: ${new Date(sale.created_at).toLocaleString()}</div>
        <div>Invoice: ${sale.invoice_no}</div>
        <hr class="my-2 border-gray-300">
        <table class="w-full text-xs">
            <thead>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>`;

    sale.items.forEach(it => {
        html += `
            <tr>
                <td>${it.product.name}</td>
                <td class="text-right">${it.quantity}</td>
                <td class="text-right">${it.subtotal.toFixed(2)}</td>
            </tr>`;
    });

    html += `
            </tbody>
        </table>
        <hr class="my-2 border-gray-300">
        <div class="flex justify-between"><span>Subtotal:</span><span>${sale.subtotal.toFixed(2)}</span></div>
        <div class="flex justify-between"><span>Tax:</span><span>${sale.tax.toFixed(2)}</span></div>
        <div class="flex justify-between font-bold"><span>Total:</span><span>${sale.total.toFixed(2)}</span></div>
    `;

    body.innerHTML = html;
    receipt.classList.remove('hidden');

    // 🖨️ Trigger print
    setTimeout(() => window.print(), 500);
}
</script>
<script>
    
document.getElementById('submitSale').addEventListener('click', function(e) {
    e.preventDefault();

    if (cart.length === 0) {
        alert('Cart empty');
        return;
    }

    const paymentMethod = document.getElementById('paymentMethod').value;
    const csrfToken = document.querySelector('input[name="_token"]').value;

    fetch('{{ route('pos.sales.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            payment_method: paymentMethod,
            cart: cart
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Response:', data);

        if (data.status !== 'success') {
            alert('❌ Something went wrong: ' + (data.message || 'Unknown error'));
            return;
        }

        const sale = data.sale;

        // ✅ Optional: Show Receipt Popup or Auto-Print
        let receiptWindow = window.open('', 'PRINT', 'height=600,width=400');
        receiptWindow.document.write(`
            <html>
                <head><title>Invoice ${sale.invoice_no}</title></head>
                <body style="font-family:monospace; font-size:14px">
                    <center><b>C4 Fitness Gym</b></center>
                    <p><b>Invoice:</b> ${sale.invoice_no}<br>
                    <b>Date:</b> ${new Date(sale.created_at).toLocaleString()}<br>
                    <b>Payment:</b> ${sale.payment_method}</p>
                    <hr>
                    <table width="100%">
                        <tr><th align="left">Item</th><th align="right">Qty</th><th align="right">Total</th></tr>
                        ${sale.items.map(i => `
                            <tr>
                                <td>${i.product.name}</td>
                                <td align="right">${i.quantity}</td>
                                <td align="right">${parseFloat(i.subtotal).toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    </table>
                    <hr>
                    <p>
                        Subtotal: Rs. ${parseFloat(sale.subtotal).toFixed(2)}<br>
                        Tax: Rs. ${parseFloat(sale.tax).toFixed(2)}<br>
                        <b>Total: Rs. ${parseFloat(sale.total).toFixed(2)}</b>
                    </p>
                    <hr>
                    <p style="text-align:center;">Thank you!</p>
                    <p style="text-align:center;"><b>Software by 0301-6228258</b></p>
                    <script>window.print();<\/script>
                </body>
            </html>
        `);
        receiptWindow.document.close();

        // ✅ Reset cart
        cart = [];
        
        renderCart();
    })
    .catch(err => {
        console.error(err);
        alert('❌ Error: ' + err.message);
    });
});
</script>


</x-app-layout>
