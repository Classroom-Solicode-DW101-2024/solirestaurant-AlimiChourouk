<?php
session_start();
$idCmd = $_GET['idCmd'] ;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande Confirmée</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="Confirmée">
    <h1>Commande Confirmée !</h1>
    <p>Votre commande (ID : <?= htmlspecialchars($idCmd) ?>) a été enregistrée avec succès.</p>
    <p>Statut : En attente</p>
    <a href="index.php">Retour à l'accueil</a>
  </div>
</body>
</html>