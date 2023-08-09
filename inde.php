<?php
session_start(); // Start the session

if (isset($_POST['save'])) {
    // Check if the user is logged in (replace 'user_id' with the actual name of your session variable)
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in"); // Handle the case when the user is not logged in
    }

    // Get the values from the textarea inputs
    $htmlCode = $_POST["html"];
    $cssCode = $_POST["css"];
    $jsCode = $_POST["js"];

    // Sanitize the input (optional, but recommended to prevent SQL injection)
    $htmlCode = htmlspecialchars($htmlCode);
    $cssCode = htmlspecialchars($cssCode);
    $jsCode = htmlspecialchars($jsCode);

    // Connect to the database (Replace 'your_username', 'your_password', and 'your_database_name' with your actual database credentials)
    $conn = mysqli_connect("localhost:3307", "root", "", "code");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get the user ID from the session (replace 'user_id' with the actual name of your session variable)
    $user_id = $_SESSION['user_id'];

    // Check if the user already has saved code in the database
    $existing_p_id = null;
    $check_sql = "SELECT p_id FROM program WHERE u_id = '$user_id'";
    $result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($result) > 0) {
        $existing_code_data = mysqli_fetch_assoc($result);
        $existing_p_id = $existing_code_data['p_id'];
    }

    // Prepare the SQL statement with placeholders
    if ($existing_p_id) {
        // If existing_p_id is not null, perform an update
        $sql = "UPDATE program SET html_code = ?, css_code = ?, js_code = ? WHERE p_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $htmlCode, $cssCode, $jsCode, $existing_p_id);
    } else {
        // If existing_p_id is null, perform an insert
        $sql = "INSERT INTO program (html_code, css_code, js_code, u_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $htmlCode, $cssCode, $jsCode, $user_id);
    }

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $p_id = $existing_p_id ? $existing_p_id : mysqli_insert_id($conn);
        mysqli_stmt_close($stmt); // Close the prepared statement
        mysqli_close($conn); // Close the database connection
        echo "<script>alert('Code saved');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['load'])) {
    if (isset($_SESSION['user_id'])) {
        // User is logged in, proceed with loading code from the database
        $user_id = $_SESSION['user_id'];

        // Connect to the database (Replace 'your_username', 'your_password', and 'your_database_name' with your actual database credentials)
        $conn = mysqli_connect("localhost:3307", "root", "", "code");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare the SQL statement to fetch code from the database
        $sql = "SELECT html_code, css_code, js_code FROM program WHERE u_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user_id);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $htmlCode, $cssCode, $jsCode);
            mysqli_stmt_fetch($stmt);

            // Close the prepared statement and database connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // Output the code data as JSON
            echo json_encode(array(
                "htmlCode" => $htmlCode,
                "cssCode" => $cssCode,
                "jsCode" => $jsCode,
            ));
            exit(); // Terminate the script after sending the JSON response
        } else {
            // Error fetching code from the database
            echo json_encode(array("error" => "Failed to fetch code."));
            exit(); // Terminate the script after sending the JSON response
        }
    } else {
        // User is not logged in
        echo json_encode(array("error" => "User not logged in."));
        exit(); // Terminate the script after sending the JSON response
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITOR</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/d721a7fdaa.js" crossorigin="anonymous"></script>
</head>
<body class="all">
    <div class="body-div">
        <div class="head-div">
            <div>
                <h1 class="heading">Online C<?php echo"<Ø>"?>de Editor</h1>
            </div>
            <div class="button-class">
                <button type="submit" onclick=logout()>LOGOUT</button>
            </div>
        </div>
        <div class="container">
            <form action="" method="post" id="codeForm">
                <div class="left">
                    <div class="save-div">
                        <input class="save-class" type="submit" name="save" value="save">
                    </div>
                    <div>
                        <input type="submit" id="loadButton" name="load" value="load">
                    </div>
                    <div class="html-div">
                        <label class="lb-1"><i class="fa-brands fa-html5"></i> HTML</label>
                        <textarea name="html" id="html-code" onkeyup="run()"></textarea>
                    </div>
                    <div class="css-div">
                        <label class="lb-2"><i class="fa-brands fa-css3-alt"></i> CSS</label>
                        <textarea name="css" id="css-code" onkeyup="run()"></textarea>
                    </div>
                    <div class="js-div">
                        <label class="lb-3"><i class="fa-brands fa-square-js"></i> JAVASCRIPT</label>
                        <textarea name="js" id="js-code" onkeyup="run()"></textarea>
                    </div>
                </div>
            </form>
            <div class="right">
                <label class="lb-out"><i class="fa-solid fa-play"></i> OUTPUT</label>
                <iframe id="output"></iframe>
            </div>
        </div>
    </div>
    <script>

        // Function to handle logout and redirect to login.html
        function logout() {
            // You can clear any user session or do other necessary cleanup here before redirecting
            window.location.href = "login.php";
        }

        function run() {
            var htmlCode = document.getElementById("html-code").value;
            var cssCode = document.getElementById("css-code").value;
            var jsCode = document.getElementById("js-code").value;
            var output = document.getElementById("output");

            output.contentDocument.body.innerHTML = htmlCode + "<style>" + cssCode + "</style>";
            output.contentWindow.eval(jsCode);
        }

        function handleTabKey(event, textarea) {
        if (event.keyCode === 9) {
            event.preventDefault();
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            textarea.value =
                textarea.value.substring(0, start) +
                "\t" +
                textarea.value.substring(end);
            textarea.selectionStart = textarea.selectionEnd = start + 1;
        }
    }

    function handleEnterKey(event, textarea) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var value = textarea.value;
            var beforeCursor = value.substring(0, start);
            var afterCursor = value.substring(end, value.length);

            // Check if the line contains a semicolon before the cursor
            var semicolonIndex = beforeCursor.lastIndexOf(";");
            if (semicolonIndex !== -1 && semicolonIndex > beforeCursor.lastIndexOf("{")) {
                var lineIndentation = beforeCursor.substring(semicolonIndex + 1).match(/^\s*/)[0];
                textarea.value = beforeCursor + "\n" + lineIndentation + afterCursor;
                textarea.selectionStart = textarea.selectionEnd = start + lineIndentation.length + 1;
            } else {
                textarea.value = beforeCursor + "\n" + afterCursor;
                textarea.selectionStart = textarea.selectionEnd = start + 1;
            }
        }
    }

    document.getElementById("html-code").addEventListener("keydown", function (event) {
        handleTabKey(event, this);
        handleEnterKey(event, this);
    });

    document.getElementById("css-code").addEventListener("keydown", function (event) {
        handleTabKey(event, this);
        handleEnterKey(event, this);
    });

    document.getElementById("js-code").addEventListener("keydown", function (event) {
        handleTabKey(event, this);
        handleEnterKey(event, this);
    });

    // Call the loadCode function when the "Load" button is clicked
    document.getElementById("loadButton").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent the form from submitting
        loadCode();
    });

    //--------------------load function code--------------------
    function loadCode() {
        // Set the textarea values with the retrieved code using innerHTML
        <?php
        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            // User is logged in, proceed with loading code from the database
            $user_id = $_SESSION['user_id'];

            // Connect to the database (Replace 'your_username', 'your_password', and 'your_database_name' with your actual database credentials)
            $conn = mysqli_connect("localhost:3307", "root", "", "code");

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Prepare the SQL statement to fetch code from the database
            $sql = "SELECT html_code, css_code, js_code FROM program WHERE u_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $user_id);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $htmlCode, $cssCode, $jsCode);
                mysqli_stmt_fetch($stmt);

                // Close the prepared statement and database connection
                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                // Output the code data as JavaScript variables to be used in the script
                echo "document.getElementById('html-code').value = " . json_encode(htmlspecialchars_decode($htmlCode)) . ";\n";
                echo "document.getElementById('css-code').value = " . json_encode(htmlspecialchars_decode($cssCode)) . ";\n";
                echo "document.getElementById('js-code').value = " . json_encode(htmlspecialchars_decode($jsCode)) . ";\n";
            } else {
                // Error fetching code from the database
                echo "console.log('Failed to load code from the database.');\n";
            }
        } else {
            // User is not logged in
            echo "console.log('User not logged in.');\n";
        }
        ?>

        // Now that we have loaded the code, we can call the `run()` function to display the output
        run();
    }

    // Call the loadCode function when the page loads to retrieve and display the code
    window.addEventListener("DOMContentLoaded", loadCode);
    </script>
</body>
</html>
