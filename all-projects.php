<?php
require_once 'projects.php';
$projectsManager = new Projects();
$projects = $projectsManager->getAllProjects();
$categories = $projectsManager->getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Tous les projets de Christ-Henoc Moungabio — Développeur Full Stack (PHP/Laravel, React, React Native, Next.js, Vue) avec automatisation n8n, intégration API et administration systèmes & réseaux." data-fr="Tous les projets de Christ-Henoc Moungabio — Développeur Full Stack (PHP/Laravel, React, React Native, Next.js, Vue) avec automatisation n8n, intégration API et administration systèmes & réseaux." data-en="All projects by Christ-Henoc Moungabio — Full Stack Developer (PHP/Laravel, React, React Native, Next.js, Vue) with n8n automation, API integrations and systems & network administration." />
  <script>
    (function(){
      const l = localStorage.getItem('lang');
      if(l) document.documentElement.lang = l;
      document.documentElement.classList.add('lang-flash');
    })();
  </script>
  <style>.lang-flash body{ visibility: hidden; }</style>
  <title data-fr="Tous les projets – Christ‑Henoc Moungabio" data-en="All projects – Christ‑Henoc Moungabio">Tous les projets – Christ‑Henoc Moungabio</title>

  <!-- Tailwind CDN -->
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

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />

  <!-- Open Graph -->
  <meta property="og:title" content="Tous les projets – Christ‑Henoc Moungabio" data-fr="Tous les projets – Christ‑Henoc Moungabio" data-en="All projects – Christ‑Henoc Moungabio" />
  <meta property="og:description" content="Découvrez tous mes projets de développement web et mobile." data-fr="Découvrez tous mes projets de développement web et mobile." data-en="Discover all my web and mobile development projects." />
  <meta property="og:type" content="website" />
  <meta property="og:image" content="assets/cover.jpg" />
  <meta name="theme-color" content="#00e6bc" />

  <style>
    html { scroll-behavior: smooth; }
    .reveal { opacity: 0; transform: translateY(20px); transition: all .7s ease; }
    .reveal.show { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body class="bg-gray-950 text-gray-100 font-poppins">

<!-- NAVBAR -->
<header class="sticky top-0 z-50 navbar">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="index.php" class="flex items-center gap-3 group animate-fade-in-up">
        <img src="assets/profil.jpg" alt="Photo de profil de Christ-Henoc Moungabio" data-en-alt="Profile photo of Christ-Henoc Moungabio" class="w-10 h-10 rounded-full ring-2 ring-brand-500/40 animate-pulse" loading="lazy">
        <span class="font-semibold tracking-wide group-hover:text-brand-400">Christ‑Henoc Moungabio</span>
      </a>
      <nav class="hidden md:flex items-center gap-8 text-sm">
        <a href="index.php#about" class="nav-link hover:text-brand-400" data-fr="À propos" data-en="About">À propos</a>
        <a href="index.php#timeline" class="nav-link hover:text-brand-400" data-fr="Parcours" data-en="Journey">Parcours</a>
        <a href="index.php#projects" class="nav-link hover:text-brand-400" data-fr="Projets" data-en="Projects">Projets</a>
        <a href="index.php#testimonials" class="nav-link hover:text-brand-400" data-fr="Témoignages" data-en="Testimonials">Témoignages</a>
        <a href="index.php#skills" class="nav-link hover:text-brand-400" data-fr="Compétences" data-en="Skills">Compétences</a>
        <a href="index.php#contact" class="nav-link hover:text-brand-400" data-fr="Contact" data-en="Contact">Contact</a>
        <a href="assets/CV_Christ-Henoc_Moungabio.pdf" class="btn-secondary" download="CV_Christ-Henoc_Moungabio.pdf" data-fr="Télécharger CV" data-en="Download CV">Télécharger CV</a>

        <button id="langToggle" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
      </nav>
      <button id="menuBtn" class="md:hidden p-2 rounded-lg border border-white/10 hover:border-brand-400/60" aria-label="Ouvrir le menu" data-fr-aria="Ouvrir le menu" data-en-aria="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>
  <div id="mobileMenu" class="md:hidden hidden mobile-menu">
    <div class="px-4 py-4 space-y-3 text-sm">
      <a href="index.php#about" class="block hover:text-brand-400 nav-link" data-fr="À propos" data-en="About">À propos</a>
      <a href="index.php#timeline" class="block hover:text-brand-400 nav-link" data-fr="Parcours" data-en="Journey">Parcours</a>
      <a href="index.php#projects" class="block hover:text-brand-400 nav-link" data-fr="Projets" data-en="Projects">Projets</a>
      <a href="index.php#testimonials" class="block hover:text-brand-400 nav-link" data-fr="Témoignages" data-en="Testimonials">Témoignages</a>
      <a href="index.php#skills" class="block hover:text-brand-400 nav-link" data-fr="Compétences" data-en="Skills">Compétences</a>
      <a href="index.php#contact" class="block hover:text-brand-400 nav-link" data-fr="Contact" data-en="Contact">Contact</a>
      <a href="assets/CV_Christ-Henoc_Moungabio.pdf" class="inline-block btn-secondary" download="CV_Christ-Henoc_Moungabio.pdf" data-fr="Télécharger CV" data-en="Download CV">Télécharger CV</a>
      <button id="langToggleMobile" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-b from-gray-900 to-gray-950 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight animate-gradient bg-gradient-to-r from-white via-brand-400 to-white bg-clip-text text-transparent">
      <span data-fr="Tous mes projets" data-en="All my projects">Tous mes projets</span>
    </h1>
    <p class="mt-5 text-gray-300 text-base md:text-lg max-w-2xl mx-auto" data-fr="Découvrez l'ensemble de mes réalisations en développement web et mobile" data-en="Discover all my web and mobile development projects">
      Découvrez l'ensemble de mes réalisations en développement web et mobile
    </p>
  </div>
</section>

<!-- PROJECTS -->
<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
      <div>
        <h2 class="text-2xl md:text-3xl font-bold text-brand-300" data-fr="Projets" data-en="Projects">Projets</h2>
        <p class="text-gray-300 mt-2" data-fr="Collection complète de mes projets de développement." data-en="Complete collection of my development projects.">
          Collection complète de mes projets de développement.
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-2 text-sm">
        <button data-filter="all" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="Tous" data-en="All">Tous</button>
        <button data-filter="laravel" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="Laravel" data-en="Laravel">Laravel</button>
        <button data-filter="react" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="React" data-en="React">React</button>
        <button data-filter="nextjs" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="Next.js" data-en="Next.js">Next.js</button>
        <button data-filter="vue" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="Vue" data-en="Vue">Vue</button>
        <button data-filter="wordpress" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="WordPress" data-en="WordPress">WordPress</button>
        <button data-filter="automatisation" class="filter-btn px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" data-fr="Automatisation" data-en="Automation">Automatisation</button>
      </div>
    </div>

    <div id="projectGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      <?php foreach ($projects as $project): ?>
        <?php echo $projectsManager->renderProjectCard($project); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="border-t border-white/5 py-8 text-center text-sm text-gray-400">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p>&copy; <span id="year"></span> Christ‑Henoc Moungabio. Tous droits réservés.</p>
    <div class="mt-3 flex justify-center gap-4">
      <a href="https://www.linkedin.com/in/christ-henoc-moungabio-0a0ba4323/" class="hover:text-brand-400" aria-label="LinkedIn" data-fr-aria="LinkedIn" data-en-aria="LinkedIn">LinkedIn</a>
      <a href="mailto:mg.christ.henoc@gmail.com" class="hover:text-brand-400" aria-label="Email" data-fr-aria="Email" data-en-aria="Email">Email</a>
    </div>
  </div>
</footer>

<!-- Back to top -->
<a href="#home" class="fixed bottom-6 right-6 p-3 rounded-full bg-white/5 border border-white/10 backdrop-blur hover:bg-white/10" aria-label="Retour en haut" data-fr-aria="Retour en haut" data-en-aria="Back to top">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 19a1 1 0 0 1-1-1V8.41l-3.3 3.3a1 1 0 1 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 8.4V18a1 1 0 0 1-1 1z"/></svg>
</a>

<!-- Scripts -->
<script>
  document.getElementById('year').textContent = new Date().getFullYear();

  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('show'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  const buttons = document.querySelectorAll('.filter-btn');
  const cards = document.querySelectorAll('#projectGrid .proj');
  buttons.forEach(btn => btn.addEventListener('click', () => {
    const key = btn.dataset.filter;
    buttons.forEach(b => b.classList.remove('ring-1','ring-brand-400'));
    btn.classList.add('ring-1','ring-brand-400');
    cards.forEach(c => {
      if(key==='all'){ c.classList.remove('hidden'); return; }
      const match = (key==='laravel' && c.classList.contains('proj--laravel')) ||
                    (key==='react' && c.classList.contains('proj--react')) ||
                    (key==='nextjs' && c.classList.contains('proj--nextjs')) ||
                    (key==='vue' && c.classList.contains('proj--vue')) ||
                    (key==='wordpress' && c.classList.contains('proj--wordpress')) ||
                    (key==='automatisation' && c.classList.contains('proj--automatisation'));
      c.classList.toggle('hidden', !match);
    });
  }));
</script>
<script src="assets/lang.js"></script>

</body>
</html>
