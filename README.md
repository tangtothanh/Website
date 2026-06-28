# Project học phần Công nghệ Web (CT275)

Học kỳ 3, Năm học 2025-2026

**Tên dự án**: Website bán coffee

**MSSV 1**:DC25V7X232

**Họ tên SV 1**:Tăng Tố Thanh

**MSSV 2**:

**Họ tên SV 2**:

**Lớp học phần**: 

## Cài đặt & chạy thử

### Yêu cầu

- PHP >= 8.0 (có extension `pdo_pgsql`)
- PostgreSQL (có extension `pgcrypto`, dùng để hash mật khẩu mẫu)
- Composer

### Các bước

1. Cài thư viện PHP:
   ```
   composer install
   ```

2. Tạo file `.env` ở thư mục gốc (cùng cấp `composer.json`):
   ```
   DB_HOST="localhost"
   DB_PORT="5432"
   DB_NAME="ct275_project"
   DB_USER="postgres"
   DB_PASS="postgres"
   ```

3. Tạo database rồi import schema và dữ liệu mẫu (theo đúng thứ tự):
   ```
   psql -U postgres -c "CREATE DATABASE ct275_project"
   psql -U postgres -d ct275_project -f ct275_project.sql
   psql -U postgres -d ct275_project -f seed_data.sql
   ```
   `ct275_project.sql` sẽ xóa và tạo lại toàn bộ bảng (DROP TABLE ... CASCADE) — chỉ chạy lại khi muốn reset dữ liệu.

4. Chạy server (dùng PHP built-in server, trỏ document root vào `public/`):
   ```
   php -S localhost:8000 -t public
   ```
   Sau đó truy cập http://localhost:8000

### Tài khoản demo (sau khi import `seed_data.sql`)

- Khách hàng: `a@example.com` / `password` (hoặc `b@example.com` / `password`)
- Quản trị viên: `admin` / `adminpass` (đăng nhập tại `/admin/login`)

