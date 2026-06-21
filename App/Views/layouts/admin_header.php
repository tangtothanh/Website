<header class="text-center text-light" style="margin:0; padding:0; position: relative; z-index: 1000;">

    <div class="d-flex align-items-center justify-content-between px-4 py-2" style="
            background-color: rgb(206, 255, 225);
            border-bottom: 1px solid #ccc;
            width: 100%;
            position: relative;
            box-sizing: border-box;">

        <div class="d-flex align-items-center gap-2">
            <img src="/img/logo/logo2.png" alt="Logo Passion" height="70">
            <h4 class="text-success display-6">PASSION COFFEE </h4>
            <i class="text-success display-7">Theo đuổi đam mê, thành công sẽ theo đuổi bạn</i>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-success">Xin chào, <?php 
                $admin = AUTHGUARD()->admin();
                echo htmlspecialchars($admin ? $admin->qtv_tendn : 'Admin');
            ?></span>

            <a href="/admin/logout" class="btn btn-success" title="Đăng xuất">
                <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </div>
    <div style="background-color: #198754; width: 100%; margin: 0; padding: 0;">
        <div class="btn-group w-100 justify-content-center" role="group">
            <a href="/admin/stores" class="btn btn-success">QUẢN LÝ CỬA HÀNG</a>
            <a href="/admin/products/create" class="btn btn-success">QUẢN LÝ SẢN PHẨM</a>
            <a href="/admin/orders" class="btn btn-success">QUẢN LÝ ĐƠN HÀNG</a>
            <a href="/admin/statistics" class="btn btn-success">THỐNG KÊ ĐƠN VÀ DOANH THU</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="SHA384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6o+Pz/l69hL+yF5h4D1P4Bf5bM5O6cO9aO" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="SHA384-07I/J9L4F5+lM4/QvM6/5g7S9sH4aE/6j3I2I2G7z8R6K4O7f4B/4N2/H7A/9E" crossorigin="anonymous"></script>
</header>