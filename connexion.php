<?php 
require_once 'configuration/database.php';
?>

<form action="" method="post" class="profil">
    <label>Identifiant :</label>
    <input type="text" name="identifiant" autocomplete="username" placeholder="Entrez votre identifiant" required>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" autocomplete="current-password" placeholder="Entrez votre mot de passe" required>

    <button type="submit" name="submit">Se connecter</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $identifiant = $_POST['identifiant'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $sql = "SELECT * 
            FROM administrateur 
            WHERE identifiant = ? AND mot_de_passe = ?"; 
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('ss', $identifiant, $mot_de_passe);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();

    if ($result->num_rows === 1) {
        // Redirection vers la page de profil après la connexion réussie
        header("Location: index.php?page=profil");
        exit();
    } else {
        echo "Identifiant ou mot de passe incorrect.";
    }
}