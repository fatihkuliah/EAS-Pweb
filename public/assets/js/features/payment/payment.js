const bankList = document.querySelector('#bankList');
const ewalletList = document.querySelector('#ewalletList');
const vaList = document.querySelector('#vaList');
const qrisList = document.querySelector('#qrisList');
const paymentTotal = document.querySelector('#paymentTotal');

const checkout = MieME.read(MieME.keys.checkout, {
  items: [],
  total: 0,
});

// Since bank payment methods are dynamically populated from the database by PHP, 
// we will grab them from database arrays or fall back to standard local lists.
const databaseMethods = window.MieMEDatabaseData?.methods || {};

const metodeBank = databaseMethods['Bank Transfer'] || [
  { nama: 'BCA', logo: 'assets/images/payment/Bank_Central_Asia.svg' },
  { nama: 'BRI', logo: 'assets/images/payment/BANK_BRI_logo.svg' },
  { nama: 'BNI', logo: 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg' },
  { nama: 'Mandiri', logo: 'assets/images/payment/Bank_Mandiri_logo_2016.svg' },
];

const metodeEwallet = databaseMethods['E-Wallet'] || [
  { nama: 'GoPay', logo: 'assets/images/payment/Gopay_logo.svg' },
  { nama: 'DANA', logo: 'assets/images/payment/Logo_dana_blue.svg' },
  { nama: 'OVO', logo: 'assets/images/payment/Logo_ovo_purple.svg' },
  { nama: 'LinkAja', logo: 'assets/images/payment/LinkAja.svg' },
];

const metodeVa = databaseMethods['Virtual Account'] || [
  { nama: 'VA BCA', logo: 'assets/images/payment/Bank_Central_Asia.svg' },
  { nama: 'VA BRI', logo: 'assets/images/payment/BANK_BRI_logo.svg' },
  { nama: 'VA BNI', logo: 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg' },
  { nama: 'VA Mandiri', logo: 'assets/images/payment/Bank_Mandiri_logo_2016.svg' },
];

const metodeQris = databaseMethods['QR Payment'] || [{ nama: 'QRIS', logo: 'assets/images/payment/Logo_QRIS.svg' }];

function rupiah(angka) {
  return MieME.rupiah(angka);
}

function renderPayment(target, data) {
  target.innerHTML = data
    .map(
      (item) => `
        <button class="payment-card" onclick="pilihPayment('${item.nama}', '${item.nama === 'QRIS' ? 'qris' : 'upload-payment'}')">
          <img class="img-fluid w-50" src="${item.logo}" alt="${item.nama}" />
          <span class="fs-4">${item.nama}</span>
        </button>
      `
    )
    .join('');
}

paymentTotal.innerHTML = rupiah(checkout.total || 0);
renderPayment(bankList, metodeBank);
renderPayment(ewalletList, metodeEwallet);
renderPayment(vaList, metodeVa);
renderPayment(qrisList, metodeQris);

function pilihPayment(nama, link) {
  const formData = new FormData();
  formData.append('method', nama);

  fetch('payment/select', { method: 'POST', body: formData })
    .then(() => {
      MieME.write(MieME.keys.payment, { nama });
      window.location.href = link;
    });
}
