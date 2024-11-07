<?php
session_start();

// Dane logowania administratora
$correctUsername = 'Epinard';
$correctPassword = 'BaBeczka2115!';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $correctUsername && $_POST['password'] === $correctPassword) {
        $_SESSION['isAdmin'] = true; // Administrator zalogowany
        header('Location: error_log.php'); // Przekierowanie do panelu logów
        exit;
    } else {
        $error = 'Nieprawidłowy login lub hasło.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Logowanie Administratora</title>
</head>
<body>
    <h2>Logowanie Administratora</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Login:</label><br>
        <input type="text" name="username" required><br>
        <label>Hasło:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Zaloguj</button>
    </form>
</body>
</html>
