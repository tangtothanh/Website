<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\PDOFactory;

class ProductController extends Controller
{
    protected $productModel;

    public function __construct()
    {
        // Khởi tạo kết nối DB
        $config = [
            'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
            'db_port' => $_ENV['DB_PORT'] ?? '5432',
            'db_name' => $_ENV['DB_NAME'] ?? 'ct275_project',
            'db_user' => $_ENV['DB_USER'] ?? 'postgres',
            'db_pass' => $_ENV['DB_PASS'] ?? 'password',
        ];
        $pdo = (new PDOFactory())->create($config);
        $this->productModel = new Product($pdo);
        $this->setLayout('layouts/admin_master');
    }

    // Sửa hàm create cũ
    public function create()
    {
        // 1. Xử lý Lọc & Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $categoryId = isset($_GET['filter_category']) ? (int)$_GET['filter_category'] : null;
        $limit = 20; // 20 sản phẩm/trang
        $offset = ($page - 1) * $limit;

        // 2. Gọi Model lấy dữ liệu
        $categories = $this->productModel->getCategories();
        $products = $this->productModel->getProductsPaginated($limit, $offset, $categoryId);
        $totalProducts = $this->productModel->countTotal($categoryId);
        $totalPages = ceil($totalProducts / $limit);

        // 3. Truyền hết sang View
        $this->view('products/create', [
            'categories' => $categories,
            'products' => $products,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'currentCategory' => $categoryId
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            // Xử lý upload ảnh (Tóm tắt)
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $imageName = time() . '_' . uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            }

            $data = [
                'id' => $_POST['product_id'], // Lấy ID từ hidden field
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id'],
                'image' => $imageName // Nếu null, model sẽ bỏ qua cập nhật ảnh
            ];

            $this->productModel->updateProduct($data);
            header('Location: /admin/products/create'); // Quay lại trang quản lý
            exit;
        }
    }

    // Thêm hàm delete
    public function delete()
    {
        if (isset($_POST['product_id'])) {
            $this->productModel->deleteProduct($_POST['product_id']);
        }
        header('Location: /admin/products/create');
        exit;
    }

    // 2. Xử lý lưu dữ liệu (ĐÃ CẬP NHẬT)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $imageName = null; // Mặc định là null

            // --- XỬ LÝ UPLOAD ẢNH ---
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

                // Đường dẫn thư mục uploads
                $uploadDir = __DIR__ . '/../../public/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Tạo tên file ngẫu nhiên để tránh trùng
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . '_' . uniqid() . '.' . $fileExtension;

                $targetPath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    die("Lỗi: Không thể lưu file ảnh vào thư mục public/uploads.");
                }
            }

            // --- GOM DỮ LIỆU (Đã thêm category_id) ---
            $data = [
                'name'        => $_POST['name'],
                'price'       => $_POST['price'],
                'description' => $_POST['description'],
                'image'       => $imageName,
                'category_id' => $_POST['category_id'] ?? null
            ];

            // Gọi Model để lưu
            if ($this->productModel->addProduct($data)) {
                // Thành công -> Quay về trang quản lý sản phẩm (giống update)
                header('Location: /admin/products/create');
                exit;
            } else {
                echo "Lỗi: Không thể lưu vào Cơ sở dữ liệu.";
            }
        }
    }
}
