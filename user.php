<?php
session_start();
include_once "./includes/connexionbdd.php";
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connexion']) || $_SESSION['connexion'] !== true || !isset($_SESSION['id']))
    {
        $_SESSION['flash_error'] = "Vous devez être connecté pour accéder à cette page.";
        header("Location: ./connexion.php");
        exit;
    }

$user_id = $_SESSION['id'];

// Récupérer les informations de l'utilisateur
try
    {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user)
        {
            session_destroy();
            header("Location: ./connexion.php");
            exit;
        }
    }
catch(PDOException $e)
    {
        error_log("Erreur récupération utilisateur : " . $e->getMessage());
        die("Erreur système");
    }

// ========== TRAITEMENT MODIFICATION PROFIL ==========
if (!empty($_POST["modifier_profil"]))
    {
        $civilite = trim($_POST["civilite"]);
        $prenom = trim(strip_tags($_POST["prenom"]));
        $nom = trim(strip_tags($_POST["nom"]));
        $email = trim(strtolower($_POST["email"]));
        if (strlen($prenom) < 2 || strlen($prenom) > 50)
                $_SESSION['flash_error'] = "Le prénom doit contenir entre 2 et 50 caractères.";
        elseif (strlen($nom) < 2 || strlen($nom) > 50) 
            $_SESSION['flash_error'] = "Le nom doit contenir entre 2 et 50 caractères.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $_SESSION['flash_error'] = "L'adresse email n'est pas valide.";
        else
            {
                try
                    {
                        $sql = "SELECT id FROM users WHERE mail = :email AND id != :user_id";
                        $stmt = $connexion->prepare($sql);
                        $stmt->bindValue(":email", $email);
                        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                        $stmt->execute();
                        if ($stmt->fetch())
                            $_SESSION['flash_error'] = "Cette adresse email est déjà utilisée.";
                        else
                            {
                                $sql = "UPDATE users SET civilite = :civilite, prenom = :prenom, nom = :nom, mail = :email WHERE id = :user_id";
                                $stmt = $connexion->prepare($sql);
                                $stmt->bindValue(":civilite", $civilite);
                                $stmt->bindValue(":prenom", $prenom);
                                $stmt->bindValue(":nom", $nom);
                                $stmt->bindValue(":email", $email);
                                $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                                $stmt->execute();
                                
                                $_SESSION['flash_success'] = "Profil modifié avec succès !";
                                $_SESSION['prenom'] = $prenom;
                                $_SESSION['nom'] = $nom;
                                $_SESSION['email'] = $email;
                                
                                $user['civilite'] = $civilite;
                                $user['prenom'] = $prenom;
                                $user['nom'] = $nom;
                                $user['mail'] = $email;
                            }
                    }
                    catch(PDOException $e)
                    {
                        error_log("Erreur modification profil : " . $e->getMessage());
                        $_SESSION['flash_error'] = "Une erreur est survenue.";
                    }
            }
        header("Location: ./user.php#profil");
        exit;
    }

// ========== TRAITEMENT CHANGEMENT MOT DE PASSE ==========
if (!empty($_POST["changer_mdp"]))
    {
        $ancien_mdp = $_POST["ancien_mdp"];
        $nouveau_mdp = $_POST["nouveau_mdp"];
        $confirmer_mdp = $_POST["confirmer_mdp"];
        if (!password_verify($ancien_mdp, $user['mdp'])) 
            $_SESSION['flash_error'] = "L'ancien mot de passe est incorrect.";
        elseif (strlen($nouveau_mdp) < 8) 
            $_SESSION['flash_error'] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        elseif ($nouveau_mdp !== $confirmer_mdp) 
            $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
        else
            {
                try
                    {
                        $mdp_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
                        $sql = "UPDATE users SET mdp = :mdp WHERE id = :user_id";
                        $stmt = $connexion->prepare($sql);
                        $stmt->bindValue(":mdp", $mdp_hash);
                        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                        $stmt->execute();
                        
                        $_SESSION['flash_success'] = "Mot de passe modifié avec succès !";
                    } 
                catch(PDOException $e)
                    {
                        error_log("Erreur changement mot de passe : " . $e->getMessage());
                        $_SESSION['flash_error'] = "Une erreur est survenue.";
                    }
            }
        header("Location: ./user.php#profil");
        exit;
    }

