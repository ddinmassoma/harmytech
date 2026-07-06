<?php 
require_once 'configuration/database.php';
?>

<form action="" method="post" class="profil">
    <label>Email :</label>
    <input type="email" name="email" autocomplete="email" required>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" autocomplete="current-password" required>

    <button type="submit">Se connecter</button>
</form>