<?php
session_start();
$utilisateurs = $_POST['utilisateurs'];
$contacts = $_POST['contacts'];
$id_utilisateur = $_POST['id_utilisateur'];
$utilisateur_courant = $utilisateurs->xpath("//user[user_id='$id_utilisateur']")[0] ?? null;
$action = $_POST['action'];

switch($action){
     case 'mettre_a_jour_profil':
            if (isset($_POST['username'], $_POST['telephone'])) {
                $nouveau_telephone = htmlspecialchars($_POST['telephone']);
                $ancien_telephone = (string)$utilisateur_courant->telephone;
                if ($nouveau_telephone !== $ancien_telephone) {
                    $utilisateur_existe = $utilisateurs->xpath("//user[telephone='$nouveau_telephone' and user_id!='$id_utilisateur']")[0];
                    if ($utilisateur_existe) {
                        header('Location: views/view.php?error=telephone_already_used');
                        exit;
                    }
                }
                
                $utilisateur_courant->username = htmlspecialchars($_POST['username']);
                $utilisateur_courant->telephone = $nouveau_telephone;
                
                if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    $nom_fichier = uniqid() . '_' . basename($_FILES['profile_photo']['name']);
                    $fichier_cible = $upload_dir . $nom_fichier;
                    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $fichier_cible)) {
                        $utilisateur_courant->profile_photo = $nom_fichier;
                    }
                }
                
                // Sauvegarder les modifications
                $resultat = $utilisateurs->asXML('xmls/users.xml');
                
                if ($resultat) {
                    header('Location: views/view.php?success=profile_updated');
                } else {
                    header('Location: views/view.php?error=update_failed');
                }
            } else {
                header('Location: views/view.php?error=missing_profile_data');
            }
            exit;

}

?>