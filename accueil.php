<form action="" method="get">
		<input
			type="text"
			placeholder="Entrez le nom du produit"
			name="search"
			required>
		<button type="submit" name="submit">Rechercher</button>
</form>

<form method="get" action="">
    <input type="hidden" name="page" value="accueil">
    
    <select name="marque">
        <option value="">-- Sélectionnez une marque --</option>
        <option value="apple">Apple</option>
        <option value="Samsung">Samsung</option>
        <option value="xiaomi">Xiaomi</option>
        <option value="Google">Google</option>
        <option value="motorola">Motorola</option>
    </select>

    <select name="couleur">
        <option value="">-- Sélectionnez une couleur --</option>
        <option value="noir">Noir</option>
        <option value="blanc">Blanc</option>
        <option value="gris">Gris</option>
        <option value="rouge">Rouge</option>
        <option value="bleu">Bleu</option>
        <option value="vert">Vert</option>
        <option value="jaune">Jaune</option>
        <option value="violet">Violet</option>
        <option value="rose">Rose</option>
        <option value="orange">Orange</option>
        <option value="autre">Autre</option>
    </select>

    <select name="memoire">
        <option value="">-- Sélectionnez une mémoire --</option>
        <option value="32Go">32 Go</option>
        <option value="64Go">64 Go</option>
        <option value="128Go">128 Go</option>
        <option value="256Go">256 Go</option>
        <option value="512Go">512 Go</option>
        <option value="1To">1 To</option>
        <option value="autre">Autre</option>
    </select>

    <button type="submit">Voir les produits</button>
</form>

<?php
    if (isset($_GET['submit'])) {
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $searchString = mysqli_real_escape_string($connection_string, trim(htmlentities($_GET['search'] ?? '')));
        $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $limit = 4;
        $offset = ($page - 1) * $limit;

        if ($connection_string->connect_error) {
            echo "Failed to connect to Database";
            exit();
        }
        
        $searchString = "%$searchString%";
        $sql = "SELECT * 
                FROM base_de_donn__e___harmytech___feuille_1 
                WHERE nom LIKE ? 
                ORDER BY id
                LIMIT $limit OFFSET $offset";
        $prepared_stmt = $connection_string->prepare($sql);
        $prepared_stmt->bind_param('s', $searchString);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();

        if ($result->num_rows === 0) {
            echo "No match found";
            exit();
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<b>Nom</b>: ". $row['nom'] . "<br/>";
                echo "<b>Couleur</b>: ". $row['couleur'] . "<br/>";
                echo "<b>Marque</b>: ". $row['marque'] . "<br/>";
                echo "<b>Modèle</b>: ". $row['model'] . "<br/>";
                echo "<b>Réference constructeur</b>: ". $row['reference'] . "<br/>";
                echo "<b>ID</b>: ". $row['id'] . "<br/>";
                echo ""."<br/>";
            }
        }

        $totalArticles = $connection_string->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1 WHERE nom LIKE '$searchString'")->fetch_row()[0];
        $totalPages = ceil($totalArticles / $limit);
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo ' ';
                echo '<strong>' . $i . '</strong> ';
            } else {
                echo ' ';
                echo '<a href="index.php?page=accueil&search=' .urlencode($_GET['search']) .'&submit=Rechercher&subpage=' . $i .'">' . $i .'</a>';
            }
        }
    }
?>

<?php
if (isset($_GET['submit'])) {
    exit();
}else{
    //Valeurs des filtres
    $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $marque = $_GET['marque']??'';
    $couleur =$_GET['couleur']??'';
    $memoire = $_GET['memoire']??'';
    $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $limit = 4;
    $offset = ($page - 1) * $limit;

    //Valeur de la requête SQL en fonction des filtres sélectionnés
    if($couleur =="autre"){
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur NOT IN ('noir', 'blanc', 'gris', 'rouge', 'bleu', 'vert', 'jaune', 'violet', 'rose', 'orange') 
            AND memoire LIKE '%$memoire%'
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }elseif($memoire =="autre"){
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur LIKE '%$couleur%' 
            AND memoire NOT IN ('32Go', '64Go', '128Go', '256Go', '512Go', '1To') 
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }else{
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur LIKE '%$couleur%' 
            AND memoire LIKE '%$memoire%'
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }
    
            
    $result = $connection->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<b>Nom</b>: ". htmlspecialchars($row['nom']) . "<br/>";
            echo "<b>Couleur</b>: ". htmlspecialchars($row['couleur']) . "<br/>";
            echo "<b>Marque</b>: ". htmlspecialchars($row['marque']) . "<br/>";
            echo "<b>Modèle</b>: ". htmlspecialchars($row['model']) . "<br/>";
            echo "<b>Mémoire</b>: ". htmlspecialchars($row['memoire']) . "<br/>";
            echo "<b>Réference constructeur</b>: ". htmlspecialchars($row['reference']) . "<br/>";
            echo "<b>ID</b>: ". $row['id'] . "<br/>";
            echo "<br/>";
        }
    } else {
        echo "Aucun produit trouvé <br/>";
    }

    $totalArticlesQuery = $connection->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1 WHERE marque LIKE '%$marque%' AND couleur LIKE '%$couleur%' AND memoire LIKE '%$memoire%'");
    $totalArticles = $totalArticlesQuery->fetch_row()[0];
    $totalPages = ceil($totalArticles / $limit);
    
    if ($totalPages > 1) {
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo ' <strong>' . $i . '</strong> ';
            } else {
                echo ' <a href="index.php?page=accueil&marque=' . urlencode($marque) . '&couleur=' . urlencode($couleur) . '&memoire=' . urlencode($memoire) . '&subpage=' . $i . '">'  . $i . '</a> ';
            }
        }
    }
}