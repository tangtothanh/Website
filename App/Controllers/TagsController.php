<?php

namespace App\Controllers;

use App\Models\Tag;

class TagsController extends Controller
{
    private Tag $tagModel;
    protected $db;

    public function __construct()
    {
        // Kiểm tra đăng nhập
        if (!AUTHGUARD()->isUserLoggedIn()) {
            redirect('/login');
        }

        parent::__construct();
        $this->db = PDO(); 
        // Khởi tạo connection và model
        $this->tagModel = new Tag($this->db->create());
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $name = trim($_POST['name'] ?? '');
        $userId = AUTHGUARD()->user()->id;

        if (empty($id) || empty($name)) {
            redirect('/', ['flash_data' => ['error' => 'Dữ liệu không hợp lệ.']]);
        }

        // Tìm tag để đảm bảo nó thuộc về user hiện tại
        $tag = $this->tagModel->findById($id, $userId);

        if (!$tag) {
            redirect('/', ['flash_data' => ['error' => 'Tag không tồn tại hoặc bạn không có quyền sửa.']]);
        }

        // Gọi model để cập nhật
        if ($this->tagModel->update($name)) { // Lưu ý: phương thức update trong model Tag.php của bạn dùng $this->id đã set
            redirect('/', ['flash_data' => ['success' => 'Cập nhật Tag thành công!']]);
        } else {
            redirect('/', ['flash_data' => ['error' => 'Không thể cập nhật Tag.']]);
        }
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $userId = AUTHGUARD()->user()->id;

        if (empty($id)) {
            redirect('/', ['flash_data' => ['error' => 'ID Tag không hợp lệ.']]);
        }

        // Tìm tag để gán ID và UserID vào object model
        $tag = $this->tagModel->findById($id, $userId);

        if (!$tag) {
             redirect('/', ['flash_data' => ['error' => 'Tag không tồn tại hoặc bạn không có quyền xóa.']]);
        }

        // Thực hiện xóa
        if ($tag->delete()) {
            redirect('/', ['flash_data' => ['success' => 'Đã xóa Tag thành công!']]);
        } else {
            redirect('/', ['flash_data' => ['error' => 'Đã xảy ra lỗi khi xóa Tag.']]);
        }
    }
}