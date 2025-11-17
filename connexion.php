<?php
session_start();

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');
define('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
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
    </main>

    <?php include "./includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>