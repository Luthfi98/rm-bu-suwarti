<?php
// Handle status update if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];
    if (updateOrderStatus($orderId, $status)) {
        echo "<script>alert('Status berhasil diperbarui!');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status!');</script>";
    }
}

// Get filter parameters
$search = $_GET['search'] ?? null;
$status = $_GET['status'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of items per page
$offset = ($page - 1) * $perPage;

// Get total count of orders for pagination
$totalOrders = getTotalOrders($search, $status, $startDate, $endDate);
$totalPages = ceil($totalOrders / $perPage);

// Get orders with pagination
$orders = getAllOrders($search, $status, $startDate, $endDate, $offset, $perPage);
// var_dump($orders);die;
?>


<link rel="stylesheet" href="assets/css/admin-order.css">


<!-- Add Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="admin-order-container" >
    <div class="admin-order-header" style="margin-top: 3rem;">
        <h2 style="color: white; font-size: 1.8rem; margin: 0;">Daftar Pesanan</h2>
    </div>

    <!-- Filter Form -->
    <div class="filter-card">
        <div class="card-body">
            <form method="GET" action="data-order.php" class="filter-form">
                <input type="hidden" name="page" value="adminOrder">
                
                <div class="filter-group">
                    <label for="search">Nomor Pesanan</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Masukkan nomor pesanan">
                </div>
                
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= ($_GET['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= (isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : date('Y-m-d')) ?>">
                </div>
                
                <div class="filter-group">
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars(isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d')) ?>">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <a href="index.phpdata-order.php" class="btn-reset">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-card">
        <div class="card-body">
            <style>
                .table-responsive {
                    overflow-x: auto;
                }
                .table {
                    width: 100%;
                    min-width: 1000px;
                }
            </style>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="position: sticky; left: 0; background-color: white; word-wrap: break-word;" width="10%">No. Pesanan</th>
                            <th>Nama Pemesan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada pesanan ditemukan</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td style="position: sticky; left: 0; background-color: white;"><?= htmlspecialchars($order['order_number']) ?></td>
                                <td style="word-wrap: break-word;"><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td style="text-align: right;">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                <td style="text-align: center;">
                                    <span class="badge bg-<?= getStatusColor($order['status']) ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-info" onclick="showOrderDetail(<?= $order['id'] ?>)">Detail</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination-container" style="margin-top: 20px; display: flex; justify-content: center;">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . urlencode($status) : '' ?><?= $startDate ? '&start_date=' . urlencode($startDate) : '' ?><?= $endDate ? '&end_date=' . urlencode($endDate) : '' ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);

                        if ($startPage > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1' . ($search ? '&search=' . urlencode($search) : '') . ($status ? '&status=' . urlencode($status) : '') . ($startDate ? '&start_date=' . urlencode($startDate) : '') . ($endDate ? '&end_date=' . urlencode($endDate) : '') . '">1</a></li>';
                            if ($startPage > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . urlencode($status) : '' ?><?= $startDate ? '&start_date=' . urlencode($startDate) : '' ?><?= $endDate ? '&end_date=' . urlencode($endDate) : '' ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php
                        if ($endPage < $totalPages) {
                            if ($endPage < $totalPages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . ($search ? '&search=' . urlencode($search) : '') . ($status ? '&status=' . urlencode($status) : '') . ($startDate ? '&start_date=' . urlencode($startDate) : '') . ($endDate ? '&end_date=' . urlencode($endDate) : '') . '">' . $totalPages . '</a></li>';
                        }
                        ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . urlencode($status) : '' ?><?= $startDate ? '&start_date=' . urlencode($startDate) : '' ?><?= $endDate ? '&end_date=' . urlencode($endDate) : '' ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2"></i>
                    Detail Pesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column - Order Info -->
                    <div class="col col-left">
                        <div class="order-info">
                            <div class="section-header">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Pesanan
                            </div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">No. Pesanan</span>
                                    <span id="orderNumber" class="info-value"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal</span>
                                    <span id="orderDate" class="info-value"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status</span>
                                    <span id="orderStatus" class="info-value"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Total</span>
                                    <span id="orderTotal" class="info-value"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nama Pelanggan</span>
                                    <span id="customerName" class="info-value"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Bukti Pembayaran</span>
                                    <span id="buktiPembayaran" class="info-value"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Order Items -->
                    <div class="col col-right">
                        <div class="order-items">
                            <div class="section-header">
                                <i class="fas fa-list me-2"></i>
                                Item Pesanan
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="orderItemsTable">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="status-update">
                    <select class="form-select" id="statusUpdate">
                        <option value="pending">Pending</option>
                        <option value="waiting payment">Waiting Payment</option>
                        <option value="waiting confirmation">Waiting Confirmation</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="updateOrderStatus()">
                        <i class="fas fa-save me-2"></i>
                        Update
                    </button>
                </div>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
let currentOrderId = null;
let orderModal = null;

// Initialize modal when document is ready
document.addEventListener('DOMContentLoaded', function() {
    orderModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
});

function showOrderDetail(orderId) {
    currentOrderId = orderId;
    
    // Create XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `functions/order.php?id=${orderId}`, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    const order = data.order;
                    const items = data.orderItems;
                    
                    // Update modal content
                    document.getElementById('orderNumber').textContent = order.order_number;
                    document.getElementById('orderDate').textContent = new Date(order.created_at).toLocaleString('id-ID');
                    document.getElementById('orderStatus').innerHTML = `<span class="badge bg-${getStatusColor(order.status)}">${order.status}</span>`;
                    document.getElementById('orderTotal').textContent = `Rp ${parseInt(order.total_amount).toLocaleString('id-ID')}`;
                    document.getElementById('statusUpdate').value = order.status;
                    document.getElementById('customerName').textContent = order.username;
                    if (order.payment_proof) {
                        document.getElementById('buktiPembayaran').innerHTML = `
                            <img src="${order.payment_proof}" width="200px" height="200px" alt="Bukti Pembayaran" class="img-thumbnail">
                        `;
                    } else {
                        document.getElementById('buktiPembayaran').innerHTML = '<p class="text-center">Belum ada bukti pembayaran</p>';
                    }
                    
                    // Update items table
                    const tbody = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
                    tbody.innerHTML = '';
                    items.forEach(item => {
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td>${item.name}</td>
                            <td>Rp ${parseInt(item.price).toLocaleString('id-ID')}</td>
                            <td>${item.quantity}</td>
                            <td>Rp ${parseInt(item.price * item.quantity).toLocaleString('id-ID')}</td>
                        `;
                    });
                    
                    // Show modal
                    if (orderModal) {
                        orderModal.show();
                    } else {
                        console.error('Modal not initialized');
                        alert('Terjadi kesalahan saat menampilkan modal');
                    }
                } else {
                    alert('Gagal mengambil detail pesanan');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                alert('Terjadi kesalahan saat memproses data');
            }
        } else {
            alert('Gagal mengambil detail pesanan');
        }
    };
    
    xhr.onerror = function() {
        console.error('Request failed');
        alert('Terjadi kesalahan saat mengambil detail pesanan');
    };
    
    xhr.send();
}

function updateOrderStatus() {
    if (!currentOrderId) return;
    
    const newStatus = document.getElementById('statusUpdate').value;
    
    // Create XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'functions/order.php?id=' + currentOrderId + '&status=' + newStatus, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    // Update status badge in modal
                    document.getElementById('orderStatus').innerHTML = `<span class="badge bg-${getStatusColor(newStatus)}">${newStatus}</span>`;
                    // Update status in table
                    const statusCell = document.querySelector(`tr[data-order-id="${currentOrderId}"] .badge`);
                    if (statusCell) {
                        statusCell.className = `badge bg-${getStatusColor(newStatus)}`;
                        statusCell.innerHTML = newStatus;
                    }
                    alert('Status berhasil diperbarui');
                } else {
                    alert(data.message || 'Gagal memperbarui status');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                alert('Terjadi kesalahan saat memproses data');
            }
        } else {
            alert('Gagal memperbarui status');
        }
    };
    
    xhr.onerror = function() {
        console.error('Request failed');
        alert('Terjadi kesalahan saat memperbarui status');
    };
    
    xhr.send(`order_id=${currentOrderId}&status=${newStatus}`);
}

function getStatusColor(status) {
    switch (status) {
        case 'pending': return 'warning';
        case 'processing': return 'info';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        case 'waiting confirmation': return 'primary';
        case 'waiting payment': return 'secondary';
        default: return 'secondary';
    }
}
</script>

<?php
function getStatusColor($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'processing':
            return 'info';
        case 'completed':
            return 'success';
        case 'cancelled':
            return 'danger';
        case 'waiting confirmation':
            return 'primary';
        case 'waiting payment':
            return 'secondary';
        default:
            return 'secondary';
    }
}
?>