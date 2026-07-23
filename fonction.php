<?php
//universelle :
function value(){
    $connection_string = new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $page = isset($_GET['subpage']) ? (int)$_GET['subpage'] : 1;
    $id=$_SESSION['user_id'];
    if ($page < 1) {
        $page = 1;
    }
    $limit = 4;
    $offset = ($page - 1) * $limit;
    if ($connection_string->connect_error) {
        echo "<div class='alert alert-error'>Erreur : impossible de se connecter à la base de donnée</div>";
        exit();
    }

    return [$connection_string, $page, $id, $limit, $offset];
}

function pages($totalArticles, $limit, $page){
    $totalPages = ceil($totalArticles / $limit);
    $params = $_GET;
    
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo ' <strong>' . $i . '</strong> ';
        } else {
            $params['subpage'] = $i;
            $queryString = http_build_query($params);
            echo ' <a href="index.php?' . htmlspecialchars($queryString) .'">' . $i .'</a> ';
        }
    }
}

function close($prepared_stmt, $connection_string){
    if($prepared_stmt) {
        $prepared_stmt->close();
    }
    $connection_string->close();
}


//accueil.php
function sql_accueil($limit, $offset, $couleur, $marque, $memoire, $proprietaire){
    if(isset($_GET['submit'])){
        $sql = "SELECT * 
                FROM base_de_donn__e___harmytech___feuille_1 
                WHERE nom LIKE ? 
                ORDER BY id
                LIMIT $limit OFFSET $offset";
    }elseif($couleur =="autre"){
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur NOT IN ('noir', 'blanc', 'gris', 'rouge', 'bleu', 'vert', 'jaune', 'violet', 'rose', 'orange')
            AND couleur LIKE '%$couleur%' 
            AND memoire LIKE '%$memoire%'
            AND id_proprietaire LIKE '$proprietaire'
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }elseif($proprietaire==1){
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur LIKE '%$couleur%' 
            AND memoire LIKE '%$memoire%'
            AND id_proprietaire != 0
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }elseif($proprietaire==0){
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur LIKE '%$couleur%' 
            AND memoire LIKE '%$memoire%'
            AND id_proprietaire = 0
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }else{
        $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 
            WHERE marque LIKE '%$marque%' 
            AND couleur LIKE '%$couleur%' 
            AND memoire LIKE '%$memoire%'
            AND id_proprietaire LIKE '$proprietaire'
            ORDER BY id
            LIMIT $limit OFFSET $offset";
    }
    return $sql;
} 

function name_user($row, $connection) {
    if (empty($row['id_proprietaire'])) {
        return "Utilisateur inconnu"; 
    }

    $id_user = $row['id_proprietaire'];
    $nom = "Utilisateur inconnu"; 

    $sql_nom = "SELECT nom FROM administrateur WHERE id = ?";
    
    if ($stmt = $connection->prepare($sql_nom)) {
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()) {
            $nom = $data['nom'];
        }
        $stmt->close();
    }

    return $nom;
}

function prenom_user($row, $connection) {
    if (empty($row['id_proprietaire'])) {
        return ""; 
    }

    $id_user = $row['id_proprietaire'];
    $prenom = "Utilisateur inconnu"; 

    $sql_nom = "SELECT prenom FROM administrateur WHERE id = ?";
    
    if ($stmt = $connection->prepare($sql_nom)) {
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()) {
            $prenom = $data['prenom'];
        }
        $stmt->close();
    }

    return $prenom;
}

function verification_proprietaire($id_proprietaire,$connection){
    $sql="SELECT * FROM administrateur WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id_proprietaire);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        return "connue";
    }else{
        return "inconnue";
    }
}

