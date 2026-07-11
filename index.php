<?php
require_once 'configuration/database.php';
$page = $_GET['page'] ?? 'accueil'; 
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
                <a href="index.php?page=connexion">Connexion</a>
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
        <footer>
            <p>Harmytech</p>
            <p>contact.fr@harmytech.com</p>
            <p>01 84 80 41 50</p>
        </footer>
    </body>
</html>