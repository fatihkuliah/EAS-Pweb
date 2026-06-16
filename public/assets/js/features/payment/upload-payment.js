const checkout = MieME.read(MieME.keys.checkout, null);
const storedPayment = MieME.read(MieME.keys.payment, null);
const payment = { ...(window.MieMECurrentPayment || {}), ...(storedPayment || {}) };
const receiptInput = document.querySelector('#receiptInput');
const receiptPreview = document.querySelector('#receiptPreview');
const uploadText = document.querySelector('#uploadText');
const uploadForm = document.querySelector('#uploadForm');
const successBox = document.querySelector('#successBox');
let receipt = '';

if (!checkout || !payment?.nama) window.location.href = 'order';

document.querySelector('#uploadTotal').innerHTML = MieME.rupiah(checkout?.total || 0);
document.querySelector('#paymentMethod').innerHTML = payment?.nama || 'Metode Pembayaran';

const paymentTarget = document.querySelector('#paymentTarget');
const paymentNumberLabel = document.querySelector('#paymentNumberLabel');
const paymentNumber = document.querySelector('#paymentNumber');
const paymentAccountName = document.querySelector('#paymentAccountName');

if (payment?.number) {
  paymentNumberLabel.innerHTML = payment.number_label || 'Nomor Tujuan';
  paymentNumber.innerHTML = payment.number;
  paymentAccountName.innerHTML = payment.account_name || 'MieME Indonesia';
} else {
  paymentTarget.classList.add('d-none');
}

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
  const file = receiptInput.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('receipt', file);

  fetch('upload-payment', { method: 'POST', body: formData })
    .then((response) => {
      if (response.ok) {
        localStorage.removeItem(MieME.keys.checkout);
        localStorage.removeItem(MieME.keys.payment);
        localStorage.removeItem(MieME.keys.cart);
        
        uploadForm.classList.add('d-none');
        successBox.classList.remove('d-none');
      } else {
        alert('Gagal mengunggah bukti pembayaran.');
      }
    });
});
