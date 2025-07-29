// ========================================
// SCRIPT PRINCIPAL POUR VIEW.PHP
// ========================================

// Fonctions pour les modales d'alerte et confirmation
function showAlert(message, title = "Information") {
  const modal = document.createElement('div');
  modal.className = 'modal alert-modal';
  modal.innerHTML = `
    <div class="modal-content">
      <div class="modal-header">
        <h3>${title}</h3>
        <button class="modal-close" onclick="closeAlertModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p>${message}</p>
        <div class="modal-actions">
          <button class="modal-btn primary" onclick="closeAlertModal()">OK</button>
        </div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  
  // Fermer en cliquant à l'extérieur
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeAlertModal();
    }
  });
}

function showConfirm(message, title = "Confirmation", onConfirm, onCancel) {
  const modal = document.createElement('div');
  modal.className = 'modal confirm-modal';
  modal.innerHTML = `
    <div class="modal-content">
      <div class="modal-header">
        <h3>${title}</h3>
        <button class="modal-close" onclick="closeConfirmModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p>${message}</p>
        <div class="modal-actions">
          <button class="modal-btn secondary" onclick="closeConfirmModal()">Annuler</button>
          <button class="modal-btn danger" onclick="executeConfirm()">Confirmer</button>
        </div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  
  // Stocker les callbacks
  modal._onConfirm = onConfirm;
  modal._onCancel = onCancel;
  
  // Fermer en cliquant à l'extérieur
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeConfirmModal();
    }
  });
}

function closeAlertModal() {
  const modal = document.querySelector('.alert-modal');
  if (modal) {
    modal.remove();
  }
}

function closeConfirmModal() {
  const modal = document.querySelector('.confirm-modal');
  if (modal) {
    if (modal._onCancel) {
      modal._onCancel();
    }
    modal.remove();
  }
}

function executeConfirm() {
  const modal = document.querySelector('.confirm-modal');
  if (modal && modal._onConfirm) {
    modal._onConfirm();
  }
  closeConfirmModal();
}

// Fonction pour confirmer la déconnexion
function confirmerDeconnexion() {
  showConfirm(
    "Êtes-vous sûr de vouloir vous déconnecter ?\n\nVous devrez vous reconnecter pour accéder à l'application.",
    "Confirmation de déconnexion",
    function() {
      window.location.href = '../auth/logout.php';
    }
  );
}