function affichage_produit($row,$signature){
    $connection=new mysqli("127.0.0.1", "root", "", "harmytech_phone");
    $nom=name_user($row,$connection);
    $prenom=prenom_user($row,$connection);
    echo "<div class='product-card'>";
        echo "<div class='product-body'>";
            echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']) . "</h3>";
            if(filter_var($row['image'], FILTER_VALIDATE_URL)===false) {
                echo "<img src ='https://as1.ftcdn.net/jpg/03/34/83/22/220_F_334832255_IMxvzYRygjd20VlSaIAFZrQWjozQH6BQ.jpg' class='product-img' alt='unknow product'>";
            }else {
                echo "<img src ='". $row['image']."' class='product-img' alt='Product image'>";
            }
            echo "<div class='product-info-grid'>";
                echo "<div class='info-item'><span>Marque :</span> <strong>" . htmlspecialchars($row['marque']) . "</strong></div>";
                echo "<div class='info-item'><span>Modèle :</span> <strong>" . htmlspecialchars($row['model']) . "</strong></div>";
                echo "<div class='info-item'><span>Couleur :</span> <strong>" . htmlspecialchars($row['couleur']) . "</strong></div>";
                echo "<div class='info-item'><span>Mémoire :</span> <strong>" . htmlspecialchars($row['memoire']) . "</strong></div>";
                if($_SESSION['user_statut']=='administrateur'){
                    echo "<div class='info-item'><span>Nom du propriétaire :</span> <strong>" . htmlspecialchars($nom) ." " . htmlspecialchars($prenom) ."</strong></div>";
                }
                echo "<div class='info-item'><span>Référence :</span> <code class='product-ref'>" . htmlspecialchars($row['reference']) . "</code></div>";
            echo "</div>";
            
            echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
        echo "</div>";
        echo "<div class='product-footer'>";
            echo "<a href='index.php?page=modifier&id=" . $row['id'] . "&sig=". $signature ."' class='btn-card btn-card-edit'>";
                echo "Modifier";
            echo "</a>";
            echo "<a href='index.php?page=supprimer&id=" . $row['id'] . "&sig=". $signature ."' class='btn-card btn-card-delete'>";
                echo "Supprimer";
            echo "</a>";
        echo "</div>";
    echo "</div>";
}

function affichage_accueil($result){
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $secret = "une_cle_secrete_tres_longue_et_complexe_cote_serveur";
            $signature = hash_hmac('sha256', $row['id'], $secret);
            affichage_produit($row,$signature);
        }
    } else {
        echo "<div class='alert alert-error'>Aucun produit trouvé</div>";
    }
}

