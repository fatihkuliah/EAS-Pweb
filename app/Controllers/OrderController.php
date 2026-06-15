<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct()
    {
        if (\App\Models\User::current() === null) {
            redirect('auth');
        }
    }

    public function index(): void
    {
        $this->view('orders', [
            'title' => 'Riwayat Pesanan MieME',
            'orders' => (new OrderService())->all(),
            'message' => flash(),
        ]);
    }

    public function export(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=riwayat_pesanan_mieme.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Tanggal', 'Nomor Pesanan', 'Total Item', 'Total Pembayaran', 'Status']);

        foreach ((new OrderService())->all() as $order) {
            fputcsv($output, [
                $order['tanggal'],
                $order['id'],
                array_sum(array_column($order['items'], 'qty')),
                $order['total'],
                $order['status'],
            ]);
        }
    }

    public function invoice(): void
    {
        $order = (new OrderService())->find((string) ($_GET['id'] ?? ''));

        if ($order === null) {
            redirect('orders');
        }

        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $order['id'] . '-invoice.txt');

        echo "INVOICE MIEME\n";
        echo 'Nomor: ' . $order['id'] . "\n";
        echo 'Tanggal: ' . $order['tanggal'] . "\n";
        echo 'Penerima: ' . $order['penerima'] . "\n\n";

        foreach ($order['items'] as $item) {
            echo $item['nama'] . ' x ' . $item['qty'] . ' = ' . rupiah($item['harga'] * $item['qty']) . "\n";
        }

        echo "\nTotal: " . rupiah($order['total']) . "\n";
        echo 'Status: ' . $order['status'] . "\n";
    }

    public function review(): void
    {
        $order = (new OrderService())->find((string) ($_GET['id'] ?? ''));

        if ($order === null || $order['status'] !== 'Selesai') {
            redirect('orders');
        }

        $this->view('review', [
            'title' => 'Review MieME',
            'order' => $order,
        ]);
    }

    public function storeReview(): never
    {
        (new OrderService())->saveReview(
            (string) post('order_id'),
            (int) post('rating', 5),
            trim((string) post('komentar'))
        );

        flash('Review berhasil disimpan. Terima kasih.');
        redirect('orders');
    }
}
