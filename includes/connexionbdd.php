<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    function log_error($msg)
    {
    $fichier = fopen("error.log", "a+");
    fwrite($fichier, date("d/m/Y H:i:s : ").$msg."\n");
    fclose($fichier);
    }

    try{
        $connexion = new PDO("mysql:host=$server;", $username, $password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE IF NOT EXISTS projet_exam CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql);
    }
    catch(PDOException $e){
        date_default_timezone_set("Europe/Paris");
        setlocale(LC_TIME, "fr_FR");
        $fichier = fopen("error.log", "a+");
        fwrite($fichier, date("d/m/Y H:i:s : ").$e->getMessage()."\n");
        fclose($fichier);
    }
    //nom de la base de donnée
    $dbname = 'projet_exam';
    try{
        $connexion = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        //users
        $sql = "CREATE TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT PRIMARY KEY,
            civilite VARCHAR(3),
            prenom VARCHAR(50) NOT NULL,
            nom VARCHAR(50) NOT NULL,
            mail VARCHAR(100) UNIQUE NOT NULL,
            mdp VARCHAR(255) NOT NULL,
            `role` VARCHAR(20),
            reset_token VARCHAR(64) DEFAULT NULL,
            reset_token_expires DATETIME DEFAULT NULL,
            date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql);
        //galeries
        $sql2 = "CREATE TABLE IF NOT EXISTS galeries(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            mime_type VARCHAR(100) DEFAULT NULL,
            file_size INT UNSIGNED DEFAULT NULL,
            image_data LONGBLOB, -- contient l'image binaire
            legende VARCHAR(255) DEFAULT NULL,
            image_type ENUM('particulier','professionnel','domotique') NOT NULL DEFAULT 'particulier',
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql2);
        //contact
        $sql3 = "CREATE TABLE IF NOT EXISTS contact(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            prenom VARCHAR(100) DEFAULT NULL,
            nom VARCHAR(100) DEFAULT NULL,
            email VARCHAR(255) NOT NULL,
            sujet VARCHAR(255) DEFAULT NULL,
            `message` TEXT NOT NULL,
            `status` ENUM('new','read','closed') DEFAULT 'new',
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql3);
        //commentaire
        $sql4 = "CREATE TABLE IF NOT EXISTS commentaire(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            pseudo VARCHAR(100) DEFAULT 'Anonyme',
            email VARCHAR(255) DEFAULT NULL,
            note TINYINT UNSIGNED NOT NULL DEFAULT 5,
            commentaire TEXT,
            approved TINYINT(1) DEFAULT 0,
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql4);
        //partenaire
        $sql5 = "CREATE TABLE IF NOT EXISTS partenaire(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            `url` VARCHAR(255) NOT NULL,
            `description` VARCHAR(255) DEFAULT NULL,
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql5);
        //requête
        $sql6 = "CREATE TABLE IF NOT EXISTS requete_devis(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            type_client VARCHAR(200) DEFAULT NULL,
            contact_name VARCHAR(200) DEFAULT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(50) DEFAULT NULL,
            `message` TEXT,
            `status` ENUM('new','in_progress','closed') DEFAULT 'new',
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_bin";
        $connexion->exec($sql6);
    }
    catch(PDOException $e){
        date_default_timezone_set("Europe/Paris");
        setlocale(LC_TIME, "fr_FR");
        $fichier = fopen("error.log", "a+");
        fwrite($fichier, date("d/m/Y H:i:s : ").$e->getMessage()."\n");
        fclose($fichier);
    }
?>