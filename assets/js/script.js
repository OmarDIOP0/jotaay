// Variables globales
let currentChat = null
let currentChatType = null
let currentChatId = null
let currentContactId = null
let currentGroupId = null
let confirmationCallback = null
const contactsData = [] // Declare contactsData
const usersData = [] // Declare usersData
const groupsData = [] // Declare groupsData
const messagesData = [] // Declare messagesData
const currentUserId = null // Declare currentUserId
const currentUserPhone = null // Declare currentUserPhone

// Initialisation
document.addEventListener("DOMContentLoaded", () => {
  initializeApp()
})

function initializeApp() {
  // Initialiser les événements
  setupEventListeners()

  // Auto-scroll des messages
  scrollToBottom()

  // Afficher la notification si elle existe
  const notification = document.getElementById("notification")
  if (notification && !notification.classList.contains("hidden")) {
    setTimeout(() => {
      closeNotification()
    }, 5000)
  }

  // Responsive
  updateResponsiveElements()
}

function setupEventListeners() {
  // Input de message
  const messageInput = document.getElementById("message-input")
  if (messageInput) {
    messageInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault()
        sendMessage()
      }
    })

    messageInput.addEventListener("input", function () {
      adjustTextareaHeight(this)
    })
  }

  // Formulaire de message
  const messageForm = document.getElementById("message-form")
  if (messageForm) {
    messageForm.addEventListener("submit", (e) => {
      e.preventDefault()
      sendMessage()
    })
  }

  // Fermeture des modals en cliquant à l'extérieur
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal-overlay")) {
      closeAllModals()
    }
  })

  // Recherche
  const searchInput = document.getElementById("search-input")
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      filterChats(e.target.value)
    })
  }

  // Responsive
  window.addEventListener("resize", updateResponsiveElements)

  // File input change
  const fileInput = document.getElementById("file-input")
  if (fileInput) {
    fileInput.addEventListener("change", (e) => {
      if (e.target.files.length > 0) {
        showNotification(`Fichier sélectionné: ${e.target.files[0].name}`, "success")
      }
    })
  }
}

// Gestion des onglets
function switchTab(tabName) {
  // Mettre à jour les onglets actifs
  document.querySelectorAll(".nav-tab").forEach((tab) => {
    tab.classList.remove("active")
  })
  document.querySelector(`[data-tab="${tabName}"]`).classList.add("active")

  // Afficher le bon panneau
  document.querySelectorAll("#chats-panel, #contacts-panel, #groups-panel").forEach((panel) => {
    panel.classList.add("hidden")
  })

  const targetPanel = document.getElementById(`${tabName}-panel`)
  if (targetPanel) {
    targetPanel.classList.remove("hidden")
  }
}

// Gestion des chats
function openChat(chatId) {
  currentChat = chatId
  const [type, id] = chatId.split(":")
  currentChatType = type
  currentChatId = id

  if (type === "contact") {
    currentContactId = id
  } else if (type === "group") {
    currentGroupId = id
  }

  // Mettre à jour l'état actif
  document.querySelectorAll(".chat-item").forEach((item) => {
    item.classList.remove("active")
  })
  event.currentTarget.classList.add("active")

  // Afficher le container de chat
  document.getElementById("welcome-screen").classList.add("hidden")
  document.getElementById("chat-container").classList.remove("hidden")

  // Charger les informations du chat
  loadChatInfo(type, id)

  // Charger les messages du chat
  loadChatMessages(type, id)

  // Marquer les messages comme lus
  markMessagesAsRead(chatId)

  // Sur mobile, basculer vers la vue chat
  updateResponsiveElements()
}

