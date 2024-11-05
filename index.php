<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Symulator Lotto</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="process.php" method="post" id="lottoForm">
        <h2>Symulator Lotto</h2>
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

    <script src="script.js"></script>
</body>

</html>
