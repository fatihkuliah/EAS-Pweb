<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class AdminController extends Controller
{
    public function __construct()
    {
        $user = \App\Models\User::current();
        if ($user === null) {
            redirect('auth');
        }

        if (($user['role'] ?? 'customer') !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden - Anda tidak memiliki akses ke halaman ini.";
            exit;
        }
    }

    public function index(): void
    {
        $db = Database::connect();
        
        // KPIs
        $kpi = [];
        
        // Orders Today
        $stmt = $db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURRENT_DATE");
        $kpi['total_orders_today'] = $stmt->fetchColumn();
        
        // Revenue Today
        $stmt = $db->query("SELECT SUM(total_price) FROM orders WHERE DATE(created_at) = CURRENT_DATE AND status != 'Batal'");
        $kpi['revenue_today'] = $stmt->fetchColumn() ?: 0;
        
        // Waiting Payment
        $stmt = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'Menunggu Pembayaran'");
        $kpi['waiting_payment'] = $stmt->fetchColumn();
        
        // Pending Payments
        $stmt = $db->query("SELECT COUNT(*) FROM payments WHERE payment_status = 'Menunggu Konfirmasi'");
        $kpi['pending_payments'] = $stmt->fetchColumn();
        
        // Processing Orders
        $stmt = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'Diproses'");
        $kpi['processing_orders'] = $stmt->fetchColumn();
        
        // Low Stock Menus
        $stmt = $db->query("SELECT COUNT(*) FROM menus WHERE stock < 10");
        $kpi['low_stock_menus'] = $stmt->fetchColumn();
        
        // Butuh Verifikasi Pembayaran (Pending Payments)
        $stmt = $db->query("
            SELECT p.payment_id, p.payment_method, p.amount, p.payment_proof, p.payment_status, 
                   o.order_number, u.name as customer_name
            FROM payments p
            JOIN orders o ON p.order_id = o.order_id
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE p.payment_status = 'Menunggu Konfirmasi'
            ORDER BY p.created_at ASC
        ");
        $pendingPayments = $stmt->fetchAll();
        
        // Recent Orders
        $stmt = $db->query("
            SELECT o.order_number, o.recipient_name as penerima, o.total_price as total, o.status, o.created_at
            FROM orders o
            ORDER BY o.created_at DESC
            LIMIT 5
        ");
        $recentOrders = $stmt->fetchAll();
        
        // Top Selling Menus
        $stmt = $db->query("
            SELECT m.menu_name, SUM(oi.quantity) as sold
            FROM order_items oi
            JOIN menus m ON oi.menu_id = m.menu_id
            GROUP BY oi.menu_id
            ORDER BY sold DESC
            LIMIT 5
        ");
        $topSelling = $stmt->fetchAll();
        
        // Latest Reviews
        $stmt = $db->query("
            SELECT r.rating, r.comment, r.created_at, r.review_id,
                   u.name as user_name, m.menu_name
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            JOIN menus m ON r.menu_id = m.menu_id
            ORDER BY r.created_at DESC
            LIMIT 5
        ");
        $latestReviews = $stmt->fetchAll();
        
        $this->view('admin/dashboard', [
            'kpi' => $kpi,
            'pendingPayments' => $pendingPayments,
            'recentOrders' => $recentOrders,
            'topSelling' => $topSelling,
            'latestReviews' => $latestReviews,
        ]);
    }

    public function orders(): void
    {
        $db = Database::connect();
        
        $search = trim((string) ($_GET['search'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        
        $query = "
            SELECT o.order_id, o.order_number, o.recipient_name as penerima, o.total_price as total, o.status, o.created_at,
                   u.name as user_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE 1=1
        ";
        $params = [];
        
        if ($search !== '') {
            $query .= " AND (o.order_number LIKE ? OR o.recipient_name LIKE ? OR u.name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status !== '') {
            $query .= " AND o.status = ?";
            $params[] = $status;
        }
        
        $query .= " ORDER BY o.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();
        
        $this->view('admin/orders', [
            'orders' => $orders,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ]
        ]);
    }

    public function orderDetail(): void
    {
        $orderNumber = trim((string) ($_GET['id'] ?? ''));
        if ($orderNumber === '') {
            flash('ID Pesanan tidak valid.', 'error');
            redirect('admin/orders');
        }
        
        $db = Database::connect();
        
        // Find order
        $stmt = $db->prepare("
            SELECT o.*, u.name as user_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE o.order_number = ?
        ");
        $stmt->execute([$orderNumber]);
        $order = $stmt->fetch();
        
        if (!$order) {
            flash('Pesanan tidak ditemukan.', 'error');
            redirect('admin/orders');
        }
        
        // Get items
        $stmt = $db->prepare("
            SELECT oi.quantity as qty, oi.price as harga, m.menu_name as nama, m.image as gambar, c.category_name as kategori
            FROM order_items oi
            JOIN menus m ON oi.menu_id = m.menu_id
            JOIN categories c ON m.category_id = c.category_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order['order_id']]);
        $items = $stmt->fetchAll();
        
        // Get payment
        $stmt = $db->prepare("
            SELECT payment_method as metode, payment_status, payment_proof as bukti
            FROM payments
            WHERE order_id = ?
            LIMIT 1
        ");
        $stmt->execute([$order['order_id']]);
        $payment = $stmt->fetch();
        
        $orderData = [
            'order_number' => $order['order_number'],
            'total' => $order['total_price'],
            'status' => $order['status'],
            'created_at' => $order['created_at'],
            'penerima' => $order['recipient_name'],
            'telepon' => $order['recipient_phone'],
            'alamat' => $order['shipping_address'],
            'catatan' => $order['note'],
            'items' => $items,
            'metode' => $payment['metode'] ?? 'Belum memilih',
            'payment_status' => $payment['payment_status'] ?? '',
            'bukti' => $payment['bukti'] ?? '',
        ];
        
        $this->view('admin/order_detail', [
            'order' => $orderData
        ]);
    }

    public function orderUpdate(): never
    {
        $orderNumber = trim((string) post('order_number'));
        $status = trim((string) post('status'));
        
        if ($orderNumber === '' || $status === '') {
            flash('Data update tidak lengkap.', 'error');
            redirect('admin/orders');
        }
        
        $db = Database::connect();
        
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE order_number = ?");
        $stmt->execute([$status, $orderNumber]);
        
        flash('Status pesanan berhasil diperbarui.');
        redirect('admin/orders/detail?id=' . $orderNumber);
    }

    public function payments(): void
    {
        $db = Database::connect();
        $status = trim((string) ($_GET['status'] ?? ''));
        
        $query = "
            SELECT p.payment_id, p.payment_method, p.amount, p.payment_proof, p.payment_status, p.paid_at,
                   o.order_number, u.name as customer_name
            FROM payments p
            JOIN orders o ON p.order_id = o.order_id
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE 1=1
        ";
        $params = [];
        
        if ($status !== '') {
            $query .= " AND p.payment_status = ?";
            $params[] = $status;
        } else {
            // Sort by pending first
            $query .= " ORDER BY CASE WHEN p.payment_status = 'Menunggu Konfirmasi' THEN 1 ELSE 2 END ASC, p.created_at DESC";
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $payments = $stmt->fetchAll();
        
        $this->view('admin/payments', [
            'payments' => $payments,
            'filters' => [
                'status' => $status
            ]
        ]);
    }

    public function paymentAction(): never
    {
        $paymentId = (int) post('payment_id');
        $action = trim((string) post('action'));
        
        if ($paymentId <= 0 || !in_array($action, ['approve', 'reject'], true)) {
            flash('Aksi pembayaran tidak valid.', 'error');
            redirect('admin/payments');
        }
        
        $db = Database::connect();
        
        // Get payment
        $stmt = $db->prepare("SELECT * FROM payments WHERE payment_id = ?");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch();
        
        if (!$payment) {
            flash('Data pembayaran tidak ditemukan.', 'error');
            redirect('admin/payments');
        }
        
        if ($action === 'approve') {
            // payment_status becomes verified
            // order status becomes paid
            $stmt = $db->prepare("UPDATE payments SET payment_status = 'verified', paid_at = CURRENT_TIMESTAMP WHERE payment_id = ?");
            $stmt->execute([$paymentId]);
            
            $stmt = $db->prepare("UPDATE orders SET status = 'paid' WHERE order_id = ?");
            $stmt->execute([$payment['order_id']]);
            
            flash('Pembayaran berhasil disetujui. Status pesanan berubah menjadi paid.');
        } else {
            // payment_status becomes rejected
            // order status returns to Menunggu Pembayaran
            $stmt = $db->prepare("UPDATE payments SET payment_status = 'rejected' WHERE payment_id = ?");
            $stmt->execute([$paymentId]);
            
            $stmt = $db->prepare("UPDATE orders SET status = 'Menunggu Pembayaran' WHERE order_id = ?");
            $stmt->execute([$payment['order_id']]);
            
            flash('Pembayaran berhasil ditolak. Status pesanan kembali menjadi Menunggu Pembayaran.');
        }
        
        redirect('admin/payments');
    }

    public function menus(): void
    {
        $db = Database::connect();
        
        $search = trim((string) ($_GET['search'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        
        $query = "
            SELECT m.menu_id as id, m.menu_name as nama, m.description as deskripsi, m.price as harga, 
                   m.stock, m.portion as porsi, m.serving_time as waktu, m.image as gambar, m.is_available,
                   c.category_name as kategori
            FROM menus m
            JOIN categories c ON m.category_id = c.category_id
            WHERE 1=1
        ";
        $params = [];
        
        if ($search !== '') {
            $query .= " AND m.menu_name LIKE ?";
            $params[] = "%$search%";
        }
        
        if ($category !== '') {
            $query .= " AND m.category_id = ?";
            $params[] = (int) $category;
        }
        
        $query .= " ORDER BY m.menu_id ASC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $menus = $stmt->fetchAll();
        
        // Categories list for filter
        $stmtCat = $db->query("SELECT * FROM categories ORDER BY category_name ASC");
        $categories = $stmtCat->fetchAll();
        
        $this->view('admin/menus', [
            'menus' => $menus,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $category,
            ]
        ]);
    }

    public function menuCreate(): void
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM categories ORDER BY category_name ASC");
        $categories = $stmt->fetchAll();
        
        $this->view('admin/menu_create', [
            'categories' => $categories
        ]);
    }

    public function menuStore(): never
    {
        $name = trim((string) post('name'));
        $categoryId = (int) post('category_id');
        $description = trim((string) post('description'));
        $price = (int) post('price');
        $stock = (int) post('stock');
        $portion = trim((string) post('portion', '1 Orang'));
        $servingTime = trim((string) post('serving_time', '25 Menit'));
        $isAvailable = (int) post('is_available', 1);
        
        if ($name === '' || $categoryId <= 0 || $price < 0 || $stock < 0) {
            flash('Mohon lengkapi seluruh field wajib dengan benar.', 'error');
            redirect('admin/menus/create');
        }
        
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = upload_file($_FILES['image'], 'menus', ['jpg', 'jpeg', 'png']);
        }
        
        if ($image === '') {
            flash('Gagal mengunggah gambar menu. Pastikan format file sesuai.', 'error');
            redirect('admin/menus/create');
        }
        
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO menus (menu_name, category_id, description, price, stock, portion, serving_time, image, is_available)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $categoryId, $description, $price, $stock, $portion, $servingTime, $image, $isAvailable]);
        
        flash('Menu baru berhasil ditambahkan.');
        redirect('admin/menus');
    }

    public function menuEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('ID Menu tidak valid.', 'error');
            redirect('admin/menus');
        }
        
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM menus WHERE menu_id = ?");
        $stmt->execute([$id]);
        $menu = $stmt->fetch();
        
        if (!$menu) {
            flash('Menu tidak ditemukan.', 'error');
            redirect('admin/menus');
        }
        
        $stmtCat = $db->query("SELECT * FROM categories ORDER BY category_name ASC");
        $categories = $stmtCat->fetchAll();
        
        $this->view('admin/menu_edit', [
            'menu' => $menu,
            'categories' => $categories
        ]);
    }

    public function menuUpdate(): never
    {
        $id = (int) post('menu_id');
        $name = trim((string) post('name'));
        $categoryId = (int) post('category_id');
        $description = trim((string) post('description'));
        $price = (int) post('price');
        $stock = (int) post('stock');
        $portion = trim((string) post('portion', '1 Orang'));
        $servingTime = trim((string) post('serving_time', '25 Menit'));
        $isAvailable = (int) post('is_available', 1);
        
        if ($id <= 0 || $name === '' || $categoryId <= 0 || $price < 0 || $stock < 0) {
            flash('Mohon lengkapi seluruh field wajib dengan benar.', 'error');
            redirect($id > 0 ? "admin/menus/edit?id=$id" : 'admin/menus');
        }
        
        $db = Database::connect();
        
        // Get existing menu to check image
        $stmt = $db->prepare("SELECT image FROM menus WHERE menu_id = ?");
        $stmt->execute([$id]);
        $menu = $stmt->fetch();
        
        if (!$menu) {
            flash('Menu tidak ditemukan.', 'error');
            redirect('admin/menus');
        }
        
        $image = $menu['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImage = upload_file($_FILES['image'], 'menus', ['jpg', 'jpeg', 'png']);
            if ($newImage !== '') {
                $image = $newImage;
            }
        }
        
        $stmt = $db->prepare("
            UPDATE menus 
            SET menu_name = ?, category_id = ?, description = ?, price = ?, stock = ?, portion = ?, serving_time = ?, image = ?, is_available = ?
            WHERE menu_id = ?
        ");
        $stmt->execute([$name, $categoryId, $description, $price, $stock, $portion, $servingTime, $image, $isAvailable, $id]);
        
        flash('Menu berhasil diperbarui.');
        redirect('admin/menus');
    }

    public function menuDelete(): never
    {
        $id = (int) post('menu_id');
        if ($id <= 0) {
            flash('ID Menu tidak valid.', 'error');
            redirect('admin/menus');
        }
        
        $db = Database::connect();
        
        // Check if menu is in any order items (foreign key constraint protection)
        $stmt = $db->prepare("SELECT COUNT(*) FROM order_items WHERE menu_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            flash('Menu tidak dapat dihapus karena sudah pernah dipesan oleh pelanggan. Anda bisa mengubah ketersediaannya menjadi Kosong.', 'error');
            redirect('admin/menus');
        }
        
        // Also clean up favorites first
        $stmt = $db->prepare("DELETE FROM favorites WHERE menu_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $db->prepare("DELETE FROM menus WHERE menu_id = ?");
        $stmt->execute([$id]);
        
        flash('Menu berhasil dihapus.');
        redirect('admin/menus');
    }

    public function categories(): void
    {
        $db = Database::connect();
        
        $editId = (int) ($_GET['edit_id'] ?? 0);
        $editingCategory = null;
        
        if ($editId > 0) {
            $stmt = $db->prepare("SELECT * FROM categories WHERE category_id = ?");
            $stmt->execute([$editId]);
            $editingCategory = $stmt->fetch() ?: null;
        }
        
        $stmt = $db->query("
            SELECT c.*, COUNT(m.menu_id) as menu_count
            FROM categories c
            LEFT JOIN menus m ON c.category_id = m.category_id
            GROUP BY c.category_id
            ORDER BY c.category_name ASC
        ");
        $categories = $stmt->fetchAll();
        
        $this->view('admin/categories', [
            'categories' => $categories,
            'editingCategory' => $editingCategory
        ]);
    }

    public function categoryStore(): never
    {
        $name = trim((string) post('name'));
        if ($name === '') {
            flash('Nama kategori wajib diisi.', 'error');
            redirect('admin/categories');
        }
        
        $db = Database::connect();
        
        // Check duplicate name
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE LOWER(category_name) = LOWER(?)");
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() > 0) {
            flash('Nama kategori sudah digunakan.', 'error');
            redirect('admin/categories');
        }
        
        $stmt = $db->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->execute([$name]);
        
        flash('Kategori baru berhasil disimpan.');
        redirect('admin/categories');
    }

    public function categoryUpdate(): never
    {
        $id = (int) post('category_id');
        $name = trim((string) post('name'));
        
        if ($id <= 0 || $name === '') {
            flash('Data tidak valid.', 'error');
            redirect('admin/categories');
        }
        
        $db = Database::connect();
        
        // Check duplicate name excluding current
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE LOWER(category_name) = LOWER(?) AND category_id != ?");
        $stmt->execute([$name, $id]);
        if ($stmt->fetchColumn() > 0) {
            flash('Nama kategori sudah digunakan.', 'error');
            redirect("admin/categories?edit_id=$id");
        }
        
        $stmt = $db->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
        $stmt->execute([$name, $id]);
        
        flash('Kategori berhasil diperbarui.');
        redirect('admin/categories');
    }

    public function categoryDelete(): never
    {
        $id = (int) post('category_id');
        if ($id <= 0) {
            flash('ID Kategori tidak valid.', 'error');
            redirect('admin/categories');
        }
        
        $db = Database::connect();
        
        // Relational safety
        $stmt = $db->prepare("SELECT COUNT(*) FROM menus WHERE category_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            flash('Kategori tidak dapat dihapus karena masih dikaitkan ke beberapa menu. Ubah kategori menu-menu tersebut terlebih dahulu.', 'error');
            redirect('admin/categories');
        }
        
        $stmt = $db->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->execute([$id]);
        
        flash('Kategori berhasil dihapus.');
        redirect('admin/categories');
    }

    public function users(): void
    {
        $db = Database::connect();
        
        $search = trim((string) ($_GET['search'] ?? ''));
        $role = trim((string) ($_GET['role'] ?? ''));
        
        $query = "
            SELECT u.user_id, u.name, u.email, u.role, u.profile_image,
                   COUNT(o.order_id) as order_count,
                   IFNULL(SUM(o.total_price), 0) as total_spent
            FROM users u
            LEFT JOIN orders o ON u.user_id = o.user_id AND o.status != 'Batal'
            WHERE 1=1
        ";
        $params = [];
        
        if ($search !== '') {
            $query .= " AND (u.name LIKE ? OR u.email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($role !== '') {
            $query .= " AND u.role = ?";
            $params[] = $role;
        }
        
        $query .= " GROUP BY u.user_id ORDER BY u.name ASC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
        // Single user detail panel data
        $detailId = (int) ($_GET['detail_id'] ?? 0);
        $userDetail = null;
        if ($detailId > 0) {
            $stmtDetail = $db->prepare("
                SELECT u.*, 
                       COUNT(o.order_id) as order_count,
                       IFNULL(SUM(o.total_price), 0) as total_spent
                FROM users u
                LEFT JOIN orders o ON u.user_id = o.user_id AND o.status != 'Batal'
                WHERE u.user_id = ?
                GROUP BY u.user_id
            ");
            $stmtDetail->execute([$detailId]);
            $userDetail = $stmtDetail->fetch() ?: null;
        }
        
        $this->view('admin/users', [
            'users' => $users,
            'userDetail' => $userDetail,
            'filters' => [
                'search' => $search,
                'role' => $role
            ]
        ]);
    }

    public function userUpdate(): never
    {
        $id = (int) post('user_id');
        $role = trim((string) post('role'));
        
        $currentUser = \App\Models\User::current();
        if ($id <= 0 || !in_array($role, ['customer', 'admin'], true)) {
            flash('Data update role tidak valid.', 'error');
            redirect('admin/users');
        }
        
        if ($currentUser && $id === (int) $currentUser['user_id']) {
            flash('Anda tidak diizinkan mengubah role akun Anda sendiri untuk menghindari kehilangan hak akses admin.', 'error');
            redirect("admin/users?detail_id=$id");
        }
        
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$role, $id]);
        
        flash('Role pengguna berhasil diperbarui.');
        redirect("admin/users?detail_id=$id");
    }

    public function reviews(): void
    {
        $db = Database::connect();
        
        $rating = trim((string) ($_GET['rating'] ?? ''));
        
        $query = "
            SELECT r.review_id, r.rating, r.comment, r.created_at,
                   u.name as user_name, m.menu_name
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            JOIN menus m ON r.menu_id = m.menu_id
            WHERE 1=1
        ";
        $params = [];
        
        if ($rating !== '') {
            $query .= " AND r.rating = ?";
            $params[] = (int) $rating;
        }
        
        $query .= " ORDER BY r.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $reviews = $stmt->fetchAll();
        
        $this->view('admin/reviews', [
            'reviews' => $reviews,
            'filters' => [
                'rating' => $rating
            ]
        ]);
    }

    public function reviewDelete(): never
    {
        $id = (int) post('review_id');
        if ($id <= 0) {
            flash('ID Ulasan tidak valid.', 'error');
            redirect('admin/reviews');
        }
        
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM reviews WHERE review_id = ?");
        $stmt->execute([$id]);
        
        flash('Ulasan berhasil dihapus dari modul moderasi.');
        redirect('admin/reviews');
    }

    public function reports(): void
    {
        $db = Database::connect();
        
        $revenue = [];
        
        // Daily revenue
        $stmt = $db->query("SELECT SUM(total_price) FROM orders WHERE DATE(created_at) = CURRENT_DATE AND status != 'Batal'");
        $revenue['today'] = $stmt->fetchColumn() ?: 0;
        
        // Monthly revenue
        $stmt = $db->query("SELECT SUM(total_price) FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE) AND YEAR(created_at) = YEAR(CURRENT_DATE) AND status != 'Batal'");
        $revenue['month'] = $stmt->fetchColumn() ?: 0;
        
        // Yearly revenue
        $stmt = $db->query("SELECT SUM(total_price) FROM orders WHERE YEAR(created_at) = YEAR(CURRENT_DATE) AND status != 'Batal'");
        $revenue['year'] = $stmt->fetchColumn() ?: 0;
        
        // All-time revenue
        $stmt = $db->query("SELECT SUM(total_price) FROM orders WHERE status != 'Batal'");
        $revenue['total'] = $stmt->fetchColumn() ?: 0;
        
        // Order Status stats
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
        $orderStats = $stmt->fetchAll();
        
        // Top 5 menus
        $stmt = $db->query("
            SELECT m.menu_name, SUM(oi.quantity) as sold
            FROM order_items oi
            JOIN menus m ON oi.menu_id = m.menu_id
            GROUP BY oi.menu_id
            ORDER BY sold DESC
            LIMIT 5
        ");
        $topSelling = $stmt->fetchAll();
        
        $this->view('admin/reports', [
            'revenue' => $revenue,
            'orderStats' => $orderStats,
            'topSelling' => $topSelling
        ]);
    }

    public function reportsExport(): void
    {
        $startDate = trim((string) ($_GET['start_date'] ?? ''));
        $endDate = trim((string) ($_GET['end_date'] ?? ''));
        
        if ($startDate === '') {
            $startDate = date('Y-m-01');
        }
        if ($endDate === '') {
            $endDate = date('Y-m-d');
        }
        
        $db = Database::connect();
        
        $stmt = $db->prepare("
            SELECT o.order_number as `No. Pesanan`, 
                   u.name as `Nama Customer`, 
                   o.penerima as `Nama Penerima`, 
                   o.phone_number as `No. Telepon`,
                   o.shipping_address as `Alamat Pengiriman`,
                   o.total_price as `Total Pembayaran`,
                   o.status as `Status Pesanan`,
                   o.created_at as `Tanggal Transaksi`
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE DATE(o.created_at) BETWEEN ? AND ?
            ORDER BY o.created_at ASC
        ");
        $stmt->execute([$startDate, $endDate]);
        $transactions = $stmt->fetchAll();
        
        // Set headers to trigger file download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="laporan-penjualan-' . $startDate . '-to-' . $endDate . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV BOM for UTF-8 compatibility with Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if (!empty($transactions)) {
            // Write headers
            fputcsv($output, array_keys($transactions[0]));
            
            // Write rows
            foreach ($transactions as $row) {
                fputcsv($output, $row);
            }
        } else {
            fputcsv($output, ['Pesan', 'Tidak ada data transaksi ditemukan pada rentang tanggal tersebut.']);
        }
        
        fclose($output);
        exit;
    }
}
