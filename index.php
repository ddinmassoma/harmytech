<?php
session_start();
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
                        if($_SESSION['user_statut']=='administrateur'){
                            echo "<a href='index.php?page=profil_administrateur'>Gestion des profils</a>";
                            echo "<a href='index.php?page=historique'>Historique des connections</a>";
                        }
                        echo "<a href='index.php?page=profil'>Profil</a>";
                        echo "<a href='index.php?page=accueil'>Accueil</a>";
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
                    case 'création_compte':
                        include 'création_compte.php';
                        break;
                    case 'profil_administrateur':
                        include 'profil_administrateur.php';
                        break;
                    case 'ajout_utilisateur':
                        include 'ajout_utilisateur.php';
                        break;
                    case 'modification_utilisateur' :
                        include 'modification_utilisateur.php';
                        break;
                    case 'supression_utilisateur':
                        include 'supression_utilisateur.php';
                        break;
                    case 'profil':
                        include 'profil.php';
                        break;
                    case 'historique':
                        include 'historique.php';
                        break;
                    default:
                        include '404.php';
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