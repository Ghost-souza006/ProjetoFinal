<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar se o usuário é reporter ou admin
if ($_SESSION['usuario_tipo'] !== 'reporter' && $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

require_once 'conexao.php';

$id_noticia = $_GET['id'] ?? 0;
$mensagem = '';
$tipo_mensagem = '';

// Configurações de upload
$upload_dir = 'imagens/noticias/';
$tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$tamanho_maximo = 5 * 1024 * 1024; // 5MB

// Criar diretório se não existir
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Buscar a notícia
if ($_SESSION['usuario_tipo'] === 'admin') {
    // Admin pode editar qualquer notícia
    $stmt = $pdo->prepare('SELECT n.*, u.nome as autor_nome FROM noticias n INNER JOIN usuarios u ON n.autor = u.id WHERE n.id = ?');
    $stmt->execute([$id_noticia]);
} else {
    // Reporter só pode editar suas próprias notícias
    $stmt = $pdo->prepare('SELECT * FROM noticias WHERE id = ? AND autor = ?');
    $stmt->execute([$id_noticia, $_SESSION['usuario_id']]);
}
$noticia = $stmt->fetch();

if (!$noticia) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['noticia'] ?? '');
    $imagem = $noticia['imagem']; // Manter imagem atual por padrão

    // Processar upload da imagem (se enviado novo arquivo)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem'];
        
        // Verificar tipo do arquivo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipo_mime = finfo_file($finfo, $arquivo['tmp_name']);
        finfo_close($finfo);

        if (!in_array($tipo_mime, $tipos_permitidos)) {
            $mensagem = 'Tipo de arquivo não permitido. Use apenas JPG, PNG, GIF ou WEBP.';
            $tipo_mensagem = 'erro';
        } elseif ($arquivo['size'] > $tamanho_maximo) {
            $mensagem = 'Arquivo muito grande. Tamanho máximo: 5MB.';
            $tipo_mensagem = 'erro';
        } else {
            // Gerar nome único para o arquivo
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nome_arquivo = uniqid('noticia_') . '.' . $extensao;
            $caminho_destino = $upload_dir . $nome_arquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminho_destino)) {
                // Remover imagem antiga se existir
                if (!empty($noticia['imagem']) && file_exists($noticia['imagem'])) {
                    unlink($noticia['imagem']);
                }
                $imagem = 'imagens/noticias/' . $nome_arquivo;
            } else {
                $mensagem = 'Erro ao salvar a imagem.';
                $tipo_mensagem = 'erro';
            }
        }
    }

    if (empty($mensagem) && ($titulo === '' || $conteudo === '')) {
        $mensagem = 'Título e conteúdo são obrigatórios.';
        $tipo_mensagem = 'erro';
    }

    if (empty($mensagem)) {
        if ($_SESSION['usuario_tipo'] === 'admin') {
            $stmt = $pdo->prepare('UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ?');
            if ($stmt->execute([$titulo, $conteudo, $imagem, $id_noticia])) {
                $mensagem = 'Notícia atualizada com sucesso!';
                $tipo_mensagem = 'sucesso';
                header('refresh:2;url=dashboard.php');
                exit;
            }
        } else {
            $stmt = $pdo->prepare('UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ? AND autor = ?');
            if ($stmt->execute([$titulo, $conteudo, $imagem, $id_noticia, $_SESSION['usuario_id']])) {
                $mensagem = 'Notícia atualizada com sucesso!';
                $tipo_mensagem = 'sucesso';
                header('refresh:2;url=dashboard.php');
                exit;
            }
        }
        $mensagem = 'Erro ao atualizar notícia.';
        $tipo_mensagem = 'erro';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .upload-area {
            border: 2px dashed rgba(59, 130, 246, 0.4);
            border-radius: 0;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(59, 130, 246, 0.05);
            position: relative;
        }
        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(59, 130, 246, 0.1);
        }
        .upload-area.dragover {
            border-color: var(--primary);
            background: rgba(59, 130, 246, 0.15);
        }
        .upload-area i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        .upload-area p {
            color: var(--text-secondary);
            margin-bottom: 10px;
        }
        .upload-area .btn-upload {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: 0;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .upload-area .btn-upload:hover {
            background: var(--primary-dark);
        }
        .upload-area input[type="file"] {
            display: none;
        }
        .preview-imagem {
            max-width: 100%;
            max-height: 200px;
            margin-top: 15px;
            border-radius: 0;
            display: none;
        }
        .file-info {
            margin-top: 10px;
            padding: 10px;
            background: rgba(16, 185, 129, 0.1);
            border-left: 3px solid var(--success);
            color: var(--success);
            font-size: 0.9rem;
            display: none;
        }
        .imagem-atual {
            margin-top: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .imagem-atual img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 0;
        }
        .imagem-atual p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand"><img src="imagens/logo-ecofinancas.png" alt="Logo" style="height: 45px; margin-right: 0.5rem;"> EcoFinanças</div>
        <div class="navbar-info">
            <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
            <a href="logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> Editar Notícia</h2>
            </div>
            <div class="card-body">
                <?php if ($mensagem): ?>
                    <div class="alert alert-<?= $tipo_mensagem === 'sucesso' ? 'success' : 'error' ?>">
                        <i class="fas fa-<?= $tipo_mensagem === 'sucesso' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="noticia-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título *</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-image"></i> Imagem da Notícia</label>
                        
                        <?php if (!empty($noticia['imagem'])): ?>
                        <div class="imagem-atual">
                            <p><strong>Imagem atual:</strong></p>
                            <img src="<?= htmlspecialchars($noticia['imagem']) ?>" alt="Imagem atual">
                            <p><i class="fas fa-info-circle"></i> Envie uma nova imagem para substituir a atual.</p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Arraste uma imagem aqui ou</p>
                            <label for="imagem" class="btn-upload">
                                <i class="fas fa-folder-open"></i> Escolher Arquivo
                            </label>
                            <input type="file" id="imagem" name="imagem" accept="image/*">
                            <img id="previewImagem" class="preview-imagem" alt="Preview">
                            <div id="fileInfo" class="file-info"></div>
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Formatos aceitos: JPG, PNG, GIF, WEBP. Tamanho máximo: 5MB.
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <label for="noticia" class="form-label"><i class="fas fa-align-left"></i> Conteúdo *</label>
                        <textarea id="noticia" name="noticia" class="form-control auto-resize-textarea" rows="6" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Atualizar</button>
                        <a href="dashboard.php" class="btn btn-ghost"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview de imagem
        const inputImagem = document.getElementById('imagem');
        const previewImagem = document.getElementById('previewImagem');
        const fileInfo = document.getElementById('fileInfo');
        const uploadArea = document.getElementById('uploadArea');

        inputImagem.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tipo
                if (!file.type.startsWith('image/')) {
                    alert('Por favor, selecione apenas arquivos de imagem.');
                    inputImagem.value = '';
                    return;
                }
                
                // Validar tamanho (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Arquivo muito grande. Tamanho máximo: 5MB.');
                    inputImagem.value = '';
                    return;
                }

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImagem.src = e.target.result;
                    previewImagem.style.display = 'block';
                };
                reader.readAsDataURL(file);

                // Mostrar info do arquivo
                const tamanhoMB = (file.size / (1024 * 1024)).toFixed(2);
                fileInfo.textContent = `✓ ${file.name} (${tamanhoMB} MB)`;
                fileInfo.style.display = 'block';
            }
        });

        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                inputImagem.files = files;
                inputImagem.dispatchEvent(new Event('change'));
            }
        });

        // Auto-resize textarea
        const textarea = document.getElementById('noticia');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }
    </script>
</body>
</html>
