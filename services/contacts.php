<?php
session_start();

$utilisateurs = $_POST['utilisateurs'];
$contacts = $_POST['contacts'];
$id_utilisateur = $_POST['id_utilisateur'];
$utilisateur_courant = $_POST['utilisateur_courant'];
$action = $_POST['action'];

switch($action){
    case 'ajouter_contact':
            // Ajouter un contact
            if (isset($_POST['contact_name'], $_POST['contact_telephone'])) {
                $contact_name = htmlspecialchars($_POST['contact_name']);
                $contact_telephone = htmlspecialchars($_POST['contact_telephone']);
                
                // Vérifier si le contact existe déjà pour cet utilisateur
                $contact_existant = $contacts->xpath("//contact[user_id='$id_utilisateur' and contact_telephone='$contact_telephone']")[0];
                
                if ($contact_existant) {
                    // Le contact existe déjà
                    header('Location: views/view.php?error=contact_already_exists');
                    exit;
                }
                
                // Vérifier si le numéro de téléphone correspond à un utilisateur existant
                $utilisateur_existe = $utilisateurs->xpath("//user[telephone='$contact_telephone']")[0];
                if (!$utilisateur_existe) {
                    // L'utilisateur n'existe pas
                    header('Location: views/view.php?error=user_not_found');
                    exit;
                }
                
                // Vérifier que l'utilisateur ne s'ajoute pas lui-même
                if ($contact_telephone === $utilisateur_courant->telephone) {
                    header('Location: views/view.php?error=cannot_add_self');
                    exit;
                }
                
                // Ajouter le contact
                $contact = $contacts->addChild('contact');
                $contact->addChild('contact_id', uniqid());
                $contact->addChild('user_id', $id_utilisateur);
                $contact->addChild('contact_name', $contact_name);
                $contact->addChild('contact_telephone', $contact_telephone);
                
                // Sauvegarder le fichier
                $resultat = $contacts->asXML('xmls/contacts.xml');
                
                if ($resultat) {
                    header('Location: views/view.php?success=contact_added');
                } else {
                    header('Location: views/view.php?error=add_failed');
                }
            } else {
                header('Location: views/view.php?error=missing_contact_data');
            }
            exit;
    case 'lister_contacts':
        // Trouver tous les contacts de l'utilisateur connecté
        $contacts_utilisateur = $contacts->xpath("//contact[user_id='$id_utilisateur']");

        echo "<div style='padding: 10px;'>";
        echo "<h4>Mes contacts</h4>";
        echo "<div style='max-height: 250px; overflow-y: auto;'>";

        if (empty($contacts_utilisateur)) {
            echo "<p>Aucun contact trouvé.</p>";
        } else {
            foreach ($contacts_utilisateur as $contact) {
                $contact_name = htmlspecialchars((string)$contact->contact_name);
                $contact_telephone = htmlspecialchars((string)$contact->contact_telephone);
                $contact_id = htmlspecialchars((string)$contact->contact_id);

                $utilisateur_contact = $utilisateurs->xpath("//user[telephone='$contact_telephone']")[0];
                $user_exists = $utilisateur_contact ? true : false;
                $profile_photo = $user_exists ? ((string)$utilisateur_contact->profile_photo ?: "../assets/img/default.png") : "../assets/img/default.png";
                $profile_photo = htmlspecialchars($profile_photo);
                $username = $user_exists ? htmlspecialchars((string)$utilisateur_contact->username) : "Inconnu";

                echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;'>";
                echo "<div style='display: flex; align-items: center; gap: 10px;'>";
                echo "<img src='" . $profile_photo . "' alt='photo' width='40' height='40' style='border-radius: 50%; object-fit: cover;'>";
                echo "<div>";
                echo "<strong>$contact_name</strong><br>";
                echo "<small>$contact_telephone</small>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo $user_exists 
                    ? "<span style='background: #28a745; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Utilisateur</span>"
                    : "<span style='background: #dc3545; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Non inscrit</span>";
                echo "</div>";

                echo "</div>";
            }
        }

        echo "</div>";
        echo "</div>";
        exit;



    case 'supprimer_contact':
            // Supprimer un contact
            if (isset($_POST['contact_id'])) {
                $contact_id = htmlspecialchars($_POST['contact_id']);
                
                // Vérifier que le contact existe
                $contact = $contacts->xpath("//contact[contact_id   ='$contact_id']")[0];
                
                if ($contact) {
                    // Vérifier que l'utilisateur connecté est le propriétaire du contact
                    if ((string)$contact->user_id === $id_utilisateur) {
                        // Supprimer le contact
                    $dom = dom_import_simplexml($contact);
                    $dom->parentNode->removeChild($dom);
                        
                        // Sauvegarder le fichier
                        $resultat = $contacts->asXML('xmls/contacts.xml');
                        
                        if ($resultat) {
                            header('Location: views/view.php?success=contact_deleted');
                        } else {
                            header('Location: views/view.php?error=delete_failed');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized');
                    }
                } else {
                    header('Location: views/view.php?error=contact_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_contact_id');
            }
            exit;
     case 'modifier_contact':
            if (isset($_POST['contact_id'], $_POST['contact_name'])) {
                $contact_id = htmlspecialchars($_POST['contact_id']);
                $nouveau_nom = htmlspecialchars($_POST['contact_name']);
                // Vérifier que le contact existe
                $contact = $contacts->xpath("//contact[contact_id='$contact_id']")[0];
                if ($contact) {
                    // Vérifier que l'utilisateur connecté est le propriétaire du contact
                    if ((string)$contact->user_id === $id_utilisateur) {
                        $contact->contact_name = $nouveau_nom;
                        $resultat = $contacts->asXML('xmls/contacts.xml');
                        if ($resultat) {
                            header('Location: views/view.php?success=contact_edited');
                        } else {
                            header('Location: views/view.php?error=edit_failed');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized');
                    }
                } else {
                    header('Location: views/view.php?error=contact_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_contact_data');
            }
            exit;

}

?>