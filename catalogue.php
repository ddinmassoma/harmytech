<h1>Sélectionnez une marque :</h1>
<form method="post" action="">
    <select name="marque">
        <option value="">-- Sélectionnez une marque --</option>
        <option value="apple">Apple</option>
        <option value="Samsung">Samsung</option>
        <option value="xiaomi">Xiaomi</option>
        <option value="Google">Google</option>
        <option value="motorola">Motorola</option>
    </select>
    <button type="submit" name="submit">Voir les produits</button>
</form>

<?php
if (isset($_POST['submit'])){
    $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $marque = $_POST['marque'];
    $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE marque LIKE '%$marque%'";
    $result = $connection->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<b>Nom</b>: ". $row['nom'] . "<br/>";
            echo "<b>Couleur</b>: ". $row['couleur'] . "<br/>";
            echo "<b>Marque</b>: ". $row['marque'] . "<br/>";
            echo "<b>Modèle</b>: ". $row['model'] . "<br/>";
            echo "<b>Réference constructeur</b>: ". $row['ref constructeur'] . "<br/>";
            echo "<b>ID</b>: ". $row['id'] . "<br/>";
            echo ""."<br/>";
        }
    } else {
        echo "Aucun produit trouvé pour cette marque.";
    }
}

