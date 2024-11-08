<?php
require_once 'db_connect.php'; 
require_once 'lotto.php'; 

if (!class_exists('Lotto')) {
    echo json_encode(['error' => 'Brak klasy Lotto']);
    exit;
}

if (!isset($pdo) || !($pdo instanceof PDO)) {
    echo json_encode(['error' => 'Problem z połączeniem bazy danych.']);
    exit;
}

$lotto = new Lotto(); 
$randomNumbers = $lotto->generateNumbers(); 

if (isset($_POST['userNumbers'])) {
    $userNumbers = $_POST['userNumbers'];
    
    $userNumbers = array_map('intval', $userNumbers);

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
    $matchingNumbers = $lotto->checkMatches($userNumbers, $randomNumbers);
    $numberOfMatches = count($matchingNumbers);
    //$prize = $lotto->calculatePrize($numberOfMatches); 
    try {
        $sql = "INSERT INTO results (user_numbers, random_numbers, matches, prize) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([implode(", ", $userNumbers), implode(", ", $randomNumbers), $numberOfMatches, $prize]);
    } catch (PDOException $e) {
        error_log('Błąd zapisu do bazy danych: ' . $e->getMessage(), 3, 'error.log');
        echo json_encode(['error' => 'Błąd zapisu do bazy danych']);
        exit();
    }
    $response = [
        'userNumbers' => $userNumbers,
        'randomNumbers' => $randomNumbers,
        'matches' => $matchingNumbers,
        'numberOfMatches' => $numberOfMatches,
     //   'prize' => $prize
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;

} else {
    echo json_encode(['error' => 'Proszę wybrać liczby zjebie.']);
}
?>
