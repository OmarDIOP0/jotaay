<div class="contacts-container">
    <!-- Bouton Ajouter un contact -->
    <div class="add-contact-section">
        <button type="button" onclick="afficherModalAjoutContact()" class="add-contact-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                <circle cx="20" cy="4" r="2" fill="currentColor"></circle>
                <line x1="20" y1="3" x2="20" y2="5"></line>
                <line x1="19" y1="4" x2="21" y2="4"></line>
            </svg>
            Ajouter un contact
        </button>
    </div>

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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="confirmerSuppressionContact('<?php echo $contact->contact_id; ?>', '<?php echo htmlspecialchars($contact->contact_name); ?>')" class="contact-action-btn delete">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php } ?>
        <?php } ?>
        
        <?php if (!$hasContacts) { ?>
        <div class="empty-contacts">
            <svg class="empty-contacts-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <h3 class="empty-contacts-title">Aucun contact pour le moment</h3>
            <p class="empty-contacts-message">
                Ajoutez votre premier contact pour commencer à discuter.
            </p>
            <button type="button" onclick="afficherModalAjoutContact()" class="add-contact-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    <circle cx="20" cy="4" r="2" fill="currentColor"></circle>
                    <line x1="20" y1="3" x2="20" y2="5"></line>
                    <line x1="19" y1="4" x2="21" y2="4"></line>
                </svg>
                Ajouter un contact
            </button>
        </div>
        <?php } ?>
    </div>
</div>

<script src="../assets/js/global.js"></script>