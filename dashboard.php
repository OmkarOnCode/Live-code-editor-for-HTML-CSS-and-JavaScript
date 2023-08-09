<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Panel</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            background-image: linear-gradient(to right top, #161fb3, #3c21b3, #5323b3, #6527b3, #742bb3, #5c45c2, #3e57cd, #0067d4, #007ed3, #008fc3, #009bad, #4aa49c);
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        h2 {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        form {
            display: inline-block;
            margin-right: 5px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form input[type="submit"] {
            padding: 5px;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            display: inline-block; /* Add this line to make buttons appear side by side */
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .container {
            margin: 0;
            max-width: 1259px;
            border: 1px solid #ddd;
            padding: 20px;
            padding-right: 76px;
        }
        .update-form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello Admin</h1>

        <!-- Add the HTML form for creating a new admin -->
        <h2>Create New Admin</h2>
        <form method="post">
            <input type="text" name="name" required placeholder="Name"><br>

            <input type="password" name="password" required placeholder="Password"><br>

            <input type="submit" name="create" value="Create Admin">
        </form>

        <!-- Add the PHP code here -->

<?php
// Replace these variables with your actual database credentials
$host = "localhost:3307";
$username = "root";
$password = "";
$database = "code";

// Create a database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to securely hash the password
//function hashPassword($password) {
//    return password_hash($password, PASSWORD_BCRYPT);
//}

// Function to validate and sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

// CREATE - Add a new admin to the database
if (isset($_POST['create'])) {
    // Get user input from the form
    $name = sanitizeInput($_POST['name']);
    $password = sanitizeInput($_POST['password']); // Hash the password before storing

    // Insert the data into the database
    // Insert the data into the user_form table
    $insertUserSql = "INSERT INTO admin (name, password) VALUES ('$name','$password')";
    if (mysqli_query($conn, $insertUserSql)) {
        // New admin created successfully.
        echo "<script>alert('New admin created successfully.');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// READ - Fetch user data from the database
$sql = "SELECT id, name, email, password FROM user_form";
$result = mysqli_query($conn, $sql);

// UPDATE - Update user data in the database
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Hash the password before updating (if provided)
    //$hashedPassword = hashPassword($password);

    $updateSql = "UPDATE user_form SET name='$name', email='$email', password='$password' WHERE id='$id'";
    if (mysqli_query($conn, $updateSql)) {
        echo "User updated successfully.";

        // Redirect to dashboard.php after updating
        ob_start(); // Start output buffering
        header("Location: dashboard.php");
        ob_end_flush(); // Flush output buffer and redirect
        exit; // Make sure to exit after the redirect
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}


// DELETE - Delete user data from the database
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Define the DELETE query for user_form table
    $deleteUserSql = "DELETE FROM user_form WHERE id='$id'";
    if (mysqli_query($conn, $deleteUserSql)) {
        echo "<script>alert('User deleted successfully.');</script>";
        // Redirect to the admin panel after deletion
        header("Location: dashboard.php");
        exit; // Make sure to exit after the redirect
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}


// Display admin data in a table
echo "<h2>Admin List</h2>";

// SELECT admins from the admin table
$adminSql = "SELECT name, password FROM admin";
$adminResult = mysqli_query($conn, $adminSql);

if (mysqli_num_rows($adminResult) > 0) {
    echo "<table>";
    echo "<tr><th>Name</th><th>Password</th></tr>";

    while ($adminRow = mysqli_fetch_assoc($adminResult)) {
        echo "<tr>";
        echo "<td>" . $adminRow['name'] . "</td>";
        echo "<td>" . $adminRow['password'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No admins found.";
}

// Display user data in a table
echo "<h2>User List</h2>";
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Password</th><th>Actions</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' name='delete' value='Delete'>
                </form>
            </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No users found.";
}

// Close the database connection
mysqli_close($conn);
?>

        <!-- Add the HTML form for creating a new user -->
        <!-- <h2>Create New User</h2>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <input type="submit" name="create" value="Create User">
        </form> -->
    </div>
</body>
</html>
