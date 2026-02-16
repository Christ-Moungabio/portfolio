<?php
/**
 * Configuration centralisée du portfolio
 * Support pour environnement local et en ligne
 * 
 * Pour passer en mode en ligne:
 * - Soit renommer .env en .env.local et .env.online en .env
 * - Soit définir ENV_MODE = 'online' ci-dessous
 */

// ============================================
// CONFIGURATION MANUELLE - Modifier ici si besoin
// ============================================

// Mode: 'local' pour développement local, 'online' pour serveur en ligne
// Cette valeur est surchargée si le fichier .env ou .env.online existe
define('ENV_MODE', 'local'); // Options: 'local' ou 'online'

// ============================================
// LECTURE DES FICHIERS DE CONFIGURATION
// ============================================

function loadEnvConfig() {
    global $envConfig;
    
    // Valeurs par défaut (local)
    $config = [
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'portfolio',
        'DB_USER' => 'root',
        'DB_PASS' => '',
        'DB_CHARSET' => 'utf8mb4',
        'SITE_URL' => 'http://localhost/portfolio/',
    ];
    
    // Utiliser des chemins absolus basés sur le répertoire de ce fichier
    $baseDir = dirname(__FILE__);
    $envPath = $baseDir . '/.env';
    $envOnlinePath = $baseDir . '/.env.online';
    
    // PRIORITÉ 1: Charger .env (le plus important, utilisé partout)
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = parseEnvFile($lines, $config);
        return $config;
    }
    
    // PRIORITÉ 2: Si .env n'existe pas, essayer .env.online (fallback)
    if (file_exists($envOnlinePath)) {
        $lines = file($envOnlinePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = parseEnvFile($lines, $config);
        return $config;
    }
    
    // Sinon utiliser les valeurs par défaut
    return $config;
}

function parseEnvFile($lines, $config) {
    foreach ($lines as $line) {
        $line = trim($line);
        // Ignorer les commentaires et les lignes vides
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Parser les variables KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Supprimer les guillemets si présents
            $value = trim($value, '"\'');
            
            if (!empty($key)) {
                $config[$key] = $value;
            }
        }
    }
    return $config;
}

// Charger la configuration
$envConfig = loadEnvConfig();

// Définir les constantes de configuration
define('DB_HOST', $envConfig['DB_HOST']);
define('DB_NAME', $envConfig['DB_NAME']);
define('DB_USER', $envConfig['DB_USER']);
define('DB_PASS', $envConfig['DB_PASS']);
define('DB_CHARSET', $envConfig['DB_CHARSET'] ?? 'utf8mb4');
define('SITE_URL', $envConfig['SITE_URL'] ?? 'http://localhost/portfolio/');

define('ASSETS_URL', SITE_URL . 'assets/');

// Mode debug (afficher les erreurs)
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/**
 * Classe Database - Singleton pattern pour la connexion PDO
 */
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME;
            if (!empty(DB_CHARSET)) {
                $dsn .= ";charset=" . DB_CHARSET;
            }
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // Set charset via PDO if not in DSN
            if (!empty(DB_CHARSET)) {
                $this->pdo->exec("SET NAMES " . DB_CHARSET);
            }
        } catch (PDOException $e) {
            $baseDir = dirname(__FILE__);
            $envPath = $baseDir . '/.env';
            $envContent = '';
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                // Masquer le mot de passe
                $envContent = preg_replace('/DB_PASS=(.*)/', 'DB_PASS=****', $envContent);
            }
            
            $errorMsg = "Erreur de connexion à la base de données: " . $e->getMessage();
            $errorMsg .= "\n\n=== INFOS DE CONNEXION ===\n";
            $errorMsg .= "Host: " . DB_HOST . "\n";
            $errorMsg .= "Database: " . DB_NAME . "\n";
            $errorMsg .= "User: " . DB_USER . "\n";
            $errorMsg .= "Charset: " . DB_CHARSET . "\n";
            
            $errorMsg .= "\n=== FICHIERS DÉTECTÉS ===\n";
            $errorMsg .= ".env existe: " . (file_exists($envPath) ? "✅ OUI" : "❌ NON") . "\n";
            
            if (file_exists($envPath)) {
                $errorMsg .= "\n=== CONTENU DU .env ===\n";
                $errorMsg .= $envContent;
            }
            
            $errorMsg .= "\n=== CHEMINS FICHIERS ===\n";
            $errorMsg .= "Config file: " . __FILE__ . "\n";
            $errorMsg .= "Base dir: " . $baseDir . "\n";
            $errorMsg .= ".env path: " . $envPath . "\n";
            
            // Si on est en local, montrer plus de détails
            if (getenv('HTTP_HOST') === false || strpos(getenv('HTTP_HOST') ?? '', 'localhost') !== false) {
                $errorMsg .= "\nAssurez-vous que MySQL est lancé.";
            }
            
            die($errorMsg);
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
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
