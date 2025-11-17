<?php
    session_start();
    include "./includes/connexionbdd.php";
    include "./includes/fonctions.php";
    if (!isset($connexion))
    {
        die("Erreur: connexion à la BDD introuvable.");
    }

    // -----------------------------------Admin-----------------------------------//
    $stmt = $connexion->prepare("SELECT prenom, nom FROM users WHERE role = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // -----------------------------------Service-----------------------------------//
    // --- SUPPRESSION d’un service ---
    if (isset($_GET['delete']))
    {
        $id = (int)$_GET['delete'];
        $stmt = $connexion->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin.php");
        exit;
    }

    // --- AJOUT d’un service ---
    if (isset($_POST['ajouter']))
    {
        $title = trim($_POST['title']);
        // Génération du slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-éèàùêûîô]+/', '-', $title)));
        $description = trim($_POST['description']);
        $categorie = $_POST['categorie'];

        $stmt = $connexion->prepare("INSERT INTO services (title, slug, description, categorie) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $description, $categorie]);

        header("Location: admin.php");
        exit;
    }

    // --- RÉCUPÉRATION des services ---
    $services = $connexion->query("SELECT * FROM services ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);


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
    <title>Admins</title>
</head>
<body>
    <?php
        include "./includes/header.php";
    ?>
    <main>
    <?php
         if ($admin)
        {
            // Affichage du nom dans une balise <h1>
            echo "<h1>Bienvenu " . htmlspecialchars($admin['prenom']) . " " . htmlspecialchars($admin['nom']) . "</h1>";
        }
        else
        {
                echo "<h1>Admin non trouvé</h1>";
        }
    ?>
    <!------------------------------------ SERVICE ---------------------------------->
    <section>
        <h2>Gestion des services</h2>
        <br>
        <!-- Formulaire d’ajout -->
        <div class="contact">
            <div class="contact-form">
            <form method="POST">
                <h3>Ajouter un nouveau service</h3>
                <label>Titre :</label>
                <input type="text" name="title" required>

                <label>Description :</label>
                <textarea name="description" rows="4"></textarea>

                <label>Catégorie :</label>
                <select name="categorie">
                    <option value="particulier">Particulier</option>
                    <option value="professionnel">Professionnel</option>
                    <option value="autre">Autre</option>
                </select>

                <button type="submit" name="ajouter">Ajouter</button>
            </form>
            </div>
        </div>
        <!-- Tableau des services -->
        <table>
            <tr>
                <th>Titre</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Date création</th>
                <th>Modif</th>
            </tr>
            <?php 
                foreach ($services as $service): 
            ?>
            <tr>
                <td><?= htmlspecialchars($service['title']) ?></td>
                <td><?= htmlspecialchars($service['slug']) ?></td>
                <td><?= htmlspecialchars(substr($service['description'], 0, 50)) ?>...</td>
                <td><?= htmlspecialchars($service['categorie']) ?></td>
                <td><?= htmlspecialchars($service['date_creation']) ?></td>
                <td>
                    <a href="?delete=<?= $service['id'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce service ?')">Supprimer</a>
                </td>
            </tr>
            <?php 
                endforeach;
             ?>
        </table>
    </section>
    </main>
    <?php
        include "./includes/footer.php";
    ?>  
    <script src="./asset/Js/jquery-3.7.1.min.js"></script>
    <script src="./asset/Js/script.js"></script>
</body>
</html>