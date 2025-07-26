<?php
session_start();
file_put_contents("debug.log", "API appelé avec action : " . ($_POST['action'] ?? 'aucune') . "\n", FILE_APPEND);

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    if (isset($_GET['action']) && in_array($_GET['action'], ['lister_membres', 'obtenir_membres_groupe'])) {
        http_response_code(401);
        echo "<p style='color:red;'>Session expirée ou utilisateur non connecté.</p>";
        exit;
    }
    header('Location: auth/login.php');
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
$id_utilisateur = $_SESSION['user_id'];
$resultat_utilisateur = $utilisateurs->xpath("//user[id='$id_utilisateur']");
$utilisateur_courant = $resultat_utilisateur ? $resultat_utilisateur[0] : null;

$_POST['utilisateurs'] = $utilisateurs;
$_POST['contacts'] = $contacts;
$_POST['groupes'] = $groupes;
$_POST['messages'] = $messages;
$_POST['id_utilisateur'] = $id_utilisateur;
$_POST['utilisateur_courant'] = $utilisateur_courant;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    $contact_actions = ['ajouter_contact', 'supprimer_contact', 'modifier_contact'];
    $group_actions   = ['creer_groupe', 'supprimer_groupe','ajouter_membre', 'supprimer_membre','ajouter_coadmin', 'retirer_coadmin', 'quitter_groupe', 'lister_membres', 'obtenir_membres_groupe'];
    $message_actions = ['envoyer_message', 'send_message'];
    $profil_actions = ['mettre_a_jour_profil'];

    if(in_array($action, $contact_actions)) {
        include 'services/contacts.php';
    } elseif (in_array($action, $group_actions)) {
        include 'services/groupes.php';
    } elseif (in_array($action, $message_actions)) {
        include 'services/messages.php';
    } elseif (in_array($action, $profil_actions)) {
        include 'services/profil.php';
    } else {
        http_response_code(400);
        echo "<p style='color:red;'>Action non reconnue.</p>";
    }


}
?>