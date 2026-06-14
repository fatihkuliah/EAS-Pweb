const checkout = MieME.read(MieME.keys.checkout, null);
const payment = MieME.read(MieME.keys.payment, null);
const receiptInput = document.querySelector('#receiptInput');
const receiptPreview = document.querySelector('#receiptPreview');
const uploadText = document.querySelector('#uploadText');
const uploadForm = document.querySelector('#uploadForm');
const successBox = document.querySelector('#successBox');
let receipt = '';

if (!checkout || !payment) window.location.href = 'order';

document.querySelector('#uploadTotal').innerHTML = MieME.rupiah(checkout?.total || 0);
document.querySelector('#paymentMethod').innerHTML = payment?.nama || 'Metode Pembayaran';

receiptInput.addEventListener('change', () => {
  const file = receiptInput.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = () => {
    receipt = reader.result;
    receiptPreview.src = receipt;
    receiptPreview.classList.remove('d-none');
    uploadText.innerHTML = 'Ganti bukti pembayaran';
  };
  reader.readAsDataURL(file);
});

uploadForm.addEventListener('submit', (event) => {
  event.preventDefault();
  if (!receipt) return;
  MieME.orderFromCheckout(receipt);
  uploadForm.classList.add('d-none');
  successBox.classList.remove('d-none');
});