// Gestion des onglets
document.addEventListener("DOMContentLoaded", () => {
    // Initialiser l'onglet actif
    const activeTab = document.querySelector(".nav-tab.active")
    if (activeTab) {
      const tabId = activeTab.dataset.tab
      showTab(tabId)
    }
  
    // Gestion des clics sur les onglets
    document.querySelectorAll(".nav-tab").forEach((tab) => {
      tab.addEventListener("click", () => {
        const tabId = tab.dataset.tab
  
        // Retirer la classe active de tous les onglets
        document.querySelectorAll(".nav-tab").forEach((t) => t.classList.remove("active"))
        document.querySelectorAll(".tab-panel").forEach((p) => p.classList.remove("active"))
  
        // Ajouter la classe active à l'onglet cliqué
        tab.classList.add("active")
        showTab(tabId)
      })
    })
  
    // Auto-scroll du chat
    const chatContainer = document.getElementById("chat-container")
    if (chatContainer) {
      chatContainer.scrollTop = chatContainer.scrollHeight
    }
  
    // Auto-resize du textarea
    const messageInput = document.querySelector(".message-input")
    if (messageInput) {
      messageInput.addEventListener("input", function () {
        this.style.height = "auto"
        this.style.height = Math.min(this.scrollHeight, 120) + "px"
      })
    }
  
    // Gestion du bouton de pièce jointe
    const attachmentBtn = document.querySelector(".attachment-btn")
    const fileInput = document.querySelector(".file-input")
    if (attachmentBtn && fileInput) {
      attachmentBtn.addEventListener("click", () => {
        fileInput.click()
      })
    }
  
    // Fermer les modales en cliquant à l'extérieur
    setupModalCloseHandlers()
  
    // Masquer les notifications après 5 secondes
    setTimeout(() => {
      const notifications = document.querySelectorAll('[style*="position: fixed"]')
      notifications.forEach((notification) => {
        notification.style.transform = "translateX(400px)"
        notification.style.opacity = "0"
        setTimeout(() => notification.remove(), 300)
      })
    }, 5000)
  })
  
  function showTab(tabId) {
    const panel = document.getElementById(tabId + "-panel")
    if (panel) {
      panel.classList.add("active")
    }
  }
  
  function setupModalCloseHandlers() {
    const modals = [
      "groupActionsModal",
      "imageModal",
      "profileEditModal",
      "contactAddModal",
      "contactEditModal",
      "groupCreateModal",
      "groupMembersModal",
      "groupCoAdminsModal",
      "groupRemoveMemberModal",
      "groupAddMemberModal",
      "groupDeleteModal",
      "groupLeaveModal",
    ]
  
    modals.forEach((modalId) => {
      const modal = document.getElementById(modalId)
      if (modal) {
        modal.addEventListener("click", function (e) {
          if (e.target === this) {
            closeModal(modalId)
          }
        })
      }
    })
  }
  
  function closeModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
      modal.style.display = "none"
      document.body.style.overflow = "auto"
    }
  }
  
  // ========================================
  // FONCTIONS POUR LES CONTACTS
  // ========================================
  
  function afficherModalAjoutContact() {
    const modal = document.getElementById("contactAddModal")
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  
    setTimeout(() => {
      const firstInput = modal.querySelector('input[name="contact_name"]')
      if (firstInput) firstInput.focus()
    }, 100)
  }
  
  function fermerModalAjoutContact() {
    closeModal("contactAddModal")
    const form = document.getElementById("formulaireAjoutContact")
    if (form) form.reset()
  }
  
  function afficherModalEditionContact(idContact, nomContact) {
    const modal = document.getElementById("contactEditModal")
    document.getElementById("idEditionContact").value = idContact
    document.getElementById("nomEditionContact").value = nomContact
  
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  
    setTimeout(() => {
      const nameInput = modal.querySelector('input[name="contact_name"]')
      if (nameInput) {
        nameInput.focus()
        nameInput.select()
      }
    }, 100)
  }
  
  function fermerModalEditionContact() {
    closeModal("contactEditModal")
  }
  
  function confirmerSuppressionContact(idContact, nomContact) {
    showConfirm(
      `Êtes-vous sûr de vouloir supprimer le contact "${nomContact}" ?\n\nCette action est irréversible.`,
      "Confirmation de suppression",
      function() {
        const form = document.createElement("form")
        form.method = "POST"
        form.action = "../api.php"
        form.innerHTML = `
                <input type="hidden" name="action" value="supprimer_contact">
                <input type="hidden" name="contact_id" value="${idContact}">
            `
        document.body.appendChild(form)
        form.submit()
      }
    )
  }
  
  // ========================================
  // FONCTIONS POUR LES GROUPES
  // ========================================
  
  function afficherModalCreationGroupe() {
    const modal = document.getElementById("groupCreateModal")
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  
    setTimeout(() => {
      const firstInput = modal.querySelector('input[name="group_name"]')
      if (firstInput) firstInput.focus()
    }, 100)
  }
  
  function fermerModalCreationGroupe() {
    closeModal("groupCreateModal")
    const form = document.getElementById("formulaireCreationGroupe")
    if (form) form.reset()
  }
