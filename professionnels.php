<?php
session_start();
include_once "./includes/connexionbdd.php";
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', './asset/image/galerie/');
}
// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');

// Récupérer uniquement les images de type "domotique"
try {
    $stmt = $connexion->prepare("SELECT * FROM galeries WHERE image_type = :type ORDER BY date_creation DESC");
    $stmt->execute([':type' => 'professionnel']);
    $pro_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur récupération images domotique : " . $e->getMessage());
    $pro_images = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Solutions électriques professionnelles pour entreprises et industriels. Conformité, câblages, automatismes, maintenance machines-outils et installations industrielles.">
    <meta name="keywords" content="électricien professionnel, installation industrielle, câblage informatique, maintenance machines-outils, automatismes, éclairage industriel">
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
    
    <title>Professionnels - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <!-- HERO SECTION -->
        <section class="hero" aria-label="Bannière professionnels">
            <div id="slider" role="region" aria-label="Carrousel d'images">
                <img src="./asset/image/ampoule.jpg" alt="Installation électrique professionnelle moderne">
                <img src="./asset/image/ampoule2.jpg" alt="Tableau électrique industriel">
                <img src="./asset/image/ampoule3.jpg" alt="Système électrique automatisé">
            </div>

            <div class="hero-overlay">
                <h1>Professionnels</h1>
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
            <h2>Solutions électriques pour les professionnels</h2>
            <p>
                Nous intervenons auprès des entreprises et industriels pour garantir la fiabilité, la performance et la conformité de leurs installations électriques.
            </p>
        </section>

        <!-- GALERIE PHOTOS -->
         <section class="gallery" aria-label="Galerie de nos installations domotiques">
            <?php if (empty($pro_images)): ?>
                <p>Aucune image domotique disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($pro_images as $img): ?>
                    <figure class="item">
                        <img src="<?= UPLOAD_DIR . htmlspecialchars($img['filename'], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($img['legende'] ?? 'Installation domotique', ENT_QUOTES, 'UTF-8') ?>"
                            loading="lazy">
                    </figure>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <!-- <section class="gallery" aria-label="Galerie de nos réalisations professionnelles">
            <figure class="item">
                <img src="./asset/image/photos tertiaire/126 De Gaulle (11).JPG" 
                     alt="Installation électrique tertiaire complète" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/photos tertiaire/20240628_193817.jpg" 
                     alt="Câblage réseau professionnel structuré" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/photos tertiaire/20240809_174016.jpg" 
                     alt="Tableau électrique industriel" 
                     loading="lazy">
            </figure>
            <figure class="item">
                <img src="./asset/image/photos tertiaire/IMG_20251008_115159.jpg" 
                     alt="Installation électrique en milieu industriel" 
                     loading="lazy">
            </figure>
        </section> -->

        <!-- SECTION PRÉSENTATION -->
        <section class="presentation">
            <h2>Notre engagement</h2>
            <blockquote cite="">
                "Nous accompagnons entreprises, usines et collectivités avec des solutions conformes et adaptées à leurs besoins spécifiques."
            </blockquote>
        </section>

        <!-- SECTION PRESTATIONS -->
        <section class="prestations" id="prestations">
            <h3>Prestations professionnelles</h3>

            <article class="prestation">
                <h4>Conformité & normes</h4>
                <ul>
                    <li>Réalisation de travaux suivant les rapports APAVE, VERITAS, NORISKO, etc., avec solutions adaptées</li>
                    <li>Mise en conformité des installations existantes</li>
                    <li>Audits électriques et diagnostics de sécurité</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Câblages électriques & informatiques</h4>
                <ul>
                    <li>Pose de prises informatiques RJ45</li>
                    <li>Raccordement des prises à baie de brassage</li>
                    <li>Recette et tests de certification réseau</li>
                    <li>Raccordement d'onduleurs et protection des équipements</li>
                    <li>Câblage structuré VDI (Voix, Données, Images)</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Maintenance machines-outils</h4>
                <ul>
                    <li>Électromécanique sur machines industrielles</li>
                    <li>Dépannage et diagnostic des pannes électriques</li>
                    <li>Maintenance préventive et curative</li>
                    <li>Modernisation d'équipements existants</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Automatismes industriels</h4>
                <ul>
                    <li>Câblages suivant cahiers des charges</li>
                    <li>Installation d'automates programmables (API/PLC)</li>
                    <li>Systèmes de contrôle-commande</li>
                    <li>Interface homme-machine (IHM)</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Alimentation & installation machines</h4>
                <ul>
                    <li>Réalisation et branchement des alimentations électriques</li>
                    <li>Dimensionnement des câbles et protections</li>
                    <li>Mise en service et tests de fonctionnement</li>
                    <li>Documentation technique complète</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Installations électriques industrielles</h4>
                <ul>
                    <li>Pose de chemins de câbles, goulottes et moulures</li>
                    <li>Installation de prises industrielles (mono et triphasé)</li>
                    <li>Interrupteurs, boutons-poussoirs et commandes</li>
                    <li>Systèmes de va-et-vient et télérupteurs</li>
                    <li>Distribution électrique en milieu industriel</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Éclairage industriel</h4>
                <ul>
                    <li>Pose d'éclairage LED haute performance</li>
                    <li>Intervention avec nacelle élévatrice</li>
                    <li>Maintenance préventive sur demande</li>
                    <li>Optimisation énergétique de l'éclairage</li>
                    <li>Éclairage de zones de production et entrepôts</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Éclairage de secours (BAES)</h4>
                <ul>
                    <li>Études et conception de systèmes d'éclairage d'évacuation</li>
                    <li>Pose conforme aux normes de sécurité incendie</li>
                    <li>Entretien réglementaire et vérifications périodiques</li>
                    <li>Remplacement et mise à niveau des installations</li>
                </ul>
            </article>

            <article class="prestation">
                <h4>Tableaux & armoires électriques</h4>
                <ul>
                    <li>Armoires de commande et de distribution</li>
                    <li>Coffrets techniques sur mesure</li>
                    <li>Tableaux divisionnaires et secondaires</li>
                    <li>TGBT (Tableau Général Basse Tension)</li>
                    <li>Conception et réalisation de plans électriques</li>
                    <li>Schémas unifilaires et de câblage détaillés</li>
                    <li>Documentation technique As-Built</li>
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