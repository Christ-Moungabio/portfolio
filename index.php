<?php
require_once 'projects.php';
$projectsManager = new Projects();
$projects = $projectsManager->getLimitedProjectsIncludingPrivate(12);
$allProjects = $projectsManager->getAllProjects();
$showSeeMore = count($allProjects) > 12;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Portfolio de Christ-Henoc Moungabio — Développeur Full Stack (PHP/Laravel, React, React Native, Next.js, Vue) avec automatisation n8n, intégration API et administration systèmes & réseaux." data-fr="Portfolio de Christ-Henoc Moungabio — Développeur Full Stack (PHP/Laravel, React, React Native, Next.js, Vue) avec automatisation n8n, intégration API et administration systèmes & réseaux." data-en="Portfolio of Christ-Henoc Moungabio — Full Stack Developer (PHP/Laravel, React, React Native, Next.js, Vue) with n8n automation, API integrations and systems & network administration." />
  <script>
    (function(){
      const l = localStorage.getItem('lang');
      if(l) document.documentElement.lang = l;
      // add a small class to prevent visible flash before JS applies translations
      document.documentElement.classList.add('lang-flash');
    })();
  </script>
  <style>
    /* hide page until lang applied to avoid FOUC (removed by lang.js after init) */
    .lang-flash body{ visibility: hidden; }
  </style>
  <title data-fr="Portfolio – Christ‑Henoc Moungabio" data-en="Portfolio – Christ‑Henoc Moungabio">Portfolio – Christ‑Henoc Moungabio</title>

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
  <meta property="og:title" content="Portfolio – Christ‑Henoc Moungabio" data-fr="Portfolio – Christ‑Henoc Moungabio" data-en="Portfolio – Christ‑Henoc Moungabio" />
  <meta property="og:description" content="Développeur Full Stack (PHP/Laravel, React, React Native, Next.js, Vue) • Automatisation n8n • Intégration d'API • Réseaux." data-fr="Développeur Full Stack (PHP/Laravel, React, React Native, Next.js, Vue) • Automatisation n8n • Intégration d'API • Réseaux." data-en="Full Stack Developer (PHP/Laravel, React, React Native, Next.js, Vue) • n8n automation • API integrations • Networks." />
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
      <a href="#home" class="flex items-center gap-3 group animate-fade-in-up">
        <img src="assets/profil.jpg" alt="Photo de profil de Christ-Henoc Moungabio" data-en-alt="Profile photo of Christ-Henoc Moungabio" class="w-10 h-10 rounded-full ring-2 ring-brand-500/40 animate-pulse" loading="lazy">
        <span class="font-semibold tracking-wide group-hover:text-brand-400">Christ‑Henoc Moungabio</span>
      </a>
      <nav class="hidden md:flex items-center gap-8 text-sm">
        <a href="#about" class="nav-link hover:text-brand-400" data-fr="À propos" data-en="About">À propos</a>
        <a href="#timeline" class="nav-link hover:text-brand-400" data-fr="Parcours" data-en="Journey">Parcours</a>
        <a href="#projects" class="nav-link hover:text-brand-400" data-fr="Projets" data-en="Projects">Projets</a>
        <a href="#testimonials" class="nav-link hover:text-brand-400" data-fr="Témoignages" data-en="Testimonials">Témoignages</a>
        <a href="#skills" class="nav-link hover:text-brand-400" data-fr="Compétences" data-en="Skills">Compétences</a>
        <a href="#contact" class="nav-link hover:text-brand-400" data-fr="Contact" data-en="Contact">Contact</a>
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
      <a href="#about" class="block hover:text-brand-400 nav-link" data-fr="À propos" data-en="About">À propos</a>
      <a href="#timeline" class="block hover:text-brand-400 nav-link" data-fr="Parcours" data-en="Journey">Parcours</a>
      <a href="#projects" class="block hover:text-brand-400 nav-link" data-fr="Projets" data-en="Projects">Projets</a>
      <a href="#testimonials" class="block hover:text-brand-400 nav-link" data-fr="Témoignages" data-en="Testimonials">Témoignages</a>
      <a href="#skills" class="block hover:text-brand-400 nav-link" data-fr="Compétences" data-en="Skills">Compétences</a>
      <a href="#contact" class="block hover:text-brand-400 nav-link" data-fr="Contact" data-en="Contact">Contact</a>
      <a href="assets/CV_Christ-Henoc_Moungabio.pdf" class="inline-block btn-secondary" download="CV_Christ-Henoc_Moungabio.pdf" data-fr="Télécharger CV" data-en="Download CV">Télécharger CV</a>
      <button id="langToggleMobile" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
    </div>
  </div>
