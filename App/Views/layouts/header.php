<header class="text-center text-light" style="margin:0; padding:0; position: relative; z-index: 1000;">

    <div class="d-flex align-items-center justify-content-between px-4 py-2" style="
            background-color: rgb(206, 255, 225);
            border-bottom: 1px solid #ccc;
            width: 100%;
            position: relative;
            box-sizing: border-box;">

        <div class="d-flex align-items-center gap-3">
            <img src="/img/logo/logo2.png" alt="Logo Passion" height="70">
            <form action="/search" method="GET" class="input-group" style="width: 300px;">
                <input type="text"
                    class="form-control"
                    name="q"
                    placeholder="Bạn muốn mua gì hôm nay?"
                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                    required>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-light" title="nhận hàng">
                <i class="fa-solid fa-motorcycle"></i> Phương thức nhận hàng
            </button>
            <a href="/cart" class="btn btn-success position-relative" title="Giỏ hàng">
                <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                <?php
                $cartCount = 0;
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $cartCount += $item['quantity'];
                    }
                }
                if ($cartCount > 0):
                ?>
                    <span id="cart-badge"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="<?= $cartCount > 0 ? '' : 'display:none;' ?>">
                        <?= $cartCount ?>
                    </span>
                <?php endif; ?>
            </a>
            <?php if (AUTHGUARD()->isCustomerLoggedIn()): ?>
                <?php $customer = AUTHGUARD()->customer(); ?>
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user me-2"></i>
                        <?= htmlspecialchars($customer->kh_ten ?? 'Tài khoản') ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fa-solid fa-user me-2"></i>
                                <?= htmlspecialchars($customer->kh_ten ?? '') ?>
                            </h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fa-solid fa-user-circle me-2"></i>
                                Thông tin tài khoản
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fa-solid fa-receipt me-2"></i>
                                Đơn hàng của tôi
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="/logout" class="d-inline">
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa-solid fa-sign-out-alt me-2"></i>
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/login" class="btn btn-success" title="Đăng nhập">
                    <i class="fa-solid fa-user me-2"></i>
                    Đăng nhập
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div style="background-color: #198754; width: 100%; margin: 0; padding: 0;">
        <div class="btn-group w-100 justify-content-center" role="group">
            <a href="/" class="btn btn-success">TRANG CHỦ</a>
            <div class="dropdown">
                <button type="button" class="dropbtn btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    MENU
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/category/1">Cà phê</a></li>
                    <li><a class="dropdown-item" href="/category/2">Trà sữa</a></li>
                    <li><a class="dropdown-item" href="/category/3">Bánh ngọt</a></li>
                </ul>
            </div>

            <a href="/cua-hang" class="btn btn-success">CỬA HÀNG</a>
            <a href="/ve-passion" class="btn btn-success">VỀ PASSION</a>
            <a href="/lien-he" class="btn btn-success">LIÊN HỆ</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="SHA384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6o+Pz/l69hL+yF5h4D1P4Bf5bM5O6cO9aO" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="SHA384-07I/J9L4F5+lM4/QvM6/5g7S9sH4aE/6j3I2I2G7z8R6K4O7f4B/4N2/H7A/9E" crossorigin="anonymous"></script>
</header>