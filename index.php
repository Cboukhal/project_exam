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
    
    <title>Accueil - <?php echo SITE_TITLE; ?></title>
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

        <!-- SECTION PARTICULIERS -->
        <section id="particuliers" class="service-section">
            <h2>Particuliers</h2>
            <div class="section-content">
                <p>Mise en conformité installation, tableaux électriques, chauffage électrique, dépannages, domotique, alarme et contrôle d'accès...</p>
                
                <div class="gallery">
                    <figure class="item">
                        <img src="./asset/image/particuliers/20220323_182957.jpg" 
                             alt="Installation électrique résidentielle moderne"
                             loading="lazy">
                    </figure>
                    <figure class="item">
                        <img src="./asset/image/particuliers/20191209_130940.jpg" 
                             alt="Tableau électrique aux normes"
                             loading="lazy">
                    </figure>
                    <figure class="item">
                        <img src="./asset/image/particuliers/20140314_161634.jpg" 
                             alt="Système de chauffage électrique"
                             loading="lazy">
                    </figure>
                </div>
                
                <a href="particuliers.php" class="btn">En savoir plus</a>
            </div>
        </section>

        <!-- SECTION PROFESSIONNELS -->
        <section id="professionnels" class="service-section">
            <h2>Professionnels</h2>
            <div class="section-content">
                <p>Réalisation de travaux suivant les rapports de conformité, câblages électriques et informatiques, maintenance machines-outils, câblages pour automatismes, alimentations électriques, installations électriques dans les usines, appareils d'éclairage...</p>
                
                <div class="gallery">
                    <figure class="item">
                        <img src="./asset/image/photos tertiaire/126 De Gaulle (11).JPG" 
                             alt="Installation électrique tertiaire professionnelle"
                             loading="lazy">
                        <figcaption>Installation tertiaire</figcaption>
                    </figure>
                    <figure class="item">
                        <img src="./asset/image/photos tertiaire/20240628_193817.jpg" 
                             alt="Câblage réseau professionnel"
                             loading="lazy">
                        <figcaption>Câblage réseau</figcaption>
                    </figure>
                    <figure class="item">
                        <img src="./asset/image/photos tertiaire/20240809_174016.jpg" 
                             alt="Maintenance industrielle"
                             loading="lazy">
                        <figcaption>Maintenance industrielle</figcaption>
                    </figure>
                </div>
                
                <a href="professionnels.php" class="btn">En savoir plus</a>
            </div>
        </section>

        <!-- SECTION DOMOTIQUE -->
        <section id="domotique" class="service-section">
            <h2>Domotique</h2>
            <div class="section-content">
                <p>Réalisation complète de vos installations domotiques : programmation d'automatismes, gestion intelligente de l'énergie, intégration de capteurs et pilotage centralisé.</p>
                
                <div class="gallery">
                    <figure class="item">
                        <img src="./asset/image/domotique/P_20171213_153435.jpg" 
                             alt="Système domotique centralisé"
                             loading="lazy">
                        <figcaption>Pilotage centralisé</figcaption>
                    </figure>
                    <figure class="item">
                        <img src="./asset/image/domotique/20240323_123545.jpg" 
                             alt="Installation domotique moderne"
                             loading="lazy">
                        <figcaption>Installation intelligente</figcaption>
                    </figure>
                    <figure class="item">
                        <img src="./asset/image/domotique/P_20171213_153435.jpg" 
                             alt="Automatisation résidentielle"
                             loading="lazy">
                        <figcaption>Automatisation</figcaption>
                    </figure>
                </div>
                
                <a href="domotique.php" class="btn">En savoir plus</a>
            </div>
        </section>

        <!-- SECTION CONTACT -->
        <section id="contact" class="contact-section">
            <h2>Contact</h2>
            <div class="contact">
                <div class="contact-wrapper">
                    <div class="contact-form">
                        <p><strong>Adresse :</strong> 67 rue du Charme</p>
                        <p><strong>Téléphone :</strong> <a href="tel:+33XXXXXXXXX">01 XX XX XX</a></p>
                        
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
                               maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="Email *" 
                               required 
                               aria-required="true"
                               maxlength="150">
                    </div>

                    <div class="form-group">
                        <label for="sujet" class="sr-only">Sujet</label>
                        <input type="text" 
                               id="sujet" 
                               name="sujet" 
                               placeholder="Sujet *" 
                               required 
                               aria-required="true"
                               maxlength="200">
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
                                  rows="5"></textarea>
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