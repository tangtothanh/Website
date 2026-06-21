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

            // Ghép chuỗi địa chỉ đẹp để hiển thị
            $fullAddress = $_POST['street'] . ', ' .
                $_POST['ward_name'] . ', ' .
                $_POST['district_name'] . ', ' .
                $_POST['province_name'];

            $data = [
                'name' => $_POST['name'],
                'full_address' => $fullAddress,
                'province_name' => $_POST['province_name'],
                'district_name' => $_POST['district_name'],
                'province_code' => $_POST['province_code'],
                'district_code' => $_POST['district_code'],
                'ward_code' => $_POST['ward_code'],
                'street' => $_POST['street'],
                'phone' => $_POST['phone'],
                'status' => $_POST['status'], // Giá trị trả về là '1' hoặc '0'
                'open_time' => $_POST['open_time'],
                'close_time' => $_POST['close_time']
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
            $this->storeModel->deleteStore($_POST['store_id']);
        }
        header('Location: /admin/stores');
        exit;
    }
}
