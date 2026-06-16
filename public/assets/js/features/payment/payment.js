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
const databaseMethods = window.MieMEPaymentMethods || window.MieMEDatabaseData?.methods || {};

const metodeBank = databaseMethods['Bank Transfer'] || [
  { nama: 'BCA', logo: 'assets/images/payment/Bank_Central_Asia.svg', category: 'Bank Transfer', number_label: 'No. Rekening', number: '1234567890', account_name: 'MieME Indonesia' },
  { nama: 'BRI', logo: 'assets/images/payment/BANK_BRI_logo.svg', category: 'Bank Transfer', number_label: 'No. Rekening', number: '112233445566', account_name: 'MieME Indonesia' },
  { nama: 'BNI', logo: 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg', category: 'Bank Transfer', number_label: 'No. Rekening', number: '0099887766', account_name: 'MieME Indonesia' },
  { nama: 'Mandiri', logo: 'assets/images/payment/Bank_Mandiri_logo_2016.svg', category: 'Bank Transfer', number_label: 'No. Rekening', number: '9000012345678', account_name: 'MieME Indonesia' },
];

const metodeEwallet = databaseMethods['E-Wallet'] || [
  { nama: 'GoPay', logo: 'assets/images/payment/Gopay_logo.svg', category: 'E-Wallet', number_label: 'Nomor E-Wallet', number: '081234567890', account_name: 'MieME Indonesia' },
  { nama: 'DANA', logo: 'assets/images/payment/Logo_dana_blue.svg', category: 'E-Wallet', number_label: 'Nomor E-Wallet', number: '081234567891', account_name: 'MieME Indonesia' },
  { nama: 'OVO', logo: 'assets/images/payment/Logo_ovo_purple.svg', category: 'E-Wallet', number_label: 'Nomor E-Wallet', number: '081234567892', account_name: 'MieME Indonesia' },
  { nama: 'LinkAja', logo: 'assets/images/payment/LinkAja.svg', category: 'E-Wallet', number_label: 'Nomor E-Wallet', number: '081234567893', account_name: 'MieME Indonesia' },
];

const metodeVa = databaseMethods['Virtual Account'] || [
  { nama: 'VA BCA', logo: 'assets/images/payment/Bank_Central_Asia.svg', category: 'Virtual Account', number_label: 'Nomor Virtual Account', number: '88081234567890', account_name: 'MieME Indonesia' },
  { nama: 'VA BRI', logo: 'assets/images/payment/BANK_BRI_logo.svg', category: 'Virtual Account', number_label: 'Nomor Virtual Account', number: '26215081234567890', account_name: 'MieME Indonesia' },
  { nama: 'VA BNI', logo: 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg', category: 'Virtual Account', number_label: 'Nomor Virtual Account', number: '988081234567890', account_name: 'MieME Indonesia' },
  { nama: 'VA Mandiri', logo: 'assets/images/payment/Bank_Mandiri_logo_2016.svg', category: 'Virtual Account', number_label: 'Nomor Virtual Account', number: '8950801234567890', account_name: 'MieME Indonesia' },
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
          <img class="img-fluid w-50" src="${MieME.assetUrl(item.logo)}" alt="${item.nama}" />
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
  const item = [...metodeBank, ...metodeEwallet, ...metodeVa, ...metodeQris].find((method) => method.nama === nama) || { nama };
  const formData = new FormData();
  formData.append('method', nama);

  fetch('payment/select', { method: 'POST', body: formData })
    .then(() => {
      MieME.write(MieME.keys.payment, item);
      window.location.href = link;
    });
}
