<?php require_once '../controller.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jotaay - Messagerie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>
    <?php
    // Notifications d'erreur et de succès
    if (isset($_GET['error'])) {
        $error_message = '';
        switch ($_GET['error']) {
            case 'minimum_two_members':
                $error_message = 'Erreur : Vous devez sélectionner au moins deux contacts pour créer un groupe.';
                break;
            case 'contact_not_found':
                $error_message = 'Erreur : Contact introuvable.';
                break;
            case 'unauthorized':
                $error_message = 'Erreur : Vous n\'êtes pas autorisé à supprimer ce contact.';
                break;
            case 'delete_failed':
                $error_message = 'Erreur : Échec de la suppression du contact.';
                break;
            case 'missing_contact_id':
                $error_message = 'Erreur : ID du contact manquant.';
                break;
            case 'contact_already_exists':
                $error_message = 'Erreur : Ce contact existe déjà dans votre liste.';
                break;
            case 'user_not_found':
                $error_message = 'Erreur : Aucun utilisateur trouvé avec ce numéro de téléphone.';
                break;
            case 'cannot_add_self':
                $error_message = 'Erreur : Vous ne pouvez pas vous ajouter vous-même comme contact.';
                break;
            case 'add_failed':
                $error_message = 'Erreur : Échec de l\'ajout du contact.';
                break;
            case 'missing_contact_data':
                $error_message = 'Erreur : Données du contact manquantes.';
                break;
            case 'group_not_found':
                $error_message = 'Erreur : Groupe introuvable.';
                break;
            case 'group_delete_failed':
                $error_message = 'Erreur : Échec de la suppression du groupe.';
                break;
            case 'group_leave_failed':
                $error_message = 'Erreur : Échec de la sortie du groupe.';
                break;
            case 'member_remove_failed':
                $error_message = 'Erreur : Échec du retrait du membre.';
                break;
            case 'coadmin_manage_failed':
                $error_message = 'Erreur : Échec de la gestion des co-admins.';
                break;
            case 'unauthorized_group_action':
                $error_message = 'Erreur : Vous n\'êtes pas autorisé à effectuer cette action.';
                break;
            case 'member_not_found':
                $error_message = 'Erreur : Membre introuvable dans le groupe.';
                break;
            case 'missing_group_id':
                $error_message = 'Erreur : ID du groupe manquant.';
                break;
            case 'missing_group_data':
                $error_message = 'Erreur : Données du groupe manquantes.';
                break;
            case 'coadmin_already_exists':
                $error_message = 'Erreur : Cet utilisateur est déjà co-admin du groupe.';
                break;
            case 'coadmin_not_found':
                $error_message = 'Erreur : Co-admin introuvable dans le groupe.';
                break;
            case 'group_creation_failed':
                $error_message = 'Erreur : Échec de la création du groupe.';
                break;
            case 'update_failed':
                $error_message = 'Erreur : Échec de la mise à jour du profil.';
                break;
            case 'missing_profile_data':
                $error_message = 'Erreur : Données du profil manquantes.';
                break;
            case 'message_send_failed':
                $error_message = 'Erreur : Échec de l\'envoi du message.';
                break;
            case 'missing_message_data':
                $error_message = 'Erreur : Données du message manquantes.';
                break;
            case 'telephone_already_used':
                $error_message = 'Erreur : Ce numéro de téléphone est déjà utilisé par un autre utilisateur.';
                break;
            default:
                $error_message = 'Une erreur est survenue.';
        }
        if ($error_message) {
            echo "<div class='notification' style='background: #f44336;'>$error_message</div>";
        }
    }
    
    if (isset($_GET['success'])) {
        $success_message = '';
        switch ($_GET['success']) {
            case 'contact_deleted':
                $success_message = '✅ Contact supprimé avec succès !';
                break;
            case 'contact_added':
                $success_message = '✅ Contact ajouté avec succès !';
                break;
            case 'contact_updated':
                $success_message = '✅ Contact modifié avec succès !';
                break;
            case 'message_sent':
                $success_message = '✅ Message envoyé avec succès !';
                break;
            case 'group_created':
                $success_message = '✅ Groupe créé avec succès !';
                break;
            case 'group_deleted':
                $success_message = '✅ Groupe supprimé avec succès !';
                break;
            case 'group_left':
                $success_message = '✅ Vous avez quitté le groupe avec succès !';
                break;
            case 'member_removed':
                $success_message = '✅ Membre retiré du groupe avec succès !';
                break;
            case 'coadmin_added':
                $success_message = '✅ Co-admin ajouté avec succès !';
                break;
            case 'coadmin_removed':
                $success_message = '✅ Co-admin retiré avec succès !';
                break;
            case 'profile_updated':
                $success_message = '✅ Profil mis à jour avec succès !';
                break;
            default:
                $success_message = 'Opération réussie !';
        }
        if ($success_message) {
            echo "<div class='notification' style='background: #4caf50;'>$success_message</div>";
        }
    }
    ?>
    
    <div class="app-container">
        <!-- Sidebar moderne -->
        <div class="modern-sidebar">
            <!-- En-tête utilisateur -->
            <div class="sidebar-header">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php if ($utilisateur_courant->profile_photo && $utilisateur_courant->profile_photo != 'default.jpg') { ?>
                            <img src="../uploads/<?php echo htmlspecialchars($utilisateur_courant->profile_photo); ?>" alt="Photo de profil">
                        <?php } else { ?>
                            <div class="avatar-placeholder">
                                <img src="../assets/img/JOTAAY.png" alt="JOTAAY">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="user-details">
                        <h2 class="user-name"><?php echo htmlspecialchars($utilisateur_courant->username); ?></h2>
                        <p class="user-status">En ligne</p>
                    </div>
                </div>
                <div class="user-actions">
                    <button class="action-btn" onclick="afficherModalEditionProfil()" title="Paramètres">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06-.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </button>
                    <button onclick="confirmerDeconnexion()" class="action-btn" title="Déconnexion">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Barre de recherche -->
            <div class="search-container">
                <div class="search-input-wrapper">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" class="search-input" placeholder="Rechercher ou commencer une nouvelle discussion">
                </div>
            </div>

            <!-- Menu de navigation -->
            <div class="sidebar-nav">
                <button class="nav-tab active" data-tab="chats">
                    <svg class="nav-tab-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span class="nav-tab-text">Chats</span>
                </button>
                <button class="nav-tab" data-tab="contacts">
                    <svg class="nav-tab-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="nav-tab-text">Contacts</span>
                </button>
                <button class="nav-tab" data-tab="groups">
                    <svg class="nav-tab-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="nav-tab-text">Groupes</span>
                </button>
            </div>

            <!-- Contenu de la sidebar -->
            <div class="sidebar-content">
                <!-- Onglet Chats -->
                <div class="tab-panel active" id="chats-panel">
                    <div class="chat-list">
                        <?php
                        // Récupérer toutes les conversations (contacts + groupes)
                        $conversations = [];
                        
                        // Conversations avec contacts
                        $contacts_utilisateur = $contacts->xpath("//contact[user_id='$id_utilisateur']");
                        foreach ($contacts_utilisateur as $contact) {
                            $utilisateur_contact = $utilisateurs->xpath("//user[telephone='{$contact->contact_telephone}']");
                            if (!empty($utilisateur_contact)) {
                                $utilisateur_contact = $utilisateur_contact[0];
                                $id_utilisateur_contact = obtenirIdUtilisateurParTelephone($utilisateurs, $contact->contact_telephone);
                                $messages_conversation = $messages->xpath("//message[(sender_id='$id_utilisateur' and recipient='$contact->contact_telephone') or (sender_id='$id_utilisateur_contact' and recipient='$utilisateur_courant->telephone')]");
                                
                                $dernier_message = null;
                                if (!empty($messages_conversation)) {
                                    usort($messages_conversation, function($a, $b) {
                                        return strtotime((string)$b->timestamp) - strtotime((string)$a->timestamp);
                                    });
                                    $dernier_message = $messages_conversation[0];
                                }
                                
                                $conversations[] = [
                                    'type' => 'contact',
                                    'id' => $contact->contact_id,
                                    'nom' => $contact->contact_name,
                                    'photo' => $utilisateur_contact->profile_photo,
                                    'dernier_message' => $dernier_message,
                                    'timestamp' => $dernier_message ? (string)$dernier_message->timestamp : '0'
                                ];
                            }
                        }
                        
                        // Conversations de groupes
                        foreach ($groupes->group as $groupe) {
                            $est_admin = ((string)$groupe->id_admin === $id_utilisateur);
                            $est_membre = false;
                            foreach ($groupe->member_id as $id_membre) {
                                if ((string)$id_membre === $id_utilisateur) {
                                    $est_membre = true;
                                    break;
                                }
                            }
                            
                            if ($est_admin || $est_membre) {
                                $messages_groupe = $messages->xpath("//message[recipient_group='{$groupe->group_id}']");
                                $dernier_message = null;
                                if (!empty($messages_groupe)) {
                                    usort($messages_groupe, function($a, $b) {
                                        return strtotime((string)$b->timestamp) - strtotime((string)$a->timestamp);
                                    });
                                    $dernier_message = $messages_groupe[0];
                                }
                                
                                $conversations[] = [
                                    'type' => 'groupe',
                                    'id' => $groupe->group_id,
                                    'nom' => $groupe->group_name,
                                    'photo' => $groupe->group_photo ?? '',
                                    'dernier_message' => $dernier_message,
                                    'timestamp' => $dernier_message ? (string)$dernier_message->timestamp : '0'
                                ];
                            }
                        }
                        
                        // Trier par timestamp décroissant
                        usort($conversations, function($a, $b) {
                            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
                        });
                        
                        foreach ($conversations as $conv) {
                        ?>
                        <div class="contact-item" onclick="window.location.href='?conversation=<?php echo $conv['type']; ?>:<?php echo $conv['id']; ?>'">
                            <div class="contact-avatar">
                                <?php if ($conv['photo'] && $conv['photo'] != 'default.jpg') { ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($conv['photo']); ?>" alt="Photo">
                                <?php } else { ?>
                                    <?php echo strtoupper(substr($conv['nom'], 0, 1)); ?>
                                <?php } ?>
                            </div>
                            <div class="contact-info">
                                <div class="contact-name">
                                    <?php echo htmlspecialchars($conv['nom']); ?>
                                    <?php if ($conv['type'] === 'groupe') { ?>
                                        <span style="background: var(--primary-green); color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: 8px;">Groupe</span>
                                    <?php } ?>
                                </div>
                                <div class="contact-meta">
                                    <?php if ($conv['dernier_message']) { ?>
                                        <?php 
                                        $expediteur = $utilisateurs->xpath("//user[user_id='{$conv['dernier_message']->sender_id}']");
                                        $expediteur = !empty($expediteur) ? $expediteur[0] : null;
                                        $envoye_par_moi = $conv['dernier_message']->sender_id == $id_utilisateur;
                                        ?>
                                        <span style="font-size: 13px; color: var(--text-secondary);">
                                            <?php echo $envoye_par_moi ? 'Vous: ' : ($expediteur && $conv['type'] === 'groupe' ? htmlspecialchars($expediteur->username . ': ') : ''); ?>
                                            <?php echo htmlspecialchars(substr($conv['dernier_message']->content, 0, 30)); ?>
                                            <?php if (strlen($conv['dernier_message']->content) > 30) echo '...'; ?>
                                        </span>
                                        <span style="font-size: 11px; color: var(--text-muted); margin-left: 8px;">
                                            <?php echo date('H:i', strtotime($conv['timestamp'])); ?>
                                        </span>
                                    <?php } else { ?>
                                        <span style="font-size: 13px; color: var(--text-muted);">Aucun message</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <?php if (empty($conversations)) { ?>
                        <div class="empty-contacts">
                            <svg class="empty-contacts-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
                            <h3 class="empty-contacts-title">Aucune conversation</h3>
                            <p class="empty-contacts-message">
                                Commencez à discuter avec vos contacts ou créez un groupe.
                            </p>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Onglet Contacts -->
                <div class="tab-panel" id="contacts-panel">
                    <?php include 'contacts_view.php'; ?>
                </div>

                <!-- Onglet Groupes -->
                <div class="tab-panel" id="groups-panel">
                    <?php include 'groups_view.php'; ?>
                </div>
            </div>
        </div>

        <!-- Vue principale -->
        <div class="main-view">
            <?php
            $current_conversation = $_GET['conversation'] ?? '';
            $messages_to_show = [];
            $conversation_name = '';
            $conversation_avatar = '';
            
            if ($current_conversation) {
               
                list($type, $id) = explode(':', $current_conversation);
                 
                if ($type === 'contact') {
                    // Récupérer les informations du contact par son ID
                    $contact_info_result = $contacts->xpath("//contact[contact_id='$id']");
                    $contact_info = !empty($contact_info_result) ? $contact_info_result[0] : null;
                   
                    
                    if ($contact_info) {
                        // Récupérer l'ID de l'utilisateur contact par son numéro de téléphone
                        $contact_user_id = obtenirIdUtilisateurParTelephone($utilisateurs, $contact_info->contact_telephone);
                        $contact_user = $utilisateurs->xpath("//user[telephone='{$contact_info->contact_telephone}']");
                        $contact_user = !empty($contact_user) ? $contact_user[0] : null;
                        
                        if ($contact_user_id) {
                            // Récupérer les messages entre les deux utilisateurs
                            $messages_to_show = $messages->xpath("//message[(sender_id='$id_utilisateur' and recipient='{$contact_info->contact_telephone}') or (sender_id='$contact_user_id' and recipient='$utilisateur_courant->telephone')]");
                            // var_dump($messages_to_show);
                            // Trier les messages par timestamp
                            usort($messages_to_show, function($a, $b) {
                                return strtotime((string)$a->timestamp) - strtotime((string)$b->timestamp);
                            });
                            
                            // Marquer comme lus tous les messages reçus non lus
                            foreach ($messages->xpath("//message[sender_id='$contact_user_id' and recipient='$utilisateur_courant->telephone']") as $msg) {
                                if (!isset($msg->read_by) || !in_array($id_utilisateur, explode(',', (string)$msg->read_by))) {
                                    $read_by = isset($msg->read_by) ? (string)$msg->read_by : '';
                                    $read_by_arr = $read_by ? explode(',', $read_by) : [];
                                    $read_by_arr[] = $id_utilisateur;
                                    $msg->read_by = implode(',', array_unique($read_by_arr));
                                }
                            }
                            $messages->asXML('../xmls/messages.xml');
                        } else {
                            $messages_to_show = [];
                        }
                        
                        $conversation_name = $contact_info->contact_name;
                        $conversation_avatar = $contact_user ? $contact_user->profile_photo : '';
                    } else {
                        $messages_to_show = [];
                    }
                } elseif ($type === 'groupe') {
                    // Récupérer les informations du groupe
                    $groupe_info_result = $groupes->xpath("//group[group_id='$id']");
                    $groupe_info = !empty($groupe_info_result) ? $groupe_info_result[0] : null;
                    
                    if ($groupe_info) {
                        // Récupérer les messages du groupe
                        $messages_to_show = $messages->xpath("//message[recipient_group='$id']");
                        
                        // Trier les messages par timestamp
                        usort($messages_to_show, function($a, $b) {
                            return strtotime((string)$a->timestamp) - strtotime((string)$b->timestamp);
                        });
                        
                        $conversation_name = $groupe_info->name;
                        $conversation_avatar = $groupe_info->group_photo ?? '';
                    }
                }
            }
            
            if ($current_conversation && !empty($messages_to_show)) {
                // Afficher la conversation
                ?>
                <div class="chat-container">
                    <!-- En-tête de la conversation -->
                    <div class="chat-header">
                        <div class="chat-avatar">
                            <?php if ($conversation_avatar && $conversation_avatar != 'default.jpg') { ?>
                                <img src="../uploads/<?php echo htmlspecialchars($conversation_avatar); ?>" alt="Avatar">
                            <?php } else { ?>
                                <?php echo strtoupper(substr($conversation_name, 0, 1)); ?>
                            <?php } ?>
                        </div>
                        <div class="chat-info">
                            <h3><?php echo htmlspecialchars($conversation_name); ?></h3>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="chat-messages" id="chat-container">
                        <?php foreach ($messages_to_show as $message) { ?>
                            <div class="message-bubble <?php echo $message->sender_id == $id_utilisateur ? 'sent' : 'received'; ?>">
                                <div class="message-content">
                                    <?php if (isset($message->content) && !empty($message->content)) { ?>
                                        <p><?php echo htmlspecialchars($message->content); ?></p>
                                    <?php } ?>
                                    
                                    <?php if (isset($message->file) && !empty($message->file)) { ?>
                                        <div class="message-file">
                                            <?php
                                            $extension = pathinfo($message->file, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $isVideo = in_array(strtolower($extension), ['mp4', 'avi', 'mov', 'wmv']);
                                            ?>
                                            
                                            <?php if ($isImage) { ?>
                                                <img src="../uploads/<?php echo htmlspecialchars($message->file); ?>" alt="Image" class="message-image" onclick="ouvrirModalImage('../uploads/<?php echo htmlspecialchars($message->file); ?>')">
                                            <?php } elseif ($isVideo) { ?>
                                                <video controls class="message-video">
                                                    <source src="../uploads/<?php echo htmlspecialchars($message->file); ?>" type="video/<?php echo $extension; ?>">
                                                </video>
                                            <?php } else { ?>
                                                <a href="../uploads/<?php echo htmlspecialchars($message->file); ?>" download class="file-download">
                                                    <span class="file-icon">📎</span>
                                                    <span class="file-name"><?php echo htmlspecialchars($message->file); ?></span>
                                                    <span class="file-size">Télécharger</span>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    
                                    <div class="message-meta">
                                        <span class="message-time"><?php echo date('H:i', strtotime((string)$message->timestamp ?? 'now')); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Zone de saisie -->
                    <div class="chat-input">
                        <form action="../api.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="send_message">
                            <input type="hidden" name="recipient" value="<?php echo isset($type) && $type === 'contact' && isset($contact_info) ? htmlspecialchars($contact_info->contact_telephone) : (isset($id) ? htmlspecialchars($id) : ''); ?>">
                            <input type="hidden" name="recipient_type" value="<?php echo isset($type) ? $type : ''; ?>">
                            
                            <div class="input-container">
                                <button type="button" class="attachment-btn">
                                    <span>📎</span>
                                </button>
                                <input type="file" name="file" class="file-input" accept="image/*,video/*,application/*">
                                
                                <textarea name="content" class="message-input" placeholder="Tapez un message..." rows="1"></textarea>
                                
                                <button type="button" class="emoji-btn">
                                    <span>😊</span>
                                </button>
                                
                                <button type="submit" class="send-btn">
                                    <span>➤</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            } else {
                // État vide - Message de bienvenue
                ?>
                <div class="welcome-container">
                    <div class="welcome-content">
                        <svg class="welcome-icon" width="80" height="80" viewBox="0 0 24 24" fill="var(--primary-color)" stroke="none">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            <line x1="8" y1="9" x2="16" y2="9" stroke="white" stroke-width="2"/>
                            <line x1="8" y1="12" x2="14" y2="12" stroke="white" stroke-width="2"/>
                            <line x1="8" y1="15" x2="12" y2="15" stroke="white" stroke-width="2"/>
                        </svg>
                        <h1 class="welcome-title">
                            Bienvenue sur<br>
                            <span class="welcome-brand">Jotaay</span>
                        </h1>
                        <p class="welcome-message">
                            Sélectionnez une conversation pour<br>
                            commencer à discuter avec vos contacts<br>
                            et groupes.
                        </p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <!-- Modal pour afficher les images -->
    <div id="imageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <img id="modalImage" src="/placeholder.svg" alt="Image" style="max-width: 100%; max-height: 80vh;">
            <button type="button" onclick="closeImageModal()" class="modal-close">&times;</button>
        </div>
    </div>

    <!-- Modal pour l'édition du profil -->
    <div id="profileEditModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Modifier le profil</h3>
                <button type="button" onclick="fermerModalEditionProfil()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formulaireEditionProfil" action="../api.php" method="post" enctype="multipart/form-data" class="modern-form">
                    <input type="hidden" name="action" value="mettre_a_jour_profil">
                    <div class="form-group">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($utilisateur_courant->username); ?>" class="form-input" placeholder="Votre nom d'utilisateur" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" value="<?php echo htmlspecialchars($utilisateur_courant->telephone); ?>" class="form-input" pattern="(77|70|78|76)[0-9]{7}" title="Numéro doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Photo de profil</label>
                        <input type="file" name="profile_photo" class="form-input" accept="image/*">
                        <small class="form-help">Formats acceptés : JPG, PNG, GIF. Taille max : 5MB</small>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="modern-btn btn-primary">
                            <span>💾</span> Mettre à jour
                        </button>
                        <button type="button" onclick="fermerModalEditionProfil()" class="modern-btn btn-secondary">
                            <span>❌</span> Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour l'ajout de contact -->
    <div id="contactAddModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ajouter un contact</h3>
                <button type="button" onclick="fermerModalAjoutContact()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formulaireAjoutContact" action="../api.php" method="post" class="modern-form">
                    <input type="hidden" name="action" value="ajouter_contact">
                    
                    <div class="form-group">
                        <label class="form-label">Nom du contact</label>
                        <input type="text" name="contact_name" class="form-input" placeholder="Nom du contact" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Numéro de téléphone</label>
                        <input type="text" name="contact_telephone" class="form-input" pattern="(77|70|78|76)[0-9]{7}" title="Numéro doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres" placeholder="ex: 771234567" required>
                        <small class="form-help">Le numéro doit correspondre à un utilisateur existant</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="modern-btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Ajouter Contact
                        </button>
                        <button type="button" onclick="fermerModalAjoutContact()" class="modern-btn btn-secondary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour l'édition de contact -->
    <div id="contactEditModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Modifier le contact</h3>
                <button type="button" onclick="fermerModalEditionContact()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formulaireEditionContact" action="../api.php" method="post" class="modern-form">
                    <input type="hidden" name="action" value="modifier_contact">
                    <input type="hidden" name="contact_id" id="idEditionContact">
                    <div class="form-group">
                        <label class="form-label">Nouveau nom du contact</label>
                        <input type="text" name="contact_name" id="nomEditionContact" class="form-input" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="modern-btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Modifier
                        </button>
                        <button type="button" onclick="fermerModalEditionContact()" class="modern-btn btn-secondary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour la création de groupe -->
    <div id="groupCreateModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Créer un nouveau groupe</h3>
                <button type="button" onclick="fermerModalCreationGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formulaireCreationGroupe" action="../api.php" method="post" enctype="multipart/form-data" class="modern-form">
                    <input type="hidden" name="action" value="creer_groupe">
                    <div class="form-group">
                        <label class="form-label">Nom du groupe</label>
                        <input type="text" name="group_name" class="form-input" placeholder="Nom du groupe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Photo du groupe</label>
                        <input type="file" name="group_photo" class="form-input" accept="image/*">
                        <small class="form-help">Formats acceptés : JPG, PNG, GIF. Taille max : 5MB</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sélectionner les membres</label>
                        <div class="member-selection-container">
                            <?php
                            foreach ($contacts->xpath("//contact[user_id='$id_utilisateur']") as $contact) {
                                $utilisateur_contact = $utilisateurs->xpath("//user[telephone='{$contact->contact_telephone}']");
                                if (!empty($utilisateur_contact)) {
                                    $utilisateur_contact = $utilisateur_contact[0];
                                    echo "<label class='member-checkbox'>";
                                    echo "<input type='checkbox' name='ids_membres[]' value='" . htmlspecialchars($utilisateur_contact->user_id) . "'>";
                                    echo "<span>" . htmlspecialchars($contact->contact_name) . "</span>";
                                    echo "</label>";
                                }
                            }
                            ?>
                        </div>
                        <small class="form-help">Sélectionnez au moins 2 contacts pour créer un groupe</small>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="modern-btn btn-primary">
                            <span>🏠</span>
                            Créer le Groupe
                        </button>
                        <button type="button" onclick="fermerModalCreationGroupe()" class="modern-btn btn-secondary">
                            <span>❌</span>
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modales pour les actions de groupe -->
    <div id="groupMembersModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupMembersTitle">Membres du groupe</h3>
                <button type="button" onclick="fermerModalMembresGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupMembersContent"></div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalMembresGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Fermer
                    </button><button onclick="afficherModalMembresGroupe('<?php echo $groupe->group_id; ?>')">Voir membres</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour les actions de groupe -->
    <div id="groupActionsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupActionsTitle">Actions de groupe</h3>
                <button type="button" onclick="closeGroupActionsModal()" class="modal-close">&times;</button>
            </div>
            <div id="groupActionsContent" class="modal-body">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
        </div>
    </div>

    <div id="groupCoAdminsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupCoAdminsTitle">Gérer les co-admins</h3>
                <button type="button" onclick="fermerModalCoAdminsGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupCoAdminsContent"></div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalCoAdminsGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="groupRemoveMemberModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupRemoveMemberTitle">Retirer un membre</h3>
                <button type="button" onclick="fermerModalRetirerMembre()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupRemoveMemberContent"></div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalRetirerMembre()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="groupAddMemberModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupAddMemberTitle">Ajouter un membre</h3>
                <button type="button" onclick="fermerModalAjouterMembre()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupAddMemberContent"></div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalAjouterMembre()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="groupDeleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupDeleteTitle">Supprimer le groupe</h3>
                <button type="button" onclick="fermerModalSupprimerGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupDeleteContent"></div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalSupprimerGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="groupLeaveModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupLeaveTitle">Quitter le groupe</h3>
                <button type="button" onclick="fermerModalQuitterGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupLeaveContent"></div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalQuitterGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/global.js"></script>
</body>
</html>
