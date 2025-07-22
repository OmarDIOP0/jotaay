<?php
session_start();

// Gestion robuste de la session pour AJAX
if (!isset($_SESSION['id_utilisateur']) || empty($_SESSION['id_utilisateur'])) {
    if (isset($_GET['action']) && in_array($_GET['action'], ['lister_membres', 'obtenir_membres_groupe'])) {
        http_response_code(401);
        echo "<p style='color:red;'>Session expirée ou utilisateur non connecté.</p>";
        exit;
    }
    header('Location: connexion/login.php');
    exit;
}

// Charger les données XML avec vérification
$utilisateurs = @simplexml_load_file('xmls/users.xml');
if ($utilisateurs === false) {
    die('Erreur : Impossible de charger users.xml. Vérifiez le fichier ou le chemin.');
}
$contacts = @simplexml_load_file('xmls/contacts.xml');
if ($contacts === false) {
    die('Erreur : Impossible de charger contacts.xml. Vérifiez le fichier ou le chemin.');
}
$groupes = @simplexml_load_file('xmls/groups.xml');
if ($groupes === false) {
    die('Erreur : Impossible de charger groups.xml. Vérifiez le fichier ou le chemin.');
}
$messages = @simplexml_load_file('xmls/messages.xml');
if ($messages === false) {
    die('Erreur : Impossible de charger messages.xml. Vérifiez le fichier ou le chemin.');
}

// Récupérer l'utilisateur connecté
$id_utilisateur = $_SESSION['id_utilisateur'];
$utilisateur_courant = $utilisateurs->xpath("//user[id='$id_utilisateur']")[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
       

        

        

       

       

        





       

    }
}
?>