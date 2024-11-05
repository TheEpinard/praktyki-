<link rel="stylesheet" href="style.css">
<?php
class Lotto {
    private $min;
    private $max;
    private $quantity;

    public function __construct($min = 1, $max = 49, $quantity = 6) {
        $this->min = $min;
        $this->max = $max;
        $this->quantity = $quantity;
    }

    public function generateNumbers() {
        $numbers = [];
        while (count($numbers) < $this->quantity) {
            $number = rand($this->min, $this->max);
            if (!in_array($number, $numbers)) {
                $numbers[] = $number;
            }
        }
        return $numbers;
    }

    public function checkMatches($userNumbers, $randomNumbers) {
        return array_intersect($userNumbers, $randomNumbers);
    }

    public function calculatePrize($numberOfMatches) {
        switch ($numberOfMatches) {
            case 6:
                return "1 000 000 zł";
            case 5:
                return "50 000 zł";
            case 4:
                return "5 000 zł";
            case 3:
                return "500 zł";
            default:
                return "0 zł";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lotto = new Lotto();
    $userNumbers = isset($_POST['userNumbers']) ? $_POST['userNumbers'] : []; 

    if (count($userNumbers) != 6 || count(array_unique($userNumbers)) != 6 || min($userNumbers) < 1 || max($userNumbers) > 49) {
        echo "Proszę wybrać 6 unikalnych liczb od 1 do 49.";
        exit; 
    }

    $randomNumbers = $lotto->generateNumbers(); 

    $matchingNumbers = $lotto->checkMatches($userNumbers, $randomNumbers);
    $numberOfMatches = count($matchingNumbers);
    $prize = $lotto->calculatePrize($numberOfMatches);

    echo "Twoje liczby: " . implode(", ", $userNumbers) . "<br>";
    echo "Wylosowane liczby: " . implode(", ", $randomNumbers) . "<br>";
    echo "Trafione liczby: " . implode(", ", $matchingNumbers) . "<br>";
    echo "Liczba trafień: " . $numberOfMatches . "<br>";
    echo "Twoja wygrana: " . $prize;


} else {
    echo '<form action="" method="post">
            <label>Wybierz 6 unikalnych liczb od 1 do 49:</label><br>';
    for ($i = 0; $i < 6; $i++) {
        echo '<input type="number" name="userNumbers[]" min="1" max="49" required><br>'; 
    }
    echo '<button type="submit">Zagraj!</button>
          </form>';
}
?>
