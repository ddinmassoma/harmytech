<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=connexion");
    exit;
} elseif ($_SESSION['user_statut'] == 'utilisateur') {
    $_SESSION['message_erreur'] = "Le statut d'administrateur est requis pour effectuer cette action.";
    header("Location: index.php?page=accueil"); 
    exit;
}

if (isset($_SESSION['message_erreur'])) {
    echo "<div class='alert alert-error'>" . htmlspecialchars($_SESSION['message_erreur']) . "</div>";
    unset($_SESSION['message_erreur']);
}

if(isset($_GET['reset'])){
    header('Location: index.php?page=profil_administrateur');
    exit();
}

$secret = "une_cle_secrete_tres_longue_et_complexe_cote_serveur";
$id_recu = $_GET['id'];
$signature_recue = $_GET['sig'];
$signature_attendue = hash_hmac('sha256', $id_recu, $secret);
if ($signature_attendue!=$signature_recue) {
    http_response_code(403);
    echo "<p class='alert alert-error'>URL invalide ou falsifiée</p>";
    die();
}

require_once 'fonction.php';

[$connection_string, $page, , $limit, $offset] = value();
$id_proprietaire = $_GET['id'];
$sql ="SELECT * FROM base_de_donn__e___harmytech___feuille_1 
    WHERE id_proprietaire = ?
    ORDER BY id
    LIMIT $limit OFFSET $offset";

$prepared_stmt = $connection_string->prepare($sql);
$prepared_stmt->bind_param('i', $id_proprietaire);
$prepared_stmt->execute();
$result = $prepared_stmt->get_result();
echo"<h1>Details des possessions</h1>";
affichage_accueil($result);

$totalArticles = $connection_string->query("SELECT COUNT(*) 
    FROM base_de_donn__e___harmytech___feuille_1 
    WHERE id_proprietaire = $id_proprietaire")->fetch_row()[0];
pages($totalArticles, $limit, $page);
close($prepared_stmt,$connection_string);
echo"
<div class='form-actions'>
    <a href='index.php?page=profil_administrateur' class='btn-form btn-form-secondary'>
        <svg width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
            <line x1='19' y1='12' x2='5' y2='12'></line>
            <polyline points='12 19 5 12 12 5'></polyline>
        </svg>
        Retour
    </a>
</div>";

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


/* --- Boutons Stylisés --- */
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
        flex-direction: row-reverse;
        justify-content: flex-start;
    }
}

.btn-form-secondary {
    background-color: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    gap: 8px;
}

.btn-form-secondary:hover {
    background-color: #e2e8f0;
    color: #0f172a;
    transform: translateY(-1px);
}

.btn-form-secondary:active {
    transform: translateY(1px);
}

.btn-form svg {
    flex-shrink: 0;
}

.icon {
    margin-right: 6px;
    font-weight: bold;
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

/* Bouton Modifier */
.btn-card-edit {
    background-color: var(--color-dark-bg);
    color: #ffffff;
}

.btn-card-edit:hover {
    background-color: #1c2541;
}

/* Bouton Supprimer */
.btn-card-delete {
    background-color: transparent;
    color: var(--color-danger);
    border: 1px solid var(--color-danger);
}

.btn-card-delete:hover {
    background-color: var(--color-danger);
    color: #ffffff;
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