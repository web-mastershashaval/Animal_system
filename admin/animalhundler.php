<?php 
include('config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define uploads directory
$targetDir = __DIR__ . "/uploads/";  

// Handle form submission for adding a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_FILES['product_image']['name'];

    // Validate uploads directory
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true); // Create directory if it doesn't exist
    }

    $imageFileType = strtolower(pathinfo($productImage, PATHINFO_EXTENSION));
    $uniqueFileName = uniqid() . '.' . $imageFileType;  // Unique filename
    $targetFile = $targetDir . $uniqueFileName;

    $uploadOk = 1;

    // Validate image
    $check = getimagesize($_FILES['product_image']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES['product_image']['size'] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Move uploaded file and insert into database
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO animal_products (product_name, product_price, product_image) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sds", $productName, $productPrice, $uniqueFileName);
            if ($stmt->execute()) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error inserting product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading the file.";
        }
    }
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    $sql = "DELETE FROM animal_products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve all products
$query = "SELECT * FROM animal_products";
$result = $conn->query($query);

if (!$result) {
    die("Error retrieving data: " . $conn->error);
}
?>
