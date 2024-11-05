<link rel="stylesheet" href="style.css">
<?php
require_once 'db_connect.php'; 
require_once 'Lotto.php'; 

// Sprawdzenie, czy klasa Lotto została załadowana
if (!class_exists('Lotto')) {
    echo json_encode(['error' => 'Nie załadowałeś klasy Lotto!']);
    exit; 
}

// Sprawdzenie połączenia z bazą danych
if (!isset($pdo) || !($pdo instanceof PDO)) {
    echo json_encode(['error' => 'Problem z połączeniem do bazy danych.']);
    exit;
}

$lotto = new Lotto(); 
$randomNumbers = $lotto->generateNumbers(); // Generowanie losowych liczb

if (isset($_POST['userNumbers'])) {
    $userNumbers = $_POST['userNumbers'];
    
    // Upewnij się, że liczby są liczbami całkowitymi
    $userNumbers = array_map('intval', $userNumbers);

    // Walidacja: sprawdzanie, czy użytkownik wybrał dokładnie 6 unikalnych liczb
    if (count($userNumbers) !== 6 || count(array_unique($userNumbers)) !== 6) {
        echo json_encode(['error' => 'Musisz wybrać dokładnie 6 różnych liczb.']);
        exit();
    }

    foreach ($userNumbers as $number) {
        if ($number < 1 || $number > 49) {
            echo json_encode(['error' => 'Liczby muszą być z zakresu od 1 do 49.']);
            exit();
        }
    }

    // Sprawdzenie trafień i wyliczenie nagrody
    $matchingNumbers = $lotto->checkMatches($userNumbers, $randomNumbers);
    $numberOfMatches = count($matchingNumbers);
    $prize = $lotto->calculatePrize($numberOfMatches); 

    try {
        // Zapis wyników do bazy danych
        $sql = "INSERT INTO results (user_numbers, random_numbers, matches) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([implode(", ", $userNumbers), implode(", ", $randomNumbers), $numberOfMatches]);
    } catch (PDOException $e) {
            
        error_log('Twoja wiadomość błędu', 3, 'error.log');

        exit();
    }

    // Przygotowanie odpowiedzi JSON
    $response = [
        'userNumbers' => $userNumbers,
        'randomNumbers' => $randomNumbers,
        'matches' => $matchingNumbers,
        'numberOfMatches' => $numberOfMatches,
        'prize' => $prize
    ];

    header('Content-Type: application/json'); 

file_put_contents('debug_log.txt', json_encode($response));

    echo json_encode($response);



} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Proszę wypełnić formularz i wybrać swoje liczby.']);
}
?>
