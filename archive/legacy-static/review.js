const orderId = new URLSearchParams(window.location.search).get('id');
const order = MieME.orders().find((item) => item.id == orderId);
const ratingRow = document.querySelector('#ratingRow');
const reviewForm = document.querySelector('#reviewForm');
const reviewSuccess = document.querySelector('#reviewSuccess');
let rating = 5;

if (!order || order.status != 'Selesai') {
  window.location.href = 'orders.html';
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
  MieME.saveReview(order.id, {
    rating,
    komentar: document.querySelector('#reviewComment').value.trim(),
  });
  reviewForm.classList.add('d-none');
  reviewSuccess.classList.remove('d-none');
});

renderRating();
