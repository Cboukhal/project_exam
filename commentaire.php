<?php
    session_start();
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
    <title>Particuliers</title>
</head>
<body>
    <?php
        include "./includes/header.php";
    ?>
    <main>
      <!-- 🏠 ACCUEIL -->
<section class="hero">
  <div id="slider">
    <img src="./asset/image/ampoule.jpg" alt="ampoule">
    <img src="./asset/image/ampoule2.jpg" alt="ampoule2">
    <img src="./asset/image/ampoule3.jpg" alt="ampoule3">
  </div>

  <div class="hero-overlay">
    <h1>Domotique</h1>
    <p>Thierry Decramp - SECIC - Artisan électricien</p>
    <p>Électricien depuis plus de 15 ans, spécialisé dans les nouvelles technologies et respectueux des normes.</p>
    <a href="contact.html" class="btn">Contact</a>
  </div>

  <!-- Les points de navigation -->
  <div class="hero-dots">
    <span class="dot active" data-index="0"></span>
    <span class="dot" data-index="1"></span>
    <span class="dot" data-index="2"></span>
  </div>
</section>
  <!-- ===================== INTRO ===================== -->
  <section class="intro">
    <h3>Votre maison, plus intelligente</h3>
    <p>
      Installation et paramétrage de solutions domotiques : gestion de l’éclairage, des volets, du chauffage,
      de la sécurité et supervision à distance via smartphone ou tablette.
    </p>
  </section>

  <!-- ===================== GALERIE ===================== -->
  <section class="gallery">
    <div class="item"><img src="./asset/image/domotique/20151204_175036.jpg" alt=""></div>
    <div class="item"><img src="./asset/image/domotique/20240323_123545.jpg" alt=""></div>
    <div class="item"><img src="./asset/image/domotique/P_20171213_153435.jpg" alt=""></div>
    <div class="item"><img src="./asset/image/domotique/SHAW (62).JPG" alt=""></div>
    <div class="item"><img src="./asset/image/domotique/synergieTebis.jpg" alt=""></div>
  </section>

  <!-- ===================== PRESENTATION ===================== -->
  <section class="presentation">
    <h3>Notre engagement</h3>
    <blockquote>
     "Des solutions domotiques fiables, simples à utiliser et parfaitement adaptées à votre mode de vie."
    </blockquote>
  </section>

  <!-- ===================== PRESTATIONS ===================== -->
  <section class="prestations" id="prestations">
    <h3>Nos prestations domotique</h3>

    <div class="prestation">
      <h4>1. Gestion de l’éclairage</h4>
      <ul>
        <li>Éclairage automatique et scénarios personnalisés</li>
        <li>Commandes à distance via application mobile</li>
      </ul>
    </div>

    <div class="prestation">
      <h4>2. Chauffage intelligent</h4>
      <ul>
        <li>Régulation automatique selon la température ambiante</li>
        <li>Optimisation énergétique pour réduire vos dépenses</li>
      </ul>
    </div>

    <div class="prestation">
      <h4>3. Sécurité connectée</h4>
      <ul>
        <li>Études et solutions de chauffage électrique</li>
        <li>VMC performante et adaptée aux besoins</li>
      </ul>
    </div>

    <div class="prestation">
      <h4>4. Éclairage & domotique</h4>
      <ul>
        <li>Caméras, alarmes et capteurs interconnectés</li>
        <li>Contrôle en temps réel depuis votre smartphone</li>
      </ul>
    </div>
    </main>
    <?php
        include "./includes/footer.php";
    ?>  
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>