<?php include('../includes/navbar.php'); ?>
<?php
include('../config/config.php');

// --- ADD ITEM ---
if (isset($_POST['add_item'])) {
    $name = $_POST['item_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "INSERT INTO menu_items (item_name, category, price, stock)
              VALUES ('$name', '$category', '$price', '$stock')";
    mysqli_query($conn, $query);

    header('Location: menu.php');
    exit();
}

// --- DELETE ITEM ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM menu_items WHERE id=$id");
    header('Location: menu.php');
    exit();
}

// --- EDIT ITEM (Show Form) ---
if (isset($_GET['edit'])) {
    include('../includes/header.php');
    include('../includes/navbar.php');

    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM menu_items WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    ?>

    <h2>Edit Menu Item</h2>
    <form action="menu_actions.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="item_name" value="<?php echo $row['item_name']; ?>" required>
        <input type="text" name="category" value="<?php echo $row['category']; ?>" required>
        <input type="number" name="price" value="<?php echo $row['price']; ?>" required>
        <input type="number" name="stock" value="<?php echo $row['stock']; ?>" required>
        <button type="submit" name="update_item">Update</button>
    </form>

    <?php
    include('../includes/footer.php');
    exit();
}

// --- UPDATE ITEM ---
if (isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $name = $_POST['item_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "UPDATE menu_items SET 
                item_name='$name', 
                category='$category', 
                price='$price', 
                stock='$stock' 
              WHERE id=$id";

    mysqli_query($conn, $query);
    header('Location: menu.php');
    exit();
}
?>