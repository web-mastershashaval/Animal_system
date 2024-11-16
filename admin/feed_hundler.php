<?php 
include('config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_FILES['product_image']['name'];
    $targetDir = __DIR__ . "/uploads/";  // Use absolute path to the uploads folder
    $imageFileType = strtolower(pathinfo($productImage, PATHINFO_EXTENSION));
    $targetFile = $targetDir . uniqid() . '.' . $imageFileType;  // Use a unique filename
    $uploadOk = 1;

    // Check if the file is an image
    $check = getimagesize($_FILES['product_image']['tmp_name']);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['product_image']['size'] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Upload file and insert into the database
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO animal_products (product_name, product_price, product_image) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sds", $productName, $productPrice, $targetFile);
            if ($stmt->execute()) {
                // Redirect to the same page after successful insertion
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit(); // Ensure no further code is executed
            } else {
                echo "Error inserting product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
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
        // Redirect to the same page after successful deletion
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit(); // Ensure no further code is executed
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve all products from the database
$query = "SELECT * FROM animal_products";
$result = $conn->query($query);

// Ensure the query executed successfully
if (!$result) {
    die("Error retrieving data: " . $conn->error);
}
?>
