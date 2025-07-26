<!-- Panneau Groupes -->
<div id="groups-panel" class="groups-panel hidden">
    <div class="panel-header">
        <button class="add-group-btn" onclick="showAddGroupModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z"/>
            </svg>
            Ajouter un groupe
        </button>
    </div>
    <div class="chat-list">
        <?php 
        $groupes_utilisateur = $groupes->xpath("//group[membre_id='$id_utilisateur']");
        if (empty($groupes_utilisateur)) : ?>
            <div class="empty-state" style="text-align:center; color:#888; margin-top:2em;">
                <div class="empty-icon" style="font-size:2em;">👥</div>
                <h3>Aucun groupe pour le moment</h3>
                <p>Créez votre premier groupe pour commencer à discuter en équipe.</p>
            </div>
        <?php else:
            foreach ($groupes_utilisateur as $groupe): 
                $membres = [];
                foreach ($groupe->membre_id as $mid) {
                    $m = $utilisateurs->xpath("//user[id='$mid']");
                    if ($m && isset($m[0])) {
                        $membres[] = htmlspecialchars($m[0]->username ?? $m[0]->prenom ?? $m[0]->nom ?? $m[0]->telephone);
                    }
                }
                $photo = ($groupe->photo_groupe && $groupe->photo_groupe != 'default.jpg') ? '../uploads/' . htmlspecialchars($groupe->photo_groupe) : '';
                $membres_js = json_encode($membres);
        ?>
            <div class="chat-item" style="cursor:pointer" onclick="openGroupInfo('<?php echo $groupe->group_id; ?>', '<?php echo htmlspecialchars($groupe->group_name, ENT_QUOTES); ?>', '', '<?php echo $photo; ?>', <?php echo $membres_js; ?>)">
                <div class="chat-avatar">
                    <?php if ($groupe->photo_groupe && $groupe->photo_groupe != 'default.jpg'): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($groupe->photo_groupe); ?>" alt="Avatar">
                    <?php else: ?>
                        <img src="/placeholder.svg?height=48&width=48&text=<?php echo strtoupper(substr($groupe->group_name, 0, 1)); ?>" alt="Avatar">
                    <?php endif; ?>
                </div>
                <div class="chat-info">
                    <div class="chat-header">
                        <span class="chat-name"><?php echo htmlspecialchars($groupe->group_name); ?></span>
                        <?php if ($groupe->admin_id == $id_utilisateur): ?>
                            <span class="badge-admin">Admin</span>
                        <?php endif; ?>
                    </div>
                    <div class="chat-preview">
                        <span class="last-message"></span>
                        <span class="group-meta"><?php echo count($groupe->membre_id); ?> membres</span>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- Modal Ajouter Groupe -->
<div id="add-group-modal" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-header">
            <h3>Ajouter un groupe</h3>
            <button class="modal-close" onclick="closeAddGroupModal()">×</button>
        </div>
        <form action="../api.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="creer_groupe">
            <div class="modal-content">
                <div class="form-group">
                    <label for="group_name">Nom du groupe</label>
                    <input type="text" id="group_name" name="nom_groupe" placeholder="Entrez le nom du groupe" required>
                </div>
                <div class="form-group">
                    <label for="group_description">Description</label>
                    <textarea id="group_description" name="description_groupe" placeholder="Description du groupe" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="group_photo">Photo du groupe</label>
                    <input type="file" id="group_photo" name="photo_groupe" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Sélectionner les membres</label>
                    <div class="group-members-selection">
                        <?php foreach ($contacts->xpath("//contact[user_id='$id_utilisateur']") as $contact): ?>
                            <div class="member-checkbox">
                                <input type="checkbox" name="ids_membres[]" value="<?php echo htmlspecialchars($contact->contact_telephone); ?>" id="member_<?php echo $contact->id; ?>">
                                <label for="member_<?php echo $contact->id; ?>"><?php echo htmlspecialchars($contact->contact_name); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeAddGroupModal()">Annuler</button>
                <button type="submit" class="btn-primary">Ajouter</button>
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
                        <textarea id="group-info-description" readonly rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Membres</label>
                        <div id="group-members-list" class="group-members-selection" style="max-height: 120px; overflow-y: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeGroupInfoModal()">Fermer</button>
            <button type="button" class="btn-primary" onclick="editGroup()">Modifier</button>
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
                    <textarea id="edit_group_description" name="group_description" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_group_photo">Nouvelle photo</label>
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

<!-- Formulaire caché pour suppression de groupe -->
<form id="deleteGroupForm" action="../api.php" method="post" style="display: none;">
    <input type="hidden" name="action" value="delete_group">
    <input type="hidden" name="group_id" id="groupIdToDelete">
</form> 