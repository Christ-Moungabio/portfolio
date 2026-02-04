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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title data-fr="Projet - <?php echo $title; ?>" data-en="Project - <?php echo $title; ?>">Projet - <?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .carousel-container { scroll-snap-type: x mandatory; }
        .carousel-item { scroll-snap-align: start; }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navigation -->
    <nav class="bg-gray-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="index.php" class="text-xl font-bold text-purple-400">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à l'accueil
                </a>
                <div class="flex items-center space-x-4">
                    <button onclick="window.location.href='index.php'" class="text-gray-300 hover:text-white" data-fr="Retour" data-en="Back">Retour</button>
                    <button id="langToggle" class="px-3 py-2 rounded-lg border border-white/10 hover:border-brand-400/60" disabled aria-disabled="true" title="Langue : Français (basculement désactivé)" aria-label="Langue : Français, basculement désactivé">FR</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6" data-fr="<?php echo $title; ?>" data-en="<?php echo $title; ?>"><?php echo $title; ?></h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto" data-fr="<?php echo $description; ?>" data-en="<?php echo $descriptionEn; ?>"><?php echo $description; ?></p>
        </div>
    </section>

    <!-- Image Carousel -->
    <?php if (!empty($galleryImages)): ?>
    <section class="py-16 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" data-fr="Galerie du Projet" data-en="Project Gallery">Galerie du Projet</h2>

            <div class="relative">
                <div class="carousel-container flex overflow-x-auto space-x-4 pb-4">
                    <?php foreach ($galleryImages as $index => $image): ?>
                    <div class="carousel-item flex-shrink-0 w-full md:w-1/3">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Image <?php echo $index + 1; ?>" class="w-full h-64 object-cover rounded-lg">
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Carousel Navigation -->
                <div class="flex justify-center mt-4 space-x-2">
                    <button onclick="scrollCarousel(-1)" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg" title="Image précédente" data-fr="Image précédente" data-en="Previous image">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="scrollCarousel(1)" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg" title="Image suivante" data-fr="Image suivante" data-en="Next image">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Project Description -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-8" data-fr="Description du Projet" data-en="Project Description">Description du Projet</h2>

            <div class="prose prose-lg prose-invert mx-auto">
                <h3 class="text-2xl font-semibold mb-4" data-fr="Détails du projet" data-en="Project Details">Détails du projet</h3>
                <p class="mb-6" data-fr="<?php echo $description; ?>" data-en="<?php echo $descriptionEn; ?>">
                    <?php echo $description; ?>
                </p>

                <?php if (!empty($features)): ?>
                <h3 class="text-2xl font-semibold mb-4" data-fr="Fonctionnalités principales" data-en="Key Features">Fonctionnalités principales</h3>
                <ul class="list-disc list-inside mb-6 space-y-2">
                    <?php foreach ($features as $feature): ?>
                    <li><?php echo htmlspecialchars($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <h3 class="text-2xl font-semibold mb-4" data-fr="Technologies utilisées" data-en="Technologies Used">Technologies utilisées</h3>
                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <?php foreach ($technologies as $tech): ?>
                    <div class="bg-gray-800 p-4 rounded-lg text-center">
                        <span class="font-semibold text-purple-400"><?php echo htmlspecialchars($tech); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Carousel functionality
        function scrollCarousel(direction) {
            const container = document.querySelector('.carousel-container');
            if (container) {
                const scrollAmount = container.clientWidth;
                container.scrollBy({
                    left: direction * scrollAmount,
                    behavior: 'smooth'
                });
            }
        }

        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    <script src="assets/lang.js"></script>
</body>
</html>
