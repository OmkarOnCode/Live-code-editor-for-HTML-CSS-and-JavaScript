<?php
// Ensure the code runs only when the form is submitted
if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Database connection details
    $host = 'localhost:3307'; // Update this with your database host
    $user = 'root'; // Update this with your database username
    $pass = ''; // Update this with your database password
    $dbname = 'code'; // Update this with your database name

    // Connect to the database
    $conn = mysqli_connect($host, $user, $pass, $dbname);

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // SQL query to check if the name and password exist in the admin table
    $query = "SELECT * FROM admin WHERE name = '$name' AND password = '$password'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query returned any rows (i.e., successful login)
    if (mysqli_num_rows($result) > 0) {
        // Start the session (if not already started)
        session_start();

        // Set a session variable to indicate the user is logged in (you can add more data as needed)
        $_SESSION['loggedIn'] = true;

        // Redirect to the desired page after successful login (replace 'dashboard.php' with the appropriate page)
        header('Location: dashboard.php');
        exit();
    } else {
        // Invalid login credentials, show an alert message
        echo '<script>alert("Invalid password or user does not exist. Please try again.");</script>';
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"></link>
    <link rel="preconnect" href="https://fonts.googleapis.com"></link>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin></link>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet"></link>
    <style>

        *{
            margin:0;
            padding:0;
        }

        body{
            padding-top:1rem;
            background-repeat: no-repeat;
            background-image: linear-gradient(to right top, #1a2122, #13252b, #082935, #002c41, #002e4d, #03365e, #123d6f, #244380, #31539d, #3f63bc, #4e74db, #5f85fb);            background-size: 102% 696px;       
        }

        .container{
            background-color:rgb(255, 255, 255);
            height: 27rem;
            width: 30%;
            margin: 0 35%;
            margin-top:7rem;
            display:flex;
            flex-direction: column;
            align-items: center;
            border-radius:20px;
            box-shadow: 10px 10px 103px black;
        }

        .heading{
            padding-top:5rem;
            font-family:'Montserrat', sans-serif;;
        }

        .name{
            padding-top:3rem;
        }
        .pswd{
            padding-top:28px;
        }

        .submit{
            padding-top:40px;
            background-color: linear-gradient(to bottom, #1658eb, #0079f8, #0094f9, #00abf3, #00bfea, #00c9e5, #00d1dc, #00d9cf, #00dbc7, #00ddbd, #00dfb1, #1ae0a4);
        }

        input{
            width:15rem;
            height:30px;
            border-radius:20px;
        }
        input[type=text]{
            padding-left:10px;
            font-family:Bahnschrift;
        }
        input[type=password]{
            padding-left:10px;
            font-family:Bahnschrift;
        }

        input[type=submit]{
            color:white;
            padding-left:10px;
            font-family:Bahnschrift;
            width:252px;
            font-size: larger;
            padding-top: 4px;
            background-image: linear-gradient(to right top, #1a2122, #13252b, #082935, #002c41, #002e4d, #03365e, #123d6f, #244380, #31539d, #3f63bc, #4e74db, #5f85fb);        }

        input[type=submit]:hover{
            background: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="heading">
            <h1>Admin Login</h1>
        </div>
        <form action="#" method="post">

        <div class="name">
            <input type="text" name="name" id="" placeholder="Name" required>
        </div>
        <div class="pswd">
            <input type="password" name="password" id="" placeholder="Password" required>
        </div>
        <div class="submit">
            <input type="submit" name="login" value="LOGIN">
        </div>
        </form>
    </div>
</body>
</html>