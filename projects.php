<?php
require_once 'config.php';

class Projects {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllProjects() {
        $sql = "SELECT * FROM projects WHERE private = 0 ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getLimitedProjects($limit = 12) {
        $sql = "SELECT * FROM projects WHERE private = 0 ORDER BY created_at DESC LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql);
    }

    public function getLimitedProjectsIncludingPrivate($limit = 12) {
        $sql = "SELECT * FROM projects ORDER BY created_at DESC LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql);
    }

    public function getProjectBySlug($slug) {
        $sql = "SELECT * FROM projects WHERE slug = ?";
        return $this->db->fetch($sql, [$slug]);
    }

    public function getProjectsByCategory($category) {
        $sql = "SELECT * FROM projects WHERE category = ? AND private = 0 ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$category]);
    }

    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM projects ORDER BY category";
        return $this->db->fetchAll($sql);
    }

    public function getAllTechnologies() {
        $sql = "SELECT technologies FROM projects WHERE private = 0";
        $results = $this->db->fetchAll($sql);
        $allTechnologies = [];

        foreach ($results as $result) {
            $technologies = json_decode($result['technologies'], true);
            if (is_array($technologies)) {
                $allTechnologies = array_merge($allTechnologies, $technologies);
            }
        }

        $uniqueTechnologies = array_unique($allTechnologies);
        sort($uniqueTechnologies);
        return $uniqueTechnologies;
    }

    public function renderProjectCard($project) {
        $technologies = json_decode($project['technologies'], true);
        $techHtml = '';
        foreach ($technologies as $tech) {
            $techHtml .= '<span class="skill-tag">' . htmlspecialchars($tech) . '</span>';
        }

        $description = htmlspecialchars($project['description']);
        $descriptionEn = htmlspecialchars($project['description_en'] ?: $project['description']);
        $maxLength = 100;
        if (strlen($description) > $maxLength) {
            $description = substr($description, 0, $maxLength) . '.........';
        }
        if (strlen($descriptionEn) > $maxLength) {
            $descriptionEn = substr($descriptionEn, 0, $maxLength) . '.........';
        }

        $projectUrl = 'project.php?slug=' . htmlspecialchars($project['slug']);
        $isPrivate = $project['private'] == 1;

        $defaultImage = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWYyOTM3Ii8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzljYTNhZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4=';

        if ($isPrivate) {
            $buttonsHtml = '<button class="px-4 py-2 bg-gray-600 text-gray-300 font-semibold rounded-lg cursor-not-allowed" disabled data-fr="Projet privé" data-en="Private project">Projet privé</button>';
        } else {
            $buttonsHtml = '
                <a href="' . $projectUrl . '" class="px-4 py-2 bg-brand-500 text-gray-900 font-semibold rounded-lg hover:bg-brand-400 transition" data-fr="Voir le projet" data-en="View project">Voir le projet</a>
                <button class="px-4 py-2 bg-gray-600 text-gray-300 font-semibold rounded-lg hover:bg-gray-500 transition" data-fr="🔒 Code privé" data-en="🔒 Private code">🔒 Code privé</button>';
        }

        $classes = 'proj proj--' . htmlspecialchars($project['category']);
        foreach ($technologies as $tech) {
            $classes .= ' proj--' . htmlspecialchars($tech);
        }

        return '
        <div class="' . $classes . ' reveal project-card">
            <div class="project-image">
                <img src="' . htmlspecialchars($project['main_image'] ?: $defaultImage) . '" alt="' . htmlspecialchars($project['title']) . '" data-en-alt="' . htmlspecialchars($project['title']) . '" class="w-full h-48 md:h-72 object-cover rounded-lg mb-4">
            </div>
            <h3 class="text-xl font-semibold mb-2">' . htmlspecialchars($project['title']) . '</h3>
            <p class="text-gray-300 mb-4" data-fr="' . $description . '" data-en="' . $descriptionEn . '">' . $description . '</p>
            <div class="flex flex-wrap gap-2 mb-4">
                ' . $techHtml . '
            </div>
            <div class="flex gap-3">
                ' . $buttonsHtml . '
            </div>
        </div>';
    }
}
?>
