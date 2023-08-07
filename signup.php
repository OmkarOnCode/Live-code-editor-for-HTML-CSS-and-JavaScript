<?php
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = 'localhost:3307';
    $user = 'root';
    $pass = '';
    $dbname = 'code';

    $conn = mysqli_connect($host, $user, $pass, $dbname);

    // Check if the email already exists in the database
    $check_sql = "SELECT * FROM user_form WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // User already exists, show alert box
        echo "<script>alert('User with this email already exists.');</script>";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO user_form(name, email, password) VALUES ('$name', '$email', '$password')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Registration successful, redirect to login page
            header("Location: login.php");
            exit(); // Ensure that the script stops execution after the redirect
        } else {
            // Handle the case when the insertion fails (you can add error handling here)
            echo "Registration failed.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body{
            background-repeat: no-repeat;
            background-image: linear-gradient(to right top, #1a2122, #13252b, #082935, #002c41, #002e4d, #03365e, #123d6f, #244380, #31539d, #3f63bc, #4e74db, #5f85fb);            background-size: 102% 696px;
        }
        .container{
            background-color:white;
            height: 27rem;
            width: 30%;
            margin: 0 35%;
            margin-top:7rem;
            flex-direction: column;
            align-items: center;
            border-radius:20px;
            display:flex;
            box-shadow: 10px 10px 103px black;
        }

        input{
            width:15rem;
            height:30px;
            border-radius:20px;
        }

        input[type=text],input[type=email],input[type=password]{
            padding-left:12px;
            font-family:Bahnschrift;
            font-size: 13px;
        }

        .in-1{
            padding-top:5rem;
        }

        .in-2,.in-3{
            padding-top: 23px;
        }

        .in-4{
            padding-top: 35px
        }

        input[type=submit]{
            color:white;
            padding-left:10px;
            font-family:Bahnschrift;
            height: 37px;
            width:256px;
            font-size: larger;
            padding-top: 4px;
            background-image: linear-gradient(to right top, #1a2122, #13252b, #082935, #002c41, #002e4d, #03365e, #123d6f, #244380, #31539d, #3f63bc, #4e74db, #5f85fb);            background-size: 394px;
        }

        input[type=submit]:hover{
            background: black;
        }

        p{
            padding-top: 16px;
            font-family: Tahoma;
        }
        a{
            color: #2e7173;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="" method="POST">
            <div class="in-1">
                <input type="text" name="name" id="" placeholder="Create username" required>
            </div>
            <div class="in-2">
                <input type="email" name="email" id="" placeholder="Email" required>
            </div>
            <div class="in-3">
                <input type="password" name="password" id="" placeholder="Password" required>
            </div>
            <div class="in-4">
                <input type="submit" name="submit" value="Create Account">
            </div>
        </form>
            <div>
                <p>Already have an account?<a href="login.php"> LOGIN</a></p>
            </div>
    </div>
</body>
</html>