function loadChatInfo(type, id) {
  const chatName = document.getElementById("chat-name")
  const chatStatus = document.getElementById("chat-status")
  const chatAvatar = document.getElementById("chat-avatar")
  const messageRecipient = document.getElementById("message-recipient")
  const messageRecipientType = document.getElementById("message-recipient-type")

  if (type === "contact") {
    // Trouver le contact dans les données
    const contact = contactsData.find((c) => c.id == id)
    if (contact) {
      chatName.textContent = contact.name
      chatStatus.textContent = "En ligne"
      messageRecipient.value = contact.phone
      messageRecipientType.value = "contact"

      // Trouver l'utilisateur pour la photo
      const user = usersData.find((u) => u.telephone === contact.phone)
      if (user && user.profile_photo && user.profile_photo !== "default.jpg") {
        chatAvatar.src = `../uploads/${user.profile_photo}`
      } else {
        chatAvatar.src = `/placeholder.svg?height=40&width=40&text=${contact.name.charAt(0).toUpperCase()}`
      }
    }
  } else if (type === "group") {
    // Trouver le groupe dans les données
    const group = groupsData.find((g) => g.id == id)
    if (group) {
      chatName.textContent = group.name
      chatStatus.textContent = "Groupe"
      messageRecipient.value = id
      messageRecipientType.value = "groupe"

      if (group.photo && group.photo !== "default.jpg") {
        chatAvatar.src = `../uploads/${group.photo}`
      } else {
        chatAvatar.src = `/placeholder.svg?height=40&width=40&text=${group.name.charAt(0).toUpperCase()}`
      }
    }
  }
}

function loadChatMessages(type, id) {
  const messagesArea = document.getElementById("messages-area")
  messagesArea.innerHTML = ""

  let chatMessages = []

  if (type === "contact") {
    // Trouver le contact pour récupérer son téléphone
    const contact = contactsData.find((c) => c.id == id)
    if (contact) {
      const contactUser = usersData.find((u) => u.telephone === contact.phone)
      if (contactUser) {
        // Messages entre l'utilisateur courant et ce contact
        chatMessages = messagesData.filter(
          (msg) =>
            (msg.sender_id == currentUserId && msg.recipient === contact.phone) ||
            (msg.sender_id == contactUser.id && msg.recipient === currentUserPhone),
        )
      }
    }
  } else if (type === "group") {
    // Messages du groupe
    chatMessages = messagesData.filter((msg) => msg.recipient_group == id)
  }

  // Trier par timestamp
  chatMessages.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp))

  // Afficher les messages
  chatMessages.forEach((message) => {
    displayMessage(message)
  })

  scrollToBottom()
}

function displayMessage(message) {
  const messagesArea = document.getElementById("messages-area")
  const messageDiv = document.createElement("div")
  const isSent = message.sender_id == currentUserId

  messageDiv.className = `message ${isSent ? "sent" : "received"}`

  let senderInfo = ""
  if (!isSent && currentChatType === "group") {
    const sender = usersData.find((u) => u.id == message.sender_id)
    if (sender) {
      senderInfo = `<div class="message-sender">${sender.username} ${sender.nom}</div>`
    }
  }

  let fileContent = ""
  if (message.file) {
    const fileExtension = message.file.split(".").pop().toLowerCase()
    const isImage = ["jpg", "jpeg", "png", "gif", "webp"].includes(fileExtension)
    const isVideo = ["mp4", "avi", "mov", "wmv", "flv", "webm"].includes(fileExtension)

    if (isImage) {
      fileContent = `
        <div class="message-file-preview">
          <img src="../uploads/${message.file}" alt="Image" onclick="openImageModal('../uploads/${message.file}')">
        </div>
      `
    } else if (isVideo) {
      fileContent = `
        <div class="message-file-preview">
          <video controls>
            <source src="../uploads/${message.file}" type="video/${fileExtension}">
            Votre navigateur ne supporte pas la lecture de vidéos.
          </video>
        </div>
      `
    } else {
      fileContent = `
        <div class="message-file-preview">
          <a href="../uploads/${message.file}" download class="file-download-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16.5,6V17.5A4,4 0 0,1 12.5,21.5A4,4 0 0,1 8.5,17.5V5A2.5,2.5 0 0,1 11,2.5A2.5,2.5 0 0,1 13.5,5V15.5A1,1 0 0,1 12.5,16.5A1,1 0 0,1 11.5,15.5V6H10V15.5A2.5,2.5 0 0,0 12.5,18A2.5,2.5 0 0,0 15,15.5V5A4,4 0 0,0 11,1A4,4 0 0,0 7,5V17.5A5.5,5.5 0 0,0 12.5,23A5.5,5.5 0 0,0 18,17.5V6H16.5Z"/>
            </svg>
            ${message.file}
          </a>
        </div>
      `
    }
  }

  let statusIcon = ""
  if (isSent) {
    statusIcon = `
      <svg class="message-status" width="16" height="12" viewBox="0 0 16 12" fill="currentColor">
        <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l3.61 3.463c.143.14.361.125.484-.033L10.91 3.879a.366.366 0 0 0-.064-.512z"/>
      </svg>
    `
  }

  const messageTime = new Date(message.timestamp).toLocaleTimeString("fr-FR", {
    hour: "2-digit",
    minute: "2-digit",
  })

  messageDiv.innerHTML = `
    <div class="message-content">
      ${senderInfo}
      ${message.content ? `<p>${message.content}</p>` : ""}
      ${fileContent}
      <span class="message-time">${messageTime}${statusIcon}</span>
    </div>
  `

  messagesArea.appendChild(messageDiv)
}

