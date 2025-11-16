<?php
session_start();

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Services électriques pour particuliers : mise en conformité, tableaux électriques, chauffage, dépannages, domotique, alarmes. Installation sécurisée et conforme aux normes.">
    <meta name="keywords" content="électricien particulier, mise en conformité électrique, dépannage électrique, chauffage électrique, alarme maison, domotique résidentielle">
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
    
    <title>Particuliers - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <!-- HERO SECTION -->
        <section class="hero" aria-label="Bannière particuliers">
            <div id="slider" role="region" aria-label="Carrousel d'images">
                <img src="./asset/image/ampoule.jpg" alt="Installation électrique domestique moderne">
                <img src="./asset/image/ampoule2.jpg" alt="Tableau électrique résidentiel">
                <img src="./asset/image/ampoule3.jpg" alt="Éclairage intelligent pour particuliers">
            </div>

            <div class="hero-overlay">
                <h1>Particuliers</h1>
                <p class="hero-subtitle"><?php echo SITE_TITLE; ?> - Artisan électricien</p>
                <p>Électricien depuis plus de 15 ans, spécialisé dans les nouvelles technologies et respectueux des normes.</p>
                <a href="contact.php" class="btn" aria-label="Accéder à la page contact">Nous contacter</a>
            </div>

            <!-- Navigation du carrousel -->
            <div class="hero-dots" role="navigation" aria-label="Navigation du carrousel">
                <span class="dot active" data-index="0" aria-label="Image 1" aria-current="true"></span>
                <span class="dot" data-index="1" aria-label="Image 2"></span>
                <span class="dot" data-index="2" aria-label="Image 3"></span>
            </div>
        </section>

        <!-- SECTION INTRO -->
        <section class="intro">
            <h2>Votre sécurité, notre priorité</h2>
            <p>
                Nous vous conseillons et assurons la sécurité de vos installations électriques domestiques, avec des solutions adaptées à votre habitat et conformes aux normes en vigueur.
            </p>
        </section>

        <!-- GALERIE PHOTOS -->
        <section class="gallery" aria-label="Galerie de nos réalisations pour particuliers">
            <figure class="item">
                <img src="./asset/image/particuliers/20140307_171332.jpg" 
                     alt="Installation électrique résidentielle" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/20140314_161634.jpg" 
                     alt="Chauffage électrique moderne" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/20170713_161151.jpg" 
                     alt="Tableau électrique domestique" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/20191209_130940.jpg" 
                     alt="Mise en conformité électrique" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/20220323_182957.jpg" 
                     alt="Installation domotique résidentielle" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/20220711_100204.jpg" 
                     alt="Rénovation électrique complète" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/IMG-20240710-WA0000.jpg" 
                     alt="Éclairage intérieur moderne" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/particuliers/P_20180417_121552.jpg" 
                     alt="Système de sécurité électrique" 
                     loading="lazy">
            </figure>
        </section>

        <!-- SECTION PRÉSENTATION -->
        <section class="presentation">
            <h2>Notre engagement</h2>
            <blockquote cite="">
                "Des interventions fiables, sécurisées et conformes aux normes en vigueur pour votre tranquillité d'esprit."
            </blockquote>
        </section>

        <!-- SECTION PRESTATIONS -->
        <section class="prestations" id="prestations">
            <h3>Nos prestations pour particuliers</h3>

            <article class="prestation">
                <h4>Réhabilitation & mise en conformité</h4>
                <ul>
                    <li>Diagnostic complet de votre installation électrique</li>
                    <li>Conseils et solutions conformes aux normes NF C 15-100</li>
                    <li>Garanties NF et label PROMOTELEC</li>
                    <li>Mise aux normes pour la vente ou la location</li>
                    <li>Attestation de conformité Consuel</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Rénovation tableaux & circuits électriques</h4>
                <ul>
                    <li>Remplacement de tableaux électriques vétustes</li>
                    <li>Installation de disjoncteurs différentiels et divisionnaires</li>
                    <li>Circuits encastrés, semi-encastrés ou apparents</li>
                    <li>Ajout de prises et interrupteurs</li>
                    <li>Réorganisation et étiquetage du tableau</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Chauffage électrique & ventilation</h4>
                <ul>
                    <li>Études et conseil pour optimiser votre chauffage</li>
                    <li>Installation de radiateurs électriques performants</li>
                    <li>Planchers chauffants électriques</li>
                    <li>VMC simple ou double flux adaptée à vos besoins</li>
                    <li>Programmation et régulation thermique</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Éclairage & solutions domotiques</h4>
                <ul>
                    <li>Gestion automatisée de l'éclairage intérieur et extérieur</li>
                    <li>Installation de variateurs et détecteurs de présence</li>
                    <li>Éclairage LED basse consommation</li>
                    <li>Solutions domotiques simples et efficaces</li>
                    <li>Contrôle à distance via smartphone</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Dépannages d'urgence</h4>
                <ul>
                    <li>Recherche de panne et diagnostic précis</li>
                    <li>Réparation de court-circuit partiel ou total</li>
                    <li>Résolution de défauts d'isolement</li>
                    <li>Remplacement d'équipements défectueux</li>
                    <li>Intervention rapide 7j/7</li>
                    <li>Conseil personnalisé et devis gratuit</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Alarme & contrôle d'accès</h4>
                <ul>
                    <li>Étude de sécurité adaptée à votre logement</li>
                    <li>Installation d'alarmes anti-intrusion</li>
                    <li>Systèmes de vidéosurveillance IP</li>
                    <li>Interphones et visiophones</li>
                    <li>Contrôle d'accès (badges, digicodes)</li>
                    <li>Mise en service et formation à l'utilisation</li>
                </ul>
            </article>
        </section>

    </main>

    <?php include "./includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>