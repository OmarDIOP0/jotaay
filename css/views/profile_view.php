<div class="profile-section">
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($utilisateur_courant->profile_photo && $utilisateur_courant->profile_photo != 'default.jpg') { ?>
                <img src="../uploads/<?php echo htmlspecialchars($utilisateur_courant->profile_photo); ?>" alt="Photo de profil">
            <?php } else { ?>
                <?php echo strtoupper(substr($utilisateur_courant->username, 0, 1)); ?>
            <?php } ?>
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($utilisateur_courant->username . ' ' . $utilisateur_courant->nom); ?></h2>
            <div class="profile-telephone"><?php echo htmlspecialchars($utilisateur_courant->telephone); ?></div>
        </div>
    </div>
    <button type="button" onclick="afficherModalEditionProfil()" class="modern-btn btn-primary" id="afficherBoutonEditionProfil">
        <span>✏️</span> Modifier le profil
    </button>
</div>

<script src="../assets/js/global.js"></script>