</header>

<!-- HERO -->
<section id="home" class="hero-bg relative overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div class="reveal animate-slide-in-left">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight animate-gradient bg-gradient-to-r from-white via-brand-400 to-white bg-clip-text text-transparent" data-fr="Développeur Full Stack <span class=\"text-brand-400 animate-pulse\">Web & Mobile</span>" data-en="Full Stack <span class=\"text-brand-400 animate-pulse\">Web & Mobile</span> Developer">
          Développeur Full Stack <span class="text-brand-400 animate-pulse">Web & Mobile</span>
        </h1>
        <p class="mt-5 text-gray-300 text-base md:text-lg max-w-xl leading-relaxed" data-fr="Je transforme vos idées en solutions web et mobile fiables et modernes. Spécialisé en back-end (PHP/Laravel), front-end (React/Next.js/Vue), mobile (React Native) et automatisation (n8n). Basé à Brazzaville, ouvert aux missions freelance à distance." data-en="I transform your ideas into reliable and modern web and mobile solutions. Specialized in back-end (PHP/Laravel), front-end (React/Next.js/Vue), mobile (React Native) and automation (n8n). Based in Brazzaville, open to remote freelance missions.">
          Je transforme vos idées en solutions web et mobile fiables et modernes. Spécialisé en back-end (PHP/Laravel), front-end (React/Next.js/Vue), mobile (React Native) et automatisation (n8n). Basé à Brazzaville, ouvert aux missions freelance à distance.
        </p>

        <div class="mt-7 flex flex-wrap gap-3">
          <a href="#projects" class="btn-primary animate-fade-in-up" style="animation-delay: 0.2s" data-fr="Voir mes projets" data-en="View my projects">Voir mes projets</a>
          <a href="#contact" class="btn-secondary animate-fade-in-up" style="animation-delay: 0.4s" data-fr="Me contacter" data-en="Contact me">Me contacter</a>
        </div>
        <ul class="mt-6 flex flex-wrap gap-3 text-xs text-gray-300">
          <li class="skill-tag animate-fade-in-up" style="animation-delay: 0.6s">Laravel</li>
          <li class="skill-tag animate-fade-in-up" style="animation-delay: 0.7s">Symfony</li>
          <li class="skill-tag animate-fade-in-up" style="animation-delay: 0.8s">MySQL & PostgreSQL</li>
          <li class="skill-tag animate-fade-in-up" style="animation-delay: 0.9s">n8n & Intégrations API</li>
          <li class="skill-tag animate-fade-in-up" style="animation-delay: 1.0s">Bootstrap & Tailwind</li>
        </ul>
      </div>
      <div class="reveal animate-slide-in-right flex justify-center md:justify-end">
        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-brand-500/20 to-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
          <img src="assets/profil.jpg" alt="Photo de profil de Christ-Henoc Moungabio" data-en-alt="Profile photo of Christ-Henoc Moungabio" class="relative w-80 h-80 md:w-96 md:h-96 object-cover rounded-full ring-8 ring-brand-500/40 shadow-2xl hover:scale-105 transition-all duration-500 hover:shadow-brand-500/25" />
          <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-br from-brand-500 to-purple-600 rounded-full flex items-center justify-center animate-bounce">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-brand-500/10 rounded-full blur-xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-xl animate-pulse" style="animation-delay: 1s"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-blue-500/10 rounded-full blur-xl animate-pulse" style="animation-delay: 2s"></div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-3 gap-10 items-start">
      <div class="md:col-span-2 reveal">
        <h2 class="text-2xl md:text-3xl font-bold text-brand-300" data-fr="À propos" data-en="About">À propos</h2>
        <p class="mt-4 text-gray-300 leading-relaxed" data-fr="Titulaire d’une licence en <strong>Génie Informatique & Réseaux</strong> (ESCAT, 2025), je suis un développeur Full Stack maîtrisant le back‑end (<strong>PHP/Laravel</strong>), le front-end (<strong>React/Next.js/Vue</strong>), le mobile (<strong>React Native</strong>) et l’<strong>automatisation</strong> avec <strong>n8n</strong>. Mon approche : livrer des solutions fiables, évolutives et bien documentées." data-en="Holder of a degree in <strong>Computer Science & Networks</strong> (ESCAT, 2025), I am a Full Stack developer mastering back-end (<strong>PHP/Laravel</strong>), front-end (<strong>React/Next.js/Vue</strong>), mobile (<strong>React Native</strong>) and <strong>automation</strong> with <strong>n8n</strong>. My approach: deliver reliable, scalable and well-documented solutions.">
          Titulaire d’une licence en <strong>Génie Informatique & Réseaux</strong> (ESCAT, 2025), je suis un développeur Full Stack maîtrisant le back‑end (<strong>PHP/Laravel</strong>), le front-end (<strong>React/Next.js/Vue</strong>), le mobile (<strong>React Native</strong>) et l’<strong>automatisation</strong> avec <strong>n8n</strong>. Mon approche : livrer des solutions fiables, évolutives et bien documentées.
        </p>
      </div>
      <aside class="reveal">
        <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
          <h3 class="font-semibold mb-3" data-fr="Infos rapides" data-en="Quick Info">Infos rapides</h3>
          <ul class="text-sm text-gray-300 space-y-2">
            <li>Brazzaville, Congo</li>
            <li><a href="mailto:contact@christ.moungabio.mg-it-solutions.com" class="hover:text-brand-400">contact@christ.moungabio.mg-it-solutions.com</a></li>
            <li>+242 06 968 84 32</li>
            <li><a href="https://www.linkedin.com/in/christ-henoc-moungabio-0a0ba4323/" class="hover:text-brand-400" aria-label="LinkedIn" data-fr-aria="LinkedIn" data-en-aria="LinkedIn">LinkedIn</a></li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- TIMELINE / PARCOURS -->
