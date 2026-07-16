<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}

if (isset($_SESSION['message_erreur'])) {
    echo "<div class='alert alert-error'>" . htmlspecialchars($_SESSION['message_erreur']) . "</div>";
    unset($_SESSION['message_erreur']);
}
?>

<div class="catalog-container">
    
    <div class="action-header">
        <a href="index.php?page=ajouter" class="btn btn-accent">
            <span class="icon">+</span> Ajouter un produit
        </a>
    </div>

    <div class="search-filter-bar">
        
        <form action="" method="get" class="search-form">
            <input 
                type="text" 
                placeholder="Entrez le nom du produit..." 
                name="search">
            <button type="submit" name="submit" class="btn btn-primary">Rechercher</button>
            <button type="submit" name="reset" class="btn btn-primary">Reset</button>
        </form>

        <form method="get" action="" class="filter-form">
            <input type="hidden" name="page" value="accueil">
             
            <div class="select-wrapper">
                <select name="marque">
                    <option value="">-- Sélectionner une marque --</option>
                    <option value="apple">Apple</option>
                    <option value="Samsung">Samsung</option>
                    <option value="xiaomi">Xiaomi</option>
                    <option value="Google">Google</option>
                    <option value="motorola">Motorola</option>
                </select>

                <select name="couleur">
                    <option value="">-- Sélectionner une couleur --</option>
                    <option value="noir">Noir</option>
                    <option value="blanc">Blanc</option>
                    <option value="gris">Gris</option>
                    <option value="rouge">Rouge</option>
                    <option value="bleu">Bleu</option>
                    <option value="vert">Vert</option>
                    <option value="jaune">Jaune</option>
                    <option value="violet">Violet</option>
                    <option value="rose">Rose</option>
                    <option value="orange">Orange</option>
                    <option value="autre">Autre</option>
                </select>

                <select name="memoire">
                    <option value="">-- Sélectionner une mémoire --</option>
                    <option value="32Go">32 Go</option>
                    <option value="64Go">64 Go</option>
                    <option value="128Go">128 Go</option>
                    <option value="256Go">256 Go</option>
                    <option value="512Go">512 Go</option>
                    <option value="1To">1 To</option>
                    <option value="2To">2 To</option>
                    <option value="inconnue">Autre</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Voir les produits</button>
        </form>
    </div>
</div>

