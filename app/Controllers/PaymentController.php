<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function index(): void
    {
        $checkout = new CheckoutService();
        $payment = new PaymentService();

        $this->view('payment', [
            'title' => 'Payment MieME',
            'checkout' => $checkout->current() ?? ['total' => 0],
            'methods' => $payment->methods(),
        ]);
    }

    public function select(): never
    {
        $method = (string) post('method', 'QRIS');
        (new PaymentService())->choose($method);
        redirect($method === 'QRIS' ? 'qris' : 'upload-payment');
    }

    public function qris(): void
    {
        $checkout = new CheckoutService();
        (new PaymentService())->choose('QRIS');

        $this->view('payment/qris', [
            'title' => 'QRIS MieME',
            'checkout' => $checkout->current() ?? ['total' => 0],
        ]);
    }

    public function upload(): void
    {
        $checkout = new CheckoutService();
        $payment = new PaymentService();

        $this->view('payment/upload', [
            'title' => 'Upload Bukti MieME',
            'checkout' => $checkout->current() ?? ['total' => 0],
            'payment' => $payment->current() ?? ['nama' => 'Metode Pembayaran'],
        ]);
    }

    public function storeUpload(): never
    {
        $payment = new PaymentService();
        $receipt = isset($_FILES['receipt']) ? $payment->uploadReceipt($_FILES['receipt']) : '';
        $order = (new OrderService())->createFromCheckout($receipt);

        if ($order === null) {
            redirect('order');
        }

        flash('Pesanan berhasil dibuat. Bukti pembayaran diterima.');
        redirect('orders');
    }
}
