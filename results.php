<?php
session_start();
require 'db_connect.php';

// Sprawdzenie, czy użytkownik jest zalogowany jako administrator
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header('Location: admin_login.php'); // Przekierowanie do logowania dla nieadminów
    exit;
}

// Pobranie historii gier z bazy danych
$sql = "SELECT * FROM results ORDER BY date DESC";
$stmt = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historia Gier - Administrator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Historia Gier</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Data</th>
            <th>Twoje Liczby</th>
            <th>Wylosowane Liczby</th>
            <th>Trafienia</th>
            <th>Wygrana</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['user_numbers']); ?></td>
                <td><?php echo htmlspecialchars($row['random_numbers']); ?></td>
                <td><?php echo htmlspecialchars($row['matches']); ?></td>
                <td><?php echo htmlspecialchars($row['prize']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <form action="index.php" method="get">
        <button type="submit">Wróć</button>
    </form>
</body>
</html>
