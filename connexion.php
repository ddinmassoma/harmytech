<?php
if($_SESSION['redirection']==true){
    header("Location: index.php?page=accueil");
}

if (isset($_POST['connexion'])) {
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

    //ID de l'administrateur
    $sql_id ="SELECT id FROM administrateur WHERE identifiant = ? AND mot_de_passe = ?";
    $prepared_stmt_id = $connection_string->prepare($sql_id);
    $prepared_stmt_id->bind_param('ss', $identifiant, $mot_de_passe);
    $prepared_stmt_id->execute();
    $id = $prepared_stmt_id;

    if ($result->num_rows === 1) {
        $_SESSION['user_id'] = $id;
        header("Location: index.php?page=accueil");
        exit();
        
    } else {
        echo "<p class='alert alert-error'>Identifiant ou mot de passe incorrect.</p>";
    }
    $prepared_stmt->close();
    $prepared_stmt_id->close();
    $connection_string->close();
}
?>

<form action="" method="post" class="profil">
    <label>Identifiant :</label>
    <input type="text" name="identifiant" autocomplete="username" placeholder="Entrez votre identifiant" required>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" autocomplete="current-password" placeholder="Entrez votre mot de passe" required>

    <button type="submit" name="connexion">Se connecter</button>
</form>

<style>
:root {
    --color-primary: #0b132b;    /* Le bleu nuit de ton en-tête */
    --color-accent: #5a46e5;     /* Le violet vibrant de ta ligne */
    --color-bg-light: #f8fafc;   /* Un fond très légèrement grisé pour les inputs */
    --color-text: #1e293b;       /* Gris sombre pour une lecture confortable */
    --color-border: #cbd5e1;     /* Gris clair pour les bordures */
}

/* Style général du formulaire */
.profil {
    max-width: 400px;
    margin: 60px auto;        
    padding: 30px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); /* Effet d'ombrage léger */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    flex-direction: column;
}

/* Style des labels (Identifiant, Mot de passe) */
.profil label {
    display: block;
    margin-bottom: 8px;
    color: var(--color-primary);
    font-weight: 600;
    font-size: 0.95rem;
}

/* Style des champs de saisie (inputs) */
.profil input[type="text"],
.profil input[type="password"] {
    width: 100%;
    padding: 12px 16px;
    margin-bottom: 24px;      
    border: 1px solid var(--color-border);
    border-radius: 6px;
    background-color: var(--color-bg-light);
    color: var(--color-text);
    font-size: 1rem;
    box-sizing: border-box;       
    transition: all 0.3s ease;  
}

/* Effet visuel quand l'utilisateur clique dans un champ (Focus) */
.profil input:focus {
    outline: none;
    border-color: var(--color-accent); /* Rappel du violet de ta ligne */
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(90, 70, 229, 0.15); /* Léger halo violet */
}

/* Style du bouton "Se connecter" */
.profil button[type="submit"] {
    width: 100%;
    padding: 14px;
    background-color: var(--color-primary); /* Base sur ton bleu nuit */
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
}

/* Effet au survol du bouton */
.profil button[type="submit"]:hover {
    background-color: var(--color-accent); /* Le bouton devient violet au survol ! */
}

/* Effet au clic sur le bouton */
.profil button[type="submit"]:active {
    transform: scale(0.98); /* Léger effet d'enfoncement */
}

/* --- Style des Messages d'Alerte (Succès / Erreur) --- */
.alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 14px;
    font-weight: 500;
    border-left: 5px solid transparent;
}

/* Alerte Erreur */
.alert-error {
    background-color: #fef2f2;
    color: #991b1b;
    border-color: #ef4444;
}
</style>