<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $telephone = $_POST['telephone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!preg_match('/^(77|70|78|76)[0-9]{7}$/', $telephone)) {
        $erreur = "Le numéro de téléphone doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres.";
    } else {
        $utilisateurs = simplexml_load_file('../xmls/users.xml');
        $utilisateur_existe = $utilisateurs->xpath("//user[telephone='$telephone']");

        if ($utilisateur_existe) {
            $erreur = "Ce numéro est déjà utilisé.";
        } else {
            $nouvel_utilisateur = $utilisateurs->addChild('user');
            $nouvel_utilisateur->addChild('user_id', uniqid());
            $nouvel_utilisateur->addChild('username', $username);
            $nouvel_utilisateur->addChild('telephone', $telephone);
            $nouvel_utilisateur->addChild('password', $password);

            if (!empty($_FILES['profile_photo']['name'])) {
                $nom_fichier = uniqid() . '_' . basename($_FILES['profile_photo']['name']);
                move_uploaded_file($_FILES['profile_photo']['tmp_name'], '../uploads/' . $nom_fichier);
                $nouvel_utilisateur->addChild('profile_photo', $nom_fichier);
            } else {
                $nouvel_utilisateur->addChild('profile_photo', '../assets/img/JOTAAY.png');
            }

            $utilisateurs->asXML('../xmls/users.xml');
            header('Location: login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - Jotaay</title>
    <link rel="stylesheet" href="../assets/css/connexion.css" />
</head>
<body>
    <div class="login-container">
        <!-- Section logo -->
        <div class="login-logo-section">
            <img src="../assets/img/JOTAAY.png" alt="Logo Jotaay" class="login-logo" />
        </div>

        <!-- Section formulaire -->
        <div class="login-form-section">
            <h1 class="login-title">Créer un compte</h1>
            <p class="login-subtitle">Rejoins-nous et commence à discuter !</p>

            <?php if (isset($erreur)) : ?>
                <div class="login-error"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="login-form" novalidate>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur
                    <span>*</span>
                    </label>
                    <input 
                    type="text" 
                    id="username" 
                    name="username" required 
                    placeholder="ex: maremegueye"
                    />
                </div>

                <div class="form-group">
                    <label for="telephone">Numéro de téléphone
                        <span>*</span>
                    </label>
                    <input
                        type="text"
                        id="telephone"
                        name="telephone"
                        required
                        pattern="(77|70|78|76)[0-9]{7}"
                        title="Numéro doit commencer par 77, 70, 78 ou 76 suivi de 7 chiffres"
                        placeholder="ex: 7........"
                    />
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe
                        <span>*</span>
                    </label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="form-group">
                    <label for="profile_photo">Photo de profil</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" />
                </div>

                <button type="submit" class="login-btn">S'inscrire</button>
            </form>

            <div class="login-link">
                Déjà un compte ? <a href="login.php">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>