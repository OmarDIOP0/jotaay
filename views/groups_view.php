<div class="groups-container">
    <!-- Bouton Ajouter un groupe -->
    <div class="add-group-section">
        <button type="button" onclick="afficherModalCreationGroupe()" class="add-group-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                <circle cx="20" cy="4" r="2" fill="currentColor"></circle>
                <line x1="20" y1="3" x2="20" y2="5"></line>
                <line x1="19" y1="4" x2="21" y2="4"></line>
            </svg>
            Ajouter un groupe
        </button>
    </div>

    <!-- Liste des groupes -->
    <div class="groups-list">
        <?php 
        // Afficher tous les groupes où l'utilisateur est membre OU admin
        $hasGroups = false;
        foreach ($groupes->group as $groupe) {
            $est_membre = false;
            foreach ($groupe->membre_id as $id_membre) {
                if (trim((string)$id_membre) === trim((string)$id_utilisateur)) {
                    $est_membre = true;
                    break;
                }
            }
            $est_admin = trim((string)$groupe->admin_id) === trim((string)$id_utilisateur);
            if (!$est_membre && !$est_admin) continue;
            
            $hasGroups = true;
            $coadmins = isset($groupe->coadmins) ? explode(',', (string)$groupe->coadmins) : [];
            $est_coadmin = in_array(trim((string)$id_utilisateur), array_map('trim', $coadmins));
            $peut_gerer = $est_admin || $est_coadmin;
            $ids_membres = [];
            foreach ($groupe->membre_id as $id_membre) {
                $ids_membres[] = trim((string)$id_membre);
            }
            $admin_id = trim((string)$groupe->admin_id);
            $tous_les_ids = $ids_membres;
            $tous_les_ids[] = $admin_id;
            $ids_uniques = array_unique($tous_les_ids);
            $nombre_membres = count($ids_uniques);
        ?>
        <div class="group-item">
            <div class="group-avatar">
                <?php if ($groupe->group_photo && $groupe->group_photo != 'default.jpg') { ?>
                    <img src="../uploads/<?php echo htmlspecialchars($groupe->group_photo); ?>" alt="Photo Groupe">
                <?php } else { ?>
                    <?php echo strtoupper(substr($groupe->group_name, 0, 1)); ?>
                <?php } ?>
            </div>
            <div class="group-info">
                <div class="group-name">
                    <?php echo htmlspecialchars($groupe->group_name); ?>
                    <?php if ($est_admin) { ?>
                        <span class="group-badge admin">Admin</span>
                    <?php } elseif ($est_coadmin) { ?>
                        <span class="group-badge coadmin">Co-Admin</span>
                    <?php } ?>
                </div>
                <div class="group-meta"><?php echo $nombre_membres; ?> membres</div>
            </div>
            <div class="group-actions">
                <select class="group-action-select" onchange="gererActionGroupeSelect(this, '<?php echo $groupe->id; ?>')">
                    <option value="">Actions</option>
                    <option value="ouvrir_conversation">Ouvrir la conversation</option>
                    <option value="lister_membres">Lister les membres</option>
                    <?php if ($peut_gerer) { ?>
                        <option value="gerer_coadmins">Gérer les co-admins</option>
                        <option value="retirer_membre">Retirer un membre</option>
                        <option value="ajouter_membre">Ajouter un membre</option>
                    <?php } ?>
                    <?php if ($est_admin) { ?>
                        <option value="supprimer_groupe">Supprimer le groupe</option>
                    <?php } else { ?>
                        <option value="quitter_groupe">Quitter le groupe</option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php } ?>
        
        <?php if (!$hasGroups) { ?>
        <div class="empty-groups">
            <svg class="empty-groups-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <h3 class="empty-groups-title">Aucun groupe pour le moment</h3>
            <p class="empty-groups-message">
                Créez votre premier groupe pour commencer à discuter en équipe.
            </p>
        </div>
        <?php } ?>
    </div>
</div>

<script src="../assets/js/global.js"></script> 