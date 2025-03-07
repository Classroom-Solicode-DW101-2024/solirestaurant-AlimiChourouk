<?php
session_start();
// Configuration de la base de données
$host = 'localhost';
$dbname = 'solirestaurant';
$username = 'root';
$password = '';

// Connexion PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . htmlspecialchars($e->getMessage()));
}

// Fonction pour obtenir le dernier ID client

    function getLastIdClient() {
        global $pdo;
            $sql = "SELECT MAX(idClient) AS maxId FROM client";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($result['maxId'])) {
                $MaxId = 0;
            } else {
                $MaxId = $result['maxId'];
            }
            return $MaxId;
        }
    
// Fonction pour vérifier si un numéro de téléphone existe

function tel_existe($tel){
    global $pdo;
    $sql = "SELECT * FROM client where telCl=:tel";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tel', $tel);
    $stmt->execute();
    $rusult = $stmt->fetch(PDO::FETCH_ASSOC);
    return $rusult;
}?>