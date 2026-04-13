> Projeto final em PHP desenvolvido durante as aulas do curso técnico, abordando conceitos de desenvolvimento web, programação orientada a objetos e banco de dados relacional.

**📅 Última Atualização:** 13 de abril de 2026

## 📌 Sobre o Projeto

Este repositório contém um **Portal de Notícias Financeiras** completo em PHP, focando em conceitos essenciais do desenvolvimento web, como:

- Autenticação e autorização de usuários
- CRUD completo (Create, Read, Update, Delete)
- Manipulação de Banco de Dados MySQL com PDO
- Tratamento de exceções e segurança
- Design responsivo com Soft UI e Glassmorphism
- Sistema de sessões e controle de acesso
- Upload e gerenciamento de imagens

O projeto visa consolidar o aprendizado e pode ser utilizado como portfólio profissional.

---
## 🛠️ Tecnologias Utilizadas

- **PHP** (8.0+ recomendado)
- **MySQL** (MariaDB 10.4+)
- **PDO** para conexão com banco de dados
- **HTML5 & CSS3** (Vanilla)
- **JavaScript** (para interações no frontend)
- **Font Awesome** para ícones
- **Google Fonts** (Inter)
- XAMPP para ambiente de desenvolvimento local

---

## 🚀 Como Executar

