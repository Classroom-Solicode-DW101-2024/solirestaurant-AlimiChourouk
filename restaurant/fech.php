<?php
$host = "localhost";
$dbname = "SoliRestaurant";
$username = "root";
$password = "";
$port = 8889; // Utilisez 3306 si c'est le port par défaut

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie !";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
