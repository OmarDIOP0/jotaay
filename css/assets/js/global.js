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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nav-tab').forEach(onglet => {
        onglet.addEventListener('click', () => {
            // Retirer la classe active de tous les onglets
            document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            
            // Ajouter la classe active à l'onglet cliqué
            onglet.classList.add('active');
            document.getElementById(onglet.dataset.tab + '-panel').classList.add('active');
        });
    });

    // Maintenir l'onglet actif selon l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const ongletActif = urlParams.get('tab');
    if (ongletActif) {
        // Retirer la classe active de tous les onglets
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        
        // Ajouter la classe active à l'onglet spécifié
        const cibleOnglet = document.querySelector(`[data-tab="${ongletActif}"]`);
        const ciblePanel = document.getElementById(ongletActif + '-panel');
        
        if (cibleOnglet && ciblePanel) {
            cibleOnglet.classList.add('active');
            ciblePanel.classList.add('active');
        }
    }

    // Fermer le modal des actions de groupe en cliquant à l'extérieur
    const modalActionsGroupe = document.getElementById('groupActionsModal');
    if (modalActionsGroupe) {
        modalActionsGroupe.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModalActionsGroupe();
            }
        });
    }

    // Fermer le modal d'image en cliquant à l'extérieur
    const modalImage = document.getElementById('imageModal');
    if (modalImage) {
        modalImage.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModalImage();
            }
        });
    }

    // Fermer le modal d'édition de profil en cliquant à l'extérieur
    const modalProfileEdit = document.getElementById('profileEditModal');
    if (modalProfileEdit) {
        modalProfileEdit.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModalEditionProfil();
            }
        });
    }

    // Fermer le modal d'ajout de contact en cliquant à l'extérieur
    const modalContactAdd = document.getElementById('contactAddModal');
    if (modalContactAdd) {
        modalContactAdd.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModalAjoutContact();
            }
        });
    }

    // Fermer le modal d'édition de contact en cliquant à l'extérieur
    const modalContactEdit = document.getElementById('contactEditModal');
    if (modalContactEdit) {
        modalContactEdit.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModalEditionContact();
            }
        });
    }

    // Fermer le modal de création de groupe en cliquant à l'extérieur
    const modalGroupCreate = document.getElementById('groupCreateModal');
    if (modalGroupCreate) {
        modalGroupCreate.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModalCreationGroupe();
            }
        });
    }

    // Fermer les modales de groupe en cliquant à l'extérieur
    const modalesGroupe = [
        'groupMembersModal',
        'groupCoAdminsModal', 
        'groupRemoveMemberModal',
        'groupAddMemberModal',
        'groupDeleteModal',
        'groupLeaveModal'
    ];

    modalesGroupe.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    switch(modalId) {
                        case 'groupMembersModal':
                            fermerModalMembresGroupe();
                            break;
                        case 'groupCoAdminsModal':
                            fermerModalCoAdminsGroupe();
                            break;
                        case 'groupRemoveMemberModal':
                            fermerModalRetirerMembre();
                            break;
                        case 'groupAddMemberModal':
                            fermerModalAjouterMembre();
                            break;
                        case 'groupDeleteModal':
                            fermerModalSupprimerGroupe();
                            break;
                        case 'groupLeaveModal':
                            fermerModalQuitterGroupe();
                            break;
                    }
                }
            });
        }
    });

    // Validation du formulaire d'ajout de contact
    const formulaireAjoutContact = document.getElementById('formulaireAjoutContact');
    if (formulaireAjoutContact) {
        formulaireAjoutContact.addEventListener('submit', function(e) {
            const nomContact = this.querySelector('input[name="contact_name"]').value.trim();
            const telephoneContact = this.querySelector('input[name="contact_telephone"]').value.trim();
            
            // Vérifier que le nom n'est pas vide
            if (nomContact.length < 2) {
                e.preventDefault();
                showAlert('Le nom du contact doit contenir au moins 2 caractères.', 'Erreur de validation');
                return false;
            }
            
            // Vérifier le format du numéro de téléphone
            const motifTelephone = /^(77|70|78|76)[0-9]{7}$/;
            if (!motifTelephone.test(telephoneContact)) {
                e.preventDefault();
                showAlert('Le numéro de téléphone doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres.', 'Erreur de validation');
                return false;
            }
            
            // Vérifier que l'utilisateur ne s'ajoute pas lui-même
            const telephoneUtilisateurCourant = document.querySelector('input[name="current_user_telephone"]')?.value || '';
            if (telephoneContact === telephoneUtilisateurCourant) {
                e.preventDefault();
                showAlert('Vous ne pouvez pas vous ajouter vous-même comme contact.', 'Erreur de validation');
                return false;
            }
            
            // Fermer la modale après soumission réussie
            setTimeout(() => {
                fermerModalAjoutContact();
            }, 100);
        });
    }

    // Auto-scroll du chat
    const conteneurChat = document.getElementById('chat-container');
    if (conteneurChat) {
        conteneurChat.scrollTop = conteneurChat.scrollHeight;
    }

    // Auto-resize du textarea
    const champMessage = document.querySelector('.message-input');
    if (champMessage) {
        champMessage.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Gestion du bouton de pièce jointe
    const attachmentBtn = document.querySelector('.attachment-btn');
    const fileInput = document.querySelector('.file-input');
    if (attachmentBtn && fileInput) {
        attachmentBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // Validation du formulaire d'édition de contact
    const formulaireEditionContact = document.getElementById('formulaireEditionContact');
    if (formulaireEditionContact) {
        formulaireEditionContact.addEventListener('submit', function(e) {
            const nomContact = this.querySelector('input[name="contact_name"]').value.trim();
            
            // Vérifier que le nom n'est pas vide
            if (nomContact.length < 2) {
                e.preventDefault();
                showAlert('Le nom du contact doit contenir au moins 2 caractères.', 'Erreur de validation');
                return false;
            }
            
            // Fermer la modale après soumission réussie
            setTimeout(() => {
                fermerModalEditionContact();
            }, 100);
        });
    }

    // Validation du formulaire de création de groupe
    const formulaireCreationGroupe = document.getElementById('formulaireCreationGroupe');
    if (formulaireCreationGroupe) {
        formulaireCreationGroupe.addEventListener('submit', function(e) {
            const nomGroupe = this.querySelector('input[name="group_name"]').value.trim();
            const membresSelectionnes = this.querySelectorAll('input[name="ids_membres[]"]:checked');
            
            // Vérifier que le nom du groupe n'est pas vide
            if (nomGroupe.length < 2) {
                e.preventDefault();
                showAlert('Le nom du groupe doit contenir au moins 2 caractères.', 'Erreur de validation');
                return false;
            }
            
            // Vérifier qu'au moins 2 membres sont sélectionnés
            if (membresSelectionnes.length < 2) {
                e.preventDefault();
                showAlert('Vous devez sélectionner au moins 2 contacts pour créer un groupe.', 'Erreur de validation');
                return false;
            }
            
            // Fermer la modale après soumission réussie
            setTimeout(() => {
                fermerModalCreationGroupe();
            }, 100);
        });
    }

    // Notification pour les erreurs et succès
    setTimeout(() => {
        const notifErreur = document.querySelector('[style*="position: fixed"]');
        if (notifErreur) {
            notifErreur.style.transform = 'translateX(400px)';
            notifErreur.style.opacity = '0';
            setTimeout(() => notifErreur.remove(), 300);
        }
    }, 5000);

    // Recherche dynamique des groupes

    const champRecherche = document.getElementById('rechercheGroupes');
    if (champRecherche) {
        champRecherche.addEventListener('input', function() {
            const filtre = champRecherche.value.toLowerCase();
            document.querySelectorAll('.groupe-item').forEach(function(item) {
                const nom = item.textContent.toLowerCase();
                item.style.display = nom.includes(filtre) ? '' : 'none';
            });
        });
    }

    const champRechercheDiscussions = document.getElementById('rechercheDiscussions');
    if (champRechercheDiscussions) {
        champRechercheDiscussions.addEventListener('input', function() {
            const filtre = champRechercheDiscussions.value.toLowerCase();
            document.querySelectorAll('.discussion-item').forEach(function(item) {
                const nom = item.textContent.toLowerCase();
                item.style.display = nom.includes(filtre) ? '' : 'none';
            });
        });
    }

    const champRechercheContacts = document.getElementById('rechercheContacts');
    if (champRechercheContacts) {
        champRechercheContacts.addEventListener('input', function() {
            const filtre = champRechercheContacts.value.toLowerCase();
            document.querySelectorAll('.contact-item').forEach(function(item) {
                const nom = item.textContent.toLowerCase();
                item.style.display = nom.includes(filtre) ? '' : 'none';
            });
        });
    }
});

