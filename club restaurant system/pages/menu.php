<?php
include('../config/config.php'); // connect to DB
include('../includes/header.php');
include('../includes/navbar.php');
// Fetch menu items
$query = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $query);
?>

<h2>🍽️ Menu / Stock Management</h2>

<!-- Add New Menu Item Form -->
<form action="menu_actions.php" method="POST">
    <input type="text" name="item_name" placeholder="Item Name" required>
    <input type="text" name="category" placeholder="Category" required>
    <input type="number" name="price" placeholder="Price" required>
    <input type="number" name="stock" placeholder="Stock" required>
    <button type="submit" name="add_item">Add Item</button>
</form>

<hr>

<!-- Display Existing Items -->
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['item_name']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo $row['stock']; ?></td>
        <td>
            <a href="menu_actions.php?edit=<?php echo $row['id']; ?>">Edit</a> |
            <a href="menu_actions.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this item?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php
include('../includes/footer.php');
?>