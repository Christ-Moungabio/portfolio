<?php
// Script de configuration de la base de données
require_once 'config.php';
require_once 'db.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Base de données créée avec succès.<br>";

    // Sélectionner la base de données
    $pdo->exec("USE " . DB_NAME);

    // Créer la table des projets
    $sql = "
    CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(255) UNIQUE NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        description_en TEXT,
        technologies JSON NOT NULL,
        category VARCHAR(50) NOT NULL,
        main_image VARCHAR(255),
        gallery_images JSON,
        features JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'projects' créée avec succès.<br>";

    // Créer la table des administrateurs
    $sql = "
    CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'admins' créée avec succès.<br>";

    // Insérer l'administrateur par défaut
    $stmt = $pdo->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE username=VALUES(username)");
    $stmt->execute(['admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.com']);
    echo "Administrateur par défaut inséré avec succès.<br>";

    // Insérer les données par défaut
    $projects = [
        [
            'slug' => 'brazza-na-ndule-talent',
            'title' => 'Brazza na Ndulé Talent',
            'description' => 'Plateforme de concours musical en ligne pour découvrir et promouvoir les talents musicaux africains.',
            'description_en' => 'Online music competition platform to discover and promote African musical talents.',
            'technologies' => json_encode(['Vue.js', 'Symfony', 'MySQL']),
            'category' => 'vue',
            'main_image' => 'assets/brazza/brazza2.PNG',
            'gallery_images' => json_encode(['assets/brazza/brazza2.PNG', 'assets/brazza/brazza3.PNG', 'assets/brazza/brazza4.PNG']),
            'features' => json_encode(['Système de vote en temps réel', 'Profils artistes détaillés', 'Tableau de bord administrateur'])
        ],
        [
            'slug' => 'gestion-paiement-activites',
            'title' => 'Gestion & Paiement Activités',
            'description' => 'Application web pour organiser des activités, gérer des vagues d\'inscription et suivre les paiements.',
            'description_en' => 'Web application to organize activities, manage registration waves and track payments.',
            'technologies' => json_encode(['Laravel', 'MySQL', 'Tailwind CSS']),
            'category' => 'laravel',
            'main_image' => 'assets/gestion/gestion1.PNG',
            'gallery_images' => json_encode(['assets/gestion/gestion1.PNG', 'assets/gestion/gestion2.PNG', 'assets/gestion/gestion3.PNG', 'assets/gestion/gestion4.PNG', 'assets/gestion/gestion5.PNG', 'assets/gestion/gestion6.PNG']),
            'features' => json_encode(['Gestion des activités', 'Gestion des vagues d\'inscription', 'Suivi des paiements', 'Rapports et statistiques'])
        ],
        [
            'slug' => 'lina-shop',
            'title' => 'Lina Shop',
            'description' => 'Boutique en ligne pour accessoires et vêtements tendance avec panier, paiement sécurisé et compte client.',
            'description_en' => 'Online store for trendy accessories and clothing with cart, secure payment and customer account.',
            'technologies' => json_encode(['Laravel', 'MySQL', 'Tailwind CSS']),
            'category' => 'laravel',
            'main_image' => 'assets/lina/lina1.PNG',
            'gallery_images' => json_encode(['assets/lina/lina1.PNG', 'assets/lina/Lina2.PNG', 'assets/lina/lina3.PNG', 'assets/lina/lina4.PNG', 'assets/lina/lina5.PNG', 'assets/lina/lina6.PNG']),
            'features' => json_encode(['Catalogue produits', 'Panier d\'achat', 'Paiement sécurisé', 'Gestion des comptes clients'])
        ],
        [
            'slug' => 'maknet-entreprise',
            'title' => 'Maknet Entreprise',
            'description' => 'Site web d\'entreprise pour Maknet, présentant services, équipe et contact avec design professionnel.',
            'description_en' => 'Corporate website for Maknet, presenting services, team and contact with professional design.',
            'technologies' => json_encode(['WordPress', 'PHP', 'CSS']),
            'category' => 'wordpress',
            'main_image' => 'assets/maknet-entreprise/Maknet.jpg',
            'gallery_images' => json_encode(['assets/maknet-entreprise/Maknet.jpg']),
            'features' => json_encode(['Présentation des services', 'Page équipe', 'Formulaire de contact', 'Design responsive'])
        ],
        [
            'slug' => 'carte-pointage',
            'title' => 'Carte Pointage',
            'description' => 'Système de pointage électronique pour gérer les horaires de travail et générer des rapports.',
            'description_en' => 'Electronic time tracking system to manage work schedules and generate reports.',
            'technologies' => json_encode(['Laravel', 'MySQL', 'Bootstrap']),
            'category' => 'laravel',
            'main_image' => 'assets/carte-pointage/cart1.PNG',
            'gallery_images' => json_encode(['assets/carte-pointage/cart1.PNG', 'assets/carte-pointage/cart2.PNG', 'assets/carte-pointage/cart3.PNG', 'assets/carte-pointage/cart4.PNG', 'assets/carte-pointage/cart5.PNG']),
            'features' => json_encode(['Pointage automatique', 'Gestion des employés', 'Rapports horaires', 'Interface intuitive'])
        ],
        [
            'slug' => 'cashcraft',
            'title' => 'Cashcraft',
            'description' => 'Application de gestion financière personnelle avec suivi des dépenses et budgets.',
            'description_en' => 'Personal finance management application with expense tracking and budgets.',
            'technologies' => json_encode(['React', 'Node.js', 'MongoDB']),
            'category' => 'react',
            'main_image' => 'assets/cashcraft/cash.PNG',
            'gallery_images' => json_encode(['assets/cashcraft/cash.PNG']),
            'features' => json_encode(['Suivi des dépenses', 'Gestion des budgets', 'Rapports financiers', 'Synchronisation multi-appareils'])
        ],
        [
            'slug' => 'anda',
            'title' => 'Anda',
            'description' => 'Plateforme de blog moderne avec contenu optimisé SEO et interface utilisateur intuitive.',
            'description_en' => 'Modern blog platform with SEO-optimized content and intuitive user interface.',
            'technologies' => json_encode(['Next.js', 'TypeScript', 'Tailwind CSS']),
            'category' => 'nextjs',
            'main_image' => 'assets/anda/anda1.PNG',
            'gallery_images' => json_encode(['assets/anda/anda1.PNG']),
            'features' => json_encode(['Éditeur de contenu', 'Optimisation SEO', 'Interface responsive', 'Gestion des commentaires'])
        ],
        [
            'slug' => 'mnbk-kenaya',
            'title' => 'Mnbk Kenaya',
            'description' => 'Application web interactive pour la gestion de projets collaboratifs avec interface moderne.',
            'description_en' => 'Interactive web application for collaborative project management with modern interface.',
            'technologies' => json_encode(['Vue.js', 'Firebase', 'Vuetify']),
            'category' => 'vue',
            'main_image' => 'assets/mnbk-kenaya/kenaya.PNG',
            'gallery_images' => json_encode(['assets/mnbk-kenaya/kenaya.PNG']),
            'features' => json_encode(['Gestion de projets', 'Collaboration en temps réel', 'Tableaux de bord', 'Notifications'])
        ],
        [
            'slug' => 'maknet-shop',
            'title' => 'Maknet Shop',
            'description' => 'Application mobile e-commerce avec expérience utilisateur fluide et paiements intégrés.',
            'description_en' => 'Mobile e-commerce application with smooth user experience and integrated payments.',
            'technologies' => json_encode(['React Native', 'Expo', 'SQLite']),
            'category' => 'react',
            'main_image' => 'assets/maknet-shop/fashion1.PNG',
            'gallery_images' => json_encode(['assets/maknet-shop/fashion1.PNG', 'assets/maknet-shop/fashion2.PNG']),
            'features' => json_encode(['Navigation fluide', 'Paiements intégrés', 'Gestion du panier', 'Synchronisation cloud'])
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO projects (slug, title, description, description_en, technologies, category, main_image, gallery_images, features) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), description_en=VALUES(description_en), technologies=VALUES(technologies), category=VALUES(category), main_image=VALUES(main_image), gallery_images=VALUES(gallery_images), features=VALUES(features)");

    foreach ($projects as $project) {
        $stmt->execute([
            $project['slug'],
            $project['title'],
            $project['description'],
            $project['description_en'],
            $project['technologies'],
            $project['category'],
            $project['main_image'],
            $project['gallery_images'],
            $project['features']
        ]);
    }

    echo "Données insérées avec succès.<br>";
    echo "<strong>Configuration terminée !</strong><br>";
    echo "Vous pouvez maintenant accéder à votre portfolio dynamique à l'adresse : <a href='index.php'>index.php</a>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
