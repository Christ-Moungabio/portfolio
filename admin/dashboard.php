<?php
session_start();
require_once '../db.php';
require_once '../projects.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$projectsManager = new Projects();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add_project') {
            $slug = strtolower(str_replace(' ', '-', trim($_POST['title'])));
            $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

            // Handle main image upload
            $mainImagePath = '';
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../assets/' . $slug . '/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = 'main_' . time() . '_' . basename($_FILES['main_image']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['main_image']['tmp_name'], $targetPath)) {
                    $mainImagePath = 'assets/' . $slug . '/' . $fileName;
                }
            }

            // Handle gallery images upload
            $galleryImages = [];
            if (isset($_FILES['gallery_images'])) {
                $uploadDir = '../assets/' . $slug . '/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = 'gallery_' . $key . '_' . time() . '_' . basename($_FILES['gallery_images']['name'][$key]);
                        $targetPath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $galleryImages[] = 'assets/' . $slug . '/' . $fileName;
                        }
                    }
                }
            }

            $data = [
                'slug' => $slug,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'description_en' => trim($_POST['description_en'] ?? ''),
                'technologies' => json_encode(array_map('trim', explode(',', $_POST['technologies']))),
                'category' => trim($_POST['category']),
                'main_image' => $mainImagePath,
                'gallery_images' => json_encode($galleryImages),
                'features' => json_encode(array_map('trim', explode(',', $_POST['features'] ?? '')))
            ];

            try {
                $db = Database::getInstance();
                $db->query(
                    "INSERT INTO projects (slug, title, description, description_en, technologies, category, main_image, gallery_images, features) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
                $message = 'Projet ajouté avec succès !';

                // Create the project page
                createProjectPage($data);
            } catch (Exception $e) {
                $error = 'Erreur lors de l\'ajout du projet: ' . $e->getMessage();
            }
        } elseif ($action === 'delete_project') {
            $id = (int)$_POST['project_id'];
            try {
                $db = Database::getInstance();
                $project = $db->fetch("SELECT slug FROM projects WHERE id = ?", [$id]);
                if ($project) {
                    $db->query("DELETE FROM projects WHERE id = ?", [$id]);
                    // Delete the project page file
                    $filePath = $project['slug'] . '.php';
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $message = 'Projet supprimé avec succès !';
                }
            } catch (Exception $e) {
                $error = 'Erreur lors de la suppression du projet: ' . $e->getMessage();
            }
        }
    }
}

$projects = $projectsManager->getAllProjects();

// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add_admin') {
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
        } elseif ($action === 'delete_admin') {
            $adminId = (int)$_POST['admin_id'];
            // Prevent deleting yourself
            if ($adminId !== $_SESSION['admin_id']) {
                try {
                    $db = Database::getInstance();
                    $db->query("DELETE FROM admins WHERE id = ?", [$adminId]);
                    $message = 'Administrateur supprimé avec succès !';
                } catch (Exception $e) {
                    $error = 'Erreur lors de la suppression de l\'administrateur: ' . $e->getMessage();
                }
            } else {
                $error = 'Vous ne pouvez pas vous supprimer vous-même.';
            }
        } elseif ($action === 'update_admin') {
            $adminId = (int)$_POST['admin_id'];
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);

            try {
                $db = Database::getInstance();
                if (!empty($_POST['password'])) {
                    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                    $db->query("UPDATE admins SET username = ?, password = ?, email = ? WHERE id = ?", [$username, $password, $email, $adminId]);
                } else {
                    $db->query("UPDATE admins SET username = ?, email = ? WHERE id = ?", [$username, $email, $adminId]);
                }
                $message = 'Administrateur mis à jour avec succès !';
            } catch (Exception $e) {
                $error = 'Erreur lors de la mise à jour de l\'administrateur: ' . $e->getMessage();
            }
        }
    }
}

// Get all admins
$db = Database::getInstance();
$admins = $db->fetchAll("SELECT id, username, email, created_at FROM admins ORDER BY created_at DESC");

