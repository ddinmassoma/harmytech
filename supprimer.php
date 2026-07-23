<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}elseif ($_SESSION['user_statut'] == 'utilisateur') {
    $_SESSION['message_erreur'] = "Le statut d'administrateur est requis pour effectuer cette action.";
    header("Location: index.php?page=accueil"); 
    exit;
}

$secret = "une_cle_secrete_tres_longue_et_complexe_cote_serveur";
$id_recu = $_GET['id'];
$signature_recue = $_GET['sig'];
$signature_attendue = hash_hmac('sha256', $id_recu, $secret);
require_once 'fonction.php';
?>

<style>
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

<h1>Supprimer un produit</h1>
<?php 
if ($signature_attendue!=$signature_recue) {
    http_response_code(403);
    echo "<p class='alert alert-error'>URL invalide ou falsifiée</p>";
    die();
}
?>

<?php
// name_user($row, $connection);
// prenom_user($row, $connection);
$connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
$id = $_GET['id'];
$sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
$sql_delete ="DELETE FROM base_de_donn__e___harmytech___feuille_1 WHERE id = ?";
if (isset($_GET['id'])) {
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('i', $id);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();
    $row = $result->fetch_assoc();
    if ($result->num_rows != 1) {
            header("Location: index.php?page=accueil");
            exit();   
    }

    if (isset($_POST['supprimer'])) {
        $prepared_stmt = $connection_string->prepare($sql_delete);
        $prepared_stmt->bind_param('i', $id);
        if ($prepared_stmt->execute() === true) {
            echo "<p class='alert alert-success'>Produit supprimer avec succés</p>";
            echo "<a href='index.php?page=accueil' class='btn-card btn-card-back'>";
                echo "Retour à l'accueil";
            echo "</a>";
        } else {
            echo "<p class='alert alert-error'>Erreur lors de la suppression du produit.</p>";
            echo "<a href='index.php?page=accueil' class='btn-card btn-card-back'>";
                echo "Retour à l'accueil";
            echo "</a>";
        }
        $prepared_stmt->close();
        $connection_string->close();
    } else{
        $nom=name_user($row,$connection_string);
        $prenom=prenom_user($row,$connection_string);
        echo "<div class='product-card'>";
            echo "<div class='product-body'>";
                echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']) . "</h3>";
                if(filter_var($row['image'], FILTER_VALIDATE_URL) === false) {
                    echo "<img src ='https://as1.ftcdn.net/jpg/03/34/83/22/220_F_334832255_IMxvzYRygjd20VlSaIAFZrQWjozQH6BQ.jpg' class='product-img' alt='unknow product'>";
                }else {
                    echo "<img src ='". $row['image']."' class='product-img' alt='Product image'>";
                }
                echo "<div class='product-info-grid'>";
                    echo "<div class='info-item'><span>Marque :</span> <strong>" . htmlspecialchars($row['marque']) . "</strong></div>";
                    echo "<div class='info-item'><span>Modèle :</span> <strong>" . htmlspecialchars($row['model']) . "</strong></div>";
                    echo "<div class='info-item'><span>Couleur :</span> <strong>" . htmlspecialchars($row['couleur']) . "</strong></div>";
                    echo "<div class='info-item'><span>Mémoire :</span> <strong>" . htmlspecialchars($row['memoire']) . "</strong></div>";
                    echo "<div class='info-item'><span>Nom du propriétaire :</span> <strong>" . htmlspecialchars($nom) ." " . htmlspecialchars($prenom) ."</strong></div>";
                    echo "<div class='info-item'><span>Référence :</span> <code class='product-ref'>" . htmlspecialchars($row['reference']) . "</code></div>";
                echo "</div>";
                echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
            echo "</div>";

            echo "<p class='alert alert-advetissement'>Êtes-vous sûr de vouloir supprimer ce produit ?</p>"."<br/>";
            
            echo "<div class='product-footer'>";
                echo "<a href='index.php?page=accueil' class='btn-card btn-card-back'>";
                    echo "Retour à l'accueil";
                echo "</a>";
                echo"<form action='' method='post'>";
                    echo"<button type='submit' name='supprimer' class='btn-form btn-form-submit'>Supprimer le produit</button>";
                echo "</form>";
            echo "</div>";
        echo "</div>";
    }
}else {
    echo "<p class='alert alert-error'>Aucun produit trouvé.</p>";
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
    --color-danger: #ef4444;       
    --color-danger-hover: #dc2626;
    --color-muted: #6b7280; 
}

/* --- La Carte Produit --- */
.product-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    max-width: 450px;
    margin: 20px 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    font-family: 'Segoe UI', sans-serif;
}

.product-img {
    width: 140px;
    height: 140px;         
    object-fit: contain;
    border-radius: 8px;
    display: block;        
    margin: 15px auto;     
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    background-color: #ffffff;
    padding: 10px;
}

/* --- Corps de la carte --- */
.product-body {
    padding: 20px;
    position: relative;
}

.product-title {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 18px;
    color: var(--color-dark-bg); 
    border-bottom: 2px solid var(--color-accent);
    padding-bottom: 8px;
}

/* Grille d'informations internes */
.product-info-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item {
    font-size: 14px;
    color: var(--color-text-main);
    display: flex;
    justify-content: space-between; 
}

.info-item span {
    color: var(--color-muted);
}

.product-ref {
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 12px;
}

/* Badge ID discret en haut à droite */
.product-id-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #f1f5f9;
    color: var(--color-muted);
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: bold;
}

.product-footer {
    background: #f8fafc;
    padding: 12px 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end; /* Aligne les boutons à droite */
    border-top: 1px solid #edf2f7;
}

/* Style de base des boutons de carte */
.btn-card {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.2s ease;
    text-align: center;
}

/* Bouton Accueil */
.btn-card-back {
    background-color: var(--color-dark-bg);
    color: #ffffff;
}

.btn-card-back:hover {
    background-color: #1c2541;
    transform: translateY(-1px);
}

/* Bouton Supprimer */
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

.btn-form:active {
    transform: translateY(1px);
}

.btn-form-submit {
    background-color: var(--color-danger-hover);
    color: #ffffff;
}

.btn-form-submit:hover {
    transform: translateY(-1px);
}

/* --- Style des Messages d'Alerte (Succès / Erreur / Avertissement) --- */
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

/* Alerte Avertissement */
.alert-advetissement{
    background-color: #fef2f2;
    color: #fa8e00;
    border-color: #e7782e;
}
</style>