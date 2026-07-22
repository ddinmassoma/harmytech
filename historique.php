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
    header('Location: index.php?page=historique');
    exit();
}

require_once 'fonction.php';
?>

<h1>Historique des connexion</h1>
<div class="catalog-container">

    <div class="search-filter-bar">
        <form action="" method="get" class="search-form">
            <input type="hidden" name="page" value="historique">
            <input 
                type="text" 
                placeholder="Entrez un nom..." 
                name="search">
            <button type="submit" name="recherche" class="btn btn-primary">Rechercher</button>
            <button type="submit" name="reset" class="btn btn-primary">Reset</button>
        </form>

        <form method="get" action="" class="filter-form">
            <input type="hidden" name="page" value="historique">
             
            <div class="select-wrapper">
                <select name="lettre">
                    <option value="%">-- Filtrer par lettre --</option>
                    <option value="a">a</option>
                    <option value="b">b</option>
                    <option value="c">c</option>
                    <option value="d">d</option>
                    <option value="e">e</option>
                    <option value="f">f</option>
                    <option value="g">g</option>
                    <option value="h">h</option>
                    <option value="i">i</option>
                    <option value="j">j</option>
                    <option value="k">k</option>
                    <option value="l">l</option>
                    <option value="m">m</option>
                    <option value="n">n</option>
                    <option value="o">o</option>
                    <option value="p">p</option>
                    <option value="q">q</option>
                    <option value="r">r</option>
                    <option value="s">s</option>
                    <option value="t">t</option>
                    <option value="u">u</option>
                    <option value="v">v</option>
                    <option value="w">w</option>
                    <option value="x">x</option>
                    <option value="y">y</option>
                    <option value="z">z</option>
                </select>

                <select name="annee">
                    <option value="%">-- Filtrer par année --</option>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                </select>

                <select name="mois">
                    <option value="%">-- Filtrer par mois --</option>
                    <option value="01">Janvier</option>
                    <option value="02">Février</option>
                    <option value="03">Mars</option>
                    <option value="04">Avril</option>
                    <option value="05">Mai</option>
                    <option value="06">Juin</option>
                    <option value="07">Juillet</option>
                    <option value="08">Août</option>
                    <option value="09">Septembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Décembre</option>
                </select>

                <select name="statut">
                    <option value="%">-- Filtrer par statut --</option>
                    <option value="utilisateur">utilisateur</option>
                    <option value="administrateur">administrateur</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Voir les utilisateurs</button>
        </form>
    </div>
</div>

<?php
$mois = $_GET['mois']??'%';
$annee = $_GET['annee']??'%';
$lettre = $_GET['lettre']??'%';
$statut = $_GET['statut']??'%';
[$connection_string, $page, $id, $limit, $offset] = value();


if(isset($_GET['recherche'])){
    $recherche = $_GET['search']??'';
    $sql = "SELECT * 
        FROM historique_connexion
        WHERE prenom_utilisateur = ? OR nom_utilisateur = ?
        ORDER BY `date` DESC
        LIMIT $limit OFFSET $offset";

    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('ss', $recherche, $recherche);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();

    affichage_historique($result);

    $count_sql = "SELECT COUNT(*) FROM historique_connexion WHERE prenom_utilisateur = '$recherche' OR nom_utilisateur = '$recherche'";
    $totalUser = $connection_string->query($count_sql)->fetch_row()[0];
    pages($totalUser, $limit, $page);

    close($prepared_stmt,$connection_string);
}else{$sql = "SELECT * 
            FROM historique_connexion 
            WHERE (nom_utilisateur LIKE '$lettre%' OR prenom_utilisateur LIKE '$lettre%')
            AND `date` LIKE '%-$mois-%' AND `date` LIKE '$annee-%'
            AND statut_utilisateur LIKE '$statut'
            ORDER BY `date` DESC
            LIMIT $limit OFFSET $offset";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();

    affichage_historique($result);
    $totalUser = $connection_string->query("SELECT COUNT(*) 
    FROM historique_connexion
    WHERE (nom_utilisateur LIKE '$lettre%' OR prenom_utilisateur LIKE '$lettre%')
    AND `date` LIKE '%-$mois-%' AND `date` LIKE '$annee-%'
    AND statut_utilisateur LIKE '$statut'
    ORDER BY `date` DESC")->fetch_row()[0];
    pages($totalUser, $limit, $page);

    close($prepared_stmt,$connection_string);
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

/* --- Conteneur Général --- */
.catalog-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    color: var(--color-text-main);
}

/* --- Alignement des barres --- */
.action-header {
    margin-bottom: 25px;
}

.search-filter-bar {
    display: flex;
    flex-direction: column;
    gap: 15px;
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    border: 1px solid #edf2f7;
    margin-bottom: 30px;
}

@media (min-width: 768px) {
    .search-filter-bar {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

/* --- Formulaires --- */
.search-form, .filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.select-wrapper {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
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

.btn-accent {
    background-color: var(--color-accent);
    color: #ffffff;
}

.btn-accent:hover {
    background-color: var(--color-accent-hover);
    transform: translateY(-1px);
}

.btn:active {
    transform: translateY(1px);
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

.profile-img {
    width: 120px;          
    height: 120px;         
    object-fit: cover;     
    border-radius: 50%;    
    display: block;        
    margin: 15px auto;     
    border: 3px solid #e2e8f0; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
