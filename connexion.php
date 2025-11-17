<?php
session_start();

// // Inclure la connexion PDO (doit définir $connexion)
include "./includes/connexionbdd.php";
include "./includes/fonctions.php";

// Fonction utilitaire pour écrire les messages d'erreur (optionnel)
// function log_error($msg) {
//     file_put_contents(__DIR__ . "/error.log", date("d/m/Y H:i:s : ").$msg.PHP_EOL, FILE_APPEND);
// }

// --- FLASH helpers ---
function set_flash($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}
function get_flash($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}

// Générer / vérifier token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}
$csrf_token = $_SESSION['csrf_token'];

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    // Vérifier token CSRF
    $posted_token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($csrf_token, $posted_token)) {
        set_flash('error', 'Opération non autorisée (CSRF).');
        header("Location: connexion.php");
        exit;
    }

    // Récupération et nettoyage
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation basique
    if (empty($email) || empty($password)) {
        set_flash('error', 'Veuillez renseigner votre email et votre mot de passe.');
        $_SESSION['form_data']['email'] = $email;
        header("Location: connexion.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Adresse email invalide.');
        $_SESSION['form_data']['email'] = $email;
        header("Location: connexion.php");
        exit;
    }

    try {
        // Récupérer l'utilisateur par email
        $stmt = $connexion->prepare("SELECT id, prenom, nom, mail, mdp, `role` FROM users WHERE mail = :mail LIMIT 1");
        $stmt->bindValue(':mail', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Ne pas préciser si l'email existe pour des raisons de sécurité
            set_flash('error', 'Identifiants invalides.');
            $_SESSION['form_data']['email'] = $email;
            header("Location: connexion.php");
            exit;
        }

        // Vérifier mot de passe (attend hash en base)
        if (!password_verify($password, $user['mdp'])) {
            set_flash('error', 'Identifiants invalides.');
            $_SESSION['form_data']['email'] = $email;
            header("Location: connexion.php");
            exit;
        }

        // Tout ok : authentifier l'utilisateur
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['mail'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        $_SESSION['connexion'] = true;

        // Redirection selon rôle
        if (isset($user['role']) && $user['role'] === 'admin') {
            header("Location: ./admin.php");
            exit;
        } else {
            header("Location: ./profil.php"); // ou index.php selon ton projet
            exit;
        }

    } catch (PDOException $e) {
        // log_error("Login error: " . $e->getMessage());
        set_flash('error', "Erreur serveur, réessayez plus tard.");
        header("Location: connexion.php");
        exit;
    }
}

// Récupérer messages flash pour affichage
$flash_error = get_flash('error');
$flash_success = get_flash('success');
$form_email = $_SESSION['form_data']['email'] ?? '';
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Thierry Decramp - Artisan électricien à L'Isle-Adam. Plus de 15 ans d'expérience en électricité, domotique et installations professionnelles.">
    <meta name="keywords" content="électricien, domotique, installation électrique, L'Isle-Adam, Val d'Oise">
    <meta name="author" content="SECIC - Thierry Decramp">
    
    <!-- Preconnect pour optimiser le chargement des polices -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Polices Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="./asset/css/style2.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Connexion - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
     <main>
        <div class="login-wrapper" role="main" aria-labelledby="login-title">
            <h1 id="login-title">Connexion</h1>

            <?php if (!empty($flash_error)): ?>
                <div class="alert alert-error" role="alert"><?= htmlspecialchars($flash_error) ?></div>
            <?php endif; ?>

            <?php if (!empty($flash_success)): ?>
                <div class="alert alert-success" role="status"><?= htmlspecialchars($flash_success) ?></div>
            <?php endif; ?>

            <form action="connexion.php" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required value="<?= htmlspecialchars($form_email) ?>" placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" required placeholder="Mot de passe">
                </div>

                <div class="form-group">
                    <button class="btn" type="submit" name="connexion" value="1">Se connecter</button>
                </div>

                <a class="small-link" href="inscription.php">Pas encore inscrit ? Créer un compte</a>
                <a class="small-link" href="motdepasse_oublie.php">Mot de passe oublié</a>
            </form>
        </div>
    </main>

    <?php include "./includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>


