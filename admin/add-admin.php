<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    try {
        $db = Database::getInstance();
        $db->query("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)", [$username, $password, $email]);
        $message = 'Administrateur ajouté avec succès !';
    } catch (Exception $e) {
        $error = 'Erreur lors de l\'ajout de l\'administrateur: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un administrateur - Portfolio Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        brand: {
                            50: '#e6fffb', 100: '#b3fff2', 200: '#80ffe8', 300: '#4dffdf',
                            400: '#1affd5', 500: '#00e6bc', 600: '#00b396', 700: '#008071',
                            800: '#004e4b', 900: '#002b29'
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-950 text-gray-100 font-poppins">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 border-r border-white/10">
            <div class="p-6">
                <h2 class="text-xl font-bold text-brand-400">Admin Panel</h2>
                <p class="text-sm text-gray-400 mt-1">Gestion du portfolio</p>
            </div>
            <nav class="px-4">
                <a href="dashboard.php#projects" class="block px-4 py-2 mt-2 text-sm font-semibold text-gray-300 hover:text-brand-400 rounded-lg">Projets</a>
                <a href="dashboard.php#admins" class="block px-4 py-2 mt-2 text-sm font-semibold text-gray-300 hover:text-brand-400 rounded-lg">← Retour aux administrateurs</a>
                <a href="logout.php" class="block px-4 py-2 mt-2 text-sm font-semibold text-red-400 hover:text-red-300 rounded-lg">Déconnexion</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-400">Ajouter un administrateur</h1>
                    <span class="text-sm text-gray-400">Connecté en tant que: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 mb-6">
                        <p class="text-green-400"><?php echo htmlspecialchars($message); ?></p>
                        <div class="mt-4">
                            <a href="dashboard.php#admins" class="px-4 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400">Retour à la liste des administrateurs</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-6">
                        <p class="text-red-400"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <div class="bg-gray-800 rounded-lg border border-white/10 p-8">
                    <form method="POST" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nom d'utilisateur *</label>
                            <input type="text" name="username" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Entrez un nom d'utilisateur unique">
                            <p class="text-xs text-gray-400 mt-1">Le nom d'utilisateur doit être unique</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Adresse email *</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="admin@exemple.com">
                            <p class="text-xs text-gray-400 mt-1">L'adresse email doit être unique et valide</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Mot de passe *</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Entrez un mot de passe sécurisé">
                            <p class="text-xs text-gray-400 mt-1">Le mot de passe sera hashé et stocké de manière sécurisée</p>
                        </div>

                        <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-blue-400 mb-2">Informations importantes</h3>
                            <ul class="text-xs text-blue-300 space-y-1">
                                <li>• Le nouveau compte aura accès complet au panneau d'administration</li>
                                <li>• Assurez-vous que l'adresse email est valide pour la récupération du mot de passe</li>
                                <li>• Le mot de passe ne peut pas être récupéré une fois défini</li>
                            </ul>
                        </div>

                        <div class="flex gap-4 pt-6 border-t border-gray-700">
                            <button type="submit" class="px-8 py-3 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-gray-950 transition">
                                Créer l'administrateur
                            </button>
                            <a href="dashboard.php#admins" class="px-8 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-950 transition">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
