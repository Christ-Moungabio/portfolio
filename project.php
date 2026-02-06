<?php
require_once 'projects.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: index.php');
    exit;
}

$projectsManager = new Projects();
$project = $projectsManager->getProjectBySlug($slug);

if (!$project) {
    header('Location: index.php');
    exit;
}

// Check if project is private and user is not admin
if ($project['private'] == 1) {
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('Location: index.php');
        exit;
    }
}

$technologies = json_decode($project['technologies'], true);
$galleryImages = json_decode($project['gallery_images'], true) ?: [];
$features = json_decode($project['features'], true) ?: [];

$title = htmlspecialchars($project['title']);
$description = htmlspecialchars($project['description']);
$descriptionEn = htmlspecialchars($project['description_en'] ?: $project['description']);
$mainImage = htmlspecialchars($project['main_image']) ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWYyOTM3Ii8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzljYTNhZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4=';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script>
    (function(){
      const l = localStorage.getItem('lang');
      if(l) document.documentElement.lang = l;
      document.documentElement.classList.add('lang-flash');
    })();
  </script>
  <style>.lang-flash body{ visibility: hidden; }</style>
  <meta name="description" content="<?php echo $description; ?>" data-fr="<?php echo $description; ?>" data-en="<?php echo $descriptionEn; ?>" />
  <meta property="og:description" content="<?php echo $description; ?>" data-fr="<?php echo $description; ?>" data-en="<?php echo $descriptionEn; ?>" />
  <meta property="og:title" content="<?php echo $title; ?>" data-fr="<?php echo $title; ?>" data-en="<?php echo $title; ?>" />
  <title data-fr="<?php echo $title; ?>" data-en="<?php echo $title; ?>"><?php echo $title; ?> - Christ-Henoc Moungabio</title>
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
          },
          boxShadow: { soft: '0 10px 30px rgba(0,0,0,0.25)' }
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
        <a href="index.php" class="hover:text-brand-400" data-fr="Portfolio" data-en="Portfolio">Portfolio</a>
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
      <a href="index.php" class="block hover:text-brand-400">Portfolio</a>
      <button id="langToggleMobile" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-b from-gray-900 to-gray-950 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div class="reveal">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight" data-fr="<?php echo $title; ?>" data-en="<?php echo $title; ?>"><?php echo $title; ?></h1>
        <p class="mt-5 text-gray-300 text-base md:text-lg max-w-xl" data-fr="<?php echo $description; ?>" data-en="<?php echo $descriptionEn; ?>">
          <?php echo $description; ?>
        </p>
        <div class="mt-7 flex flex-wrap gap-3">
          <?php if (!empty($galleryImages)): ?>
          <a href="#gallery" class="px-5 py-3 rounded-xl bg-brand-500 text-gray-900 font-semibold hover:bg-brand-400 transition" data-fr="Voir la galerie" data-en="View gallery">Voir la galerie</a>
          <?php endif; ?>
          <a href="index.php" class="px-5 py-3 rounded-xl border border-white/10 text-white font-semibold hover:border-brand-400 hover:text-brand-400 transition" data-fr="Retour au portfolio" data-en="Back to portfolio">Retour au portfolio</a>
        </div>
      </div>
      <div class="reveal">
        <img src="<?php echo $mainImage; ?>" alt="Image principale du projet <?php echo $title; ?>" class="w-full rounded-lg shadow-soft">
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
        <p class="mt-4 text-gray-300 leading-relaxed" data-fr="<?php echo $description; ?>" data-en="<?php echo $descriptionEn; ?>">
          <?php echo $description; ?>
        </p>

        <?php if (!empty($features)): ?>
        <div class="mt-8">
          <h3 class="text-xl font-semibold mb-4" data-fr="Fonctionnalités principales" data-en="Key Features">Fonctionnalités principales</h3>
          <ul class="space-y-2">
            <?php foreach ($features as $feature): ?>
            <li class="flex items-center text-gray-300">
              <svg class="w-5 h-5 text-brand-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
              </svg>
              <?php echo htmlspecialchars($feature); ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
      <aside class="reveal">
        <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
          <h3 class="font-semibold mb-3" data-fr="Technologies utilisées" data-en="Technologies used">Technologies utilisées</h3>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($technologies as $tech): ?>
            <span class="px-3 py-1 text-xs rounded bg-white/10"><?php echo htmlspecialchars($tech); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- GALLERY -->
<?php if (!empty($galleryImages)): ?>
<section id="gallery" class="py-16 bg-gradient-to-b from-gray-950 to-gray-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl md:text-3xl font-bold text-brand-300 mb-10 text-center" data-fr="Galerie du projet" data-en="Project Gallery">Galerie du projet</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      <?php foreach ($galleryImages as $index => $image): ?>
      <div class="reveal">
        <img src="<?php echo htmlspecialchars($image); ?>" alt="Image du projet <?php echo $index + 1; ?>" class="w-full h-48 object-cover rounded-lg shadow-soft hover:scale-105 transition-transform duration-300">
        <p class="mt-2 text-sm text-gray-400" data-fr="Image <?php echo $index + 1; ?>" data-en="Image <?php echo $index + 1; ?>">Image <?php echo $index + 1; ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FOOTER -->
<footer class="border-t border-white/5 py-8 text-center text-sm text-gray-400">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p data-fr="&copy; <span id=\"year\"></span> Christ‑Henoc Moungabio. Tous droits réservés." data-en="&copy; <span id=\"year\"></span> Christ-Henoc Moungabio. All rights reserved.">&copy; <span id="year"></span> Christ‑Henoc Moungabio. Tous droits réservés.</p>
  </div>
</footer>

<!-- Scripts -->
<script>
  document.getElementById('year').textContent = new Date().getFullYear();

  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { 
      if (e.isIntersecting) {
        e.target.classList.add('show');
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
</script>
<script src="assets/lang.js"></script>

</body>
</html>
