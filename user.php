<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connexion']) || $_SESSION['connexion'] !== true || !isset($_SESSION['id'])) {
    $_SESSION['flash_error'] = "Vous devez être connecté pour accéder à cette page.";
    header("Location: ./connexion.php");
    exit;
}

include_once "./includes/connexionbdd.php";

$user_id = $_SESSION['id'];

// Récupérer les informations de l'utilisateur
try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        session_destroy();
        header("Location: ./connexion.php");
        exit;
    }
} catch(PDOException $e) {
    error_log("Erreur récupération utilisateur : " . $e->getMessage());
    die("Erreur système");
}

// ========== TRAITEMENT MODIFICATION PROFIL ==========
if (!empty($_POST["modifier_profil"])) {
    $civilite = trim($_POST["civilite"]);
    $prenom = trim(strip_tags($_POST["prenom"]));
    $nom = trim(strip_tags($_POST["nom"]));
    $email = trim(strtolower($_POST["email"]));
    
    // Validation
    if (strlen($prenom) < 2 || strlen($prenom) > 50) {
        $_SESSION['flash_error'] = "Le prénom doit contenir entre 2 et 50 caractères.";
    }
    elseif (strlen($nom) < 2 || strlen($nom) > 50) {
        $_SESSION['flash_error'] = "Le nom doit contenir entre 2 et 50 caractères.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = "L'adresse email n'est pas valide.";
    }
    else {
        // Vérifier si l'email est déjà utilisé par un autre utilisateur
        try {
            $sql = "SELECT id FROM users WHERE mail = :email AND id != :user_id";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                $_SESSION['flash_error'] = "Cette adresse email est déjà utilisée.";
            } else {
                // Mettre à jour le profil
                $sql = "UPDATE users SET civilite = :civilite, prenom = :prenom, nom = :nom, mail = :email WHERE id = :user_id";
                $stmt = $connexion->prepare($sql);
                $stmt->bindValue(":civilite", $civilite);
                $stmt->bindValue(":prenom", $prenom);
                $stmt->bindValue(":nom", $nom);
                $stmt->bindValue(":email", $email);
                $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->execute();
                
                $_SESSION['flash_success'] = "Profil modifié avec succès !";
                
                // Recharger les données
                $user['civilite'] = $civilite;
                $user['prenom'] = $prenom;
                $user['nom'] = $nom;
                $user['mail'] = $email;
            }
        } catch(PDOException $e) {
            error_log("Erreur modification profil : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur est survenue.";
        }
    }
    
    header("Location: ./user.php#profil");
    exit;
}

