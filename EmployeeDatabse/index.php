<?php
require_once 'connectdb.php';

//Checks if user searched something in the search bar
if(isset($_GET['search']) && $_GET['search'] != "") {
    $like = "%" . $_GET['search'] . "%";
    //Searches through database to filter results
    $stmt = $conn->prepare("SELECT id, name, email, keyprogramming FROM cvs
                            WHERE name LIKE ? OR keyprogramming LIKE ?");
    $stmt->bind_param("ss", $like, $like);
} else {
    //shows everything if there was no search
    $stmt = $conn->prepare("SELECT id, name, email, keyprogramming FROM cvs");
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>AstonCV</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<h1>All CVs</h1>
<!-- Searches for people who match name or skill entered -->
<form method="GET">
    <input type="text" name="search" placeholder="Search by name or skill" value="<?php echo e($_GET['search'] ?? ''); ?>">
    <button type="submit">Search</button>
</form>
<!-- Navigation through website -->
<a href="register.php">Register</a>
<a href="login.php">Login</a>
<a href="update.php">Update my CV</a>

<hr>
<!-- Displays information of people who match what the user searched -->
<?php
while($row = $result->fetch_assoc()) {
    echo "<p>";
    echo "<a href='details.php?id=" . (int)$row["id"] . "'>";
    echo "<strong>" . e($row["name"]) . "</strong></a><br>";
    echo e($row["email"]) . "<br>";
    echo e($row["keyprogramming"]);
    echo "</p><hr>";
}
?>

</body>
</html>
