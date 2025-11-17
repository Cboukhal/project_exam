<?php
    session_start();
    date_default_timezone_set('Europe/Paris');
    
    // Traitement du formulaire
    if(!empty($_POST["envoyer_commentaire"]))
    {
        include_once "./includes/connexionbdd.php";
        
        // Récupérer et sécuriser les données
        $nom = !empty($_POST["nom"]) ? htmlspecialchars(trim($_POST["nom"])) : 'Anonyme';
        $email = htmlspecialchars(trim($_POST["email"]));
        $note = intval($_POST["note"]);
        $commentaire = htmlspecialchars(trim($_POST["commentaire"]));
        
        // Validation
        if($note < 1 || $note > 5) {
            $_SESSION['flash_error'] = "La note doit être entre 1 et 5 étoiles.";
        }
        elseif(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "Veuillez fournir une adresse email valide.";
        }
        elseif(empty($commentaire)) {
            $_SESSION['flash_error'] = "Le commentaire ne peut pas être vide.";
        }
        else {
            try {
                // Insérer le commentaire dans la BDD
                $sql = "INSERT INTO commentaire (pseudo, email, note, commentaire, approved, date_creation) 
                        VALUES (:pseudo, :email, :note, :commentaire, 0, NOW())";
                $stmt = $connexion->prepare($sql);
                $stmt->bindValue(":pseudo", $nom);
                $stmt->bindValue(":email", $email);
                $stmt->bindValue(":note", $note, PDO::PARAM_INT);
                $stmt->bindValue(":commentaire", $commentaire);
                $stmt->execute();
                
                $_SESSION['flash_success'] = "Merci pour votre commentaire ! Il sera visible après modération.";
            }
            catch(PDOException $e) {
                error_log("Erreur insertion commentaire : " . $e->getMessage());
                $_SESSION['flash_error'] = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
        
        header("Location: ./commentaire.php");
        exit;
    }
    
    // Récupérer les messages flash
    $flash_success = $_SESSION['flash_success'] ?? '';
    $flash_error = $_SESSION['flash_error'] ?? '';
    unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    
    // Récupérer les 3 derniers commentaires approuvés
    include_once "./includes/connexionbdd.php";
    try {
        $sql = "SELECT pseudo, note, commentaire, DATE_FORMAT(date_creation, '%d/%m/%Y') as date_fr 
                FROM commentaire 
                WHERE approved = 1 
                ORDER BY date_creation DESC 
                LIMIT 3";
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {
        error_log("Erreur récupération commentaires : " . $e->getMessage());
        $commentaires = [];
    }
?>
<!------------------------------------------------------------------------------>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Laissez votre avis sur nos services">
    <meta name="keywords" content="avis, commentaires, notes, témoignages">
    <meta name="author" content="Thierry Decramp">
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/favicon" href="./asset/image/OIP.webp">
    <title>Commentaires - Thierry Decramp</title>
    <style>
        .alert {
            padding: 15px;
            margin: 20px auto;
            border-radius: 5px;
            max-width: 800px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .stars-container {
            margin: 15px 0;
        }
        .stars {
            font-size: 40px;
            cursor: pointer;
            user-select: none;
        }
        .star {
            color: #ddd;
            transition: color 0.2s;
        }
        .star:hover,
        .star.active {
            color: gold;
        }
        .rating-text {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .error-message {
            display: none;
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .error-message.show {
            display: block;
        }
        .temoignage-card {
            background: #f8f9fa;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .temoignage-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .temoignage-stars {
            font-size: 20px;
            color: gold;
        }
        .temoignage-date {
            color: #666;
            font-size: 14px;
        }
        .temoignage-author {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .temoignage-content {
            color: #555;
            line-height: 1.6;
        }
        .no-comments {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <!-- ACCUEIL -->
        <section class="hero">
            <div id="slider">
                <img src="./asset/image/ampoule.jpg" alt="ampoule">
                <img src="./asset/image/ampoule2.jpg" alt="ampoule2">
                <img src="./asset/image/ampoule3.jpg" alt="ampoule3">
            </div>

            <div class="hero-overlay">
                <h1>Commentaires</h1>
                <p>Thierry Decramp - SECIC - Artisan électricien</p>
                <p>Électricien depuis plus de 15 ans, spécialisé dans les nouvelles technologies et respectueux des normes.</p>
                <a href="contact.php" class="btn">Contact</a>
            </div>

            <div class="hero-dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
            </div>
        </section>

        <!-- Introduction -->
        <section class="presentation">
            <blockquote>"Que pensez-vous de nos services ?"</blockquote>
        </section>

        <!-- Messages flash -->
        <?php if (!empty($flash_success)): ?>
            <div class="alert alert-success"><?= $flash_success ?></div>
        <?php endif; ?>
        
        <?php if (!empty($flash_error)): ?>
            <div class="alert alert-error"><?= $flash_error ?></div>
        <?php endif; ?>

        <!-- SECTION TÉMOIGNAGES -->
        <section class="prestations">
            <h2>Derniers témoignages</h2>
            
            <?php if(empty($commentaires)): ?>
                <div class="no-comments">
                    <p>Aucun témoignage pour le moment. Soyez le premier à laisser votre avis !</p>
                </div>
            <?php else: ?>
                <?php foreach($commentaires as $com): ?>
                    <div class="temoignage-card">
                        <div class="temoignage-header">
                            <span class="temoignage-stars">
                                <?php 
                                for($i = 1; $i <= 5; $i++) {
                                    echo $i <= $com['note'] ? '★' : '☆';
                                }
                                ?>
                            </span>
                            <span class="temoignage-date"><?= htmlspecialchars($com['date_fr']) ?></span>
                        </div>
                        <div class="temoignage-author">
                            <?= htmlspecialchars($com['pseudo']) ?>
                        </div>
                        <div class="temoignage-content">
                            "<?= nl2br(htmlspecialchars($com['commentaire'])) ?>"
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
    
    <?php include "./includes/footer.php"; ?>
    
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script>
        // Gestion des étoiles
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const noteInput = document.getElementById('note-value');
            const ratingText = document.getElementById('ratingText');
            const errorMessage = document.getElementById('errorMessage');
            const form = document.getElementById('commentForm');
            
            let selectedRating = 0;
            
            // Textes selon la note
            const ratingTexts = {
                1: 'Très insatisfait',
                2: 'Insatisfait',
                3: 'Satisfait',
                4: 'Très satisfait',
                5: 'Excellent !'
            };
            
            // Au clic sur une étoile
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedRating = parseInt(this.dataset.rating);
                    noteInput.value = selectedRating;
                    updateStars(selectedRating);
                    ratingText.textContent = ratingTexts[selectedRating];
                    ratingText.style.color = '#28a745';
                    errorMessage.classList.remove('show');
                });
                
                // Survol
                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    updateStars(rating);
                });
            });
            
            // Remettre la sélection au survol
            document.getElementById('starRating').addEventListener('mouseleave', function() {
                updateStars(selectedRating);
            });
            
            // Mise à jour visuelle des étoiles
            function updateStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }
            
            // Validation à la soumission
            form.addEventListener('submit', function(e) {
                if (selectedRating === 0) {
                    e.preventDefault();
                    errorMessage.classList.add('show');
                    ratingText.textContent = 'Veuillez choisir une note';
                    ratingText.style.color = '#dc3545';
                    
                    // Scroll vers les étoiles
                    document.getElementById('starRating').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            });
        });
    </script>
</body>
</html>