<?php 
$restaurants = "";
require("fech.php");

$sql = "SELECT * FROM plat";
$stmt = $pdo->query($sql);
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['chercher'])) {
    $chercher = $_POST['type_cuisine'];
} elseif($_POST['cherche']) {
    $chercher = $_POST['type_categorie'];
}
else {
    $chercher = ''; // Si aucun type n'est sélectionné, afficher tous les plats
}

$platsParCuisine = [];
foreach ($plats as $plat) {
    $platsParCuisine[$plat['TypeCuisine']][] = $plat;
}

$restaurants .= "<div class='cuisine-container'>";

if ($chercher === '') {
    // Afficher tous les plats si aucun type n'est sélectionné
   
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
            $restaurants .= "<p><strong>Prix :</strong> {$plat['prix']} $</p>";
            $restaurants .= "<button>Ajouter</button>";
            $restaurants .= "</div>";
        }
        $restaurants .= "</div>";  
        $restaurants .= "</div>";  
    }
    $restaurants .= "</div>";  
    
} else {
    // Afficher les plats filtrés par type de cuisine
    if (isset($platsParCuisine[$chercher])) {
        $restaurants .= "<div class='cuisine'>";
        $restaurants .= "<h2>$chercher</h2>";
        $restaurants .= "<div class='plats-container'>";
        foreach ($platsParCuisine[$chercher] as $plat) {
            $restaurants .= "<div class='plat'>";
            $restaurants .= "<img src=\"{$plat['image']}\" alt=\"{$plat['nomPlat']}\">";
            $restaurants .= "<p><strong>Nom :</strong> {$plat['nomPlat']}</p>";
            $restaurants .= "<p><strong>Catégorie :</strong> {$plat['categoriePlat']}</p>";
            $restaurants .= "<p><strong>Prix :</strong> {$plat['prix']} $</p>";
            $restaurants .= "<button>Ajouter</button>";
            $restaurants .= "</div>";
        }
        $restaurants .= "</div>";  
        $restaurants .= "</div>";  
    } else {
        $restaurants .= "<p>Aucun plat trouvé pour ce type de cuisine.</p>";
    }
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
    <form method="POST">
        <select name="type_cuisine" id="type_cuisine">
            <option value="">Sélectionner un type de cuisine</option>
            <option value="Marocaine">Marocaine</option>
            <option value="Italienne">Italienne</option>
            <option value="Chinoise">Chinoise</option>
            <option value="Espagnole">Espagnole</option>
            <option value="Francaise">Francaise</option>
        </select>
        <select name="type_categorie" id="tye-categorie">
                <option value="">Sélectionner un type de categorie</option>
                <option value="plat_principal">plat principal</option>
                <option value="dessert">dessert</option>
                <option value="entree">entrée</option>
    </select>
        <button type="submit" name="chercher">Chercher</button>
    </form>
</Header>
<body>
    <div>
        <?= $restaurants ?>
    </div>
</body>
</html>