function sendMessage() {
  const messageForm = document.getElementById("message-form")
  const messageInput = document.getElementById("message-input")
  const fileInput = document.getElementById("file-input")
  const message = messageInput.value.trim()

  if (!message && !fileInput.files.length) return
  if (!currentChat) return

  // Ajouter le message à l'interface immédiatement
  if (message) {
    const currentTime = new Date().toLocaleTimeString("fr-FR", {
      hour: "2-digit",
      minute: "2-digit",
    })

    displayMessage({
      sender_id: currentUserId,
      content: message,
      timestamp: new Date().toISOString(),
      file: null,
    })

    // Vider l'input
    messageInput.value = ""
    adjustTextareaHeight(messageInput)
    scrollToBottom()
  }

  // Soumettre le formulaire
  messageForm.submit()
}

function markMessagesAsRead(chatId) {
  // Supprimer les badges de messages non lus
  const chatItem = document.querySelector(`[onclick="openChat('${chatId}')"]`)
  if (chatItem) {
    const unreadBadge = chatItem.querySelector(".unread-badge")
    if (unreadBadge) {
      unreadBadge.remove()
    }
  }
}

// Filtrage des chats
function filterChats(searchTerm) {
  const chatItems = document.querySelectorAll("#chats-panel .chat-item")
  const searchLower = searchTerm.toLowerCase()

  chatItems.forEach((item) => {
    const chatName = item.querySelector(".chat-name").textContent.toLowerCase()
    const lastMessage = item.querySelector(".last-message").textContent.toLowerCase()

    if (chatName.includes(searchLower) || lastMessage.includes(searchLower)) {
      item.style.display = "flex"
    } else {
      item.style.display = "none"
    }
  })
}

// Utilitaires
function scrollToBottom() {
  const messagesArea = document.getElementById("messages-area")
  if (messagesArea) {
    messagesArea.scrollTop = messagesArea.scrollHeight
  }
}

function adjustTextareaHeight(textarea) {
  textarea.style.height = "auto"
  textarea.style.height = Math.min(textarea.scrollHeight, 100) + "px"
}

// Gestion des modals
function toggleSettings() {
  document.getElementById("modal-overlay").classList.remove("hidden")
}

function closeModal() {
  document.getElementById("modal-overlay").classList.add("hidden")
}

function closeAllModals() {
  document.querySelectorAll(".modal-overlay").forEach((modal) => {
    modal.classList.add("hidden")
  })
}

// Modal Ajouter Contact
function showAddContactModal() {
  document.getElementById("add-contact-modal").classList.remove("hidden")
}

function closeAddContactModal() {
  document.getElementById("add-contact-modal").classList.add("hidden")
}

// Modal Créer Groupe
function showCreateGroupModal() {
  document.getElementById("create-group-modal").classList.remove("hidden")
}

function closeCreateGroupModal() {
  document.getElementById("create-group-modal").classList.add("hidden")
}

// Modal Menu Chat
function showChatMenu() {
  const modal = document.getElementById("chat-menu-modal")
  const title = document.getElementById("chat-menu-title")
  const contactActions = document.getElementById("contact-menu-actions")
  const groupActions = document.getElementById("group-menu-actions")

  if (currentChatType === "contact") {
    title.textContent = "Menu du contact"
    contactActions.classList.remove("hidden")
    groupActions.classList.add("hidden")
  } else if (currentChatType === "group") {
    title.textContent = "Menu du groupe"
    contactActions.classList.add("hidden")
    groupActions.classList.remove("hidden")
  }

  modal.classList.remove("hidden")
}

