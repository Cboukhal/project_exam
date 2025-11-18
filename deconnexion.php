<?php
/**
 * Page de déconnexion
 * Détruit la session et redirige vers l'accueil
 */

session_start();

// Sauvegarder le prénom pour le message d'au revoir (optionnel)
$prenom = $_SESSION['prenom'] ?? 'Utilisateur';

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire le cookie de session si existant
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Détruire le cookie "Se souvenir de moi" si existant
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', true, true);
}

// Détruire la session
session_destroy();

// Redémarrer une nouvelle session pour le message flash
session_start();
$_SESSION['flash_success'] = "Au revoir " . htmlspecialchars($prenom) . " ! Vous avez été déconnecté avec succès.";

// Redirection vers la page d'accueil
header("Location: ./index.php");
exit;
?>