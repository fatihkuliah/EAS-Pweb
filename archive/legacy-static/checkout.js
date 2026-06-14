const cart = MieME.cart();
const user = MieME.user();
const checkoutItems = document.querySelector('#checkoutItems');
const checkoutTotal = document.querySelector('#checkoutTotal');
const checkoutForm = document.querySelector('#checkoutForm');

if (!cart.length) window.location.href = 'order.html';

document.querySelector('#penerima').value = user?.nama || '';
document.querySelector('#telepon').value = user?.telepon || '';
document.querySelector('#alamat').value = user?.alamat || '';

checkoutItems.innerHTML = cart
  .map(
    (item) => `
      <div class="summary-line">
        <span>${item.nama}<small>${item.qty} x ${MieME.rupiah(item.harga)}</small></span>
        <b>${MieME.rupiah(item.harga * item.qty)}</b>
      </div>
    `
  )
  .join('');
checkoutTotal.innerHTML = MieME.rupiah(MieME.totalCart(cart));

checkoutForm.addEventListener('submit', (event) => {
  event.preventDefault();
  MieME.write(MieME.keys.checkout, {
    items: cart,
    total: MieME.totalCart(cart),
    penerima: document.querySelector('#penerima').value.trim(),
    telepon: document.querySelector('#telepon').value.trim(),
    alamat: document.querySelector('#alamat').value.trim(),
    catatan: document.querySelector('#catatan').value.trim(),
    tanggal: new Date().toISOString(),
  });
  window.location.href = 'payment.html';
});