function afficherActionsGroupe(idGroupe, nomGroupe, estAdmin, estCoAdmin) {
    const modal = document.getElementById('groupActionsModal');
    const titre = document.getElementById('groupActionsTitle');
    const contenu = document.getElementById('groupActionsContent');
    
    titre.textContent = `Actions - ${nomGroupe}`;
    
    let actionsHtml = `
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="?conversation=groupe:${idGroupe}&tab=discussions" class="modern-btn btn-primary" style="text-decoration: none; text-align: center;">
                <span>💬</span>
                Ouvrir la conversation
            </a>
    `;
    
    if (estAdmin || estCoAdmin) {
        actionsHtml += `
            <button type="button" onclick="listerMembresGroupe('${idGroupe}', '${nomGroupe}')" class="modern-btn btn-secondary">
                <span>👥</span>
                Lister les membres
            </button>
        `;
    }
    
    if (estAdmin) {
        actionsHtml += `
            <button type="button" onclick="gererCoAdmins('${idGroupe}', '${nomGroupe}')" class="modern-btn btn-secondary">
                <span>👑</span>
                Gérer les co-admins
            </button>
            <button type="button" onclick="retirerMembreGroupe('${idGroupe}', '${nomGroupe}')" class="modern-btn btn-warning">
                <span>➖</span>
                Retirer un membre
            </button>
        `;
    }
    
    actionsHtml += `
        </div>
    `;
    
    contenu.innerHTML = actionsHtml;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fermerModalActionsGroupe() {
    document.getElementById('groupActionsModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}
  function listerMembresGroupe(idGroupe, nomGroupe) {
    // Charger les données du groupe via AJAX ou afficher dans un modal
    const modal = document.getElementById('groupActionsModal');
    const titre = document.getElementById('groupActionsTitle');
    const contenu = document.getElementById('groupActionsContent');
    
    titre.textContent = `Membres - ${nomGroupe}`;
    
    // Simuler le chargement des membres (en réalité, on ferait un appel AJAX)
    let membresHtml = `
        <div style="max-height: 300px; overflow-y: auto;">
            <h4>Liste des membres du groupe</h4>
            <div id="membersList">
                <p>Chargement des membres...</p>
            </div>
        </div>
        <div style="margin-top: 15px;">
            <button type="button" onclick="fermerModalActionsGroupe()" class="modern-btn btn-secondary">
                <span>❌</span>
                Fermer
            </button>
        </div>
    `;
    
    contenu.innerHTML = membresHtml;
    
    // Charger les membres via AJAX
    fetch(`../api.php?action=lister_membres`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('membersList').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('membersList').innerHTML = '<p>Erreur lors du chargement des membres.</p>';
        });
}
  function gererActionGroupeSelect(select, idGroupe) {
    const action = select.value
    if (!action) return
  
    select.value = "" // Reset select
  
    switch (action) {
      case "ouvrir_conversation":
        window.location.href = "?conversation=groupe:" + idGroupe + "&tab=groups"
        break
      case "lister_membres":
        // listerMembresGroupe(idGroupe, nomGroupe);
        afficherModalMembresGroupe(idGroupe)
        break
      case "gerer_coadmins":
        afficherModalCoAdminsGroupe(idGroupe)
        break
      case "retirer_membre":
        afficherModalRetirerMembre(idGroupe)
        break
      case "ajouter_membre":
        afficherModalAjouterMembre(idGroupe)
        break
      case "supprimer_groupe":
        afficherModalSupprimerGroupe(idGroupe)
        break
      case "quitter_groupe":
        afficherModalQuitterGroupe(idGroupe)
        break
    }
  }
  
function afficherModalMembresGroupe(idGroupe) {
    console.log("ID du groupe reçu :", idGroupe);

    const modal = document.getElementById("groupMembersModal");
    const titre = document.getElementById("groupMembersTitle");
    const contenu = document.getElementById("groupMembersContent");

    titre.textContent = "Membres du groupe";
    contenu.innerHTML = "<p>Chargement des membres...</p>";

    modal.style.display = "flex";
    document.body.style.overflow = "hidden";

    // Charger les membres via AJAX
    fetch(`../api.php?action=lister_membres&group_id=${idGroupe}`)
        .then((response) => {
            console.log("URL fetch :", response.url);
            return response.text();
        })
        .then((data) => {
            contenu.innerHTML = data;
        })
        .catch((error) => {
            contenu.innerHTML = "<p>Erreur lors du chargement des membres.</p>";
        });
}
  
  function fermerModalMembresGroupe() {
    closeModal("groupMembersModal")
  }
  
  function afficherModalCoAdminsGroupe(idGroupe) {
    const modal = document.getElementById("groupCoAdminsModal")
    const titre = document.getElementById("groupCoAdminsTitle")
    const contenu = document.getElementById("groupCoAdminsContent")
  
    titre.textContent = "Gérer les co-admins"
    contenu.innerHTML = "<p>Chargement...</p>"
  
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  
    fetch(`../api.php?action=get_group_members&id_group=${idGroupe}&action_type=coadmin`)
      .then((response) => response.text())
      .then((data) => {
        contenu.innerHTML = data
      })
      .catch((error) => {
        contenu.innerHTML = "<p>Erreur lors du chargement.</p>"
      })
  }
  
  function fermerModalCoAdminsGroupe() {
    closeModal("groupCoAdminsModal")
  }
  
  function afficherModalRetirerMembre(idGroupe) {
    const modal = document.getElementById("groupRemoveMemberModal")
    const titre = document.getElementById("groupRemoveMemberTitle")
    const contenu = document.getElementById("groupRemoveMemberContent")
  
    titre.textContent = "Retirer un membre"
    contenu.innerHTML = "<p>Chargement...</p>"
  
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  
    fetch(`../api.php?action=get_group_members&id_group=${idGroupe}&action_type=remove`)
      .then((response) => response.text())
      .then((data) => {
        contenu.innerHTML = data
      })
      .catch((error) => {
        contenu.innerHTML = "<p>Erreur lors du chargement.</p>"
      })
  }
  
  function fermerModalRetirerMembre() {
    closeModal("groupRemoveMemberModal")
  }
  
  function afficherModalAjouterMembre(idGroupe) {
    const modal = document.getElementById("groupAddMemberModal")
    const titre = document.getElementById("groupAddMemberTitle")
    const contenu = document.getElementById("groupAddMemberContent")
  
    titre.textContent = "Ajouter un membre"
    contenu.innerHTML = "<p>Chargement...</p>"
  
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  
    fetch(`../api.php?action=get_available_contacts&id_group=${idGroupe}`)
      .then((response) => response.text())
      .then((data) => {
        contenu.innerHTML = data
      })
      .catch((error) => {
        contenu.innerHTML = "<p>Erreur lors du chargement.</p>"
      })
  }
  
  function fermerModalAjouterMembre() {
    closeModal("groupAddMemberModal")
  }
  
  function afficherModalSupprimerGroupe(idGroupe) {
    const modal = document.getElementById("groupDeleteModal")
    const titre = document.getElementById("groupDeleteTitle")
    const contenu = document.getElementById("groupDeleteContent")
  
    titre.textContent = "Supprimer le groupe"
    contenu.innerHTML = `
          <p>Êtes-vous sûr de vouloir supprimer ce groupe ?</p>
          <p><strong>Cette action est irréversible.</strong></p>
          <form method="post" action="../api.php">
              <input type="hidden" name="action" value="supprimer_groupe">
              <input type="hidden" name="group_id" value="${idGroupe}">
              <div class="form-actions">
                  <button type="submit" class="modern-btn btn-danger">
                      <span>🗑️</span> Confirmer la suppression
                  </button>
              </div>
          </form>
      `
  
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  }
  
  function fermerModalSupprimerGroupe() {
    closeModal("groupDeleteModal")
  }
  
  function afficherModalQuitterGroupe(idGroupe) {
    const modal = document.getElementById("groupLeaveModal")
    const titre = document.getElementById("groupLeaveTitle")
    const contenu = document.getElementById("groupLeaveContent")
  
    titre.textContent = "Quitter le groupe"
    contenu.innerHTML = `
          <p>Êtes-vous sûr de vouloir quitter ce groupe ?</p>
          <p><strong>Vous ne pourrez plus accéder aux messages de ce groupe.</strong></p>
          <form method="post" action="../api.php">
              <input type="hidden" name="action" value="quitter_groupe">
              <input type="hidden" name="group_id" value="${idGroupe}">
              <div class="form-actions">
                  <button type="submit" class="modern-btn btn-danger">
                      <span>🚪</span> Confirmer
                  </button>
              </div>
          </form>
      `
  
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  }
  
  function fermerModalQuitterGroupe() {
    closeModal("groupLeaveModal")
  }
  
  // ========================================
  // FONCTIONS POUR LES MODALS
  // ========================================
  
  function ouvrirModalImage(srcImage) {
    const modal = document.getElementById("imageModal")
    const modalImg = document.getElementById("modalImage")
    modal.style.display = "flex"
    modalImg.src = srcImage
    document.body.style.overflow = "hidden"
  }
  
  function fermerModalImage() {
    closeModal("imageModal")
  }
  
  function closeImageModal() {
    fermerModalImage()
  }
  
  function closeGroupActionsModal() {
    closeModal("groupActionsModal")
  }
  
  // ========================================
  // FONCTIONS POUR LE PROFIL
  // ========================================
  
  function afficherModalEditionProfil() {
    const modal = document.getElementById("profileEditModal")
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  }
  
  function fermerModalEditionProfil() {
    closeModal("profileEditModal")
  }
  
  // Fonctions legacy pour compatibilité
  function afficherFormulaireAjoutContact() {
    afficherModalAjoutContact()
  }
  
  function cacherFormulaireAjoutContact() {
    fermerModalAjoutContact()
  }
  
  function afficherFormulaireEditionContact(idContact, nomContact) {
    afficherModalEditionContact(idContact, nomContact)
  }
  
  function cacherFormulaireEditionContact() {
    fermerModalEditionContact()
  }
  
  function afficherFormulaireCreationGroupe() {
    afficherModalCreationGroupe()
  }
  
  function cacherFormulaireCreationGroupe() {
    fermerModalCreationGroupe()
  }
  
  function afficherFormulaireEditionProfil() {
    afficherModalEditionProfil()
  }
  
  function cacherFormulaireEditionProfil() {
    fermerModalEditionProfil()
  }
  
  // Validation des formulaires
  document.addEventListener("DOMContentLoaded", () => {
    // Validation du formulaire d'ajout de contact
    const formulaireAjoutContact = document.getElementById("formulaireAjoutContact")
    if (formulaireAjoutContact) {
      formulaireAjoutContact.addEventListener("submit", function (e) {
        const nomContact = this.querySelector('input[name="contact_name"]').value.trim()
        const telephoneContact = this.querySelector('input[name="contact_telephone"]').value.trim()
  
        if (nomContact.length < 2) {
          e.preventDefault()
          showAlert("Le nom du contact doit contenir au moins 2 caractères.", "Erreur de validation")
          return false
        }
  
        const motifTelephone = /^(77|70|78|76)[0-9]{7}$/
        if (!motifTelephone.test(telephoneContact)) {
          e.preventDefault()
          showAlert("Le numéro de téléphone doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres.", "Erreur de validation")
          return false
        }
  
        setTimeout(() => {
          fermerModalAjoutContact()
        }, 100)
      })
    }
  
    // Validation du formulaire de création de groupe
    const formulaireCreationGroupe = document.getElementById("formulaireCreationGroupe")
    if (formulaireCreationGroupe) {
      formulaireCreationGroupe.addEventListener("submit", function (e) {
        const nomGroupe = this.querySelector('input[name="group_name"]').value.trim()
        const membresSelectionnes = this.querySelectorAll('input[name="ids_membres[]"]:checked')
  
        if (nomGroupe.length < 2) {
          e.preventDefault()
          showAlert("Le nom du groupe doit contenir au moins 2 caractères.", "Erreur de validation")
          return false
        }
  
        if (membresSelectionnes.length < 2) {
          e.preventDefault()
          showAlert("Vous devez sélectionner au moins 2 contacts pour créer un groupe.", "Erreur de validation")
          return false
        }
  
        setTimeout(() => {
          fermerModalCreationGroupe()
        }, 100)
      })
    }
  })
  