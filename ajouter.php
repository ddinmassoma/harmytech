<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=connexion");
    exit;
} elseif ($_SESSION['user_statut'] == 'utilisateur') {
    $_SESSION['message_erreur'] = "Le statut d'administrateur est requis pour effectuer cette action.";
    header("Location: index.php?page=accueil"); 
    exit;
}

require_once 'fonction.php';
?>

<h1>Ajouter un produit</h1>
<div class="search-filter-bar">
    <form action="" method="get" class="filter-form">
        <input type="hidden" name="page" value="ajouter">
        <div class="select-wrapper">
            <select name="nb_produit">
                <option value="1">-- Entrer le nombre de produit à ajouter --</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <button type="submit" class="btn btn-primary" name="list_ajout">Entrer</button>
        </div>
    </form>
</div>

<?php 
if(isset($_GET['list_ajout'])){
    $nb_produit = isset($_GET['nb_produit']) ? (int)$_GET['nb_produit'] : 1;
    $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    
    echo "<form action='' method='get'>";
    echo "<input type='hidden' name='page' value='ajouter'>";
    echo "<input type='hidden' name='list_ajout' value=''>";
    echo "<input type='hidden' name='nb_produit' value='".$nb_produit."'>";
    for($i=1; $i<=$nb_produit; $i++){
        formulaire_ajout_produit($i);
    }
    echo "<div class='form-actions'>";
        echo "<button type='submit' name='ajouter' class='btn-form btn-form-submit'>Ajouter les produits</button>";
        echo "<a href='index.php?page=accueil' class='btn-form btn-form-back'>Retour à l'accueil</a>";
    echo "</div>";
    echo "</form>";
    
    if (isset($_GET['ajouter'])){ 
        for($i=1; $i<=$nb_produit; $i++){
            $nom = $_GET['nom'.$i] ?? '';
            $marque = $_GET['marque'.$i] ?? '';
            $couleur = $_GET['couleur'.$i] ?? '';
            $memoire = $_GET['memoire'.$i] ?? '';
            $model = $_GET['model'.$i] ?? '';
            $reference = $_GET['reference'.$i] ?? '';
            $image = $_GET['image'.$i] ?? ''; 
            $id_proprietaire = $_GET['id_proprietaire'.$i]??'';
            if ($marque!='' && $nom!='' && $couleur!='' && $reference!='' && $model!='' && $memoire!='' && $image!= ''&& $id_proprietaire!=''){
                ajouter_produit($connection, $marque, $nom, $couleur, $reference, $model, $memoire, $image, $id_proprietaire);
            } 
        }
        $connection->close();
    }
    
} else {
    echo "<form action='' method='get'>";
    echo "<input type='hidden' name='page' value='ajouter'>";   
    formulaire_ajout_produit(1);
    echo "<div class='form-actions'>";
        echo "<button type='submit' name='ajouter' class='btn-form btn-form-submit'>Ajouter le produit</button>";
        echo "<a href='index.php?page=accueil' class='btn-form btn-form-back'>Retour à l'accueil</a>";
    echo "</div>";
    echo "</form>";

    if(isset($_GET['ajouter'])){
        $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $nom = $_GET['nom1'] ?? '';
        $marque = $_GET['marque1'] ?? '';
        $couleur = $_GET['couleur1'] ?? '';
        $memoire = $_GET['memoire1'] ?? '';
        $model = $_GET['model1'] ?? '';
        $reference = $_GET['reference1'] ?? '';
        $image = $_GET['image1'] ?? '';
        $id_proprietaire = $_GET['id_proprietaire1'] ?? '';
        if ($marque!='' && $nom!='' && $couleur!='' && $reference!='' && $model!='' && $memoire!='' && $image!='' && $id_proprietaire!=''){
            ajouter_produit($connection, $marque, $nom, $couleur, $reference, $model, $memoire, $image, $id_proprietaire);
        }
        $connection->close();
    }
}
?>

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

/* Bouton Ajouter */
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

/* --- Inputs & Selects stylisés --- */
.search-filter-bar input[type="text"],
.search-filter-bar select {
    padding: 10px 14px;
    font-size: 14px;
    border: 1px solid var(--color-border);
    border-radius: 6px;
    background-color: var(--color-light-bg);
    color: var(--color-text-main);
    outline: none;
    transition: all 0.2s ease-in-out;
    min-width: 180px;
}

.search-filter-bar input[type="text"]:focus,
.search-filter-bar select:focus {
    border-color: var(--color-accent);
    box-shadow: 0 0 0 3px rgba(94, 92, 230, 0.15);
    background-color: #ffffff;
}

/* --- Boutons Stylisés --- */
.btn {
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background-color: var(--color-dark-bg);
    color: #ffffff;
}

.btn-primary:hover {
    background-color: #1c2541;
    transform: translateY(-1px);
}

.btn:active {
    transform: translateY(1px);
}
</style>