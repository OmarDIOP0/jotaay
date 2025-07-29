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
    case 'lister_contacts':
        $group_id = $_GET['group_id'] ?? null;
        $groupe = null;

        if ($group_id) {
            $groupe = $groupes->xpath("//group[group_id='$group_id']")[0] ?? null;
        }

        // Lister les contacts de l'utilisateur
        $contacts_utilisateur = $contacts->xpath("//contact[user_id='$id_utilisateur']");

        echo "<div style='padding: 10px;'>";
        echo "<h4>Mes contacts</h4>";
        echo "<div style='max-height: 250px; overflow-y: auto;'>";

        if (empty($contacts_utilisateur)) {
            echo "<p>Aucun contact trouvé.</p>";
        } else {
            foreach ($contacts_utilisateur as $contact) {
                $contact_telephone = (string)$contact->contact_telephone;
                $contact_name = htmlspecialchars((string)$contact->contact_name);
                $contact_id = (string)$contact->contact_id;

                $utilisateur_contact = $utilisateurs->xpath("//user[telephone='$contact_telephone']")[0] ?? null;
                $user_exists = $utilisateur_contact !== null;
                $user_id = $user_exists ? (string)$utilisateur_contact->user_id : null;

                $profile_photo = $user_exists ? ((string)$utilisateur_contact->profile_photo ?: "../assets/img/JOTAAY.png") : "../assets/img/JOTAAY.png";
                $profile_photo = htmlspecialchars($profile_photo);
                $username = $user_exists ? htmlspecialchars((string)$utilisateur_contact->username) : "Inconnu";

                echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;'>";
                echo "<div style='display: flex; align-items: center; gap: 10px;'>";
                echo "<img src='$profile_photo' alt='photo' width='40' height='40' style='border-radius: 50%; object-fit: cover;'>";
                echo "<div>";
                echo "<strong>$contact_name</strong><br>";
                echo "<small>$contact_telephone</small>";
                echo "</div>";
                echo "</div>";

                echo "<div>";

                if ($user_exists) {
                    // echo "<span style='background: #28a745; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Utilisateur</span><br>";

                    // Si groupe chargé, vérifier si utilisateur_contact est déjà membre
                    if ($groupe && $user_id) {
                        $est_deja_membre = ((string)$groupe->admin_id === $user_id);
                        if (!$est_deja_membre) {
                            foreach ($groupe->membre_id as $membre_id) {
                                if ((string)$membre_id === $user_id) {
                                    $est_deja_membre = true;
                                    break;
                                }
                            }
                        }

                        if (!$est_deja_membre) {
                            echo "<form method='POST' action='../api.php' style='margin-top: 5px;'>";
                            echo "<input type='hidden' name='action' value='ajouter_membre'>";
                            echo "<input type='hidden' name='group_id' value='" . htmlspecialchars($group_id) . "'>";
                            echo "<input type='hidden' name='id_nouveau_membre' value='" . htmlspecialchars($user_id) . "'>";
                            echo "<button type='submit' class='btn btn-sm btn-primary'>Ajouter</button>";
                            echo "</form>";

                        } else {
                            echo "<span style='background: #17a2b8; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Déjà membre</span>";
                            echo "<form method='POST' action='../api.php' style='margin-top: 5px;'>";
                            echo "<input type='hidden' name='action' value='retirer_membre'>";
                            echo "<input type='hidden' name='group_id' value='" . htmlspecialchars($group_id) . "'>";
                            echo "<input type='hidden' name='membre_id' value='" . htmlspecialchars($user_id) . "'>";
                            echo "<button type='submit' class='btn btn-sm btn-danger'>Retirer</button>";
                            echo "</form>";
                        }
                    }
                } else {
                    echo "<span style='background: #dc3545; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Non inscrit</span>";
                }

                echo "</div>";
                echo "</div>";
            }
        }

        echo "</div>";
        echo "</div>";
        exit;


            }
        

?>