<header>
    <div class="logo">
        <img src="./asset/image/title.png" alt="Logo SECIC">
        <div class="logo-text">
            Thierry Decramp
            <br>Artisan électricien
        </div>
    </div>
    <!--Burger-->
    <div class="burger">
        <span></span>
    </div>
    <nav>
        <ul>
            <li><a href='index.php'>Accueil</a></li>
            <li><a href='professionnels.php'>Professionnels</a></li>
            <li><a href='particuliers.php'>Particuliers</a></li>
            <li><a href='domotique.php'>Domotique</a></li>
            <li><a href='commentaire.php'>Commentaire</a></li>
            <li><a href='contact.php'>Contact</a></li>
            <li><a href='liens.php'>Liens</a></li>
            <?php
            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['connexion']) && $_SESSION['connexion'] === true) {
                // Utilisateur connecté
                $prenom = htmlspecialchars($_SESSION['prenom'] ?? 'Utilisateur');
                $role = $_SESSION['role'] ?? 'user';
                
                // // Afficher le lien vers le profil ou admin selon le rôle
                // if ($role === 'admin') {
                //     echo "<li><a href='admin.php'>Admin</a></li>";
                // }
                echo "<li><a href='user.php'>Profil ($prenom)</a></li>";
            } else {
                // Utilisateur non connecté
                echo "<li><a href='connexion.php'>Connexion</a></li>";
            }
            ?>
        </ul>
    </nav>
</header>