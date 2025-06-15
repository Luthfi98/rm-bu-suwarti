<?php
// Check if user is admin
if ($role !== 'admin') {
    header('Location: ' . $baseUrl);
    exit();
}

?>

<div class="admin-container">
    <div class="admin-header">
        <h2 style="color: white; font-size: 1.8rem; margin: 0;">Data Contact</h2>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <!-- Filter Form -->
    <div class="filter-card">
        <div class="card-body">
            <form method="GET" action="data-contact.php" class="filter-form">
                <div class="filter-group">
                    <label for="search">Cari Contact</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Cari berdasarkan nama, email, atau nomor telepon">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">

                        Filter
                    </button>
                    <a href="data-contact.php" class="btn-reset">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="orders-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contacts)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data contact</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contacts as $index => $contact): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($contact['name']) ?></td>
                                    <td><?= htmlspecialchars($contact['email']) ?></td>
                                    <td><?= htmlspecialchars($contact['phone']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus contact ini?');">
                                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                            <button type="submit" name="delete" class="btn-delete" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">
                                                <i data-feather="trash-2" style="width: 1rem; height: 1rem;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.admin-container {
    width: 80%;
    margin: 6rem auto 2rem auto;
    padding: 0 1rem;
}

.admin-header {
    margin-bottom: 2rem;
}

.admin-header h2 {
    color: #2c3e50;
    font-size: 1.8rem;
}

.filter-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.filter-form {
    padding: 1.5rem;
}

.filter-group {
    margin-bottom: 1rem;
}

.filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #4a5568;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 1rem;
}

.filter-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn-filter {
    background: #3498db;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-reset {
    color: #4a5568;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
}

.orders-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table-responsive {
    overflow-x: auto;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
    color: #4a5568;
}

.order-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #4a5568;
}

.btn-delete {
    background: #e53e3e;
    color: white;
    border: none;
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-delete:hover {
    background: #c53030;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.alert-success {
    background: #c6f6d5;
    color: #2f855a;
    border: 1px solid #9ae6b4;
}

.alert-danger {
    background: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
}

.text-center {
    text-align: center;
}

@media (max-width: 768px) {
    .admin-container {
        padding: 0 0.5rem;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .btn-filter,
    .btn-reset {
        width: 100%;
        text-align: center;
    }
    
    .order-table th,
    .order-table td {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
}
</style>
