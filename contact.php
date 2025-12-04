<?php
session_start();

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');
define('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
define('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');

// Définir le fuseau horaire
date_default_timezone_set('Europe/Paris');

// Traitement du formulaire
if (!empty($_POST["envoie"])) {
    include_once "./includes/fonctions.php";
    include_once "./includes/connexionbdd.php";

    // ===== VÉRIFICATION reCAPTCHA =====
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    
    if (empty($recaptcha_response)) {
        $_SESSION['flash_error'] = "Veuillez cocher la case reCAPTCHA.";
        header("Location: ./contact.php");
        exit;
    }
    
    // Vérifier le reCAPTCHA côté serveur
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $recaptcha_options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($recaptcha_data),
            'timeout' => 10
        ]
    ];
    
    $recaptcha_context = stream_context_create($recaptcha_options);
    $recaptcha_result = @file_get_contents($recaptcha_url, false, $recaptcha_context);
    
    if ($recaptcha_result === false) {
        error_log("Erreur connexion reCAPTCHA");
        $_SESSION['flash_error'] = "Erreur de validation reCAPTCHA. Veuillez réessayer.";
        header("Location: ./contact.php");
        exit;
    }
    
    $recaptcha_json = json_decode($recaptcha_result);
    
    if (!$recaptcha_json || !isset($recaptcha_json->success) || !$recaptcha_json->success) {
        $error_codes = isset($recaptcha_json->{'error-codes'}) ? implode(', ', $recaptcha_json->{'error-codes'}) : 'inconnu';
        error_log("reCAPTCHA failed - Error codes: " . $error_codes);
        $_SESSION['flash_error'] = "Validation reCAPTCHA échouée. Veuillez réessayer.";
        header("Location: ./contact.php");
        exit;
    }

    // Nettoyage basique des champs (sans conversion en minuscules)
    $nom     = trim(strip_tags($_POST["nom"]));
    $mail    = trim(strtolower($_POST["email"]));
    $sujet   = trim(strip_tags($_POST["sujet"]));
    $message = trim(strip_tags($_POST["message"]));

    // Validation des données
    if (strlen($nom) < 2 || strlen($nom) > 100) {
        $_SESSION['flash_error'] = "Le nom doit contenir entre 2 et 100 caractères.";
        $_SESSION['form_data'] = $_POST;
        header("Location: ./contact.php");
        exit;
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = "L'adresse email n'est pas valide.";
        $_SESSION['form_data'] = $_POST;
        header("Location: ./contact.php");
        exit;
    }

    if (strlen($sujet) < 3 || strlen($sujet) > 200) {
        $_SESSION['flash_error'] = "Le sujet doit contenir entre 3 et 200 caractères.";
        $_SESSION['form_data'] = $_POST;
        header("Location: ./contact.php");
        exit;
    }

    if (strlen($message) < 10 || strlen($message) > 1000) {
        $_SESSION['flash_error'] = "Le message doit contenir entre 10 et 1000 caractères.";
        $_SESSION['form_data'] = $_POST;
        header("Location: ./contact.php");
        exit;
    }

    // Date formatée
    $date = date('d/m/Y H:i:s');

    // Récupérer l'email admin
    $adminEmail = 'boukhalfa.camil@hotmail.fr'; // Email par défaut
    try {
        $stmt = $connexion->prepare("SELECT mail FROM users WHERE role = 'admin' LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['mail'])) {
            $adminEmail = $result['mail'];
        }
    } catch (PDOException $e) {
        error_log("Erreur récupération admin email : " . $e->getMessage());
    }

    // ===== Préparer les emails =====
    $objet = SITE_TITLE . " - " . htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8');
    $contenu = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #2c3e50;'>Confirmation de votre message</h2>
                <p>Bonjour <strong>" . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . "</strong>,</p>
                <p>Nous avons bien reçu votre message et vous en remercions.</p>
                <p>Notre équipe vous recontactera dans les plus brefs délais.</p>
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                <p><strong>Sujet :</strong> " . htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8') . "</p>
                <p><strong>Votre message :</strong></p>
                <div style='background: #f9f9f9; padding: 15px; border-left: 4px solid #3498db;'>
                    " . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "
                </div>
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 12px; color: #777;'>Message envoyé le " . $date . "</p>
                <p style='margin-top: 30px;'>Cordialement,<br>
                <strong>" . SITE_TITLE . "</strong><br>
                Artisan électricien<br>
                67 rue du Charme, L'Isle-Adam</p>
            </div>
        </body>
        </html>
    ";
    
    $objet_admin = "🔔 Nouveau message depuis le site - " . htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8');
    $contenu_admin = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5;'>
                <div style='background: white; padding: 20px; border-radius: 5px;'>
                    <h2 style='color: #e74c3c;'>📧 Nouvelle demande de contact</h2>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 10px; background: #ecf0f1; font-weight: bold; width: 30%;'>Nom :</td>
                            <td style='padding: 10px;'>" . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . "</td>
                        </tr>
                        <tr>
                            <td style='padding: 10px; background: #ecf0f1; font-weight: bold;'>Email :</td>
                            <td style='padding: 10px;'><a href='mailto:" . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . "</a></td>
                        </tr>
                        <tr>
                            <td style='padding: 10px; background: #ecf0f1; font-weight: bold;'>Sujet :</td>
                            <td style='padding: 10px;'>" . htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8') . "</td>
                        </tr>
                    </table>
                    <div style='margin-top: 20px;'>
                        <p style='font-weight: bold; color: #2c3e50;'>Message :</p>
                        <div style='background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 3px;'>
                            " . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "
                        </div>
                    </div>
                    <hr style='border: 1px solid #eee; margin: 20px 0;'>
                    <p style='font-size: 11px; color: #999;'>Reçu le : " . $date . "</p>
                </div>
            </div>
        </body>
        </html>
    ";

    // ===== Envoi des emails =====
    ob_start();
    
    $sent_user  = envoyerMail($objet, $mail, $contenu);
    $sent_admin = envoyerMail($objet_admin, $adminEmail, $contenu_admin);
    
    $debug_output = ob_get_clean();
    if (!empty($debug_output)) {
        error_log("Debug PHPMailer: " . $debug_output);
    }

    // ===== Sauvegarde en BDD =====
    // ===== Séparation prénom / nom =====
    $nom = trim(strip_tags($_POST['nom']));

    // Séparation : 1er mot = prénom, reste = nom de famille
    $nom_parts = preg_split('/\s+/', $nom, 2); 

    $prenom = $nom_parts[0]; 
    $nom_famille = isset($nom_parts[1]) ? $nom_parts[1] : $nom_parts[0];

    // ===== Sauvegarde en BDD (table contact) =====
    try {
            $sql = "INSERT INTO contact (prenom, nom, email, sujet, `message`) 
                    VALUES (:prenom, :nom, :email, :sujet, :message)";
            $stmt = $connexion->prepare($sql);

            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $nom_famille, PDO::PARAM_STR);
            $stmt->bindValue(':email', $mail, PDO::PARAM_STR);
            $stmt->bindValue(':sujet', $sujet, PDO::PARAM_STR);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur insertion contact : " . $e->getMessage());
            $_SESSION['flash_error'] = "Erreur lors de l'enregistrement du message.";
        }

    // === Messages de feedback ===
    if ($sent_user && $sent_admin) {
        $_SESSION['flash_success'] = "Message envoyé avec succès ! Un email de confirmation vous a été envoyé.";
    } elseif ($sent_admin && !$sent_user) {
        $_SESSION['flash_success'] = "Message envoyé à notre équipe.";
        $_SESSION['flash_error']   = "L'email de confirmation n'a pas pu être envoyé à votre adresse.";
    } elseif (!$sent_admin && $sent_user) {
        $_SESSION['flash_success'] = "Email de confirmation envoyé.";
        $_SESSION['flash_error']   = "La notification interne a échoué, mais votre message a été enregistré.";
    } else {
        $_SESSION['flash_error'] = "Erreur lors de l'envoi des emails. Votre message a néanmoins été enregistré.";
    }

    // Nettoyer les données du formulaire en cas de succès
    unset($_SESSION['form_data']);

    // ===== Redirection =====
    header("Location: ./contact.php");
    exit;
}

