<h1>Supprimer un produit</h1>

<?php
if (isset($_GET['id'])) {
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $id = $_GET['id'];
    $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('i', $id);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();
    $row = $result->fetch_assoc();

    if (isset($_POST['supprimer'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('i', $id);
    if ($prepared_stmt->execute() === true) {
        echo "Produit supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du produit.";
    }
    $prepared_stmt->close();
    $connection_string->close();
    } else{
        echo "<b>Nom</b>: ". $row['nom'] . "<br/>";
        echo "<b>Couleur</b>: ". $row['couleur'] . "<br/>";
        echo "<b>Marque</b>: ". $row['marque'] . "<br/>";
        echo "<b>Modèle</b>: ". $row['model'] . "<br/>";
        echo "<b>Mémoire</b>: ". $row['memoire'] . "<br/>";
        echo "<b>Réference constructeur</b>: ". $row['reference'] . "<br/>";
        echo "<b>ID</b>: ". $row['id'] . "<br/>";
        echo "<p>Êtes-vous sûr de vouloir supprimer ce produit ?</p>"."<br/>";
    }
}else {
    echo "Aucun produit trouvé.";
}
?>

<form action="" method="post">
    <button type="submit" name="supprimer">Supprimer le produit</button>
</form>

<a href="index.php?page=accueil">
    <button>Retour à l'accueil</button>
</a>