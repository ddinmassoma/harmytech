<h1>Supprimer un produit</h1>

<?php
if (isset($_POST['confirmer_supprimer'])) {
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $sql_delete = "DELETE FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
        $prepared_stmt_delete = $connection_string->prepare($sql_delete);
        $prepared_stmt_delete->bind_param('i', $id);
        if ($prepared_stmt_delete->execute() === true) {
            echo "Produit supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du produit.";
        }
        $prepared_stmt_delete->close();
}else if (isset($_POST['annuler_supprimer'])) {
    echo "Suppression annulée.";
    exit();
}
?>

<form action="" method="post">
    <input type="number" name="id" placeholder="ID du produit à supprimer" required>
    <button type="submit" name="supprimer">Sélectionner</button>
</form>

<?php
if (isset($_POST['supprimer'])) {
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $id = $_POST['id'] ?? '';
    echo "Information du produit supprimé :<br/>";
    $sql_info = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
    $prepared_stmt_info = $connection_string->prepare($sql_info);
    $prepared_stmt_info->bind_param('i', $id);
    $prepared_stmt_info->execute();
    $result_info = $prepared_stmt_info->get_result();

    if ($result_info->num_rows === 1) {
        $row = $result_info->fetch_assoc();
        echo "<b>Nom</b>: ". htmlspecialchars($row['nom']) . "<br/>";
        echo "<b>Couleur</b>: ". htmlspecialchars($row['couleur']) . "<br/>";
        echo "<b>Marque</b>: ". htmlspecialchars($row['marque']) . "<br/>";
        echo "<b>Modèle</b>: ". htmlspecialchars($row['model']) . "<br/>";
        echo "<b>Mémoire</b>: ". htmlspecialchars($row['memoire']) . "<br/>";
        echo "<b>Réference constructeur</b>: ". htmlspecialchars($row['reference']) . "<br/>";
        echo "<b>ID</b>: ". htmlspecialchars($row['id']) . "<br/>";

        echo "
        <form action='' method='post'>
            <button type='submit' name='confirmer_supprimer'>Confirmer la suppression</button>
            <button type='submit' name='annuler_supprimer'>Annuler</button>
        </form>
        ";
    } else {
        echo "Aucun produit trouvé avec cet ID.";
    }

    $connection_string->close();
}