<?php require_once '../controller.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jotaay - Messagerie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                    <div class="user-actions">
                        <button class="action-btn" title="Paramètres">
                            <span>⚙️</span>
                        </button>
                        <a href="../auth/logout.php" class="action-btn" title="Déconnexion">
                            <span>🔁</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Barre de recherche -->
            <div class="search-container">
                <div class="search-input-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" class="search-input" placeholder="Rechercher ou commencer une nouvelle discussion">
                </div>
            </div>

            <!-- Menu de navigation -->
            <div class="sidebar-nav">
                <button class="nav-tab" data-tab="chats">
                    <span class="nav-tab-icon">💬</span>
                    <span class="nav-tab-text">Chats</span>
                    <span class="nav-badge">2</span>
                </button>
                <button class="nav-tab" data-tab="contacts">
                    <span class="nav-tab-icon">👥</span>
                    <span class="nav-tab-text">Contacts</span>
                </button>
                <button class="nav-tab active" data-tab="groups">
                    <span class="nav-tab-icon">🏠</span>
                    <span class="nav-tab-text">Groupes</span>
                </button>
            </div>

            <!-- Contenu de la sidebar -->
            <div class="sidebar-content">
                <!-- Onglet Chats -->
                <div class="tab-panel" id="chats-panel">
                    <div class="chat-list">
                        <!-- Liste des conversations récentes -->
                    </div>
                </div>

                <!-- Onglet Contacts -->
                <div class="tab-panel" id="contacts-panel">
                    <?php include 'contacts_view.php'; ?>
                </div>

                <!-- Onglet Groupes -->
                <div class="tab-panel active" id="groups-panel">
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
                    
                    $conversation_name = $contact_info ? $contact_info->contact_name : '';
                    $conversation_avatar = $contact_info ? $contact_info->contact_telephone : '';
                } elseif ($type === 'groupe') {
                    // Récupérer les informations du groupe
                    $groupe_info_result = $groupes->xpath("//group[id='$id']");
                    $groupe_info = !empty($groupe_info_result) ? $groupe_info_result[0] : null;
                    
                    if ($groupe_info) {
                        // Récupérer les messages du groupe
                        $messages_to_show = $messages->xpath("//message[recipient_type='groupe' and recipient='$id']");
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
                                </div>
                                
                                <?php if ($message->sender_id == $id_utilisateur) { ?>
                                    <div class="message-meta" style="justify-content: flex-end; margin-top: 4px;">
                                        <span class="message-time"><?php echo date('H:i', strtotime($message['timestamp'] ?? 'now')); ?></span>
                                    </div>
                                <?php } ?>
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
                                <input type="file" name="file" class="file-input" accept="image/*,video/*,application/*" style="display: none;">
                                
                                <textarea name="content" class="message-input" placeholder="Tapez un message..." rows="1"></textarea>
                                
                                <button type="button" class="emoji-btn">
                                    <span>🙂</span>
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
                        <div class="welcome-icon">💬</div>
                        <h1 class="welcome-title">
                            Bienvenue sur<br>
                            <span class="welcome-brand">Jotaay</span>
                        </h1>
                        <p class="welcome-message">
                            Sélectionnez une conversation pour commencer à discuter avec vos contacts et groupes.
                        </p>
                    </div>
                </div>
                <?php
            }
            ?>
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

    <!-- Modal pour afficher les images -->
    <div id="imageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <img id="modalImage" src="" alt="Image" style="max-width: 100%; max-height: 80vh;">
            <button type="button" onclick="closeImageModal()" class="modal-close">&times;</button>
        </div>
    </div>

    <!-- Formulaire caché pour la suppression de contact -->
    <form id="deleteContactForm" action="../api.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="supprimer_contact">
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
                            <span>➕</span>
                            Ajouter Contact
                        </button>
                        <button type="button" onclick="fermerModalAjoutContact()" class="modern-btn btn-secondary">
                            <span>❌</span>
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
                            <span>✏️</span> Modifier
                        </button>
                        <button type="button" onclick="fermerModalEditionContact()" class="modern-btn btn-secondary">
                            <span>❌</span>
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
                                $utilisateur_contact = $utilisateurs->xpath("//user[telephone='{$contact->contact_telephone}']")[0];
                                if ($utilisateur_contact) {
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
    <!-- Modal pour lister les membres -->
    <div id="groupMembersModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupMembersTitle">Membres du groupe</h3>
                <button type="button" onclick="fermerModalMembresGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupMembersContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalMembresGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour gérer les co-admins -->
    <div id="groupCoAdminsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupCoAdminsTitle">Gérer les co-admins</h3>
                <button type="button" onclick="fermerModalCoAdminsGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupCoAdminsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalCoAdminsGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour retirer un membre -->
    <div id="groupRemoveMemberModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupRemoveMemberTitle">Retirer un membre</h3>
                <button type="button" onclick="fermerModalRetirerMembre()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupRemoveMemberContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalRetirerMembre()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un membre -->
    <div id="groupAddMemberModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupAddMemberTitle">Ajouter un membre</h3>
                <button type="button" onclick="fermerModalAjouterMembre()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupAddMemberContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalAjouterMembre()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour supprimer le groupe -->
    <div id="groupDeleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupDeleteTitle">Supprimer le groupe</h3>
                <button type="button" onclick="fermerModalSupprimerGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupDeleteContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalSupprimerGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour quitter le groupe -->
    <div id="groupLeaveModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="groupLeaveTitle">Quitter le groupe</h3>
                <button type="button" onclick="fermerModalQuitterGroupe()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="groupLeaveContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="form-actions">
                    <button type="button" onclick="fermerModalQuitterGroupe()" class="modern-btn btn-secondary">
                        <span>❌</span> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Champ caché pour le télételephone de l'utilisateur actuel -->
    <input type="hidden" name="current_user_telephone" value="<?php echo $utilisateur_courant->telephone; ?>">

    <script src="../assets/js/global.js"></script>
</body>
</html>