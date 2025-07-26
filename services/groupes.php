<?php
session_start();

$utilisateurs = $_POST['utilisateurs'];
$contacts = $_POST['contacts'];
$id_utilisateur = $_POST['id_utilisateur'];
$utilisateur_courant = $_POST['utilisateur_courant'];
$action = $_POST['action'];

switch($action){
    case 'creer_groupe':
            // Créer un groupe
            if (!isset($_POST['members']) || count($_POST['members']) < 2) {
                header('Location: views/view.php?error=minimum_two_members');
            exit;
            }
            $groupe = $groupes->addChild('group');
            $groupe->addChild('group_id', uniqid('groupe_id'));
            $groupe->addChild('group_name', htmlspecialchars($_POST['nom_groupe']));
            $groupe->addChild('admin_id', $id_utilisateur);
            if (isset($_FILES['group_photo']) && $_FILES['group_photo']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/';
                $nom_fichier = uniqid() . '_' . basename($_FILES['group_photo']['name']);
                $fichier_cible = $upload_dir . $nom_fichier;
                if (move_uploaded_file($_FILES['group_photo']['tmp_name'], $fichier_cible)) {
                    $groupe->addChild('group_photo', $nom_fichier);
                }
            }
            foreach ($_POST['ids_membres'] as $id_membre) {
                $groupe->addChild('membre_id', htmlspecialchars($id_membre));
            }
            $resultat = $groupes->asXML('xmls/groups.xml');
            
            if ($resultat) {
                header('Location: views/view.php?success=group_created');
            } else {
                header('Location: views/view.php?error=group_creation_failed');
            }
            exit;

       
    
    case 'supprimer_groupe':
            if (isset($_POST['id_groupe'])) {
                $id_groupe = htmlspecialchars($_POST['id_groupe']);
                
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                
                if ($groupe) {
                    // Vérifier que l'utilisateur connecté est l'admin du groupe
                    if ((string)$groupe->admin_id === $id_utilisateur) {
                        // Supprimer le groupe
                    $dom = dom_import_simplexml($groupe);
                    $dom->parentNode->removeChild($dom);
                        
                        // Sauvegarder le fichier
                        $resultat = $groupes->asXML('xmls/groups.xml');
                        
                        if ($resultat) {
                            header('Location: views/view.php?success=group_deleted');
                        } else {
                            header('Location: views/view.php?error=group_delete_failed');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized_group_action');
                    }
                } else {
                    header('Location: views/view.php?error=group_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_group_id');
            }
            exit;
    
        case 'retirer_membre':
            if (isset($_POST['id_groupe'], $_POST['id_membre'])) {
                $id_groupe = htmlspecialchars($_POST['id_groupe']);
                $id_membre = htmlspecialchars($_POST['id_membre']);
                
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                
                if ($groupe) {
                    // Vérifier que l'utilisateur connecté est admin ou coadmin
                    $est_admin = (string)$groupe->admin_id === $id_utilisateur;
                    $coadmins = isset($groupe->coadmins) ? explode(',', (string)$groupe->coadmins) : [];
                    $est_coadmin = in_array($id_utilisateur, $coadmins);
                    
                    if ($est_admin || $est_coadmin) {
                        // Vérifier que le membre existe dans le groupe
                        $membre_existe = false;
                        foreach ($groupe->member_id as $id_membre_groupe) {
                            if ((string)$id_membre_groupe === $id_membre) {
                                $membre_existe = true;
                                break;
                            }
                        }
                        
                        if ($membre_existe) {
                            // Retirer le membre du groupe (version sûre)
                            $new_member_ids = [];
                            foreach ($groupe->member_id as $id_membre_groupe) {
                                if ((string)$id_membre_groupe !== $id_membre) {
                                    $new_member_ids[] = (string)$id_membre_groupe;
                                }
                            }
                            // Supprimer tous les <member_id>
                            unset($groupe->member_id);
                            // Réajouter les membres restants
                            foreach ($new_member_ids as $mid) {
                                $groupe->addChild('member_id', $mid);
                            }
                            // Si le membre retiré était admin, transférer l'admin à un autre membre
                            if ((string)$groupe->admin_id === $id_membre) {
                                if (!empty($new_member_ids)) {
                                    $groupe->admin_id = $new_member_ids[0];
                                    // Supprimer les coadmins si l'admin est retiré
                                    unset($groupe->coadmins);
                                }
                            }
                            // Retirer le membre des coadmins s'il l'était
                            if (isset($groupe->coadmins)) {
                                $coadmin_list = explode(',', (string)$groupe->coadmins);
                                $coadmin_list = array_diff($coadmin_list, [$id_membre]);
                                if (!empty($coadmin_list)) {
                                    $groupe->coadmins = implode(',', $coadmin_list);
                                } else {
                                    unset($groupe->coadmins);
                                }
                            }
                            // Sauvegarder le fichier
                            $resultat = $groupes->asXML('xmls/groups.xml');
                            if ($resultat) {
                                header('Location: views/view.php?success=member_removed');
                            } else {
                                header('Location: views/view.php?error=member_remove_failed');
                            }
                        } else {
                            header('Location: views/view.php?error=member_not_found');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized_group_action');
                    }
                } else {
                    header('Location: views/view.php?error=group_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_group_data');
            }
            exit;
            case 'ajouter_coadmin':
            if (isset($_POST['id_groupe'], $_POST['id_coadmin'])) {
                $id_groupe = htmlspecialchars($_POST['id_groupe']);
                $id_coadmin = htmlspecialchars($_POST['id_coadmin']);
                
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                
                if ($groupe) {
                    // Vérifier que l'utilisateur connecté est admin
                    if ((string)$groupe->admin_id === $id_utilisateur) {
                        // Vérifier que le coadmin est membre du groupe
                        $est_membre = false;
                        foreach ($groupe->member_id as $id_membre) {
                            if ((string)$id_membre === $id_coadmin) {
                                $est_membre = true;
                                break;
                            }
                        }
                        
                        if ($est_membre) {
                            // Ajouter le coadmin
                            $coadmins = isset($groupe->coadmins) ? explode(',', (string)$groupe->coadmins) : [];
                            if (!in_array($id_coadmin, $coadmins)) {
                                $coadmins[] = $id_coadmin;
                                $groupe->coadmins = implode(',', $coadmins);
                                
                                // Sauvegarder le fichier
                                $resultat = $groupes->asXML('xmls/groups.xml');
                                
                                if ($resultat) {
                                    header('Location: views/view.php?success=coadmin_added');
                                } else {
                                    header('Location: views/view.php?error=coadmin_manage_failed');
                                }
                            } else {
                                header('Location: views/view.php?error=coadmin_already_exists');
                            }
                        } else {
                            header('Location: views/view.php?error=member_not_found');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized_group_action');
                    }
                } else {
                    header('Location: views/view.php?error=group_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_group_data');
            }
            exit;

        case 'retirer_coadmin':
            if (isset($_POST['id_groupe'], $_POST['id_coadmin'])) {
                $id_groupe = htmlspecialchars($_POST['id_groupe']);
                $id_coadmin = htmlspecialchars($_POST['id_coadmin']);
                
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                
                if ($groupe) {
                    // Vérifier que l'utilisateur connecté est admin
                    if ((string)$groupe->admin_id === $id_utilisateur) {
                        // Retirer le coadmin
                        if (isset($groupe->coadmins)) {
                    $coadmins = explode(',', (string)$groupe->coadmins);
                            $coadmins = array_diff($coadmins, [$id_coadmin]);
                            if (!empty($coadmins)) {
                        $groupe->coadmins = implode(',', $coadmins);
                            } else {
                                unset($groupe->coadmins);
                            }
                            
                            // Sauvegarder le fichier
                            $resultat = $groupes->asXML('xmls/groups.xml');
                            
                            if ($resultat) {
                                header('Location: views/view.php?success=coadmin_removed');
                            } else {
                                header('Location: views/view.php?error=coadmin_manage_failed');
                            }
                        } else {
                            header('Location: views/view.php?error=coadmin_not_found');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized_group_action');
                    }
                } else {
                    header('Location: views/view.php?error=group_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_group_data');
            }
            exit;

        case 'quitter_groupe':
            if (isset($_POST['id_groupe'])) {
                $id_groupe = htmlspecialchars($_POST['id_groupe']);
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                if ($groupe) {
                    // Vérifier que l'utilisateur est membre du groupe
                    $est_membre = false;
                    foreach ($groupe->member_id as $id_membre_groupe) {
                        if ((string)$id_membre_groupe === $id_utilisateur) {
                            $est_membre = true;
                            break;
                        }
                    }
                    if ($est_membre) {
                        // Retirer l'utilisateur du groupe (sans créer de <member_id/> vide)
                        $member_ids = [];
                        foreach ($groupe->member_id as $id_membre_groupe) {
                            if ((string)$id_membre_groupe !== $id_utilisateur && trim((string)$id_membre_groupe) !== '') {
                                $member_ids[] = (string)$id_membre_groupe;
                            }
                        }
                        unset($groupe->member_id);
                        foreach ($member_ids as $id_membre_groupe) {
                            $groupe->addChild('member_id', $id_membre_groupe);
                        }
                        // Si l'utilisateur était admin, transférer l'admin à un autre membre
                        if ((string)$groupe->admin_id === $id_utilisateur) {
                            if (!empty($member_ids)) {
                                $groupe->admin_id = $member_ids[0];
                                unset($groupe->coadmins);
                            } else {
                                // Si plus aucun membre, on peut supprimer le groupe ou laisser l'admin seul (ici on laisse le groupe)
                            }
                        }
                        // Retirer l'utilisateur des coadmins s'il l'était
                        if (isset($groupe->coadmins)) {
                            $coadmin_list = explode(',', (string)$groupe->coadmins);
                            $coadmin_list = array_diff($coadmin_list, [$id_utilisateur]);
                            if (!empty($coadmin_list)) {
                                $groupe->coadmins = implode(',', $coadmin_list);
                            } else {
                                unset($groupe->coadmins);
                            }
                        }
                        $resultat = $groupes->asXML('xmls/groups.xml');
                        if ($resultat) {
                            header('Location: views/view.php?success=group_left');
                        } else {
                            header('Location: views/view.php?error=group_leave_failed');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized_group_action');
                    }
                } else {
                    header('Location: views/view.php?error=group_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_group_id');
            }
            exit;

        case 'lister_membres':
            if (isset($_GET['id_groupe'])) {
                $id_groupe = htmlspecialchars($_GET['id_groupe']);
                
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                
                if ($groupe) {
                    // Vérifier que l'utilisateur est membre du groupe
                    $est_membre = false;
                    foreach ($groupe->member_id as $id_membre_groupe) {
                        if ((string)$id_membre_groupe === $id_utilisateur) {
                            $est_membre = true;
                            break;
                        }
                    }
                    
                    if ($est_membre) {
                        echo "<div style='padding: 10px;'>";
                        echo "<h4>Membres du groupe : " . htmlspecialchars($groupe->name) . "</h4>";
                        echo "<div style='max-height: 250px; overflow-y: auto;'>";
                        
                        foreach ($groupe->member_id as $id_membre_groupe) {
                            $membre = $utilisateurs->xpath("//user[id='$id_membre_groupe']")[0];
                            if ($membre) {
                                $est_admin = (string)$groupe->admin_id === $id_membre_groupe;
                                $coadmins = isset($groupe->coadmins) ? explode(',', (string)$groupe->coadmins) : [];
                                $est_coadmin = in_array($id_membre_groupe, $coadmins);
                                
                                echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;'>";
                                echo "<div>";
                                echo "<strong>" . htmlspecialchars($membre->prenom . ' ' . $membre->nom) . "</strong>";
                                echo "<br><small>" . htmlspecialchars($membre->telephone) . "</small>";
                                echo "</div>";
                                echo "<div>";
                                if ($est_admin) {
                                    echo "<span style='background: #28a745; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Admin</span>";
                                } elseif ($est_coadmin) {
                                    echo "<span style='background: #ffc107; color: black; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Co-Admin</span>";
                                } else {
                                    echo "<span style='background: #6c757d; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;'>Membre</span>";
                                }
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                        
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<p>Vous n'êtes pas membre de ce groupe.</p>";
                    }
                } else {
                    echo "<p>Groupe introuvable.</p>";
                }
            } else {
                echo "<p>ID du groupe manquant.</p>";
            }
            exit;

        case 'obtenir_membres_groupe':
            if (isset($_GET['id_groupe'])) {
                $id_groupe = htmlspecialchars($_GET['id_groupe']);
                
                // Vérifier que le groupe existe
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                
                if ($groupe) {
                    // Vérifier que l'utilisateur est admin ou coadmin
                    $est_admin = (string)$groupe->admin_id === $id_utilisateur;
                    $coadmins = isset($groupe->coadmins) ? explode(',', (string)$groupe->coadmins) : [];
                    $est_coadmin = in_array($id_utilisateur, $coadmins);
                    
                    if ($est_admin || $est_coadmin) {
                        echo "<div style='padding: 10px;'>";
                        
                        // Pour la gestion des co-admins
                        if (isset($_GET['action_type']) && $_GET['action_type'] === 'coadmin') {
                            echo "<h4>Gérer les co-admins</h4>";
                            echo "<div style='max-height: 300px; overflow-y: auto;'>";
                            
                            foreach ($groupe->member_id as $id_membre) {
                                if ((string)$id_membre !== $id_utilisateur) { // Ne pas afficher l'admin principal
                                    $membre = $utilisateurs->xpath("//user[id='$id_membre']")[0];
                                    if ($membre) {
                                        $est_coadmin = in_array($id_membre, $coadmins);
                                        
                                        echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;'>";
                                        echo "<div>";
                                        echo "<strong>" . htmlspecialchars($membre->prenom . ' ' . $membre->nom) . "</strong>";
                                        echo "<br><small>" . htmlspecialchars($membre->telephone) . "</small>";
                                        echo "</div>";
                                        echo "<div>";
                                        if ($est_coadmin) {
                                            echo "<form method='post' action='../api.php' style='display: inline;'>";
                                            echo "<input type='hidden' name='action' value='retirer_coadmin'>";
                                            echo "<input type='hidden' name='id_groupe' value='" . htmlspecialchars($id_groupe) . "'>";
                                            echo "<input type='hidden' name='id_coadmin' value='" . htmlspecialchars($id_membre) . "'>";
                                            echo "<button type='submit' style='background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>Retirer co-admin</button>";
                                            echo "</form>";
                                        } else {
                                            echo "<form method='post' action='../api.php' style='display: inline;'>";
                                            echo "<input type='hidden' name='action' value='ajouter_coadmin'>";
                                            echo "<input type='hidden' name='id_groupe' value='" . htmlspecialchars($id_groupe) . "'>";
                                            echo "<input type='hidden' name='id_coadmin' value='" . htmlspecialchars($id_membre) . "'>";
                                            echo "<button type='submit' style='background: #28a745; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>Ajouter co-admin</button>";
                                            echo "</form>";
                                        }
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                            }
                            
                            echo "</div>";
                        }
                        // Pour le retrait de membres
                        elseif (isset($_GET['action_type']) && $_GET['action_type'] === 'remove') {
                            echo "<h4>Sélectionner un membre à retirer</h4>";
                            echo "<div style='max-height: 300px; overflow-y: auto;'>";
                            
                            foreach ($groupe->member_id as $id_membre) {
                                if ((string)$id_membre !== $id_utilisateur) { // Ne pas pouvoir se retirer soi-même
                                    $membre = $utilisateurs->xpath("//user[id='$id_membre']")[0];
                                    if ($membre) {
                                        $est_admin = (string)$groupe->admin_id === $id_membre;
                                        
                                        echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;'>";
                                        echo "<div>";
                                        echo "<strong>" . htmlspecialchars($membre->prenom . ' ' . $membre->nom) . "</strong>";
                                        echo "<br><small>" . htmlspecialchars($membre->telephone) . "</small>";
                                        if ($est_admin) {
                                            echo "<br><small style='color: #dc3545;'>⚠️ Admin principal - ne peut pas être retiré</small>";
                                        }
                                        echo "</div>";
                                        echo "<div>";
                                        if (!$est_admin) {
                                            echo "<form method='post' action='../api.php' style='display: inline;'>";
                                            echo "<input type='hidden' name='action' value='retirer_membre'>";
                                            echo "<input type='hidden' name='id_groupe' value='" . htmlspecialchars($id_groupe) . "'>";
                                            echo "<input type='hidden' name='id_membre' value='" . htmlspecialchars($id_membre) . "'>";
                                            echo "<button type='submit' onclick='return confirm(\"Retirer " . htmlspecialchars($membre->prenom . ' ' . $membre->nom) . " du groupe ?\")' style='background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>Retirer</button>";
                                            echo "</form>";
                                        }
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                            }
                            
                            echo "</div>";
                        }
                        else {
                            echo "<h4>Gérer les co-admins</h4>";
                            echo "<div style='max-height: 300px; overflow-y: auto;'>";
                            
                            foreach ($groupe->member_id as $id_membre) {
                                if ((string)$id_membre !== $id_utilisateur) { // Ne pas afficher l'admin principal
                                    $membre = $utilisateurs->xpath("//user[id='$id_membre']")[0];
                                    if ($membre) {
                                        $est_coadmin = in_array($id_membre, $coadmins);
                                        
                                        echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;'>";
                                        echo "<div>";
                                        echo "<strong>" . htmlspecialchars($membre->prenom . ' ' . $membre->nom) . "</strong>";
                                        echo "<br><small>" . htmlspecialchars($membre->telephone) . "</small>";
                                        echo "</div>";
                                        echo "<div>";
                                        if ($est_coadmin) {
                                            echo "<form method='post' action='../api.php' style='display: inline;'>";
                                            echo "<input type='hidden' name='action' value='retirer_coadmin'>";
                                            echo "<input type='hidden' name='id_groupe' value='" . htmlspecialchars($id_groupe) . "'>";
                                            echo "<input type='hidden' name='id_coadmin' value='" . htmlspecialchars($id_membre) . "'>";
                                            echo "<button type='submit' style='background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>Retirer co-admin</button>";
                                            echo "</form>";
                                        } else {
                                            echo "<form method='post' action='../api.php' style='display: inline;'>";
                                            echo "<input type='hidden' name='action' value='ajouter_coadmin'>";
                                            echo "<input type='hidden' name='id_groupe' value='" . htmlspecialchars($id_groupe) . "'>";
                                            echo "<input type='hidden' name='id_coadmin' value='" . htmlspecialchars($id_membre) . "'>";
                                            echo "<button type='submit' style='background: #28a745; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>Ajouter co-admin</button>";
                                            echo "</form>";
                                        }
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                            }
                            
                            echo "</div>";
                        }
                        
                        echo "</div>";
                    } else {
                        echo "<p>Vous n'avez pas les permissions pour gérer ce groupe.</p>";
                    }
                } else {
                    echo "<p>Groupe introuvable.</p>";
                }
            } else {
                echo "<p>ID du groupe manquant.</p>";
            }
            exit;

        case 'ajouter_membre':
            if (isset($_POST['id_groupe'], $_POST['id_nouveau_membre'])) {
                $id_groupe = htmlspecialchars($_POST['id_groupe']);
                $id_nouveau_membre = htmlspecialchars($_POST['id_nouveau_membre']);
                $groupe = $groupes->xpath("//group[id='$id_groupe']")[0];
                if ($groupe) {
                    $est_admin = (string)$groupe->admin_id === $id_utilisateur;
                    $coadmins = isset($groupe->coadmins) ? explode(',', (string)$groupe->coadmins) : [];
                    $est_coadmin = in_array($id_utilisateur, $coadmins);
                    if ($est_admin || $est_coadmin) {
                        $already_member = false;
                        foreach ($groupe->member_id as $id_membre_groupe) {
                            if ((string)$id_membre_groupe === $id_nouveau_membre) {
                                $already_member = true;
                                break;
                            }
                        }
                        if (!$already_member && (string)$groupe->admin_id !== $id_nouveau_membre) {
                            $groupe->addChild('member_id', $id_nouveau_membre);
                            $groupes->asXML('xmls/groups.xml');
                            header('Location: views/view.php?success=member_added');
                        } else {
                            header('Location: views/view.php?error=member_already_in_group');
                        }
                    } else {
                        header('Location: views/view.php?error=unauthorized_group_action');
                    }
                } else {
                    header('Location: views/view.php?error=group_not_found');
                }
            } else {
                header('Location: views/view.php?error=missing_group_data');
            }
            exit;

}

?>