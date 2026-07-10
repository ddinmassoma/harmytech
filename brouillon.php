<h1>Ajouter un produit</h1>
<div class="search-filter-bar">
    <form action="" method="get" class="filter-form">
        <input type="hidden" name="page" value="brouillon">
        <div class="select-wrapper">
            <select name="nb_produit">
                <option value="0">-- Entrer le nombre de produit à ajouter --</option>
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
    $nb_produit=$_GET['nb_produit']? (int)$_GET['nb_produit']:0;
    $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    for($i=1;$i<=$nb_produit;$i++){
    echo "<div class='form-container'>";
        echo"<h1 class='form-title'>Produit n°". $i ."</h1>";

        echo "<form action='' method='get' class='product-form'>";

            echo "<input type='hidden' name='page' value='brouillon'>";

            echo "<div class='form-grid'>";
                echo "<div class='input-group'>";
                    echo "<input type='text' name='nom$i' placeholder='Nom du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='marque$i' placeholder='Marque du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='couleur$i' placeholder='Couleur du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='memoire$i' placeholder='Mémoire du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='model$i' placeholder='Modèle du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='reference$i' placeholder='Référence du produit' required>";
                echo "</div>";
            echo "</div>";

            echo "<div class='form-actions'>";
                echo "<button type='submit' name='ajouter' class='btn-form btn-form-submit'>";
                    echo "Ajouter les produits";
                echo "</button>";
                echo "<a href='index.php?page=accueil' class='btn-form btn-form-back'>";
                    echo "Retour à l'accueil";
                echo "</a>";
            echo "</div>";

        echo "</form>";
    echo "</div>";
    }
    if($nb_produit==0){
        $newURL="index.php?page=brouillon";
        header("Location: " . $newURL, true, 303);      
    }else{
    if (isset($_GET['ajouter'])){
        if($nb_produit>1){
            $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
            $erreur = true;
            for($i=1;$i<=$nb_produit;$i++){
                $nom = $_GET['nom'.$i] ?? '';
                $marque = $_GET['marque'.$i] ?? '';
                $couleur = $_GET['couleur'.$i] ?? '';
                $memoire = $_GET['memoire'.$i] ?? '';
                $model = $_GET['model'.$i] ?? '';
                $reference = $_GET['reference'.$i] ?? '';
                $sql = "INSERT INTO base_de_donn__e___harmytech___feuille_1 (nom, marque, couleur, memoire, model, reference) VALUES (?, ?, ?, ?, ?, ?)";
                $prepared_stmt = $connection->prepare($sql);
                $prepared_stmt->bind_param('ssssss', $nom, $marque, $couleur, $memoire, $model, $reference);
                if ($prepared_stmt->execute() === true) {
                    $erreur = false;
                }
            }
            if ($erreur==false) {
                    echo "<p class='alert alert-error'>Erreur lors de l'ajout du produit.</p>";
            } else {
                echo "<p class='alert alert-success'>Produit ajouté avec succès.</p>";
            }
            $connection->close();
        }
    }
    }
}else{
    echo "<div class='form-container'>";
        echo"<h1 class='form-title'>Produit n°1</h1>";

        echo "<form action='' method='post' class='product-form'>";
            echo "<div class='form-grid'>";
                echo "<div class='input-group'>";
                    echo "<input type='text' name='nom' placeholder='Nom du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='marque' placeholder='Marque du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='couleur' placeholder='Couleur du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='memoire' placeholder='Mémoire du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='model' placeholder='Modèle du produit' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='reference' placeholder='Référence du produit' required>";
                echo "</div>";
            echo "</div>";
            echo "<div class='form-actions'>";
                echo "<button type='submit' name='ajouter' class='btn-form btn-form-submit'>Ajouter le produit</button>";
                echo "<a href='index.php?page=accueil' class='btn-form btn-form-back'>Retour à l'accueil</a>";
            echo "</div>";
        echo "</form>";
    echo "</div>";


    if(isset($_POST['ajouter'])){
        $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $nom = $_POST['nom'] ?? '';
        $marque = $_POST['marque'] ?? '';
        $couleur = $_POST['couleur'] ?? '';
        $memoire = $_POST['memoire'] ?? '';
        $model = $_POST['model'] ?? '';
        $reference = $_POST['reference'] ?? '';
        $sql = "INSERT INTO base_de_donn__e___harmytech___feuille_1 (nom, marque, couleur, memoire, model, reference) VALUES (?, ?, ?, ?, ?, ?)";
        $prepared_stmt = $connection->prepare($sql);
        $prepared_stmt->bind_param('ssssss', $nom, $marque, $couleur, $memoire, $model, $reference);
        if ($prepared_stmt->execute() === false) {
            echo "<p class='alert alert-error'>Erreur lors de l'ajout du produit.</p>";
        } else {
            echo "<p class='alert alert-success'>Produit ajouté avec succès.</p>";
        }
        $prepared_stmt->close();
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