<?php
session_start();
require_once '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $db = Database::getInstance();
        $admin = $db->fetch("SELECT id, username, password FROM admins WHERE username = ?", [$username]);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Portfolio</title>
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
<body class="bg-gray-950 text-gray-100 font-poppins min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-brand-400">Connexion Admin</h2>
            <p class="mt-2 text-sm text-gray-400">Accédez au panneau d'administration</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4">
                <p class="text-red-400 text-sm"><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-gray-950 transition">
                Se connecter
            </button>
        </form>

  
        
    </div>
</body>
</html>
