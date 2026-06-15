const orderId = new URLSearchParams(window.location.search).get('id');
const order = MieME.orders().find((item) => item.id == orderId);
const ratingRow = document.querySelector('#ratingRow');
const reviewForm = document.querySelector('#reviewForm');
const reviewSuccess = document.querySelector('#reviewSuccess');
let rating = 5;

if (!order || order.status != 'Selesai') {
  window.location.href = 'orders';
}

document.querySelector('#reviewOrder').innerHTML = order?.id || 'Nomor Pesanan';

function renderRating() {
  ratingRow.innerHTML = [1, 2, 3, 4, 5].map((star) => `<button type="button" onclick="setRating(${star})">${star <= rating ? '★' : '☆'}</button>`).join('');
}

function setRating(value) {
  rating = value;
  renderRating();
}

reviewForm.addEventListener('submit', (event) => {
  event.preventDefault();

  const formData = new FormData();
  formData.append('order_id', order.id);
  formData.append('rating', rating);
  formData.append('komentar', document.querySelector('#reviewComment').value.trim());

  fetch('review', { method: 'POST', body: formData })
    .then((response) => {
      if (response.ok) {
        reviewForm.classList.add('d-none');
        reviewSuccess.classList.remove('d-none');
      } else {
        alert('Gagal mengirim review.');
      }
    });
});

renderRating();