function closeChatMenuModal() {
  document.getElementById("chat-menu-modal").classList.add("hidden")
}

// Modal Informations Contact
function showContactInfo() {
  closeChatMenuModal()

  const contact = contactsData.find((c) => c.id == currentContactId)
  if (contact) {
    document.getElementById("contact-info-name").value = contact.name
    document.getElementById("contact-info-phone").value = contact.phone

    const user = usersData.find((u) => u.telephone === contact.phone)
    if (user && user.profile_photo && user.profile_photo !== "default.jpg") {
      document.getElementById("contact-info-photo").src = `../uploads/${user.profile_photo}`
    } else {
      document.getElementById("contact-info-photo").src =
        `/placeholder.svg?height=80&width=80&text=${contact.name.charAt(0).toUpperCase()}`
    }
  }

  document.getElementById("contact-info-modal").classList.remove("hidden")
}

function closeContactInfoModal() {
  document.getElementById("contact-info-modal").classList.add("hidden")
}

function editContact() {
  closeContactInfoModal()

  const name = document.getElementById("contact-info-name").value
  const phone = document.getElementById("contact-info-phone").value

  document.getElementById("edit-contact-id").value = currentContactId
  document.getElementById("edit_contact_name").value = name
  document.getElementById("edit_contact_telephone").value = phone

  document.getElementById("edit-contact-modal").classList.remove("hidden")
}

function closeEditContactModal() {
  document.getElementById("edit-contact-modal").classList.add("hidden")
}

// Modal Informations Groupe
function showGroupInfo() {
  closeChatMenuModal()

  const group = groupsData.find((g) => g.id == currentGroupId)
  if (group) {
    document.getElementById("group-info-name").value = group.name
    document.getElementById("group-info-description").value = group.description || "Aucune description"

    if (group.photo && group.photo !== "default.jpg") {
      document.getElementById("group-info-photo").src = `../uploads/${group.photo}`
    } else {
      document.getElementById("group-info-photo").src =
        `/placeholder.svg?height=80&width=80&text=${group.name.charAt(0).toUpperCase()}`
    }

    // Charger la liste des membres
    loadGroupMembers(group.id)
  }

  document.getElementById("group-info-modal").classList.remove("hidden")
}

function loadGroupMembers(groupId) {
  const membersList = document.getElementById("group-members-list")
  membersList.innerHTML = ""

  // Simuler la liste des membres (à remplacer par les vraies données)
  const members = [
    { name: "Marie Diallo", isAdmin: true },
    { name: "Jean Dupont", isAdmin: false },
    { name: "Sophie Martin", isAdmin: false },
  ]

  members.forEach((member) => {
    const memberDiv = document.createElement("div")
    memberDiv.className = "member-checkbox"
    memberDiv.innerHTML = `<span>👤 ${member.name}${member.isAdmin ? " (Admin)" : ""}</span>`
    membersList.appendChild(memberDiv)
  })
}

function closeGroupInfoModal() {
  document.getElementById("group-info-modal").classList.add("hidden")
}

// Modal Gérer Groupe
function showManageGroupModal() {
  closeChatMenuModal()
  document.getElementById("manage-group-modal").classList.remove("hidden")
}

function closeManageGroupModal() {
  document.getElementById("manage-group-modal").classList.add("hidden")
}

function showEditGroupModal() {
  closeManageGroupModal()

  const group = groupsData.find((g) => g.id == currentGroupId)
  if (group) {
    document.getElementById("edit-group-id").value = currentGroupId
    document.getElementById("edit_group_name").value = group.name
    document.getElementById("edit_group_description").value = group.description || ""
  }

  document.getElementById("edit-group-modal").classList.remove("hidden")
}

function closeEditGroupModal() {
  document.getElementById("edit-group-modal").classList.add("hidden")
}