function createProjectPage($project) {
    $slug = $project['slug'];
    $filePath = $slug . '.php';

    $content = '<?php
require_once \'projects.php\';
$projectsManager = new Projects();
$projectData = $projectsManager->getProjectBySlug(\'' . $slug . '\');
if (!$projectData) {
    header(\'HTTP/1.0 404 Not Found\');
    echo \'Projet non trouvé\';
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script>
    (function(){
      const l = localStorage.getItem(\'lang\');
      if(l) document.documentElement.lang = l;
      document.documentElement.classList.add(\'lang-flash\');
    })();
  </script>
  <style>.lang-flash body{ visibility: hidden; }</style>
  <meta name="description" content="' . htmlspecialchars($project['description']) . '" data-fr="' . htmlspecialchars($project['description']) . '" data-en="' . htmlspecialchars($project['description_en'] ?: $project['description']) . '" />
  <meta property="og:description" content="' . htmlspecialchars($project['description']) . '" data-fr="' . htmlspecialchars($project['description']) . '" data-en="' . htmlspecialchars($project['description_en'] ?: $project['description']) . '" />
  <meta property="og:title" content="' . htmlspecialchars($project['title']) . ' - Christ-Henoc Moungabio" data-fr="' . htmlspecialchars($project['title']) . ' - Christ-Henoc Moungabio" data-en="' . htmlspecialchars($project['title']) . ' - Christ-Henoc Moungabio" />
  <title data-fr="' . htmlspecialchars($project['title']) . ' - Christ-Henoc Moungabio" data-en="' . htmlspecialchars($project['title']) . ' - Christ-Henoc Moungabio">' . htmlspecialchars($project['title']) . ' - Christ-Henoc Moungabio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: [\'Poppins\', \'ui-sans-serif\', \'system-ui\'] },
          colors: {
            brand: {
              50: \'#e6fffb\', 100: \'#b3fff2\', 200: \'#80ffe8\', 300: \'#4dffdf\',
              400: \'#1affd5\', 500: \'#00e6bc\', 600: \'#00b396\', 700: \'#008071\',
              800: \'#004e4b\', 900: \'#002b29\'
            }
          },
          boxShadow: { soft: \'0 10px 30px rgba(0,0,0,0.25)\' }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <style>
    html { scroll-behavior: smooth; }
    .reveal { opacity: 0; transform: translateY(20px); transition: all .7s ease; }
    .reveal.show { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body class="bg-gray-950 text-gray-100 font-poppins">

<!-- HEADER -->
<header class="sticky top-0 z-50 backdrop-blur bg-gray-950/70 border-b border-white/5">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="index.php" class="flex items-center gap-3 group">
        <img src="assets/profil.jpg" alt="Photo de profil de Christ-Henoc Moungabio" data-en-alt="Profile photo of Christ-Henoc Moungabio" class="w-10 h-10 rounded-full ring-2 ring-brand-500/40" loading="lazy">
        <span class="font-semibold tracking-wide group-hover:text-brand-400">Christ‑Henoc Moungabio</span>
      </a>
      <nav class="hidden md:flex items-center gap-8 text-sm">
        <button onclick="window.location.href=\'index.php\'" class="hover:text-brand-400" data-fr="Retour" data-en="Back">Retour</button>
        <button id="langToggle" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
      </nav>
      <button id="menuBtn" class="md:hidden p-2 rounded-lg border border-white/10" aria-label="Ouvrir le menu" data-fr-aria="Ouvrir le menu" data-en-aria="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>
  <div id="mobileMenu" class="md:hidden hidden border-t border-white/5">
    <div class="px-4 py-4 space-y-3 text-sm">
      <button onclick="window.location.href=\'index.php\'" class="block hover:text-brand-400">Retour</button>
      <button id="langToggleMobile" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-b from-gray-900 to-gray-950 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div class="reveal">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight" data-fr="' . htmlspecialchars($project['title']) . '" data-en="' . htmlspecialchars($project['title']) . '">' . htmlspecialchars($project['title']) . '</h1>
        <p class="mt-5 text-gray-300 text-base md:text-lg max-w-xl" data-fr="' . htmlspecialchars($project['description']) . '" data-en="' . htmlspecialchars($project['description_en'] ?: $project['description']) . '">
          ' . htmlspecialchars($project['description']) . '
        </p>
        <div class="mt-7 flex flex-wrap gap-3">
          <a href="#gallery" class="px-5 py-3 rounded-xl bg-brand-500 text-gray-900 font-semibold hover:bg-brand-400 transition" data-fr="Voir la galerie" data-en="View gallery">Voir la galerie</a>
        </div>
      </div>
      <div class="reveal">
        <img src="' . htmlspecialchars($project['main_image'] ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWYyOTM3Ii8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzljYTNhZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4=') . '" alt="' . htmlspecialchars($project['title']) . '" data-en-alt="' . htmlspecialchars($project['title']) . '" class="w-full rounded-lg shadow-soft">
      </div>
    </div>
  </div>
</section>

<!-- DESCRIPTION -->
<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-3 gap-10 items-start">
      <div class="md:col-span-2 reveal">
        <h2 class="text-2xl md:text-3xl font-bold text-brand-300" data-fr="À propos du projet" data-en="About the project">À propos du projet</h2>
        <p class="mt-4 text-gray-300 leading-relaxed" data-fr="' . htmlspecialchars($project['description']) . '" data-en="' . htmlspecialchars($project['description_en'] ?: $project['description']) . '">
          ' . htmlspecialchars($project['description']) . '
        </p>
        <div class="mt-6">
          <h3 class="text-xl font-semibold mb-4" data-fr="Fonctionnalités principales" data-en="Main features">Fonctionnalités principales</h3>
          <div class="grid md:grid-cols-2 gap-4">';

    $features = json_decode($project['features'], true);
    if ($features) {
        foreach ($features as $feature) {
            $content .= '
            <div class="p-4 rounded-lg border border-white/10 bg-white/5">
              <p class="text-sm text-gray-300">' . htmlspecialchars($feature) . '</p>
            </div>';
        }
    }

    $content .= '
          </div>
        </div>
      </div>
      <aside class="reveal">
        <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
          <h3 class="font-semibold mb-3">Technologies utilisées</h3>
          <div class="flex flex-wrap gap-2">';

    $technologies = json_decode($project['technologies'], true);
    if ($technologies) {
        foreach ($technologies as $tech) {
            $content .= '<span class="px-3 py-1 text-xs rounded bg-white/10">' . htmlspecialchars($tech) . '</span>';
        }
    }

    $content .= '
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section id="gallery" class="py-16 bg-gradient-to-b from-gray-950 to-gray-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl md:text-3xl font-bold text-brand-300 mb-10 text-center" data-fr="Galerie du projet" data-en="Project Gallery">Galerie du projet</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">';

    $galleryImages = json_decode($project['gallery_images'], true);
    if ($galleryImages) {
        $imageCount = 1;
        foreach ($galleryImages as $image) {
            if (!empty($image)) {
                $content .= '
      <div class="reveal">
        <img src="' . htmlspecialchars($image) . '" alt="Image ' . $imageCount . '" data-en-alt="Image ' . $imageCount . '" class="w-full h-48 object-cover rounded-lg shadow-soft">
        <p class="mt-2 text-sm text-gray-400">Image ' . $imageCount . '</p>
      </div>';
                $imageCount++;
            }
        }
    }

    $content .= '
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="border-t border-white/5 py-8 text-center text-sm text-gray-400">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p>&copy; <span id="year"></span> Christ‑Henoc Moungabio. Tous droits réservés.</p>
  </div>
</footer>

<!-- Scripts -->
<script>
  document.getElementById(\'year\').textContent = new Date().getFullYear();

  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add(\'show\'); });
  }, { threshold: 0.1 });
  document.querySelectorAll(\'.reveal\').forEach(el => io.observe(el));

  const menuBtn = document.getElementById(\'menuBtn\');
  const mobileMenu = document.getElementById(\'mobileMenu\');
  menuBtn?.addEventListener(\'click\', () => mobileMenu.classList.toggle(\'hidden\'));


</script>
<script src="assets/lang.js"></script>

</body>
</html>';

    file_put_contents($filePath, $content);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Portfolio</title>
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
                <a href="#projects" class="block px-4 py-2 mt-2 text-sm font-semibold bg-brand-500 text-gray-900 rounded-lg">Projets</a>
                <a href="#admins" class="block px-4 py-2 mt-2 text-sm font-semibold text-gray-300 hover:text-brand-400 rounded-lg">Administrateurs</a>
                <a href="logout.php" class="block px-4 py-2 mt-2 text-sm font-semibold text-red-400 hover:text-red-300 rounded-lg">Déconnexion</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-400">Dashboard</h1>
                    <span class="text-sm text-gray-400">Connecté en tant que: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 mb-6">
                        <p class="text-green-400"><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-6">
                        <p class="text-red-400"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Projects Section -->
                <section id="projects" class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-white">Gestion des Projets</h2>
                        <a href="add-project.php" class="px-4 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400">
                            Ajouter un projet
                        </a>
                    </div>

                    <!-- Projects List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($projects as $project): ?>
                            <div class="p-6 bg-gray-800 rounded-lg border border-white/10">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p class="text-gray-400 text-sm mb-4"><?php echo htmlspecialchars(substr($project['description'], 0, 100)) . '...'; ?></p>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php
                                    $techs = json_decode($project['technologies'], true);
                                    foreach (array_slice($techs, 0, 3) as $tech) {
                                        echo '<span class="px-2 py-1 text-xs bg-white/10 rounded">' . htmlspecialchars($tech) . '</span>';
                                    }
                                    ?>
                                </div>
                                <div class="flex gap-2">
                                    <a href="../<?php echo htmlspecialchars($project['slug']); ?>.php" target="_blank" class="px-3 py-1 bg-brand-500 text-gray-900 text-sm font-semibold rounded hover:bg-brand-400">Voir</a>
                                    <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                        <input type="hidden" name="action" value="delete_project">
                                        <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded hover:bg-red-400">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Admins Section -->
                <section id="admins" class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-white">Gestion des Administrateurs</h2>
                        <a href="add-admin.php" class="px-4 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400">
                            Ajouter un administrateur
                        </a>
                    </div>

                    <!-- Admins List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($admins as $admin): ?>
                            <div class="p-6 bg-gray-800 rounded-lg border border-white/10">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($admin['username']); ?></h3>
                                <p class="text-gray-400 text-sm mb-2"><?php echo htmlspecialchars($admin['email']); ?></p>
                                <p class="text-gray-500 text-xs mb-4">Créé le: <?php echo date('d/m/Y', strtotime($admin['created_at'])); ?></p>
                                <div class="flex gap-2">
                                    <button onclick="editAdmin(<?php echo $admin['id']; ?>, '<?php echo htmlspecialchars($admin['username']); ?>', '<?php echo htmlspecialchars($admin['email']); ?>')" class="px-3 py-1 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-blue-400">Modifier</button>
                                    <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                                        <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')">
                                            <input type="hidden" name="action" value="delete_admin">
                                            <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded hover:bg-red-400">Supprimer</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-600 text-gray-400 text-sm font-semibold rounded cursor-not-allowed">Vous-même</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Edit Admin Modal -->
                <div id="editAdminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
                            <h3 class="text-xl font-semibold mb-4">Modifier l'administrateur</h3>
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="action" value="update_admin">
                                <input type="hidden" name="admin_id" id="edit_admin_id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Nom d'utilisateur</label>
                                    <input type="text" name="username" id="edit_username" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                    <input type="email" name="email" id="edit_email" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Nouveau mot de passe</label>
                                    <input type="password" name="password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                                </div>
                                <div class="flex gap-4">
                                    <button type="submit" class="px-6 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400">Mettre à jour</button>
                                    <button type="button" onclick="closeEditModal()" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-500">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editAdmin(id, username, email) {
            document.getElementById('edit_admin_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('editAdminModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editAdminModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editAdminModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
