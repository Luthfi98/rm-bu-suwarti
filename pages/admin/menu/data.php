
<div class="admin-container">
    <div class="admin-header">
        <h2 class="admin-title">Menu Management</h2>
        <a href="add-menu.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Item
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="admin-filters">
        <form action="data-menu.php" method="GET" class="filter-form">
            <!-- <input type="hidden" name="page" value="admin"> -->
            <div class="filter-group">
                <input type="text" 
                       name="search" 
                       placeholder="Search menu items..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       class="search-input">
                
                <select name="category" class="category-select">
                    <option value="">All Categories</option>
                    <?php
                    $categories = ['food', 'drink'];
                    foreach($categories as $cat) {
                        $selected = (isset($_GET['category']) && $_GET['category'] === $cat) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>" . ucfirst($cat) . "</option>";
                    }
                    ?>
                </select>

                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                
                <?php if(isset($_GET['search']) || isset($_GET['category'])): ?>
                    <a href="data-menu.php" class="btn btn-clear">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="admin-menu-grid">
        <?php foreach($menuItems as $item): ?>
        <div class="admin-menu-card">
            <div class="menu-card-image">
                <img src="<?php echo $baseUrl ?>assets/img/menu/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
            </div>
            <div class="menu-card-content">
                <h3 class="menu-item-name"><?php echo $item['name']; ?></h3>
                <p class="menu-item-description"><?php echo $item['description']; ?></p>
                <div class="menu-item-details">
                    <span class="menu-item-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></span>
                    <span class="menu-item-category"><?php echo ucfirst($item['category']); ?></span>
                </div>
                <div class="menu-card-actions">
                    <a href="edit-menu.php?id=<?php echo $item['id']; ?>" 
                       class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="data-menu.php?type=delete&id=<?php echo $item['id']; ?>" 
                       class="btn btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this item?')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.admin-container {
    width: 100%;
    max-width: 1200px;
    margin: 5rem auto 2rem;
    padding: 0 1rem;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    margin-top: 3rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.admin-title {
    font-size: clamp(1.5rem, 4vw, 1.8rem);
    color: white;
    margin: 0;
}

.admin-menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    padding-bottom: 1rem;
}

.admin-menu-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.admin-menu-card:hover {
    transform: translateY(-5px);
}

.menu-card-image {
    width: 100%;
    height: clamp(150px, 30vw, 200px);
    overflow: hidden;
}

.menu-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.menu-card-content {
    padding: clamp(1rem, 3vw, 1.5rem);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.menu-item-name {
    font-size: clamp(1rem, 3vw, 1.2rem);
    margin: 0 0 0.5rem;
    color: #333;
}

.menu-item-description {
    color: #666;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
    margin-bottom: 1rem;
    line-height: 1.4;
    flex-grow: 1;
}

.menu-item-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.menu-item-price {
    font-weight: bold;
    color: #2ecc71;
}

.menu-item-category {
    background: #f0f0f0;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    color: #666;
}

.menu-card-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
}

.btn {
    padding: clamp(0.4rem, 2vw, 0.5rem) clamp(0.8rem, 2vw, 1rem);
    border-radius: 4px;
    text-decoration: none;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-primary {
    background: #2ecc71;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #27ae60;
}

.btn-edit {
    background: #3498db;
    color: white;
    border: none;
}

.btn-edit:hover {
    background: #2980b9;
}

.btn-delete {
    background: #e74c3c;
    color: white;
    border: none;
}

.btn-delete:hover {
    background: #c0392b;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-danger {
    background: #fde8e8;
    color: #c81e1e;
    border: 1px solid #fbd5d5;
}

.admin-filters {
    margin-bottom: 2rem;
    background: #fff;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filter-form {
    width: 100%;
}

.filter-group {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
    width: 100%;
}

.search-input {
    flex: 1;
    min-width: 200px;
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.category-select {
    min-width: 150px;
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    background: white;
}

.btn-filter {
    background: #3498db;
    color: white;
    border: none;
}

.btn-filter:hover {
    background: #2980b9;
}

.btn-clear {
    background: #95a5a6;
    color: white;
    border: none;
}

.btn-clear:hover {
    background: #7f8c8d;
}

/* Media Queries */
@media (max-width: 1200px) {
    .admin-menu-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .admin-menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .admin-header .btn {
        width: 100%;
        justify-content: center;
    }

    .filter-group {
        flex-direction: column;
    }

    .search-input,
    .category-select {
        width: 100%;
    }

    .btn-filter,
    .btn-clear {
        width: 100%;
        justify-content: center;
    }

    .menu-card-actions {
        flex-direction: column;
    }

    .menu-card-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .admin-container {
        padding: 0 0.5rem;
    }

    .admin-menu-grid {
        grid-template-columns: repeat(6, 1fr);
        gap: 0.5rem;
    }

    .menu-card-image {
        height: 100px;
    }

    .menu-card-content {
        padding: 0.5rem;
    }

    .menu-item-name {
        font-size: 0.8rem;
        margin-bottom: 0.3rem;
    }

    .menu-item-description {
        font-size: 0.7rem;
        margin-bottom: 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .menu-item-details {
        flex-direction: column;
        gap: 0.2rem;
        align-items: flex-start;
    }

    .menu-item-price {
        font-size: 0.8rem;
    }

    .menu-item-category {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }

    .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
}
</style>