// ========== TRAITEMENT CHANGEMENT MOT DE PASSE ==========
if (!empty($_POST["changer_mdp"])) {
    $ancien_mdp = $_POST["ancien_mdp"];
    $nouveau_mdp = $_POST["nouveau_mdp"];
    $confirmer_mdp = $_POST["confirmer_mdp"];
    
    // Validation
    if (!password_verify($ancien_mdp, $user['mdp'])) {
        $_SESSION['flash_error'] = "L'ancien mot de passe est incorrect.";
    }
    elseif (strlen($nouveau_mdp) < 8) {
        $_SESSION['flash_error'] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
    }
    elseif ($nouveau_mdp !== $confirmer_mdp) {
        $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
    }
    else {
        try {
            $mdp_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET mdp = :mdp WHERE id = :user_id";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(":mdp", $mdp_hash);
            $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $_SESSION['flash_success'] = "Mot de passe modifié avec succès !";
        } catch(PDOException $e) {
            error_log("Erreur changement mot de passe : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur est survenue.";
        }
    }
    
    header("Location: ./user.php#profil");
    exit;
}

// ========== TRAITEMENT COMMENTAIRE ==========
if (!empty($_POST["envoyer_commentaire"])) {
    $note = intval($_POST["note"]);
    $commentaire = htmlspecialchars(trim($_POST["commentaire"]));
    
    if ($note < 1 || $note > 5) {
        $_SESSION['flash_error'] = "La note doit être entre 1 et 5 étoiles.";
    }
    elseif (strlen($commentaire) < 10 || strlen($commentaire) > 500) {
        $_SESSION['flash_error'] = "Le commentaire doit contenir entre 10 et 500 caractères.";
    }
    else {
        try {
            $sql = "INSERT INTO commentaire (pseudo, email, note, commentaire, approved, date_creation) 
                    VALUES (:pseudo, :email, :note, :commentaire, 0, NOW())";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(":pseudo", $user['prenom'] . ' ' . $user['nom']);
            $stmt->bindValue(":email", $user['mail']);
            $stmt->bindValue(":note", $note, PDO::PARAM_INT);
            $stmt->bindValue(":commentaire", $commentaire);
            $stmt->execute();
            
            $_SESSION['flash_success'] = "Merci pour votre avis ! Il sera visible après modération.";
        } catch(PDOException $e) {
            error_log("Erreur insertion commentaire : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur est survenue.";
        }
    }
    
    header("Location: ./user.php#commentaires");
    exit;
}

// Récupérer les messages flash
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// ========== RÉCUPÉRATION HISTORIQUE ==========
// Historique des contacts
try {
    $sql = "SELECT id, sujet, message, status, DATE_FORMAT(date_creation, '%d/%m/%Y à %H:%i') as date_fr 
            FROM contact 
            WHERE email = :email 
            ORDER BY date_creation DESC 
            LIMIT 10";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(":email", $user['mail']);
    $stmt->execute();
    $historique_contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Erreur récupération contacts : " . $e->getMessage());
    $historique_contacts = [];
}

// Historique des demandes de devis
try {
    $sql = "SELECT id, Professionnels_Particuliers, message, status, DATE_FORMAT(date_creation, '%d/%m/%Y à %H:%i') as date_fr 
            FROM requete_devis 
            WHERE email = :email 
            ORDER BY date_creation DESC 
            LIMIT 10";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(":email", $user['mail']);
    $stmt->execute();
    $historique_devis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Erreur récupération devis : " . $e->getMessage());
    $historique_devis = [];
}

// Mes commentaires
try {
    $sql = "SELECT id, note, commentaire, approved, DATE_FORMAT(date_creation, '%d/%m/%Y à %H:%i') as date_fr 
            FROM commentaire 
            WHERE email = :email 
            ORDER BY date_creation DESC 
            LIMIT 10";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(":email", $user['mail']);
    $stmt->execute();
    $mes_commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Erreur récupération commentaires : " . $e->getMessage());
    $mes_commentaires = [];
}
?>
<!------------------------------------------------------------------------------>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/favicon" href="./asset/image/OIP.webp">
    <title>Mon espace</title>
</head>
<body>
    <?php
        include "./includes/header.php";
    ?>
    <main>
    <div class="user-dashboard">
            <!-- Section de bienvenue -->
            <div class="welcome-section">
                <h1>👋 Bonjour <?php echo htmlspecialchars($user['prenom']); ?> !</h1>
                <p>Bienvenue dans votre espace personnel</p>
                <p style="font-size: 0.9rem; opacity: 0.9;">
                    Membre depuis le <?php echo date('d/m/Y', strtotime($user['date_creation'])); ?>
                </p>
            </div>

            <!-- Messages flash -->
            <?php if (!empty($flash_success)): ?>
                <div class="alert alert-success" role="alert">
                    <strong>✓</strong> <?= htmlspecialchars($flash_success) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($flash_error)): ?>
                <div class="alert alert-error" role="alert">
                    <strong>⚠</strong> <?= htmlspecialchars($flash_error) ?>
                </div>
            <?php endif; ?>

            <!-- Onglets de navigation -->
            <div class="tabs">
                <button class="tab-button active" data-tab="profil">📋 Mon Profil</button>
                <button class="tab-button" data-tab="historique">📜 Historique</button>
                <button class="tab-button" data-tab="commentaires">💬 Mes Avis</button>
                <button class="tab-button" data-tab="nouveau-avis">⭐ Laisser un avis</button>
            </div>

            <!-- ONGLET 1 : MON PROFIL -->
            <div id="profil" class="tab-content active">
                <div class="card">
                    <h3>Modifier mes informations</h3>
                    <form method="post" action="./user.php">
                        <div class="form-group">
                            <label for="civilite">Civilité *</label>
                            <select id="civilite" name="civilite" required>
                                <option value="M." <?= $user['civilite'] === 'M.' ? 'selected' : '' ?>>M.</option>
                                <option value="Mme" <?= $user['civilite'] === 'Mme' ? 'selected' : '' ?>>Mme</option>
                                <option value="Mx" <?= $user['civilite'] === 'Mx' ? 'selected' : '' ?>>Mx</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" 
                                   value="<?= htmlspecialchars($user['prenom']) ?>" 
                                   required minlength="2" maxlength="50">
                        </div>
                        
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" 
                                   value="<?= htmlspecialchars($user['nom']) ?>" 
                                   required minlength="2" maxlength="50">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['mail']) ?>" 
                                   required>
                        </div>
                        
                        <button type="submit" name="modifier_profil" value="1" class="btn">
                            Enregistrer les modifications
                        </button>
                    </form>
                </div>

                <div class="card">
                    <h3>Changer mon mot de passe</h3>
                    <form method="post" action="./user.php">
                        <div class="form-group">
                            <label for="ancien_mdp">Ancien mot de passe *</label>
                            <input type="password" id="ancien_mdp" name="ancien_mdp" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nouveau_mdp">Nouveau mot de passe * (min. 8 caractères)</label>
                            <input type="password" id="nouveau_mdp" name="nouveau_mdp" 
                                   required minlength="8">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmer_mdp">Confirmer le nouveau mot de passe *</label>
                            <input type="password" id="confirmer_mdp" name="confirmer_mdp" required>
                        </div>
                        
                        <button type="submit" name="changer_mdp" value="1" class="btn">
                            Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>

            <!-- ONGLET 2 : HISTORIQUE -->
            <div id="historique" class="tab-content">
                <div class="card">
                    <h3>📧 Mes demandes de contact (<?= count($historique_contacts) ?>)</h3>
                    <?php if (empty($historique_contacts)): ?>
                        <div class="empty-state">
                            <p>Aucune demande de contact pour le moment</p>
                        </div>
                    <?php else: ?>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Sujet</th>
                                    <th>Message</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique_contacts as $contact): ?>
                                <tr>
                                    <td><?= htmlspecialchars($contact['date_fr']) ?></td>
                                    <td><?= htmlspecialchars($contact['sujet'] ?? 'Sans sujet') ?></td>
                                    <td><?= htmlspecialchars(substr($contact['message'], 0, 50)) ?>...</td>
                                    <td>
                                        <span class="status-badge status-<?= $contact['status'] ?>">
                                            <?php
                                            $statuts = ['new' => 'Nouveau', 'read' => 'Lu', 'closed' => 'Traité'];
                                            echo $statuts[$contact['status']] ?? $contact['status'];
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h3>📄 Mes demandes de devis (<?= count($historique_devis) ?>)</h3>
                    <?php if (empty($historique_devis)): ?>
                        <div class="empty-state">
                            <p>Aucune demande de devis pour le moment</p>
                        </div>
                    <?php else: ?>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Demande</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique_devis as $devis): ?>
                                <tr>
                                    <td><?= htmlspecialchars($devis['date_fr']) ?></td>
                                    <td><?= htmlspecialchars($devis['Professionnels_Particuliers'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars(substr($devis['message'], 0, 50)) ?>...</td>
                                    <td>
                                        <span class="status-badge status-<?= $devis['status'] ?>">
                                            <?php
                                            $statuts = ['new' => 'Nouveau', 'in_progress' => 'En cours', 'closed' => 'Traité'];
                                            echo $statuts[$devis['status']] ?? $devis['status'];
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ONGLET 3 : MES AVIS -->
            <div id="commentaires" class="tab-content">
                <div class="card">
                    <h3>⭐ Mes avis laissés (<?= count($mes_commentaires) ?>)</h3>
                    <?php if (empty($mes_commentaires)): ?>
                        <div class="empty-state">
                            <p>Vous n'avez pas encore laissé d'avis</p>
                            <button class="btn" onclick="switchTab('nouveau-avis')">Laisser un avis</button>
                        </div>
                    <?php else: ?>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mes_commentaires as $com): ?>
                                <tr>
                                    <td><?= htmlspecialchars($com['date_fr']) ?></td>
                                    <td>
                                        <?php for($i=0; $i<$com['note']; $i++): ?>
                                            <span style="color: #ffc107;">★</span>
                                        <?php endfor; ?>
                                        <?php for($i=$com['note']; $i<5; $i++): ?>
                                            <span style="color: #ddd;">★</span>
                                        <?php endfor; ?>
                                    </td>
                                    <td><?= htmlspecialchars(substr($com['commentaire'], 0, 60)) ?>...</td>
                                    <td>
                                        <span class="status-badge <?= $com['approved'] ? 'status-approved' : 'status-pending' ?>">
                                            <?= $com['approved'] ? 'Publié' : 'En attente' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ONGLET 4 : NOUVEAU AVIS -->
            <div id="nouveau-avis" class="tab-content">
                <div class="card">
                    <h3>⭐ Laisser un avis sur nos services</h3>
                    <form method="post" action="./user.php">
                        <div class="form-group">
                            <label>Votre note *</label>
                            <div class="stars-container">
                                <div class="stars" id="starRating">
                                    <span class="star" data-rating="1">★</span>
                                    <span class="star" data-rating="2">★</span>
                                    <span class="star" data-rating="3">★</span>
                                    <span class="star" data-rating="4">★</span>
                                    <span class="star" data-rating="5">★</span>
                                </div>
                                <span id="ratingText" style="margin-left: 15px; color: #666;">
                                    Cliquez sur les étoiles
                                </span>
                            </div>
                            <input type="hidden" id="note-value" name="note" value="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaire">Votre avis * (10-500 caractères)</label>
                            <textarea id="commentaire" name="commentaire" rows="5" 
                                      required minlength="10" maxlength="500"
                                      placeholder="Partagez votre expérience avec nos services..."></textarea>
                            <small style="color: #999;">Caractères restants : <span id="charCount">500</span></small>
                        </div>
                        
                        <button type="submit" name="envoyer_commentaire" value="1" class="btn">
                            Publier mon avis
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php
        include "./includes/footer.php";
    ?>  
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>