// ========================================
// FONCTIONS POUR LES CONTACTS
// ========================================

// Fonction de confirmation pour la suppression de contact
function confirmerSuppressionContact(idContact, nomContact) {
    showConfirm(
        `Êtes-vous sûr de vouloir supprimer le contact "${nomContact}" ?\n\nCette action est irréversible.`,
        "Confirmation de suppression",
        function() {
            document.getElementById('contactIdToDelete').value = idContact;
            document.getElementById('deleteContactForm').submit();
        }
    );
}

// Fonctions pour l'ajout de contact
function afficherModalAjoutContact() {
    const modal = document.getElementById('contactAddModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Focus sur le premier champ
    setTimeout(() => {
        const firstInput = modal.querySelector('input[name="contact_name"]');
        if (firstInput) firstInput.focus();
    }, 100);
}

function fermerModalAjoutContact() {
    const modal = document.getElementById('contactAddModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Réinitialiser le formulaire
    const form = modal.querySelector('form');
    if (form) form.reset();
}

// Fonctions legacy pour compatibilité
function afficherFormulaireAjoutContact() {
    afficherModalAjoutContact();
}

function cacherFormulaireAjoutContact() {
    fermerModalAjoutContact();
}

// Fonction pour l'édition de contact (à implémenter plus tard)
function editerContact(idContact, nomContact, telephoneContact) {
    alert(`Édition du contact "${nomContact}" (${telephoneContact})\n\nCette fonctionnalité sera implémentée prochainement.`);
}

function afficherModalEditionContact(idContact, nomContact) {
    console.log(">>> ID transmis :", idContact);
    console.log(">>> Nom transmis :", nomContact);
    
    const modal = document.getElementById('contactEditModal');
    document.getElementById('idEditionContact').value = idContact;
    document.getElementById('nomEditionContact').value = nomContact;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Focus sur le champ de nom
    setTimeout(() => {
        const nameInput = modal.querySelector('input[name="contact_name"]');
        if (nameInput) {
            nameInput.focus();
            nameInput.select();
        }
    }, 100);
}

function fermerModalEditionContact() {
    const modal = document.getElementById('contactEditModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fonctions legacy pour compatibilité
function afficherFormulaireEditionContact(idContact, nomContact) {
    afficherModalEditionContact(idContact, nomContact);
}

function cacherFormulaireEditionContact() {
    fermerModalEditionContact();
}

// ========================================
// FONCTIONS POUR LES GROUPES
// ========================================

// Fonctions pour la gestion des groupes
function afficherModalCreationGroupe() {
    const modal = document.getElementById('groupCreateModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Focus sur le premier champ
    setTimeout(() => {
        const firstInput = modal.querySelector('input[name="group_name"]');
        if (firstInput) firstInput.focus();
    }, 100);
}

function fermerModalCreationGroupe() {
    const modal = document.getElementById('groupCreateModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Réinitialiser le formulaire
    const form = modal.querySelector('form');
    if (form) form.reset();
}

// Fonctions legacy pour compatibilité
function afficherFormulaireCreationGroupe() {
    afficherModalCreationGroupe();
}

function cacherFormulaireCreationGroupe() {
    fermerModalCreationGroupe();
}

function confirmerSuppressionGroupe(idGroupe, nomGroupe) {
    showConfirm(
        `Êtes-vous sûr de vouloir supprimer le groupe "${nomGroupe}" ?\n\nCette action est irréversible et supprimera définitivement le groupe.`,
        "Confirmation de suppression",
        function() {
            document.getElementById('groupIdToDelete').value = idGroupe;
            document.getElementById('deleteGroupForm').submit();
        }
    );
}

function confirmerQuitterGroupe(idGroupe, nomGroupe) {
    showConfirm(
        `Êtes-vous sûr de vouloir quitter le groupe "${nomGroupe}" ?\n\nVous ne pourrez plus accéder aux messages de ce groupe.`,
        "Confirmation de quitter",
        function() {
            document.getElementById('groupIdToLeave').value = idGroupe;
            document.getElementById('leaveGroupForm').submit();
        }
    );
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
    fetch(`../api.php?action=list_members&id_group=${idGroupe}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('membersList').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('membersList').innerHTML = '<p>Erreur lors du chargement des membres.</p>';
        });
}

function gererCoAdmins(idGroupe, nomGroupe) {
    const modal = document.getElementById('groupActionsModal');
    const titre = document.getElementById('groupActionsTitle');
    const contenu = document.getElementById('groupActionsContent');
    
    titre.textContent = `Gestion des co-admins - ${nomGroupe}`;
    
    let coadminHtml = `
        <div style="max-height: 400px; overflow-y: auto;">
            <h4>Gérer les co-admins</h4>
            <div id="coadminList">
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
    
    contenu.innerHTML = coadminHtml;
    
    // Charger les membres pour la gestion des co-admins
    fetch(`../api.php?action=get_group_members&id_group=${idGroupe}&action_type=coadmin`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('coadminList').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('coadminList').innerHTML = '<p>Erreur lors du chargement des membres.</p>';
        });
}

function retirerMembreGroupe(idGroupe, nomGroupe) {
    const modal = document.getElementById('groupActionsModal');
    const titre = document.getElementById('groupActionsTitle');
    const contenu = document.getElementById('groupActionsContent');
    
    titre.textContent = `Retirer un membre - ${nomGroupe}`;
    
    let retirerHtml = `
        <div style="max-height: 400px; overflow-y: auto;">
            <h4>Sélectionner un membre à retirer</h4>
            <div id="removeMemberList">
                <p>Chargement des membres...</p>
            </div>
        </div>
        <div style="margin-top: 15px;">
            <button type="button" onclick="fermerModalActionsGroupe()" class="modern-btn btn-secondary">
                <span>❌</span>
                Annuler
            </button>
        </div>
    `;
    
    contenu.innerHTML = retirerHtml;
    
    // Charger les membres pour le retrait
    fetch(`../api.php?action=get_group_members&id_group=${idGroupe}&action_type=remove`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('removeMemberList').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('removeMemberList').innerHTML = '<p>Erreur lors du chargement des membres.</p>';
        });
}

// ========================================
// FONCTIONS POUR LES MODALS
// ========================================

// Fonctions pour le modal d'image
function ouvrirModalImage(srcImage) {
    document.getElementById('modalImage').src = srcImage;
    document.getElementById('imageModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fermerModalImage() {
    document.getElementById('imageModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// ========================================
// FONCTIONS UTILITAIRES
// ========================================

function gererActionGroupeSelect(select, idGroupe) {
    const valeur = select.value;
    if (!valeur) return;
    // Réinitialiser le select après choix
    select.selectedIndex = 0;
    if (valeur === 'ouvrir_conversation') {
        window.location.href = '?conversation=groupe:' + idGroupe + '&tab=discussions';
    } else if (valeur === 'lister_membres') {
        document.getElementById('liste-membres-' + idGroupe).style.display = 'flex';
    } else if (valeur === 'gerer_coadmins') {
        document.getElementById('coadmins-modal-' + idGroupe).style.display = 'flex';
    } else if (valeur === 'retirer_membre') {
        document.getElementById('retirer-membre-modal-' + idGroupe).style.display = 'flex';
    } else if (valeur === 'supprimer_groupe') {
        document.getElementById('supprimer-groupe-modal-' + idGroupe).style.display = 'flex';
    } else if (valeur === 'quitter_groupe') {
        document.getElementById('quitter-groupe-modal-' + idGroupe).style.display = 'flex';
    } else if (valeur === 'ajouter_membre') {
        document.getElementById('ajouter-membre-modal-' + idGroupe).style.display = 'flex';
    }
}

// Fonctions pour les modales de groupe
function afficherModalMembresGroupe(idGroupe) {
    const modal = document.getElementById('groupMembersModal');
    const titre = document.getElementById('groupMembersTitle');
    const contenu = document.getElementById('groupMembersContent');
    
    titre.textContent = `Membres du groupe`;
    
    // Charger les membres via AJAX
    fetch(`../api.php?action=list_members&id_group=${idGroupe}`)
        .then(response => response.text())
        .then(data => {
            contenu.innerHTML = data;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            contenu.innerHTML = '<p>Erreur lors du chargement des membres.</p>';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
}

function fermerModalMembresGroupe() {
    const modal = document.getElementById('groupMembersModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function afficherModalCoAdminsGroupe(idGroupe) {
    const modal = document.getElementById('groupCoAdminsModal');
    const titre = document.getElementById('groupCoAdminsTitle');
    const contenu = document.getElementById('groupCoAdminsContent');
    
    titre.textContent = `Gérer les co-admins`;
    
    // Charger les co-admins via AJAX
    fetch(`../api.php?action=get_group_members&id_group=${idGroupe}&action_type=coadmin`)
        .then(response => response.text())
        .then(data => {
            contenu.innerHTML = data;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            contenu.innerHTML = '<p>Erreur lors du chargement des membres.</p>';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
}

function fermerModalCoAdminsGroupe() {
    const modal = document.getElementById('groupCoAdminsModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function afficherModalRetirerMembre(idGroupe) {
    const modal = document.getElementById('groupRemoveMemberModal');
    const titre = document.getElementById('groupRemoveMemberTitle');
    const contenu = document.getElementById('groupRemoveMemberContent');
    
    titre.textContent = `Retirer un membre`;
    
    // Charger les membres pour le retrait via AJAX
    fetch(`../api.php?action=get_group_members&id_group=${idGroupe}&action_type=remove`)
        .then(response => response.text())
        .then(data => {
            contenu.innerHTML = data;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            contenu.innerHTML = '<p>Erreur lors du chargement des membres.</p>';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
}

function fermerModalRetirerMembre() {
    const modal = document.getElementById('groupRemoveMemberModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function afficherModalAjouterMembre(idGroupe) {
    const modal = document.getElementById('groupAddMemberModal');
    const titre = document.getElementById('groupAddMemberTitle');
    const contenu = document.getElementById('groupAddMemberContent');
    
    titre.textContent = `Ajouter un membre`;
    
    // Charger les contacts disponibles via AJAX
    fetch(`../api.php?action=get_available_contacts&id_group=${idGroupe}`)
        .then(response => response.text())
        .then(data => {
            contenu.innerHTML = data;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            contenu.innerHTML = '<p>Erreur lors du chargement des contacts.</p>';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
}

function fermerModalAjouterMembre() {
    const modal = document.getElementById('groupAddMemberModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function afficherModalSupprimerGroupe(idGroupe) {
    const modal = document.getElementById('groupDeleteModal');
    const titre = document.getElementById('groupDeleteTitle');
    const contenu = document.getElementById('groupDeleteContent');
    
    titre.textContent = `Supprimer le groupe`;
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
    `;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fermerModalSupprimerGroupe() {
    const modal = document.getElementById('groupDeleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function afficherModalQuitterGroupe(idGroupe) {
    const modal = document.getElementById('groupLeaveModal');
    const titre = document.getElementById('groupLeaveTitle');
    const contenu = document.getElementById('groupLeaveContent');
    
    titre.textContent = `Quitter le groupe`;
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
    `;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fermerModalQuitterGroupe() {
    const modal = document.getElementById('groupLeaveModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Version alternative de gererActionGroupeSelect (legacy)
function gererActionGroupeSelectAlt(selectEl, idGroupe) {
    const action = selectEl.value;
    selectEl.value = ""; // reset

    switch(action) {
        case 'ouvrir_conversation':
            window.location.href = '?conversation=groupe:' + idGroupe;
            break;
        case 'lister_membres':
            afficherModalMembresGroupe(idGroupe);
            break;
        case 'gerer_coadmins':
            afficherModalCoAdminsGroupe(idGroupe);
            break;
        case 'retirer_membre':
            afficherModalRetirerMembre(idGroupe);
            break;
        case 'ajouter_membre':
            afficherModalAjouterMembre(idGroupe);
            break;
        case 'supprimer_groupe':
            afficherModalSupprimerGroupe(idGroupe);
            break;
        case 'quitter_groupe':
            afficherModalQuitterGroupe(idGroupe);
            break;
    }
} 

// Fonctions pour les contacts
function afficherFormulaireAjoutContact() {
    document.getElementById('formulaireAjoutContact').style.display = 'block';
}
function cacherFormulaireAjoutContact() {
    document.getElementById('formulaireAjoutContact').style.display = 'none';
}
// function afficherFormulaireEditionContact(id, nom) {
//     document.getElementById('formulaireEditionContact').style.display = 'block';
//     document.getElementById('idEditionContact').value = id;
//     document.getElementById('nomEditionContact').value = nom;
// }
function cacherFormulaireEditionContact() {
    document.getElementById('formulaireEditionContact').style.display = 'none';
}
function confirmerSuppressionContact(id, nom) {
    showConfirm(
        'Êtes-vous sûr de vouloir supprimer le contact "' + nom + '" ?',
        "Confirmation de suppression",
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../api.php';
            form.innerHTML = '<input type="hidden" name="action" value="supprimer_contact"><input type="hidden" name="contact_id" value="' + id + '">';
            document.body.appendChild(form);
            form.submit();
        }
    );
}

// Fonctions pour le profil
function afficherModalEditionProfil() {
    const modal = document.getElementById('profileEditModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fermerModalEditionProfil() {
    const modal = document.getElementById('profileEditModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fonctions legacy pour compatibilité (si encore utilisées ailleurs)
function afficherFormulaireEditionProfil() {
    afficherModalEditionProfil();
}

function cacherFormulaireEditionProfil() {
    fermerModalEditionProfil();
}

// Fonctions pour les modales
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'block';
    modalImg.src = imageSrc;
}
function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}
function closeGroupActionsModal() {
    document.getElementById('groupActionsModal').style.display = 'none';
}

// Fonction pour gérer les actions de groupe
function gererActionGroupeSelect(select, idGroupe) {
    const action = select.value;
    if (!action) return;
    
    switch(action) {
        case 'ouvrir_conversation':
            window.location.href = '?conversation=groupe:' + idGroupe + '&tab=discussions';
            break;
        case 'lister_membres':
            afficherModalMembresGroupe(idGroupe);
            break;
        case 'gerer_coadmins':
            afficherModalCoAdminsGroupe(idGroupe);
            break;
        case 'retirer_membre':
            afficherModalRetirerMembre(idGroupe);
            break;
        case 'ajouter_membre':
            afficherModalAjouterMembre(idGroupe);
            break;
        case 'supprimer_groupe':
            afficherModalSupprimerGroupe(idGroupe);
            break;
        case 'quitter_groupe':
            afficherModalQuitterGroupe(idGroupe);
            break;
    }
    select.value = ''; // Reset select
}

// Fermer les modales en cliquant en dehors
window.onclick = function(event) {
    const modals = document.querySelectorAll('.image-modal');
    modals.forEach(function(modal) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    const imageModal = document.getElementById('imageModal');
    if (event.target === imageModal) {
        imageModal.style.display = 'none';
    }
    
    const groupActionsModal = document.getElementById('groupActionsModal');
    if (event.target === groupActionsModal) {
        groupActionsModal.style.display = 'none';
    }
}

// Fonction pour nettoyer l'URL après affichage des messages
function nettoyerUrl() {
    // Vérifier si l'URL contient des paramètres à nettoyer
    const urlParams = new URLSearchParams(window.location.search);
    const paramsANettoyer = ['success', 'error', 'conversation', 'groupe', 'contact'];
    
    let hasParamsToClean = false;
    for (let param of paramsANettoyer) {
        if (urlParams.has(param)) {
            hasParamsToClean = true;
            break;
        }
    }
    
    if (hasParamsToClean) {
        // Attendre 3 secondes puis nettoyer l'URL
        setTimeout(function() {
            // Supprimer tous les paramètres de l'URL sans recharger la page
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 3000);
    }
}

// Exécuter le nettoyage d'URL au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    nettoyerUrl();
});