<section id="timeline" class="py-16 bg-gradient-to-b from-gray-950 to-gray-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl md:text-3xl font-bold text-brand-300 mb-10" data-fr="Parcours" data-en="Journey">Parcours</h2>
    <div class="relative pl-6 md:pl-10">
      <span class="absolute left-2 md:left-4 top-0 h-full w-px bg-gradient-to-b from-brand-500/60 to-transparent"></span>

      <!-- Timeline items -->
       <div class="reveal relative mb-10">
        <span class="absolute -left-[14px] md:-left-[10px] top-1 inline-block w-3 h-3 rounded-full bg-brand-400 ring-4 ring-brand-400/20"></span>
        <h3 class="text-lg md:text-xl font-semibold" data-fr="Missions Freelance et autres" data-en="Freelance Missions and others">Missions Freelance et autres</h3>
            <p class="text-xs text-gray-400" data-fr="2023 - Présent" data-en="2023 - Present">2023 - Présent</p>
        <p class="mt-2 text-gray-300" data-fr="Développement de projets freelance en web et mobile" data-en="Freelance development of web and mobile projects">Développement de projets freelance en web et mobile</p>
        </div>
        <div class="reveal relative">
          <span class="absolute -left-[14px] md:-left-[10px] top-1 inline-block w-3 h-3 rounded-full bg-brand-400 ring-4 ring-brand-400/20"></span>
          <h3 class="text-lg md:text-xl font-semibold" data-fr="Stage à Congo Digital Service" data-en="Internship at Congo Digital Service">Stage à Congo Digital Service</h3>
          <p class="text-xs text-gray-400">2022</p>
          <p class="mt-2 text-gray-300" data-fr="Stage en développement informatique" data-en="Internship in computer development">Stage en développement informatique</p><br>
        </div>
     
        <div class="reveal relative mb-10">
          <span class="absolute -left-[14px] md:-left-[10px] top-1 inline-block w-3 h-3 rounded-full bg-brand-400 ring-4 ring-brand-400/20"></span>
          <h3 class="text-lg md:text-xl font-semibold" data-fr="Université ESCAT 2025" data-en="ESCAT University 2025">Université ESCAT 2025</h3>
          <p class="text-xs text-gray-400">2025</p>
          <p class="mt-2 text-gray-300" data-fr="Licence en Génie Informatique & Réseau" data-en="Degree in Computer Science & Networks">Licence en Génie Informatique & Réseau</p>
        </div>
        <div class="reveal relative mb-10">
          <span class="absolute -left-[14px] md:-left-[10px] top-1 inline-block w-3 h-3 rounded-full bg-brand-400 ring-4 ring-brand-400/20"></span>
          <h3 class="text-lg md:text-xl font-semibold" data-fr="LYCÉE TECHNIQUE INDUSTRIEL 1ER MAI" data-en="Technical High School 1st May">LYCÉE TECHNIQUE INDUSTRIEL 1ER MAI</h3>
          <p class="text-xs text-gray-400">2020 - 2021</p>
          <p class="mt-2 text-gray-300" data-fr="Baccalauréat En Réseaux & Télécommunications" data-en="Baccalaureate in Networks & Telecommunications">Baccalauréat En Réseaux & Télécommunications</p>
        </div>
        <div class="reveal relative mb-10">
          <span class="absolute -left-[14px] md:-left-[10px] top-1 inline-block w-3 h-3 rounded-full bg-brand-400 ring-4 ring-brand-400/20"></span>
          <h3 class="text-lg md:text-xl font-semibold" data-fr="Formation iConnect" data-en="iConnect Training">Formation iConnect</h3>
          <p class="text-xs text-gray-400">2021 - 2023</p>
          <p class="mt-2 text-gray-300" data-fr="iconnect" data-en="iconnect">iconnect</p>
        </div>
      </div>
      
  </div>