// ========== TRAITEMENT COMMENTAIRE ==========
if (!empty($_POST["envoyer_commentaire"]))
    {
        $note = intval($_POST["note"]);
        $commentaire = htmlspecialchars(trim($_POST["commentaire"]));
        
        if ($note < 1 || $note > 5) 
            $_SESSION['flash_error'] = "La note doit être entre 1 et 5 étoiles.";
        elseif (strlen($commentaire) < 10 || strlen($commentaire) > 500)
            $_SESSION['flash_error'] = "Le commentaire doit contenir entre 10 et 500 caractères.";
        else 
            {
                try
                    {
                        $sql = "INSERT INTO commentaire (pseudo, email, note, commentaire, approved, date_creation) 
                                VALUES (:pseudo, :email, :note, :commentaire, 0, NOW())";
                        $stmt = $connexion->prepare($sql);
                        $stmt->bindValue(":pseudo", $user['prenom'] . ' ' . $user['nom']);
                        $stmt->bindValue(":email", $user['mail']);
                        $stmt->bindValue(":note", $note, PDO::PARAM_INT);
                        $stmt->bindValue(":commentaire", $commentaire);
                        $stmt->execute();
                        
                        $_SESSION['flash_success'] = "Merci pour votre avis ! Il sera visible après modération.";
                    } 
                    catch(PDOException $e) 
                    {
                        error_log("Erreur insertion commentaire : " . $e->getMessage());
                        $_SESSION['flash_error'] = "Une erreur est survenue.";
                    }
            }
        header("Location: ./user.php#nouveau-avis");
        exit;
    }

// ========== TRAITEMENT DEMANDE DE DEVIS ==========
if (!empty($_POST["envoyer_devis"])) 
    {
        $type_client = trim($_POST["type_client"]);
        $contact_name = htmlspecialchars(trim($_POST["contact_name"]));
        $email = trim($_POST["email"]);
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $message_devis = htmlspecialchars(trim($_POST["message_devis"]));
        
        // Validation
        if (empty($type_client) || !in_array($type_client, ['Particulier', 'Professionnel', 'Domotique'])) 
            $_SESSION['flash_error'] = "Veuillez sélectionner un type de client.";
        elseif (strlen($contact_name) < 2 || strlen($contact_name) > 200) 
            $_SESSION['flash_error'] = "Le nom doit contenir entre 2 et 200 caractères.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            $_SESSION['flash_error'] = "L'adresse email n'est pas valide.";
        elseif (!empty($phone) && strlen($phone) > 50) 
            $_SESSION['flash_error'] = "Le numéro de téléphone est trop long.";
        elseif (strlen($message_devis) < 20 || strlen($message_devis) > 1000) 
            $_SESSION['flash_error'] = "Le message doit contenir entre 20 et 1000 caractères.";
        else 
            {
                try 
                    {
                        $sql = "INSERT INTO requete_devis 
                                (Professionnels_Particuliers, contact_name, email, phone, message, status, date_creation) 
                                VALUES (:type_client, :contact_name, :email, :phone, :message, 'new', NOW())";
                        
                        $stmt = $connexion->prepare($sql);
                        $stmt->bindValue(":type_client", $type_client);
                        $stmt->bindValue(":contact_name", $contact_name);
                        $stmt->bindValue(":email", $email);
                        $stmt->bindValue(":phone", $phone);
                        $stmt->bindValue(":message", $message_devis);
                        $stmt->execute();
                        
                        $_SESSION['flash_success'] = "✓ Votre demande de devis a été envoyée ! Nous vous contacterons rapidement.";
                    } 
                catch(PDOException $e) 
                    {
                        error_log("Erreur insertion devis : " . $e->getMessage());
                        $_SESSION['flash_error'] = "Une erreur est survenue lors de l'envoi.";
                    }
            }
        header("Location: ./user.php#demander-devis");
        exit;
    }