// Récupérer les messages flash et données du formulaire
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error = $_SESSION['flash_error'] ?? '';
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['flash_success'], $_SESSION['flash_error'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez Thierry Decramp, électricien professionnel. Devis gratuit, intervention rapide. Disponible du lundi au vendredi de 8h à 18h, urgences 24/7.">
    <meta name="keywords" content="contact électricien, devis électricité, dépannage urgence, L'Isle-Adam électricien">
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
    
    <title>Contact - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <!-- HERO SECTION -->
        <section class="hero" aria-label="Bannière d'accueil">
            <div id="slider" role="region" aria-label="Carrousel d'images">
                <img src="./asset/image/ampoule.jpg" alt="Installation électrique moderne avec ampoule LED">
                <img src="./asset/image/ampoule2.jpg" alt="Tableau électrique professionnel">
                <img src="./asset/image/ampoule3.jpg" alt="Système domotique intelligent">
            </div>

            <div class="hero-overlay">
                <h1>Contact</h1>
                <p class="hero-subtitle"><?php echo SITE_TITLE; ?> - Artisan électricien</p>
                <p>Électricien depuis plus de 15 ans, spécialisé dans les nouvelles technologies et respectueux des normes.</p>
                <!-- <a href="contact.php" class="btn" aria-label="Accéder à la page contact">Nous contacter</a> -->
            </div>

            <!-- Navigation du carrousel -->
            <div class="hero-dots" role="navigation" aria-label="Navigation du carrousel">
                <span class="dot active" data-index="0" aria-label="Image 1" aria-current="true"></span>
                <span class="dot" data-index="1" aria-label="Image 2"></span>
                <span class="dot" data-index="2" aria-label="Image 3"></span>
            </div>
        </section>

        <!-- SECTION HORAIRES -->
        <section class="presentation">
            <h2>Nos horaires</h2>
            <blockquote cite="">
                Ouvert du lundi au vendredi de 8h à 18h<br>
                Dépannages d'urgence disponibles 24h/24 et 7j/7
            </blockquote>
        </section>

        <!-- SECTION CONTACT -->
        <section id="contact" class="contact-section">
            <h2>Contactez-nous</h2>
            
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

            <div class="contact">
                <div class="contact-wrapper">
                    <div class="contact-form">
                        <!-- Informations de contact -->
                        <div class="contact-info">
                            <p><strong>Adresse :</strong> 67 rue du Charme, L'Isle-Adam</p>
                            <p><strong>Téléphone :</strong> <a href="tel:+33XXXXXXXXX">01 XX XX XX XX</a></p>
                            <p><strong>Email :</strong> <a href="mailto:contact@secic-electricite.fr">contact@secic-electricite.fr</a></p>
                        </div>

                        <!-- Formulaire -->
                        <form action="./contact.php" method="post" novalidate>
                            <div class="form-group">
                                <label for="nom" class="sr-only">Nom</label>
                                <input type="text" 
                                       id="nom" 
                                       name="nom" 
                                       placeholder="Nom *" 
                                       required 
                                       aria-required="true"
                                       minlength="2"
                                       maxlength="100"
                                       value="<?= isset($form_data['nom']) ? htmlspecialchars($form_data['nom']) : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Email *" 
                                       required 
                                       aria-required="true"
                                       maxlength="150"
                                       value="<?= isset($form_data['email']) ? htmlspecialchars($form_data['email']) : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="sujet" class="sr-only">Sujet</label>
                                <input type="text" 
                                       id="sujet" 
                                       name="sujet" 
                                       placeholder="Sujet *" 
                                       required 
                                       aria-required="true"
                                       maxlength="200"
                                       value="<?= isset($form_data['sujet']) ? htmlspecialchars($form_data['sujet']) : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="message" class="sr-only">Message</label>
                                <textarea id="message" 
                                          name="message" 
                                          placeholder="Message *" 
                                          required 
                                          aria-required="true"
                                          minlength="10"
                                          maxlength="1000"
                                          rows="5"><?= isset($form_data['message']) ? htmlspecialchars($form_data['message']) : '' ?></textarea>
                            </div>

                            <!-- reCAPTCHA v2 -->
                            <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>

                            <button type="submit" name="envoie" value="1" class="btn">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include "./includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>