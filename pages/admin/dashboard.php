
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Selamat datang di panel admin Ayam Goreng Bu Suwarti</p>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-cards">
            <div class="card">
                <i data-feather="shopping-bag"></i>
                <h3>Total Pesanan</h3>
                <p><?php echo $totalOrders; ?></p>
            </div>
            <div class="card">
                <i data-feather="users"></i>
                <h3>Pendapatan Pending</h3>
                <p>Rp <?php echo number_format($pendingRevenue, 0, ',', '.'); ?></p>
            </div>
            <div class="card">
                <i data-feather="dollar-sign"></i>
                <h3>Pendapatan</h3>
                <p>Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.dashboard-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 5rem auto 0;
}

.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card i {
    width: 40px;
    height: 40px;
    color: #333;
    margin-bottom: 1rem;
}

.card h3 {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.card p {
    font-size: 1.5rem;
    font-weight: bold;
    color: #666;
}

/* Responsive styles */
@media screen and (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
        margin-top: 5rem;
    }
    
    .dashboard-header h1 {
        font-size: 1.5rem;
    }
    
    .card {
        padding: 1rem;
    }
}

@media screen and (max-width: 480px) {
    .dashboard-container {
        padding: 0.5rem;
        margin-top: 5rem;
    }
    
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
}
</style>
