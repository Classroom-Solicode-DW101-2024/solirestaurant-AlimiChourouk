<?php
session_start();

if (isset($_POST['ajouter'])) {
    $idPlat = $_POST['ajouter'];

    require("fech.php");
    $stmt = $pdo->prepare("SELECT * FROM plat WHERE idPlat = :idPlat");
    $stmt->bindParam(':idPlat', $idPlat);
    $stmt->execute();
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plat) {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        $found = false;
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['idPlat'] == $plat['idPlat']) {
                $item['quantite']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['panier'][] = [
                'idPlat' => $plat['idPlat'],
                'nomPlat' => $plat['nomPlat'],
                'prix' => $plat['prix'],
                'quantite' => 1,
                'image' => $plat['image']
            ];
        }
    }
}

header("Location: index.php#{$idPlat}");
exit();
?>
