<?php
session_start();  // Start session at the top of your PHP file

// Ensure session variable exists before using it
if (!isset($_SESSION['username'])) {
    echo "Owner name is not set.";
    exit;
} else {
    $ownerName = $_SESSION['username'];
}

include('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];  // Get logged-in user ID

// Check for valid connection
if ($conn === false) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Handling POST requests for Add, Update, and Delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Pet
    if (isset($_POST['add_pet'])) {
        $petName = $_POST['pet_name'];
        $petType = $_POST['pet_type'];
        $petBreed = $_POST['pet_breed'];
        $petAge = $_POST['pet_age'];

        $sql = "INSERT INTO pets (pet_name, owner_name, pet_type, pet_breed, pet_age, user_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Query preparation failed: ' . $conn->error);
        }
        $stmt->bind_param("ssssii", $petName, $ownerName, $petType, $petBreed, $petAge, $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Update Pet
    if (isset($_POST['update_pet'])) {
        $petName = $_POST['pet_name'];
        $petType = $_POST['pet_type'];
        $petBreed = $_POST['pet_breed'];
        $petAge = $_POST['pet_age'];
        $petId = $_POST['pet_id'];

        $sql = "UPDATE pets SET pet_name=?, owner_name=?, pet_type=?, pet_breed=?, pet_age=? 
                WHERE id=? AND user_id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Query preparation failed: ' . $conn->error);
        }
        $stmt->bind_param("ssssiiii", $petName, $ownerName, $petType, $petBreed, $petAge, $petId, $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Delete Pet
    if (isset($_POST['delete_pet'])) {
        $petId = $_POST['pet_id'];

        $sql = "DELETE FROM pets WHERE id=? AND user_id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Query preparation failed: ' . $conn->error);
        }
        $stmt->bind_param("ii", $petId, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch only the pets related to the logged-in user
$query = "SELECT id, pet_name, pet_type, pet_breed, pet_age FROM pets WHERE user_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Query preparation failed: ' . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Information</title>
    <style>
        /* General Reset */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; display: flex; min-height: 100vh; flex-direction: column; }
        .main-content { padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 28px; }
        .form-container, .overview-section { width: 100%; max-width: 600px; background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: none; }
        .form-container h2, .overview-section h2 { text-align: center; }
        .toggle-btn { margin: 10px; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .toggle-btn:hover { background-color: #0056b3; }
        .submit-btn { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .submit-btn:hover { background-color: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .form-group { margin-bottom: 15px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 8px 12px; border: 1px solid #ccc; background-color: #ffc107; cursor: pointer; }
        button:hover { background-color: #e0a800; }
    </style>
</head>
<body>

<div class="main-content">
    <button class="toggle-btn" onclick="toggleSection('addPetSection')">Add Pet</button>
    <button class="toggle-btn" onclick="toggleSection('updatePetSection')">Update Pet</button>
    <button class="toggle-btn" onclick="toggleSection('petOverviewSection')">View Pets</button>

    <!-- Add Pet Form Section -->
    <section class="form-container" id="addPetSection">
        <h2>Add New Pet</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group"><label for="pet_name">Pet Name</label><input type="text" id="pet_name" name="pet_name" required></div>
            <div class="form-group"><label for="pet_type">Pet Type</label><input type="text" id="pet_type" name="pet_type" required></div>
            <div class="form-group"><label for="pet_breed">Pet Breed</label><input type="text" id="pet_breed" name="pet_breed" required></div>
            <div class="form-group"><label for="pet_age">Pet Age</label><input type="number" id="pet_age" name="pet_age" required></div>
            <div class="form-group"><input type="submit" name="add_pet" value="Add Pet" class="submit-btn"></div>
        </form>
    </section>

    <!-- Update Pet Form Section -->
    <section class="form-container" id="updatePetSection">
        <h2>Update Pet</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" id="pet_id" name="pet_id"> <!-- This will be populated by JavaScript -->
            <div class="form-group"><label for="pet_name_update">Pet Name</label><input type="text" id="pet_name_update" name="pet_name" required></div>
            <div class="form-group"><label for="pet_type_update">Pet Type</label><input type="text" id="pet_type_update" name="pet_type" required></div>
            <div class="form-group"><label for="pet_breed_update">Pet Breed</label><input type="text" id="pet_breed_update" name="pet_breed" required></div>
            <div class="form-group"><label for="pet_age_update">Pet Age</label><input type="number" id="pet_age_update" name="pet_age" required></div>
            <div class="form-group"><input type="submit" name="update_pet" value="Update Pet" class="submit-btn"></div>
        </form>
    </section>

    <!-- Pets Overview Section -->
    <section class="overview-section" id="petOverviewSection">
        <h2>Your Pets</h2>
        <table>
            <thead><tr><th>Pet ID</th><th>Pet Name</th><th>Type</th><th>Breed</th><th>Age</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['pet_name']; ?></td>
                        <td><?php echo $row['pet_type']; ?></td>
                        <td><?php echo $row['pet_breed']; ?></td>
                        <td><?php echo $row['pet_age']; ?></td>
                        <td>
                            <button onclick="editPet(<?php echo $row['id']; ?>)">Edit</button>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="pet_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="delete_pet" value="Delete" onclick="return confirm('Are you sure you want to delete this pet?');">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>

<script>
    function toggleSection(sectionId) {
        var sections = document.querySelectorAll('.form-container, .overview-section');
        sections.forEach(function (section) {
            section.style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }

    function editPet(petId) {
        // Fetch pet data by petId (you could do this with AJAX)
        alert('Editing pet with ID: ' + petId);
        // For now, just pre-fill the form for demonstration
        document.getElementById('pet_id').value = petId;
        document.getElementById('pet_name_update').value = 'Dummy Name';  // Update with actual data
        document.getElementById('pet_type_update').value = 'Dummy Type';  // Update with actual data
        document.getElementById('pet_breed_update').value = 'Dummy Breed';  // Update with actual data
        document.getElementById('pet_age_update').value = 2;  // Update with actual data
        toggleSection('updatePetSection');
    }
</script>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
