<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jotaay - Messagerie Sénégalaise</title>
    <link rel="stylesheet" href="assets/css/onboarding.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1282A2;
            --secondary: #034078;
            --light: #FEFCFB;
            --dark: #0A1128;
            --accent: #001F54;
        }
        
        .teranga-bg {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
    </style>
</head>
<body class="home-body teranga-bg">
    <!-- Header avec touche locale -->
    <header class="main-header">
        <div class="container">
            <div class="logo-wrapper">
                <div class="logo">
                    <svg class="logo-icon" width="36" height="36" viewBox="0 0 24 24">
                        <!-- Icône personnalisée combinant message et baobab -->
                        <path d="M21 12a9 9 0 01-9 9H5l-4 4V12a9 9 0 019-9h7a9 9 0 019 9z" fill="var(--light)"/>
                        <path d="M12 5L8 9h3v6h2V9h3l-4-4z" fill="var(--primary)"/>
                    </svg>
                    <span class="logo-text">Jotaay</span>
                </div>
                <div class="nav-lang">
                    <select class="lang-selector">
                        <option value="fr">Français</option>
                        <option value="wo">Wolof</option>
                    </select>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section avec visuel local -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span>🇸🇳 kay wahtane</span>
                </div>
                <h1 class="hero-title">
                    Restez connectés,<br>
                    <span class="highlight">Fo meuna nékk</span>
                </h1>
                <p class="hero-subtitle">
                    Jotaay réinvente la messagerie instantanée avec une touche locale.
                    Discutez simplement avec vos proches, où qu'ils soient.
                </p>
                
                <div class="hero-actions">
                    <a href="auth/register.php" class="cta-btn primary">
                        <span>S'inscrire gratuitement</span>
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </a>
                    <a href="auth/login.php" class="cta-btn secondary">
                        <span>J'ai déjà un compte</span>
                    </a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <strong>50K+</strong>
                        <span>Utilisateurs</span>
                    </div>
                    <div class="stat-item">
                        <strong>100%</strong>
                        <span>Sénégalais</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="phone-mockup">
                    <div class="screen">
                        <!-- Conversation typique avec emojis locaux -->
                        <div class="chat-bubble received">
                            <p>Nanga def ? 😊</p>
                        </div>
                        <div class="chat-bubble sent">
                            <p>Mangi fi rekk, jërëjëf ! 🌍</p>
                        </div>
                        <div class="chat-bubble received">
                            <p>Jamm akk salam! 🎉</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Valeurs avec illustrations locales -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Notre Approche</h2>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🚀</div>
                    <h3>Optimisé Réseau</h3>
                    <p>Fonctionne même avec 2G dans les zones reculées</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">👨‍👩‍👧‍👦</div>
                    <h3>Groupes Familiaux</h3>
                    <p>Créez des groupes pour toute la famille</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">💬</div>
                    <h3>Expressions Locales</h3>
                    <p>Emojis et stickers typiquement sénégalais</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">🛡️</div>
                    <h3>Confidentialité</h3>
                    <p>Vos données restent au Sénégal</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Ils utilisent Jotaay</h2>
            
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="user-avatar">AM</div>
                    <p class="testimonial-text">
                        "Avec Jotaay, je reste en contact avec ma famille à Tamba depuis Dakar sans souci !"
                    </p>
                    <div class="user-info">
                        <strong>Aminata, Dakar</strong>
                        <span>Commerçante</span>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="user-avatar">PD</div>
                    <p class="testimonial-text">
                        "Les groupes de quartier sur Jotaay ont changé notre façon de communiquer !"
                    </p>
                    <div class="user-info">
                        <strong>Pape, Thiès</strong>
                        <span>Étudiant</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="final-cta">
        <div class="container">
            <h2>Prêt à essayer Jotaay ?</h2>
            <p>Rejoignez la communauté dès maintenant</p>
            
            <div class="cta-buttons">
                <a href="auth/register.php" class="cta-btn primary">Créer un compte</a>
                <a href="auth/login.php" class="cta-btn secondary">Se connecter</a>
            </div>
            
            <!-- <div class="app-stores">
                <a href="#" class="store-btn">
                    <img src="assets/img/google-play-badge.png" alt="Disponible sur Google Play">
                </a>
                <a href="#" class="store-btn">
                    <img src="assets/img/app-store-badge.png" alt="Disponible sur l'App Store">
                </a>
            </div> -->
        </div>
    </section>

    <!-- Footer avec éléments culturels -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="logo">Jotaay</div>
                    <p>La messagerie qui vous ressemble</p>
                    <div class="social-links">
                        <a href="#">FB</a>
                        <a href="#">IG</a>
                        <a href="#">TW</a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">Fonctionnalités</a></li>
                        <li><a href="#">Télécharger</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Légal</h4>
                    <ul>
                        <li><a href="#">CGU</a></li>
                        <li><a href="#">Confidentialité</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Contact</h4>
                    <address>
                        Dakar, Sénégal<br>
                        contact@jotaay.sn<br>
                        +221 33 123 45 67
                    </address>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Jotaay - Fierté Sénégalaise</p>
                <div class="footer-lang">
                    <span>🌍 Français</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/onboarding.js"></script>
</body>
</html>