<?php
session_start();

$servername = "sql110.infinityfree.com";
$username = "if0_41176520";
$password = "1W89jn4xLkI";
$dbname = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {

    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $query = "SELECT * FROM student WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        $_SESSION['email'] = $email;
        header("Location: ./home/dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

/* ================= REGISTER ================= */
if (isset($_POST['create'])) {

    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $check = $conn->query("SELECT * FROM student WHERE email='$email'");

    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO student (name, email, password)
                   VALUES ('$name', '$email', '$hashedPassword')";

        if ($conn->query($insert) === TRUE) {
            $success = "Account created successfully!";
        } else {
            $error = "Error creating account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Sign Up / Sign In</title>
    <link rel="stylesheet" type="text/css" href="../loginn.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <div class="main">
        <!-- CREATE ACCOUNT FORM -->
        <div class="container a-container" id="a-container">
            <form id="a-form" class="form" method="" action="">
                <h2 class="form_title title">Create Account</h2>
                
                <div class="form__icons">
                    <img class="form__icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSI1MHB4IiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA1MCA1MCIgd2lkdGg9IjUwcHgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6c2tldGNoPSJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnMiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48dGl0bGUvPjxkZWZzLz48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIj48ZyBmaWxsPSIjMDAwMDAwIiBpZD0iRmFjZWJvb2siPjxwYXRoIGQ9Ik0yNSw1MCBDMzguODA3MTE5NCw1MCA1MCwzOC44MDcxMTk0IDUwLDI1IEM1MCwxMS4xOTI4ODA2IDM4LjgwNzExOTQsMCAyNSwwIEMxMS4xOTI4ODA2LDAgMCwxMS4xOTI4ODA2IDAsMjUgQzAsMzguODA3MTE5NCAxMS4xOTI4ODA2LDUwIDI1LDUwIFogTTI1LDQ3IEMzNy4xNTAyNjUxLDQ3IDQ3LDM3LjE1MDI2NTEgNDcsMjUgQzQ3LDEyLjg0OTczNDkgMzcuMTUwMjY1MSwzIDI1LDMgQzEyLjg0OTczNDksMyAzLDEyLjg0OTczNDkgMywyNSBDMywzNy4xNTAyNjUxIDEyLjg0OTczNDksNDcgMjUsNDcgWiBNMjYuODE0NTE5NywzNiBMMjYuODE0NTE5NywyNC45OTg3MTIgTDMwLjA2ODc0NDksMjQuOTk4NzEyIEwzMC41LDIxLjIwNzYwNzIgTDI2LjgxNDUxOTcsMjEuMjA3NjA3MiBMMjYuODIwMDQ4NiwxOS4zMTAxMjI3IEMyNi44MjAwNDg2LDE4LjMyMTM0NDIgMjYuOTIwNzIwOSwxNy43OTE1MzQxIDI4LjQ0MjU1MzgsMTcuNzkxNTM0MSBMMzAuNDc2OTYyOSwxNy43OTE1MzQxIEwzMC40NzY5NjI5LDE0IEwyNy4yMjIyNzY5LDE0IEMyMy4zMTI4NzU3LDE0IDIxLjkzNjg2NzgsMTUuODM5MDkzNyAyMS45MzY4Njc4LDE4LjkzMTg3MDkgTDIxLjkzNjg2NzgsMjEuMjA4MDM2NiBMMTkuNSwyMS4yMDgwMzY2IEwxOS41LDI0Ljk5OTE0MTMgTDIxLjkzNjg2NzgsMjQuOTk5MTQxMyBMMjEuOTM2ODY3OCwzNiBMMjYuODE0NTE5NywzNiBaIE0yNi44MTQ1MTk3LDM2IiBpZD0iT3ZhbC0xIi8+PC9nPjwvZz48L3N2Zz4=" alt="">
                    <img class="form__icon" src="data:image/svg+xml;base64,..." alt="">
                    <img class="form__icon" src="data:image/svg+xml;base64,..." alt="">
                </div>

                <span class="form__span">or use email for registration</span>
                <input class="form__input" type="text" placeholder="Name">
                <input class="form__input" type="text" placeholder="Email">
                <input class="form__input" type="password" placeholder="Password">
                <button class="form__button button submit">SIGN UP</button>
            </form>
        </div>

        <!-- SIGN IN FORM -->
        <div class="container b-container" id="b-container">
            <form id="b-form" class="form" method="" action="">
                <h2 class="form_title title">Sign in to Website</h2>
                
                <div class="form__icons">
                    <img class="form__icon" src="data:image/svg+xml;base64,..." alt="">
                    <img class="form__icon" src="data:image/svg+xml;base64,..." alt="">
                    <img class="form__icon" src="data:image/svg+xml;base64,..." alt="">
                </div>

                <span class="form__span">or use your email account</span>
                <input class="form__input" type="text" placeholder="Email">
                <input class="form__input" type="password" placeholder="Password">
                <a class="form__link" href="#">Forgot your password?</a>
                <button class="form__button button submit">SIGN IN</button>
            </form>
        </div>

        <!-- SWITCH BUTTONS -->
        <div class="switch" id="switch-cnt">
            <div class="switch__circle"></div>
            <div class="switch__circle switch__circle--t"></div>

            <div class="switch__container" id="switch-c1">
                <h2 class="switch__title title">Welcome Back !</h2>
                <p class="switch__description description">To keep connected with us please login with your personal info</p>
                <button class="switch__button button switch-btn">SIGN IN</button>
            </div>

            <div class="switch__container is-hidden" id="switch-c2">
                <h2 class="switch__title title">Hello Friend !</h2>
                <p class="switch__description description">Enter your personal details and start journey with us</p>
                <button class="switch__button button switch-btn">SIGN UP</button>
            </div>
        </div>
    </div>

    <script src="../js/loginn.js"></script>
</body>
</html>