<?php
    session_start();

    // $voyage_id = $_GET['voyage_id'] ?? '';
    $voyage_id = 1;
    $options = $_GET['options'] ?? '';
    $nb_personnes = $_GET['nb_personnes'] ?? '';

    $content = file_get_contents("data/voyages.json");
    $voyages = json_decode($content, true);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitualif</title>
    <link rel="stylesheet" href="style/recapitulatif.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <a href="index.php">
            <img src="assets/logo2.png" class="logo" alt="logo">
        </a>
        <div class="nav-buttons">
            <?php
                if (isset($_SESSION["user"])) {
                    $username = htmlspecialchars($_SESSION["user"]["username"]);
                    echo '<span class="welcome">Bienvenue, ' . $username . '</span>';
                    echo '<a href="profile2.php" id="nav-button">Mon profil</a>';
                    echo '<a href="logout.php" id="nav-button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
                } else {
                    echo '<a href="register.php" id="nav-button">S\'inscrire</a>';
                    echo '<a href="login.php" id="nav-button">Se connecter</a>';
                }
            ?>
        </div>
    </nav>
    <div id="main-card">
        <h1>Récapitualif de votre voyage :</h1>

        <div class="inside-container">
            <?php
                foreach($voyages as $voyage){
                    if($voyage["id"] == $voyage_id){
                        $titre = $voyage["titre"];
                        $pays = $voyage["pays"];
                        $transport = $voyage["transport"];
                        $duree = $voyage["duree"];
                        $imageUrl = "assets/voyages/". $voyage["id"] . ".jpg";
                        $prix = $voyage["prix"];
                        echo <<<HTML
                            <div class="card">
                                <a href="voyage.php?id={$voyage['id']}">
                                <img src="$imageUrl" alt="$titre">
                                <div class="text">
                                    <h1 id="titre">$titre</h1>
                                    <h2>$pays</h2>
                                    <h3>{$nb_personnes} personne(s)</h3>
                                    <p>Transport : {$transport}</p>
                                    <p>Durée : {$duree}</p>
                                    <h3>Prix voyage : {$prix}€</h3>
                                    <p>Options : {$duree}</p>
                                    <h3>Prix total : {$prix}€</h3>
                                </div>
                                </a>
                        
                            </div>
                        HTML;
                    }
                }
            ?>
            <form action='payment.php' method='POST'>
                <!--
                <input type='hidden' name='transaction' value='<?= $transaction ?>'>
                <input type='hidden' name='montant' value='<?= $montant ?>'>
                <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                <input type='hidden' name='retour' value='<?= $retour ?>'>
                <input type='hidden' name='control' value='<?= $control ?>'>
                -->
                <input type='submit' value="Passer au paiement">
            </form>
        </div>

        
    </div>

    <footer>
        <p>&copy; Fishing Travel</p>
        <ul>
            <li>Derrien Enzo</li>  
            <li>Drecourt Raphaël</li>
            <li>Nisar Sofiane</li>
        </ul>
        <a href="https://github.com/raphael950/AgenceVoyage.git">Lien du github</a>
    </footer>
</body>
</html>