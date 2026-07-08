<h1>Ajouter un produit</h1>

<form action="" method="post">
    <input type="text" name="nom" placeholder="Nom du produit" required>
    <input type="text" name="marque" placeholder="Marque du produit" required>
    <input type="text" name="couleur" placeholder="Couleur du produit" required>
    <input type="text" name="memoire" placeholder="Mémoire du produit" required>
    <input type="text" name="model" placeholder="Modèle du produit" required>
    <input type="text" name="reference" placeholder="Référence du produit" required>
    <button type="submit" name="ajouter">Ajouter</button>
</form>

<?php
if (isset($_POST['ajouter'])) {
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $nom = $_POST['nom'] ?? '';
    $marque = $_POST['marque'] ?? '';
    $couleur = $_POST['couleur'] ?? '';
    $memoire = $_POST['memoire'] ?? '';
    $model = $_POST['model'] ?? '';
    $reference = $_POST['reference'] ?? '';

    $sql = "INSERT INTO base_de_donn__e___harmytech___feuille_1 (nom, marque, couleur, memoire, model, reference) VALUES (?, ?, ?, ?, ?, ?)";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('ssssss', $nom, $marque, $couleur, $memoire, $model, $reference);
    if ($prepared_stmt->execute() === false) {
        echo "Erreur lors de l'ajout du produit.";
    } else {
        echo "Produit ajouté avec succès.";
    }
    $prepared_stmt->close();
    $connection_string->close();
}