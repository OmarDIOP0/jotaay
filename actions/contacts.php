<?php
session_start();

$action = $_POST['action'];

switch($action){
    case 'ajouter_contact':
            // Ajouter un contact
            if (isset($_POST['nom_contact'], $_POST['telephone_contact'])) {
                $nom_contact = htmlspecialchars($_POST['nom_contact']);
                $telephone_contact = htmlspecialchars($_POST['telephone_contact']);
                
                // Vérifier si le contact existe déjà pour cet utilisateur
                $contact_existant = $contacts->xpath("//contact[user_id='$id_utilisateur' and contact_telephone='$telephone_contact']")[0];
                
                if ($contact_existant) {
                    // Le contact existe déjà
                    header('Location: views/view.php?error=contact_already_exists');
                    exit;
                }
                
                // Vérifier si le numéro de téléphone correspond à un utilisateur existant
                $utilisateur_existe = $utilisateurs->xpath("//user[telephone='$telephone_contact']")[0];
                if (!$utilisateur_existe) {
                    // L'utilisateur n'existe pas
                    header('Location: views/view.php?error=user_not_found');
                    exit;
                }
                
                // Vérifier que l'utilisateur ne s'ajoute pas lui-même
                if ($telephone_contact === $utilisateur_courant->telephone) {
                    header('Location: views/view.php?error=cannot_add_self');
                    exit;
                }
                
                // Ajouter le contact
                $contact = $contacts->addChild('contact');
                $contact->addChild('id', uniqid());
                $contact->addChild('user_id', $id_utilisateur);
                $contact->addChild('contact_name', $nom_contact);
                $contact->addChild('contact_telephone', $telephone_contact);
                
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

    case 'supprimer_contact':
            // Supprimer un contact
            if (isset($_POST['id_contact'])) {
                $id_contact = htmlspecialchars($_POST['id_contact']);
                
                // Vérifier que le contact existe
                $contact = $contacts->xpath("//contact[id='$id_contact']")[0];
                
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
            if (isset($_POST['id_contact'], $_POST['nom_contact'])) {
                $id_contact = htmlspecialchars($_POST['id_contact']);
                $nouveau_nom = htmlspecialchars($_POST['nom_contact']);
                // Vérifier que le contact existe
                $contact = $contacts->xpath("//contact[id='$id_contact']")[0];
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