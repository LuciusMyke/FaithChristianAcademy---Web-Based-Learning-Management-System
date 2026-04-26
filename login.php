<?php
session_start();
$servername = "sql110.infinityfree.com";  // Replace with your database server name
$username = "if0_41176520";     // Replace with your MySQL username
$password = "1W89jn4xLkI";     // Replace with your MySQL password
$dbname = "if0_41176520_faith";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["username"];
    $password = $_POST["password"];

    // Sanitize input to prevent SQL injection
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    // Query to check user credentials
    $query = "SELECT * FROM student WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // User found, set session and then redirect
        $_SESSION['email'] = $email;
        header("location: index.php");
        exit(); // Exit here to prevent further execution
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="ring">
  <i style="--clr:#00ff0a;"></i>
  <i style="--clr:#ff0057;"></i>
  <i style="--clr:#fffd44;"></i>
  <form action="login.php" method="POST" class="login">
  <h2>Login</h2>
<?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>
  <div class="inputBx">
    <input type="text" name="username" placeholder="Username" required>
  </div>

  <div class="inputBx">
    <input type="password" name="password" placeholder="Password" required>
  </div>

  <div class="inputBx">
    <input type="submit" name="login" value="Sign in">
  </div>

  <div class="links">
    <a href="#">Forget Password</a>
    <a href="#">Signup</a>
  </div>
</form>

</div>
</body>
</html>