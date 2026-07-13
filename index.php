<?php
session_start();
require_once 'configuration/database.php';
$_SESSION['redirection'] = false;
if (isset($_SESSION['user_id'])) {
    $page = $_GET['page'] ?? 'accueil'; 
    $_SESSION['redirection'] = true;
}else {
    $page = $_GET['page'] ?? 'connexion'; 
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catalogue Harmytech</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Catalogue Harmytech</h1>
            <nav>
                <?php
                    if (isset($_SESSION['user_id'])) {
                        echo "<a href='deconnexion.php' class='deconnexion'>Se déconnecter</a>";
                    }
                ?>
            </nav>
        </header>
        <main>
            <?php
                switch ($page) {
                    case 'accueil':
                        include 'accueil.php';
                        break;
                    case 'connexion':
                        include 'connexion.php';
                        break;
                    case 'ajouter':
                        include 'ajouter.php';
                        break;
                    case 'modifier':
                        include 'modifier.php';
                        break;
                    case 'supprimer':
                        include 'supprimer.php';
                        break;
                    default:
                        include 'configuration/404.php';
                        break;
                }
            ?>
        </main>
        <style>
            .deconnexion{
                color : red;
                text-decoration-style: solid;
            }
        </style>
        <footer>
            <p>Harmytech</p>
            <p>contact.fr@harmytech.com</p>
            <p>01 84 80 41 50</p>
        </footer>
    </body>
</html>