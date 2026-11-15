<?php
session_start();
require_once 'connectdb.php';

$message = "";
//Check if login button has been pressed
if(isset($_POST['login'])) {
    $email = $_POST['email']; //Gets users input
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM cvs WHERE email = ?"); //finds user in database
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) { //checks if the user exists and fetches their data
        $user = $result->fetch_assoc();
        //password verification
        if(password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            $message = 'Login successful';
        } else {
            $message = 'Wrong password';
        }
    } else {
        $message = 'User not found';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<h2>Login</h2>
<?php if($message != "") { echo "<p>" . e($message) . "</p>"; } ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
</form>

<a href="index.php">Back</a>
<?php if(isset($_SESSION['user_id'])) { ?>
    <a href="logout.php">Logout</a>
<?php } ?>
</body>
</html>
