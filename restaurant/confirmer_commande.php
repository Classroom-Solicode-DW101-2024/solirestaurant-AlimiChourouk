<?php
session_start();

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Erreur : Votre panier est vide.";
    exit;
}

require("fech.php"); 

$idCmd = substr(uniqid(), -4); 

$idClient = isset($_SESSION['idClient']) ? $_SESSION['idClient'] : 1; 

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO commande (idCmd, idCl, dateCmd, Statut) VALUES (:idCmd, :idCl, NOW(), 'en attente')");
    $stmt->execute([
        ':idCmd' => $idCmd,
        ':idCl' => $idClient
    ]);

    $stmtPlat = $pdo->prepare("INSERT INTO commande_plat (idPlat, idCmd, qte) VALUES (:idPlat, :idCmd, :qte)");
    foreach ($_SESSION['panier'] as $item) {
        $stmtPlat->execute([
            ':idPlat' => $item['idPlat'],
            ':idCmd' => $idCmd,
            ':qte' => $item['quantite']
        ]);
    }

    $pdo->commit();


    unset($_SESSION['panier']);

    header("Location: confirmation.php?idCmd=$idCmd");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur lors de la confirmation de la commande : " . $e->getMessage();
    exit;
}
?>