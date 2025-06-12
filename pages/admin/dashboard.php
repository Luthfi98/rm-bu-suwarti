<?php
require_once __DIR__ . '/../../config/database.php';

// Get total orders
$totalOrdersQuery = "SELECT COUNT(*) as total FROM orders";
$totalOrdersStmt = $conn->prepare($totalOrdersQuery);
$totalOrdersStmt->execute();
$totalOrders = $totalOrdersStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get pending revenue
$pendingRevenueQuery = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE status = 'pending'";
$pendingRevenueStmt = $conn->prepare($pendingRevenueQuery);
$pendingRevenueStmt->execute();
$pendingRevenue = $pendingRevenueStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get total revenue
$totalRevenueQuery = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE status = 'completed'";
$totalRevenueStmt = $conn->prepare($totalRevenueQuery);
$totalRevenueStmt->execute();
$totalRevenue = $totalRevenueStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get sales data for chart
$salesDataQuery = "SELECT 
    mi.name as menu_name,
    SUM(oi.quantity) as total_quantity,
    SUM(oi.quantity * oi.price) as total_revenue
FROM order_items oi
JOIN menu_items mi ON oi.menu_item_id = mi.id
JOIN orders o ON oi.order_id = o.id
WHERE o.status = 'completed'
GROUP BY mi.id, mi.name
ORDER BY total_quantity DESC
LIMIT 10";
$salesDataStmt = $conn->prepare($salesDataQuery);
$salesDataStmt->execute();
$salesData = $salesDataStmt->fetchAll(PDO::FETCH_ASSOC);

// Get category sales data
$categoryDataQuery = "SELECT 
    mi.category,
    SUM(oi.quantity) as total_quantity,
    SUM(oi.quantity * oi.price) as total_revenue
FROM order_items oi
JOIN menu_items mi ON oi.menu_item_id = mi.id
JOIN orders o ON oi.order_id = o.id
WHERE o.status = 'completed'
GROUP BY mi.category";
$categoryDataStmt = $conn->prepare($categoryDataQuery);
$categoryDataStmt->execute();
$categoryData = $categoryDataStmt->fetchAll(PDO::FETCH_ASSOC);

// Debug information
error_log("Category Data: " . print_r($categoryData, true));

// Prepare data for charts
$menuNames = array_column($salesData, 'menu_name');
$quantities = array_column($salesData, 'total_quantity');
$revenues = array_column($salesData, 'total_revenue');

$categories = array_column($categoryData, 'category');
$categoryQuantities = array_column($categoryData, 'total_quantity');
$categoryRevenues = array_column($categoryData, 'total_revenue');

// Debug information
error_log("Categories: " . print_r($categories, true));
error_log("Category Quantities: " . print_r($categoryQuantities, true));
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 style="color: white;">Dashboard</h1>
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
        
        <div class="charts-container">
            <div class="chart-card">
                <h3>Grafik Penjualan Menu</h3>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <h3>Penjualan per Kategori</h3>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
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
    width: 90%;
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
    display: flex;
    gap: 1.5rem;
    margin-top: 2rem;
    overflow-x: auto;
    padding-bottom: 1rem;
}

.charts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
    min-width: 250px;
    flex: 1;
}

.chart-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-container {
    height: 400px;
    margin-top: 1rem;
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
        min-width: 200px;
    }

    .chart-container {
        height: 300px;
    }

    .charts-container {
        grid-template-columns: 1fr;
    }
}

@media screen and (max-width: 480px) {
    .dashboard-container {
        padding: 0.5rem;
        margin-top: 5rem;
    }
    
    .dashboard-cards {
        flex-direction: column;
    }

    .card {
        width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart for Top Menu Items
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($menuNames); ?>,
            datasets: [{
                label: 'Jumlah Penjualan',
                data: <?php echo json_encode($quantities); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Terjual'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Menu'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Top 10 Menu Terlaris',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const quantity = context.raw || 0;
                            const revenue = <?php echo json_encode($revenues); ?>[context.dataIndex];
                            return [
                                `Jumlah Terjual: ${quantity} item`,
                                `Pendapatan: Rp ${Number(revenue).toLocaleString('id-ID')}`
                            ];
                        }
                    }
                }
            }
        }
    });

    // Pie Chart for Category Distribution
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($categories); ?>,
            datasets: [{
                data: <?php echo json_encode($categoryQuantities); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribusi Penjualan per Kategori',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            const revenue = <?php echo json_encode($categoryRevenues); ?>[context.dataIndex];
                            return [
                                `${label}: ${value} item (${percentage}%)`,
                                `Pendapatan: Rp ${Number(revenue).toLocaleString('id-ID')}`
                            ];
                        }
                    }
                }
            }
        }
    });
});
</script>
