const ordersList = document.querySelector('#ordersList');
const exportCsv = document.querySelector('#exportCsv');
const orders = MieME.orders();

function renderOrders() {
  if (!orders.length) {
    ordersList.innerHTML = '<div class="detail-box text-center"><h1>Belum ada pesanan</h1><p>Silakan pilih menu favorit anda dahulu.</p></div>';
    return;
  }

  ordersList.innerHTML = orders
    .map(
      (order) => `
        <div class="order-history">
          <div>
            <p class="m-0 form-note">${new Date(order.tanggal).toLocaleString('id-ID')}</p>
            <h1>${order.id}</h1>
            <p>${order.items.map((item) => `${item.nama} (${item.qty})`).join(', ')}</p>
            <span class="status-pill">${order.status}</span>
          </div>
          <div class="history-action">
            <h2>${MieME.rupiah(order.total)}</h2>
            <a href="detail-order?id=${order.id}" class="order-back text-decoration-none">Detail</a>
            <button onclick="invoice('${order.id}')" class="order-back">Invoice</button>
            ${order.status == 'Selesai' ? `<a href="review?id=${order.id}" class="order-back text-decoration-none">Review</a>` : ''}
          </div>
        </div>
      `
    )
    .join('');
}

function invoice(id) {
  const order = orders.find((item) => item.id == id);
  const lines = [
    'INVOICE MIEME',
    `Nomor: ${order.id}`,
    `Tanggal: ${new Date(order.tanggal).toLocaleString('id-ID')}`,
    `Penerima: ${order.penerima}`,
    '',
    ...order.items.map((item) => `${item.nama} x ${item.qty} = ${MieME.rupiah(item.harga * item.qty)}`),
    '',
    `Total: ${MieME.rupiah(order.total)}`,
    `Status: ${order.status}`,
  ];
  const blob = new Blob([lines.join('\n')], { type: 'text/plain' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = `${order.id}-invoice.txt`;
  link.click();
}

exportCsv.addEventListener('click', () => {
  const header = 'Tanggal,Nomor Pesanan,Total Item,Total Pembayaran,Status\n';
  const rows = orders
    .map((order) => [new Date(order.tanggal).toLocaleString('id-ID'), order.id, order.items.reduce((sum, item) => sum + item.qty, 0), order.total, order.status].join(','))
    .join('\n');
  const link = document.createElement('a');
  link.href = encodeURI(`data:text/csv;charset=utf-8,${header}${rows}`);
  link.download = 'riwayat_pesanan_mieme.csv';
  link.click();
});

renderOrders();
