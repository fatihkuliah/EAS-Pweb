const detailList = document.querySelector('#detailList');
const detailTotal = document.querySelector('#detailTotal');
const bayarBtn = document.querySelector('#bayarBtn');

const orderId = new URLSearchParams(window.location.search).get('id');
const selectedOrder = orderId ? MieME.orders().find((order) => order.id == orderId) : null;
const checkout = selectedOrder || JSON.parse(localStorage.getItem('miemeCheckout')) || {
  items: JSON.parse(localStorage.getItem('miemeCart')) || [],
  total: 0,
};

function rupiah(angka) {
  return MieME.rupiah(angka);
}

const total = checkout.items.reduce((hasil, item) => hasil + item.harga * item.qty, 0);

if (checkout.items.length == 0) {
  detailList.innerHTML = '<p class="m-0 detail-empty">Belum ada pesanan. Silakan pilih menu dahulu.</p>';
  bayarBtn.disabled = true;
} else {
  detailList.innerHTML = checkout.items
    .map(
      (item) => `
        <div class="detail-item">
          <img src="${item.gambar}" alt="${item.nama}" />
          <div>
            <h1>${item.nama}</h1>
            <p class="m-0">${rupiah(item.harga)} x ${item.qty}</p>
          </div>
          <h2>${rupiah(item.harga * item.qty)}</h2>
        </div>
      `
    )
    .join('');

  if (selectedOrder) {
    detailList.innerHTML += `
      <div class="order-meta">
        <div><p>Status</p><h2>${selectedOrder.status}</h2></div>
        <div><p>Metode</p><h2>${selectedOrder.metode || '-'}</h2></div>
        <div><p>Penerima</p><h2>${selectedOrder.penerima || '-'}</h2></div>
        <div><p>Telepon</p><h2>${selectedOrder.telepon || '-'}</h2></div>
        <div class="wide"><p>Alamat</p><h2>${selectedOrder.alamat || '-'}</h2></div>
        <div class="wide"><p>Catatan</p><h2>${selectedOrder.catatan || '-'}</h2></div>
        ${
          selectedOrder.bukti
            ? `<div class="wide">
                <p>Bukti Pembayaran</p>
                <a href="${(window.MieMEDatabaseData?.storageUrl || 'storage/') + selectedOrder.bukti}" target="_blank" class="d-block rounded-3 overflow-hidden" style="max-height: 250px;">
                  <img class="receipt-img" src="${(window.MieMEDatabaseData?.storageUrl || 'storage/') + selectedOrder.bukti}" alt="Bukti pembayaran" style="width: 100%; object-fit: contain;" />
                </a>
               </div>`
            : ''
        }
      </div>
    `;
    bayarBtn.innerHTML = 'Kembali Riwayat';
  }
}

detailTotal.innerHTML = rupiah(total);

bayarBtn.addEventListener('click', () => {
  localStorage.setItem('miemeCheckout', JSON.stringify({ ...checkout, total }));
  if (selectedOrder) {
    window.location.href = 'orders';
    return;
  }
  window.location.href = 'payment';
});
