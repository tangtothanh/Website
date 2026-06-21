<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Store;
use App\Models\PDOFactory;

class StoreController extends Controller
{
    protected $storeModel;

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
        $this->storeModel = new Store($pdo);
        $this->setLayout('layouts/admin_master');
    }

    /**
     * Trang frontend: hiển thị danh sách cửa hàng cho khách (Store Locator)
     */
    public function locator()
    {
        // Lấy tất cả cửa hàng đang hoạt động
        $stores = $this->storeModel->getAllActiveStores();

        // Lấy danh sách tỉnh/thành duy nhất để hiển thị bộ lọc
        $provinces = [];
        foreach ($stores as $store) {
            $provinceName = $store['ch_thanhpho'] ?? $store['province_name'] ?? '';
            if ($provinceName !== '' && !in_array($provinceName, $provinces, true)) {
                $provinces[] = $provinceName;
            }
        }
        sort($provinces);

        // Frontend dùng layout mặc định
        $this->setLayout('layouts/master');
        $this->view('stores/locator', [
            'stores' => $stores,
            'provinces' => $provinces
        ]);
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $stores = $this->storeModel->getStoresPaginated($limit, $offset);
        $totalStores = $this->storeModel->countTotal();
        $totalPages = ceil($totalStores / $limit);

        $this->view('stores/index', [
            'stores' => $stores,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }

    // Xử lý chung cho cả Thêm và Sửa
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['_csrf'] ?? '')) {
                abort_csrf();
            }
            // Basic server-side validation and sanitization
            $name = trim($_POST['name'] ?? '');
            $street = trim($_POST['street'] ?? '');
            $province_name = trim($_POST['province_name'] ?? '');
            $province_code = trim($_POST['province_code'] ?? '');
            $district_name = trim($_POST['district_name'] ?? '');
            $district_code = trim($_POST['district_code'] ?? '');
            $ward_code = trim($_POST['ward_code'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $status = isset($_POST['status']) ? ($_POST['status'] == '1' ? 1 : 0) : 0;
            $open_time = trim($_POST['open_time'] ?? '');
            $close_time = trim($_POST['close_time'] ?? '');

            $errors = [];
            if ($name === '') {
                $errors['name'] = 'Tên cửa hàng không được để trống.';
            }
            if ($province_code === '') {
                $errors['province'] = 'Vui lòng chọn tỉnh/thành.';
            }
            if ($street === '') {
                $errors['street'] = 'Vui lòng nhập số nhà và đường.';
            }
            if ($phone === '') {
                $errors['phone'] = 'Vui lòng nhập số điện thoại.';
            }

            if (!empty($errors)) {
                redirect('/admin/stores', ['errors' => $errors, 'form' => $_POST]);
            }

            // Ghép chuỗi địa chỉ đẹp để hiển thị
            $fullAddress = $street . ', ' .
                $ward_code . ', ' .
                $district_name . ', ' .
                $province_name;

            $data = [
                'name' => $name,
                'full_address' => $fullAddress,
                'province_name' => $province_name,
                'district_name' => $district_name,
                'province_code' => $province_code,
                'district_code' => $district_code,
                'ward_code' => $ward_code,
                'street' => $street,
                'phone' => $phone,
                'status' => $status,
                'open_time' => $open_time,
                'close_time' => $close_time
            ];

            // Nếu có ID -> Cập nhật, Không có ID -> Thêm mới
            if (!empty($_POST['store_id'])) {
                $data['id'] = $_POST['store_id'];
                $this->storeModel->updateStore($data);
            } else {
                $this->storeModel->addStore($data);
            }

            header('Location: /admin/stores');
            exit;
        }
    }

    public function delete()
    {
        if (isset($_POST['store_id'])) {
            if (!validate_csrf_token($_POST['_csrf'] ?? '')) {
                abort_csrf();
            }
            $this->storeModel->deleteStore($_POST['store_id']);
        }
        header('Location: /admin/stores');
        exit;
    }
}
