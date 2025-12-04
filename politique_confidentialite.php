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
    <meta name="description" content="Politique de confidentialité et protection des données personnelles - Thierry Decramp SECIC">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./asset/css/style2.css">
    <link rel="icon" type="image/webp" href="./asset/image/OIP.webp">
    
    <title>Politique de Confidentialité - <?php echo SITE_TITLE; ?></title>
</head>
<body>
    <?php include "./includes/header.php"; ?>
    
    <main>
        <div class="legal-container">
            <div class="legal-header">
                <h1>Politique de Confidentialité</h1>
                <p class="update-date">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
            </div>
            
            <div class="legal-content">
                <div class="info-box">
                    <strong>🔒 Votre vie privée nous tient à cœur :</strong> Cette politique de confidentialité vous informe de la manière dont nous collectons, utilisons et protégeons vos données personnelles conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés.
                </div>

                <h2>1. Identité du responsable du traitement</h2>
                <p>
                    Le responsable du traitement de vos données personnelles est :
                </p>
                
                <div class="info-box">
                    <strong>Raison sociale :</strong> SECIC - Thierry Decramp<br>
                    <strong>Représentant légal :</strong> Thierry Decramp<br>
                    <strong>Adresse :</strong> 67 rue du Charme, L'Isle-Adam, France<br>
                    <strong>Email :</strong> <a href="mailto:contact@decramp.fr">contact@decramp.fr</a><br>
                    <strong>Téléphone :</strong> 01 XX XX XX XX
                </div>

                <p>
                    Pour toute question relative à la protection de vos données personnelles ou pour exercer vos droits, vous pouvez nous contacter à l'adresse email ci-dessus en précisant l'objet : <strong>"Protection des données - RGPD"</strong>.
                </p>

                <h2>2. Données personnelles collectées</h2>
                <p>
                    Dans le cadre de l'utilisation de notre site web, nous sommes amenés à collecter et traiter différentes catégories de données personnelles vous concernant.
                </p>

                <h3>2.1 Données collectées via le formulaire de contact</h3>
                <p>
                    Lorsque vous utilisez notre formulaire de contact, nous collectons :
                </p>
                <ul>
                    <li><strong>Identité :</strong> Nom et prénom</li>
                    <li><strong>Contact :</strong> Adresse email</li>
                    <li><strong>Message :</strong> Sujet et contenu de votre demande</li>
                    <li><strong>Données techniques :</strong> Adresse IP, date et heure de l'envoi</li>
                </ul>
                <p>
                    <strong>Caractère obligatoire :</strong> Ces données sont nécessaires pour traiter votre demande. Sans ces informations, nous ne pourrons pas vous répondre.
                </p>

                <h3>2.2 Données collectées lors de la création d'un compte utilisateur</h3>
                <p>
                    Si vous créez un compte sur notre site, nous collectons :
                </p>
                <ul>
                    <li><strong>Civilité :</strong> M., Mme, Mx</li>
                    <li><strong>Identité :</strong> Prénom et nom</li>
                    <li><strong>Contact :</strong> Adresse email</li>
                    <li><strong>Données de connexion :</strong> Mot de passe (hashé et sécurisé)</li>
                    <li><strong>Métadonnées :</strong> Date de création du compte, date de dernière connexion</li>
                </ul>

                <h3>2.3 Données collectées via les demandes de devis</h3>
                <p>
                    Lorsque vous effectuez une demande de devis (réservée aux utilisateurs inscrits), nous collectons :
                </p>
                <ul>
                    <li><strong>Type de client :</strong> Particulier, Professionnel ou Domotique</li>
                    <li><strong>Identité :</strong> Nom complet</li>
                    <li><strong>Contact :</strong> Email et numéro de téléphone (optionnel)</li>
                    <li><strong>Description du projet :</strong> Message détaillant vos besoins</li>
                    <li><strong>Date :</strong> Date de la demande</li>
                </ul>

                <h3>2.4 Données collectées via les avis et commentaires</h3>
                <p>
                    Si vous déposez un avis sur nos services (réservé aux utilisateurs inscrits), nous collectons :
                </p>
                <ul>
                    <li><strong>Identité :</strong> Prénom et nom (affichés publiquement après validation)</li>
                    <li><strong>Contact :</strong> Adresse email (non affichée publiquement)</li>
                    <li><strong>Évaluation :</strong> Note de 1 à 5 étoiles</li>
                    <li><strong>Commentaire :</strong> Texte de votre avis (10 à 500 caractères)</li>
                    <li><strong>Date :</strong> Date de publication</li>
                </ul>

                <h3>2.5 Données collectées automatiquement</h3>
                <p>
                    Lors de votre navigation sur le site, certaines données techniques sont collectées automatiquement :
                </p>
                <ul>
                    <li><strong>Cookies de session :</strong> Identifiant de session PHP (voir section 8)</li>
                    <li><strong>Logs serveur :</strong> Adresse IP, navigateur, système d'exploitation, pages consultées, date et heure des requêtes</li>
                </ul>

                <h2>3. Finalités et bases légales du traitement</h2>
                <p>
                    Vos données personnelles sont collectées et traitées pour les finalités suivantes :
                </p>

                <table class="admin-table" style="margin: 20px 0;">
                    <thead>
                        <tr>
                            <th>Finalité</th>
                            <th>Base légale</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Traitement des demandes de contact</td>
                            <td>Consentement (article 6.1.a RGPD)</td>
                        </tr>
                        <tr>
                            <td>Création et gestion des comptes utilisateurs</td>
                            <td>Exécution d'un contrat (article 6.1.b RGPD)</td>
                        </tr>
                        <tr>
                            <td>Traitement des demandes de devis</td>
                            <td>Exécution d'un contrat (article 6.1.b RGPD)</td>
                        </tr>
                        <tr>
                            <td>Modération et publication des avis</td>
                            <td>Consentement (article 6.1.a RGPD)</td>
                        </tr>
                        <tr>
                            <td>Sécurité du site et prévention de la fraude</td>
                            <td>Intérêt légitime (article 6.1.f RGPD)</td>
                        </tr>
                        <tr>
                            <td>Respect des obligations légales</td>
                            <td>Obligation légale (article 6.1.c RGPD)</td>
                        </tr>
                    </tbody>
                </table>

                <h2>4. Destinataires des données</h2>
                <h3>4.1 Destinataires internes</h3>
                <p>
                    Vos données personnelles sont accessibles en interne aux personnes suivantes :
                </p>
                <ul>
                    <li><strong>Administrateur du site :</strong> Thierry Decramp (gestion des demandes de contact, devis et modération des avis)</li>
                    <li><strong>Personnel habilité :</strong> Employés en charge de la relation client</li>
                </ul>

                <h3>4.2 Destinataires externes</h3>
                <p>
                    Vos données peuvent être transmises aux prestataires suivants :
                </p>
                <ul>
                    <li><strong>Hébergeur du site :</strong> [Nom de l'hébergeur] - pour l'hébergement des données sur des serveurs sécurisés</li>
                    <li><strong>Service de messagerie :</strong> Serveur SMTP pour l'envoi des emails de confirmation</li>
                    <li><strong>Google reCAPTCHA :</strong> Google Inc. - pour la protection contre les spams (formulaire de contact uniquement)</li>
                </ul>

                <div class="warning-box">
                    <strong>⚠️ Important :</strong> Nous ne vendons, ne louons ni ne partageons vos données personnelles avec des tiers à des fins commerciales ou marketing.
                </div>

                <h3>4.3 Transferts hors Union Européenne</h3>
                <p>
                    <strong>Google reCAPTCHA :</strong> L'utilisation de reCAPTCHA implique un transfert de données vers les États-Unis. Google met en œuvre des garanties appropriées conformément au RGPD (clauses contractuelles types).
                </p>
                <p>
                    Pour plus d'informations : <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Politique de confidentialité de Google</a>
                </p>

                <h2>5. Durée de conservation des données</h2>
                <p>
                    Vos données personnelles sont conservées pour la durée strictement nécessaire aux finalités pour lesquelles elles ont été collectées :
                </p>

                <table class="admin-table" style="margin: 20px 0;">
                    <thead>
                        <tr>
                            <th>Type de données</th>
                            <th>Durée de conservation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Messages de contact</td>
                            <td>3 ans à compter de l'envoi</td>
                        </tr>
                        <tr>
                            <td>Comptes utilisateurs actifs</td>
                            <td>Jusqu'à suppression du compte par l'utilisateur ou 3 ans d'inactivité</td>
                        </tr>
                        <tr>
                            <td>Demandes de devis</td>
                            <td>3 ans à compter de la demande (obligations légales comptables)</td>
                        </tr>
                        <tr>
                            <td>Avis et commentaires publiés</td>
                            <td>Jusqu'à suppression du compte ou demande de retrait</td>
                        </tr>
                        <tr>
                            <td>Logs de connexion</td>
                            <td>12 mois maximum (obligations légales de sécurité)</td>
                        </tr>
                        <tr>
                            <td>Données de facturation (si applicable)</td>
                            <td>10 ans (obligations légales comptables et fiscales)</td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    À l'issue de ces durées, vos données sont supprimées de manière sécurisée ou anonymisées de façon irréversible.
                </p>

                <h2>6. Sécurité des données</h2>
                <h3>6.1 Mesures de sécurité techniques</h3>
                <p>
                    Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos données personnelles contre tout accès non autorisé, modification, divulgation ou destruction :
                </p>
                <ul>
                    <li><strong>Chiffrement HTTPS :</strong> Toutes les communications entre votre navigateur et notre site sont chiffrées</li>
                    <li><strong>Hashage des mots de passe :</strong> Les mots de passe sont hashés avec l'algorithme bcrypt (jamais stockés en clair)</li>
                    <li><strong>Protection contre les injections SQL :</strong> Utilisation de requêtes préparées PDO</li>
                    <li><strong>Protection XSS :</strong> Échappement systématique des données affichées (htmlspecialchars)</li>
                    <li><strong>Protection anti-spam :</strong> Google reCAPTCHA v2 sur le formulaire de contact</li>
                    <li><strong>Validation des entrées :</strong> Vérification stricte côté serveur de toutes les données soumises</li>
                    <li><strong>Limitation des tentatives de connexion :</strong> Prévention des attaques par force brute</li>
                </ul>

                <h3>6.2 Mesures organisationnelles</h3>
                <ul>
                    <li>Accès restreint aux données (comptes administrateurs protégés par mot de passe fort)</li>
                    <li>Sauvegardes régulières et sécurisées de la base de données</li>
                    <li>Mise à jour régulière des logiciels et systèmes</li>
                    <li>Formation et sensibilisation du personnel aux bonnes pratiques de sécurité</li>
                </ul>

                <div class="warning-box">
                    <strong>⚠️ Votre responsabilité :</strong> Vous êtes responsable de la confidentialité de vos identifiants de connexion. Ne les communiquez jamais à des tiers et déconnectez-vous après chaque utilisation sur un ordinateur partagé.
                </div>

                <h2>7. Vos droits sur vos données personnelles</h2>
                <p>
                    Conformément au RGPD et à la loi Informatique et Libertés, vous disposez des droits suivants concernant vos données personnelles :
                </p>

                <h3>7.1 Droit d'accès (article 15 RGPD)</h3>
                <p>
                    Vous avez le droit d'obtenir la confirmation que des données vous concernant sont traitées et d'accéder à ces données. Vous pouvez également obtenir une copie de vos données.
                </p>

                <h3>7.2 Droit de rectification (article 16 RGPD)</h3>
                <p>
                    Vous pouvez demander la correction de données inexactes ou incomplètes vous concernant. Vous pouvez également modifier vos informations directement depuis votre espace personnel.
                </p>

                <h3>7.3 Droit à l'effacement / "droit à l'oubli" (article 17 RGPD)</h3>
                <p>
                    Vous pouvez demander la suppression de vos données personnelles dans les cas suivants :
                </p>
                <ul>
                    <li>Les données ne sont plus nécessaires au regard des finalités pour lesquelles elles ont été collectées</li>
                    <li>Vous retirez votre consentement et il n'existe pas d'autre fondement juridique au traitement</li>
                    <li>Vous vous opposez au traitement et il n'existe pas de motif légitime impérieux</li>
                    <li>Les données ont fait l'objet d'un traitement illicite</li>
                </ul>
                <p>
                    <strong>Suppression de compte :</strong> Vous pouvez supprimer votre compte à tout moment depuis votre espace personnel. Cette action est définitive et irréversible.
                </p>

                <h3>7.4 Droit à la limitation du traitement (article 18 RGPD)</h3>
                <p>
                    Vous pouvez demander la limitation du traitement de vos données dans certaines circonstances (par exemple, pendant la vérification de l'exactitude des données).
                </p>

                <h3>7.5 Droit à la portabilité (article 20 RGPD)</h3>
                <p>
                    Vous avez le droit de recevoir vos données dans un format structuré, couramment utilisé et lisible par machine, et de les transmettre à un autre responsable de traitement.
                </p>

                <h3>7.6 Droit d'opposition (article 21 RGPD)</h3>
                <p>
                    Vous pouvez vous opposer à tout moment au traitement de vos données personnelles pour des raisons tenant à votre situation particulière.
                </p>

                <h3>7.7 Droit de définir des directives post-mortem</h3>
                <p>
                    Vous avez le droit de définir des directives relatives au sort de vos données personnelles après votre décès.
                </p>

                <h3>7.8 Comment exercer vos droits ?</h3>
                <div class="info-box">
                    <strong>📧 Pour exercer l'un de ces droits, contactez-nous :</strong><br><br>
                    <strong>Par email :</strong> <a href="mailto:contact@decramp.fr">contact@decramp.fr</a> (objet : "Exercice de mes droits RGPD")<br>
                    <strong>Par courrier :</strong> SECIC - Thierry Decramp, 67 rue du Charme, L'Isle-Adam, France<br><br>
                    <strong>Informations à fournir :</strong>
                    <ul style="margin-top: 10px;">
                        <li>Nom, prénom et adresse email du compte concerné</li>
                        <li>Copie d'une pièce d'identité (pour vérification)</li>
                        <li>Description précise de votre demande</li>
                    </ul>
                    <strong>Délai de réponse :</strong> 1 mois maximum à compter de la réception de votre demande.
                </div>

                <h3>7.9 Droit d'introduire une réclamation</h3>
                <p>
                    Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la Commission Nationale de l'Informatique et des Libertés (CNIL) :
                </p>
                <div class="info-box">
                    <strong>CNIL</strong><br>
                    3 Place de Fontenoy - TSA 80715<br>
                    75334 PARIS CEDEX 07<br>
                    Téléphone : 01 53 73 22 22<br>
                    Site web : <a href="https://www.cnil.fr" target="_blank" rel="noopener">https://www.cnil.fr</a>
                </div>

                <h2 id="cookies">8. Cookies et technologies similaires</h2>
                <h3>8.1 Qu'est-ce qu'un cookie ?</h3>
                <p>
                    Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, smartphone, tablette) lors de la visite d'un site web. Il permet au site de mémoriser des informations sur votre visite.
                </p>

                <h3>8.2 Cookies utilisés sur notre site</h3>
                <p>
                    Notre site utilise <strong>uniquement des cookies strictement nécessaires</strong> au fonctionnement du site :
                </p>

                <table class="admin-table" style="margin: 20px 0;">
                    <thead>
                        <tr>
                            <th>Nom du cookie</th>
                            <th>Type</th>
                            <th>Finalité</th>
                            <th>Durée</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PHPSESSID</td>
                            <td>Cookie de session</td>
                            <td>Maintien de la connexion utilisateur et gestion de session</td>
                            <td>Session (supprimé à la fermeture du navigateur)</td>
                        </tr>
                    </tbody>
                </table>

                <div class="info-box">
                    <strong>✅ Cookies strictement nécessaires :</strong> Ces cookies sont indispensables au fonctionnement du site (gestion de la connexion, du panier, des préférences). Conformément à l'article 82 de la loi Informatique et Libertés, ces cookies ne nécessitent <strong>pas de consentement préalable</strong>.
                </div>

                <h3>8.3 Cookies tiers</h3>
                <p>
                    <strong>Google reCAPTCHA :</strong> Le service reCAPTCHA utilisé sur notre formulaire de contact peut déposer des cookies pour vérifier que vous n'êtes pas un robot. Ces cookies sont soumis à la politique de confidentialité de Google.
                </p>
                <p>
                    Pour plus d'informations : <a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener">Politique de cookies de Google</a>
                </p>

                <h3>8.4 Comment gérer les cookies ?</h3>
                <p>
                    Vous pouvez configurer votre navigateur pour refuser les cookies. Cependant, la désactivation des cookies strictement nécessaires peut empêcher l'utilisation de certaines fonctionnalités du site (notamment la connexion à votre compte).
                </p>
                <p>
                    <strong>Paramétrage par navigateur :</strong>
                </p>
                <ul>
                    <li><strong>Chrome :</strong> Paramètres → Confidentialité et sécurité → Cookies</li>
                    <li><strong>Firefox :</strong> Options → Vie privée et sécurité → Cookies</li>
                    <li><strong>Safari :</strong> Préférences → Confidentialité</li>
                    <li><strong>Edge :</strong> Paramètres → Cookies et autorisations de site</li>
                </ul>

                <h2>9. Modification de la politique de confidentialité</h2>
                <p>
                    Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment afin de refléter les évolutions légales, réglementaires ou techniques.
                </p>
                <p>
                    En cas de modification substantielle, nous vous en informerons par un message sur la page d'accueil du site ou par email si vous disposez d'un compte utilisateur.
                </p>
                <p>
                    Nous vous encourageons à consulter régulièrement cette page pour prendre connaissance des éventuelles modifications.
                </p>

                <h2>10. Mineurs</h2>
                <p>
                    Notre site s'adresse à un public majeur. Nous ne collectons pas sciemment de données personnelles concernant des mineurs de moins de 15 ans.
                </p>
                <p>
                    Si vous êtes parent ou tuteur légal et que vous découvrez que votre enfant nous a fourni des données personnelles sans votre consentement, veuillez nous contacter immédiatement afin que nous puissions supprimer ces informations.
                </p>

                <h2>11. Contact et réclamations</h2>
                <div class="contact-box">
                    <h3>📧 Questions sur la protection de vos données ?</h3>
                    <p>Pour toute question relative à cette politique de confidentialité ou à la protection de vos données personnelles :</p>
                    <p>
                        <strong>Email :</strong> <a href="mailto:contact@decramp.fr">contact@decramp.fr</a><br>
                        <strong>Objet :</strong> "Protection des données - RGPD"<br>
                        <strong>Téléphone :</strong> 01 XX XX XX XX<br>
                        <strong>Courrier :</strong> SECIC - Thierry Decramp<br>
                        67 rue du Charme, L'Isle-Adam, France
                    </p>
                </div>

                <div class="info-box">
                    <strong>📚 Documents complémentaires :</strong><br>
                    • <a href="cgu.php" style="color: #004080; text-decoration: underline;">Conditions Générales d'Utilisation (CGU)</a><br>
                    • <a href="mentions_legales.php" style="color: #004080; text-decoration: underline;">Mentions légales</a>
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