<?php
    if (isset($_GET['submit'])) {
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $searchString = mysqli_real_escape_string($connection_string, trim(htmlentities($_GET['search'] ?? '')));
        $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $limit = 4;
        $offset = ($page - 1) * $limit;

        if ($connection_string->connect_error) {
            echo "Failed to connect to Database";
            exit();
        }
        
        $searchString = "%$searchString%";
        $sql = "SELECT * 
                FROM base_de_donn__e___harmytech___feuille_1 
                WHERE nom LIKE ? 
                ORDER BY id
                LIMIT $limit OFFSET $offset";
        $prepared_stmt = $connection_string->prepare($sql);
        $prepared_stmt->bind_param('s', $searchString);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();

        if ($result->num_rows === 0) {
            echo "Aucun produit trouvé";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                    echo "<div class='product-body'>";
                        echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']) . "</h3>";
                        
                        echo "<div class='product-info-grid'>";
                            echo "<div class='info-item'><span>Marque :</span> <strong>" . htmlspecialchars($row['marque']) . "</strong></div>";
                            echo "<div class='info-item'><span>Modèle :</span> <strong>" . htmlspecialchars($row['model']) . "</strong></div>";
                            echo "<div class='info-item'><span>Couleur :</span> <strong>" . htmlspecialchars($row['couleur']) . "</strong></div>";
                            echo "<div class='info-item'><span>Mémoire :</span> <strong>" . htmlspecialchars($row['memoire']) . "</strong></div>";
                            echo "<div class='info-item'><span>Référence :</span> <code class='product-ref'>" . htmlspecialchars($row['reference']) . "</code></div>";
                        echo "</div>";
                        
                        echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
                    echo "</div>";
                    echo "<div class='product-footer'>";
                        echo "<a href='index.php?page=modifier&id=" . $row['id'] . "' class='btn-card btn-card-edit'>";
                            echo "Modifier";
                        echo "</a>";
                        echo "<a href='index.php?page=supprimer&id=" . $row['id'] . "' class='btn-card btn-card-delete'>";
                            echo "Supprimer";
                        echo "</a>";
                    echo "</div>";
                echo "</div>";
            }
        }

        $totalArticles = $connection_string->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1 WHERE nom LIKE '$searchString'")->fetch_row()[0];
        $totalPages = ceil($totalArticles / $limit);
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo ' ';
                echo '<strong>' . $i . '</strong> ';
            } else {
                echo ' ';
                echo '<a href="index.php?page=accueil&search=' .urlencode($_GET['search']) .'&submit=Rechercher&subpage=' . $i .'">' . $i .'</a>';
            }
        }

        $prepared_stmt->close();
        $connection_string->close();
    }elseif(!isset($_GET['submit']) && isset($_GET['reset'])){
        $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $limit = 4;
        $offset = ($page - 1) * $limit;

        if ($connection_string->connect_error) {
            echo "Echec de connexion à la base de donnée";
            exit();
        }
        
        $sql = "SELECT * 
                FROM base_de_donn__e___harmytech___feuille_1  
                ORDER BY id
                LIMIT $limit OFFSET $offset";
        $prepared_stmt = $connection_string->prepare($sql);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();

        if ($result->num_rows === 0) {
            echo "Aucun produit trouvé";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                    echo "<div class='product-body'>";
                        echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']) . "</h3>";
                        
                        echo "<div class='product-info-grid'>";
                            echo "<div class='info-item'><span>Marque :</span> <strong>" . htmlspecialchars($row['marque']) . "</strong></div>";
                            echo "<div class='info-item'><span>Modèle :</span> <strong>" . htmlspecialchars($row['model']) . "</strong></div>";
                            echo "<div class='info-item'><span>Couleur :</span> <strong>" . htmlspecialchars($row['couleur']) . "</strong></div>";
                            echo "<div class='info-item'><span>Mémoire :</span> <strong>" . htmlspecialchars($row['memoire']) . "</strong></div>";
                            echo "<div class='info-item'><span>Référence :</span> <code class='product-ref'>" . htmlspecialchars($row['reference']) . "</code></div>";
                        echo "</div>";
                        
                        echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
                    echo "</div>";
                    echo "<div class='product-footer'>";
                        echo "<a href='index.php?page=modifier&id=" . $row['id'] . "' class='btn-card btn-card-edit'>";
                            echo "Modifier";
                        echo "</a>";
                        echo "<a href='index.php?page=supprimer&id=" . $row['id'] . "' class='btn-card btn-card-delete'>";
                            echo "Supprimer";
                        echo "</a>";
                    echo "</div>";
                echo "</div>";
            }
        }

        $totalArticles = $connection_string->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1")->fetch_row()[0];
        $totalPages = ceil($totalArticles / $limit);
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo ' ';
                echo '<strong>' . $i . '</strong> ';
            } else {
                echo ' ';
                echo '<a href="index.php?page=accueil&search=' .urlencode($_GET['search']) .'&submit=Rechercher&subpage=' . $i .'">' . $i .'</a>';
            }
        }

        $prepared_stmt->close();
        $connection_string->close();
    }else{
        //Valeurs des filtres
        $connection = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
        $marque = $_GET['marque']??'';
        $couleur =$_GET['couleur']??'';
        $memoire = $_GET['memoire']??'';
        $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $limit = 4;
        $offset = ($page - 1) * $limit;

        //Valeur de la requête SQL en fonction des filtres sélectionnés
        if($couleur =="autre"){
            $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
                WHERE marque LIKE '%$marque%' 
                AND couleur NOT IN ('noir', 'blanc', 'gris', 'rouge', 'bleu', 'vert', 'jaune', 'violet', 'rose', 'orange')
                AND couleur LIKE '%$couleur%' 
                AND memoire LIKE '%$memoire%'
                ORDER BY id
                LIMIT $limit OFFSET $offset";
        }else{
            $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
                WHERE marque LIKE '%$marque%' 
                AND couleur LIKE '%$couleur%' 
                AND memoire LIKE '%$memoire%'
                ORDER BY id
                LIMIT $limit OFFSET $offset";
        }

                
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                    echo "<div class='product-body'>";
                        echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']) . "</h3>";
                        
                        echo "<div class='product-info-grid'>";
                            echo "<div class='info-item'><span>Marque :</span> <strong>" . htmlspecialchars($row['marque']) . "</strong></div>";
                            echo "<div class='info-item'><span>Modèle :</span> <strong>" . htmlspecialchars($row['model']) . "</strong></div>";
                            echo "<div class='info-item'><span>Couleur :</span> <strong>" . htmlspecialchars($row['couleur']) . "</strong></div>";
                            echo "<div class='info-item'><span>Mémoire :</span> <strong>" . htmlspecialchars($row['memoire']) . "</strong></div>";
                            echo "<div class='info-item'><span>Référence :</span> <code class='product-ref'>" . htmlspecialchars($row['reference']) . "</code></div>";
                        echo "</div>";
                        
                        echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
                    echo "</div>";
                    echo "<div class='product-footer'>";
                        echo "<a href='index.php?page=modifier&id=" . $row['id'] . "' class='btn-card btn-card-edit'>";
                            echo "Modifier";
                        echo "</a>";
                        echo "<a href='index.php?page=supprimer&id=" . $row['id'] . "' class='btn-card btn-card-delete'>";
                            echo "Supprimer";
                        echo "</a>";
                    echo "</div>";
                echo "</div>";
            }
        } else {
            echo "Aucun produit trouvé <br/>";
        }

        $totalArticlesQuery = $connection->query("SELECT COUNT(*) FROM base_de_donn__e___harmytech___feuille_1 WHERE marque LIKE '%$marque%' AND couleur LIKE '%$couleur%' AND memoire LIKE '%$memoire%'");
        $totalArticles = $totalArticlesQuery->fetch_row()[0];
        $totalPages = ceil($totalArticles / $limit);

        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $page) {
                    echo ' <strong>' . $i . '</strong> ';
                } else {
                    echo ' <a href="index.php?page=accueil&marque=' . urlencode($marque) . '&couleur=' . urlencode($couleur) . '&memoire=' . urlencode($memoire) . '&subpage=' . $i . '">'  . $i . '</a> ';
                }
            }
        }
        $connection->close();
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