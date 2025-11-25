<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');

include_once "./includes/connexionbdd.php";

// Récupérer le token depuis l'URL
$token = $_GET['token'] ?? '';
$token_valide = false;
$user_data = null;

// Vérifier le token
if (!empty($token)) {
    try {
        $sql = "SELECT id, prenom, nom, mail, reset_token_expires 
                FROM users 
                WHERE reset_token = :token 
                LIMIT 1";
        $stmt = $connexion->prepare($sql);
        $stmt->bindValue(":token", $token);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data) {
            // Vérifier si le token n'est pas expiré
            $expiration = strtotime($user_data['reset_token_expires']);
            if ($expiration > time()) {
                $token_valide = true;
            } else {
                $_SESSION['flash_error'] = "Ce lien de réinitialisation a expiré. Veuillez en demander un nouveau.";
            }
        } else {
            $_SESSION['flash_error'] = "Lien de réinitialisation invalide.";
        }
    }
    catch(PDOException $e) {
        error_log("Erreur vérification token : " . $e->getMessage());
        $_SESSION['flash_error'] = "Une erreur est survenue.";
    }
} else {
    $_SESSION['flash_error'] = "Aucun token fourni.";
}

// ========== TRAITEMENT NOUVEAU MOT DE PASSE ==========
if (!empty($_POST["reinitialiser_mdp"]) && $token_valide && $user_data) {
    $nouveau_mdp = $_POST["nouveau_mdp"];
    $confirmer_mdp = $_POST["confirmer_mdp"];
    
    // Validation
    if (strlen($nouveau_mdp) < 8) {
        $_SESSION['flash_error'] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    elseif ($nouveau_mdp !== $confirmer_mdp) {
        $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
    }
    else {
        try {
            // Hasher le nouveau mot de passe
            $mdp_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            
            // Mettre à jour le mot de passe et supprimer le token
            $sql = "UPDATE users 
                    SET mdp = :mdp, 
                        reset_token = NULL, 
                        reset_token_expires = NULL 
                    WHERE id = :id";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(":mdp", $mdp_hash);
            $stmt->bindValue(":id", $user_data['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $_SESSION['flash_success'] = "Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.";
            
            error_log("Mot de passe réinitialisé pour l'utilisateur ID : " . $user_data['id']);
            
            header("Location: ./connexion.php");
            exit;
        }
        catch(PDOException $e) {
            error_log("Erreur réinitialisation mot de passe : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
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
    <meta name="description" content="Créez votre nouveau mot de passe.">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Nouveau mot de passe - <?php echo SITE_TITLE; ?></title>
    
    <style>
        .password-reset-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .password-reset-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .password-reset-header h1 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        
        .password-reset-header p {
            color: #666;
            font-size: 0.95em;
            line-height: 1.6;
        }
        
        .icon-key {
            font-size: 4em;
            color: #27ae60;
            margin-bottom: 20px;
        }
        
        .user-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .user-info p {
            margin: 5px 0;
            color: #555;
        }
        
        .user-info strong {
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #27ae60;
            outline: none;
        }
        
        .password-strength {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s;
        }
        
        .password-strength-bar.weak {
            width: 33%;
            background: #f44336;
        }
        
        .password-strength-bar.medium {
            width: 66%;
            background: #ff9800;
        }
        
        .password-strength-bar.strong {
            width: 100%;
            background: #4caf50;
        }
        
        .password-hint {
            font-size: 0.85em;
            margin-top: 5px;
            color: #666;
        }
        
        .btn-reset {
            width: 100%;
            padding: 15px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-reset:hover {
            background: #229954;
        }
        
        .btn-reset:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-to-login a {
            color: #3498db;
            text-decoration: none;
            font-size: 0.95em;
        }
        
        .back-to-login a:hover {
            text-decoration: underline;
        }
        
        .requirements {
            background: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 0.9em;
        }
        
        .requirements ul {
            margin: 10px 0 0 20px;
            padding: 0;
        }
        
        .requirements li {
            margin: 5px 0;
            color: #666;
        }
        
        .error-box {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .error-box h2 {
            color: #c62828;
            margin: 0 0 10px 0;
        }
    </style>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="password-reset-container">
            <!-- Messages flash -->
            <?php if (!empty($flash_success)): ?>
                <div class="alert alert-success" role="alert" style="margin-bottom: 20px;">
                    <strong>✓</strong> <?= htmlspecialchars($flash_success) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($flash_error)): ?>
                <div class="alert alert-error" role="alert" style="margin-bottom: 20px;">
                    <strong>⚠</strong> <?= htmlspecialchars($flash_error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($token_valide && $user_data): ?>
                <!-- Formulaire de réinitialisation -->
                <div class="password-reset-header">
                    <div class="icon-key">🔑</div>
                    <h1>Nouveau mot de passe</h1>
                    <p>Créez un mot de passe sécurisé pour votre compte.</p>
                </div>
                
                <div class="user-info">
                    <p>Compte : <strong><?= htmlspecialchars($user_data['prenom'] . ' ' . $user_data['nom']) ?></strong></p>
                    <p><?= htmlspecialchars($user_data['mail']) ?></p>
                </div>
                
                <form method="post" action="./reinitialiser-mot-de-passe.php?token=<?= htmlspecialchars($token) ?>">
                    <div class="form-group">
                        <label for="nouveau_mdp">Nouveau mot de passe *</label>
                        <input type="password" 
                               id="nouveau_mdp" 
                               name="nouveau_mdp" 
                               placeholder="••••••••" 
                               required 
                               minlength="8"
                               autofocus>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strength-bar"></div>
                        </div>
                        <div class="password-hint" id="password-hint">
                            Utilisez au moins 8 caractères
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmer_mdp">Confirmer le mot de passe *</label>
                        <input type="password" 
                               id="confirmer_mdp" 
                               name="confirmer_mdp" 
                               placeholder="••••••••" 
                               required>
                    </div>
                    
                    <div class="requirements">
                        <strong>📋 Exigences du mot de passe :</strong>
                        <ul>
                            <li>Au moins 8 caractères</li>
                            <li>Mélange de majuscules et minuscules (recommandé)</li>
                            <li>Au moins un chiffre (recommandé)</li>
                            <li>Caractères spéciaux pour plus de sécurité</li>
                        </ul>
                    </div>
                    
                    <button type="submit" name="reinitialiser_mdp" value="1" class="btn-reset">
                        Réinitialiser mon mot de passe
                    </button>
                </form>
                
            <?php else: ?>
                <!-- Message d'erreur si token invalide -->
                <div class="error-box">
                    <h2>❌ Lien invalide ou expiré</h2>
                    <p>Ce lien de réinitialisation n'est plus valide.</p>
                    <p>Les liens expirent après 1 heure pour des raisons de sécurité.</p>
                    <div style="margin-top: 20px;">
                        <a href="./mot-de-passe-oublie.php" class="btn-reset" style="display: inline-block; text-decoration: none;">
                            Demander un nouveau lien
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="back-to-login">
                <a href="./connexion.php">← Retour à la connexion</a>
            </div>
        </div>
    </main>
    
    <?php include "./includes/footer.php"; ?>
    
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script>
        // Indicateur de force du mot de passe
        const mdpInput = document.getElementById('nouveau_mdp');
        const strengthBar = document.getElementById('strength-bar');
        const passwordHint = document.getElementById('password-hint');
        
        if (mdpInput) {
            mdpInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                strengthBar.className = 'password-strength-bar';
                
                if (strength === 0 || strength === 1) {
                    strengthBar.classList.add('weak');
                    passwordHint.textContent = 'Mot de passe faible';
                    passwordHint.style.color = '#f44336';
                } else if (strength === 2 || strength === 3) {
                    strengthBar.classList.add('medium');
                    passwordHint.textContent = 'Mot de passe moyen';
                    passwordHint.style.color = '#ff9800';
                } else {
                    strengthBar.classList.add('strong');
                    passwordHint.textContent = 'Mot de passe fort !';
                    passwordHint.style.color = '#4caf50';
                }
            });
        }

        // Vérification de la correspondance des mots de passe
        const confirmerMdp = document.getElementById('confirmer_mdp');
        
        if (confirmerMdp) {
            confirmerMdp.addEventListener('input', function() {
                if (this.value && this.value !== mdpInput.value) {
                    this.setCustomValidity('Les mots de passe ne correspondent pas');
                    this.style.borderColor = '#f44336';
                } else {
                    this.setCustomValidity('');
                    this.style.borderColor = '#27ae60';
                }
            });
        }
    </script>
</body>
</html>