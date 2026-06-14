const qrisTotal = document.querySelector('#qrisTotal');
const checkout = MieME.read(MieME.keys.checkout, { total: 0 });

function rupiah(angka) {
  return MieME.rupiah(angka);
}

qrisTotal.innerHTML = rupiah(checkout.total || 0);
