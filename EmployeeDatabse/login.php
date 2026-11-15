<?php
session_start();
require_once 'connectdb.php';
require_once 'jargon.php';

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
    <title><?php echo e(jargonify('Login')); ?></title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="<?php echo jargon_on() ? 'jargon' : ''; ?>">
<?php if(jargon_on()) { ?>
<div class="jargon-banner">Synergy mode active: all talent has been aligned to core competencies.</div>
<?php } ?>
<h2><?php echo e(jargonify('Login')); ?></h2>
<?php if($message != "") { echo "<p>" . e(jargonify($message)) . "</p>"; } ?>
<form method="POST">
    <input type="email" name="email" placeholder="<?php echo e(jargonify('Email')); ?>" required>
    <input type="password" name="password" placeholder="<?php echo e(jargonify('Password')); ?>" required>
    <button name="login"><?php echo e(jargonify('Login')); ?></button>
</form>

<a href="index.php"><?php echo e(jargonify('Back')); ?></a>
<a href="<?php echo e(jargon_toggle_url()); ?>" class="jargon-toggle"><?php echo e(jargon_label()); ?></a>
<?php if(isset($_SESSION['user_id'])) { ?>
    <a href="logout.php"><?php echo e(jargonify('Logout')); ?></a>
<?php } ?>
</body>
</html>
