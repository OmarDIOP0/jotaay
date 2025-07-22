<?php
// Démarrer la session si elle n'est pas déjà active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telephone = $_POST['telephone'];
    $mot_de_passe = $_POST['password'];

    $utilisateurs = simplexml_load_file('../xmls/users.xml');
    if ($utilisateurs === false) {
        die("Erreur : impossible de charger le fichier XML des utilisateurs.");
    }

    foreach ($utilisateurs->user as $utilisateur) {
        if (
            $utilisateur->telephone == $telephone &&
            password_verify($mot_de_passe, $utilisateur->password)
        ) {
            $_SESSION['user'] = (string) $utilisateur->id;
            header('Location: ../views/view.php');
            exit;
        }
    }

    $erreur = "Numéro ou mot de passe incorrect";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - Jotaay</title>
    <link rel="stylesheet" href="../assets/css/connexion.css" />
</head>
<body>
    <div class="login-container">
        <div class="login-logo-section">
            <img src="../assets/img/JOTAAY.png" alt="Logo Jotaay" class="login-logo" />
        </div>

        <div class="login-form-section">
            <h1 class="login-title">Bienvenue dans JOTAAY 👋</h1>
            <p class="login-subtitle">Kaay waxtan ak sey xaarit</p>

            <?php if (isset($erreur)) : ?>
                <div class="login-error"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <form method="post" class="login-form" novalidate>
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
                        placeholder="exemple: 7........"
                    />
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe
                        <span>*</span>
                    </label>
                    <input 
                    type="password" id="password" name="password" required 
                />
                </div>

                <button type="submit" class="login-btn">Se connecter</button>
            </form>

            <div class="login-link">
                Pas de compte ? <a href="register.php">S'inscrire ici</a>
            </div>
        </div>
    </div>
</body>
</html>