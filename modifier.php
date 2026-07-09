<h1>Modifier un produit</h1>

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
}
?>

<form action="" method="post">
    <input type="text" name="nom" value="<?php echo isset($row['nom']) ? htmlspecialchars($row['nom']) : ''; ?>" >
    <input type="text" name="marque" value="<?php echo isset($row['marque']) ? htmlspecialchars($row['marque']) : ''; ?>">
    <input type="text" name="couleur" value="<?php echo isset($row['couleur']) ? htmlspecialchars($row['couleur']) : ''; ?>">
    <input type="text" name="memoire" value="<?php echo isset($row['memoire']) ? htmlspecialchars($row['memoire']) : ''; ?>">
    <input type="text" name="model" value="<?php echo isset($row['model']) ? htmlspecialchars($row['model']) : ''; ?>">
    <input type="text" name="reference" value="<?php echo isset($row['reference']) ? htmlspecialchars($row['reference']) : ''; ?>">
    <button type="submit" name="modifier">Modifier</button>
</form>

<?php
if (isset($_POST['modifier'])) {
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $id = $_GET['id'];
    $nom = $_POST['nom'] ?? '';
    $marque = $_POST['marque'] ?? '';
    $couleur = $_POST['couleur'] ?? '';
    $memoire = $_POST['memoire'] ?? '';
    $model = $_POST['model'] ?? '';
    $reference = $_POST['reference'] ?? '';

    $sql = "UPDATE base_de_donn__e___harmytech___feuille_1 SET nom = ?, marque = ?, couleur = ?, memoire = ?, model = ?, reference = ? WHERE id = ?";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('ssssssi', $nom, $marque, $couleur, $memoire, $model, $reference, $id);
    if ($prepared_stmt->execute() === true) {
        echo "Produit modifié avec succès.";
    } else {
        echo "Erreur lors de la modification du produit.";
    }
    $prepared_stmt->close();
    $connection_string->close();
}
?>

<a href="index.php?page=accueil">
    <button>Retour à l'accueil</button>
</a>

