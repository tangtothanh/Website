<?php

namespace App\Models;

use PDO;

class Customer
{
    protected $pdo;

    public $kh_ma = -1;
    public $kh_ten;
    public $kh_email;
    public $kh_sdt;
    public $kh_diachi;
    public $kh_matkhau;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Tìm khách hàng theo email
    public function findByEmail(string $email): ?Customer
    {
        // PostgreSQL trả về tên cột chữ thường, nên dùng alias hoặc truy cập bằng cả hai cách
        $stmt = $this->pdo->prepare("
            SELECT KH_MA as kh_ma, KH_TEN as kh_ten, KH_EMAIL as kh_email, 
                   KH_SDT as kh_sdt, KH_DIACHI as kh_diachi, KH_MATKHAU as kh_matkhau
            FROM KHACH_HANG 
            WHERE KH_EMAIL = :email
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $customer = new Customer($this->pdo);
            $customer->fillFromDbRow($row);
            return $customer;
        }
        
        return null;
    }

    // Tìm khách hàng theo ID
    public function findById(int $id): ?Customer
    {
        // PostgreSQL trả về tên cột chữ thường, nên dùng alias
        $stmt = $this->pdo->prepare("
            SELECT KH_MA as kh_ma, KH_TEN as kh_ten, KH_EMAIL as kh_email, 
                   KH_SDT as kh_sdt, KH_DIACHI as kh_diachi, KH_MATKHAU as kh_matkhau
            FROM KHACH_HANG 
            WHERE KH_MA = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $customer = new Customer($this->pdo);
            $customer->fillFromDbRow($row);
            return $customer;
        }
        
        return null;
    }

    // Lưu khách hàng (thêm mới hoặc cập nhật)
    public function save(): bool
    {
        if ($this->kh_ma >= 0) {
            // Cập nhật
            $stmt = $this->pdo->prepare("
                UPDATE KHACH_HANG 
                SET KH_TEN = :ten, KH_EMAIL = :email, KH_SDT = :sdt, 
                    KH_DIACHI = :diachi, KH_MATKHAU = :matkhau
                WHERE KH_MA = :id
            ");
            return $stmt->execute([
                'id' => $this->kh_ma,
                'ten' => $this->kh_ten,
                'email' => $this->kh_email,
                'sdt' => $this->kh_sdt,
                'diachi' => $this->kh_diachi,
                'matkhau' => $this->kh_matkhau
            ]);
        } else {
            // Thêm mới
            $stmt = $this->pdo->prepare("
                INSERT INTO KHACH_HANG (KH_TEN, KH_EMAIL, KH_SDT, KH_DIACHI, KH_MATKHAU)
                VALUES (:ten, :email, :sdt, :diachi, :matkhau)
            ");
            $result = $stmt->execute([
                'ten' => $this->kh_ten,
                'email' => $this->kh_email,
                'sdt' => $this->kh_sdt,
                'diachi' => $this->kh_diachi,
                'matkhau' => $this->kh_matkhau
            ]);
            
            if ($result) {
                $this->kh_ma = (int)$this->pdo->lastInsertId();
            }
            
            return $result;
        }
    }

    // Điền dữ liệu từ form
    public function fill(array $data): Customer
    {
        $this->kh_ten = $data['name'] ?? '';
        $this->kh_email = $data['email'] ?? '';
        $this->kh_sdt = $data['phone'] ?? '';
        $this->kh_diachi = $data['address'] ?? '';
        
        // Mã hóa mật khẩu nếu có
        if (isset($data['password']) && !empty($data['password'])) {
            $this->kh_matkhau = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this;
    }

    // Điền dữ liệu từ database row
    private function fillFromDbRow(array $row)
    {
        $this->kh_ma = (int)$row['kh_ma'];
        $this->kh_ten = $row['kh_ten'] ?? '';
        $this->kh_email = $row['kh_email'] ?? '';
        $this->kh_sdt = $row['kh_sdt'] ?? '';
        $this->kh_diachi = $row['kh_diachi'] ?? '';
        $this->kh_matkhau = $row['kh_matkhau'] ?? '';
    }

    // Kiểm tra email đã tồn tại chưa
    private function isEmailInUse(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM KHACH_HANG WHERE KH_EMAIL = :email AND KH_MA != :id");
            $stmt->execute(['email' => $email, 'id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM KHACH_HANG WHERE KH_EMAIL = :email");
            $stmt->execute(['email' => $email]);
        }
        return $stmt->fetchColumn() > 0;
    }

    // Validate dữ liệu
    public function validate(array $data): array
    {
        $errors = [];

        // Validate tên
        if (empty($data['name']) || strlen(trim($data['name'])) < 2) {
            $errors['name'] = 'Tên phải có ít nhất 2 ký tự.';
        }

        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } elseif ($this->isEmailInUse($data['email'], $this->kh_ma >= 0 ? $this->kh_ma : null)) {
            $errors['email'] = 'Email đã được sử dụng.';
        }

        // Validate mật khẩu (chỉ khi đăng ký hoặc đổi mật khẩu)
        if (empty($this->kh_ma) || isset($data['password'])) {
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
            } elseif (isset($data['password_confirmation']) && $data['password'] !== $data['password_confirmation']) {
                $errors['password_confirmation'] = 'Mật khẩu xác nhận không khớp.';
            }
        }

        return $errors;
    }
}
