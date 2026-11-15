<?php
require_once 'connectdb.php';
require_once 'jargon.php';

$message = "";
// checks if register has been pressed and gets details
if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashPassword = password_hash($password, PASSWORD_DEFAULT); //hashes password

    // adds new user to database
    $stmt = $conn->prepare("INSERT INTO cvs (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashPassword);

    try {
        $stmt->execute();
        $message = "Registration complete!";
    } catch (mysqli_sql_exception $ex) {
        // error 1062 = duplicate email (email column is UNIQUE)
        if($ex->getCode() == 1062) {
            $message = "That email is already registered.";
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo e(jargonify('Register')); ?></title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="<?php echo jargon_on() ? 'jargon' : ''; ?>">
<?php if(jargon_on()) { ?>
<div class="jargon-banner">Synergy mode active: all talent has been aligned to core competencies.</div>
<?php } ?>
<h2><?php echo e(jargonify('Register')); ?></h2>
<?php if($message != "") { echo "<p>" . e(jargonify($message)) . "</p>"; } ?>
<!-- Form for registration window/page -->
<form method="POST">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="<?php echo e(jargonify('Email')); ?>" required><br>
    <input type="password" name="password" placeholder="<?php echo e(jargonify('Password')); ?>" required><br>
    <button name="register"><?php echo e(jargonify('Register')); ?></button>
</form>

<a href="index.php"><?php echo e(jargonify('Back')); ?></a>
<a href="<?php echo e(jargon_toggle_url()); ?>" class="jargon-toggle"><?php echo e(jargon_label()); ?></a>
</body>
</html>
