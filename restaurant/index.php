<?php 
session_start(); // Démarrer la session pour gérer le panier

require("fech.php");

// Vérifier si une recherche a été effectuée
$chercher = isset($_POST['type_couisine']) ? $_POST['type_couisine'] : '';

// Ajouter un plat au panier
if (isset($_POST['ajouter'])) {
    $idPlat = $_POST['idPlat'];
    $nomPlat = $_POST['nomPlat'];
    $prixPlat = $_POST['prixPlat'];

    if (!isset($_SESSION['panier'][$idPlat])) {
        $_SESSION['panier'][$idPlat] = [
            'nom' => $nomPlat,
            'prix' => $prixPlat,
            'quantite' => 1
        ];
    } else {
        $_SESSION['panier'][$idPlat]['quantite']++;
    }
}

// Construire la requête SQL en fonction de la recherche
if (!empty($chercher)) {
    $sql = "SELECT * FROM plat WHERE TypeCuisine = :chercher";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':chercher', $chercher, PDO::PARAM_STR);
    $stmt->execute();
} else {
    $sql = "SELECT * FROM plat";
    $stmt = $pdo->query($sql);
}
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grouper les plats par type de cuisine
$platsParCuisine = [];
foreach ($plats as $plat) {
    $platsParCuisine[$plat['TypeCuisine']][] = $plat;
}

// Générer l'affichage des plats
$restaurants = "<div class='cuisine-container'>";
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

        // Formulaire pour ajouter au panier
        $restaurants .= "<form method='POST'>";
        $restaurants .= "<input type='hidden' name='idPlat' value='{$plat['id']}'>";
        $restaurants .= "<input type='hidden' name='nomPlat' value='{$plat['nomPlat']}'>";
        $restaurants .= "<input type='hidden' name='prixPlat' value='{$plat['prix']}'>";
        $restaurants .= "<button type='submit' name='ajouter'>Ajouter</button>";
        $restaurants .= "</form>";

        $restaurants .= "</div>";
    }
    $restaurants .= "</div></div>";  
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
<body>

<header>
    <h1>Restaurants</h1>
    <form method="POST">
        <select name="type_couisine">
            <option value="">Tous les types</option>
            <option value="Marocaine" <?= $chercher == 'Marocaine' ? 'selected' : '' ?>>Marocaine</option>
            <option value="Italienne" <?= $chercher == 'Italienne' ? 'selected' : '' ?>>Italienne</option>
            <option value="Chinoise" <?= $chercher == 'Chinoise' ? 'selected' : '' ?>>Chinoise</option>
            <option value="Espagnole" <?= $chercher == 'Espagnole' ? 'selected' : '' ?>>Espagnole</option>
            <option value="Francaise" <?= $chercher == 'Francaise' ? 'selected' : '' ?>>Française</option>
        </select>
        <button type="submit" name="chercher">Chercher</button>
    </form>
</header>

<div>
    <?= $restaurants ?>
</div>

<!-- Affichage du panier -->
<div class="panier">
    <h2>Panier</h2>
    <?php if (!empty($_SESSION['panier'])): ?>
        <ul>
            <?php foreach ($_SESSION['panier'] as $id => $plat): ?>
                <li>
                    <?= $plat['nom'] ?> - <?= $plat['prix'] ?> DH (x<?= $plat['quantite'] ?>)
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="POST">
            <button type="submit" name="vider">Vider le panier</button>
        </form>
    <?php else: ?>
        <p>Le panier est vide.</p>
    <?php endif; ?>
</div>

</body>
</html>