// Modal Confirmation
function showConfirmationModal(title, message, callback) {
  document.getElementById("confirmation-title").textContent = title
  document.getElementById("confirmation-message").textContent = message
  confirmationCallback = callback
  document.getElementById("confirmation-modal").classList.remove("hidden")
}

function closeConfirmationModal() {
  document.getElementById("confirmation-modal").classList.add("hidden")
  confirmationCallback = null
}

function confirmAction() {
  if (confirmationCallback) {
    confirmationCallback()
  }
  closeConfirmationModal()
}

// Actions des contacts
function deleteContact() {
  closeChatMenuModal()
  showConfirmationModal(
    "Supprimer le contact",
    "Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.",
    () => {
      document.getElementById("contactIdToDelete").value = currentContactId
      document.getElementById("deleteContactForm").submit()
    },
  )
}

// Actions des groupes
function leaveGroup() {
  closeChatMenuModal()
  showConfirmationModal("Quitter le groupe", "Êtes-vous sûr de vouloir quitter ce groupe ?", () => {
    document.getElementById("groupIdToLeave").value = currentGroupId
    document.getElementById("leaveGroupForm").submit()
  })
}

function deleteGroup() {
  closeManageGroupModal()
  showConfirmationModal(
    "Supprimer le groupe",
    "Êtes-vous sûr de vouloir supprimer ce groupe ? Cette action est irréversible et supprimera tous les messages.",
    () => {
      document.getElementById("groupIdToDelete").value = currentGroupId
      document.getElementById("deleteGroupForm").submit()
    },
  )
}

function showAddMemberModal() {
  showNotification("Fonctionnalité d'ajout de membres à venir !")
}

function showRemoveMemberModal() {
  showNotification("Fonctionnalité de retrait de membres à venir !")
}

// Responsive
function updateResponsiveElements() {
  const backBtn = document.getElementById("back-btn")
  const appContainer = document.querySelector(".app-container")

  if (window.innerWidth <= 768) {
    if (backBtn) backBtn.style.display = "flex"
    if (currentChat) {
      appContainer.classList.add("chat-open")
    }
  } else {
    if (backBtn) backBtn.style.display = "none"
    appContainer.classList.remove("chat-open")
  }
}

// Retour à la liste des chats sur mobile
function backToChats() {
  if (window.innerWidth <= 768) {
    document.querySelector(".app-container").classList.remove("chat-open")
    document.getElementById("welcome-screen").classList.remove("hidden")
    document.getElementById("chat-container").classList.add("hidden")
    currentChat = null
    currentChatType = null
    currentChatId = null
    currentContactId = null
    currentGroupId = null
  }
}

// Autres fonctions
function showAttachmentMenu() {
  const fileInput = document.getElementById("file-input")
  fileInput.click()
}

function showEmojiPicker() {
  showNotification("Sélecteur d'emoji à venir !")
}

function searchInChat() {
  showNotification("Recherche dans le chat à venir !")
}

function logout() {
  showConfirmationModal("Déconnexion", "Êtes-vous sûr de vouloir vous déconnecter ?", () => {
    window.location.href = "../auth/logout.php"
  })
}

// Système de notifications
function showNotification(message, type = "info") {
  const notification = document.getElementById("notification")
  const notificationText = document.getElementById("notification-text")

  notificationText.textContent = message
  notification.className = `notification notification-${type}`
  notification.classList.remove("hidden")

  // Auto-fermeture après 3 secondes
  setTimeout(() => {
    closeNotification()
  }, 3000)
}

function closeNotification() {
  document.getElementById("notification").classList.add("hidden")
}

// Modal pour les images
function openImageModal(imageSrc) {
  const modal = document.createElement("div")
  modal.className = "modal-overlay"
  modal.innerHTML = `
    <div class="modal-content" style="background: transparent; padding: 0; max-width: 90vw; max-height: 90vh;">
      <img src="${imageSrc}" alt="Image" style="max-width: 100%; max-height: 90vh; object-fit: contain;">
      <button class="modal-close" onclick="this.parentElement.parentElement.remove()" style="position: absolute; top: 20px; right: 20px; background: rgba(0,0,0,0.5); color: white; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; font-size: 20px;">×</button>
    </div>
  `
  document.body.appendChild(modal)

  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.remove()
    }
  })
}
