<h1>Profil administrateur</h1>
<p>Bienvenue dans votre espace administrateur !</p>

<p>Sélectionnez une option dans le menu pour continuer.</p>

<form action="" method="post">
    <button type="submit" name="ajouter">Ajouter un produit</button>
    <button type="submit" name="modifier">Modifier un produit</button>
    <button type="submit" name="supprimer">Supprimer un produit</button>
</form>

<?php
if (isset($_POST['ajouter'])) {
    header("Location: index.php?page=ajouter");
    exit();
} elseif (isset($_POST['modifier'])) {
    header("Location: index.php?page=modifier");
    exit();
} elseif (isset($_POST['supprimer'])) {
    header("Location: index.php?page=supprimer");
    exit();
}