### Pré-requisitos
- [XAMPP](https://www.apachefriends.org/) instalado com Apache e MySQL
- Navegador web moderno

### Passo a Passo

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git
   ```

2. **Copie o projeto para o diretório do XAMPP**:
   ```
   C:\xampp\htdocs\projetoFinal
   ```

3. **Importe o banco de dados**:
   - Abra o phpMyAdmin: `http://localhost/phpmyadmin`
   - Crie um novo banco chamado `portal_financas`
   - Importe o arquivo `dump.sql` ou execute o script `portal_financas.sql`

4. **Configure a conexão** (se necessário):
   - Edite o arquivo `conexao.php` com as credenciais do seu MySQL
   ```php
   $host = 'localhost';
   $dbname = 'portal_financas';
   $username = 'root';
   $password = '';
   ```

5. **Inicie os serviços do XAMPP**:
   - Ligue o Apache e MySQL no painel do XAMPP

6. **Acesse a aplicação**:
   - Abra o navegador e acesse: `http://localhost/projetoFinal`

7. **Login padrão** (Administrador):
   - **Email:** `admin@ecofinancas.com`
   - **Senha:** `1`

8. **Senha de Autorização** (para cadastrar novos admins):
   - **Senha:** `1`

---
## 📂 Estrutura do Repositório

```bash
📂 projetoFinal
├── 📁 admin/                      # Painel administrativo
│   ├── dashboard.php              # Painel principal
│   ├── nova_noticia.php           # Criar notícia
│   ├── editar_noticia.php         # Editar notícia
│   ├── excluir_noticia.php        # Excluir notícia
│   ├── gerenciar_noticias.php     # Gerenciar todas as notícias
│   ├── editar_usuario.php         # Editar usuário
│   ├── excluir_usuario.php        # Excluir usuário
│   └── gerenciar_usuarios.php     # Gerenciar todos os usuários
│
├── 📁 includes/                   # Arquivos de suporte
│   ├── conexao.php                # Conexão com banco de dados
│   ├── funcoes.php                # Funções auxiliares
│   └── verifica_login.php         # Verificação de autenticação
│
├── 📁 imagens/                    # Imagens das notícias
├── 📁 img/                        # Assets do projeto
├── 📁 config/                     # Arquivos de configuração
│
├── index.php                      # Página inicial (pública)
├── noticias.php                   # Listagem de notícias
├── noticia.php                    # Visualização individual
├── login.php                      # Página de login
├── cadastro.php                   # Cadastro de usuário
├── logout.php                     # Encerrar sessão
├── dashboard.php                  # Dashboard do usuário
├── conexao.php                    # Conexão PDO com MySQL
├── funcoes.php                    # Funções genéricas
├── config_seguranca.php           # Configurações de segurança
├── verifica_login.php             # Bloqueio de acesso
├── style.css                      # Estilização visual
├── landing.js                     # Scripts do frontend
├── dump.sql                       # Script do banco de dados
├── portal_financas.sql            # Script alternativo do BD
├── add_campos_reporter.sql        # Migração para repórteres
├── corrigir_banco.sql             # Correções do banco
├── README.md                      # Documentação
├── ADMIN.md                       # Guia do administrador
└── SEGURANCA.md                   # Políticas de segurança
```

---
## 📖 Exemplos de Código

### Conexão com Banco de Dados (conexao.php)

```php
<?php
$host = 'localhost';
$dbname = 'portal_financas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
```

### Estrutura do Banco de Dados

**Tabela de Usuários:**
```sql
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','reporter','leitor') NOT NULL DEFAULT 'leitor',
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);
```

**Tabela de Notícias:**
```sql
CREATE TABLE `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `noticia` text NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `autor` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `autor` (`autor`),
  FOREIGN KEY (`autor`) REFERENCES `usuarios`(`id`)
);
```

### Verificação de Login (verifica_login.php)

```php
<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}
?>
```

---
## 🎨 Design e Estilização

O projeto utiliza um design moderno **"Soft UI"** com as seguintes características:

- **Tons de Esmeralda:** Paleta de cores verde-esmeralda profissional
- **Glassmorphism:** Efeitos de vidro fosco interativos
- **Curvas Suaves:** Border radius para interfaces confortáveis
- **Design Responsivo:** Compatível com desktop e mobile
- **FontAwesome:** Ícones modernos e intuitivos
- **Google Fonts (Inter):** Tipografia limpa e legível

---
## 🔐 Funcionalidades

### 🏠 Área Pública
- ✅ Página inicial com listagem de notícias por recência
- ✅ Visualização individual de notícias
- ✅ Cadastro de novos usuários
- ✅ Login de usuários

### 🔐 Área Restrita (Logada)
- ✅ Dashboard personalizado
- ✅ CRUD completo de notícias (criar, editar, excluir)
- ✅ Edição de perfil do usuário
- ✅ Gerenciamento de conta

### 👑 Área Administrativa
- ✅ Gerenciamento de todas as notícias
- ✅ Gerenciamento de todos os usuários
- ✅ Controle de acesso por tipo (admin, reporter, leitor)
---
##📎Anexos📎
----> Desktop🖥️

<img width="1303" height="636" alt="image" src="https://github.com/user-attachments/assets/7bbc9103-86e8-4a38-a5f8-8cdca05a58fe" />

---
<img width="1300" height="650" alt="image" src="https://github.com/user-attachments/assets/d5c3a2a7-4cfc-4fa2-86c3-1e888360bcc0" />

---

<img width="1302" height="645" alt="image" src="https://github.com/user-attachments/assets/7b0af486-c439-4545-9172-17822281b842" />

----> Mobile📱

<img width="249" height="552" alt="image" src="https://github.com/user-attachments/assets/d29e7f92-f322-4c19-a4ca-38bde386ce3b" />                                              <img width="247" height="551" alt="image" src="https://github.com/user-attachments/assets/d1045ba2-0928-4dce-a9f2-2acbaa1db3b3" />
<img width="252" height="548" alt="image" src="https://github.com/user-attachments/assets/691f1552-e5ab-424a-a5f4-bf5442a7f39e" />

---
## 🏆 Autor(es)

👤 **Seu Nome**  
📧 Email: leo.schmitt2708@gmail.com.com  
🔗 [GitHub](https://github.com/Ghost-souza006)

---

## 🎯 Objetivo do Repositório

Este repositório serve como um portfólio para demonstrar habilidades em desenvolvimento web com PHP, aplicando conceitos de:
- Programação Orientada a Objetos
- Banco de Dados Relacional (MySQL)
- MVC simplificado
- Segurança web (hash de senhas, prevenção SQL injection com PDO)
- Design responsivo e moderno

O projeto ajuda na busca de oportunidades de emprego na área de desenvolvimento em um futuro próximo.

---
## ⚖️ Licença

Este projeto está sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 📝 Notas

- **Ambiente de Desenvolvimento:** XAMPP (Apache + MySQL + PHP)
- **Sistema Operacional:** Windows
- **IDE Recomendada:** VS Code, PHPStorm ou qualquer editor de texto
- **Navegadores Testados:** Chrome, Firefox, Edge
