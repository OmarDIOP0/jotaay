<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: auth/login.php');
    exit;
}

$cheminBase = __DIR__ . '/xmls/';

$utilisateurs = @simplexml_load_file($cheminBase . 'users.xml');
if ($utilisateurs === false) {
    die('Erreur : Impossible de charger users.xml. Vérifiez le fichier ou le chemin.');
}

$contacts = @simplexml_load_file($cheminBase . 'contacts.xml');
if ($contacts === false) {
    die('Erreur : Impossible de charger contacts.xml. Vérifiez le fichier ou le chemin.');
}

$groupes = @simplexml_load_file($cheminBase . 'groups.xml');
if ($groupes === false) {
    die('Erreur : Impossible de charger groups.xml. Vérifiez le fichier ou le chemin.');
}

$messages = @simplexml_load_file($cheminBase . 'messages.xml');
if ($messages === false) {
    die('Erreur : Impossible de charger messages.xml. Vérifiez le fichier ou le chemin.');
}

?>
