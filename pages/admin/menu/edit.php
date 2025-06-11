<div class="admin-container" style="margin-top: 5rem;">
    <div class="admin-header">
        <h2 style="color: white; font-size: 1.8rem; margin: 0;">Edit Menu Item</h2>
        <a href="data-menu.php" class="btn btn-outline">Back to Menu</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit-menu.php?id=<?php echo $item['id']; ?>" method="POST" enctype="multipart/form-data" class="admin-form">
        <div class="form-group" style="margin-bottom: 1.8rem;">
            <label for="name" style="display: block; margin-bottom: 0.6rem; font-weight: 500; color: #2c3e50; font-size: 0.95rem;">Name</label>
            <input type="text" id="name" name="name" value="<?php echo $item['name']; ?>" required 
                style="width: 100%; padding: 0.8rem; border: 2px solid #e9ecef; border-radius: 8px; transition: all 0.3s; font-size: 1rem;"
                placeholder="Enter menu item name">
        </div>

        <div class="form-group" style="margin-bottom: 1.8rem;">
            <label for="description" style="display: block; margin-bottom: 0.6rem; font-weight: 500; color: #2c3e50; font-size: 0.95rem;">Description</label>
            <textarea id="description" name="description" required 
                style="width: 100%; padding: 0.8rem; border: 2px solid #e9ecef; border-radius: 8px; min-height: 120px; resize: vertical; transition: all 0.3s; font-size: 1rem; font-family: inherit;"
                placeholder="Describe your menu item"><?php echo $item['description']; ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.8rem;">
            <label for="price" style="display: block; margin-bottom: 0.6rem; font-weight: 500; color: #2c3e50; font-size: 0.95rem;">Price</label>
            <div style="position: relative;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d;">Rp</span>
                <input type="number" id="price" name="price" value="<?php echo $item['price']; ?>" required 
                    style="width: 100%; padding: 0.8rem 0.8rem 0.8rem 3rem; border: 2px solid #e9ecef; border-radius: 8px; transition: all 0.3s; font-size: 1rem;"
                    placeholder="0">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.8rem;">
            <label for="category" style="display: block; margin-bottom: 0.6rem; font-weight: 500; color: #2c3e50; font-size: 0.95rem;">Category</label>
            <select id="category" name="category" required 
                style="width: 100%; padding: 0.8rem; border: 2px solid #e9ecef; border-radius: 8px; background-color: #fff; transition: all 0.3s; font-size: 1rem; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill=\'%236c757d\' height=\'24\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7 10l5 5 5-5z\'/><path d=\'M0 0h24v24H0z\' fill=\'none\'/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5rem;">
                <option value="food" <?php echo $item['category'] === 'food' ? 'selected' : ''; ?>>Food</option>
                <option value="drink" <?php echo $item['category'] === 'drink' ? 'selected' : ''; ?>>Drink</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.8rem;">
            <label for="image" style="display: block; margin-bottom: 0.6rem; font-weight: 500; color: #2c3e50; font-size: 0.95rem;">Current Image</label>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <img src="assets/img/menu/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                <small style="color: #6c757d; font-size: 0.875rem;">Leave empty to keep current image</small>
                <div id="imagePreview" style="display: none; width: 100%; max-width: 300px; margin: 0 auto;">
                    <img id="preview" src="#" alt="Preview" style="width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>

        <button type="submit" class="btn" 
            style="width: 100%; padding: 1rem; background-color: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s; font-size: 1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
            Update Item
        </button>
    </form>
</div>

<style>
    .admin-container {
        padding: 2rem;
        max-width: 50%;
        width: 50%;
        margin: 0 auto;
    }
    
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        width: 100%;
    }
    
    .admin-form {
        width: 100%;
        margin: 0 auto;
        padding: 2.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    @media (max-width: 1200px) {
        .admin-container {
            width: 70%;
            max-width: 70%;
        }
    }

    @media (max-width: 768px) {
        .admin-container {
            width: 90%;
            max-width: 90%;
        }
        
        .admin-form {
            padding: 1.5rem;
        }
        
        .admin-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
    
    .btn-outline {
        padding: 0.7rem 1.5rem;
        border: 2px solid #3498db;
        color: #3498db;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .btn-outline:hover {
        background-color: #3498db;
        color: white;
        transform: translateY(-1px);
    }
    
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.15);
    }
    
    .btn:hover {
        background-color: #2980b9;
        transform: translateY(-1px);
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #adb5bd;
    }
</style>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        previewContainer.style.display = 'none';
    }
}
</script>