</section>

<!-- PROJECTS -->
<section id="projects" class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
      <div>
        <h2 class="text-2xl md:text-3xl font-bold text-brand-300" data-fr="Projets" data-en="Projects">Projets</h2>
        <p class="text-gray-300 mt-2" data-fr="Sélection de projets réels et démos. Chaque carte contient une capture, une description et la stack." data-en="Selection of real projects and demos. Each card contains a screenshot, description and stack.">Sélection de projets réels et démos. Chaque carte contient une capture, une description et la stack.</p>
      </div>

    </div>

    <div id="projectGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      <?php foreach ($projects as $project): ?>
        <?php echo $projectsManager->renderProjectCard($project); ?>
      <?php endforeach; ?>
    </div>

    <?php if ($showSeeMore): ?>
      <div class="text-center mt-8">
        <a href="all-projects.php" class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-gray-900 font-semibold rounded-xl hover:from-brand-400 hover:to-brand-500 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl" data-fr="Voir plus de projets" data-en="See more projects">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          Voir plus de projets
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- TESTIMONIALS -->
<section id="testimonials" class="py-16 bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-2xl md:text-3xl font-bold text-brand-300 mb-4" data-fr="Témoignages" data-en="Testimonials">Témoignages</h2>
      <p class="text-gray-300 max-w-2xl mx-auto" data-fr="Ce que disent mes clients et partenaires de notre collaboration" data-en="What my clients and partners say about our collaboration">Ce que disent mes clients et partenaires de notre collaboration</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Testimonial 1 -->
      <div class="testimonial-card reveal">
        <div class="flex items-center mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
            J
          </div>
          <div>
            <h4 class="font-semibold text-white">Jude Brunel Missengue</h4>
            <p class="text-gray-400 text-sm">DG, Maknet Entreprise</p>
          </div>
        </div>
        <div class="flex mb-3">
          <span class="text-yellow-400">★★★★★</span>
        </div>
        <p class="text-gray-300 italic" data-fr="&quot;Christ-Henoc a travaillé sur notre plateforme d'entreprise. Son approche organisée et ses compétences techniques ont aidé à mener le projet à bien.&quot;" data-en="&quot;Christ-Henoc worked on our company platform. His organized approach and technical skills helped bring the project to fruition.&quot;">"Christ-Henoc a travaillé sur notre plateforme d'entreprise. Son approche organisée et ses compétences techniques ont aidé à mener le projet à bien."</p>
      </div>

      <!-- Testimonial 2 -->
      <div class="testimonial-card reveal">
        <div class="flex items-center mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
            H
          </div>
          <div>
            <h4 class="font-semibold text-white">M. Hardy</h4>
            <p class="text-gray-400 text-sm">Gérant, Cashcraft-Entreprise</p>
          </div>
        </div>
        <div class="flex mb-3">
          <span class="text-yellow-400">★★★★★</span>
        </div>
        <p class="text-gray-300 italic" data-fr="&quot;Christ-Henoc a développé notre site e-commerce avec une expertise remarquable. Son approche professionnelle et ses compétences techniques nous ont permis d'atteindre nos objectifs avec succès.&quot;" data-en="&quot;Christ-Henoc developed our e-commerce site with remarkable expertise. His professional approach and technical skills enabled us to achieve our goals successfully.&quot;">"Christ-Henoc a développé notre site e-commerce avec une expertise remarquable. Son approche professionnelle et ses compétences techniques nous ont permis d'atteindre nos objectifs avec succès."</p>
      </div>

      <!-- Testimonial 3 -->
      <div class="testimonial-card reveal">
        <div class="flex items-center mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
            M
          </div>
          <div>
            <h4 class="font-semibold text-white">M. Cubase</h4>
            <p class="text-gray-400 text-sm">Responsable, An Flash</p>
          </div>
        </div>
        <div class="flex mb-3">
          <span class="text-yellow-400">★★★★★</span>
        </div>
        <p class="text-gray-300 italic" data-fr="&quot;Christ-Henoc a développé la plateforme Brazza Na Ndulé Talent avec une approche méthodique et créative. Sa maîtrise des technologies modernes nous a permis de réaliser ce concours musical avec succès.&quot;" data-en="&quot;Christ-Henoc developed the Brazza Na Ndulé Talent platform with a methodical and creative approach. His mastery of modern technologies enabled us to successfully execute this music competition.&quot;">"Christ-Henoc a développé la plateforme Brazza Na Ndulé Talent avec une approche méthodique et créative. Sa maîtrise des technologies modernes nous a permis de réaliser ce concours musical avec succès."</p>
      </div>
    </div>
  </div>
