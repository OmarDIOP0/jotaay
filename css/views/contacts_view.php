<div class="contacts-container">
    <!-- Liste des contacts -->
    <div class="contacts-list">
        <?php 
        $hasContacts = false;
        foreach ($contacts->xpath("//contact[user_id='$id_utilisateur']") as $contact) { 
            $utilisateur_contact_result = $utilisateurs->xpath("//user[telephone='{$contact->contact_telephone}']");
            $utilisateur_contact = !empty($utilisateur_contact_result) ? $utilisateur_contact_result[0] : null;
            if ($utilisateur_contact) {
                $hasContacts = true;
        ?>
            <div class="contact-item">
                <div class="contact-avatar">
                    <?php if ($utilisateur_contact->profile_photo && $utilisateur_contact->profile_photo != 'default.jpg') { ?>
                        <img src="../uploads/<?php echo htmlspecialchars($utilisateur_contact->profile_photo); ?>" alt="Photo">
                    <?php } else { ?>
                        <?php echo strtoupper(substr($contact->contact_name, 0, 1)); ?>
                    <?php } ?>
                </div>
                
                <div class="contact-info">
                    <div class="contact-name"><?php echo htmlspecialchars($contact->contact_name); ?></div>
                    <div class="contact-meta"><?php echo htmlspecialchars($contact->contact_telephone); ?></div>
                </div>
                
                <div class="contact-actions">
                    <button type="button" onclick="afficherModalEditionContact('<?php echo $contact->contact_id; ?>', '<?php echo htmlspecialchars($contact->contact_name); ?>')" class="contact-action-btn edit">
                        ✏️
                    </button>
                    <button type="button" onclick="confirmerSuppressionContact('<?php echo $contact->contact_id; ?>', '<?php echo htmlspecialchars($contact->contact_name); ?>')" class="contact-action-btn delete">
                        🗑️
                    </button>
                </div>
            </div>
        <?php } ?>
        <?php } ?>
        
        <?php if (!$hasContacts) { ?>
        <div class="empty-contacts">
            <div class="empty-contacts-icon">👥</div>
            <h3 class="empty-contacts-title">Aucun contact pour le moment</h3>
            <p class="empty-contacts-message">
                Ajoutez votre premier contact pour commencer à discuter.
            </p>
            <button type="button" onclick="afficherModalAjoutContact()" class="add-contact-btn">
                <span>➕</span>
                Ajouter un contact
            </button>
        </div>
        <?php } ?>
    </div>
</div>

<script src="../assets/js/global.js"></script>