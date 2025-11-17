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
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/favicon" href="./asset/image/OIP.webp">
    <title>Profil utilisateur</title>
</head>
<body>
    <?php
        include "./includes/header.php";
    ?>
    <main>
    <?php
        //  if ($admin)
        // {
        //     // Affichage du nom dans une balise <h1>
        //     echo "<h1>Bienvenu " . htmlspecialchars($admin['prenom']) . " " . htmlspecialchars($admin['nom']) . "</h1>";
        // }
        // else
        // {
        //         echo "<h1>Admin non trouvé</h1>";
        // }
    ?>
     <!-- SECTION FORMULAIRE -->
        <section class="contact">
            <h2>Laissez votre avis</h2>
            <div class="contact-wrapper">
                <div class="contact-form">
                    <form id="commentForm" action="./commentaire.php" method="post">
                        <label for="nom">Nom (optionnel) :</label>
                        <input type="text" id="nom" name="nom" placeholder="Votre nom (ou restez anonyme)">
                        
                        <label for="email">Email <span style="color:red;">*</span> :</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com">
                        
                        <label for="note">Note <span style="color:red;">*</span> :</label>
                        <div class="stars-container">
                            <div class="stars" id="starRating">
                                <span class="star" data-rating="1">★</span>
                                <span class="star" data-rating="2">★</span>
                                <span class="star" data-rating="3">★</span>
                                <span class="star" data-rating="4">★</span>
                                <span class="star" data-rating="5">★</span>
                            </div>
                            <span class="rating-text" id="ratingText">Choisissez une note</span>
                        </div>
                        <input type="hidden" id="note-value" name="note" value="0" required>
                        <span class="error-message" id="errorMessage">Veuillez sélectionner une note</span>
                        
                        <label for="commentaire">Commentaire <span style="color:red;">*</span> :</label>
                        <textarea id="commentaire" name="commentaire" required placeholder="Partagez votre expérience..." rows="5"></textarea>
                        
                        <button type="submit" name="envoyer_commentaire" value="1" class="btn">Envoyer mon avis</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <?php
        include "./includes/footer.php";
    ?>  
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>