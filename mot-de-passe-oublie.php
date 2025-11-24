<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');

include_once "./includes/connexionbdd.php";
include_once "./includes/fonctions.php";

// ========== TRAITEMENT DEMANDE DE RÉINITIALISATION ==========
if (!empty($_POST["demander_reinitialisation"])) {
    $email = trim(strtolower($_POST["email"]));
    
    // Validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = "Veuillez fournir une adresse email valide.";
    }
    else {
        try {
            // Vérifier si l'email existe
            $sql = "SELECT id, prenom, nom FROM users WHERE mail = :email LIMIT 1";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Générer un token unique et sécurisé
                $token = bin2hex(random_bytes(32));
                $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valide 1 heure
                
                // Enregistrer le token en BDD
                $sql = "UPDATE users 
                        SET reset_token = :token, 
                            reset_token_expires = :expiration 
                        WHERE id = :id";
                $stmt = $connexion->prepare($sql);
                $stmt->bindValue(":token", $token);
                $stmt->bindValue(":expiration", $expiration);
                $stmt->bindValue(":id", $user['id'], PDO::PARAM_INT);
                $stmt->execute();
                
                // Créer le lien de réinitialisation
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/project_exam/reinitialiser-mot-de-passe.php?token=" . $token;
                
                // Préparer l'email
                $objet = SITE_TITLE . " - Réinitialisation de votre mot de passe";
                $contenu = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='UTF-8'>
                    </head>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;'>
                            <div style='background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                                <h2 style='color: #2c3e50; margin-top: 0;'>🔐 Réinitialisation de mot de passe</h2>
                                
                                <p>Bonjour <strong>" . htmlspecialchars($user['prenom'] . ' ' . $user['nom'], ENT_QUOTES, 'UTF-8') . "</strong>,</p>
                                
                                <p>Vous avez demandé à réinitialiser votre mot de passe sur <strong>" . SITE_TITLE . "</strong>.</p>
                                
                                <p>Pour créer un nouveau mot de passe, cliquez sur le bouton ci-dessous :</p>
                                
                                <div style='text-align: center; margin: 30px 0;'>
                                    <a href='" . $reset_link . "' 
                                       style='display: inline-block; 
                                              padding: 15px 30px; 
                                              background: #3498db; 
                                              color: white; 
                                              text-decoration: none; 
                                              border-radius: 5px;
                                              font-weight: bold;'>
                                        Réinitialiser mon mot de passe
                                    </a>
                                </div>
                                
                                <p style='font-size: 14px; color: #666;'>
                                    Ou copiez ce lien dans votre navigateur :<br>
                                    <a href='" . $reset_link . "' style='color: #3498db; word-break: break-all;'>" . $reset_link . "</a>
                                </p>
                                
                                <hr style='border: 1px solid #eee; margin: 25px 0;'>
                                
                                <div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; border-radius: 4px; margin: 20px 0;'>
                                    <p style='margin: 0; font-size: 14px;'>
                                        <strong>⚠️ Important :</strong><br>
                                        • Ce lien est valide pendant <strong>1 heure</strong><br>
                                        • Si vous n'avez pas demandé cette réinitialisation, ignorez cet email<br>
                                        • Ne partagez jamais ce lien avec personne
                                    </p>
                                </div>
                                
                                <p style='font-size: 12px; color: #999; margin-top: 30px;'>
                                    Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                                    Pour toute question, contactez-nous via notre formulaire de contact.
                                </p>
                                
                                <hr style='border: 1px solid #eee; margin: 25px 0;'>
                                
                                <p style='font-size: 12px; color: #777;'>
                                    Cordialement,<br>
                                    <strong>" . SITE_TITLE . "</strong><br>
                                    Artisan électricien<br>
                                    67 rue du Charme, L'Isle-Adam
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";
                
                // Envoyer l'email
                $sent = envoyerMail($objet, $email, $contenu);
                
                if ($sent) {
                    $_SESSION['flash_success'] = "Un email de réinitialisation a été envoyé à votre adresse. Vérifiez votre boîte de réception.";
                    error_log("Email de réinitialisation envoyé à : $email");
                } else {
                    $_SESSION['flash_error'] = "Erreur lors de l'envoi de l'email. Veuillez réessayer plus tard.";
                    error_log("Échec envoi email réinitialisation pour : $email");
                }
            }
            else {
                // Pour des raisons de sécurité, on affiche le même message même si l'email n'existe pas
                $_SESSION['flash_success'] = "Si cette adresse email existe, un lien de réinitialisation a été envoyé.";
                error_log("Tentative de réinitialisation pour email inexistant : $email");
            }
        }
        catch(PDOException $e) {
            error_log("Erreur réinitialisation mot de passe : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
    
    header("Location: ./mot-de-passe-oublie.php");
    exit;
}

// Récupérer les messages flash
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Réinitialisez votre mot de passe oublié en toute sécurité.">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Mot de passe oublié - <?php echo SITE_TITLE; ?></title>
    
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="password-reset-container">
            <!-- Messages flash -->
            <?php if (!empty($flash_success)): ?>
                <div class="alert alert-success" role="alert" style="margin-bottom: 20px;">
                    <strong></strong> <?= htmlspecialchars($flash_success) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($flash_error)): ?>
                <div class="alert alert-error" role="alert" style="margin-bottom: 20px;">
                    <strong>⚠</strong> <?= htmlspecialchars($flash_error) ?>
                </div>
            <?php endif; ?>
            
            <div class="password-reset-header">
                <div class="icon-lock">🔐</div>
                <h1>Mot de passe oublié ?</h1>
                <p>Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
            </div>
            
            <form method="post" action="./mot-de-passe-oublie.php">
                <div class="form-group">
                    <label for="email">Adresse email *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           placeholder="votre@email.com" 
                           required 
                           autofocus>
                </div>
                
                <div class="info-box">
                    <strong>ℹ️ Comment ça marche ?</strong>
                    1. Entrez votre adresse email<br>
                    2. Recevez un lien de réinitialisation par email<br>
                    3. Créez votre nouveau mot de passe (valide 1 heure)
                </div>
                
                <button type="submit" name="demander_reinitialisation" value="1" class="btn-reset">
                    Envoyer le lien de réinitialisation
                </button>
            </form>
            
            <div class="back-to-login">
                <a href="./connexion.php">← Retour à la connexion</a>
            </div>
        </div>
    </main>
    
    <?php include "./includes/footer.php"; ?>
    
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>