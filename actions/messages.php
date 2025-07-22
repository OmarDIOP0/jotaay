<?php
session_start();

$action = $_POST['action'];

switch($action){
     case 'envoyer_message':
            if (isset($_POST['destinataire'], $_POST['message'], $_POST['type_destinataire'])) {
                $message = $messages->addChild('message');
                $message->addChild('id', uniqid());
                $message->addChild('sender_id', $id_utilisateur);
                if ($_POST['type_destinataire'] === 'contact') {
                    $message->addChild('recipient', htmlspecialchars($_POST['destinataire']));
                } elseif ($_POST['type_destinataire'] === 'groupe') {
                    $message->addChild('recipient_group', htmlspecialchars($_POST['destinataire']));
                }
                $message->addChild('content', htmlspecialchars($_POST['message']));
                if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    $nom_fichier = uniqid() . '_' . basename($_FILES['fichier']['name']);
                    $fichier_cible = $upload_dir . $nom_fichier;
                    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $fichier_cible)) {
                        $message->addChild('file', $nom_fichier);
                    }
                }
                $message->addChild('read_by', '');
                $message->addAttribute('timestamp', date('Y-m-d\TH:i:s'));
                $messages->asXML('xmls/messages.xml');
            }
            header('Location: views/view.php?conversation=' . ($_POST['type_destinataire'] === 'groupe' ? 'groupe:' : 'contact:') . urlencode($_POST['destinataire']));
            exit;
         case 'send_message':
            if (isset($_POST['message'], $_POST['recipient'], $_POST['recipient_type'])) {
                $message_content = htmlspecialchars($_POST['message']);
                $recipient = htmlspecialchars($_POST['recipient']);
                $recipient_type = htmlspecialchars($_POST['recipient_type']);
                
                // Créer un nouveau message
                $message = $messages->addChild('message');
                $message->addChild('id', uniqid());
                $message->addChild('sender_id', $id_utilisateur);
                $message->addChild('content', $message_content);
                $message->addChild('timestamp', date('Y-m-d\TH:i:s'));
                $message->addChild('read_by', '');
                
                if ($recipient_type === 'contact') {
                    // Message vers un contact (utilise le numéro de téléphone)
                    $message->addChild('recipient', $recipient);
                } elseif ($recipient_type === 'groupe') {
                    // Message vers un groupe
                    $message->addChild('recipient_group', $recipient);
                }
                
                // Gestion des fichiers
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    $nom_fichier = uniqid() . '_' . basename($_FILES['file']['name']);
                    $fichier_cible = $upload_dir . $nom_fichier;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $fichier_cible)) {
                        $message->addChild('file', $nom_fichier);
                    }
                }
                
                // Sauvegarder le message
                $resultat = $messages->asXML('xmls/messages.xml');
                
                if ($resultat) {
                    // Rediriger vers la conversation
                    if ($recipient_type === 'contact') {
                        // Pour les contacts, récupérer l'ID du contact à partir du numéro de téléphone
                        $contact_info = $contacts->xpath("//contact[contact_telephone='$recipient' and user_id='$id_utilisateur']")[0];
                        if ($contact_info) {
                            $redirect_url = 'views/view.php?conversation=contact:' . urlencode($contact_info->id) . '&tab=discussions';
                        } else {
                            $redirect_url = 'views/view.php?tab=discussions';
                        }
                    } else {
                        // Pour les groupes, utiliser l'ID du groupe
                        $redirect_url = 'views/view.php?conversation=groupe:' . urlencode($recipient) . '&tab=discussions';
                    }
                    header('Location: ' . $redirect_url);
                } else {
                    header('Location: views/view.php?error=message_send_failed');
                }
            } else {
                header('Location: views/view.php?error=missing_message_data');
            }
            exit;

}

?>