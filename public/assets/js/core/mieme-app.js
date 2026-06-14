const MieME = (() => {
  const menus = [
    { id: 1, nama: 'Mie Spesial Sambal Matah', kategori: 'Makanan', deskripsi: 'Mie signature dengan sambal matah segar dan racikan rempah khas MieME.', gambar: 'assets/images/mie1.png', harga: 28000, porsi: '1 Orang', waktu: '25 Menit' },
    { id: 2, nama: 'Mie Signature', kategori: 'Makanan', deskripsi: 'Menu andalan dengan rasa autentik, gurih, dan tekstur mie yang lembut.', gambar: 'assets/images/mi2.png', harga: 28000, porsi: '1 Orang', waktu: '25 Menit' },
    { id: 3, nama: 'Mie Goreng Topping Istimewah', kategori: 'Makanan', deskripsi: 'Mie goreng lengkap dengan topping spesial untuk rasa yang lebih mantap.', gambar: 'assets/images/mi3.png', harga: 30000, porsi: '1 Orang', waktu: '25 Menit' },
    { id: 4, nama: 'Mie Kuah Udang Spesial', kategori: 'Makanan', deskripsi: 'Mie kuah gurih dengan udang segar dan aroma laut yang menggugah selera.', gambar: 'assets/images/mi-udang.png', harga: 35000, porsi: '1 Orang', waktu: '30 Menit' },
    { id: 5, nama: 'Mie Kuah Spesial', kategori: 'Makanan', deskripsi: 'Mie kuah hangat dengan bumbu spesial yang cocok dinikmati kapan saja.', gambar: 'assets/images/mi-kuah-s.png', harga: 28000, porsi: '1 Orang', waktu: '25 Menit' },
    { id: 6, nama: 'Es Teh', kategori: 'Minuman', deskripsi: 'Teh dingin menyegarkan untuk menemani setiap menu MieME.', gambar: 'assets/images/esteh.png', harga: 8000, porsi: '1 Gelas', waktu: '5 Menit' },
    { id: 7, nama: 'Es Buah Segar', kategori: 'Minuman', deskripsi: 'Potongan buah segar dengan kuah manis dingin yang menyegarkan.', gambar: 'assets/images/esbuah.png', harga: 15000, porsi: '1 Gelas', waktu: '5 Menit' },
    { id: 8, nama: 'Es Jeruk', kategori: 'Minuman', deskripsi: 'Jeruk segar dingin dengan rasa manis dan asam yang pas.', gambar: 'assets/images/esjeruk.png', harga: 10000, porsi: '1 Gelas', waktu: '5 Menit' },
  ];

  const keys = {
    user: 'miemeUser',
    cart: 'miemeCart',
    checkout: 'miemeCheckout',
    payment: 'miemePayment',
    orders: 'miemeOrders',
    favorites: 'miemeFavorites',
    reviews: 'miemeReviews',
  };

  const read = (key, fallback) => JSON.parse(localStorage.getItem(key) || JSON.stringify(fallback));
  const write = (key, value) => localStorage.setItem(key, JSON.stringify(value));
  const rupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka || 0);
  const totalCart = (items = cart()) => items.reduce((sum, item) => sum + item.harga * item.qty, 0);
  const cart = () => read(keys.cart, []);
  const favorites = () => read(keys.favorites, []);
  const orders = () => read(keys.orders, seedOrders());
  const user = () => read(keys.user, null);
  const getMenu = (id) => menus.find((item) => item.id == id);

  function seedOrders() {
    return [
      {
        id: 'ORD-20260601-001',
        tanggal: '2026-06-01T10:30:00.000Z',
        items: [{ ...menus[0], qty: 2 }, { ...menus[5], qty: 2 }],
        total: 72000,
        status: 'Selesai',
        penerima: 'Budi Santoso',
        telepon: '081234567890',
        alamat: 'Jl. Jendral Sudirman No. 1, Jakarta',
        catatan: 'Sambal dipisah.',
        metode: 'QRIS',
      },
    ];
  }

  function addCart(id, qty = 1) {
    const item = getMenu(id);
    const items = cart();
    const exists = items.find((cartItem) => cartItem.id == id);
    if (exists) exists.qty += qty;
    else items.push({ ...item, qty });
    write(keys.cart, items);
  }

  function updateCart(id, qty) {
    const items = cart().map((item) => (item.id == id ? { ...item, qty } : item)).filter((item) => item.qty > 0);
    write(keys.cart, items);
  }

  function toggleFavorite(id) {
    const current = favorites();
    const next = current.includes(Number(id)) ? current.filter((item) => item != id) : [...current, Number(id)];
    write(keys.favorites, next);
    return next;
  }

  function orderFromCheckout(receipt = '') {
    const checkout = read(keys.checkout, null);
    const payment = read(keys.payment, null);
    if (!checkout || !payment) return null;
    const order = {
      id: `ORD-${new Date().toISOString().replace(/\D/g, '').slice(0, 14)}`,
      tanggal: new Date().toISOString(),
      items: checkout.items,
      total: checkout.total,
      status: 'Menunggu Pembayaran',
      penerima: checkout.penerima,
      telepon: checkout.telepon,
      alamat: checkout.alamat,
      catatan: checkout.catatan,
      metode: payment.nama,
      bukti: receipt,
    };
    write(keys.orders, [order, ...orders()]);
    write(keys.cart, []);
    localStorage.removeItem(keys.checkout);
    localStorage.removeItem(keys.payment);
    return order;
  }

  function saveReview(orderId, review) {
    const reviews = read(keys.reviews, []);
    write(keys.reviews, [{ orderId, tanggal: new Date().toISOString(), ...review }, ...reviews]);
  }

  return { keys, menus, rupiah, cart, favorites, orders, user, read, write, getMenu, totalCart, addCart, updateCart, toggleFavorite, orderFromCheckout, saveReview };
})();
