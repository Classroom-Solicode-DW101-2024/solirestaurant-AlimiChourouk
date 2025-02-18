<?php
$restaurants = "";
require("fech.php");

$sql = "SELECT * FROM plat";
$stmt = $pdo->query($sql);
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);


$platsParCuisine = [];
foreach ($plats as $plat) {
    $platsParCuisine[$plat['TypeCuisine']][] = $plat;
}


foreach ($platsParCuisine as $cuisine => $plats) {
    $restaurants .= "<h2>$cuisine</h2>"; 
    foreach ($plats as $plat) {
        $restaurants .= "<div class='plat'>";
        $restaurants .= "<img src=\"{$plat['image']}\" alt=\"{$plat['nomPlat']}\">";
        $restaurants .= "<p><strong>Nom :</strong> {$plat['nomPlat']}</p>";
        $restaurants .= "<p><strong>Catégorie :</strong> {$plat['categoriePlat']}</p>";
        $restaurants .= "<p><strong>Prix :</strong> {$plat['prix']} DH</p>";
        $restaurants .= "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=1">
    <title>Restaurants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            background-color: #f8b400;
            padding: 10px;
            color: white;
            border-radius: 5px;
        }
        .plat {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .plat img {
            width: 150px;
            height: auto;
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Restaurants</h1>
    <div>
        <?= $restaurants ?>
    </div>
</body>
</html>
