<?php 
$restaurants = "";
require("fech.php");

$sql = "SELECT * FROM plat";
$stmt = $pdo->query($sql);
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(isset([chercher])){
    $chercher = $_POST['chercher'];
}
$platsParCuisine = [];
foreach ($plats as $plat) {
    $platsParCuisine[$plat['TypeCuisine']][] = $plat;
}

$restaurants .= "<div class='cuisine-container'>";
foreach ($platsParCuisine as $cuisine => $plats) {
    $restaurants .= "<div class='cuisine'>";
    $restaurants .= "<h2>$cuisine</h2>";

    $restaurants .= "<div class='plats-container'>";
    foreach ($plats as $plat) {
        $restaurants .= "<div class='plat'>";
        $restaurants .= "<img src=\"{$plat['image']}\" alt=\"{$plat['nomPlat']}\">";
        $restaurants .= "<p><strong>Nom :</strong> {$plat['nomPlat']}</p>";
        $restaurants .= "<p><strong>Catégorie :</strong> {$plat['categoriePlat']}</p>";
        $restaurants .= "<p><strong>Prix :</strong> {$plat['prix']} DH</p>";
        $restaurants .= "<button>Ajouter</button>";
        $restaurants .= "</div>";
    }
    $restaurants .= "</div>";  
    $restaurants .= "</div>";  
}
$restaurants .= "</div>";  

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=1">
    <title>Restaurants</title>
</head>
<Header>
<h1>Restaurants</h1>

    <select  name="type_couisine" id="">
        <option value="Marocaine">Marocaine</option>
        <option value="Italienne">Italienne</option>
        <option value="Chinoise">Chinoise</option>
        <option value="Espagnole">Espagnole</option>
        <option value="Francaise">Francaise</option>
         <button name="chercher">cherche</button>
    </select>
</Header>
<body>
    <div>
        <?= $restaurants ?>
    </div>
</body>
</html>
