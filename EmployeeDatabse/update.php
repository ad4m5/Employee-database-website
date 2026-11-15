<?php
session_start();
require_once 'connectdb.php';

// send user to login page if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$message = "";

//checks if they pressed update (done before loading the user so the form shows new values)
if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $keyprogramming = $_POST['keyprogramming']; //gets updated data
    $profile = $_POST['profile'];
    $education = $_POST['education'];
    $links = $_POST['links'];

    // updates database
    $stmt = $conn->prepare("UPDATE cvs SET name=?, keyprogramming=?, profile=?, education=?, URLlinks=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $keyprogramming, $profile, $education, $links, $user_id);

    if($stmt->execute()) {
        $message = "CV updated";
    } else {
        $message = "Failed to update";
    }
}

$stmt = $conn->prepare("SELECT name, keyprogramming, profile, education, URLlinks FROM cvs WHERE id=?"); //finds user's cv
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// account no longer exists
if(!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update CV</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<h2>Update your CV</h2>
<?php if($message != "") { echo "<p>" . e($message) . "</p>"; } ?>
<form method="POST">
    Name: <input type="text" name="name" value="<?php echo e($user['name']); ?>" required><br>
    Skills: <input type="text" name="keyprogramming" value="<?php echo e($user['keyprogramming']); ?>"><br>
    Profile: <textarea name="profile"><?php echo e($user['profile']); ?></textarea><br>
    Education: <textarea name="education"><?php echo e($user['education']); ?></textarea><br>
    Links: <input type="text" name="links" value="<?php echo e($user['URLlinks']); ?>"><br>
    <button name="update">Update</button>
</form>

<a href="index.php">Back</a>
<a href="logout.php">Logout</a>
</body>
</html>
