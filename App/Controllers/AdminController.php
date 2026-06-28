<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\PDOFactory;

class AdminController extends Controller
{
    protected $pdo;
    protected $orderModel;

    public function __construct()
    {
        // Kiểm tra đăng nhập admin
        if (!AUTHGUARD()->isAdminLoggedIn()) {
            redirect('/admin/login');
        }

        // Khởi tạo kết nối DB
        $config = [
            'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
            'db_port' => $_ENV['DB_PORT'] ?? '5432',
            'db_name' => $_ENV['DB_NAME'] ?? 'project_example',
            'db_user' => $_ENV['DB_USER'] ?? 'postgres',
            'db_pass' => $_ENV['DB_PASS'] ?? 'password',
        ];
        $this->pdo = (new PDOFactory())->create($config);
        $this->orderModel = new Order($this->pdo);

        $this->setLayout('layouts/admin_master');
    }

    /**
     * Hiển thị trang dashboard admin
     */
    public function index()
    {
        $data = [
            'title' => 'Trang Quản Trị - ' . APPNAME,
            'messages' => session_get_once('messages'),
        ];

        $this->view('admin/index', $data);
    }

    /**
     * Đăng xuất admin
     */
    public function logout()
    {
        AUTHGUARD()->logoutAdmin();
        redirect('/admin/login', ['messages' => ['success' => 'Đăng xuất thành công!']]);
    }

    /**
     * Hiển thị danh sách đơn hàng
     */
    public function orders()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getAllPaginated($limit, $offset);
        $totalOrders = $this->orderModel->countTotal();

        $data = [
            'title' => 'Quản Lý Đơn Hàng - ' . APPNAME,
            'orders' => $orders,
            'totalPages' => ceil($totalOrders / $limit),
            'currentPage' => $page,
            'messages' => session_get_once('messages'),
        ];

        $this->view('admin/orders', $data);
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function orderDetail()
    {
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            redirect('/admin/orders');
        }

        $order = $this->orderModel->findById((int)$orderId);

        if (!$order) {
            redirect('/admin/orders');
        }

        $data = [
            'title' => 'Chi Tiết Đơn Hàng - ' . APPNAME,
            'order' => $order,
            'items' => $this->orderModel->getItemsByOrderId((int)$orderId),
        ];

        $this->view('admin/order_detail', $data);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus()
    {
        if (!validate_csrf_token($_POST['_csrf'] ?? '')) {
            abort_csrf();
        }

        $orderId = $_POST['order_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$orderId || !$status) {
            redirect('/admin/orders', ['errors' => ['Thiếu thông tin cần thiết.']]);
        }

        $this->orderModel->updateStatus((int)$orderId, $status);

        redirect('/admin/orders', ['messages' => ['success' => 'Cập nhật trạng thái đơn hàng thành công!']]);
    }

    /**
     * Hiển thị thống kê
     */
    public function statistics()
    {
        $data = [
            'title' => 'Thống Kê - ' . APPNAME,
            'stats' => $this->orderModel->getStatistics(),
        ];

        $this->view('admin/statistics', $data);
    }
}