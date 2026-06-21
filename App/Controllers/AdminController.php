<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PDOFactory;

class AdminController extends Controller
{
    protected $pdo;

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
        // TODO: Lấy danh sách đơn hàng từ database
        $orders = [];
        
        $data = [
            'title' => 'Quản Lý Đơn Hàng - ' . APPNAME,
            'orders' => $orders,
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

        // TODO: Lấy chi tiết đơn hàng từ database
        $order = null;
        
        $data = [
            'title' => 'Chi Tiết Đơn Hàng - ' . APPNAME,
            'order' => $order,
        ];

        $this->view('admin/order_detail', $data);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus()
    {
        $orderId = $_POST['order_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$orderId || !$status) {
            redirect('/admin/orders', ['errors' => ['Thiếu thông tin cần thiết.']]);
        }

        // TODO: Cập nhật trạng thái đơn hàng trong database
        
        redirect('/admin/orders', ['messages' => ['success' => 'Cập nhật trạng thái đơn hàng thành công!']]);
    }

    /**
     * Hiển thị thống kê
     */
    public function statistics()
    {
        // TODO: Lấy dữ liệu thống kê từ database
        $stats = [
            'total_orders' => 0,
            'total_revenue' => 0,
            'total_products' => 0,
            'total_customers' => 0,
        ];
        
        $data = [
            'title' => 'Thống Kê - ' . APPNAME,
            'stats' => $stats,
        ];

        $this->view('admin/statistics', $data);
    }
}