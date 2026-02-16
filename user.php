<?php
/**
 * Script de gestion des administrateurs
 * Accès: https://christ.moungabio.mg-it-solutions.com/user.php
 * 
 * Utilisations:
 * - Créer un nouveau admin: user.php?action=create
 * - Lister les admins: user.php?action=list
 * - Réinitialiser mot de passe: user.php?action=reset
 */

require_once 'config.php';

// Fonction pour créer un nouvel administrateur
function createAdmin($username, $password, $email) {
    try {
        $db = Database::getInstance();
        
        // Vérifier si l'utilisateur existe déjà
        $existing = $db->fetch("SELECT id FROM admins WHERE username = ?", [$username]);
        if ($existing) {
            return [
                'success' => false,
                'message' => "❌ Erreur: L'utilisateur '$username' existe déjà!"
            ];
        }
        
        // Hasher le mot de passe avec bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        // Insérer le nouvel admin
        $db->query(
            "INSERT INTO admins (username, password, email) VALUES (?, ?, ?)",
            [$username, $hashedPassword, $email]
        );
        
        return [
            'success' => true,
            'message' => "✅ Nouvel administrateur créé avec succès!",
            'data' => [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'note' => 'Changez le mot de passe après la première connexion'
            ]
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "❌ Erreur: " . $e->getMessage()
        ];
    }
}

// Fonction pour lister les admins
function listAdmins() {
    try {
        $db = Database::getInstance();
        $admins = $db->fetchAll("SELECT id, username, email, created_at FROM admins ORDER BY created_at DESC");
        
        return [
            'success' => true,
            'data' => $admins
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "❌ Erreur: " . $e->getMessage()
        ];
    }
}

// Fonction pour réinitialiser un mot de passe
function resetPassword($username, $newPassword) {
    try {
        $db = Database::getInstance();
        
        // Vérifier que l'utilisateur existe
        $admin = $db->fetch("SELECT id FROM admins WHERE username = ?", [$username]);
        if (!$admin) {
            return [
                'success' => false,
                'message' => "❌ Erreur: L'utilisateur '$username' n'existe pas!"
            ];
        }
        
        // Hasher le nouveau mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        
        // Mettre à jour
        $db->query(
            "UPDATE admins SET password = ? WHERE username = ?",
            [$hashedPassword, $username]
        );
        
        return [
            'success' => true,
            'message' => "✅ Mot de passe réinitialisé avec succès!",
            'data' => [
                'username' => $username,
                'new_password' => $newPassword
            ]
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "❌ Erreur: " . $e->getMessage()
        ];
    }
}

