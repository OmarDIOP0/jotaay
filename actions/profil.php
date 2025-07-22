<?php
session_start();

$action = $_POST['action'];

switch($action){
     case 'mettre_a_jour_profil':
            // Mettre à jour le profil
            if (isset($_POST['prenom'], $_POST['nom'], $_POST['sexe'], $_POST['age'], $_POST['telephone'])) {
                $nouveau_telephone = htmlspecialchars($_POST['telephone']);
                $ancien_telephone = (string)$utilisateur_courant->telephone;
                
                // Vérifier si le nouveau numéro de téléphone n'est pas déjà utilisé par un autre utilisateur
                if ($nouveau_telephone !== $ancien_telephone) {
                    $utilisateur_existe = $utilisateurs->xpath("//user[telephone='$nouveau_telephone' and id!='$id_utilisateur']")[0];
                    if ($utilisateur_existe) {
                        header('Location: views/view.php?error=telephone_already_used');
                        exit;
                    }
                }
                
                $utilisateur_courant->prenom = htmlspecialchars($_POST['prenom']);
                $utilisateur_courant->nom = htmlspecialchars($_POST['nom']);
                $utilisateur_courant->sexe = htmlspecialchars($_POST['sexe']);
                $utilisateur_courant->age = htmlspecialchars($_POST['age']);
                $utilisateur_courant->telephone = $nouveau_telephone;
                
                // Gestion de la photo de profil
                if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    $nom_fichier = uniqid() . '_' . basename($_FILES['photo_profil']['name']);
                    $fichier_cible = $upload_dir . $nom_fichier;
                    if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $fichier_cible)) {
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