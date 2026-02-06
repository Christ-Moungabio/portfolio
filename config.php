<?php
// Configuration de la base de données
// Détection automatique de l'environnement
$isOnline = isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'mg-it-solutions.com') !== false;

if ($isOnline) {
    // Configuration pour l'environnement en ligne
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'c2652735c_christmoungabio');
    define('DB_USER', 'c2652735c_henoc');
    define('DB_PASS', 'portfolioportfolio');
    define('DB_CHARSET', 'utf8');
    define('SITE_URL', 'https://christ.moungabio.mg-it-solutions.com/');
} else {
    // Configuration pour le développement local
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'portfolio');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
    define('SITE_URL', 'http://localhost/portfolio/');
}

define('ASSETS_URL', SITE_URL . 'assets/');

// Classe Database
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME;
            if (!empty(DB_CHARSET)) {
                $dsn .= ";charset=" . DB_CHARSET;
            }
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            $this->pdo->setAttribute(3, 2); // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            $this->pdo->setAttribute(19, 2); // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            // Set charset via PDO if not in DSN
            if (!empty(DB_CHARSET)) {
                $this->pdo->exec("SET NAMES " . DB_CHARSET);
            }
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
}
?>
