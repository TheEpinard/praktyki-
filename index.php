<?php
session_start();
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    echo '<p>Nie masz uprawnień do wyświetlenia panelu administratora.</p>';
    exit; 
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Symulator Lotto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Symulator Lotto</h2>
<form action="process.php" method="post" id="lottoForm">
    <label>Wybierz 6 liczb (1-49):</label><br>
    <?php for ($i = 1; $i <= 6; $i++): ?>
        <input type="number" name="userNumbers[]" min="1" max="49" required><br>
    <?php endfor; ?>
    <label for="draws">Liczba losowań:</label>
    <input type="number" id="draws" name="numberOfDraws" min="1" value="1" required><br>
    <button type="submit">Zagraj</button>
</form>

<div id="loader" style="display: none;">Ładowanie...</div>
<div id="result"></div>
<p><a href="results.php">Zobacz historię gier</a></p>

<p><a href="admin_login.php">Panel Administratora</a></p>

<script src="script.js"></script>
</body>
</html>
