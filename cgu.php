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
    <meta name="description" content="Conditions Générales d'Utilisation du site Thierry Decramp - SECIC">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>CGU - <?php echo SITE_TITLE; ?></title>
    
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="legal-container">
            <div class="legal-header">
                <h1>Conditions Générales d'Utilisation</h1>
                <p class="update-date">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
            </div>
            
            <div class="legal-content">
                <div class="info-box">
                    <strong>📌 Important :</strong> En accédant et en utilisant ce site, vous acceptez sans réserve les présentes Conditions Générales d'Utilisation (CGU). Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser ce site.
                </div>

                <h2>1. Présentation du site</h2>
                <h3>1.1 Informations légales</h3>
                <p>
                    Le présent site web est édité par :
                </p>
                <ul>
                    <li><strong>Raison sociale :</strong> SECIC - Thierry Decramp</li>
                    <li><strong>Forme juridique :</strong> Entreprise individuelle</li>
                    <li><strong>Adresse :</strong> 67 rue du Charme, L'Isle-Adam, France</li>
                    <li><strong>Email :</strong> contact@decramp.fr</li>
                    <li><strong>Téléphone :</strong> 01 XX XX XX XX</li>
                    <li><strong>SIRET :</strong> [À compléter]</li>
                </ul>

                <h3>1.2 Hébergeur du site</h3>
                <p>
                    Le site est hébergé par :
                </p>
                <ul>
                    <li><strong>Nom :</strong> [Nom de l'hébergeur]</li>
                    <li><strong>Adresse :</strong> [Adresse de l'hébergeur]</li>
                    <li><strong>Téléphone :</strong> [Téléphone de l'hébergeur]</li>
                </ul>

                <h2>2. Objet du site</h2>
                <p>
                    Le site <strong><?php echo SITE_TITLE; ?></strong> a pour objet de présenter les services proposés par l'entreprise dans le domaine de l'électricité (installations pour particuliers, professionnels et domotique), de permettre aux visiteurs de :
                </p>
                <ul>
                    <li>Consulter les informations sur les services proposés</li>
                    <li>Visualiser des galeries photos de réalisations</li>
                    <li>Contacter l'entreprise via un formulaire de contact</li>
                    <li>Créer un compte utilisateur pour accéder à des fonctionnalités supplémentaires</li>
                    <li>Demander des devis personnalisés (utilisateurs inscrits)</li>
                    <li>Déposer des avis sur les prestations (utilisateurs inscrits)</li>
                </ul>

                <h2>3. Accès au site</h2>
                <h3>3.1 Conditions d'accès</h3>
                <p>
                    L'accès au site est gratuit et ouvert à tous les internautes. Certaines fonctionnalités nécessitent la création d'un compte utilisateur (demande de devis, dépôt d'avis).
                </p>

                <h3>3.2 Disponibilité du site</h3>
                <p>
                    Nous nous efforçons de maintenir le site accessible 24h/24 et 7j/7. Toutefois, l'accès au site peut être temporairement suspendu pour des raisons de maintenance, de mises à jour, ou en cas de force majeure. Nous ne saurions être tenus responsables des interruptions de service et de leurs conséquences.
                </p>

                <h3>3.3 Modification du site</h3>
                <p>
                    Nous nous réservons le droit de modifier, suspendre ou interrompre tout ou partie du site, de ses fonctionnalités ou de son contenu, sans préavis et sans obligation de justification.
                </p>

                <h2>4. Création et gestion du compte utilisateur</h2>
                <h3>4.1 Inscription</h3>
                <p>
                    Pour accéder à certaines fonctionnalités (demande de devis, dépôt d'avis), l'utilisateur doit créer un compte en fournissant des informations exactes et à jour :
                </p>
                <ul>
                    <li>Civilité (M., Mme, Mx)</li>
                    <li>Prénom et nom</li>
                    <li>Adresse email valide</li>
                    <li>Mot de passe sécurisé (minimum 8 caractères)</li>
                </ul>

                <div class="warning-box">
                    <strong>⚠️ Responsabilité de l'utilisateur :</strong> L'utilisateur est seul responsable de la confidentialité de ses identifiants de connexion. Toute utilisation du compte avec ces identifiants sera présumée émaner de l'utilisateur.
                </div>

                <h3>4.2 Véracité des informations</h3>
                <p>
                    L'utilisateur s'engage à fournir des informations exactes, complètes et à jour. En cas de modification de ses informations personnelles, il s'engage à les mettre à jour via son espace personnel.
                </p>

                <h3>4.3 Suppression du compte</h3>
                <p>
                    L'utilisateur peut à tout moment supprimer son compte depuis son espace personnel. Cette action est définitive et irréversible. Toutes les données associées au compte seront supprimées conformément à notre politique de confidentialité.
                </p>

                <h2>5. Utilisation du site</h2>
                <h3>5.1 Usage autorisé</h3>
                <p>
                    Le site est destiné à un usage personnel et non commercial. L'utilisateur s'engage à utiliser le site de manière loyale et conforme à sa destination.
                </p>

                <h3>5.2 Interdictions</h3>
                <p>
                    Il est strictement interdit :
                </p>
                <ul>
                    <li>D'utiliser le site à des fins illégales ou frauduleuses</li>
                    <li>De porter atteinte aux droits de propriété intellectuelle du site</li>
                    <li>De tenter d'accéder de manière non autorisée au système informatique</li>
                    <li>De diffuser des virus, malwares ou tout code malveillant</li>
                    <li>De publier des contenus injurieux, diffamatoires, racistes ou contraires aux bonnes mœurs</li>
                    <li>D'usurper l'identité d'autrui</li>
                    <li>De collecter des données personnelles d'autres utilisateurs</li>
                    <li>D'envoyer des spams ou du contenu publicitaire non sollicité</li>
                </ul>

                <h3>5.3 Sanctions</h3>
                <p>
                    En cas de non-respect de ces CGU, nous nous réservons le droit de suspendre ou de supprimer définitivement le compte de l'utilisateur, sans préavis ni indemnité.
                </p>

                <h2>6. Contenus publiés par les utilisateurs</h2>
                <h3>6.1 Commentaires et avis</h3>
                <p>
                    Les utilisateurs inscrits peuvent déposer des avis sur les services de l'entreprise. Ces avis sont soumis à modération avant publication. Nous nous réservons le droit de refuser ou de supprimer tout avis qui :
                </p>
                <ul>
                    <li>Ne respecte pas les règles de courtoisie</li>
                    <li>Contient des propos injurieux, diffamatoires ou discriminatoires</li>
                    <li>N'a pas de rapport avec les services proposés</li>
                    <li>Contient des informations personnelles ou confidentielles</li>
                </ul>

                <h3>6.2 Responsabilité de l'utilisateur</h3>
                <p>
                    L'utilisateur est seul responsable du contenu qu'il publie sur le site. En publiant un avis, l'utilisateur garantit que son contenu :
                </p>
                <ul>
                    <li>Est conforme à la législation en vigueur</li>
                    <li>Ne porte pas atteinte aux droits de tiers</li>
                    <li>Est véridique et sincère</li>
                </ul>

                <h3>6.3 Licence d'utilisation</h3>
                <p>
                    En publiant un contenu sur le site, l'utilisateur accorde à l'éditeur du site une licence non exclusive, gratuite et mondiale d'utilisation, de reproduction et de représentation de ce contenu, pour les besoins du site.
                </p>

                <h2>7. Propriété intellectuelle</h2>
                <h3>7.1 Droits de propriété</h3>
                <p>
                    L'ensemble des éléments du site (textes, images, graphismes, logo, icônes, sons, logiciels, etc.) sont la propriété exclusive de l'éditeur ou de ses partenaires, et sont protégés par les lois françaises et internationales relatives à la propriété intellectuelle.
                </p>

                <h3>7.2 Interdiction de reproduction</h3>
                <p>
                    Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite sans l'autorisation écrite préalable de l'éditeur.
                </p>

                <h3>7.3 Liens hypertextes</h3>
                <p>
                    La mise en place de liens hypertextes vers le site nécessite l'autorisation préalable de l'éditeur. Cette autorisation ne sera en aucun cas accordée à des sites diffusant des informations à caractère illégal, violent, polémique, pornographique, xénophobe ou pouvant porter atteinte à la sensibilité du plus grand nombre.
                </p>

                <h2>8. Protection des données personnelles</h2>
                <p>
                    La collecte et le traitement de vos données personnelles sont effectués dans le respect du Règlement Général sur la Protection des Données (RGPD) et de la loi Informatique et Libertés.
                </p>
                <p>
                    Pour plus d'informations sur la manière dont nous collectons, utilisons et protégeons vos données personnelles, veuillez consulter notre 
                    <a href="politique_confidentialite.php" style="color: #004080; text-decoration: underline;">Politique de Confidentialité</a>.
                </p>

                <h2>10. Responsabilité et garanties</h2>
                <h3>10.1 Limitation de responsabilité</h3>
                <p>
                    L'éditeur du site met tout en œuvre pour offrir aux utilisateurs des informations et services de qualité. Toutefois, nous ne saurions être tenus responsables :
                </p>
                <ul>
                    <li>Des erreurs, omissions ou inexactitudes dans les informations publiées</li>
                    <li>Des dommages directs ou indirects résultant de l'utilisation du site</li>
                    <li>Des interruptions de service, pannes techniques ou bugs</li>
                    <li>Des actes de piratage informatique ou de détournement de données</li>
                    <li>Du contenu des sites tiers vers lesquels pointent les liens hypertextes</li>
                </ul>

                <h3>10.2 Contenu des services</h3>
                <p>
                    Les informations diffusées sur le site sont fournies à titre indicatif. Nous nous efforçons de maintenir des informations exactes et à jour, mais ne pouvons garantir l'exhaustivité, l'exactitude ou l'actualité de ces informations.
                </p>

                <h3>10.3 Garanties de l'utilisateur</h3>
                <p>
                    L'utilisateur reconnaît utiliser le site sous sa seule responsabilité. Il garantit l'éditeur contre toute réclamation, action ou poursuite résultant de son utilisation du site ou de la violation des présentes CGU.
                </p>

                <h2>11. Loi applicable et juridiction</h2>
                <h3>11.1 Droit applicable</h3>
                <p>
                    Les présentes CGU sont régies par le droit français. En cas de litige, la langue française fera foi.
                </p>

                <h3>11.2 Règlement des litiges</h3>
                <p>
                    En cas de litige relatif à l'interprétation ou à l'exécution des présentes CGU, les parties s'efforceront de trouver une solution amiable.
                </p>
                <p>
                    À défaut d'accord amiable, le litige sera soumis aux tribunaux compétents français, conformément aux règles légales de compétence territoriale.
                </p>

                <h3>11.3 Médiation</h3>
                <p>
                    Conformément à l'article L.612-1 du Code de la consommation, en cas de litige non résolu, l'utilisateur consommateur peut recourir gratuitement à un médiateur de la consommation.
                </p>

                <h2>12. Modifications des CGU</h2>
                <p>
                    L'éditeur se réserve le droit de modifier les présentes CGU à tout moment. Les modifications entreront en vigueur dès leur publication sur le site.
                </p>
                <p>
                    Il est de la responsabilité de l'utilisateur de consulter régulièrement les CGU pour prendre connaissance des éventuelles modifications. L'utilisation continue du site après modification des CGU vaut acceptation des nouvelles conditions.
                </p>

                <h2>13. Dispositions générales</h2>
                <h3>13.1 Indépendance des clauses</h3>
                <p>
                    Si une ou plusieurs dispositions des présentes CGU sont déclarées nulles ou inapplicables, les autres dispositions conserveront toute leur force et leur portée.
                </p>

                <h3>13.2 Non-renonciation</h3>
                <p>
                    Le fait pour l'éditeur de ne pas se prévaloir d'une ou plusieurs dispositions des présentes CGU ne pourra en aucun cas impliquer la renonciation à s'en prévaloir ultérieurement.
                </p>

                <div class="contact-box">
                    <h3>📧 Une question sur nos CGU ?</h3>
                    <p>Pour toute question concernant ces Conditions Générales d'Utilisation, vous pouvez nous contacter :</p>
                    <p>
                        <strong>Email :</strong> contact@decramp.fr<br>
                        <strong>Téléphone :</strong> 01 XX XX XX XX<br>
                        <strong>Courrier :</strong> 67 rue du Charme, L'Isle-Adam, France
                    </p>
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