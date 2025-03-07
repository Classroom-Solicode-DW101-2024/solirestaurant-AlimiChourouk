<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "SoliRestaurant";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

if(isset($_POST['update_status'])) {
    $idCmd = $_POST['idCmd'];
    $newStatus = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE commande SET Statut = ? WHERE idCmd = ?");
    $stmt->execute([$newStatus, $idCmd]);
}

$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT c.*, cl.nomCl, cl.prenomCl 
    FROM commande c 
    JOIN client cl ON c.idCl = cl.idClient 
    WHERE DATE(dateCmd) = ?");
$stmt->execute([$today]);
$orders = $stmt->fetchAll();


$totalOrders = $pdo->query("SELECT COUNT(*) FROM commande WHERE DATE(dateCmd) = '$today'")->fetchColumn();
$cancelledOrders = $pdo->query("SELECT COUNT(*) FROM commande WHERE Statut = 'annulée' AND DATE(dateCmd) = '$today'")->fetchColumn();
$totalClients = $pdo->query("SELECT COUNT(DISTINCT idCl) FROM commande WHERE DATE(dateCmd) = '$today'")->fetchColumn();

$dishes = $pdo->query("SELECT p.nomPlat, SUM(cp.qte) as total_qty 
    FROM commande_plat cp 
    JOIN plat p ON cp.idPlat = p.idPlat 
    JOIN commande c ON cp.idCmd = c.idCmd 
    WHERE DATE(c.dateCmd) = '$today' 
    GROUP BY p.idPlat, p.nomPlat")->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord du Restaurant</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .stats { margin: 20px 0; }
        .table-container { margin-top: 30px; }
        .statistiques p { font-size: 16px; }
        button { background-color: #23d957; color: white; padding: 8px 12px; border: none; cursor: pointer; }
        button:hover { background-color: #c54e29; }
    </style>
</head>
<body>
    <h1 id="titre-dashboard">Tableau de bord du Restaurant - <?php echo $today; ?></h1>

    <div id="statistiques" class="stats">
        <h2>Statistiques</h2>
        <p id="total-commandes">Total des commandes : <?php echo $totalOrders; ?></p>
        <p id="commandes-annulees">Commandes annulées : <?php echo $cancelledOrders; ?></p>
        <p id="total-clients">Total des clients : <?php echo $totalClients; ?></p>
        
        <h3>Plats commandés aujourd'hui</h3>
        <table id="plats-commandes">
            <tr><th>Nom du plat</th><th>Quantité</th></tr>
            <?php foreach($dishes as $dish): ?>
                <tr>
                    <td><?php echo $dish['nomPlat']; ?></td>
                    <td><?php echo $dish['total_qty']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="table-container">
        <h2>Commandes du jour</h2>
        <table id="commandes">
            <tr>
                <th>Numéro de commande</th>
                <th>Date</th>
                <th>Client</th>
                <th>Statut</th>
                <th>Mettre à jour le statut</th>
            </tr>
            <?php foreach($orders as $order): ?>
                <tr>
                    <td><?php echo $order['idCmd']; ?></td>
                    <td><?php echo $order['dateCmd']; ?></td>
                    <td><?php echo $order['nomCl'] . ' ' . $order['prenomCl']; ?></td>
                    <td><?php echo $order['Statut']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="idCmd" value="<?php echo $order['idCmd']; ?>">
                            <select name="status" id="status-<?php echo $order['idCmd']; ?>">
                                <option value="en attente" <?php echo ($order['Statut'] == 'en attente') ? 'selected' : ''; ?>>En attente</option>
                                <option value="en cours" <?php echo ($order['Statut'] == 'en cours') ? 'selected' : ''; ?>>En cours</option>
                                <option value="expédiée" <?php echo ($order['Statut'] == 'expédiée') ? 'selected' : ''; ?>>Expédiée</option>
                                <option value="livrée" <?php echo ($order['Statut'] == 'livrée') ? 'selected' : ''; ?>>Livrée</option>
                                <option value="annulée" <?php echo ($order['Statut'] == 'annulée') ? 'selected' : ''; ?>>Annulée</option>
                            </select>
                            <button type="submit" name="update_status">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
