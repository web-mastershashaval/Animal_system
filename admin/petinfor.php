<?php
// Database connection
include_once('config.php');
session_start();
// Handle image upload
function uploadImage($file) {
    // Define the upload directory
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    if (getimagesize($file["tmp_name"]) === false) {
        return false;
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return false;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        return false;
    }

    // Try to upload the file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }

    return false;
}

// Add Pet
if (isset($_POST['add_pet'])) {
    $owner_name = $_POST['owner_name'];
    $pet_name = $_POST['pet_name'];
    $pet_type = $_POST['pet_type'];
    $pet_breed = $_POST['pet_breed'];
    $pet_age = $_POST['pet_age'];

    // Upload the image
    $pet_image = uploadImage($_FILES['pet_image']);
    if ($pet_image === false) {
        echo "Sorry, there was an error uploading your image.";
    } else {
        // SQL to insert new pet
        $sql = "INSERT INTO pets (owner_name, pet_name, pet_type, pet_image, pet_breed, pet_age)
                VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssi", $owner_name, $pet_name, $pet_type, $pet_image, $pet_breed, $pet_age);
            $stmt->execute();
            $stmt->close();
            header("Location: petinfor.php");  // Redirect to avoid form resubmission
            exit();
        }
    }
}

// Update Pet
if (isset($_POST['update_pet'])) {
    $pet_id = $_POST['pet_id'];
    $owner_name = $_POST['owner_name'];
    $pet_name = $_POST['pet_name'];
    $pet_type = $_POST['pet_type'];
    $pet_breed = $_POST['pet_breed'];
    $pet_age = $_POST['pet_age'];
    $pet_image = $_POST['pet_image'];  // This will be used if no new image is uploaded

    // Check if a new image is uploaded
    if ($_FILES['pet_image']['error'] == 0) {
        // Upload the new image
        $pet_image = uploadImage($_FILES['pet_image']);
        if ($pet_image === false) {
            echo "Sorry, there was an error uploading your image.";
        }
    }

    // SQL to update pet info
    $sql = "UPDATE pets 
            SET owner_name = ?, pet_name = ?, pet_type = ?, pet_image = ?, pet_breed = ?, pet_age = ? 
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssi", $owner_name, $pet_name, $pet_type, $pet_image, $pet_breed, $pet_age, $pet_id);
        $stmt->execute();
        $stmt->close();
        header("Location: petinfor.php");  // Redirect to avoid form resubmission
        exit();
    }
}

// Delete Pet
if (isset($_GET['delete_pet_id'])) {
    $pet_id = $_GET['delete_pet_id'];

    // SQL to delete pet
    $sql = "DELETE FROM pets WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $pet_id);
        $stmt->execute();
        $stmt->close();
        header("Location: petinfor.php");  // Redirect after deletion
        exit();
    }
}

// Fetch pets from the database
$sql = "SELECT * FROM pets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Information</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9fc;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            align-content: center;
        }
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 130vh;
            width: 100%;
            box-sizing: border-box;
        }

        .header h1 {
            color: #4a4a4a;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form Container */
        .form-container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            align-content: center;
            margin: 20px 0;
            display: none;
            transition: all 0.3s ease;
        }

        /* Button Styling */
        .toggle-btn {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin: 10px;
            width: 50%;
            transition: background-color 0.3s;
        }

        .toggle-btn:hover {
            background-color: #0056b3;
        }

        .submit-btn {
            background-color: #333;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #555;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 0.9em;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        /* Table Styling */
        .overview-section {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .overview-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .overview-section th,
        .overview-section td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }

        .overview-section th {
            background-color: green;
            color: #fff;
            font-weight: normal;
        }

        .overview-section td {
            color: #555;
        }

        .overview-section button {
            padding: 6px 12px;
            font-size: 0.9em;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: #fff;
            background-color: #28a745;
            transition: background-color 0.3s;
        }

        .overview-section button:hover {
            background-color: #218838;
        }

        /* Delete Button */
        .delete-btn {
            background-color: red;
            font-size: 0.9em;
            padding: 6px 12px;
        }

        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>Pet Information Management</h1>
        </div>

        <!-- Add Pet Form -->
        <div id="add-pet-form" class="form-container">
            <h2>Add a New Pet</h2>
            <form action="petinfor.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="owner_name">Owner's Name:</label>
                    <input type="text" id="owner_name" name="owner_name" required>
                </div>
                <div class="form-group">
                    <label for="pet_name">Pet's Name:</label>
                    <input type="text" id="pet_name" name="pet_name" required>
                </div>
                <div class="form-group">
                    <label for="pet_type">Pet's Type:</label>
                    <input type="text" id="pet_type" name="pet_type" required>
                </div>
                <div class="form-group">
                    <label for="pet_breed">Pet's Breed:</label>
                    <input type="text" id="pet_breed" name="pet_breed" required>
                </div>
                <div class="form-group">
                    <label for="pet_age">Pet's Age:</label>
                    <input type="number" id="pet_age" name="pet_age" required>
                </div>
                <div class="form-group">
                    <label for="pet_image">Upload Pet Image:</label>
                    <input type="file" id="pet_image" name="pet_image" required>
                </div>
                <button type="submit" name="add_pet" class="submit-btn">Add Pet</button>
            </form>
        </div>

        <!-- Toggle Form Button -->
        <button class="toggle-btn" onclick="toggleForm('add-pet-form')">Add New Pet</button>

        <!-- Pet Overview Section -->
        <div class="overview-section">
            <h2>All Pets</h2>
            <table>
                <thead>
                    <tr>
                        <th>Owner's Name</th>
                        <th>Pet Name</th>
                        <th>Pet Type</th>
                        <th>Pet Image</th>
                        <th>Pet Breed</th>
                        <th>Pet Age</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['owner_name']; ?></td>
                            <td><?php echo $row['pet_name']; ?></td>
                            <td><?php echo $row['pet_type']; ?></td>
                            <td><img src="<?php echo $row['pet_image']; ?>" alt="Pet Image" style="width: 50px; height: 50px;"></td>
                            <td><?php echo $row['pet_breed']; ?></td>
                            <td><?php echo $row['pet_age']; ?></td>
                            <td>
                                <button class="edit-btn" onclick="populateUpdateForm('<?php echo $row['id']; ?>', '<?php echo $row['owner_name']; ?>', '<?php echo $row['pet_name']; ?>', '<?php echo $row['pet_type']; ?>', '<?php echo $row['pet_image']; ?>', '<?php echo $row['pet_breed']; ?>', '<?php echo $row['pet_age']; ?>')">Edit</button>
                                <a href="petinfor.php?delete_pet_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this pet?')">
                                    <button class="delete-btn">Delete</button>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No pets found in the database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Toggle visibility of forms
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === "none" ? "block" : "none";
        }

        // Populate the Update Form with current pet data
        function populateUpdateForm(id, owner_name, pet_name, pet_type, pet_image, pet_breed, pet_age) {
            // You can populate the fields for editing a pet
            document.getElementById('owner_name').value = owner_name;
            document.getElementById('pet_name').value = pet_name;
            document.getElementById('pet_type').value = pet_type;
            document.getElementById('pet_image').value = pet_image;
            document.getElementById('pet_breed').value = pet_breed;
            document.getElementById('pet_age').value = pet_age;
            document.getElementById('update_pet_id').value = id;
            // You can switch forms for editing as needed
        }
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
