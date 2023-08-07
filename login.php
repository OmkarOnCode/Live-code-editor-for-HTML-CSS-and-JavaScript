<?php
session_start(); // Start the session (if not already started)

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = 'localhost:3307';
    $user = 'root';
    $pass = '';
    $dbname = 'code';

    $conn = mysqli_connect($host, $user, $pass, $dbname);

    // Check if the email exists in the database
    $sql = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Email exists, check if the password is correct
        $user_data = mysqli_fetch_assoc($result);
        if ($user_data['password'] === $password) {
            // Password is correct, start the session and store the user's ID and email
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_email'] = $email;

            // Redirect to the user's dashboard or a welcome page (inde.php in this case)
            header("Location: inde.php");
            exit(); // Ensure that the script stops execution after the redirect
        } else {
            // Wrong password, show error message
            $error_message = "Wrong password.";
        }
    } else {
        // User does not exist, show error message
        $error_message = "User with this email does not exist.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            paddind-top:6rem;
            background-repeat: no-repeat;
            background-image: linear-gradient(to right top, #1a2122, #13252b, #082935, #002c41, #002e4d, #03365e, #123d6f, #244380, #31539d, #3f63bc, #4e74db, #5f85fb);            background-size: 102% 696px;       
        }
        .container{
            background-color:white;
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
            padding-top:3rem;
            font-family: 'Montserrat', sans-serif;
        }
        .email{
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
        input[type=email]{
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

        .para{
            padding-top:32px;
        }
        p{
            font-family:Tahoma;
        }
        a{
            text-decoration:none;
            color: #2e7173;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="heading">
            <h1>WELCOME</h1>
        </div>
        <form action="#" method="post">

        <div class="email">
            <input type="email" name="email" id="" placeholder="Email" required>
        </div>
        <div class="pswd">
            <input type="password" name="password" id="" placeholder="Password" required>
        </div>
        <div class="submit">
            <input type="submit" name="login" value="LOGIN">
        </div>

        </form>
        <?php if (isset($error_message)) { ?>
            <p><?php echo $error_message; ?></p>
        <?php } ?>
        <div class="para">
            <p>Don't have a account? <a href="signup.php">SIGNUP</a></p>
        </div>
    </div>
</body>
</html>