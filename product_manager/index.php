<?php
require('../model/database.php');
require('../model/category.php');
require('../model/category_db.php');
require('../model/product.php');
require('../model/product_db.php');
$categoryDB = new CategoryDB();
$productDB = new ProductDB();
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'list_products';
}
if ($action == 'list_products') {
    $category_id = $_GET['category_id'];
    if ($category_id == null || $category_id == false) {
        $category_id = 1;
    }
    $current_category = $categoryDB->getCategory($category_id);
    $categories = $categoryDB->getCategories();
    $products = $productDB->getProductsByCategory($category_id);
    include('product_list.php');
} else if ($action == 'delete_product') {
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];
    $productDB->deleteProduct($product_id);
    header("Location: .?category_id=$category_id");
} else if ($action == 'show_add_form') {
    $categories = $categoryDB->getCategories();
    include('product_add.php');
} else if ($action == 'add_product') {
    $category_id = $_POST['category_id'];
    $code = $_POST['code'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    if (empty($code) || empty($name) || empty($price)) {
        $error = "Invalid product data. Check all fields and try again.";
        include('../errors/error.php');
    } else {
        $current_category = $categoryDB->getCategory($category_id);
        $product = new Product();
		$product->setCategory($current_category);
		$product->setCode($code);
		$product->setName($name);
		$product->setPrice($price);
        $productDB->addProduct($product);
        header("Location: .?category_id=$category_id");
    }
}
?>
