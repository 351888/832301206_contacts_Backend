<?php
// 简化版ThinkPHP框架核心文件
class ThinkPHP {
    private static $instance;
    private $config;
    private $db;
    
    private function __construct() {
        // 加载配置
        $this->config = require CONFIG_PATH . 'config.php';
        // 初始化数据库连接
        $this->initDatabase();
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function initDatabase() {
        try {
            // 先尝试不指定数据库名连接
            $pdo = new PDO(
                "mysql:host={$this->config['db_host']};charset=utf8",
                $this->config['db_user'],
                $this->config['db_pass']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 创建数据库
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->config['db_name']}");
            
            // 连接到指定数据库
            $this->db = new PDO(
                "mysql:host={$this->config['db_host']};dbname={$this->config['db_name']};charset=utf8",
                $this->config['db_user'],
                $this->config['db_pass']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // 返回统一JSON错误，便于前端识别
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'error' => '数据库连接失败',
                'detail' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    public function getDb() {
        return $this->db;
    }
    
    public function run() {
        // 简单的路由处理
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // 处理CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        
        if ($method == 'OPTIONS') {
            exit(0);
        }
        
        // API路由
        if (strpos($uri, '/api/contacts') !== false) {
            require APP_PATH . 'controller/ContactsController.php';
            $controller = new ContactsController();
            
            if ($method == 'GET') {
                if (isset($_GET['id'])) {
                    $controller->getContact($_GET['id']);
                } else {
                    $controller->getAllContacts();
                }
            } elseif ($method == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $controller->addContact($data);
            } elseif ($method == 'PUT') {
                $data = json_decode(file_get_contents('php://input'), true);
                $id = basename($uri);
                $controller->updateContact($id, $data);
            } elseif ($method == 'DELETE') {
                $id = basename($uri);
                $controller->deleteContact($id);
            }
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(['error' => '404 Not Found']);
        }
    }
}

// 运行应用
$app = ThinkPHP::getInstance();
$app->run();