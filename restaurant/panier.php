<?php
session_start();
 
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit;
}

$panier = $_SESSION['panier'];
$total = 0;

if (isset($_POST['modifier'])) {
    $idPlat = $_POST['id_plat'];
    $quantite = (int)$_POST['quantite']; // Conversion en entier pour plus de sécurité
    
    foreach ($panier as $key => $item) {
        if ($item['idPlat'] == $idPlat) {
            $panier[$key]['quantite'] = max(1, $quantite); // Assure que la quantité est au moins 1
            break; // Sort de la boucle une fois l'élément trouvé
        }
    }
    $_SESSION['panier'] = $panier; // Met à jour la session
}

if (isset($_POST['supprimer'])) {
    foreach ($panier as $key => $item) {
        if ($item['idPlat'] == $_POST['id_plat']) {
            unset($panier[$key]);
            break;
        }
    }
    $_SESSION['panier'] = array_values($panier); 
} 

foreach ($panier as $item) {
    $total += $item['prix'] * $item['quantite'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=9">
    <title>Panier</title>
</head>
<body>
<Header>
       <div id="imgTitre">
        <img id="img" src="img/logo.png" alt="logo de l'application">
        <link rel="stylesheet" href="style.css">
          <h1>Restaurants</h1>
          
        </div>
        <h3>Votre Panier</h3>
        <nav>
            <ul> <a href="index.php">Accueil</a></ul>
        </nav>
</Header>
<section>
    
    <div class="panier">
        <?php foreach ($panier as $item): ?>
            <div class="plat-panier">
                <img src="<?= $item['image'] ?>" alt="<?= $item['nomPlat'] ?>">
                <p><strong>Nom :</strong> <?= $item['nomPlat'] ?></p>
                <p><strong>Prix :</strong> <?= $item['prix'] ?> $</p>
                <form method="POST">
                    <input type="hidden" name="id_plat" value="<?= $item['idPlat'] ?>">
                    <input type="number" name="quantite" value="<?= $item['quantite'] ?>" min="1">
                    <button type="submit" name="modifier">Modifier</button>
                </form>
                <form method="POST">
                    <input type="hidden" name="id_plat" value="<?= $item['idPlat'] ?>">
                    <button type="submit" name="supprimer">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <section>
   <section id="total">
    <h2>Total : <?= $total ?> $</h2>
    <form method="POST" action="confirmer_commande.php">
        <button type="submit">Confirmer la commande</button>
    </form>
   </section>
</body>
</html>