<?php phpinfo(); ?>
// Toggle form visibility
        function toggleForm(formId) {
            var form = document.getElementById(formId);
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        // Populate the update form with data
        function populateUpdateForm(id, owner_name, pet_name, pet_type, pet_image, pet_breed, pet_age) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_owner_name').value = owner_name;
            document.getElementById('update_pet_name').value = pet_name;
            document.getElementById('update_pet_type').value = pet_type;
            document.getElementById('update_pet_breed').value = pet_breed;
            document.getElementById('update_pet_age').value = pet_age;
            document.getElementById('updateForm').style.display = 'block'; // Show update form
        }


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

        /* Action Buttons (Edit & Delete) */
        .overview-section button {
            padding: 8px 16px;
            font-size: 0.9em;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .overview-section .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .overview-section .edit-btn:hover {
            background-color: #0056b3;
        }

        .overview-section .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .overview-section .delete-btn:hover {
            background-color: #c82333;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .form-container, .overview-section {
                max-width: 100%;
                padding: 15px;
            }
        }
    </style>


//animal products


<?php 
include('config.php');
session_start();
// Query to fetch products
$sql = "SELECT id, product_name, product_price, product_image FROM animal_products";  // Make sure 'products' is your correct table name
$result = $conn->query($sql);  // Execute the query and store the result

// Check if the query was successful
if (!$result) {
    die("Error fetching products: " . $conn->error);  // Handle any SQL query errors
}
?>

<header>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1, h2 {
        text-align: center;
        color: #333;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .main-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100%;
        width: 100%;
        box-sizing: border-box;
        padding: 20px;
    }

    .form-container {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .overview-section {
        margin-top: 29px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: green;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f4f4f4;
    }

    img {
        border-radius: 5px;
    }

    .delete-button {
        color: #fff;
        background-color: #dc3545;
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .delete-button:hover {
        background-color: #c82333;
    }
</style>
</header>

<div class="main-container">
    <div class="dashboard">
        <div class="form-container" id="productForm">
            <h2>Add New Product</h2>
            <form action="feed_hundler.php" method="POST" enctype="multipart/form-data">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>

                <label for="product_price">Product Price:</label>
                <input type="number" id="product_price" name="product_price" required step="0.01">

                <label for="product_image">Product Image:</label>
                <input type="file" id="product_image" name="product_image" accept="image/*" >

                <input type="submit" name="add_product" value="Add Product">
            </form>
        </div>

        <div class="overview-section">
            <h2>Existing Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Product Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_price']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" width="100">
                                </td>
                                <td>
                                    <a href="?delete=<?php echo htmlspecialchars($row['id']); ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No products available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="../scripts/animalfeed.js" defer></script>

<?php $conn->close(); ?>
