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
    <meta name="description" content="Découvrez nos partenaires professionnels : Qualifelec, Promotelec, FFD Domotique. Certifications et organismes de référence du secteur électrique.">
    <meta name="keywords" content="partenaires électriciens, Qualifelec, Promotelec, FFD Domotique, certification électrique, organismes professionnels">
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
    
    <title>Liens et Partenaires - <?php echo SITE_TITLE; ?></title>
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
                <h1>Accueil</h1>
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

        <!-- SECTION INTRODUCTION -->
        <section class="presentation">
            <h2>Nos références et partenaires</h2>
            <blockquote cite="">
                "Découvrez les organismes professionnels et partenaires qui garantissent la qualité de nos prestations et notre expertise dans le domaine de l'électricité."
            </blockquote>
        </section>

        <!-- SECTION ORGANISMES PROFESSIONNELS -->
        <section class="prestations" id="organismes">
            <h2>Organismes professionnels et certifications</h2>

            <article class="prestation">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Qualifelec
                </h4>
                <p>
                    Organisme de qualification des entreprises d'électricité et d'énergie. Qualifelec atteste des compétences techniques et de la régularité administrative des entreprises du secteur électrique. Cette qualification est un gage de qualité et de sécurité pour tous vos travaux d'installation électrique et de rénovation.
                </p>
                <a href="https://www.qualifelec.fr/" 
                   class="btn" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="Visiter le site de Qualifelec (ouvre dans un nouvel onglet)">
                    Visiter le site
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

            <article class="prestation">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Promotelec
                </h4>
                <p>
                    Association créée en 1962 pour promouvoir la sécurité, la qualité et le confort des installations électriques dans le bâtiment résidentiel. Promotelec regroupe tous les acteurs du secteur : constructeurs, électriciens, bureaux d'études. Elle définit les labels et certifications garantissant des installations électriques performantes et sûres.
                </p>
                <a href="https://www.promotelec.com/" 
                   class="btn" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="Visiter le site de Promotelec (ouvre dans un nouvel onglet)">
                    Visiter le site
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

            <article class="prestation">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Fédération Française de Domotique (FFD)
                </h4>
                <p>
                    Fédération à but non lucratif dédiée au développement de la domotique et de la maison connectée en France. Elle s'adresse à tous les acteurs professionnels : électricité, énergie, sécurité, télécommunications, automatisme, etc. La FFD promeut les bonnes pratiques et l'innovation dans le domaine de la maison intelligente.
                </p>
                <a href="https://www.ffdomotique.org/" 
                   class="btn" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="Visiter le site de la FFD (ouvre dans un nouvel onglet)">
                    Visiter le site
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

            <article class="prestation">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Consuel
                </h4>
                <p>
                    Comité National pour la Sécurité des Usagers de l'Électricité. Organisme indépendant chargé de vérifier la conformité des installations électriques neuves ou rénovées aux normes de sécurité en vigueur (NF C 15-100). L'attestation Consuel est obligatoire pour le raccordement au réseau électrique.
                </p>
                <a href="https://www.consuel.com/" 
                   class="btn" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="Visiter le site du Consuel (ouvre dans un nouvel onglet)">
                    Visiter le site
                    <span aria-hidden="true">↗</span>
                </a>
            </article>
        </section>


    <?php include "./includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>