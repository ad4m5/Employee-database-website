<?php
require_once 'connectdb.php';

$id = (int)($_GET['id'] ?? 0); //gets ID
// Finds ID in table and gets the data
$stmt = $conn->prepare("SELECT name, email, keyprogramming, profile, education, URLlinks FROM cvs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if(!$row) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CV Details</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<?php if(!$row) { ?>
    <h1>CV not found</h1>
<?php } else { ?>
    <!-- Outputs data on the window -->
    <h1><?php echo e($row['name']); ?></h1>
    <p>Email: <?php echo e($row['email']); ?></p>
    <p>Skills: <?php echo e($row['keyprogramming']); ?></p>
    <p>Profile: <?php echo e($row['profile']); ?></p>
    <p>Education: <?php echo e($row['education']); ?></p>
    <p>Links: <?php echo e($row['URLlinks']); ?></p>
<?php } ?>

    <a href="index.php">Back</a>
</body>
</html>
