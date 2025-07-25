<?php require_once '../controller.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaxTaan - Messagerie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="../assets/css/modern-app.css">
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
                $error_message = 'Erreur : Aucun utilisateur trouvé avec ce numéro de télételephone.';
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
            echo "<div style='position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(240, 147, 251, 0.3); z-index: 1000;'>$error_message</div>";
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
            echo "<div style='position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%); color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(74, 222, 128, 0.3); z-index: 1000;'>$success_message</div>";
        }
    }
    ?>
    
    <div class="app-container">
        <!-- Sidebar moderne -->
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

            <!-- Navigation par onglets -->
            <div class="sidebar-nav">
                <button class="nav-tab active" data-tab="profile">
                    <span class="nav-tab-icon">👤</span>
                    Profil
                </button>
                <button class="nav-tab" data-tab="contacts">
                    <span class="nav-tab-icon">👥</span>
                    Contacts
                </button>
                <button class="nav-tab" data-tab="groups">
                    <span class="nav-tab-icon">🏠</span>
                    Groupes
                </button>
                <button class="nav-tab" data-tab="discussions">
                    <span class="nav-tab-icon">💬</span>
                    Discussions
                </button>
            </div>

            <!-- Contenu de la sidebar -->
            <div class="sidebar-content">
                <!-- Onglet Profil -->
                <div class="tab-panel active" id="profile-panel">
                    <?php include 'profile_view.php'; ?>
                </div>

                <!-- Onglet Contacts -->
                <div class="tab-panel" id="contacts-panel">
                    <?php include 'contacts_view.php'; ?>
                </div>

                <!-- Onglet Groupes -->
                <div class="tab-panel" id="groups-panel">
                    <?php include 'groups_view.php'; ?>
                </div>

                <!-- Onglet Discussions -->
                <div class="tab-panel" id="discussions-panel">
                    <?php include 'discussions_view.php'; ?>
                </div>
            </div>
        </div>

        <!-- Zone de chat -->
        <div class="chat-area">
            <?php
            $current_conversation = $_GET['conversation'] ?? '';
            $messages_to_show = [];
            $conversation_name = '';
            $conversation_avatar = '';
            
            if ($current_conversation) {
                list($type, $id) = explode(':', $current_conversation);
                if ($type === 'contact') {
                    // Récupérer les informations du contact par son ID
                    $contact_info_result = $contacts->xpath("//contact[id='$id']");
                    $contact_info = !empty($contact_info_result) ? $contact_info_result[0] : null;
                    
                    if ($contact_info) {
                        // Récupérer l'ID de l'utilisateur contact par son numéro de téléphone
                        $contact_user_id = obtenirIdUtilisateurParTelephone($utilisateurs, $contact_info->contact_telephone);
                        
                        if ($contact_user_id) {
                            // Récupérer les messages entre les deux utilisateurs
                            $messages_to_show = $messages->xpath("//message[(sender_id='$id_utilisateur' and recipient='{$contact_info->contact_telephone}') or (sender_id='$contact_user_id' and recipient='$utilisateur_courant->telephone')]");
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
                    } else {
                        $messages_to_show = [];
                    }
                    
                    $conversation_name = $contact_info ? htmlspecialchars($contact_info->contact_name) : 'Contact';
                    $conversation_avatar = strtoupper(substr($conversation_name, 0, 1));
                } elseif (trim($type) === 'groupe') {
                    $messages_to_show = $messages->xpath("//message[recipient_group='$id']");
                    // Marquer comme lus tous les messages de groupe non lus
                    foreach ($messages->xpath("//message[recipient_group='$id']") as $msg) {
                        if (!isset($msg->read_by) || !in_array($id_utilisateur, explode(',', (string)$msg->read_by))) {
                            $read_by = isset($msg->read_by) ? (string)$msg->read_by : '';
                            $read_by_arr = $read_by ? explode(',', $read_by) : [];
                            $read_by_arr[] = $id_utilisateur;
                            $msg->read_by = implode(',', array_unique($read_by_arr));
                        }
                    }
                    $messages->asXML('../xmls/messages.xml');
                    $group_info_result = $groupes->xpath("//group[id='$id']");
                    $group_info = !empty($group_info_result) ? $group_info_result[0] : null;
                    $conversation_name = $group_info ? htmlspecialchars($group_info->name) : 'Groupe';
                    $conversation_avatar = strtoupper(substr($conversation_name, 0, 1));
                }
            }
            ?>
            
            <?php if ($current_conversation) { ?>
                <!-- Header du chat -->
                <div class="chat-header">
                    <div class="chat-avatar">
                        <?php if ($type === 'groupe' && $group_info && $group_info->group_photo && $group_info->group_photo != 'default.jpg') { ?>
                            <img src="../uploads/<?php echo htmlspecialchars($group_info->group_photo); ?>" alt="Group Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php } elseif ($type === 'contact' && $contact_info) { 
                            // Récupérer l'utilisateur contact pour sa photo de profil
                            $utilisateur_contact = $utilisateurs->xpath("//user[telephone='{$contact_info->contact_telephone}']")[0];
                            if ($utilisateur_contact && $utilisateur_contact->profile_photo && (string)$utilisateur_contact->profile_photo != 'default.jpg') { ?>
                                <img src="../uploads/<?php echo htmlspecialchars($utilisateur_contact->profile_photo); ?>" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php } else { ?>
                                <?php echo $conversation_avatar; ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?php echo $conversation_avatar; ?>
                        <?php } ?>
                    </div>
                    <div class="chat-info" style="flex:1;">
                        <h3 style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                            <?php echo $conversation_name; ?>
                            <a href="?tab=discussions" class="modern-btn btn-danger btn-small" style="margin-left:16px;">✖</a>
                        </h3>
                        <div class="chat-status">
                            <?php if ($type === 'groupe' && $group_info) { ?>
                                <?php 
                                $nb_membres = 0;
                                if (isset($group_info->member_id)) {
                                    $nb_membres = count($group_info->member_id);
                                }
                                echo $nb_membres; 
                                ?> membres
                            <?php } else { ?>
                                En ligne
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="chat-messages" id="chat-container">
                    <?php if (empty($messages_to_show)) { ?>
                        <div class="empty-chat">
                            <div class="empty-chat-icon">💬</div>
                            <h3>Aucun message</h3>
                            <p>Commencez la conversation en envoyant votre premier message !</p>
                        </div>
                    <?php } else { ?>
                        <?php foreach ($messages_to_show as $message) { ?>
                            <div class="message-bubble <?php echo $message->sender_id == $id_utilisateur ? 'sent' : 'received'; ?>">
                                <?php if ($message->sender_id != $id_utilisateur) { ?>
                                    <div class="message-meta">
                                        <?php $sender = $utilisateurs->xpath("//user[id='{$message->sender_id}']")[0]; ?>
                                        <span class="message-sender"><?php echo htmlspecialchars($sender->username); ?></span>
                                        <span class="message-time"><?php echo date('H:i', strtotime($message['timestamp'] ?? 'now')); ?></span>
                                    </div>
                                <?php } ?>
                                
                                <div class="message-content">
                                    <p><?php echo htmlspecialchars($message->content); ?></p>
                                    
                                    <?php if ($message->file) { ?>
                                        <div class="message-file" style="margin-top: 8px;">
                                            <?php
                                            $file_extension = strtolower(pathinfo($message->file, PATHINFO_EXTENSION));
                                            $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $is_video = in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm']);
                                            ?>
                                            
                                            <?php if ($is_image) { ?>
                                                <!-- Affichage des images -->
                                                <div class="file-preview">
                                                    <img src="../uploads/<?php echo htmlspecialchars($message->file); ?>" alt="Image" class="message-image" onclick="openImageModal('../uploads/<?php echo htmlspecialchars($message->file); ?>')">
                                                </div>
                                            <?php } elseif ($is_video) { ?>
                                                <!-- Affichage des vidéos -->
                                                <div class="file-preview">
                                                    <video controls class="message-video">
                                                        <source src="../uploads/<?php echo htmlspecialchars($message->file); ?>" type="video/<?php echo $file_extension; ?>">
                                                        Votre navigateur ne supporte pas la lecture de vidéos.
                                                    </video>
                                                </div>
                                            <?php } else { ?>
                                                <!-- Affichage des autres fichiers -->
                                                <a href="../uploads/<?php echo htmlspecialchars($message->file); ?>" download class="file-download">
                                                    <span class="file-icon">📎</span>
                                                    <span class="file-name"><?php echo htmlspecialchars($message->file); ?></span>
                                                    <span class="file-size">Télécharger</span>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                <?php if ($message->sender_id == $id_utilisateur) { ?>
                                    <div class="message-meta" style="justify-content: flex-end; margin-top: 4px;">
                                        <span class="message-time"><?php echo date('H:i', strtotime($message['timestamp'] ?? 'now')); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <!-- Zone de saisie -->
                <div class="chat-input">
                    <form action="../api.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="send_message">
                        <input type="hidden" name="recipient" value="<?php echo isset($type) && $type === 'contact' && isset($contact_info) ? htmlspecialchars($contact_info->contact_telephone) : (isset($id) ? htmlspecialchars($id) : ''); ?>">
                        <input type="hidden" name="recipient_type" value="<?php echo isset($type) ? $type : ''; ?>">
                        
                        <div class="input-container">
                            <textarea name="message" class="message-input" placeholder="Tapez votre message..." rows="1" ></textarea>
                            
                            <div class="file-input-wrapper">
                                <input type="file" name="file" class="file-input" accept="image/*,video/*,application/*">
                                <div class="file-input-btn">
                                    📎
                                </div>
                            </div>
                            
                            <button type="submit" class="send-btn">
                                <span>📤</span>
                                Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            <?php } else { ?>
                <!-- État vide -->
                <div class="chat-messages">
                    <div class="empty-chat">
                        <div class="empty-chat-icon">💬</div>
                        <h3>Bienvenue sur WaxTaan</h3>
                        <p>Sélectionnez un contact ou un groupe pour commencer à discuter.</p>
                    </div>
                </div>
            <?php } ?>
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
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($utilisateur_courant->username); ?>" required>
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
    <!-- Modal pour afficher les images -->
    <div id="imageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <img id="modalImage" src="" alt="Image" style="max-width: 100%; max-height: 80vh;">
            <button type="button" onclick="closeImageModal()" class="modal-close">&times;</button>
        </div>
    </div>

    <!-- Formulaire caché pour la suppression de contact -->
    <form id="deleteContactForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="delete_contact">
        <input type="hidden" name="contact_id" id="contactIdToDelete">
    </form>

    <!-- Formulaire caché pour la suppression de groupe -->
    <form id="deleteGroupForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="delete_group">
        <input type="hidden" name="id_group" id="groupIdToDelete">
    </form>

    <!-- Formulaire caché pour quitter un groupe -->
    <form id="leaveGroupForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="leave_group">
        <input type="hidden" name="id_group" id="groupIdToLeave">
    </form>

    <!-- Champ caché pour le télételephone de l'utilisateur actuel -->
    <input type="hidden" name="current_user_telephone" value="<?php echo $utilisateur_courant->telephone; ?>">

    <script src="/assets/js/global.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>