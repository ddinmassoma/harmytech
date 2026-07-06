<form action="" method="post">
		<input
			type="text"
			placeholder="Entrez le nom du produit"
			name="search"
			required>
		<button type="submit" name="submit">Rechercher</button>
</form>

<?php
    header('Content-Type: text/html; charset=utf-8');
    if (isset($_POST['submit'])) {
        // Connect to the database
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        
        // Escape the search string and trim
        // all whitespace
        $searchString = mysqli_real_escape_string($connection_string, trim(htmlentities($_POST['search'])));

        // If there is a connection error, notify
        // the user, and Kill the script.
        if ($connection_string->connect_error) {
            echo "Failed to connect to Database";
            exit();
        }

        

        // We are using a prepared statement with the
        // search functionality to prevent SQL injection.
        // So, we need to prepend and append the search
        // string with percent signs
        $searchString = "%$searchString%";

        // The prepared statement
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE nom LIKE ?";

        // Prepare, bind, and execute the query
        $prepared_stmt = $connection_string->prepare($sql);
        $prepared_stmt->bind_param('s', $searchString);
        $prepared_stmt->execute();

        // Fetch the result
        $result = $prepared_stmt->get_result();

        if ($result->num_rows === 0) {
            // No match found
            echo "No match found";
            // Kill the script
            exit();

        } else {
            // Process the result(s)
            while ($row = $result->fetch_assoc()) {
                echo "<b>Nom</b>: ". $row['nom'] . "<br/>";
                echo "<b>Couleur</b>: ". $row['couleur'] . "<br/>";
                echo "<b>Marque</b>: ". $row['marque'] . "<br/>";
                echo "<b>Modèle</b>: ". $row['model'] . "<br/>";
                echo "<b>Réference constructeur</b>: ". $row['ref constructeur'] . "<br/>";
                echo "<b>ID</b>: ". $row['id'] . "<br/>";
                echo ""."<br/>";

            } // end of while loop
        } // end of if($result->num_rows)

    }
?>