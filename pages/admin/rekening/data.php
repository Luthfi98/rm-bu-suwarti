<?php
// Handle status update if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bank']) && isset($_POST['number']) && isset($_POST['name'])) {
    $bank = $_POST['bank'];
    $number = $_POST['number'];
    $name = $_POST['name'];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        if (updateRekening($id, $bank, $number, $name)) {
            echo "<script>alert('Data rekening berhasil diperbarui!'); window.location.href='data-rekening.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data rekening!');</script>";
        }
    } else {
        if (addRekening($bank, $number, $name)) {
            echo "<script>alert('Data rekening berhasil ditambahkan!'); window.location.href='data-rekening.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data rekening!');</script>";
        }
    }
}

// Get filter parameters
$search = $_GET['search'] ?? null;

// Get orders with pagination
$orders = getRekenings($search);
// var_dump($orders);die;

$editing = false;
$editOrder = null;

if (isset($_GET['edit']) && $_GET['edit'] === 'true' && isset($_GET['id'])) {
    $editOrder = getRekening($_GET['id']);
    if ($editOrder) {
        $editing = true;
    }
}
?>

<link rel="stylesheet" href="assets/css/admin-order.css">

<!-- Add Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="admin-order-container">
    <div class="admin-order-header" style="margin-top: 3rem;">
        <h2 style="color: white; font-size: 1.8rem; margin: 0;">Daftar Rekening</h2>
    </div>

    <!-- Filter Form -->
    <div class="filter-card">
        <div class="card-body">
            <form method="GET" action="data-rekening.php" class="filter-form">
                <div class="filter-group">
                    <label for="search">Cari Rekening</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Cari Data Rekening">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <a href="data-rekening.php" class="btn-reset">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th>Nama Bank</th>
                            <th>Nomor Rekening</th>
                            <th>Nama Rekening</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Tidak ada rekening ditemukan</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['bank']) ?></td>
                                <td><?= htmlspecialchars($order['number']) ?></td>
                                <td><?= htmlspecialchars($order['name']) ?></td>
                                <td style="text-align: center;">
                                    <a href="data-rekening.php?edit=true&id=<?= $order['id'] ?>" class="btn btn-warning">Edit</a>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $order['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <div class="orders-card" style="margin-top: 20px;">
        <div class="card-body">
        <div style="">
                <h3 style="color: #2c3e50;">Form <?= $editing ? 'Edit Data Rekening' : 'Tambahkan Data Rekening' ?></h3>
                <form method="POST" action="data-rekening.php">
                    <?php if ($editing): ?>
                        <input type="hidden" name="id" value="<?= $editOrder['id'] ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="bank">Nama Bank</label>
                        <input type="text" class="form-control" id="bank" name="bank" value="<?= $editing ? htmlspecialchars($editOrder['bank']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="number">Nomor Rekening</label>
                        <input type="number" class="form-control" id="number" name="number" value="<?= $editing ? htmlspecialchars($editOrder['number']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Pemilik Rekening</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $editing ? htmlspecialchars($editOrder['name']) : '' ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= $editing ? 'Update' : 'Tambahkan' ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Anda yakin ingin menghapus rekening ini?')) {
            window.location.href = 'data-rekening.php?delete=true&id=' + id;
        }
    }
    
    <?php if (isset($_GET['delete']) && $_GET['delete'] === 'true' && isset($_GET['id'])) {
        $id = $_GET['id'];
        if (deleteRekening($id)) {
            echo "alert('Rekening berhasil dihapus!'); window.location.href='data-rekening.php';";
        } else {
            echo "alert('Gagal menghapus rekening!');";
        }
    } ?>
</script>

