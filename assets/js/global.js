// ========================================
// SCRIPT PRINCIPAL POUR VIEW.PHP
// ========================================

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

    // Validation du formulaire d'ajout de contact
    const formulaireAjoutContact = document.getElementById('formulaireAjoutContact');
    if (formulaireAjoutContact) {
        formulaireAjoutContact.addEventListener('submit', function(e) {
            const nomContact = this.querySelector('input[name="nom_contact"]').value.trim();
            const telephoneContact = this.querySelector('input[name="telephone_contact"]').value.trim();
            
            // Vérifier que le nom n'est pas vide
            if (nomContact.length < 2) {
                e.preventDefault();
                alert('Le nom du contact doit contenir au moins 2 caractères.');
                return false;
            }
            
            // Vérifier le format du numéro de téléphone
            const motifTelephone = /^(77|70|78|76)[0-9]{7}$/;
            if (!motifTelephone.test(telephoneContact)) {
                e.preventDefault();
                alert('Le numéro de téléphone doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres.');
                return false;
            }
            
            // Vérifier que l'utilisateur ne s'ajoute pas lui-même
            const telephoneUtilisateurCourant = document.querySelector('input[name="current_user_telephone"]')?.value || '';
            if (telephoneContact === telephoneUtilisateurCourant) {
                e.preventDefault();
                alert('Vous ne pouvez pas vous ajouter vous-même comme contact.');
                return false;
            }
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

    document.querySelectorAll('.group-card').forEach(card => {
        card.addEventListener('click', function() {
            const groupId = this.dataset.groupId;
            window.location.href = '?conversation=groupe:' + groupId + '&tab=discussions';
        });
    });
});

// ========================================
// FONCTIONS POUR LES CONTACTS
// ========================================

// Fonction de confirmation pour la suppression de contact
function confirmerSuppressionContact(idContact, nomContact) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le contact "${nomContact}" ?\n\nCette action est irréversible.`)) {
        document.getElementById('contactIdToDelete').value = idContact;
        document.getElementById('deleteContactForm').submit();
    }
}

// Fonctions pour l'ajout de contact
function afficherFormulaireAjoutContact() {
    document.getElementById('formulaireAjoutContact').style.display = 'block';
    document.querySelector('#formulaireAjoutContact input[name="nom_contact"]').focus();
}

function cacherFormulaireAjoutContact() {
    document.getElementById('formulaireAjoutContact').style.display = 'none';
    document.getElementById('formulaireAjoutContact').querySelector('form').reset();
}

// Fonction pour l'édition de contact (à implémenter plus tard)
function editerContact(idContact, nomContact, telephoneContact) {
    alert(`Édition du contact "${nomContact}" (${telephoneContact})\n\nCette fonctionnalité sera implémentée prochainement.`);
}

function afficherFormulaireEditionContact(idContact, nomContact) {
    document.getElementById('idEditionContact').value = idContact;
    document.getElementById('nomEditionContact').value = nomContact;
    document.getElementById('formulaireEditionContact').style.display = 'block';
}
function cacherFormulaireEditionContact() {
    document.getElementById('formulaireEditionContact').style.display = 'none';
}

// ========================================
// FONCTIONS POUR LES GROUPES
// ========================================

// Fonctions pour la gestion des groupes
function afficherFormulaireCreationGroupe() {
    document.getElementById('formulaireCreationGroupe').style.display = '';
    document.querySelector('#formulaireCreationGroupe input[name="nom_groupe"]').focus();
}

function cacherFormulaireCreationGroupe() {
    document.getElementById('formulaireCreationGroupe').style.display = 'none';
    document.getElementById('formulaireCreationGroupe').querySelector('form').reset();
}

function confirmerSuppressionGroupe(idGroupe, nomGroupe) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le groupe "${nomGroupe}" ?\n\nCette action est irréversible et supprimera définitivement le groupe.`)) {
        document.getElementById('groupIdToDelete').value = idGroupe;
        document.getElementById('deleteGroupForm').submit();
    }
}

