<?php require_once '../controller.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaxTaan - Messagerie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./../assets/css/modern-app.css">
</head>
<body>
    <?php
    // Gestion des notifications
    $notification = '';
    $notification_type = '';
    
    if (isset($_GET['error'])) {
        $notification_type = 'error';
        switch ($_GET['error']) {
            case 'minimum_two_members':
                $notification = 'Vous devez sélectionner au moins deux contacts pour créer un groupe.';
                break;
            case 'contact_not_found':
                $notification = 'Contact introuvable.';
                break;
            case 'unauthorized':
                $notification = 'Vous n\'êtes pas autorisé à effectuer cette action.';
                break;
            case 'delete_failed':
                $notification = 'Échec de la suppression.';
                break;
            case 'missing_contact_id':
                $notification = 'ID du contact manquant.';
                break;
            case 'contact_already_exists':
                $notification = 'Ce contact existe déjà dans votre liste.';
                break;
            case 'user_not_found':
                $notification = 'Aucun utilisateur trouvé avec ce numéro de téléphone.';
                break;
            case 'cannot_add_self':
                $notification = 'Vous ne pouvez pas vous ajouter vous-même comme contact.';
                break;
            case 'add_failed':
                $notification = 'Échec de l\'ajout du contact.';
                break;
            case 'missing_contact_data':
                $notification = 'Données du contact manquantes.';
                break;
            case 'group_not_found':
                $notification = 'Groupe introuvable.';
                break;
            case 'group_delete_failed':
                $notification = 'Échec de la suppression du groupe.';
                break;
            case 'group_leave_failed':
                $notification = 'Échec de la sortie du groupe.';
                break;
            case 'member_remove_failed':
                $notification = 'Échec du retrait du membre.';
                break;
            case 'coadmin_manage_failed':
                $notification = 'Échec de la gestion des co-admins.';
                break;
            case 'unauthorized_group_action':
                $notification = 'Vous n\'êtes pas autorisé à effectuer cette action.';
                break;
            case 'member_not_found':
                $notification = 'Membre introuvable dans le groupe.';
                break;
            case 'missing_group_id':
                $notification = 'ID du groupe manquant.';
                break;
            case 'missing_group_data':
                $notification = 'Données du groupe manquantes.';
                break;
            case 'coadmin_already_exists':
                $notification = 'Cet utilisateur est déjà co-admin du groupe.';
                break;
            case 'coadmin_not_found':
                $notification = 'Co-admin introuvable dans le groupe.';
                break;
            case 'group_creation_failed':
                $notification = 'Échec de la création du groupe.';
                break;
            case 'update_failed':
                $notification = 'Échec de la mise à jour du profil.';
                break;
            case 'missing_profile_data':
                $notification = 'Données du profil manquantes.';
                break;
            case 'message_send_failed':
                $notification = 'Échec de l\'envoi du message.';
                break;
            case 'missing_message_data':
                $notification = 'Données du message manquantes.';
                break;
            case 'telephone_already_used':
                $notification = 'Ce numéro de téléphone est déjà utilisé par un autre utilisateur.';
                break;
            default:
                $notification = 'Une erreur est survenue.';
        }
    }
    
    if (isset($_GET['success'])) {
        $notification_type = 'success';
        switch ($_GET['success']) {
            case 'contact_deleted':
                $notification = 'Contact supprimé avec succès !';
                break;
            case 'contact_added':
                $notification = 'Contact ajouté avec succès !';
                break;
            case 'contact_updated':
                $notification = 'Contact modifié avec succès !';
                break;
            case 'message_sent':
                $notification = 'Message envoyé avec succès !';
                break;
            case 'group_created':
                $notification = 'Groupe créé avec succès !';
                break;
            case 'group_deleted':
                $notification = 'Groupe supprimé avec succès !';
                break;
            case 'group_left':
                $notification = 'Vous avez quitté le groupe avec succès !';
                break;
            case 'member_removed':
                $notification = 'Membre retiré du groupe avec succès !';
                break;
            case 'coadmin_added':
                $notification = 'Co-admin ajouté avec succès !';
                break;
            case 'coadmin_removed':
                $notification = 'Co-admin retiré avec succès !';
                break;
            case 'profile_updated':
                $notification = 'Profil mis à jour avec succès !';
                break;
            default:
                $notification = 'Opération réussie !';
        }
    }
    ?>

    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Header -->
            <div class="sidebar-header">
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php if ($utilisateur_courant->profile_photo && $utilisateur_courant->profile_photo != 'default.jpg'): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($utilisateur_courant->profile_photo); ?>" alt="Profile">
                        <?php else: ?>
                            <img src="/placeholder.svg?height=40&width=40&text=<?php echo strtoupper(substr($utilisateur_courant->username, 0, 1)); ?>" alt="Profile">
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($utilisateur_courant->username); ?></div>
                        <div class="user-status">En ligne</div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="action-btn" onclick="toggleSettings()" title="Paramètres">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97 0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1 0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
                        </svg>
                    </button>
                    <button class="action-btn" onclick="logout()" title="Déconnexion">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 0 1 2 2v2h-2V4H5v16h9v-2h2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Recherche -->
            <div class="search-container">
                <div class="search-box">
                    <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                    </svg>
                    <input type="text" class="search-input" placeholder="Rechercher ou commencer une nouvelle discussion" id="search-input">
                </div>
            </div>

            <!-- Navigation -->
            <div class="nav-tabs">
                <button class="nav-tab active" data-tab="chats" onclick="switchTab('chats')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z"/>
                    </svg>
                    Chats
                    <?php
                    $unread_chats = 0;
                    // Compter les messages non lus
                    foreach ($messages->xpath("//message[not(contains(read_by, '$id_utilisateur'))]") as $msg) {
                        if ($msg->sender_id != $id_utilisateur) {
                            $unread_chats++;
                        }
                    }
                    if ($unread_chats > 0): ?>
                        <span class="unread-count"><?php echo $unread_chats; ?></span>
                    <?php endif; ?>
                </button>
                <button class="nav-tab" data-tab="contacts" onclick="switchTab('contacts')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16,4C18.21,4 20,5.79 20,8C20,10.21 18.21,12 16,12C13.79,12 12,10.21 12,8C12,5.79 13.79,4 16,4M16,14C18.67,14 24,15.33 24,18V20H8V18C8,15.33 13.33,14 16,14M8.5,14L6.5,12H11.5L9.5,14M2.5,5.5H6.5V7.5H2.5V5.5M2.5,8.5H6.5V10.5H2.5V8.5M2.5,11.5H6.5V13.5H2.5V11.5Z"/>
                    </svg>
                    Contacts
                </button>
                <button class="nav-tab" data-tab="groups" onclick="switchTab('groups')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                    </svg>
                    Groupes
                </button>
            </div>

            <!-- Contenu des onglets -->
            <div id="chats-panel" class="chat-list">
                <?php
                // Récupérer toutes les conversations (contacts + groupes)
                $conversations = [];
                
                // Ajouter les contacts avec messages
                foreach ($contacts->xpath("//contact[user_id='$id_utilisateur']") as $contact) {
                    $contact_user_id = obtenirIdUtilisateurParTelephone($utilisateurs, $contact->contact_telephone);
                    if ($contact_user_id) {
                        $last_message = $messages->xpath("//message[(sender_id='$id_utilisateur' and recipient='{$contact->contact_telephone}') or (sender_id='$contact_user_id' and recipient='$utilisateur_courant->telephone')][last()]");
                        if (!empty($last_message)) {
                            $last_msg = $last_message[0];
                            $conversations[] = [
                                'type' => 'contact',
                                'id' => $contact->id,
                                'name' => $contact->contact_name,
                                'phone' => $contact->contact_telephone,
                                'last_message' => $last_msg->content,
                                'last_time' => $last_msg['timestamp'] ?? 'now',
                                'sender_id' => $last_msg->sender_id,
                                'unread_count' => count($messages->xpath("//message[sender_id='$contact_user_id' and recipient='$utilisateur_courant->telephone' and not(contains(read_by, '$id_utilisateur'))]"))
                            ];
                        }
                    }
                }
                
                // Ajouter les groupes avec messages
                foreach ($groupes->xpath("//group[member_id='$id_utilisateur']") as $group) {
                    $last_message = $messages->xpath("//message[recipient_group='{$group->id}'][last()]");
                    if (!empty($last_message)) {
                        $last_msg = $last_message[0];
                        $sender = $utilisateurs->xpath("//user[id='{$last_msg->sender_id}']")[0];
                        $conversations[] = [
                            'type' => 'group',
                            'id' => $group->id,
                            'name' => $group->name,
                            'last_message' => $last_msg->content,
                            'last_time' => $last_msg['timestamp'] ?? 'now',
                            'sender_name' => $sender ? $sender->username : 'Inconnu',
                            'sender_id' => $last_msg->sender_id,
                            'unread_count' => count($messages->xpath("//message[recipient_group='{$group->id}' and sender_id!='$id_utilisateur' and not(contains(read_by, '$id_utilisateur'))]"))
                        ];
                    }
                }
                
                // Trier par timestamp
                usort($conversations, function($a, $b) {
                    return strtotime($b['last_time']) - strtotime($a['last_time']);
                });
                
                foreach ($conversations as $conv): ?>
                    <div class="chat-item" onclick="openChat('<?php echo $conv['type']; ?>:<?php echo $conv['id']; ?>')">
                        <div class="chat-avatar">
                            <?php if ($conv['type'] === 'contact'): 
                                $contact_user = $utilisateurs->xpath("//user[telephone='{$conv['phone']}']")[0];
                                if ($contact_user && $contact_user->profile_photo && $contact_user->profile_photo != 'default.jpg'): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($contact_user->profile_photo); ?>" alt="Avatar">
                                <?php else: ?>
                                    <img src="/placeholder.svg?height=48&width=48&text=<?php echo strtoupper(substr($conv['name'], 0, 1)); ?>" alt="Avatar">
                                <?php endif; ?>
                            <?php else: 
                                $group = $groupes->xpath("//group[id='{$conv['id']}']")[0];
                                if ($group && $group->group_photo && $group->group_photo != 'default.jpg'): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($group->group_photo); ?>" alt="Avatar">
                                <?php else: ?>
                                    <img src="/placeholder.svg?height=48&width=48&text=<?php echo strtoupper(substr($conv['name'], 0, 1)); ?>" alt="Avatar">
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($conv['type'] === 'contact'): ?>
                                <div class="online-indicator"></div>
                            <?php endif; ?>
                        </div>
                        <div class="chat-info">
                            <div class="chat-header">
                                <span class="chat-name"><?php echo htmlspecialchars($conv['name']); ?></span>
                                <span class="chat-time"><?php echo date('H:i', strtotime($conv['last_time'])); ?></span>
                            </div>
                            <div class="chat-preview">
                                <span class="last-message">
                                    <?php if ($conv['type'] === 'group' && $conv['sender_id'] != $id_utilisateur): ?>
                                        <span class="sender-name"><?php echo htmlspecialchars($conv['sender_name']); ?>:</span>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars(substr($conv['last_message'], 0, 50)) . (strlen($conv['last_message']) > 50 ? '...' : ''); ?>
                                </span>
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="unread-badge"><?php echo $conv['unread_count']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Panneau Contacts -->
            <div id="contacts-panel" class="contacts-panel hidden">
                <div class="panel-header">
                    <button class="add-contact-btn" onclick="showAddContactModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z"/>
                        </svg>
                        Ajouter un contact
                    </button>
                </div>
                <div class="chat-list">
                    <?php foreach ($contacts->xpath("//contact[user_id='$id_utilisateur']") as $contact): 
                        $contact_user = $utilisateurs->xpath("//user[telephone='{$contact->contact_telephone}']")[0]; ?>
                        <div class="chat-item" onclick="openChat('contact:<?php echo $contact->id; ?>')">
                            <div class="chat-avatar">
                                <?php if ($contact_user && $contact_user->profile_photo && $contact_user->profile_photo != 'default.jpg'): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($contact_user->profile_photo); ?>" alt="Avatar">
                                <?php else: ?>
                                    <img src="/placeholder.svg?height=48&width=48&text=<?php echo strtoupper(substr($contact->contact_name, 0, 1)); ?>" alt="Avatar">
                                <?php endif; ?>
                                <div class="online-indicator"></div>
                            </div>
                            <div class="chat-info">
                                <div class="chat-header">
                                    <span class="chat-name"><?php echo htmlspecialchars($contact->contact_name); ?></span>
                                </div>
                                <div class="chat-preview">
                                    <span class="last-message"><?php echo htmlspecialchars($contact->contact_telephone); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Panneau Groupes -->
            <div id="groups-panel" class="groups-panel hidden">
                <div class="panel-header">
                    <button class="add-group-btn" onclick="showCreateGroupModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                        </svg>
                        Créer un groupe
                    </button>
                </div>
                <div class="chat-list">
                    <?php foreach ($groupes->xpath("//group[member_id='$id_utilisateur']") as $group): ?>
                        <div class="chat-item" onclick="openChat('group:<?php echo $group->id; ?>')">
                            <div class="chat-avatar">
                                <?php if ($group->group_photo && $group->group_photo != 'default.jpg'): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($group->group_photo); ?>" alt="Avatar">
                                <?php else: ?>
                                    <img src="/placeholder.svg?height=48&width=48&text=<?php echo strtoupper(substr($group->name, 0, 1)); ?>" alt="Avatar">
                                <?php endif; ?>
                            </div>
                            <div class="chat-info">
                                <div class="chat-header">
                                    <span class="chat-name"><?php echo htmlspecialchars($group->name); ?></span>
                                </div>
                                <div class="chat-preview">
                                    <span class="last-message">
                                        <?php 
                                        $member_count = is_array($group->member_id) ? count($group->member_id) : 1;
                                        echo $member_count . ' membre' . ($member_count > 1 ? 's' : '');
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Zone de chat -->
        <div class="chat-area">
            <!-- Écran d'accueil -->
            <div id="welcome-screen" class="welcome-screen">
                <div class="welcome-content">
                    <svg class="welcome-icon" width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2M6,9V7H18V9H6M14,11V13H6V11H14M16,15V17H6V15H16Z"/>
                    </svg>
                    <h2>Bienvenue sur WaxTaan</h2>
                    <p>Sélectionnez une conversation pour commencer à discuter avec vos contacts et groupes.</p>
                </div>
            </div>

            <!-- Container de chat -->
            <div id="chat-container" class="chat-container hidden">
                <!-- Header du chat -->
                <div class="chat-header">
                    <div class="chat-contact-info">
                        <button class="action-btn" onclick="backToChats()" style="display: none;" id="back-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/>
                            </svg>
                        </button>
                        <div class="contact-avatar">
                            <img id="chat-avatar" src="/placeholder.svg?height=40&width=40" alt="Avatar">
                            <div class="online-status"></div>
                        </div>
                        <div class="contact-details">
                            <div class="contact-name" id="chat-name">Contact</div>
                            <div class="contact-status" id="chat-status">En ligne</div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="action-btn" onclick="searchInChat()" title="Rechercher">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                            </svg>
                        </button>
                        <button class="action-btn" onclick="showChatMenu()" title="Menu">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div class="messages-area" id="messages-area">
                    <!-- Les messages seront chargés ici dynamiquement -->
                </div>

                <!-- Zone de saisie -->
                <div class="message-input-area">
                    <form id="message-form" action="../api.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="send_message">
                        <input type="hidden" name="recipient" id="message-recipient">
                        <input type="hidden" name="recipient_type" id="message-recipient-type">
                        
                        <div class="input-container">
                            <button type="button" class="attachment-btn" onclick="showAttachmentMenu()">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16.5,6V17.5A4,4 0 0,1 12.5,21.5A4,4 0 0,1 8.5,17.5V5A2.5,2.5 0 0,1 11,2.5A2.5,2.5 0 0,1 13.5,5V15.5A1,1 0 0,1 12.5,16.5A1,1 0 0,1 11.5,15.5V6H10V15.5A2.5,2.5 0 0,0 12.5,18A2.5,2.5 0 0,0 15,15.5V5A4,4 0 0,0 11,1A4,4 0 0,0 7,5V17.5A5.5,5.5 0 0,0 12.5,23A5.5,5.5 0 0,0 18,17.5V6H16.5Z"/>
                                </svg>
                            </button>
                            <div class="text-input-container">
                                <button type="button" class="emoji-btn" onclick="showEmojiPicker()">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,2C6.47,2 2,6.47 2,12C2,17.53 6.47,22 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M7,13C7.55,13 8,12.55 8,12C8,11.45 7.55,11 7,11C6.45,11 6,11.45 6,12C6,12.55 6.45,13 7,13M17,13C17.55,13 18,12.55 18,12C18,11.45 17.55,11 17,11C16.45,11 16,11.45 16,12C16,12.55 16.45,13 17,13M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z"/>
                                    </svg>
                                </button>
                                <textarea name="message" class="message-input" id="message-input" placeholder="Tapez un message" rows="1"></textarea>
                                <input type="file" name="file" style="display: none;" id="file-input" accept="image/*,video/*,application/*">
                            </div>
                            <button type="submit" class="send-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <?php if ($notification): ?>
        <div id="notification" class="notification notification-<?php echo $notification_type; ?>">
            <span id="notification-text"><?php echo htmlspecialchars($notification); ?></span>
            <button class="notification-close" onclick="closeNotification()">×</button>
        </div>
    <?php else: ?>
        <div id="notification" class="notification hidden">
            <span id="notification-text"></span>
            <button class="notification-close" onclick="closeNotification()">×</button>
        </div>
    <?php endif; ?>

    <!-- Modal Paramètres -->
    <div id="modal-overlay" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Paramètres</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <div class="modal-content">
                <div class="settings-section">
                    <h4>Profil</h4>
                    <form action="../api.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="profile-settings">
                            <div class="profile-photo">
                                <div class="profile-photo-container">
                                    <?php if ($utilisateur_courant->profile_photo && $utilisateur_courant->profile_photo != 'default.jpg'): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($utilisateur_courant->profile_photo); ?>" alt="Profile">
                                    <?php else: ?>
                                        <img src="/placeholder.svg?height=80&width=80&text=<?php echo strtoupper(substr($utilisateur_courant->username, 0, 1)); ?>" alt="Profile">
                                    <?php endif; ?>
                                    <input type="file" name="profile_photo" class="file-input-hidden" accept="image/*">
                                </div>
                                <button type="button" class="change-photo-btn" onclick="this.parentElement.querySelector('input[type=file]').click()">
                                    Changer la photo
                                </button>
                            </div>
                            <div class="profile-form">
                                <div class="form-group">
                                    <label for="username">Prénom</label>
                                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($utilisateur_courant->username); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur_courant->nom); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($utilisateur_courant->telephone); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" onclick="closeModal()">Annuler</button>
                            <button type="submit" class="btn-primary">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Contact -->
    <div id="add-contact-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Ajouter un contact</h3>
                <button class="modal-close" onclick="closeAddContactModal()">×</button>
            </div>
            <form action="../api.php" method="post">
                <input type="hidden" name="action" value="add_contact">
                <div class="modal-content">
                    <div class="form-group">
                        <label for="contact_name">Nom du contact</label>
                        <input type="text" id="contact_name" name="contact_name" placeholder="Entrez le nom du contact" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_telephone">Numéro de téléphone</label>
                        <input type="tel" id="contact_telephone" name="contact_telephone" placeholder="Ex: +221701234567" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeAddContactModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Créer Groupe -->
    <div id="create-group-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Créer un groupe</h3>
                <button class="modal-close" onclick="closeCreateGroupModal()">×</button>
            </div>
            <form action="../api.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create_group">
                <div class="modal-content">
                    <div class="form-group">
                        <label for="group_name">Nom du groupe</label>
                        <input type="text" id="group_name" name="group_name" placeholder="Entrez le nom du groupe" required>
                    </div>
                    <div class="form-group">
                        <label for="group_description">Description (optionnel)</label>
                        <textarea id="group_description" name="group_description" placeholder="Description du groupe" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="group_photo">Photo du groupe (optionnel)</label>
                        <input type="file" id="group_photo" name="group_photo" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Sélectionner les membres</label>
                        <div class="group-members-selection">
                            <?php foreach ($contacts->xpath("//contact[user_id='$id_utilisateur']") as $contact): ?>
                                <div class="member-checkbox">
                                    <input type="checkbox" name="members[]" value="<?php echo htmlspecialchars($contact->contact_telephone); ?>" id="member_<?php echo $contact->id; ?>">
                                    <label for="member_<?php echo $contact->id; ?>"><?php echo htmlspecialchars($contact->contact_name); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeCreateGroupModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Créer le groupe</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Menu Chat -->
    <div id="chat-menu-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3 id="chat-menu-title">Menu</h3>
                <button class="modal-close" onclick="closeChatMenuModal()">×</button>
            </div>
            <div class="modal-content">
                <div id="contact-menu-actions" class="hidden">
                    <div class="group-action-item" onclick="showContactInfo()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                        </svg>
                        Informations du contact
                    </div>
                    <div class="group-action-item" onclick="deleteContact()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                        </svg>
                        Supprimer le contact
                    </div>
                </div>
                <div id="group-menu-actions" class="hidden">
                    <div class="group-action-item" onclick="showGroupInfo()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                        </svg>
                        Informations du groupe
                    </div>
                    <div class="group-action-item" onclick="showManageGroupModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.98C19.47,12.66 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11.02L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11.02C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.66 4.57,12.98L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.98Z"/>
                        </svg>
                        Gérer le groupe
                    </div>
                    <div class="group-action-item" onclick="leaveGroup()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z"/>
                        </svg>
                        Quitter le groupe
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Informations Contact -->
    <div id="contact-info-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Informations du contact</h3>
                <button class="modal-close" onclick="closeContactInfoModal()">×</button>
            </div>
            <div class="modal-content">
                <div class="profile-settings">
                    <div class="profile-photo">
                        <img id="contact-info-photo" src="/placeholder.svg?height=80&width=80" alt="Contact">
                    </div>
                    <div class="profile-form">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" id="contact-info-name" readonly>
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="text" id="contact-info-phone" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeContactInfoModal()">Fermer</button>
                <button type="button" class="btn-primary" onclick="editContact()">Modifier</button>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Contact -->
    <div id="edit-contact-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Modifier le contact</h3>
                <button class="modal-close" onclick="closeEditContactModal()">×</button>
            </div>
            <form action="../api.php" method="post">
                <input type="hidden" name="action" value="update_contact">
                <input type="hidden" name="contact_id" id="edit-contact-id">
                <div class="modal-content">
                    <div class="form-group">
                        <label for="edit_contact_name">Nom du contact</label>
                        <input type="text" id="edit_contact_name" name="contact_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_contact_telephone">Numéro de téléphone</label>
                        <input type="tel" id="edit_contact_telephone" name="contact_telephone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEditContactModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Informations Groupe -->
    <div id="group-info-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Informations du groupe</h3>
                <button class="modal-close" onclick="closeGroupInfoModal()">×</button>
            </div>
            <div class="modal-content">
                <div class="profile-settings">
                    <div class="profile-photo">
                        <img id="group-info-photo" src="/placeholder.svg?height=80&width=80" alt="Groupe">
                    </div>
                    <div class="profile-form">
                        <div class="form-group">
                            <label>Nom du groupe</label>
                            <input type="text" id="group-info-name" readonly>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="group-info-description" readonly rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Membres</label>
                            <div id="group-members-list" class="group-members-selection" style="max-height: 150px;">
                                <!-- Les membres seront chargés ici -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeGroupInfoModal()">Fermer</button>
            </div>
        </div>
    </div>

    <!-- Modal Gérer Groupe -->
    <div id="manage-group-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Gérer le groupe</h3>
                <button class="modal-close" onclick="closeManageGroupModal()">×</button>
            </div>
            <div class="modal-content">
                <div class="settings-section">
                    <h4>Actions du groupe</h4>
                    <div class="group-action-item" onclick="showAddMemberModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z"/>
                        </svg>
                        Ajouter des membres
                    </div>
                    <div class="group-action-item" onclick="showRemoveMemberModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12M5,9.59L7.12,7.46L8.54,8.88L6.41,11L8.54,13.12L7.12,14.54L5,12.41L2.88,14.54L1.46,13.12L3.59,11L1.46,8.88L2.88,7.46L5,9.59Z"/>
                        </svg>
                        Retirer des membres
                    </div>
                    <div class="group-action-item" onclick="showEditGroupModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"/>
                        </svg>
                        Modifier le groupe
                    </div>
                    <div class="group-action-item" onclick="deleteGroup()" style="color: var(--danger);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                        </svg>
                        Supprimer le groupe
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeManageGroupModal()">Fermer</button>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Groupe -->
    <div id="edit-group-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3>Modifier le groupe</h3>
                <button class="modal-close" onclick="closeEditGroupModal()">×</button>
            </div>
            <form action="../api.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_group">
                <input type="hidden" name="group_id" id="edit-group-id">
                <div class="modal-content">
                    <div class="form-group">
                        <label for="edit_group_name">Nom du groupe</label>
                        <input type="text" id="edit_group_name" name="group_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_group_description">Description</label>
                        <textarea id="edit_group_description" name="group_description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_group_photo">Nouvelle photo (optionnel)</label>
                        <input type="file" id="edit_group_photo" name="group_photo" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEditGroupModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmation -->
    <div id="confirmation-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3 id="confirmation-title">Confirmation</h3>
                <button class="modal-close" onclick="closeConfirmationModal()">×</button>
            </div>
            <div class="modal-content">
                <p id="confirmation-message">Êtes-vous sûr de vouloir effectuer cette action ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeConfirmationModal()">Annuler</button>
                <button type="button" class="btn-primary" id="confirmation-confirm-btn" onclick="confirmAction()">Confirmer</button>
            </div>
        </div>
    </div>
<script src="../scripts/scripts.js"></script>
    <!-- Formulaires cachés pour les actions -->
    <form id="deleteContactForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="delete_contact">
        <input type="hidden" name="contact_id" id="contactIdToDelete">
    </form>

    <form id="deleteGroupForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="delete_group">
        <input type="hidden" name="id_group" id="groupIdToDelete">
    </form>

    <form id="leaveGroupForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="leave_group">
        <input type="hidden" name="id_group" id="groupIdToLeave">
    </form>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/global.js"></script>
</body>
</html>