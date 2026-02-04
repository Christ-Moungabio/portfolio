<?php
require_once 'db.php';

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

    public function renderProjectCard($project) {
        $technologies = json_decode($project['technologies'], true);
        $techHtml = '';
        foreach ($technologies as $tech) {
            $techHtml .= '<span class="skill-tag">' . htmlspecialchars($tech) . '</span>';
        }

        $projectUrl = 'project.php?slug=' . htmlspecialchars($project['slug']);

        $defaultImage = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWYyOTM3Ii8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzljYTNhZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4=';

        return '
        <div class="proj proj--' . htmlspecialchars($project['category']) . ' reveal project-card">
            <div class="project-image">
                <img src="' . htmlspecialchars($project['main_image'] ?: $defaultImage) . '" alt="' . htmlspecialchars($project['title']) . '" data-en-alt="' . htmlspecialchars($project['title']) . '" class="w-full h-auto max-h-96 object-contain rounded-lg mb-4">
                <div class="project-overlay">
                    <a href="' . $projectUrl . '" class="btn-primary" data-fr="Voir le projet" data-en="View project">Voir le projet</a>
                </div>
            </div>
            <h3 class="text-xl font-semibold mb-2">' . htmlspecialchars($project['title']) . '</h3>
            <p class="text-gray-300 mb-4" data-fr="' . htmlspecialchars($project['description']) . '" data-en="' . htmlspecialchars($project['description_en'] ?: $project['description']) . '">' . htmlspecialchars($project['description']) . '</p>
            <div class="flex flex-wrap gap-2 mb-4">
                ' . $techHtml . '
            </div>
            <div class="flex gap-3">
                <a href="' . $projectUrl . '" class="btn-primary" data-fr="Voir le projet" data-en="View project">Voir le projet</a>
                <a href="#" class="btn-secondary" data-fr="🔒 Code privé" data-en="🔒 Private code">🔒 Code privé</a>
            </div>
        </div>';
    }
}
?>
