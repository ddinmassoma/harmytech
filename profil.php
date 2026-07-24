<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}else{
    $id = $_SESSION['user_id'];
}

$connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
if ($connection_string->connect_error) {
    echo "<div class='alert alert-error'>Erreur : impossible de se connecter à la base de donnée</div>";
    exit();
}


$sql = "SELECT * FROM administrateur WHERE id = ?";
$prepared_stmt = $connection_string->prepare($sql);
$prepared_stmt->bind_param('i', $id);
$prepared_stmt->execute();
$result = $prepared_stmt->get_result();
$row = $result->fetch_assoc();
$nom = $_POST['nom']??'';
$identifiant = $_POST['identifiant']??'';
$mail = $_POST['mail']??'';
$mot_de_passe = $_POST['mot_de_passe']??'';

if(isset($_POST['modifier'])){
    $sql = "UPDATE administrateur SET nom = ?, identifiant = ?, mail = ?, mot_de_passe = ?  WHERE id = ?";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('ssssi', $nom, $identifiant, $mail, $mot_de_passe, $id);
    if ($prepared_stmt->execute() === true) {
        echo "<p class='alert alert-success'>Modification effectué avec succée.</p>";
    } else {
        echo "<p class='alert alert-error'>Erreur lors de la modification.</p>";
    }
}

if (isset($_POST['supprimer'])){
    $sql ="DELETE FROM administrateur WHERE id = ?";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('i', $id);
    if ($prepared_stmt->execute() === true) {
        $_SESSION['message']="Profil supprimé avec succée";
        unset($_SESSION['user_id']);
        header("Location: index.php?page=connexion");
        exit;
    } else {
        echo "<p class='alert alert-error'>Erreur lors de la supression.</p>";
    }
}
?>

<div class="form-container">
    <h1 class="form-title">Profil</h1>
    <form action="" method="post" class="product-form">
        <div class="form-grid">
            <div class="input-group">
                <input type="text" name="nom" value="<?php echo isset($row['nom']) ? htmlspecialchars($row['nom']) : '';?>">
            </div>
            
            <div class="input-group">
                <input type="text" name="identifiant" class="input-group" value="<?php echo isset($row['identifiant']) ? htmlspecialchars($row['identifiant']) : '';?>">
            </div>

            <div class="input-group">
                <input type="text" name="mail" class="input-group" value="<?php echo isset($row['mail']) ? htmlspecialchars($row['mail']) : ''; ?>">
            </div>

            <div class="input-group">
                <input type="text" name="mot_de_passe" class="input-group" value="<?php echo isset($row['mot_de_passe']) ? htmlspecialchars($row['mot_de_passe']) : ''; ?>">
            </div>

            <div class="input-group">
                <input type="text" name="statut" class="input-group" value="<?php echo isset($row['statut']) ? htmlspecialchars($row['statut']) : ''; ?>" disabled>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="modifier" class="btn-form btn-form-submit">Modifier</button>
            <button type="submit" name="supprimer" class="btn-form btn-form-submit">Supprimer</button>
        </div>
        
    </form>
</div>

<style>
:root {
    --color-dark-bg: #0b132b;   
    --color-accent: #5e5ce6;    
    --color-accent-hover: #4a48c6;
    --color-text-main: #1f2937;
    --color-border: #d1d5db;
    --color-light-bg: #f8fafc;
}

/* --- Conteneur du Formulaire --- */
.form-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 700px;
    margin: 40px auto;
    padding: 35px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    border: 1px solid #edf2f7;
}

/* --- Titre du formulaire --- */
.form-title {
    margin-top: 0;
    margin-bottom: 25px;
    font-size: 24px;
    color: var(--color-dark-bg);
    border-bottom: 3px solid var(--color-accent); /* Liseré violet sous le titre */
    padding-bottom: 10px;
    font-weight: 700;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

@media (min-width: 600px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr); /* Aligne les champs 2 par 2 */
    }
}

/* --- Style des Inputs --- */
.input-group input[type="text"] {
    width: 100%;
    padding: 12px 16px;
    font-size: 14px;
    border: 1px solid var(--color-border);
    border-radius: 8px;
    background-color: var(--color-light-bg);
    color: var(--color-text-main);
    outline: none;
    box-sizing: border-box;
    transition: all 0.2s ease-in-out;
}

/* Effet au clic sur un champ */
.input-group input[type="text"]:focus {
    border-color: var(--color-accent);
    box-shadow: 0 0 0 4px rgba(94, 92, 230, 0.15);
    background-color: #ffffff;
}

/* --- Boutons de gestion --- */
.form-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    border-top: 1px solid #edf2f7;
    padding-top: 20px;
}

@media (min-width: 480px) {
    .form-actions {
        flex-direction: row-reverse; /* Met le bouton principal à droite */
        justify-content: flex-start;
    }
}

.btn-form {
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
}


.btn-form-submit {
    background-color: var(--color-accent);
    color: #ffffff;
}

.btn-form-submit:hover {
    background-color: var(--color-accent-hover);
    transform: translateY(-1px);
}

/* Bouton Annuler/Retour */
.btn-form-back {
    background-color: transparent;
    color: var(--color-dark-bg);
    border: 1px solid var(--color-border);
}

.btn-form-back:hover {
    background-color: #f1f5f9;
    border-color: var(--color-dark-bg);
}

.btn-form:active {
    transform: translateY(1px);
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

/* Alerte Réussite */
.alert-success {
    background-color: #ecfdf5;
    color: #065f46;
    border-color: #10b981;
}

/* Alerte Erreur */
.alert-error {
    background-color: #fef2f2;
    color: #991b1b;
    border-color: #ef4444;
}

.deconnexion{
    color : red;
    text-decoration-style: solid;
}
</style>