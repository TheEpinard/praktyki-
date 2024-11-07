<?php
session_start();
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$logFile = 'error.log';
if (!file_exists($logFile)) {
    file_put_contents($logFile, '');
} 

// Odczyt logów błędów
$logs = file($logFile);
if ($logs === false) {
    $logs = [];
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Konsola Błędów - Administrator</title>
</head>
<body>
    <h1>Konsola Błędów</h1>
    <?php if (!empty($logs)): ?>
        <pre class="error"><?php echo htmlspecialchars(implode('', $logs)); ?></pre>
    <?php else: ?>
        <p>Brak błędów w logach.</p>
    <?php endif; ?>
    <p><a href="index.php">Wróć do strony głównej</a></p>
</body>
</html>
