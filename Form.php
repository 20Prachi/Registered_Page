<?php
// Database connection
$servername = "localhost"; // Database server (usually localhost)
$username = "root";        // Database username
$password = "";            // Database password (leave empty if you're using XAMPP)
$dbname = "user_form_Data";     // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = ""; // Initialize the success message

if (isset($_POST["submit"])) {
    $name = isset($_POST["name"]) ? $_POST["name"] : " ";
    $email = isset($_POST["email"]) ? $_POST["email"] : " ";
    $phoneno = isset($_POST["phoneno"]) ? $_POST["phoneno"] : " ";
    // Initialize error flags
    $nameerror = false;
    $emailerror = false;
    $phoneerror = false;

    // Validate the name using regular expression
    if (!empty($name) && !preg_match("#^[a-zA-Z\s]+$#", $name)) {
        $nameerror = true;
    }
    
    // Validate email using FILTER_VALIDATE_EMAIL
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailerror = true;
    }

    // Validate phone number (must be 10 digits)
    if (!empty($phoneno) && !preg_match("#^\d{10}$#", $phoneno)) {
        $phoneerror = "Phone number should be 10 digits";
    } else {
        $checkphoneno = "SELECT PhoneNumber FROM user_form WHERE PhoneNumber='$phoneno'";
        $checkphoneno_run = mysqli_query($conn, $checkphoneno);
        if (mysqli_num_rows($checkphoneno_run) > 0) {
            $phoneerror = "Phone number already exists"; // Phone number already exists
        }
    }
    // If no errors, insert data into the database
    if (!$nameerror && !$emailerror && !$phoneerror) {
        $sqlquery = "INSERT INTO `user_form`(`Name`, `Email`, `PhoneNumber`) VALUES ('$name','$email','$phoneno')";
        $result = mysqli_query($conn, $sqlquery); // Execute the query
        
        // Check if the data was inserted successfully
        if ($result) {
            $successMessage = "User registered successfully 🤩";
        } else {
            $errorMessage = "Oops! There is an error 🫤 " . mysqli_error($conn);
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form in HTML</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 12px;
            padding: 5px;
            display: none;
            margin-top: 5px;
        }

        .show-error {
            display: block;
        }

        .form-container label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        
        .success-message {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: 900;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Register Here</h2>
        <div>
    <?php
    if (!empty($successMessage)) {
        echo "<p class='success-message'>$successMessage</p>";
    } elseif (!empty($errorMessage)) {
        echo "<p class='success-message' style='color: red;'>$errorMessage</p>";
    }
    ?>
</div>

        <form method="post">
            <!-- Error message for name field -->
            <label for="name">Enter your name</label>
            <input type="text" name="name" id="name" value=""/>
            <span class="error" id="nameerror">Only letters and spaces are allowed for the name field</span>

            <!-- Error message for email field -->
            <label for="email">Enter your email</label>
            <input type="text" name="email" id="email" value=""/>
            <span class="error" id="emailerror">Email is invalid</span>

            <!-- Error message for phone number field -->
            <label for="phoneno">Enter your phone number</label>
            <input type="text" name="phoneno" id="phoneno" value=""/>
            <span class="error" id="phoneerror"><?php if (isset($phoneerror) && $phoneerror) { echo "" . $phoneerror . ""; } else { echo ""; } ?></span>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

    <script>
        var nameerror = document.getElementById("nameerror");
        var emailerror = document.getElementById("emailerror");
        var phoneerror = document.getElementById("phoneerror");

        var nameInput = document.getElementById("name");
        var emailInput = document.getElementById("email");
        var phoneInput = document.getElementById("phoneno");

        var nameerrorflag = <?php echo $nameerror ? 'true' : 'false'; ?>;
        var emailerrorflag = <?php echo $emailerror ? 'true' : 'false'; ?>;
        var phoneerrorflag = <?php echo $phoneerror ? 'true' : 'false'; ?>;

        if (nameerrorflag) {
            nameerror.classList.add('show-error');
        } else {
            nameerror.classList.remove('show-error');
        }

        if (emailerrorflag) {
            emailerror.classList.add('show-error');
        } else {
            emailerror.classList.remove('show-error');
        }

        if (phoneerrorflag) {
            phoneerror.classList.add('show-error');
        } else {
            phoneerror.classList.remove('show-error');
        }

        nameInput.addEventListener('keyup', function () {
            if (nameInput.value.match(/^[a-zA-Z\s]+$/)) {
                nameerror.classList.remove('show-error');
            }
        });

        emailInput.addEventListener('keyup', function () {
            if (emailInput.value.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
                emailerror.classList.remove('show-error');
            }
        });

        phoneInput.addEventListener('keyup', function () {
            if (phoneInput.value.match(/^\d{10}$/)) {
                phoneerror.classList.remove('show-error');
            }
        });
    </script>
</body>
</html>
