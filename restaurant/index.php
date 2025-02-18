<?php
        $restaurants="";
        require("fech.php");
        $sql = "SELECT * FROM plat";
        $stmt = $pdo->query($sql);
        $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($plats as $plat) {
            $restaurants .= "<img src=\"{$plat['image']}\" alt=\"\"> "; 
            $restaurants .= "<p>" . $plat['nomPlat'] . "</p>";  
            $restaurants .= "<p>" . $plat['categoriePlat'] . "</p>";  
            $restaurants .= "<p>" . $plat['TypeCuisine'] . "</p>";  
            $restaurants .= "<p>" . $plat['prix'] . "</p>";  
        }
?>
<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <h1>Restaurants</h1>
    <div>
        <?= $restaurants ;
        ?>
    </div>
    <img src="" alt="">
</body>
</html>