<?php 
$restaurants = "";
require("fech.php");

$sql = "SELECT * FROM plat";
$stmt = $pdo->query($sql);
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_SESSION["client"])) {
    $platsParCuisine = [];
    foreach ($plats as $plat) {
        $platsParCuisine[$plat['TypeCuisine']][] = $plat;
    }

    // Vérifier si une recherche a été effectuée
    if(isset($_POST['chercher'])) {
        $type_cuisine = isset($_POST['type_cuisine']) ? $_POST['type_cuisine'] : '';
        $type_categorie = isset($_POST['type_categorie']) ? $_POST['type_categorie'] : '';

        $restaurants .= "<div class='cuisine'>";
        if ($type_cuisine !== '') {
            $restaurants .= "<h2>$type_cuisine</h2>";
        }
        
        $restaurants .= "<div class='plats-container'>";
        foreach ($plats as $plat) {
            $afficherPlat = false;
            
            if ($type_cuisine !== '' && $type_categorie !== '') {
                if ($plat['TypeCuisine'] === $type_cuisine && $plat['categoriePlat'] === $type_categorie) {
                    $afficherPlat = true;
                }
            } elseif ($type_cuisine !== '' && $plat['TypeCuisine'] === $type_cuisine) {
                $afficherPlat = true;
            } elseif ($type_categorie !== '' && $plat['categoriePlat'] === $type_categorie) {
                $afficherPlat = true;
            }

            if ($afficherPlat) {
                $restaurants .= "<div id='{$plat['idPlat']}' class='plat'>";
                $restaurants .= "<img src=\"{$plat['image']}\" alt=\"{$plat['nomPlat']}\">";
                $restaurants .= "<p><strong>Nom :</strong> {$plat['nomPlat']}</p>";
                $restaurants .= "<p><strong>Catégorie :</strong> {$plat['categoriePlat']}</p>";
                $restaurants .= "<p><strong>Prix :</strong> {$plat['prix']} $</p>";
                $restaurants .= "<form action='ajouter_panier.php' method='POST'>";
                $restaurants .= "<button type='submit' name='ajouter' value='{$plat['idPlat']}'>Ajouter</button>";
                $restaurants .= "</form>";
                $restaurants .= "</div>";
            }
        }
        $restaurants .= "</div>";  
        $restaurants .= "</div>";  
    }

    // Affichage global si aucun filtre n'est appliqué
    if ((!isset($_POST['type_cuisine']) || $_POST['type_cuisine'] === '') && 
        (!isset($_POST['type_categorie']) || $_POST['type_categorie'] === '')) {
        
        $restaurants .= "<div class='cuisine-container'>";
        
        foreach ($platsParCuisine as $cuisine => $plats) {
            $restaurants .= "<div class='cuisine'>";
            $restaurants .= "<h2>$cuisine</h2>";
            $restaurants .= "<div class='plats-container'>";
            
            foreach ($plats as $plat) {
                $restaurants .= "<div id='{$plat['idPlat']}' class='plat'>";
                $restaurants .= "<img src=\"{$plat['image']}\" alt=\"{$plat['nomPlat']}\">";
                $restaurants .= "<p><strong>Nom :</strong> {$plat['nomPlat']}</p>";
                $restaurants .= "<p><strong>Catégorie :</strong> {$plat['categoriePlat']}</p>";
                $restaurants .= "<p><strong>Prix :</strong> {$plat['prix']} $</p>";
                $restaurants .= "<form action='ajouter_panier.php' method='POST'>";
                $restaurants .= "<button type='submit' name='ajouter' value='{$plat['idPlat']}'>Ajouter</button>";
                $restaurants .= "</form>";
                $restaurants .= "</div>";
            }
            $restaurants .= "</div>";  
            $restaurants .= "</div>";  
        }
        
        $restaurants .= "</div>"; // Fermeture correcte de .cuisine-container
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Restaurants</title>
</head>
<header>
    <div id="imgTitre">
        <img id="img" src="img/logo.png" alt="logo de l'application">
        <h1>Restaurants</h1>
    </div>
    <form method="POST">
        <select name="type_cuisine" id="type_cuisine">
            <option value="">Sélectionner un type de cuisine</option>
            <option value="Marocaine">Marocaine</option>
            <option value="Italienne">Italienne</option>
            <option value="Chinoise">Chinoise</option>
            <option value="Espagnole">Espagnole</option>
            <option value="Francaise">Française</option>
        </select>
        <select name="type_categorie" id="type_categorie">
            <option value="">Sélectionner une catégorie</option>
            <option value="plat principal">Plat principal</option>
            <option value="dessert">Dessert</option>
            <option value="entrée">Entrée</option>
        </select>
        <button type="submit" name="chercher">Chercher</button>
    </form>
    <a href="panier.php">
    <span class="counter"> (<?= count($_SESSION['panier']) ?>) </span>
        <img class="imgPanier" src="img/panierr.png" alt="panier">
    </a>
</header>
<section id="section1">
    <img src="img/burger.png" alt="image de burger">
</section>
<body>
    <div>
        <?= $restaurants ?>
    </div>
    <footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>À propos</h3>
            <p>Découvrez une expérience culinaire unique avec une variété de plats soigneusement préparés.</p>
        </div>
        <div class="footer-section">
            <h3>Contact</h3>
            <p>Email : contact@restaurant.com</p>
            <p>Téléphone : +212 600 000 000</p>
            <p>Adresse : Tanger , Maroc</p>
        </div>
        <div class="footer-section">
            <h3>Suivez-nous</h3>
            <div>
            <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
            <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
            </div>
        </div>
    </div>
    <p class="footer-credit">&copy; 2025 Restaurant | Tous droits réservés.</p>
</footer>
</body>
</html>
