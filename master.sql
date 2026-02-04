-- Création de la base de données portfolio
CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;

-- Table des projets
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
    private TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion des données par défaut pour les projets
INSERT INTO projects (slug, title, description, description_en, technologies, category, main_image, gallery_images, features) VALUES
('brazza-na-ndule-talent', 'Brazza na Ndulé Talent', 'Plateforme de concours musical en ligne pour découvrir et promouvoir les talents musicaux africains.', 'Online music competition platform to discover and promote African musical talents.', '["Vue.js", "Symfony", "MySQL"]', 'vue', 'assets/brazza/brazza2.PNG', '["assets/brazza/brazza2.PNG", "assets/brazza/brazza3.PNG", "assets/brazza/brazza4.PNG"]', '["Système de vote en temps réel", "Profils artistes détaillés", "Tableau de bord administrateur"]'),

('gestion-paiement-activites', 'Gestion & Paiement Activités', 'Application web pour organiser des activités, gérer des vagues d\'inscription et suivre les paiements.', 'Web application to organize activities, manage registration waves and track payments.', '["Laravel", "MySQL", "Tailwind CSS"]', 'laravel', 'assets/gestion/gestion1.PNG', '["assets/gestion/gestion1.PNG", "assets/gestion/gestion2.PNG", "assets/gestion/gestion3.PNG", "assets/gestion/gestion4.PNG", "assets/gestion/gestion5.PNG", "assets/gestion/gestion6.PNG"]', '["Gestion des activités", "Gestion des vagues d\'inscription", "Suivi des paiements", "Rapports et statistiques"]'),

('lina-shop', 'Lina Shop', 'Boutique en ligne pour accessoires et vêtements tendance avec panier, paiement sécurisé et compte client.', 'Online store for trendy accessories and clothing with cart, secure payment and customer account.', '["Laravel", "MySQL", "Tailwind CSS"]', 'laravel', 'assets/lina/lina1.PNG', '["assets/lina/lina1.PNG", "assets/lina/Lina2.PNG", "assets/lina/lina3.PNG", "assets/lina/lina4.PNG", "assets/lina/lina5.PNG", "assets/lina/lina6.PNG"]', '["Catalogue produits", "Panier d\'achat", "Paiement sécurisé", "Gestion des comptes clients"]'),

('maknet-entreprise', 'Maknet Entreprise', 'Site web d\'entreprise pour Maknet, présentant services, équipe et contact avec design professionnel.', 'Corporate website for Maknet, presenting services, team and contact with professional design.', '["WordPress", "PHP", "CSS"]', 'wordpress', 'assets/maknet-entreprise/Maknet.jpg', '["assets/maknet-entreprise/Maknet.jpg"]', '["Présentation des services", "Page équipe", "Formulaire de contact", "Design responsive"]'),

('carte-pointage', 'Carte Pointage', 'Système de pointage électronique pour gérer les horaires de travail et générer des rapports.', 'Electronic time tracking system to manage work schedules and generate reports.', '["Laravel", "MySQL", "Bootstrap"]', 'laravel', 'assets/carte-pointage/cart1.PNG', '["assets/carte-pointage/cart1.PNG", "assets/carte-pointage/cart2.PNG", "assets/carte-pointage/cart3.PNG", "assets/carte-pointage/cart4.PNG", "assets/carte-pointage/cart5.PNG"]', '["Pointage automatique", "Gestion des employés", "Rapports horaires", "Interface intuitive"]'),

('cashcraft', 'Cashcraft', 'Application de gestion financière personnelle avec suivi des dépenses et budgets.', 'Personal finance management application with expense tracking and budgets.', '["React", "Node.js", "MongoDB"]', 'react', 'assets/cashcraft/cash.PNG', '["assets/cashcraft/cash.PNG"]', '["Suivi des dépenses", "Gestion des budgets", "Rapports financiers", "Synchronisation multi-appareils"]'),

('anda', 'Anda', 'Plateforme de blog moderne avec contenu optimisé SEO et interface utilisateur intuitive.', 'Modern blog platform with SEO-optimized content and intuitive user interface.', '["Next.js", "TypeScript", "Tailwind CSS"]', 'nextjs', 'assets/anda/anda1.PNG', '["assets/anda/anda1.PNG"]', '["Éditeur de contenu", "Optimisation SEO", "Interface responsive", "Gestion des commentaires"]'),

('mnbk-kenaya', 'Mnbk Kenaya', 'Application web interactive pour la gestion de projets collaboratifs avec interface moderne.', 'Interactive web application for collaborative project management with modern interface.', '["Vue.js", "Firebase", "Vuetify"]', 'vue', 'assets/mnbk-kenaya/kenaya.PNG', '["assets/mnbk-kenaya/kenaya.PNG"]', '["Gestion de projets", "Collaboration en temps réel", "Tableaux de bord", "Notifications"]'),

('maknet-shop', 'Maknet Shop', 'Application mobile e-commerce avec expérience utilisateur fluide et paiements intégrés.', 'Mobile e-commerce application with smooth user experience and integrated payments.', '["React Native", "Expo", "SQLite"]', 'react', 'assets/maknet-shop/fashion1.PNG', '["assets/maknet-shop/fashion1.PNG", "assets/maknet-shop/fashion2.PNG"]', '["Navigation fluide", "Paiements intégrés", "Gestion du panier", "Synchronisation cloud"]'),

('taskflow', 'TaskFlow', 'Application web de gestion de tâches collaborative avec interface moderne, notifications en temps réel et suivi de progrès.', 'Collaborative task management web application with modern interface, real-time notifications and progress tracking.', '["React", "Node.js", "MongoDB"]', 'react', NULL, '[]', '["Gestion des tâches", "Collaboration d\'équipe", "Notifications temps réel", "Suivi de progrès"]'),

('securepay', 'SecurePay', 'Plateforme de paiement sécurisé avec intégration API et chiffrement avancé.', 'Secure payment platform with API integration and advanced encryption.', '["Laravel", "API", "Security"]', 'laravel', NULL, '[]', '["Paiements sécurisés", "Intégration API", "Chiffrement avancé", "Conformité PCI"]'),

('devconnect', 'DevConnect', 'Réseau social pour développeurs avec chat en temps réel et partage de code.', 'Social network for developers with real-time chat and code sharing.', '["React", "Node.js", "Socket.io"]', 'react', NULL, '[]', '["Chat temps réel", "Partage de code", "Profils développeurs", "Communauté technique"]');

-- Insertion d'un administrateur par défaut
INSERT INTO admins (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.com');
-- Mot de passe par défaut : password (hashé avec bcrypt)
