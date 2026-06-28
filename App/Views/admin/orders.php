<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fa-solid fa-shopping-cart me-2"></i>
        Quản Lý Đơn Hàng
    </h2>

    <?php if (isset($messages['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($messages['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Hình thức nhận</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th width="160">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['dh_ma'] ?></td>
                            <td>
                                <?= htmlspecialchars($order['dh_tenkh']) ?>
                                <br><small class="text-muted"><?= htmlspecialchars($order['dh_sdt']) ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($order['dh_htnhan']) ?>
                                <?php if (!empty($order['ch_ten'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($order['ch_ten']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-danger fw-bold"><?= number_format($order['dh_tongtien'], 0, ',', '.') ?>đ</td>
                            <td>
                                <form action="/admin/update-order-status" method="POST" class="d-flex gap-1">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="order_id" value="<?= $order['dh_ma'] ?>">
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <?php foreach (['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'] as $value => $label): ?>
                                            <option value="<?= $value ?>" <?= $order['dh_trangthai'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['dh_ngaytao'])) ?></td>
                            <td>
                                <a href="/admin/order-detail?id=<?= $order['dh_ma'] ?>" class="btn btn-sm btn-outline-success">
                                    <i class="fa fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>