</section>

<!-- SKILLS -->
<section id="skills" class="py-16 bg-gradient-to-b from-gray-900 to-gray-950">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl md:text-3xl font-bold text-brand-300 mb-12" data-fr="Compétences" data-en="Skills">Compétences</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Back-end Skills -->
      <div class="reveal">
          <h3 class="text-xl font-semibold mb-6 text-white" data-fr="Back-end" data-en="Back-end">Back-end</h3>
        <div class="space-y-4">
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">PHP</span>
              <span class="text-brand-400 font-semibold">90%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="90"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Laravel</span>
              <span class="text-brand-400 font-semibold">95%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="95"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Symfony</span>
              <span class="text-brand-400 font-semibold">85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="85"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">MySQL</span>
              <span class="text-brand-400 font-semibold">90%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="90"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">PostgreSQL</span>
              <span class="text-brand-400 font-semibold">80%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="80"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Front-end Skills -->
      <div class="reveal">
          <h3 class="text-xl font-semibold mb-6 text-white" data-fr="Front-end" data-en="Front-end">Front-end</h3>
        <div class="space-y-4">
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">React</span>
              <span class="text-brand-400 font-semibold">90%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="90"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">React Native</span>
              <span class="text-brand-400 font-semibold">85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="85"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Next.js</span>
              <span class="text-brand-400 font-semibold">85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="85"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Vue.js</span>
              <span class="text-brand-400 font-semibold">80%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="80"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">JavaScript</span>
              <span class="text-brand-400 font-semibold">90%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="90"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tools & Technologies -->
      <div class="reveal">
          <h3 class="text-xl font-semibold mb-6 text-white" data-fr="Outils & Technologies" data-en="Tools & Technologies">Outils & Technologies</h3>
        <div class="space-y-4">
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">HTML/CSS</span>
              <span class="text-brand-400 font-semibold">95%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="95"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Tailwind CSS</span>
              <span class="text-brand-400 font-semibold">90%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="90"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Git & GitHub</span>
              <span class="text-brand-400 font-semibold">85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="85"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">Docker</span>
              <span class="text-brand-400 font-semibold">75%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="75"></div>
            </div>
          </div>
          <div class="skill-progress">
            <div class="flex justify-between mb-2">
              <span class="text-gray-300">n8n</span>
              <span class="text-brand-400 font-semibold">80%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" data-progress="80"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-start">
      <div class="reveal">
        <h2 class="text-2xl md:text-3xl font-bold text-brand-300" data-fr="Contact" data-en="Contact">Contact</h2>
        <p class="mt-3 text-gray-300" data-fr="Parlons de votre projet : délais, budget, objectifs. Réponse rapide." data-en="Let's talk about your project: deadlines, budget, objectives. Quick response.">Parlons de votre projet : délais, budget, objectifs. Réponse rapide.</p>
        <div class="mt-6 space-y-2 text-sm text-gray-300">
          <p><a href="mailto:contact@christ.moungabio.mg-it-solutions.com" class="hover:text-brand-400">contact@christ.moungabio.mg-it-solutions.com</a></p>
          <p><a href="tel:+242069688432" class="hover:text-brand-400">+242 06 968 84 32</a> (WhatsApp)</p>
        </div>
      </div>
      <form action="https://formsubmit.co/contact@christ.moungabio.mg-it-solutions.com" method="POST" class="reveal p-6 md:p-8 rounded-2xl border border-brand-500/30 bg-gradient-to-br from-brand-500/5 via-brand-500/2 to-purple-500/5 shadow-xl shadow-brand-500/10 grid grid-cols-1 gap-4 backdrop-blur-sm">
        <input type="hidden" name="_subject" value="Message depuis le portfolio / christ-henoc.dev">
        <input type="hidden" name="_template" value="table">
        <input type="text" name="_honey" class="hidden" tabindex="-1" autocomplete="off">
        <input type="hidden" name="_captcha" value="false">
        <input type="hidden" name="_next" value="#thanks">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2" data-fr="Nom" data-en="Name">Nom</label>
          <input name="name" required class="form-input" placeholder="Votre nom complet" data-fr="Votre nom complet" data-en="Your full name" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2" data-fr="Email" data-en="Email">Email</label>
          <input type="email" name="email" required class="form-input" placeholder="votre.email@exemple.com" data-fr="votre.email@exemple.com" data-en="your.email@example.com" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2" data-fr="Message" data-en="Message">Message</label>
          <textarea name="message" rows="4" required class="form-input" placeholder="Décrivez votre projet en quelques lignes..." data-fr="Décrivez votre projet en quelques lignes..." data-en="Describe your project in a few lines..."></textarea>
        </div>
        <button type="submit" class="mt-4" data-fr="Envoyer" data-en="Send">Envoyer</button>
      </form>
    </div>
    <div id="thanks" class="sr-only" data-fr="Merci pour votre message ! Je vous répondrai dans les plus brefs délais." data-en="Thank you for your message! I will reply as soon as possible.">Merci pour votre message !</div>
  </div>
