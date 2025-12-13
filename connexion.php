<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');

// Rediriger si déjà connecté
if (isset($_SESSION['connexion']) && $_SESSION['connexion'] === true) {
    header("Location: ./user.php");
    exit;
}

include "./includes/connexionbdd.php";
include "./includes/fonctions.php";

// ========== TRAITEMENT CONNEXION ==========
if (!empty($_POST["connexion"])) {
    $email = trim(strtolower($_POST["email"]));
    $mdp = $_POST["mdp"];
    
    // Validation basique
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = "Veuillez fournir une adresse email valide.";
    }
    elseif (empty($mdp)) {
        $_SESSION['flash_error'] = "Veuillez saisir votre mot de passe.";
    }
    else {
        // Protection contre le bruteforce : limiter les tentatives
        $ip = $_SERVER['REMOTE_ADDR'];
        $max_tentatives = 5;
        $delai_blocage = 900; // 15 minutes en secondes
        
        // Vérifier les tentatives de connexion
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [];
        }
        
        // Nettoyer les anciennes tentatives
        $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], function($time) use ($delai_blocage) {
            return (time() - $time) < $delai_blocage;
        });
        
        // Vérifier si trop de tentatives
        if (count($_SESSION['login_attempts']) >= $max_tentatives) {
            $temps_restant = ceil(($delai_blocage - (time() - min($_SESSION['login_attempts']))) / 60);
            $_SESSION['flash_error'] = "Trop de tentatives de connexion. Veuillez réessayer dans {$temps_restant} minutes.";
        }
        else {
            try {
                // Rechercher l'utilisateur
                $sql = "SELECT * FROM users WHERE mail = :email LIMIT 1";
                $stmt = $connexion->prepare($sql);
                $stmt->bindValue(":email", $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Vérifier si l'utilisateur existe et le mot de passe est correct
                if ($user && password_verify($mdp, $user['mdp'])) {
                    // Connexion réussie
                    $_SESSION['connexion'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['email'] = $user['mail'];
                    $_SESSION['role'] = $user['role'] ?? 'user';
                    
                    // Réinitialiser les tentatives
                    unset($_SESSION['login_attempts']);
                    
                    // Se souvenir de moi (optionnel)
                    if (!empty($_POST['remember_me'])) {
                        $token = bin2hex(random_bytes(32));
                        //le système génère un token sécurisé, puis crée un cookie valable 30 jours.
                        setcookie('remember_token', $token, time() + (86400 * 30), "/", "", true, true); // 30 jours
                        
                        // Sauvegarder le token en BDD (à implémenter)
                        // $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
                    }
                    
                    // Rediriger vers la page demandée ou user.php
                    $redirect = $_SESSION['redirect_after_login'] ?? './user.php';
                    unset($_SESSION['redirect_after_login']);
                    
                    $_SESSION['flash_success'] = "Bienvenue {$user['prenom']} !";
                    header("Location: $redirect");
                    exit;
                }
                else {
                    // Échec de connexion
                    $_SESSION['login_attempts'][] = time();
                    $tentatives_restantes = $max_tentatives - count($_SESSION['login_attempts']);
                    
                    if ($tentatives_restantes > 0) {
                        $_SESSION['flash_error'] = "Email ou mot de passe incorrect. Il vous reste {$tentatives_restantes} tentative(s).";
                    } else {
                        $_SESSION['flash_error'] = "Trop de tentatives. Compte bloqué pendant 15 minutes.";
                    }
                    
                    error_log("Tentative de connexion échouée pour : $email depuis IP : $ip");
                }
            }
            catch(PDOException $e) {
                error_log("Erreur connexion : " . $e->getMessage());
                $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    }
    
    header("Location: ./connexion.php");
    exit;
}

// ========== TRAITEMENT INSCRIPTION ==========
if (!empty($_POST["inscription"])) {
    $civilite = trim($_POST["civilite"]);
    $prenom = trim(strip_tags($_POST["prenom"]));
    $nom = trim(strip_tags($_POST["nom"]));
    $email = trim(strtolower($_POST["email"]));
    $mdp = $_POST["mdp"];
    $confirmer_mdp = $_POST["confirmer_mdp"];
    $accepter_cgu = !empty($_POST["accepter_cgu"]);
    
    // Validation
    if (empty($civilite) || !in_array($civilite, ['M.', 'Mme', 'Mx'])) {
        $_SESSION['flash_error'] = "Veuillez sélectionner une civilité.";
    }
    elseif (strlen($prenom) < 2 || strlen($prenom) > 50) {
        $_SESSION['flash_error'] = "Le prénom doit contenir entre 2 et 50 caractères.";
    }
    elseif (strlen($nom) < 2 || strlen($nom) > 50) {
        $_SESSION['flash_error'] = "Le nom doit contenir entre 2 et 50 caractères.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = "L'adresse email n'est pas valide.";
    }
    elseif (strlen($mdp) < 12) {
        $_SESSION['flash_error'] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    elseif ($mdp !== $confirmer_mdp) {
        $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
    }
    elseif (!$accepter_cgu) {
        $_SESSION['flash_error'] = "Vous devez accepter les conditions générales d'utilisation.";
    }
    else {
        try {
            // Vérifier si l'email existe déjà
            $sql = "SELECT id FROM users WHERE mail = :email";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                $_SESSION['flash_error'] = "Cette adresse email est déjà utilisée.";
            }
            else {
                // Créer le compte
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (civilite, prenom, nom, mail, mdp, role, date_creation) 
                        VALUES (:civilite, :prenom, :nom, :email, :mdp, 'user', NOW())";
                $stmt = $connexion->prepare($sql);
                $stmt->bindValue(":civilite", $civilite);
                $stmt->bindValue(":prenom", $prenom);
                $stmt->bindValue(":nom", $nom);
                $stmt->bindValue(":email", $email);
                $stmt->bindValue(":mdp", $mdp_hash);
                $stmt->execute();
                
                $_SESSION['flash_success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                
                // Optionnel : Envoyer un email de bienvenue
                // include_once "./includes/fonctions.php";
                // envoyerMail("Bienvenue", $email, "Votre compte a été créé...");
            }
        }
        catch(PDOException $e) {
            error_log("Erreur inscription : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur est survenue lors de la création du compte.";
        }
    }
    
    header("Location: ./connexion.php");
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
    <meta name="description" content="Connectez-vous à votre espace client ou créez un compte pour accéder à vos services.">
    <meta name="keywords" content="connexion, inscription, espace client, compte utilisateur">
    <meta name="author" content="SECIC - Thierry Decramp">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Connexion - <?php echo SITE_TITLE; ?></title>

</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="auth-container">
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

            <div class="auth-wrapper">
                <!-- Partie gauche : Avantages -->
                <div class="auth-side left">
                    <h1>Bienvenue chez<br><?php echo SITE_TITLE; ?></h1>
                    <p>Créez un compte pour profiter de :</p>
                    <ul>
                        <li>Suivi de vos demandes en temps réel</li>
                        <li>Historique de vos interventions</li>
                        <li>Demandes de devis simplifiées</li>
                        <li>Accès à vos factures et documents</li>
                        <li>Support prioritaire</li>
                    </ul>
                </div>

                <!-- Partie droite : Formulaires -->
                <div class="auth-side right">
                    <!-- Onglets -->
                    <div class="auth-tabs">
                        <button class="auth-tab active" data-form="connexion">Connexion</button>
                        <button class="auth-tab" data-form="inscription">Inscription</button>
                    </div>

                    <!-- FORMULAIRE CONNEXION -->
                    <form id="connexion" class="auth-form active" method="post" action="./connexion.php">
                        <div class="form-group">
                            <label for="email-connexion">Email *</label>
                            <input type="email" id="email-connexion" name="email" 
                                   placeholder="votre@email.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mdp-connexion">Mot de passe *</label>
                            <input type="password" id="mdp-connexion" name="mdp" 
                                   placeholder="••••••••" required>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="remember-me" name="remember_me" value="1">
                            <label for="remember-me">Se souvenir de moi</label>
                        </div>
                        
                        <button type="submit" name="connexion" value="1" class="btn" style="width: 100%;">
                            Se connecter
                        </button>
                        
                        <div class="forgot-password">
                            <a href="./mot-de-passe-oublie.php">Mot de passe oublié ?</a>
                        </div>
                    </form>

                    <!-- FORMULAIRE INSCRIPTION -->
                    <form id="inscription" class="auth-form" method="post" action="./connexion.php">
                        <div class="form-group">
                            <label for="civilite">Civilité *</label>
                            <select id="civilite" name="civilite" required>
                                <option value="">Sélectionner</option>
                                <option value="M.">M.</option>
                                <option value="Mme">Mme</option>
                                <option value="Mx">Mx</option>
                            </select>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="prenom">Prénom *</label>
                                <input type="text" id="prenom" name="prenom" 
                                       placeholder="Jean" required minlength="2" maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label for="nom">Nom *</label>
                                <input type="text" id="nom" name="nom" 
                                       placeholder="Dupont" required minlength="2" maxlength="50">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email-inscription">Email *</label>
                            <input type="email" id="email-inscription" name="email" 
                                   placeholder="votre@email.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mdp-inscription">Mot de passe * (min. 12 caractères)</label>
                            <input type="password" id="mdp-inscription" name="mdp" 
                                   placeholder="••••••••••••" required minlength="12">
                            <div class="password-strength">
                                <div class="password-strength-bar" id="strength-bar"></div>
                            </div>
                            <div class="password-hint" id="password-hint">
                                Utilisez au moins 12 caractères avec majuscules, minuscules et chiffres
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmer-mdp">Confirmer le mot de passe *</label>
                            <input type="password" id="confirmer-mdp" name="confirmer_mdp" 
                                   placeholder="••••••••" required>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="accepter-cgu" name="accepter_cgu" 
                                   value="1" required>
                            <label for="accepter-cgu">
                                J'accepte les <a href="./cgu.php" target="_blank">conditions générales d'utilisation</a> *
                            </label>
                        </div>
                        
                        <button type="submit" name="inscription" value="1" class="btn" style="width: 100%;">
                            Créer mon compte
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include "./includes/footer.php"; ?>

    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script>
        // Gestion des onglets
        document.querySelectorAll('.auth-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const formId = this.dataset.form;
                
                // Désactiver tous les onglets et formulaires
                document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
                
                // Activer l'onglet et le formulaire sélectionnés
                this.classList.add('active');
                document.getElementById(formId).classList.add('active');
            });
        });

        // Indicateur de force du mot de passe
        const mdpInput = document.getElementById('mdp-inscription');
        const strengthBar = document.getElementById('strength-bar');
        const passwordHint = document.getElementById('password-hint');
        
        if (mdpInput) {
            mdpInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 12) strength++;
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
        const confirmerMdp = document.getElementById('confirmer-mdp');
        
        if (confirmerMdp) {
            confirmerMdp.addEventListener('input', function() {
                if (this.value && this.value !== mdpInput.value) {
                    this.setCustomValidity('Les mots de passe ne correspondent pas');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    </script>
</body>
</html>