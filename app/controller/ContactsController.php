<?php
// 联系人控制器
class ContactsController {
    private $db;
    private $model;
    
    public function __construct() {
        $app = ThinkPHP::getInstance();
        $this->db = $app->getDb();
        
        require APP_PATH . 'model/ContactModel.php';
        $this->model = new ContactModel($this->db);
    }
    
    // 获取所有联系人
    public function getAllContacts() {
        $contacts = $this->model->getAll();
        $this->jsonResponse($contacts);
    }
    
    // 获取单个联系人
    public function getContact($id) {
        $contact = $this->model->getById($id);
        if ($contact) {
            $this->jsonResponse($contact);
        } else {
            $this->jsonResponse(['error' => '联系人不存在'], 404);
        }
    }
    
    // 添加联系人
    public function addContact($data) {
        // 验证数据
        if (empty($data['name']) || empty($data['phone'])) {
            $this->jsonResponse(['error' => '姓名和电话不能为空'], 400);
            return;
        }
        
        $result = $this->model->add($data);
        if ($result) {
            $this->jsonResponse(['message' => '联系人添加成功', 'id' => $result], 201);
        } else {
            $this->jsonResponse(['error' => '添加联系人失败'], 500);
        }
    }
    
    // 更新联系人
    public function updateContact($id, $data) {
        // 验证数据
        if (empty($data['name']) || empty($data['phone'])) {
            $this->jsonResponse(['error' => '姓名和电话不能为空'], 400);
            return;
        }
        
        $result = $this->model->update($id, $data);
        if ($result) {
            $this->jsonResponse(['message' => '联系人更新成功']);
        } else {
            $this->jsonResponse(['error' => '更新联系人失败'], 500);
        }
    }
    
    // 删除联系人
    public function deleteContact($id) {
        $result = $this->model->delete($id);
        if ($result) {
            $this->jsonResponse(['message' => '联系人删除成功']);
        } else {
            $this->jsonResponse(['error' => '删除联系人失败'], 500);
        }
    }
    
    // JSON响应
    private function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}