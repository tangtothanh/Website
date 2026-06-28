<?php
$statusLabels = [
    'pending' => ['Chờ xác nhận', 'secondary'],
    'confirmed' => ['Đã xác nhận', 'info'],
    'completed' => ['Hoàn thành', 'success'],
    'cancelled' => ['Đã hủy', 'danger'],
];
?>
<div class="container mt-5 mb-5">
    <h3 class="text-success mb-4">
        <i class="fa-solid fa-receipt me-2"></i>
        Đơn hàng của tôi
    </h3>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fa-solid fa-box-open fa-3x mb-3 text-muted"></i>
            <h5>Bạn chưa có đơn hàng nào</h5>
            <a href="/san-pham" class="btn btn-success mt-3">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Hình thức nhận</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <?php $status = $statusLabels[$order['dh_trangthai']] ?? [$order['dh_trangthai'], 'secondary']; ?>
                            <tr>
                                <td>#<?= $order['dh_ma'] ?></td>
                                <td>
                                    <?= htmlspecialchars($order['dh_htnhan']) ?>
                                    <?php if (!empty($order['ch_ten'])): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($order['ch_ten']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-danger fw-bold"><?= number_format($order['dh_tongtien'], 0, ',', '.') ?> VNĐ</td>
                                <td><span class="badge bg-<?= $status[1] ?>"><?= htmlspecialchars($status[0]) ?></span></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['dh_ngaytao'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
