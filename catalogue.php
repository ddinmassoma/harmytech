<form method="get" action="">
    <input type="hidden" name="page" value="catalogue">
    
    <select name="marque">
        <option value="">-- Sélectionnez une marque --</option>
        <option value="apple">Apple</option>
        <option value="Samsung">Samsung</option>
        <option value="xiaomi">Xiaomi</option>
        <option value="Google">Google</option>
        <option value="motorola">Motorola</option>
    </select>

    <select name="couleur">
        <option value="1">Noir</option>
        <option value="2">Blanc</option>
        <option value="3">Gris</option>
        <option value="4">Rouge</option>
        <option value="5">Bleu</option>
        <option value="6">Vert</option>
        <option value="7">Jaune</option>
        <option value="8">Violet</option>
        <option value="9">Rose</option>
        <option value="10">Orange</option>
        <option value="autre">Autre</option>
    </select>

    <button type="submit">Voir les produits</button>
</form>

<?php
if (isset($_GET['marque']) && !empty($_GET['marque'])) {
    
    $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    
    $marque = $_GET['marque'];
    
    $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    
    $limit = 4;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%'
            ORDER BY id
            LIMIT $limit OFFSET $offset";
            
    $result = $connection->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<b>Nom</b>: ". htmlspecialchars($row['nom']) . "<br/>";
            echo "<b>Couleur</b>: ". htmlspecialchars($row['couleur']) . "<br/>";
            echo "<b>Marque</b>: ". htmlspecialchars($row['marque']) . "<br/>";
            echo "<b>Modèle</b>: ". htmlspecialchars($row['model']) . "<br/>";
            echo "<b>Réference constructeur</b>: ". htmlspecialchars($row['ref constructeur']) . "<br/>";
            echo "<b>ID</b>: ". $row['id'] . "<br/>";
            echo "<br/>";
        }
    } else {
        echo "Aucun produit trouvé pour cette marque.<br/>";
    }

    $totalArticlesQuery = $connection->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1 WHERE marque LIKE '%$marque%'");
    $totalArticles = $totalArticlesQuery->fetch_row()[0];
    $totalPages = ceil($totalArticles / $limit);
    
    if ($totalPages > 1) {
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo ' <strong>' . $i . '</strong> ';
            } else {
                echo ' <a href="index.php?page=catalogue&marque=' . urlencode($marque) . '&subpage=' . $i . '">'  . $i . '</a> ';
            }
        }
    }
}
?>