<?php
session_start();
date_default_timezone_set('Europe/Paris');

// Configuration
define('SITE_TITLE', 'Thierry Decramp - SECIC');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mentions légales du site Thierry Decramp - SECIC - Informations légales et coordonnées">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Mentions légales - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="legal-container">
            <div class="legal-header">
                <h1>Mentions légales</h1>
                <p class="update-date">En vigueur au <?php echo date('d/m/Y'); ?></p>
            </div>
            
            <div class="legal-content">
                <div class="info-box">
                    <strong>ℹ️ Information :</strong> Conformément aux dispositions des articles 6-III et 19 de la loi pour la Confiance dans l'Économie Numérique, nous vous informons de l'identité des différents intervenants dans le cadre de la réalisation et du suivi du site web.
                </div>

                <h2>1. Éditeur du site</h2>
                <p>
                    Le site web <strong><?php echo SITE_TITLE; ?></strong> (accessible à l'adresse <a href="https://www.decramp.fr" target="_blank" rel="noopener">https://www.decramp.fr</a>) est édité par :
                </p>
                
                <div class="info-box">
                    <strong>Raison sociale :</strong> SECIC - Thierry Decramp<br>
                    <strong>Forme juridique :</strong> Entreprise individuelle<br>
                    <strong>Représentant légal :</strong> Thierry Decramp<br>
                    <strong>Adresse du siège social :</strong> 67 rue du Charme, L'Isle-Adam, France<br>
                    <strong>SIRET :</strong> [À compléter]<br>
                    <strong>Email :</strong> <a href="mailto:contact@decramp.fr">contact@decramp.fr</a><br>
                    <strong>Téléphone :</strong> 01 XX XX XX XX<br>
                    <strong>TVA intracommunautaire :</strong> [À compléter si applicable]
                </div>

                <h2>2. Directeur de la publication</h2>
                <p>
                    Le directeur de la publication du site est <strong>Thierry Decramp</strong>, en sa qualité de responsable de l'entreprise.
                </p>
                <p>
                    <strong>Contact :</strong> <a href="mailto:contact@decramp.fr">contact@decramp.fr</a>
                </p>

                <h2>3. Hébergement du site</h2>
                <p>
                    Le site <strong><?php echo SITE_TITLE; ?></strong> est hébergé par :
                </p>
                
                <div class="info-box">
                    <strong>Nom de l'hébergeur :</strong> [Nom de l'hébergeur - ex: OVH, O2Switch, etc.]<br>
                    <strong>Adresse :</strong> [Adresse complète de l'hébergeur]<br>
                    <strong>Téléphone :</strong> [Numéro de téléphone]<br>
                    <strong>Site web :</strong> [URL de l'hébergeur]
                </div>

                <h2>4. Développement et conception technique</h2>
                <h3>4.1 Webmaster et développeur</h3>
                <p>
                    Le site a été développé par <strong>Boukhalfa Camil</strong> dans le cadre d'un projet de formation professionnelle.
                </p>
                <p>
                    <strong>Contact développeur :</strong> [Email développeur si différent]
                </p>

                <h3>4.2 Technologies utilisées</h3>
                <ul>
                    <li><strong>Langages :</strong> HTML5, CSS3, JavaScript, PHP 8</li>
                    <li><strong>Base de données :</strong> MySQL</li>
                    <li><strong>Framework CSS :</strong> Design personnalisé</li>
                    <li><strong>Polices :</strong> Google Fonts (Montserrat, Open Sans)</li>
                    <li><strong>Services tiers :</strong> Google reCAPTCHA v2</li>
                </ul>

                <h2>5. Propriété intellectuelle</h2>
                <h3>5.1 Droits d'auteur</h3>
                <p>
                    L'ensemble du contenu présent sur le site (textes, images, graphismes, logo, icônes, photographies, sons, logiciels, mise en page, structure, etc.) est la propriété exclusive de <strong>SECIC - Thierry Decramp</strong> ou de ses partenaires, sauf mention contraire.
                </p>
                <p>
                    Ces contenus sont protégés par le Code de la Propriété Intellectuelle et par les conventions internationales relatives au droit d'auteur et aux droits voisins.
                </p>

                <h3>5.2 Reproduction interdite</h3>
                <p>
                    Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est strictement interdite sans l'autorisation écrite préalable de l'éditeur du site.
                </p>
                <p>
                    Toute exploitation non autorisée du site ou de l'un de ses éléments constitue une contrefaçon et est passible de sanctions civiles et pénales prévues par le Code de la Propriété Intellectuelle.
                </p>

                <h3>5.3 Marques et logos</h3>
                <p>
                    Les marques, logos, signes et tout autre contenu du site font l'objet d'une protection par le Code de la propriété intellectuelle.
                </p>
                <p>
                    Toute reproduction totale ou partielle de ces marques ou de ces logos, effectuée à partir des éléments du site sans l'autorisation expresse de l'éditeur est donc prohibée.
                </p>

                <h2>6. Protection des données personnelles</h2>
                <h3>6.1 Responsable du traitement</h3>
                <p>
                    Le responsable du traitement des données personnelles collectées sur le site est :
                </p>
                <ul>
                    <li><strong>Nom :</strong> Thierry Decramp</li>
                    <li><strong>Email :</strong> contact@decramp.fr</li>
                    <li><strong>Adresse :</strong> 67 rue du Charme, L'Isle-Adam, France</li>
                </ul>

                <h3>6.2 Délégué à la Protection des Données (DPO)</h3>
                <p>
                    Pour toute question relative à la protection de vos données personnelles, vous pouvez contacter :
                </p>
                <ul>
                    <li><strong>Email :</strong> contact@decramp.fr</li>
                    <li><strong>Objet du message :</strong> "Protection des données - RGPD"</li>
                </ul>

                <h3>6.3 Politique de confidentialité</h3>
                <p>
                    Pour plus d'informations sur la collecte, l'utilisation et la protection de vos données personnelles, veuillez consulter notre 
                    <a href="politique_confidentialite.php" style="color: #004080; text-decoration: underline; font-weight: 600;">Politique de Confidentialité</a>.
                </p>

                <h2>8. Liens hypertextes</h2>
                <h3>8.1 Liens sortants</h3>
                <p>
                    Le site peut contenir des liens hypertextes vers d'autres sites web (notamment vers les sites de nos partenaires). L'éditeur du site n'exerce aucun contrôle sur le contenu de ces sites tiers et décline toute responsabilité quant à leur contenu.
                </p>
                <p>
                    La présence de liens vers d'autres sites ne constitue en aucun cas une validation de ces sites ou de leur contenu par l'éditeur.
                </p>

                <h3>8.2 Liens entrants</h3>
                <p>
                    Tout lien hypertexte pointant vers le site <strong><?php echo SITE_TITLE; ?></strong> doit faire l'objet d'une autorisation préalable de l'éditeur. Cette autorisation ne sera en aucun cas accordée aux sites :
                </p>
                <ul>
                    <li>Diffusant des informations à caractère illégal, violent, raciste, xénophobe ou discriminatoire</li>
                    <li>Proposant du contenu pornographique ou pédopornographique</li>
                    <li>Incitant à la commission de crimes ou délits</li>
                    <li>Portant atteinte à l'image, à la réputation ou à l'honneur de l'éditeur du site</li>
                </ul>

                <h2>9. Responsabilité et garanties</h2>
                <h3>9.1 Contenu du site</h3>
                <p>
                    L'éditeur s'efforce d'assurer au mieux l'exactitude et la mise à jour des informations diffusées sur ce site. Toutefois, il ne peut garantir l'exactitude, la précision ou l'exhaustivité des informations mises à disposition sur le site.
                </p>
                <p>
                    En conséquence, l'éditeur décline toute responsabilité :
                </p>
                <ul>
                    <li>Pour toute imprécision, inexactitude ou omission portant sur des informations disponibles sur le site</li>
                    <li>Pour tous dommages directs ou indirects résultant de l'utilisation du site ou de l'impossibilité d'y accéder</li>
                    <li>Pour tout dysfonctionnement, interruption ou suspension du site</li>
                </ul>

                <h3>9.2 Virus et sécurité</h3>
                <p>
                    L'éditeur met en œuvre tous les moyens nécessaires pour assurer la sécurité du site et protéger les données des utilisateurs. Toutefois, il ne peut garantir une sécurité absolue.
                </p>
                <p>
                    L'utilisateur est responsable de la protection de ses propres équipements contre tout virus, malware ou autre programme nuisible. L'éditeur ne saurait être tenu responsable des dommages causés à l'équipement informatique de l'utilisateur ou de la perte de données consécutive à l'utilisation du site.
                </p>

                <h3>9.3 Disponibilité du site</h3>
                <p>
                    L'éditeur s'efforce de maintenir le site accessible 24h/24 et 7j/7. Toutefois, il se réserve le droit d'interrompre l'accès au site pour des raisons de maintenance, de mise à jour ou pour toute autre raison technique, et ce sans préavis ni justification.
                </p>
                <p>
                    L'éditeur ne saurait être tenu responsable des interruptions de service et de leurs conséquences.
                </p>

                <h2>10. Droit applicable et juridiction compétente</h2>
                <h3>10.1 Loi applicable</h3>
                <p>
                    Les présentes mentions légales sont régies par le droit français. L'utilisation du site implique l'acceptation des présentes mentions légales.
                </p>

                <h3>10.2 Tribunaux compétents</h3>
                <p>
                    En cas de litige relatif à l'utilisation du site ou à l'interprétation des présentes mentions légales, et à défaut de résolution amiable, les tribunaux français seront seuls compétents pour en connaître.
                </p>
                <p>
                    Pour les litiges relatifs aux relations contractuelles, le tribunal compétent sera celui du ressort du siège social de l'entreprise ou celui du domicile de l'utilisateur, conformément aux règles de compétence territoriale en vigueur.
                </p>

                <h3>10.3 Médiation</h3>
                <p>
                    Conformément à l'article L.612-1 du Code de la consommation, l'utilisateur consommateur peut recourir gratuitement à un médiateur de la consommation en cas de litige non résolu.
                </p>
                <p>
                    <strong>Coordonnées du médiateur :</strong> [À compléter selon votre secteur d'activité]
                </p>

                <h2>11. Crédits</h2>
                <h3>11.1 Conception et développement</h3>
                <ul>
                    <li><strong>Développement web :</strong> Boukhalfa Camil</li>
                    <li><strong>Maquettage et design :</strong> Figma</li>
                    <li><strong>Photographies :</strong> © SECIC - Thierry Decramp (sauf mention contraire)</li>
                </ul>

                <h3>11.2 Ressources tierces</h3>
                <ul>
                    <li><strong>Polices :</strong> Google Fonts (Montserrat, Open Sans) - Licence Open Font</li>
                    <li><strong>Icônes :</strong> [À compléter si utilisation d'une bibliothèque d'icônes]</li>
                    <li><strong>reCAPTCHA :</strong> Google Inc. - <a href="https://www.google.com/recaptcha" target="_blank" rel="noopener">https://www.google.com/recaptcha</a></li>
                </ul>

                <h2>12. Signalement de contenu illicite</h2>
                <p>
                    Conformément à la loi pour la Confiance dans l'Économie Numérique (LCEN), tout utilisateur peut signaler à l'éditeur du site la présence de contenus illicites ou contraires aux présentes mentions légales.
                </p>
                <p>
                    Pour effectuer un signalement, merci de nous contacter à l'adresse suivante : <a href="mailto:contact@decramp.fr">contact@decramp.fr</a> en précisant :
                </p>
                <ul>
                    <li>Vos coordonnées (nom, prénom, email)</li>
                    <li>La description précise du contenu litigieux</li>
                    <li>La localisation exacte du contenu (URL de la page)</li>
                    <li>Les motifs justifiant le retrait du contenu</li>
                </ul>

                <h2>13. Modification des mentions légales</h2>
                <p>
                    L'éditeur se réserve le droit de modifier à tout moment les présentes mentions légales. Les modifications entreront en vigueur dès leur publication sur le site.
                </p>
                <p>
                    Il appartient à l'utilisateur de consulter régulièrement les mentions légales afin de prendre connaissance des éventuelles modifications.
                </p>

                <h2>14. Accessibilité</h2>
                <p>
                    L'éditeur s'engage à rendre son site accessible conformément aux standards du Référentiel Général d'Amélioration de l'Accessibilité (RGAA).
                </p>
                <p>
                    Si vous rencontrez des difficultés d'accès au site ou à certains contenus, n'hésitez pas à nous contacter à l'adresse : <a href="mailto:contact@decramp.fr">contact@decramp.fr</a>
                </p>

                <div class="contact-box">
                    <h3>📧 Une question sur nos mentions légales ?</h3>
                    <p>Pour toute question concernant ces mentions légales, vous pouvez nous contacter :</p>
                    <p>
                        <strong>Email :</strong> <a href="mailto:contact@decramp.fr">contact@decramp.fr</a><br>
                        <strong>Téléphone :</strong> 01 XX XX XX XX<br>
                        <strong>Courrier :</strong> 67 rue du Charme, L'Isle-Adam, France
                    </p>
                </div>

                <div class="info-box">
                    <strong>📚 Documents complémentaires :</strong><br>
                    • <a href="cgu.php" style="color: #004080; text-decoration: underline;">Conditions Générales d'Utilisation (CGU)</a><br>
                    • <a href="politique-confidentialite.php" style="color: #004080; text-decoration: underline;">Politique de Confidentialité</a>
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <a href="index.php" class="back-link">← Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </main>

    <?php include "./includes/footer.php"; ?>

    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>