const MieME = (() => {
  const menus = window.MieMEDatabaseData?.menus || [];

  const keys = {
    user: 'miemeUser',
    cart: 'miemeCart',
    checkout: 'miemeCheckout',
    payment: 'miemePayment',
    orders: 'miemeOrders',
    favorites: 'miemeFavorites',
    reviews: 'miemeReviews',
  };

  const read = (key, fallback) => {
    if (window.MieMEDatabaseData) {
      if (key === keys.user) return window.MieMEDatabaseData.user;
      if (key === keys.cart) return window.MieMEDatabaseData.cart || [];
      if (key === keys.orders) return window.MieMEDatabaseData.orders || [];
      if (key === keys.favorites) return window.MieMEDatabaseData.favorites || [];
    }
    return JSON.parse(localStorage.getItem(key) || JSON.stringify(fallback));
  };

  const write = (key, value) => localStorage.setItem(key, JSON.stringify(value));
  const rupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka || 0);
  const totalCart = (items = cart()) => items.reduce((sum, item) => sum + item.harga * item.qty, 0);
  const cart = () => read(keys.cart, []);
  const favorites = () => read(keys.favorites, []);
  const orders = () => read(keys.orders, []);
  const user = () => read(keys.user, null);
  const getMenu = (id) => menus.find((item) => item.id == id);

  function addCart(id, qty = 1) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('qty', qty);
    return fetch('cart/add', { 
      method: 'POST', 
      headers: { 'Accept': 'application/json' },
      body: formData 
    })
      .then((res) => res.json())
      .then((data) => {
        if (window.MieMEDatabaseData) {
          window.MieMEDatabaseData.cart = data.cart;
          window.MieMEDatabaseData.cartTotal = data.total;
        }
        return data.cart;
      });
  }

  function updateCart(id, qty) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('qty', qty);
    return fetch('cart/update', { 
      method: 'POST', 
      headers: { 'Accept': 'application/json' },
      body: formData 
    })
      .then((res) => res.json())
      .then((data) => {
        if (window.MieMEDatabaseData) {
          window.MieMEDatabaseData.cart = data.cart;
          window.MieMEDatabaseData.cartTotal = data.total;
        }
        return data.cart;
      });
  }

  function toggleFavorite(id) {
    const formData = new FormData();
    formData.append('id', id);
    return fetch('favorite/toggle', { 
      method: 'POST', 
      headers: { 'Accept': 'application/json' },
      body: formData 
    })
      .then((res) => res.json())
      .then((data) => {
        if (window.MieMEDatabaseData) {
          window.MieMEDatabaseData.favorites = data.favorites;
        }
        return data.favorites;
      });
  }

  function orderFromCheckout(receipt = '') {
    return null;
  }

  function saveReview(orderId, review) {
    // Handled in review.js submit handler
  }

  return { keys, menus, rupiah, cart, favorites, orders, user, read, write, getMenu, totalCart, addCart, updateCart, toggleFavorite, orderFromCheckout, saveReview };
})();
