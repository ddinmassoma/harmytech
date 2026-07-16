<?php
if($_SESSION['redirection'] == true){
    header("Location: index.php?page=accueil");
    exit(); // Toujours mettre un exit() après une redirection header
}

if (isset($_POST['connexion'])) {
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $identifiant = $_POST['identifiant'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $sql = "SELECT id, statut, mot_de_passe, prenom, nom FROM administrateur WHERE identifiant = ?";       
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('s', $identifiant);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($mot_de_passe === $user['mot_de_passe']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_statut'] = $user['statut'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $prepared_stmt->close();
            $connection_string->close();
            header("Location: index.php?page=accueil");
            exit();
        } else {
            echo "<p class='alert alert-error'>Identifiant ou mot de passe incorrect.</p>";
        }
        
    } else {
        echo "<p class='alert alert-error'>Identifiant ou mot de passe incorrect.</p>";
    }
    $prepared_stmt->close();
    $connection_string->close();
}
?>

<form action="" method="post" class="profil">
    <label>Identifiant :</label>
    <input type="text" name="identifiant" autocomplete="identifiant" placeholder="Entrez votre identifiant" required>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" autocomplete="mot_de_passe" placeholder="Entrez votre mot de passe" required>

    <button type="submit" name="connexion">Se connecter</button>
    <p>Vous n'avez pas encore de compte : <a href="index.php?page=création_compte">Créer un compte</a> </p>
</form>

<style>
:root {
    --color-primary: #0b132b;    
    --color-accent: #5a46e5;    
    --color-bg-light: #f8fafc;   
    --color-text: #1e293b;      
    --color-border: #cbd5e1;     
}

/* Style général du formulaire */
.profil {
    max-width: 400px;
    margin: 60px auto;        
    padding: 30px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
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