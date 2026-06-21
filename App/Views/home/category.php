<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-success mb-1"><?= htmlspecialchars($category['l_ten']) ?></h3>
            <p class="text-muted mb-0">Tìm thấy <?= $totalResults ?> sản phẩm</p>
        </div>
        
        <!-- Bộ lọc sắp xếp -->
        <div class="dropdown">
            <button class="btn btn-outline-success dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-sort"></i> 
                <?php
                switch($currentSort) {
                    case 'price_asc': echo 'Giá tăng dần'; break;
                    case 'price_desc': echo 'Giá giảm dần'; break;
                    default: echo 'Sắp xếp';
                }
                ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                <li>
                    <a class="dropdown-item <?= $currentSort === 'default' ? 'active' : '' ?>" 
                       href="/category/<?= $category['l_ma'] ?>">
                        <i class="fa fa-clock"></i> Mới nhất
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?= $currentSort === 'price_asc' ? 'active' : '' ?>" 
                       href="/category/<?= $category['l_ma'] ?>?sort=price_asc">
                        <i class="fa fa-arrow-up"></i> Giá tăng dần
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?= $currentSort === 'price_desc' ? 'active' : '' ?>" 
                       href="/category/<?= $category['l_ma'] ?>?sort=price_desc">
                        <i class="fa fa-arrow-down"></i> Giá giảm dần
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            <i class="fa fa-box-open fa-3x mb-3 d-block"></i>
            <h5>Chưa có sản phẩm nào trong danh mục này</h5>
            <p class="mb-0">Vui lòng quay lại sau</p>
        </div>
        <div class="text-center mt-4">
            <a href="/" class="btn btn-success">
                <i class="fa fa-home"></i> Quay về trang chủ
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php
                            $imgName = htmlspecialchars($product['sp_hinh']);
                            $uploadUrl = '/uploads/' . $imgName;
                            $uploadPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $uploadUrl;
                            $imgUrl = file_exists($uploadPath) ? $uploadUrl : '/img/unnamed.png';
                        ?>
                        <img src="<?= $imgUrl ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($product['sp_ten']) ?>"
                             style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='/img/unnamed.png';">
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['sp_ten']) ?></h5>
                            
                            <p class="card-text text-danger font-weight-bold">
                                <?= number_format($product['sp_gia'], 0, ',', '.') ?> VNĐ
                            </p>
                            
                            <a href="#" 
                               class="btn btn-success w-100 add-to-cart-btn" 
                               data-product-id="<?= $product['sp_ma'] ?>"
                               data-product-name="<?= htmlspecialchars($product['sp_ten']) ?>">
                                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Hàm cập nhật số lượng giỏ hàng trên header
function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Xử lý sự kiện click nút "Thêm vào giỏ hàng"
document.addEventListener('click', function(e) {
    const button = e.target.closest('.add-to-cart-btn');
    if (!button) return;
    
    if (button.disabled) {
        e.preventDefault();
        return;
    }
    
    e.preventDefault();
    
    const productId = button.getAttribute('data-product-id');
    if (!productId) return;
    
    const originalText = button.innerHTML;
    button.disabled = true;
    
    fetch('/cart/add-ajax?id=' + productId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cartCount);
            button.innerHTML = originalText;
            button.disabled = false;
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể thêm sản phẩm vào giỏ hàng'));
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
        button.innerHTML = originalText;
        button.disabled = false;
    });
});
</script>