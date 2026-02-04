<?php
session_start();
require_once '../db.php';
require_once '../projects.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        'features' => json_encode(array_map('trim', explode(',', $_POST['features'] ?? ''))),
        'private' => isset($_POST['private']) ? 1 : 0
    ];

    try {
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO projects (slug, title, description, description_en, technologies, category, main_image, gallery_images, features, private) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            array_values($data)
        );
        $message = 'Projet ajouté avec succès !';

        // Create the project page only if not private
        if ($data['private'] == 0) {
            createProjectPage($data);
        }
    } catch (Exception $e) {
        $error = 'Erreur lors de l\'ajout du projet: ' . $e->getMessage();
    }
}

function createProjectPage($project) {
    $slug = $project['slug'];
    $filePath = '../' . $slug . '.php';

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
    <title>Ajouter un projet - Portfolio Admin</title>
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
                <a href="dashboard.php#projects" class="block px-4 py-2 mt-2 text-sm font-semibold text-gray-300 hover:text-brand-400 rounded-lg">← Retour aux projets</a>
                <a href="dashboard.php#admins" class="block px-4 py-2 mt-2 text-sm font-semibold text-gray-300 hover:text-brand-400 rounded-lg">Administrateurs</a>
                <a href="logout.php" class="block px-4 py-2 mt-2 text-sm font-semibold text-red-400 hover:text-red-300 rounded-lg">Déconnexion</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-400">Ajouter un nouveau projet</h1>
                    <span class="text-sm text-gray-400">Connecté en tant que: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 mb-6">
                        <p class="text-green-400"><?php echo htmlspecialchars($message); ?></p>
                        <div class="mt-4">
                            <a href="dashboard.php#projects" class="px-4 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400">Retour à la liste des projets</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-6">
                        <p class="text-red-400"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <div class="bg-gray-800 rounded-lg border border-white/10 p-8">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Titre du projet *</label>
                                <input type="text" name="title" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Catégorie *</label>
                                <select name="category" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="laravel">Laravel</option>
                                    <option value="react">React</option>
                                    <option value="nextjs">Next.js</option>
                                    <option value="vue">Vue.js</option>
                                    <option value="wordpress">WordPress</option>
                                    <option value="automatisation">Automatisation</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description (Français) *</label>
                            <textarea name="description" rows="4" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Décrivez votre projet en français..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description (Anglais)</label>
                            <textarea name="description_en" rows="4" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Describe your project in English..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Technologies utilisées *</label>
                            <div class="flex gap-2">
                                <button type="button" id="openTechModal" class="px-4 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400">Sélectionner les technologies</button>
                                <input type="hidden" name="technologies" id="technologiesInput" required>
                            </div>
                            <div id="selectedTechs" class="flex flex-wrap gap-2 mt-2"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Image principale</label>
                                <input type="file" name="main_image" accept="image/*" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white file:bg-brand-500 file:text-gray-900 file:border-none file:rounded file:px-3 file:py-1 file:mr-3 file:font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <p class="text-xs text-gray-400 mt-1">Formats acceptés: JPG, PNG, GIF, WebP</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Images de galerie</label>
                                <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white file:bg-brand-500 file:text-gray-900 file:border-none file:rounded file:px-3 file:py-1 file:mr-3 file:font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <p class="text-xs text-gray-400 mt-1">Sélectionnez plusieurs images (Ctrl+Clic)</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Fonctionnalités principales</label>
                            <input type="text" name="features" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Ex: Authentification, API REST, Interface responsive (séparées par des virgules)">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="private" id="private" class="w-4 h-4 text-brand-600 bg-gray-700 border-gray-600 rounded focus:ring-brand-500 focus:ring-2">
                            <label for="private" class="ml-2 text-sm font-medium text-gray-300">Projet privé (ne sera pas visible publiquement)</label>
                        </div>

                        <div class="flex gap-4 pt-6 border-t border-gray-700">
                            <button type="submit" class="px-8 py-3 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-gray-950 transition">
                                Ajouter le projet
                            </button>
                            <a href="dashboard.php#projects" class="px-8 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-950 transition">
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
