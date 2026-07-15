<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}elseif ($_SESSION['user_statut'] == 'utilisateur') {
    $_SESSION['message_erreur'] = "Le statut d'administrateur est requis pour effectuer cette action.";
    header("Location: index.php?page=accueil"); 
    exit;
}
?>

<div class="form-container">
    <h1 class="form-title">Modifier un produit</h1>

    <?php
    if (isset($_GET['id'])) {
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $id = $_GET['id'];
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
        $prepared_stmt = $connection_string->prepare($sql);
        $prepared_stmt->bind_param('i', $id);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();
        $row = $result->fetch_assoc();
        if ($result->num_rows != 1) {
            header("Location: index.php?page=accueil");
            exit();   
        }
    }
    ?>
    
    <form action="" method="post" class="product-form">
        <div class="form-grid">
            <div class="input-group">
                <input type="text" name="nom" value="<?php echo isset($row['nom']) ? htmlspecialchars($row['nom']) : ''; ?>" >
            </div>
            
            <div class="input-group">
                <input type="text" name="marque" class="input-group" value="<?php echo isset($row['marque']) ? htmlspecialchars($row['marque']) : ''; ?>">
            </div>

            <div class="input-group">
                <input type="text" name="couleur" class="input-group" value="<?php echo isset($row['couleur']) ? htmlspecialchars($row['couleur']) : ''; ?>">
            </div>
            
            <div class="input-group">
                <input type="text" name="memoire" class="input-group" value="<?php echo isset($row['memoire']) ? htmlspecialchars($row['memoire']) : ''; ?>">
            </div>

            <div class="input-group">
                <input type="text" name="model" class="input-group" value="<?php echo isset($row['model']) ? htmlspecialchars($row['model']) : ''; ?>">
            </div>

            <div class="input-group">
                <input type="text" name="reference" class="input-group" value="<?php echo isset($row['reference']) ? htmlspecialchars($row['reference']) : ''; ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="modifier" class="btn-form btn-form-submit">Modifier</button>
            <a href="index.php?page=accueil" class="btn-form btn-form-back">Retour à l'accueil</a>
        </div>
        
    </form>

    <?php
    if (isset($_POST['modifier'])) {
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $id = $_GET['id'];
        $nom = $_POST['nom'] ?? '';
        $marque = $_POST['marque'] ?? '';
        $couleur = $_POST['couleur'] ?? '';
        $memoire = $_POST['memoire'] ?? '';
        $model = $_POST['model'] ?? '';
        $reference = $_POST['reference'] ?? '';

        $sql = "UPDATE base_de_donn__e___harmytech___feuille_1 SET nom = ?, marque = ?, couleur = ?, memoire = ?, model = ?, reference = ? WHERE id = ?";
        $prepared_stmt = $connection_string->prepare($sql);
        $prepared_stmt->bind_param('ssssssi', $nom, $marque, $couleur, $memoire, $model, $reference, $id);
        if ($prepared_stmt->execute() === true) {
            echo "<p class='alert alert-success'>Produit modifié avec succès.</p>";
        } else {
            echo "<p class='alert alert-error'>Erreur lors de la modification du produit.</p>";
        }
        $prepared_stmt->close();
        $connection_string->close();
    }
    ?>
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
</style>