//ajout_utilisateur.php
function formulaire_ajout_utilisateur($n){
    $nom = $_POST["nom$n"] ?? '';
    $prenom = $_POST["prenom$n"] ?? '';
    $identifiant = $_POST["identifiant$n"] ?? '';
    $mail = $_POST["mail$n"] ?? '';
    $mot_de_passe = $_POST["mot_de_passe$n"] ?? '';

    echo "<div class='form-container'>";
        echo "<h1 class='form-title'>Utilisateur n°". $n ."</h1>";
        echo "<div class='product-form'>";
            echo "<div class='form-grid'>";
                echo "<div class='input-group'>";
                    echo "<input type='text' name='nom$n' placeholder='Nom' value='".htmlspecialchars($nom, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='prenom$n' placeholder='Prenom' value='".htmlspecialchars($prenom, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='identifiant$n' placeholder='identifiant' value='".htmlspecialchars($identifiant, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='mail$n' placeholder='E-mail' value='".htmlspecialchars($mail, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='mot_de_passe$n' placeholder='Mot de passe' value='".htmlspecialchars($mot_de_passe, ENT_QUOTES)."' required>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

function verification_user($mail, $identifiant, $connection){
    $sql = "SELECT * FROM administrateur WHERE mail = ? AND identifiant = ?";
    $prepared_stmt = $connection->prepare($sql);
    $prepared_stmt->bind_param('ss', $mail, $identifiant);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();
    return $result;
}

function ajouter_ajout_utilisateur($nom, $prenom, $mail, $identifiant,$mot_de_passe,$connection){
    $sql = "INSERT INTO administrateur (nom, prenom, mail, identifiant, mot_de_passe) VALUES (?, ?, ?, ?, ?)";
    $prepared_stmt = $connection->prepare($sql);
    $prepared_stmt->bind_param('sssss', $nom, $prenom, $mail, $identifiant,$mot_de_passe);
    $result = verification_user($mail, $identifiant, $connection);
    if($result->num_rows === 1){
        echo "<p class='alert alert-error'>Erreur : l'e-mail ou l'identifiant est déjà utilisé.</p>";
    }else{
        if ($prepared_stmt->execute() === false) {
        echo "<p class='alert alert-error'>Erreur lors de l'ajout de l'utilisateur</p>";
        } else {
            echo "<p class='alert alert-success'>Utilisateur ajouté avec succès.</p>";
            $prepared_stmt->close();
        }
    }
}

//ajouter.php
function formulaire_ajout_produit($n){
    $nom = $_GET["nom$n"] ?? '';
    $marque = $_GET["marque$n"] ?? '';
    $couleur = $_GET["couleur$n"] ?? '';
    $memoire = $_GET["memoire$n"] ?? '';
    $model = $_GET["model$n"] ?? '';
    $reference = $_GET["reference$n"] ?? '';
    $image = $_GET["image$n"] ?? '';
    $id_proprietaire = $_GET['id_proprietaire$n'] ?? '';

    echo "<div class='form-container'>";
        echo "<h1 class='form-title'>Produit n°". $n ."</h1>";
        echo "<div class='product-form'>";
            echo "<div class='form-grid'>";
                echo "<div class='input-group'>";
                    echo "<input type='text' name='nom$n' placeholder='Nom du produit' value='".htmlspecialchars($nom, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='marque$n' placeholder='Marque du produit' value='".htmlspecialchars($marque, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='couleur$n' placeholder='Couleur du produit' value='".htmlspecialchars($couleur, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='memoire$n' placeholder='Mémoire du produit' value='".htmlspecialchars($memoire, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='model$n' placeholder='Modèle du produit' value='".htmlspecialchars($model, ENT_QUOTES)."' required>";
                echo "</div>";
                
                echo "<div class='input-group'>";
                    echo "<input type='text' name='reference$n' placeholder='Référence du produit' value='".htmlspecialchars($reference, ENT_QUOTES)."' required>";
                echo "</div>";

                echo "<div class='input-group'>";
                    echo "<input type='text' name='image$n' placeholder='lien image' value='".htmlspecialchars($image, ENT_QUOTES)."' required>";
                echo "</div>";

                echo "<div class='input-group'>";
                    echo "<input type='text' name='id_proprietaire$n' placeholder='ID du proprietaire' value='".htmlspecialchars($id_proprietaire, ENT_QUOTES)."' required>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

function verification_produit($reference, $nom, $connection){
    $sql = "SELECT * FROM base_de_donn__e___harmytech___feuille_1 WHERE reference = ? AND nom = ?";
    $prepared_stmt = $connection->prepare($sql);
    $prepared_stmt->bind_param('ss', $reference, $nom);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();
    return $result;
}

function ajouter_produit($connection,$marque,$nom,$couleur,$reference,$model,$memoire,$image, $id_proprietaire){
    $sql = "INSERT INTO base_de_donn__e___harmytech___feuille_1 (nom, marque, couleur, memoire, model, reference, `image`, id_proprietaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $prepared_stmt = $connection->prepare($sql);
    $prepared_stmt->bind_param('sssssssi', $nom, $marque, $couleur, $memoire, $model, $reference, $image, $id_proprietaire);
    $result=verification_produit($reference, $nom, $connection);
    $verification_id=verification_proprietaire($id_proprietaire,$connection);
    if($result->num_rows === 1){
        echo "<p class='alert alert-error'>Erreur : le nom ou la référence du produit est déjà utilisé.</p>";
    }elseif($verification_id==="inconnue"){
        echo "<p class='alert alert-error'>Erreur : ID inconnue.</p>";
    }else{
        if ($prepared_stmt->execute() === false) {
        echo "<p class='alert alert-error'>Erreur lors de l'ajout du produit \"".htmlspecialchars($nom)."\".</p>";
        } else {
            echo "<p class='alert alert-success'>Produit \"".htmlspecialchars($nom)."\" ajouté avec succès.</p>";
            $prepared_stmt->close();
        }
    }
}

//connexion.php
function historique($user,$connection_string){
    $nom = $user['nom'];
    $prenom = $user['prenom'];
    $mail = $user['mail'];
    $date = $user['date'];
    $statut = $user['statut'];
    $identifiant = $user['identifiant'];
    $id = $user['id'];
    $sql = "INSERT INTO historique_connexion (prenom_utilisateur, 
    nom_utilisateur, 
    mail_utilisateur, 
    date_utilisateur,
    statut_utilisateur,
    identifiant_utilisateur,
    id_utilisateur) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $prepared_stmt = $connection_string->prepare($sql);
    $prepared_stmt->bind_param('sssssss', $prenom, $nom, $mail, $date, $statut, $identifiant, $id);
    $prepared_stmt->execute();
}

//création_compte.php
function verification_creation_compte($colonne_sql,$connection,$valeur_unique){
    $sql = "SELECT $colonne_sql 
            FROM administrateur 
            WHERE $colonne_sql = ? "; 
    $prepared_stmt = $connection->prepare($sql);
    $prepared_stmt->bind_param('s', $valeur_unique);
    $prepared_stmt->execute();
    $result = $prepared_stmt->get_result();

    if ($result->num_rows === 1) {
        return 'erreur';
    }

}

//profil_administrateur.php
function affichage_user($row, $signature){
    echo "<div class='product-card'>";
        echo "<div class='product-body'>";
            echo "<h3 class='product-title'>" . htmlspecialchars($row['nom']).' '.htmlspecialchars($row['prenom'])."</h3>";
            if($row['statut'] == 'administrateur'){
                echo "<img src ='https://thumbs.dreamstime.com/b/ic%C3%B4ne-d-administration-vecteur-homme-utilisateur-profil-avatar-avec-roue-engrenage-pour-r%C3%A9glages-et-configuration-en-couleurs-150138136.jpg?w=576' class='profile-img' alt='Avatar Admin'></img>";
            }else{
                echo "<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' class='profile-img' alt='Avatar User'></img>";
            }
            
            echo "<div class='product-info-grid'>";
                echo "<div class='info-item'><span>Identifiant :</span> <strong>" . htmlspecialchars($row['identifiant']) . "</strong></div>";
                echo "<div class='info-item'><span>E-mail :</span> <strong>" . htmlspecialchars($row['mail']) . "</strong></div>";
                echo "<div class='info-item'><span>Statut :</span> <strong>" . htmlspecialchars($row['statut']) . "</strong></div>";
                echo "<div class='info-item'><span>Date de création du profil  :</span> <strong>" . htmlspecialchars($row['date']) . "</strong></div>";
            echo "</div>";
            
            echo "<span class='product-id-badge'>ID: " . $row['id'] . "</span>";
        echo "</div>";
        echo "<div class='product-footer'>";
            echo "<a href='index.php?page=modification_utilisateur&id=" . $row['id'] . "&sig=". $signature ."' class='btn-card btn-card-edit'>";
                echo "Modifier le statut";
            echo "</a>";
            echo "<a href='index.php?page=supression_utilisateur&id=" . $row['id'] . "&sig=". $signature ."' class='btn-card btn-card-delete'>";
                echo "Supprimer l'utilisateur";
            echo "</a>";
        echo "</div>";
    echo "</div>";
}

function affichage($result){
    if ($result->num_rows === 0) {
        echo "<div class='alert alert-error'>Aucun utilisateur trouvé</div>";
    } else {
        while ($row = $result->fetch_assoc()) {
            $secret = "une_cle_secrete_tres_longue_et_complexe_cote_serveur";
            $signature = hash_hmac('sha256', $row['id'], $secret);
            affichage_user($row,$signature);
        }
    }
}

//historique.php
function affichage_user_historique($row){
    echo "<div class='product-card'>";
        echo "<div class='product-body'>";
            echo "<h3 class='product-title'>" . htmlspecialchars($row['nom_utilisateur']).' '.htmlspecialchars($row['prenom_utilisateur'])."</h3>";
            if($row['statut_utilisateur'] == 'administrateur'){
                echo "<img src ='https://thumbs.dreamstime.com/b/ic%C3%B4ne-d-administration-vecteur-homme-utilisateur-profil-avatar-avec-roue-engrenage-pour-r%C3%A9glages-et-configuration-en-couleurs-150138136.jpg?w=576' class='profile-img' alt='Avatar Admin'>";
            }else{
                echo "<img src='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' class='profile-img' alt='Avatar User'>";
            }
            echo "<div class='product-info-grid'>";
                echo "<div class='info-item'><span>Identifiant :</span> <strong>" . htmlspecialchars($row['identifiant_utilisateur']) . "</strong></div>";
                echo "<div class='info-item'><span>E-mail :</span> <strong>" . htmlspecialchars($row['mail_utilisateur']) . "</strong></div>";
                echo "<div class='info-item'><span>Statut :</span> <strong>" . htmlspecialchars($row['statut_utilisateur']) . "</strong></div>";
                echo "<div class='info-item'><span>Date de création du profil  :</span> <strong>" . htmlspecialchars($row['date_utilisateur']) . "</strong></div>";
                echo "<div class='info-item'><span>Date de connexion  :</span> <strong>" . htmlspecialchars($row['date']) . "</strong></div>";
            echo "</div>";
            
            echo "<span class='product-id-badge'>ID: " . $row['id_utilisateur'] . "</span>";
        echo "</div>";
    echo "</div>";
}

function affichage_historique($result){
    if ($result->num_rows === 0) {
        echo "<div class='alert alert-error'>Aucun utilisateur trouvé</div>";
    } else {
        while ($row = $result->fetch_assoc()) {
            affichage_user_historique($row);
        }
    }
}