</section>

<!-- FOOTER -->
<footer class="border-t border-white/5 py-8 text-center text-sm text-gray-400">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p data-fr="&copy; <span id='year'></span> Christ‑Henoc Moungabio. Tous droits réservés." data-en="&copy; <span id='year'></span> Christ‑Henoc Moungabio. All rights reserved.">&copy; <span id="year"></span> Christ‑Henoc Moungabio. Tous droits réservés.</p>
    <div class="mt-3 flex justify-center gap-4">
      <a href="https://www.linkedin.com/in/christ-henoc-moungabio-0a0ba4323/" class="hover:text-brand-400" aria-label="LinkedIn" data-fr-aria="LinkedIn" data-en-aria="LinkedIn">LinkedIn</a>
      <a href="mailto:contact@christ.moungabio.mg-it-solutions.com" class="hover:text-brand-400" aria-label="Email" data-fr-aria="Email" data-en-aria="Email">Email</a>
    </div>
  </div>
</footer>

<!-- Back to top -->
<a href="#home" class="fixed bottom-6 right-6 p-3 rounded-full bg-white/5 border border-white/10 backdrop-blur hover:bg-white/10" aria-label="Retour en haut" data-fr-aria="Retour en haut" data-en-aria="Back to top">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 19a1 1 0 0 1-1-1V8.41l-3.3 3.3a1 1 0 1 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 8.4V18a1 1 0 0 1-1 1z"/></svg>
</a>