// Traiter les actions
$action = $_GET['action'] ?? $_POST['action'] ?? 'form';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($username) || empty($password) || empty($email)) {
            $result = ['success' => false, 'message' => '❌ Tous les champs sont obligatoires!'];
        } else {
            $result = createAdmin($username, $password, $email);
        }
    } elseif ($action === 'reset') {
        $username = trim($_POST['username'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        
        if (empty($username) || empty($newPassword)) {
            $result = ['success' => false, 'message' => '❌ Tous les champs sont obligatoires!'];
        } else {
            $result = resetPassword($username, $newPassword);
        }
    }
}

// Afficher les résultats
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Administrateurs</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #00e6bc;
            font-size: 2.5em;
            text-shadow: 0 0 20px rgba(0, 230, 188, 0.3);
        }
        
        .nav {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .nav a {
            padding: 10px 20px;
            background: linear-gradient(135deg, rgba(0, 230, 188, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
            border: 2px solid rgba(0, 230, 188, 0.3);
            color: #00e6bc;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .nav a:hover {
            background: linear-gradient(135deg, rgba(0, 230, 188, 0.2) 0%, rgba(168, 85, 247, 0.2) 100%);
            border-color: #00e6bc;
            transform: translateY(-2px);
        }
        
        .card {
            background: linear-gradient(135deg, rgba(0, 230, 188, 0.05) 0%, rgba(168, 85, 247, 0.03) 100%);
            border: 2px solid rgba(0, 230, 188, 0.2);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .card h2 {
            color: #00e6bc;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
            font-weight: 600;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        input, textarea {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, rgba(0, 230, 188, 0.08) 0%, rgba(168, 85, 247, 0.06) 100%);
            border: 2px solid rgba(0, 230, 188, 0.25);
            border-radius: 8px;
            color: #f1f5f9;
            font-family: inherit;
            font-size: 0.95em;
            transition: all 0.3s ease;
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--brand-400);
            background: linear-gradient(135deg, rgba(0, 230, 188, 0.15) 0%, rgba(168, 85, 247, 0.12) 100%);
            box-shadow: 0 0 0 4px rgba(0, 230, 188, 0.2), 0 8px 20px rgba(0, 230, 188, 0.15);
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #00e6bc 0%, #00b396 100%);
            border: none;
            color: #0f172a;
            font-weight: 700;
            font-size: 0.95em;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(0, 230, 188, 0.2);
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 230, 188, 0.3);
            background: linear-gradient(135deg, #00f5d4 0%, #00dab0 100%);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(34, 197, 94, 0.1) 100%);
            border: 2px solid rgba(34, 197, 94, 0.5);
            color: #86efac;
        }
        
        .alert-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.1) 100%);
            border: 2px solid rgba(239, 68, 68, 0.5);
            color: #fca5a5;
        }
        
        .success-data {
            background: rgba(0, 230, 188, 0.1);
            border-left: 4px solid #00e6bc;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .success-data p {
            margin: 8px 0;
        }
        
        .success-data strong {
            color: #00e6bc;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 230, 188, 0.2);
        }
        
        th {
            background: rgba(0, 230, 188, 0.1);
            color: #00e6bc;
            font-weight: 700;
        }
        
        tr:hover {
            background: rgba(0, 230, 188, 0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 Gestion des Administrateurs</h1>
        
        <div class="nav">
            <a href="?action=form">➕ Créer un Admin</a>
            <a href="?action=list">📋 Lister les Admins</a>
            <a href="?action=reset">🔑 Réinitialiser mot de passe</a>
        </div>
        
        <?php if ($result): ?>
            <div class="alert alert-<?= $result['success'] ? 'success' : 'error' ?>">
                <?= htmlspecialchars($result['message']) ?>
            </div>
            
            <?php if ($result['success'] && isset($result['data'])): ?>
                <div class="success-data">
                    <?php if (isset($result['data']['username'])): ?>
                        <p><strong>Username:</strong> <?= htmlspecialchars($result['data']['username']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($result['data']['email']) ?></p>
                        <p><strong>Password:</strong> <?= htmlspecialchars($result['data']['password']) ?></p>
                        <?php if (isset($result['data']['note'])): ?>
                            <p style="color: #fbbf24;"><strong>⚠️ Note:</strong> <?= htmlspecialchars($result['data']['note']) ?></p>
                        <?php endif; ?>
                    <?php elseif (is_array($result['data'])): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Créé le</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result['data'] as $admin): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($admin['id']) ?></td>
                                        <td><?= htmlspecialchars($admin['username']) ?></td>
                                        <td><?= htmlspecialchars($admin['email']) ?></td>
                                        <td><?= htmlspecialchars($admin['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($action === 'form' || empty($action)): ?>
            <div class="card">
                <h2>➕ Créer un nouvel administrateur</h2>
                <form method="POST" action="?action=create">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" required placeholder="Ex: admin2">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="Ex: admin@example.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required placeholder="Entrez un mot de passe sécurisé">
                    </div>
                    <button type="submit">Créer le compte</button>
                </form>
            </div>
        <?php endif; ?>
        
        <?php if ($action === 'list'): ?>
            <div class="card">
                <h2>📋 Liste des administrateurs</h2>
                <?php 
                    $listResult = listAdmins();
                    if ($listResult['success']): 
                ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listResult['data'] as $admin): ?>
                                <tr>
                                    <td><?= htmlspecialchars($admin['id']) ?></td>
                                    <td><?= htmlspecialchars($admin['username']) ?></td>
                                    <td><?= htmlspecialchars($admin['email']) ?></td>
                                    <td><?= htmlspecialchars($admin['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-error"><?= htmlspecialchars($listResult['message']) ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($action === 'reset'): ?>
            <div class="card">
                <h2>🔑 Réinitialiser le mot de passe</h2>
                <form method="POST" action="?action=reset">
                    <input type="hidden" name="action" value="reset">
                    <div class="form-group">
                        <label for="reset_username">Nom d'utilisateur</label>
                        <input type="text" id="reset_username" name="username" required placeholder="Ex: admin">
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" required placeholder="Entrez le nouveau mot de passe">
                    </div>
                    <button type="submit">Réinitialiser</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
