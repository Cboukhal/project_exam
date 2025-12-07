<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');
define('UPLOAD_DIR', './asset/image/galerie/');
//??
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

include_once "./includes/connexionbdd.php";
include_once "./includes/fonctions.php";

if (!isset($connexion))
{
    //??
    die("Erreur: connexion à la BDD introuvable.");
}

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['connexion']) || $_SESSION['connexion'] !== true || 
    !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
{
    $_SESSION['flash_error'] = "Accès réservé aux administrateurs.";
    header("Location: ./connexion.php");
    exit;
}

// ============================== GESTION DES PARTENAIRES ==============================
if (isset($_POST['ajouter_partenaire']))
{
    $nom = trim($_POST['nom'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // validations simples
    if ($nom === '' || $url === '')
    {
        $_SESSION['flash_error'] = "Le nom et l'URL sont requis.";
    }
    //Filter_var??
    elseif (!filter_var($url, FILTER_VALIDATE_URL)) 
    {
        $_SESSION['flash_error'] = "L'URL fournie n'est pas valide.";
    } 
    else 
    {
        try 
        {
            $stmt = $connexion->prepare("INSERT INTO partenaire (nom, `url`, `description`) VALUES (:nom, :url, :description)");
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description ?: null, $description === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->execute();
            $_SESSION['flash_success'] = "Partenaire ajouté avec succès.";
        } 
        catch (PDOException $e) 
        {
            $_SESSION['flash_error'] = "Erreur lors de l'ajout du partenaire.";
            error_log("Erreur ajout partenaire : " . $e->getMessage());
        }
    }
    header("Location: admin.php#partenaires");
    exit;
}

if (isset($_GET['delete_partenaire']))
{
    //??
    $id = (int)$_GET['delete_partenaire'];
    try
    {
        $stmt = $connexion->prepare("DELETE FROM partenaire WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash_success'] = "Partenaire supprimé.";
    }
    catch (PDOException $e)
    {
        $_SESSION['flash_error'] = "Erreur lors de la suppression du partenaire.";
        error_log("Erreur suppression partenaire : " . $e->getMessage());
    }
    header("Location: admin.php#partenaires");
    exit;
}

// ============================== GESTION DES COMMENTAIRES ==============================
if (isset($_GET['approve_comment']))
{
    $id = (int)$_GET['approve_comment'];
    try
    {
        $stmt = $connexion->prepare("UPDATE commentaire SET approved = 1 WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash_success'] = "Commentaire approuvé et publié.";
    }
    catch(PDOException $e)
    {
        $_SESSION['flash_error'] = "Erreur lors de l'approbation.";
        error_log("Erreur approbation commentaire : " . $e->getMessage());
    }
    header("Location: admin.php#commentaires");
    exit;
}

if (isset($_GET['reject_comment'])) {
    $id = (int)$_GET['reject_comment'];
    try
    {
        $stmt = $connexion->prepare("UPDATE commentaire SET approved = -1 WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash_success'] = "Commentaire rejeté.";
    }
    catch(PDOException $e)
    {
        $_SESSION['flash_error'] = "Erreur lors du rejet.";
        error_log("Erreur rejet commentaire : " . $e->getMessage());
    }
    header("Location: admin.php#commentaires");
    exit;
}

if (isset($_GET['delete_comment']))
{
    $id = (int)$_GET['delete_comment'];
    try
    {
        $stmt = $connexion->prepare("DELETE FROM commentaire WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash_success'] = "Commentaire supprimé définitivement.";
    }
    catch(PDOException $e)
    {
        $_SESSION['flash_error'] = "Erreur lors de la suppression.";
        error_log("Erreur suppression commentaire : " . $e->getMessage());
    }
    header("Location: admin.php#commentaires");
    exit;
}

// ============================== GESTION DES GALERIES ==============================
if (isset($_POST['upload_image']))
{
    $legende = trim($_POST['legende'] ?? '');
    $image_type = in_array($_POST['image_type'] ?? '', ['particulier','professionnel','domotique']) 
                  ? $_POST['image_type'] : 'particulier';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
    {
        //type MIME
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        
        if (!in_array($file_type, $allowed_types))
            $_SESSION['flash_error'] = "Type de fichier non autorisé. Utilisez JPG, PNG ou WebP.";
        elseif ($file_size > MAX_FILE_SIZE)
            $_SESSION['flash_error'] = "Fichier trop volumineux (max 5 MB).";
        else
        {
            //permet d'éviter les doublons
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_', true) . '.' . strtolower($extension);
            
            if (!is_dir(UPLOAD_DIR))
                //si le dossier n'existe pas, on le crée
                mkdir(UPLOAD_DIR, 0755, true);

            //L’image est déplacée du dossier temporaire vers le dossier final.
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename))
            {
                try
                {
                    // insertion : filename, legende, image_type, mime_type, file_size
                    $stmt = $connexion->prepare("INSERT INTO galeries (filename, mime_type, file_size, legende, image_type) 
                                                 VALUES (:filename, :mime_type, :file_size, :legende, :image_type)");
                    $stmt->bindValue(':filename', $filename, PDO::PARAM_STR);
                    $stmt->bindValue(':mime_type', $file_type, PDO::PARAM_STR);
                    $stmt->bindValue(':file_size', $file_size, PDO::PARAM_INT);
                    $stmt->bindValue(':legende', $legende ?: null, $legende === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
                    $stmt->bindValue(':image_type', $image_type, PDO::PARAM_STR);
                    $stmt->execute();
                    $_SESSION['flash_success'] = "Image uploadée avec succès.";
                }
                catch(PDOException $e)
                {
                    $_SESSION['flash_error'] = "Erreur lors de l'enregistrement.";
                    error_log("Erreur upload image : " . $e->getMessage());
                }
            } 
            else 
                $_SESSION['flash_error'] = "Erreur lors de l'upload du fichier.";
        }
    }
    else
        $_SESSION['flash_error'] = "Aucun fichier sélectionné ou erreur d'upload.";
    header("Location: admin.php#galeries");
    exit;
}

if (isset($_GET['delete_image']))
{
    $id = (int)$_GET['delete_image'];
    try
    {
        $stmt = $connexion->prepare("SELECT filename FROM galeries WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            $filepath = UPLOAD_DIR . $image['filename'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            $stmt = $connexion->prepare("DELETE FROM galeries WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_success'] = "Image supprimée avec succès.";
        }
    }
    catch(PDOException $e)
    {
        $_SESSION['flash_error'] = "Erreur lors de la suppression.";
        error_log("Erreur suppression image : " . $e->getMessage());
    }
    header("Location: admin.php#galeries");
    exit;
}

// ============================== GESTION DES CONTACTS ==============================
if (isset($_GET['update_contact_status']))
{
    $id = (int)$_GET['update_contact_status'];
    $status = $_GET['status'] ?? 'read';
    
    if (in_array($status, ['new', 'read', 'closed'])) {
        try {
            $stmt = $connexion->prepare("UPDATE contact SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $_SESSION['flash_success'] = "Statut mis à jour.";
        } catch(PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la mise à jour.";
            error_log("Erreur update contact : " . $e->getMessage());
        }
    }
    header("Location: admin.php#contacts");
    exit;
}

if (isset($_GET['delete_contact'])) {
    $id = (int)$_GET['delete_contact'];
    try {
        $stmt = $connexion->prepare("DELETE FROM contact WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash_success'] = "Contact supprimé.";
    } catch(PDOException $e) {
        $_SESSION['flash_error'] = "Erreur lors de la suppression.";
        error_log("Erreur suppression contact : " . $e->getMessage());
    }
    header("Location: admin.php#contacts");
    exit;
}

// ========== GESTION DES DEVIS ==========
if (isset($_GET['update_devis_status'])) {
    $id = (int)$_GET['update_devis_status'];
    $status = $_GET['status'] ?? 'in_progress';
    
    if (in_array($status, ['new', 'in_progress', 'closed'])) {
        try {
            $stmt = $connexion->prepare("UPDATE requete_devis SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $_SESSION['flash_success'] = "Statut mis à jour.";
        } catch(PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la mise à jour.";
            error_log("Erreur update devis : " . $e->getMessage());
        }
    }
    header("Location: admin.php#devis");
    exit;
}

if (isset($_GET['delete_devis'])) {
    $id = (int)$_GET['delete_devis'];
    try {
        $stmt = $connexion->prepare("DELETE FROM requete_devis WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash_success'] = "Demande de devis supprimée.";
    } catch(PDOException $e) {
        $_SESSION['flash_error'] = "Erreur lors de la suppression.";
        error_log("Erreur suppression devis : " . $e->getMessage());
    }
    header("Location: admin.php#devis");
    exit;
}

// ========== GESTION DES UTILISATEURS ==========
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    
    // Empêcher la suppression de soi-même
    if ($id === $_SESSION['id']) {
        $_SESSION['flash_error'] = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        try {
            $stmt = $connexion->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_success'] = "Utilisateur supprimé.";
        } catch(PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la suppression.";
            error_log("Erreur suppression user : " . $e->getMessage());
        }
    }
    header("Location: admin.php#utilisateurs");
    exit;
}

// ========== RÉCUPÉRATION DES DONNÉES ==========
$stmt = $connexion->prepare("SELECT prenom, nom FROM users WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$partenaires = $connexion->query("SELECT * FROM partenaire ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
// $services = $connexion->query("SELECT * FROM services ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
$commentaires = $connexion->query("SELECT * FROM commentaire ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
$galeries = $connexion->query("SELECT * FROM galeries ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
$contacts = $connexion->query("SELECT * FROM contact ORDER BY date_creation DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
$devis = $connexion->query("SELECT * FROM requete_devis ORDER BY date_creation DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
$users = $connexion->query("SELECT * FROM users ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);

// Statistiques
$stats = [
    // 'total_services' => $connexion->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'total_users' => $connexion->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_commentaires' => $connexion->query("SELECT COUNT(*) FROM commentaire")->fetchColumn(),
    'commentaires_attente' => $connexion->query("SELECT COUNT(*) FROM commentaire WHERE approved = 0")->fetchColumn(),
    'total_contacts' => $connexion->query("SELECT COUNT(*) FROM contact")->fetchColumn(),
    'contacts_nouveaux' => $connexion->query("SELECT COUNT(*) FROM contact WHERE status = 'new'")->fetchColumn(),
    'total_devis' => $connexion->query("SELECT COUNT(*) FROM requete_devis")->fetchColumn(),
    'devis_nouveaux' => $connexion->query("SELECT COUNT(*) FROM requete_devis WHERE status = 'new'")->fetchColumn(),
    'total_galeries' => $connexion->query("SELECT COUNT(*) FROM galeries")->fetchColumn(),
];

$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panneau d'administration - Gestion du site">
    <meta name="author" content="SECIC - Thierry Decramp">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="./asset/css/style2.css">
    <!-- Styles admin -->
    <link rel="stylesheet" href="./asset/css/admin.css">
    
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Administration - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="admin-container">
            <!-- Header -->
            <div class="admin-header">
                <div>
                    <h1>Panneau d'administration</h1>
                    <p>Bienvenue <?php echo htmlspecialchars($admin['prenom'] . ' ' . $admin['nom']); ?></p>
                </div>
                <div class="header-actions">
                    <a href="./user.php" class="btn btn-secondary">Mon profil</a>
                    <a href="./deconnexion.php" class="btn btn-logout">Déconnexion</a>
                </div>
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

            <!-- Statistiques -->
            <div class="stats-grid"> 
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-info">
                        <div class="stat-number">
                            <?= $stats['total_commentaires'] ?>
                            <?php if ($stats['commentaires_attente'] > 0): ?>
                                <span class="badge badge-warning"><?= $stats['commentaires_attente'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="stat-label">Commentaires</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📧</div>
                    <div class="stat-info">
                        <div class="stat-number">
                            <?= $stats['total_contacts'] ?>
                            <?php if ($stats['contacts_nouveaux'] > 0): ?>
                                <span class="badge badge-danger"><?= $stats['contacts_nouveaux'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="stat-label">Messages</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-info">
                        <div class="stat-number">
                            <?= $stats['total_devis'] ?>
                            <?php if ($stats['devis_nouveaux'] > 0): ?>
                                <span class="badge badge-danger"><?= $stats['devis_nouveaux'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="stat-label">Devis</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🖼️</div>
                    <div class="stat-info">
                        <div class="stat-number"><?= $stats['total_galeries'] ?></div>
                        <div class="stat-label">Images</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <div class="stat-number"><?= $stats['total_users'] ?></div>
                        <div class="stat-label">Utilisateurs</div>
                    </div>
                </div>
            </div>

            <!-- Onglets -->
            <div class="tabs">
                <button class="tab-button active" data-tab="dashboard">Dashboard</button>
                <button class="tab-button" data-tab="commentaires">
                    Commentaires
                    <?php if ($stats['commentaires_attente'] > 0): ?>
                        <span class="tab-badge"><?= $stats['commentaires_attente'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="tab-button" data-tab="galeries">Galeries</button>
                <button class="tab-button" data-tab="partenaires">Partenaires</button>
                <button class="tab-button" data-tab="contacts">
                    Contacts
                    <?php if ($stats['contacts_nouveaux'] > 0): ?>
                        <span class="tab-badge"><?= $stats['contacts_nouveaux'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="tab-button" data-tab="devis">
                    Devis
                    <?php if ($stats['devis_nouveaux'] > 0): ?>
                        <span class="tab-badge"><?= $stats['devis_nouveaux'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="tab-button" data-tab="utilisateurs">Utilisateurs</button>
            </div>

            <!-- ONGLET DASHBOARD -->
            <div id="dashboard" class="tab-content active">
                <div class="dashboard-grid">
                    <div class="card">
                        <h3>Activité récente</h3>
                        <div class="activity-list">
                            <?php
                            $recent_comments = $connexion->query("SELECT pseudo, DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i') as date FROM commentaire ORDER BY date_creation DESC LIMIT 5")->fetchAll();
                            foreach ($recent_comments as $comment): ?>
                                <div class="activity-item">
                                    <span class="activity-icon">💬</span>
                                    <span class="activity-text">
                                        <strong><?= htmlspecialchars($comment['pseudo']) ?></strong> a laissé un commentaire
                                    </span>
                                    <span class="activity-time"><?= $comment['date'] ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="card">
                        <h3>Notifications</h3>
                        <div class="notification-list">
                            <?php if ($stats['commentaires_attente'] > 0): ?>
                                <div class="notification-item warning">
                                    <span class="notification-icon">⚠️</span>
                                    <span><?= $stats['commentaires_attente'] ?> commentaire(s) en attente de modération</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($stats['contacts_nouveaux'] > 0): ?>
                                <div class="notification-item info">
                                    <span class="notification-icon">📬</span>
                                    <span><?= $stats['contacts_nouveaux'] ?> nouveau(x) message(s) non lu(s)</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($stats['devis_nouveaux'] > 0): ?>
                                <div class="notification-item info">
                                    <span class="notification-icon">📄</span>
                                    <span><?= $stats['devis_nouveaux'] ?> nouvelle(s) demande(s) de devis</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($stats['commentaires_attente'] == 0 && $stats['contacts_nouveaux'] == 0 && $stats['devis_nouveaux'] == 0): ?>
                                <div class="notification-item success">
                                    <span class="notification-icon">✅</span>
                                    <span>Aucune notification en attente</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ONGLET COMMENTAIRES -->
            <div id="commentaires" class="tab-content">
                <div class="card">
                    <h3>Gestion des commentaires (<?= count($commentaires) ?>)</h3>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pseudo</th>
                                    <th>Email</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($commentaires as $comment): ?>
                                <tr class="<?= $comment['approved'] == 0 ? 'row-pending' : '' ?>">
                                    <td><?= $comment['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($comment['pseudo']) ?></strong></td>
                                    <td><?= htmlspecialchars($comment['email'] ?? 'N/A') ?></td>
                                    <td>
                                        <div class="stars-display">
                                            <?php for($i=0; $i<5; $i++): ?>
                                                <span class="star <?= $i < $comment['note'] ? 'active' : '' ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="comment-text"><?= htmlspecialchars(substr($comment['commentaire'], 0, 80)) ?>...</td>
                                    <td><?= date('d/m/Y H:i', strtotime($comment['date_creation'])) ?></td>
                                    <td>
                                        <?php if ($comment['approved'] == 1): ?>
                                            <span class="badge badge-success">✓ Publié</span>
                                        <?php elseif ($comment['approved'] == -1): ?>
                                            <span class="badge badge-danger">✗ Rejeté</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">⏳ En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($comment['approved'] != 1): ?>
                                                <a href="?approve_comment=<?= $comment['id'] ?>" 
                                                   class="btn-icon btn-success" 
                                                   title="Approuver">
                                                    ✓
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($comment['approved'] != -1): ?>
                                                <a href="?reject_comment=<?= $comment['id'] ?>" 
                                                   class="btn-icon btn-warning" 
                                                   title="Rejeter">
                                                    ✗
                                                </a>
                                            <?php endif; ?>
                                            <a href="?delete_comment=<?= $comment['id'] ?>" 
                                               class="btn-icon btn-delete" 
                                               onclick="return confirm('Supprimer définitivement ce commentaire ?')"
                                               title="Supprimer">
                                                🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ONGLET GALERIES -->
            <div id="galeries" class="tab-content">
                <div class="card">
                    <h3>Uploader une nouvelle image</h3>
                    <form method="POST" enctype="multipart/form-data" class="admin-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="image">Image * (JPG, PNG, WebP - Max 5 MB)</label>
                                <input type="file" id="image" name="image" accept="image/*" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="image_type">Type d'image *</label>
                                <select id="image_type" name="image_type" required>
                                    <option value="particulier">Particulier</option>
                                    <option value="professionnel">Professionnel</option>
                                    <option value="domotique">Domotique</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="legende">Légende / Description</label>
                            <input type="text" id="legende" name="legende" placeholder="Description de l'image...">
                        </div>
                        
                        <button type="submit" name="upload_image" value="1" class="btn btn-primary">
                            📤 Uploader l'image
                        </button>
                    </form>
                </div>

                <div class="card">
                    <h3>Galerie d'images (<?= count($galeries) ?>)</h3>
                    <div class="gallery-grid">
                        <?php foreach ($galeries as $img): ?>
                        <div class="gallery-item">
                            <img src="<?= UPLOAD_DIR . htmlspecialchars($img['filename']) ?>" 
                                 alt="<?= htmlspecialchars($img['legende'] ?? 'Image') ?>">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <strong><?= htmlspecialchars($img['legende'] ?? 'Sans légende') ?></strong>
                                    <br><small>🔖 <?= htmlspecialchars(ucfirst($img['image_type'] ?? 'particulier')) ?></small>
                                    <br><small>📅 <?= date('d/m/Y', strtotime($img['date_creation'])) ?></small>
                                </div>
                            </div>
                            <button class="gallery-delete-btn" 
                                    onclick="if(confirm('Supprimer cette image ?')) location.href='?delete_image=<?= $img['id'] ?>'"
                                    title="Supprimer">
                                ×
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ONGLET PARTENAIRES -->
<div id="partenaires" class="tab-content">
    <div class="card">
        <h3>Gestion des partenaires (<?= count($partenaires) ?>)</h3>

        <!-- Formulaire ajout -->
        <form method="POST" class="admin-form" style="margin-bottom:16px;">
            <h4>Ajouter un partenaire</h4>
            <div class="form-row">
                <div class="form-group">
                    <label for="part_nom">Nom *</label>
                    <input type="text" id="part_nom" name="nom" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="part_url">URL *</label>
                    <input type="url" id="part_url" name="url" required maxlength="255" placeholder="https://example.com">
                </div>
            </div>
            <div class="form-group">
                <label for="part_description">Description</label>
                <input type="text" id="part_description" name="description" maxlength="255" placeholder="Brève description (optionnel)">
            </div>
            <div class="form-actions">
                <button type="submit" name="ajouter_partenaire" value="1" class="btn btn-primary">Ajouter</button>
            </div>
        </form>

        <!-- Liste -->
        <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>URL</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($partenaires as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><strong><?= htmlspecialchars($p['nom']) ?></strong></td>
                                <td><a href="<?= htmlspecialchars($p['url']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($p['url']) ?></a></td>
                                <td><?= htmlspecialchars($p['description'] ?? '') ?></td>
                                <td><?= date('d/m/Y', strtotime($p['date_creation'])) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?delete_partenaire=<?= $p['id'] ?>" class="btn-icon btn-delete" onclick="return confirm('Supprimer ce partenaire ?')">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            <!-- ONGLET CONTACTS -->
            <div id="contacts" class="tab-content">
                <div class="card">
                    <h3>Messages de contact (<?= count($contacts) ?>)</h3>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Sujet</th>
                                    <th>Message</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contacts as $contact): ?>
                                <tr class="<?= $contact['status'] == 'new' ? 'row-new' : '' ?>">
                                    <td><?= $contact['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($contact['date_creation'])) ?></td>
                                    <td><strong><?= htmlspecialchars(($contact['prenom'] ?? '') . ' ' . ($contact['nom'] ?? '')) ?></strong></td>
                                    <td><a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></td>
                                    <td><?= htmlspecialchars($contact['sujet'] ?? 'N/A') ?></td>
                                    <td class="message-preview">
                                        <span class="message-short"><?= htmlspecialchars(substr($contact['message'], 0, 50)) ?>...</span>
                                        <button class="btn-link" onclick="showFullMessage(<?= $contact['id'] ?>, '<?= htmlspecialchars(addslashes($contact['message'])) ?>')">Voir plus</button>
                                    </td>
                                    <td>
                                        <select class="status-select" onchange="updateContactStatus(<?= $contact['id'] ?>, this.value)">
                                            <option value="new" <?= $contact['status'] == 'new' ? 'selected' : '' ?>>🆕 Nouveau</option>
                                            <option value="read" <?= $contact['status'] == 'read' ? 'selected' : '' ?>>📖 Lu</option>
                                            <option value="closed" <?= $contact['status'] == 'closed' ? 'selected' : '' ?>>✅ Traité</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="?delete_contact=<?= $contact['id'] ?>" 
                                           class="btn-icon btn-delete" 
                                           onclick="return confirm('Supprimer ce contact ?')"
                                           title="Supprimer">
                                            🗑️
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ONGLET DEVIS -->
            <div id="devis" class="tab-content">
                <div class="card">
                    <h3>Demandes de devis (<?= count($devis) ?>)</h3>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Message</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($devis as $demande): ?>
                                <tr class="<?= $demande['status'] == 'new' ? 'row-new' : '' ?>">
                                    <td><?= $demande['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($demande['date_creation'])) ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($demande['Professionnels_Particuliers'] ?? 'N/A') ?></span></td>
                                    <td><strong><?= htmlspecialchars($demande['contact_name'] ?? 'N/A') ?></strong></td>
                                    <td><a href="mailto:<?= htmlspecialchars($demande['email']) ?>"><?= htmlspecialchars($demande['email']) ?></a></td>
                                    <td><?= htmlspecialchars($demande['phone'] ?? 'N/A') ?></td>
                                    <td class="message-preview">
                                        <span class="message-short"><?= htmlspecialchars(substr($demande['message'] ?? '', 0, 50)) ?>...</span>
                                        <?php if (strlen($demande['message'] ?? '') > 50): ?>
                                            <button class="btn-link" onclick="showFullMessage(<?= $demande['id'] ?>, '<?= htmlspecialchars(addslashes($demande['message'] ?? '')) ?>')">Voir plus</button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <select class="status-select" onchange="updateDevisStatus(<?= $demande['id'] ?>, this.value)">
                                            <option value="new" <?= $demande['status'] == 'new' ? 'selected' : '' ?>>🆕 Nouveau</option>
                                            <option value="in_progress" <?= $demande['status'] == 'in_progress' ? 'selected' : '' ?>>🔄 En cours</option>
                                            <option value="closed" <?= $demande['status'] == 'closed' ? 'selected' : '' ?>>✅ Traité</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="?delete_devis=<?= $demande['id'] ?>" 
                                           class="btn-icon btn-delete" 
                                           onclick="return confirm('Supprimer cette demande ?')"
                                           title="Supprimer">
                                            🗑️
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ONGLET UTILISATEURS -->
            <div id="utilisateurs" class="tab-content">
                <div class="card">
                    <h3>Liste des utilisateurs (<?= count($users) ?>)</h3>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Civilité</th>
                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr class="<?= $user['id'] == $_SESSION['id'] ? 'row-current-user' : '' ?>">
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['civilite'] ?? '') ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                                        <?php if ($user['id'] == $_SESSION['id']): ?>
                                            <span class="badge badge-info">Vous</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['mail']) ?></td>
                                    <td>
                                        <?php if ($user['role'] == 'admin'): ?>
                                            <span class="badge badge-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?></td>
                                    <td>
                                        <?php if ($user['id'] != $_SESSION['id']): ?>
                                            <a href="?delete_user=<?= $user['id'] ?>" 
                                               class="btn-icon btn-delete" 
                                               onclick="return confirm('Supprimer cet utilisateur ?')"
                                               title="Supprimer">
                                                🗑️
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal pour afficher les messages complets -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h3>Message complet</h3>
            <div id="modalMessageContent"></div>
        </div>
    </div>

    <!-- Modal pour éditer un service -->
    <div id="editServiceModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <h3>Modifier le service</h3>
            <form method="POST" class="admin-form">
                <!-- <input type="hidden" id="edit_service_id" name="service_id"> -->
                
                <div class="form-group">
                    <label for="edit_title">Titre *</label>
                    <input type="text" id="edit_title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_categorie">Catégorie *</label>
                    <select id="edit_categorie" name="categorie" required>
                        <option value="particulier">Particulier</option>
                        <option value="professionnel">Professionnel</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Annuler</button>
                    <button type="submit" name="modifier_service" value="1" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <?php include "./includes/footer.php"; ?>

    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script src="./asset/Js/admin.js"></script>
</body>
</html>