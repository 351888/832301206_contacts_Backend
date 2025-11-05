<?php
// 联系人模型
class ContactModel {
    private $db;
    private $table = 'contacts';
    
    public function __construct($db) {
        $this->db = $db;
        $this->createTable();
    }
    
    // 创建联系人表
    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            address VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        try {
            $this->db->exec($sql);
            // 如果表为空，插入示例数据
            $count = (int)$this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
            if ($count === 0) {
                $seed = [
                    ['张三', '13800138000', 'zhangsan@example.com', '北京市海淀区'],
                    ['李四', '13900139000', 'lisi@example.com', '上海市浦东新区'],
                    ['王五', '13700137000', 'wangwu@example.com', '广州市天河区'],
                ];
                $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, phone, email, address) VALUES (?, ?, ?, ?)");
                foreach ($seed as $row) {
                    $stmt->execute($row);
                }
            }
        } catch (PDOException $e) {
            die("创建表失败: " . $e->getMessage());
        }
    }
    
    // 获取所有联系人
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 根据ID获取联系人
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 添加联系人
    public function add($data) {
        $sql = "INSERT INTO {$this->table} (name, phone, email, address) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $email = isset($data['email']) ? $data['email'] : null;
        $address = isset($data['address']) ? $data['address'] : null;
        
        $result = $stmt->execute([
            $data['name'],
            $data['phone'],
            $email,
            $address
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    // 更新联系人
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET name = ?, phone = ?, email = ?, address = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        $email = isset($data['email']) ? $data['email'] : null;
        $address = isset($data['address']) ? $data['address'] : null;
        
        return $stmt->execute([
            $data['name'],
            $data['phone'],
            $email,
            $address,
            $id
        ]);
    }
    
    // 删除联系人
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}