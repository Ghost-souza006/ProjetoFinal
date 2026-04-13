<?php
session_start();

// Quando acessa index como usuário logado através do botão "Início"
// o parâmetro ?view=portal permite ver a página inicial em vez de dashboard.
if (isset($_SESSION['usuario_id']) && (!isset($_GET['view']) || $_GET['view'] !== 'portal')) {
    header('Location: admin/dashboard.php');
    exit;
}

$isLogado = isset($_SESSION['usuario_id']);
$nomeUsuario = $isLogado ? htmlspecialchars($_SESSION['usuario_nome']) : '';
$inicialUsuario = $isLogado ? mb_strtoupper(mb_substr($nomeUsuario, 0, 1, 'UTF-8'), 'UTF-8') : '';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>EcoFinanças - Portal de Notícias Financeiras</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <!-- Header -->
    <header class="hero-header">
        <nav class="navbar">
            <div class="navbar-brand">
                <img src="imagens/logo-ecofinancas.png" alt="Logo" style="height: 45px; margin-right: 0.5rem;">
                <span>EcoFinanças</span>
            </div>
            <div class="navbar-info">
                <?php if ($isLogado): ?>
                    <a href="noticias.php" class="btn btn-ghost btn-sm"><i class="fas fa-newspaper"></i> Notícias</a>
                    <a href="admin/dashboard.php" class="btn btn-ghost btn-sm profile-link"><span
                            class="profile-badge"><?= $inicialUsuario ?></span> <?= $nomeUsuario ?></a>
                    <a href="logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-in-alt"></i> Entrar</a>
                    <a href="cadastro.php" class="btn btn-ghost btn-sm"><i class="fas fa-user-plus"></i> Cadastrar</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-content">
            <div class="hero-text">
                <h1>Seu Portal de Notícias Financeiras</h1>
                <p>Informações atualizadas sobre economia, investimentos e finanças pessoais. Gerencie e compartilhe
                    conhecimento financeiro com nossa comunidade.</p>
                <div class="hero-actions">
                    <a href="noticias.php" class="btn btn-primary btn-lg"><i class="fas fa-newspaper"></i> Ver Notícias</a>
                    <?php if (!$isLogado): ?>
                        <a href="cadastro.php" class="btn btn-outline btn-lg"><i class="fas fa-user-plus"></i> Cadastre-se Grátis</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2>Por que escolher o EcoFinanças?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>Notícias Atualizadas</h3>
                    <p>Conteúdo financeiro fresco e relevante, atualizado diariamente por nossa equipe especializada.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Comunidade Ativa</h3>
                    <p>Conecte-se com outros entusiastas das finanças e compartilhe experiências e conhecimentos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Análises Detalhadas</h3>
                    <p>Relatórios completos sobre mercado financeiro, investimentos e tendências econômicas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Conteúdo Seguro</h3>
                    <p>Informações verificadas e confiáveis para suas decisões financeiras mais importantes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" style="background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%); padding: 4rem 0;">
        <div class="container" style="padding: 0 20px;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 style="margin-bottom: 1rem; font-size: 2.25rem;"><i class="fas fa-info-circle"></i> Sobre o EcoFinanças</h2>
                <p style="max-width: 800px; margin: 0 auto; font-size: 1.1rem; color: var(--text-muted); line-height: 1.7;">
                    O EcoFinanças é uma plataforma desenvolvida como projeto final de curso técnico, com o objetivo de
                    demonstrar habilidades em desenvolvimento web, banco de dados e programação orientada a objetos.
                </p>
            </div>
            <div class="features-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>Tecnologias Modernas</h3>
                    <p>Desenvolvido com PHP, MySQL, HTML5, CSS3 e JavaScript. Interface responsiva e design profissional com Soft UI.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Banco de Dados Relacional</h3>
                    <p>Arquitetura robusta com MySQL e PDO, garantindo segurança contra SQL Injection e alta performance.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Segurança Avançada</h3>
                    <p>Autenticação segura com hash de senhas, controle de acesso por tipo de usuário e sessões protegidas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3>Design Profissional</h3>
                    <p>Interface moderna com Glassmorphism, tons de Esmeralda e tipografia Inter para uma experiência premium.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>100% Responsivo</h3>
                    <p>Adaptável a qualquer dispositivo: desktop, tablet ou smartphone. Acesse de onde estiver.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Projeto Educacional</h3>
                    <p>Desenvolvido durante curso técnico como portfólio profissional. Demonstra competências reais em desenvolvimento web.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section" style="padding: 4rem 0;">
        <div class="container" style="padding: 0 20px;">
            <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.25rem;"><i class="fas fa-th-large"></i> Categorias de Notícias</h2>
            <div class="features-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
                <div class="feature-card" style="text-align: center;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3>Investimentos</h3>
                    <p>Ações, renda fixa, criptomoedas e estratégias para multiplicar seu patrimônio.</p>
                </div>
                <div class="feature-card" style="text-align: center;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h3>Finanças Pessoais</h3>
                    <p>Dicas de economia, orçamento doméstico e planejamento financeiro inteligente.</p>
                </div>
                <div class="feature-card" style="text-align: center;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3>Economia Global</h3>
                    <p>Análises de mercado internacional, câmbio e impacto nas finanças brasileiras.</p>
                </div>
                <div class="feature-card" style="text-align: center;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3>Mercado Imobiliário</h3>
                    <p>Tendências, financiamento e oportunidades no setor imobiliário.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Notícias Publicadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Usuários Ativos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Atualização Contínua</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container" style="padding: 0 20px;">
            <h2>Pronto para começar?</h2>
            <p>Junte-se à nossa comunidade e tenha acesso a conteúdo financeiro exclusivo.</p>
            <div class="cta-actions">
                <?php if (!$isLogado): ?>
                    <a href="cadastro.php" class="btn btn-primary btn-lg"><i class="fas fa-user-plus"></i> Criar Conta Gratuita</a>
                    <a href="login.php" class="btn btn-outline btn-lg"><i class="fas fa-sign-in-alt"></i> Já tenho conta</a>
                <?php else: ?>
                    <a href="admin/dashboard.php" class="btn btn-primary btn-lg"><i class="fas fa-tachometer-alt"></i> Acessar Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container" style="padding: 0 20px;">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="imagens/logo-ecofinancas.png" alt="Logo" style="height: 35px; margin-right: 0.5rem;">
                    <span>EcoFinanças</span>
                </div>
                <div class="footer-links">
                    <div>
                        <h4>Links Úteis</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="noticias.php" style="color: var(--text-muted); text-decoration: none;"><i class="fas fa-newspaper"></i> Notícias</a></li>
                            <li><a href="cadastro.php" style="color: var(--text-muted); text-decoration: none;"><i class="fas fa-user-plus"></i> Cadastro</a></li>
                            <li><a href="login.php" style="color: var(--text-muted); text-decoration: none;"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Tecnologias</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><i class="fab fa-php"></i> PHP 8.0+</li>
                            <li><i class="fas fa-database"></i> MySQL</li>
                            <li><i class="fab fa-html5"></i> HTML5 & CSS3</li>
                            <li><i class="fab fa-js"></i> JavaScript</li>
                        </ul>
                    </div>
                    <div>
                        <h4>Recursos</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li><i class="fas fa-shield-alt"></i> Segurança PDO</li>
                            <li><i class="fas fa-mobile-alt"></i> Design Responsivo</li>
                            <li><i class="fas fa-paint-brush"></i> Soft UI Design</li>
                            <li><i class="fas fa-graduation-cap"></i> Projeto Educacional</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 EcoFinanças. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="landing.js" defer></script>
</body>

</html>