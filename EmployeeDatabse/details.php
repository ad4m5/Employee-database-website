<?php
require_once 'connectdb.php';
require_once 'jargon.php';

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
    <title><?php echo e(jargonify('CV Details')); ?></title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="<?php echo jargon_on() ? 'jargon' : ''; ?>">

<?php if(jargon_on()) { ?>
<div class="jargon-banner">Synergy mode active: all talent has been aligned to core competencies.</div>
<?php } ?>

<?php if(!$row) { ?>
    <h1><?php echo e(jargonify('CV not found')); ?></h1>
<?php } else { ?>
    <!-- Outputs data on the window -->
    <h1><?php echo e($row['name']); ?></h1>
    <p><?php echo e(jargonify('Email')); ?>: <?php echo e($row['email']); ?></p>
    <p><?php echo e(jargonify('Skills')); ?>: <?php echo e(jargonify($row['keyprogramming'])); ?></p>
    <p><?php echo e(jargonify('Profile')); ?>: <?php echo e(jargonify($row['profile'])); ?></p>
    <p><?php echo e(jargonify('Education')); ?>: <?php echo e(jargonify($row['education'])); ?></p>
    <p><?php echo e(jargonify('Links')); ?>: <?php echo e($row['URLlinks']); ?></p>
<?php } ?>

    <a href="index.php"><?php echo e(jargonify('Back')); ?></a>
    <a href="<?php echo e(jargon_toggle_url()); ?>" class="jargon-toggle"><?php echo e(jargon_label()); ?></a>
</body>
</html>
