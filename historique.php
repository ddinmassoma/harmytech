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

function affichage_user($row, $signature){
    echo "<div class='product-card'>";
        echo "<div class='product-body'>";
            echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']).' '.htmlspecialchars($row['prenom'])."</h3>";
            
            echo "<div class='product-info-grid'>";
                echo "<div class='info-item'><span>Identifiant :</span> <strong>" . htmlspecialchars($row['identifiant']) . "</strong></div>";
                echo "<div class='info-item'><span>E-mail :</span> <strong>" . htmlspecialchars($row['mail']) . "</strong></div>";
                echo "<div class='info-item'><span>Statut :</span> <strong>" . htmlspecialchars($row['statut']) . "</strong></div>";
                echo "<div class='info-item'><span>Date de création du profil  :</span> <strong>" . htmlspecialchars($row['date']) . "</strong></div>";
            echo "</div>";
            
            echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
        echo "</div>";
    echo "</div>";
}


?>