function confirmerQuitterGroupe(idGroupe, nomGroupe) {
    if (confirm(`Êtes-vous sûr de vouloir quitter le groupe "${nomGroupe}" ?\n\nVous ne pourrez plus accéder aux messages de ce groupe.`)) {
        document.getElementById('groupIdToLeave').value = idGroupe;
        document.getElementById('leaveGroupForm').submit();
    }
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

// === MODALS GROUPES ===
function showAddGroupModal() {
    document.getElementById('add-group-modal').classList.remove('hidden');
}
function closeAddGroupModal() {
    document.getElementById('add-group-modal').classList.add('hidden');
    document.getElementById('add-group-modal').querySelector('form').reset();
}
function showGroupInfoModal() {
    document.getElementById('group-info-modal').classList.remove('hidden');
}
function closeGroupInfoModal() {
    document.getElementById('group-info-modal').classList.add('hidden');
}
function showEditGroupModal() {
    document.getElementById('edit-group-modal').classList.remove('hidden');
}
function closeEditGroupModal() {
    document.getElementById('edit-group-modal').classList.add('hidden');
    document.getElementById('edit-group-modal').querySelector('form').reset();
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

// Version alternative de gererActionGroupeSelect
function gererActionGroupeSelectAlt(selectEl, idGroupe) {
    const action = selectEl.value;
    selectEl.value = ""; // reset

    switch(action) {
        case 'ouvrir_conversation':
            window.location.href = '?conversation=groupe:' + idGroupe;
            break;
        case 'lister_membres':
            document.getElementById('liste-membres-' + idGroupe).style.display = 'flex';
            break;
        case 'gerer_coadmins':
            document.getElementById('coadmins-modal-' + idGroupe).style.display = 'flex';
            break;
        case 'retirer_membre':
            document.getElementById('retirer-membre-modal-' + idGroupe).style.display = 'flex';
            break;
        case 'supprimer_groupe':
            document.getElementById('supprimer-groupe-modal-' + idGroupe).style.display = 'flex';
            break;
        case 'quitter_groupe':
            document.getElementById('quitter-groupe-modal-' + idGroupe).style.display = 'flex';
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
function afficherFormulaireEditionContact(id, nom) {
    document.getElementById('formulaireEditionContact').style.display = 'block';
    document.getElementById('idEditionContact').value = id;
    document.getElementById('nomEditionContact').value = nom;
}
function cacherFormulaireEditionContact() {
    document.getElementById('formulaireEditionContact').style.display = 'none';
}
function confirmerSuppressionContact(id, nom) {
    if (confirm('Êtes-vous sûr de vouloir supprimer le contact "' + nom + '" ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../api.php';
        form.innerHTML = '<input type="hidden" name="action" value="supprimer_contact"><input type="hidden" name="id_contact" value="' + id + '">';
        document.body.appendChild(form);
        form.submit();
    }
}

// Fonctions pour le profil
function afficherFormulaireEditionProfil() {
    document.getElementById('formulaireEditionProfil').style.display = 'block';
    document.getElementById('afficherBoutonEditionProfil').style.display = 'none';
}
function cacherFormulaireEditionProfil() {
    document.getElementById('formulaireEditionProfil').style.display = 'none';
    document.getElementById('afficherBoutonEditionProfil').style.display = 'inline-block';
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
            window.location.href = 'discussions_view.php?groupe=' + idGroupe;
            break;
        case 'lister_membres':
            document.getElementById('liste-membres-' + idGroupe).style.display = 'block';
            break;
        case 'gerer_coadmins':
            document.getElementById('coadmins-modal-' + idGroupe).style.display = 'block';
            break;
        case 'retirer_membre':
            document.getElementById('retirer-membre-modal-' + idGroupe).style.display = 'block';
            break;
        case 'ajouter_membre':
            document.getElementById('ajouter-membre-modal-' + idGroupe).style.display = 'block';
            break;
        case 'supprimer_groupe':
            document.getElementById('supprimer-groupe-modal-' + idGroupe).style.display = 'block';
            break;
        case 'quitter_groupe':
            document.getElementById('quitter-groupe-modal-' + idGroupe).style.display = 'block';
            break;
    }
    select.value = ''; // Reset select
}

// Fonction pour ouvrir le modal d'infos groupe avec données dynamiques
function openGroupInfo(id, name, description, photo, members) {
    document.getElementById('group-info-name').value = name;
    document.getElementById('group-info-description').value = description;
    document.getElementById('group-info-photo').src = photo || '/placeholder.svg?height=80&width=80&text=' + (name ? name.charAt(0).toUpperCase() : 'G');
    // Remplir la liste des membres
    const membersList = document.getElementById('group-members-list');
    membersList.innerHTML = '';
    if (Array.isArray(members)) {
        members.forEach(m => {
            const div = document.createElement('div');
            div.className = 'member-checkbox';
            div.textContent = m;
            membersList.appendChild(div);
        });
    }
    showGroupInfoModal();
    // Stocker l'id du groupe pour édition
    document.getElementById('edit-group-id').value = id;
}

// Fonction pour pré-remplir le modal d'édition de groupe
function editGroup() {
    // Récupérer les valeurs actuelles du modal info
    const id = document.getElementById('edit-group-id').value;
    const name = document.getElementById('group-info-name').value;
    const description = document.getElementById('group-info-description').value;
    document.getElementById('edit-group-id').value = id;
    document.getElementById('edit_group_name').value = name;
    document.getElementById('edit_group_description').value = description;
    closeGroupInfoModal();
    showEditGroupModal();
}

// ========================================
// FONCTION POUR OUVRIR UNE DISCUSSION CONTACT
// ========================================
function openChat(contactId, contactName, contactPhoto) {
    // Masquer le panneau contacts si présent
    const contactsPanel = document.getElementById('contacts-panel');
    if (contactsPanel) contactsPanel.classList.add('hidden');
    // Afficher le panneau de discussion si présent
    const discussionsPanel = document.getElementById('discussions-panel');
    if (discussionsPanel) discussionsPanel.classList.remove('hidden');

    // Mettre à jour l'avatar et le nom dans le header de la messagerie
    const chatAvatar = document.getElementById('chat-avatar');
    const chatName = document.getElementById('chat-name');
    if (chatAvatar && chatName) {
        if (contactPhoto && contactPhoto !== 'default.jpg') {
            chatAvatar.src = '../uploads/' + contactPhoto;
            chatAvatar.alt = contactName;
        } else {
            // Générer les initiales
            const initials = contactName.split(' ').map(n => n[0]).join('').toUpperCase();
            // Générer une image SVG avec les initiales
            chatAvatar.src = `data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40'><rect width='100%' height='100%' fill='%23ccc'/><text x='50%' y='55%' font-size='18' font-family='Arial' fill='white' text-anchor='middle' alignment-baseline='middle'>${initials}</text></svg>`;
            chatAvatar.alt = initials;
        }
        chatName.textContent = contactName;
    }
    // Charger les messages du contact (à compléter selon ton backend)
    // ...
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