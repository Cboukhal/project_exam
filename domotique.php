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
    <meta name="description" content="Installation domotique complète : gestion intelligente de l'éclairage, chauffage, volets, sécurité. Contrôle à distance via smartphone. Maison connectée et intelligente.">
    <meta name="keywords" content="domotique, maison connectée, maison intelligente, automatisation maison, contrôle smartphone, éclairage intelligent, chauffage connecté">
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
    
    <title>Domotique - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <!-- HERO SECTION -->
        <section class="hero" aria-label="Bannière domotique">
            <div id="slider" role="region" aria-label="Carrousel d'images">
                <img src="./asset/image/ampoule.jpg" alt="Système domotique intelligent pour maison connectée">
                <img src="./asset/image/ampoule2.jpg" alt="Contrôle domotique centralisé">
                <img src="./asset/image/ampoule3.jpg" alt="Automatisation résidentielle moderne">
            </div>

            <div class="hero-overlay">
                <h1>Domotique</h1>
                <p class="hero-subtitle"><?php echo SITE_TITLE; ?> - Artisan électricien</p>
                <p>Électricien depuis plus de 15 ans, spécialisé dans les nouvelles technologies et respectueux des normes.</p>
                <a href="contact.php" class="btn" aria-label="Accéder à la page contact">Nous contacter</a>
            </div>

            <!-- Navigation du carrousel -->
            <div class="hero-dots" role="navigation" aria-label="Navigation du carrousel">
                <button class="dot active" data-index="0" aria-label="Image 1" aria-current="true"></button>
                <button class="dot" data-index="1" aria-label="Image 2"></button>
                <button class="dot" data-index="2" aria-label="Image 3"></button>
            </div>
        </section>

        <!-- SECTION INTRO -->
        <section class="intro">
            <h2>Votre maison, plus intelligente</h2>
            <p>
                Installation et paramétrage de solutions domotiques complètes : gestion automatisée de l'éclairage, des volets roulants, du chauffage, de la sécurité et supervision à distance via smartphone ou tablette. Profitez d'un confort optimal et d'économies d'énergie.
            </p>
        </section>

        <!-- GALERIE PHOTOS -->
        <section class="gallery" aria-label="Galerie de nos installations domotiques">
            <figure class="item">
                <img src="./asset/image/domotique/20151204_175036.jpg" 
                     alt="Centrale domotique avec écran de contrôle tactile" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/domotique/20240323_123545.jpg" 
                     alt="Installation domotique moderne pour maison connectée" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/domotique/P_20171213_153435.jpg" 
                     alt="Système de contrôle domotique centralisé" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/domotique/SHAW (62).JPG" 
                     alt="Tableau de commande domotique professionnel" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/domotique/synergieTebis.jpg" 
                     alt="Système Tebis pour automatisation complète de la maison" 
                     loading="lazy">
            </figure>
        </section>

        <!-- SECTION PRÉSENTATION -->
        <section class="presentation">
            <h2>Notre engagement</h2>
            <blockquote cite="">
                "Des solutions domotiques fiables, simples à utiliser et parfaitement adaptées à votre mode de vie pour un confort optimal au quotidien."
            </blockquote>
        </section>

        <!-- SECTION PRESTATIONS -->
        <section class="prestations" id="prestations">
            <h3>Nos prestations domotique</h3>

            <article class="prestation">
                <h4>Gestion intelligente de l'éclairage</h4>
                <ul>
                    <li>Éclairage automatique avec détecteurs de présence</li>
                    <li>Scénarios personnalisés (réveil, soirée, absence, etc.)</li>
                    <li>Variation d'intensité lumineuse programmable</li>
                    <li>Commandes centralisées et à distance via application mobile</li>
                    <li>Économies d'énergie jusqu'à 30%</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Chauffage intelligent & économies d'énergie</h4>
                <ul>
                    <li>Régulation automatique selon la température ambiante</li>
                    <li>Programmation par zones et par horaires</li>
                    <li>Détection de fenêtre ouverte pour éviter le gaspillage</li>
                    <li>Pilotage à distance et ajustement en temps réel</li>
                    <li>Optimisation énergétique pour réduire vos factures de 20 à 30%</li>
                    <li>Statistiques de consommation détaillées</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Volets roulants & stores automatisés</h4>
                <ul>
                    <li>Ouverture et fermeture programmées selon vos horaires</li>
                    <li>Détection solaire pour gérer l'apport thermique naturel</li>
                    <li>Simulation de présence pendant vos absences</li>
                    <li>Commande centralisée de tous les volets</li>
                    <li>Protection automatique contre les intempéries</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Sécurité connectée & surveillance</h4>
                <ul>
                    <li>Caméras IP haute définition avec vision nocturne</li>
                    <li>Alarmes anti-intrusion intelligentes</li>
                    <li>Capteurs de mouvement, d'ouverture, de fumée et de fuite d'eau</li>
                    <li>Contrôle en temps réel depuis votre smartphone</li>
                    <li>Notifications instantanées en cas d'alerte</li>
                    <li>Enregistrement vidéo et historique des événements</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Scénarios & automatisations personnalisés</h4>
                <ul>
                    <li>Scénario "Départ" : extinction automatique de tout l'équipement</li>
                    <li>Scénario "Retour" : éclairage et chauffage activés avant votre arrivée</li>
                    <li>Scénario "Nuit" : fermeture des volets, baisse du chauffage, désactivation partielle alarme</li>
                    <li>Scénario "Cinéma" : éclairage tamisé, volets fermés, ambiance optimale</li>
                    <li>Création de scénarios sur mesure selon vos habitudes</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Contrôle centralisé & commande vocale</h4>
                <ul>
                    <li>Application mobile intuitive (iOS et Android)</li>
                    <li>Interfaces murales tactiles au design moderne</li>
                    <li>Commande vocale (Google Home, Amazon Alexa, Apple HomeKit)</li>
                    <li>Télécommandes universelles programmables</li>
                    <li>Accès à distance sécurisé depuis n'importe où dans le monde</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Installation & accompagnement complet</h4>
                <ul>
                    <li>Étude personnalisée gratuite de vos besoins</li>
                    <li>Conseil sur le choix des équipements adaptés à votre budget</li>
                    <li>Installation professionnelle et discrète</li>
                    <li>Paramétrage et programmation complète du système</li>
                    <li>Formation détaillée à l'utilisation</li>
                    <li>Support technique et possibilité d'évolutions futures</li>
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