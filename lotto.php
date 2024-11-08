<?php
class Lotto {
    private int $min;
    private int $max;
    private int $quantity;

    public function __construct(int $min = 1, int $max = 49, int $quantity = 6) {
        $this->min = $min;
        $this->max = $max;
        $this->quantity = $quantity;
    }

    public function generateNumbers(): array {
        $range = range($this->min, $this->max);
        return array_rand(array_flip($range), $this->quantity);
    }

    public function checkMatches(array $userNumbers, array $randomNumbers): array {
        return array_intersect($userNumbers, $randomNumbers);
    }

    public function calculatePrize(int $numberOfMatches): string {
        return match ($numberOfMatches) {
            6 => "1 000 000 zł",
            5 => "50 000 zł",
            4 => "5 000 zł",
            3 => "500 zł",
            default => "0 zł",
        };
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    
    $lotto = new Lotto();
    $userNumbers = $_POST['userNumbers'] ?? [];

    // Walidacja danych wejściowych
    if (!is_array($userNumbers) || count($userNumbers) != 6) {
        echo json_encode(['error' => 'Proszę wybrać 6 liczb.']);
        exit;
    }

    // Konwersja wartości na liczby całkowite i sprawdzenie unikalności oraz zakresu
    $userNumbers = array_map('intval', $userNumbers);
    if (count(array_unique($userNumbers)) != 6 || min($userNumbers) < 1 || max($userNumbers) > 49) {
        echo json_encode(['error' => 'Proszę wybrać 6 unikalnych liczb od 1 do 49.']);
        exit;
    }

    // Generowanie losowych numerów i sprawdzanie zgodności
    $randomNumbers = $lotto->generateNumbers();
    $matchingNumbers = $lotto->checkMatches($userNumbers, $randomNumbers);
    $numberOfMatches = count($matchingNumbers);
   // $prize = $lotto->calculatePrize($numberOfMatches);

    // Przygotowanie i wysłanie odpowiedzi
    $response = [
        'userNumbers' => $userNumbers,
        'randomNumbers' => $randomNumbers,
        'matchingNumbers' => array_values($matchingNumbers),
        'numberOfMatches' => $numberOfMatches,
        'prize' => "sos BBQ"
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Proszę wypełnić formularz i wysłać dane.']);
}
?>
