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
            <div class="chat-item" onclick="openChat(
                'contact:<?php echo $contact->id; ?>',
                '<?php echo htmlspecialchars($contact->contact_name); ?>',
                '<?php echo $contact_user && $contact_user->profile_photo ? htmlspecialchars($contact_user->profile_photo) : ''; ?>'
            )">
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

<!-- Modal Ajouter Contact -->
<div id="add-contact-modal" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-header">
            <h3>Ajouter un contact</h3>
            <button class="modal-close" onclick="closeAddContactModal()">×</button>
        </div>
        <form action="../api.php" method="post">
            <input type="hidden" name="action" value="ajouter_contact">
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

<!-- Formulaire caché pour suppression de contact -->
<form id="deleteContactForm" action="../api.php" method="post" style="display: none;">
    <input type="hidden" name="action" value="delete_contact">
    <input type="hidden" name="contact_id" id="contactIdToDelete">
</form>