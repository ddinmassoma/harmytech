<form action="" method="post">
		<input
			type="text"
			placeholder="Entrez le nom du produit"
			name="search"
			required>
		<button type="submit" name="submit">Rechercher</button>
</form>

<?php
    if (isset($_POST['submit'])) {
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $searchString = mysqli_real_escape_string($connection_string, trim(htmlentities($_POST['search'])));
        $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $limit = 5;
        $offset = ($page - 1) * $limit;

        if ($connection_string->connect_error) {
            echo "Failed to connect to Database";
            exit();
        }
        
        $searchString = "%$searchString%";
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE nom LIKE ? LIMIT $limit OFFSET $offset";
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
                echo "<b>Réference constructeur</b>: ". $row['ref constructeur'] . "<br/>";
                echo "<b>ID</b>: ". $row['id'] . "<br/>";
                echo ""."<br/>";
            }
        }

        $totalArticles = $connection_string->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1 WHERE nom LIKE '$searchString'")->fetch_row()[0];
        $totalPages = ceil($totalArticles / $limit);
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<strong>' . $i . '</strong> ';
            } else {
                echo '<a href="index.php?page=accueil&subpage=' . $i . '">' . $i . '</a> ';
            }
        }
    }
?>