<?php
if($_SESSION['redirection']==true){
    header("Location: index.php?page=accueil");
}

require_once 'fonction.php';


if (isset($_POST['création'])){
    $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $identifiant = $_POST['identifiant'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $mail = $_POST['e-mail'] ?? '';

    $sql = "INSERT INTO administrateur(identifiant, mot_de_passe, nom, mail) VALUES ( ?, ?, ?, ?)";
    if (verification_creation_compte('mail',$connection,$mail) || verification_creation_compte('identifiant',$connection,$identifiant)=='erreur'){
        echo "<p class='alert alert-error'>Erreur lors de l'ajout du nouvelle utilisateur : vous avez entrer un identifiant ou e-mail déjà existant.</p>";
    }else{
        $prepared_stmt = $connection->prepare($sql);
        $prepared_stmt->bind_param('ssss', $identifiant, $mot_de_passe, $nom, $mail);
        if($prepared_stmt->execute() === true){
            $_SESSION['message']="Utilisateur ajouté avec succès";
            header("Location: index.php?page=connexion");
            exit();
        }else{
            echo "<p class='alert alert-error'>Erreur lors de l'ajout du nouvelle utilisateur.</p>";
        }
        $prepared_stmt->close();
    }
    $connection->close();
}

?>


<form action="" method="post" class="profil">
    <input type="hidden" name="page" value="création_compte">

    <label>Nom :</label>
    <input type="text" name="nom" autocomplete="nom" placeholder="Entrez votre nom" required>

    <label>E-mail :</label>
    <input type="text" name="e-mail" autocomplete="e-mail" placeholder="Entrez votre email" required>

    <label>Identifiant :</label>
    <input type="text" name="identifiant" autocomplete="identifiant" placeholder="Entrez votre identifiant" required>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" autocomplete="mot_de_passe" placeholder="Entrez votre mot de passe" required>

    <button type="submit" name="création">Créer un compte</button>
    <p>Vous avez déjà un compte : <a href="index.php?page=connexion">Connexion</a> </p>
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

/* Alerte Réussite */
.alert-success {
    background-color: #ecfdf5;
    color: #065f46;
    border-color: #10b981;
}
</style>