// ========== TRAITEMENT SUPPRESSION COMPTE ==========
if (!empty($_POST["supprimer_compte"]))
    {
        $confirmation = $_POST["confirmation"] ?? '';
        
        if ($confirmation === 'SUPPRIMER') 
            {
                try 
                    {
                        // Supprimer les données associées (optionnel)
                        $connexion->prepare("DELETE FROM commentaire WHERE email = ?")->execute([$user['mail']]);
                        $connexion->prepare("DELETE FROM contact WHERE email = ?")->execute([$user['mail']]);
                        
                        // Supprimer le compte
                        $stmt = $connexion->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$user_id]);
                        
                        // Détruire la session
                        session_destroy();
                        
                        header("Location: ./index.php");
                        exit;
                    } 
                catch(PDOException $e) 
                    {
                        error_log("Erreur suppression compte : " . $e->getMessage());
                        $_SESSION['flash_error'] = "Erreur lors de la suppression du compte.";
                    }
            } 
        else 
            $_SESSION['flash_error'] = "Confirmation incorrecte. Tapez 'SUPPRIMER' pour confirmer.";     
        header("Location: ./user.php#profil");
        exit;
    }

// Récupérer les messages flash
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// ========== RÉCUPÉRATION HISTORIQUE ==========
try
    {
        $sql = "SELECT id, sujet, message, status, DATE_FORMAT(date_creation, '%d/%m/%Y à %H:%i') as date_fr 
                FROM contact 
                WHERE email = :email 
                ORDER BY date_creation DESC 
                LIMIT 20";
        $stmt = $connexion->prepare($sql);
        $stmt->bindValue(":email", $user['mail']);
        $stmt->execute();
        $historique_contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
catch(PDOException $e)
    {
        error_log("Erreur récupération contacts : " . $e->getMessage());
        $historique_contacts = [];
    }

try
    {
        $sql = "SELECT id, Professionnels_Particuliers, message, status, DATE_FORMAT(date_creation, '%d/%m/%Y à %H:%i') as date_fr 
                FROM requete_devis 
                WHERE email = :email 
                ORDER BY date_creation DESC 
                LIMIT 20";
        $stmt = $connexion->prepare($sql);
        $stmt->bindValue(":email", $user['mail']);
        $stmt->execute();
        $historique_devis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
catch(PDOException $e)
    {
        error_log("Erreur récupération devis : " . $e->getMessage());
        $historique_devis = [];
    }

try {
    $sql = "SELECT id, note, commentaire, approved, DATE_FORMAT(date_creation, '%d/%m/%Y à %H:%i') as date_fr 
            FROM commentaire 
            WHERE email = :email 
            ORDER BY date_creation DESC 
            LIMIT 20";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(":email", $user['mail']);
    $stmt->execute();
    $mes_commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Erreur récupération commentaires : " . $e->getMessage());
    $mes_commentaires = [];
}

// Statistiques utilisateur
$stats = [
    'total_contacts' => count($historique_contacts),
    'total_devis' => count($historique_devis),
    'total_commentaires' => count($mes_commentaires),
    'commentaires_publies' => count(array_filter($mes_commentaires, fn($c) => $c['approved'] == 1)),
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Espace utilisateur - Gérez votre profil, consultez votre historique et laissez des avis.">
    <meta name="keywords" content="espace client, profil utilisateur, historique">
    <meta name="author" content="SECIC - Thierry Decramp">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="./asset/css/style2.css">
    <!-- Styles user -->
    <!-- <link rel="stylesheet" href="./asset/css/user.css"> -->
    
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Mon espace - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="user-container">
            <!-- Header utilisateur -->
            <div class="user-header">
                <div class="user-info">
                    <div>
                        <h1>Bonjour <?php echo htmlspecialchars($user['prenom']); ?> !</h1>
                        <p>Bienvenue dans votre espace personnel</p>
                        <p class="user-meta">
                            Membre depuis le <?php echo date('d/m/Y', strtotime($user['date_creation'])); ?>
                            <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                                <span class="badge-admin">👑 Admin</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="header-actions">
                    <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                        <a href="./admin.php" class="btn btn-admin">Panneau admin</a>
                    <?php endif; ?>
                    <a href="./deconnexion.php" class="btn btn-logout">Déconnexion</a>
                </div>
            </div>
            <br>

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

            <!-- Statistiques utilisateur -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📧</div>
                    <div class="stat-info">
                        <div class="stat-number"><?= $stats['total_contacts'] ?></div>
                        <div class="stat-label">Messages envoyés</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-info">
                        <div class="stat-number"><?= $stats['total_devis'] ?></div>
                        <div class="stat-label">Demandes de devis</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-info">
                        <div class="stat-number">
                            
                            <small><?= $stats['commentaires_publies'] ?> publiés</small>
                        </div>
                        <div class="stat-label">Avis laissés</div>
                    </div>
                </div>
            </div>

            <!-- Onglets -->
            <div class="tabs">
                <button class="tab-button active" data-tab="profil">Mon Profil</button>
                <button class="tab-button" data-tab="demander-devis">Demander un devis</button>
                <button class="tab-button" data-tab="commentaires">Mes Avis</button>
                <button class="tab-button" data-tab="nouveau-avis">Laisser un avis</button>
            </div>

            <!-- ONGLET PROFIL -->
            <div id="profil" class="tab-content active">
                <div class="card">
                    <h3>Modifier mes informations</h3>
                    <form method="post" action="./user.php" class="user-form">
                        <div class="form-row">
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
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom *</label>
                                <input type="text" id="nom" name="nom" 
                                       value="<?= htmlspecialchars($user['nom']) ?>" 
                                       required minlength="2" maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="profil_email" name="email" 
                                       value="<?= htmlspecialchars($user['mail']) ?>" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="modifier_profil" value="1" class="btn btn-primary">
                                💾 Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <h3>Changer mon mot de passe</h3>
                    <form method="post" action="./user.php" class="user-form">
                        <div class="form-group">
                            <label for="ancien_mdp">Ancien mot de passe *</label>
                            <input type="password" id="ancien_mdp" name="ancien_mdp" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nouveau_mdp">Nouveau mot de passe * (min. 8 caractères)</label>
                                <input type="password" id="nouveau_mdp" name="nouveau_mdp" 
                                       required minlength="8">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmer_mdp">Confirmer le nouveau mot de passe *</label>
                                <input type="password" id="confirmer_mdp" name="confirmer_mdp" required>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="changer_mdp" value="1" class="btn btn-primary">
                                🔐 Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card danger-zone">
                    <h3>Zone dangereuse</h3>
                    <p><strong>Attention :</strong> La suppression de votre compte est définitive et irréversible.</p>
                    <!-- <button class="btn btn-danger" onclick="showDeleteModal()">
                        🗑️ Supprimer mon compte
                    </button> -->
                    <form method="post" action="./user.php">
                        <input type="text" name="confirmation" placeholder="Tapez SUPPRIMER" required>

                        <button type="submit" name="supprimer_compte" value="1" class="btn btn-danger">
                            Supprimer mon compte
                        </button>
                    </form>
                </div>
            </div>

            <!-- ONGLET DEMANDER UN DEVIS -->
            <div id="demander-devis" class="tab-content">
                <div class="card">
                    <h3>Demander un devis personnalisé</h3>
                    <p class="form-intro">
                        Remplissez ce formulaire pour obtenir un devis adapté à vos besoins. 
                        Notre équipe vous répondra dans les plus brefs délais.
                    </p>
                    
                    <form method="post" action="./user.php" class="user-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="type_client">Type de client *</label>
                                <select id="type_client" name="type_client" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Particulier">Particulier</option>
                                    <option value="Professionnel">Professionnel</option>
                                    <option value="Domotique">Domotique</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_name">Nom complet *</label>
                                <input type="text" 
                                    id="contact_name" 
                                    name="contact_name" 
                                    value="<?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>"
                                    required 
                                    minlength="2" 
                                    maxlength="200"
                                    placeholder="Prénom Nom">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email de contact *</label>
                                <input type="email" 
                                    id="email" 
                                    name="email" 
                                    value="<?= htmlspecialchars($user['mail']) ?>"
                                    required
                                    placeholder="votre@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Téléphone (optionnel)</label>
                                <input type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    maxlength="50"
                                    placeholder="06 12 34 56 78">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message_devis">Décrivez votre projet * (20-1000 caractères)</label>
                            <textarea id="message_devis" 
                                    name="message_devis" 
                                    rows="8" 
                                    required 
                                    minlength="20" 
                                    maxlength="1000"
                                    placeholder="Décrivez votre projet en détail : type de travaux, surface, délais souhaités, budget indicatif, etc."></textarea>
                            <div class="char-counter">
                                Caractères : <span id="charCountDevis">0</span> / 1000
                            </div>
                        </div>
                
                        <div class="form-actions">
                            <button type="submit" name="envoyer_devis" value="1" class="btn btn-primary">
                                📤 Envoyer ma demande
                            </button>
                        </div>
                    </form>

                    <div class="card">
                    <h3>Mes demandes de devis (<?= count($historique_devis) ?>)</h3>
                    <?php if (empty($historique_devis)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">📋</div>
                            <p>Aucune demande de devis pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="user-table">
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
                                        <td><span class="badge-type"><?= htmlspecialchars($devis['Professionnels_Particuliers'] ?? 'N/A') ?></span></td>
                                        <td class="text-truncate"><?= htmlspecialchars(substr($devis['message'] ?? '', 0, 60)) ?>...</td>
                                        <td>
                                            <span class="status-badge status-<?= $devis['status'] ?>">
                                                <?php
                                                $statuts = ['new' => '🆕 Nouveau', 'in_progress' => '🔄 En cours', 'closed' => '✅ Traité'];
                                                echo $statuts[$devis['status']] ?? $devis['status'];
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                </div>
                </div>
            </div>
        <!-- </div> -->

            <!-- ONGLET MES AVIS -->
            <div id="commentaires" class="tab-content">
                <div class="card">
                    <h3>Mes avis laissés (<?= count($mes_commentaires) ?>)</h3>
                    <?php if (empty($mes_commentaires)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">💭</div>
                            <p>Vous n'avez pas encore laissé d'avis</p>
                            <button class="btn" onclick="switchTab('nouveau-avis')">Laisser un avis</button>
                        </div>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($mes_commentaires as $com): ?>
                            <div class="comment-item <?= $com['approved'] ? 'comment-approved' : 'comment-pending' ?>">
                                <div class="comment-header">
                                    <div class="comment-stars">
                                        <?php for($i=0; $i<5; $i++): ?>
                                            <span class="star-display <?= $i < $com['note'] ? 'filled' : '' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="comment-meta">
                                        <?= htmlspecialchars($com['date_fr']) ?>
                                    </div>
                                </div>
                                <div class="comment-text">
                                    <?= htmlspecialchars($com['commentaire']) ?>
                                </div>
                                <div class="comment-status">
                                    <?php if ($com['approved'] == 1): ?>
                                        <span class="status-badge status-published">✓ Publié</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">⏳ En attente de modération</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ONGLET NOUVEAU AVIS -->
            <div id="nouveau-avis" class="tab-content">
                <div class="card">
                    <h3>Laisser un avis sur nos services</h3>
                    <form id="commentForm" method="post" action="./user.php" class="user-form">
                        <div class="form-group">
                            <label>Votre note *</label>
                            <div class="stars-container">
                                <div class="stars-input" id="starRating">
                                    <span class="star" data-rating="1">★</span>
                                    <span class="star" data-rating="2">★</span>
                                    <span class="star" data-rating="3">★</span>
                                    <span class="star" data-rating="4">★</span>
                                    <span class="star" data-rating="5">★</span>
                                </div>
                                <span class="rating-text" id="ratingText">Cliquez sur les étoiles pour noter</span>
                            </div>
                            <input type="hidden" id="note-value" name="note" value="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaire">Votre avis * (10-500 caractères)</label>
                            <textarea id="commentaire" name="commentaire" rows="6" 
                                      required minlength="10" maxlength="500"
                                      placeholder="Partagez votre expérience avec nos services..."></textarea>
                            <div class="char-counter">
                                Caractères : <span id="charCount">0</span> / 500
                            </div>
                        </div>    
                        <div class="form-actions">
                            <button type="submit" name="envoyer_commentaire" value="1" class="btn btn-primary">
                                Publier mon avis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal suppression compte -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
            <h3>Supprimer mon compte</h3>
            <p><strong>Attention :</strong> Cette action est irréversible !</p>
            <p>Toutes vos données seront définitivement supprimées :</p>
            <ul>
                <li>Vos informations personnelles</li>
                <li>Vos commentaires</li>
                <li>Votre historique de messages</li>
            </ul>
            <form method="post" action="./user.php">
                <div class="form-group">
                    <label for="confirmation">Tapez <strong>SUPPRIMER</strong> pour confirmer :</label>
                    <input type="text" id="confirmation" name="confirmation" 
                           placeholder="SUPPRIMER" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                    <button type="submit" name="supprimer_compte" value="1" class="btn btn-danger">
                        Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>



    <?php include "./includes/footer.php"; ?>

    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>