<?php
require_once 'projects.php';
$projectsManager = new Projects();
$projectData = $projectsManager->getProjectBySlug('test');
if (!$projectData) {
    header('HTTP/1.0 404 Not Found');
    echo 'Projet non trouvé';
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
      const l = localStorage.getItem('lang');
      if(l) document.documentElement.lang = l;
      document.documentElement.classList.add('lang-flash');
    })();
  </script>
  <style>.lang-flash body{ visibility: hidden; }</style>
  <meta name="description" content="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" data-fr="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" data-en="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" />
  <meta property="og:description" content="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" data-fr="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" data-en="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" />
  <meta property="og:title" content="test - Christ-Henoc Moungabio" data-fr="test - Christ-Henoc Moungabio" data-en="test - Christ-Henoc Moungabio" />
  <title data-fr="test - Christ-Henoc Moungabio" data-en="test - Christ-Henoc Moungabio">test - Christ-Henoc Moungabio</title>
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
        <button onclick="window.location.href='index.php'" class="hover:text-brand-400" data-fr="Retour" data-en="Back">Retour</button>
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
      <button onclick="window.location.href='index.php'" class="block hover:text-brand-400">Retour</button>
      <button id="langToggleMobile" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-b from-gray-900 to-gray-950 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div class="reveal">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight" data-fr="test" data-en="test">test</h1>
        <p class="mt-5 text-gray-300 text-base md:text-lg max-w-xl" data-fr="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" data-en="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh">
          when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh
        </p>
        <div class="mt-7 flex flex-wrap gap-3">
          <a href="#gallery" class="px-5 py-3 rounded-xl bg-brand-500 text-gray-900 font-semibold hover:bg-brand-400 transition" data-fr="Voir la galerie" data-en="View gallery">Voir la galerie</a>
        </div>
      </div>
      <div class="reveal">
        <img src="assets/test/main_1770209720_gallery_1_1770198190_1f1e8_1f1f5.png" alt="test" data-en-alt="test" class="w-full rounded-lg shadow-soft">
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
        <p class="mt-4 text-gray-300 leading-relaxed" data-fr="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh" data-en="when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh">
          when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sh
        </p>
        <div class="mt-6">
          <h3 class="text-xl font-semibold mb-4" data-fr="Fonctionnalités principales" data-en="Main features">Fonctionnalités principales</h3>
          <div class="grid md:grid-cols-2 gap-4">
            <div class="p-4 rounded-lg border border-white/10 bg-white/5">
              <p class="text-sm text-gray-300">Atufd</p>
            </div>
            <div class="p-4 rounded-lg border border-white/10 bg-white/5">
              <p class="text-sm text-gray-300">ffdf</p>
            </div>
            <div class="p-4 rounded-lg border border-white/10 bg-white/5">
              <p class="text-sm text-gray-300">fftet</p>
            </div>
            <div class="p-4 rounded-lg border border-white/10 bg-white/5">
              <p class="text-sm text-gray-300">gttt</p>
            </div>
            <div class="p-4 rounded-lg border border-white/10 bg-white/5">
              <p class="text-sm text-gray-300">gtrthr</p>
            </div>
          </div>
        </div>
      </div>
      <aside class="reveal">
        <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
          <h3 class="font-semibold mb-3">Technologies utilisées</h3>
          <div class="flex flex-wrap gap-2"><span class="px-3 py-1 text-xs rounded bg-white/10">React</span><span class="px-3 py-1 text-xs rounded bg-white/10">NestJS</span><span class="px-3 py-1 text-xs rounded bg-white/10">Nuxt.js</span><span class="px-3 py-1 text-xs rounded bg-white/10">TypeScript</span><span class="px-3 py-1 text-xs rounded bg-white/10">Node.js</span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      <div class="reveal">
        <img src="assets/test/gallery_0_1770209720_gallery_1_1770198190_1f1e8_1f1f5.png" alt="Image 1" data-en-alt="Image 1" class="w-full h-48 object-cover rounded-lg shadow-soft">
        <p class="mt-2 text-sm text-gray-400">Image 1</p>
      </div>
      <div class="reveal">
        <img src="assets/test/gallery_1_1770209720_gallery_2_1770198190_1f923.png" alt="Image 2" data-en-alt="Image 2" class="w-full h-48 object-cover rounded-lg shadow-soft">
        <p class="mt-2 text-sm text-gray-400">Image 2</p>
      </div>
      <div class="reveal">
        <img src="assets/test/gallery_2_1770209720_gallery_3_1770198190_545074124_10162997660606132_7494435912756937727_n.jpg" alt="Image 3" data-en-alt="Image 3" class="w-full h-48 object-cover rounded-lg shadow-soft">
        <p class="mt-2 text-sm text-gray-400">Image 3</p>
      </div>
      <div class="reveal">
        <img src="assets/test/gallery_3_1770209720_main_1770198190_1f1e8_1f1f5.png" alt="Image 4" data-en-alt="Image 4" class="w-full h-48 object-cover rounded-lg shadow-soft">
        <p class="mt-2 text-sm text-gray-400">Image 4</p>
      </div>
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
  document.getElementById('year').textContent = new Date().getFullYear();

  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('show'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));


</script>
<script src="assets/lang.js"></script>

</body>
</html>