<!-- Schema.org -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Christ-Henoc Moungabio",
  "jobTitle": "Développeur Full Stack",
  "email": "contact@christ.moungabio.mg-it-solutions.com",
  "telephone": "+242069688432",
  "worksFor": {"@type":"Organization","name":"Freelance"},
  "knowsAbout": ["PHP","Laravel","Symfony","React","React Native","Next.js","Vue","Automatisation n8n","MySQL","PostgreSQL","API"]
}
</script>

<!-- Scripts -->
<script>
  document.getElementById('year').textContent = new Date().getFullYear();

  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { 
      if (e.isIntersecting) {
        e.target.classList.add('show');
        // Animate progress bars
        e.target.querySelectorAll('.progress-fill[data-progress]').forEach(bar => {
          const progress = bar.getAttribute('data-progress');
          setTimeout(() => {
            bar.style.width = progress + '%';
          }, 200);
        });
      }
    });
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
      const match = c.classList.contains('proj--' + key);
      c.classList.toggle('hidden', !match);
    });
  }));



  // Theme toggle functionality
  const themeToggle = document.getElementById('themeToggle');
  const themeToggleMobile = document.getElementById('themeToggleMobile');
  const body = document.body;

  // Check for saved theme preference or default to dark mode
  const currentTheme = localStorage.getItem('theme') || 'dark';
  if (currentTheme === 'light') {
    body.classList.remove('bg-gray-950', 'text-gray-100');
    body.classList.add('bg-gray-50', 'text-gray-900');
    updateThemeIcons(true);
  }

  function toggleTheme() {
    const isLight = body.classList.contains('bg-gray-50');
    if (isLight) {
      // Switch to dark mode
      body.classList.remove('bg-gray-50', 'text-gray-900');
      body.classList.add('bg-gray-950', 'text-gray-100');
      localStorage.setItem('theme', 'dark');
      updateThemeIcons(false);
    } else {
      // Switch to light mode
      body.classList.remove('bg-gray-950', 'text-gray-100');
      body.classList.add('bg-gray-50', 'text-gray-900');
      localStorage.setItem('theme', 'light');
      updateThemeIcons(true);
    }
  }

  function updateThemeIcons(isLight) {
    const sunIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>`;
    const moonIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>`;

    const icon = isLight ? moonIcon : sunIcon;
    if (themeToggle) themeToggle.innerHTML = icon;
    if (themeToggleMobile) themeToggleMobile.innerHTML = icon;
  }

  if (themeToggle) themeToggle.addEventListener('click', toggleTheme);
  if (themeToggleMobile) themeToggleMobile.addEventListener('click', toggleTheme);

  // Contact form confirmation
  if (window.location.hash === '#thanks') {
    const thanksDiv = document.getElementById('thanks');
    thanksDiv.classList.remove('sr-only');
    thanksDiv.classList.add('block', 'text-center', 'text-green-400', 'font-semibold', 'mt-4');
    const currentLang = document.documentElement.lang || 'fr';
    thanksDiv.textContent = thanksDiv.dataset[currentLang];
  }
</script>
<script src="assets/lang.js"></script>

</body>
</html>
