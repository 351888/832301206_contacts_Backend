# 通讯录网页应用

这是一个前后端分离的通讯录网页应用，使用ThinkPHP框架作为后端，前端使用HTML、JS和CSS。

## 功能特点

1. 基本联系人功能
   - 添加联系人（姓名、电话、邮箱、地址）
   - 修改联系人信息
   - 删除联系人

2. 扩展功能
   - 联系人搜索功能
   - 响应式界面设计

## 项目结构

```
contacts-backend/             # 后端项目
├── app/                      # 应用目录
│   ├── controller/           # 控制器目录
│   │   └── ContactsController.php  # 联系人控制器
│   └── model/                # 模型目录
│       └── ContactModel.php  # 联系人模型
├── config/                   # 配置目录
│   └── config.php            # 配置文件
├── index.php                 # 入口文件
└── ThinkPHP.php              # 框架核心文件

contacts-frontend/            # 前端项目
├── css/                      # CSS样式目录
│   └── style.css             # 样式文件
├── js/                       # JavaScript目录
│   └── app.js                # 应用脚本
└── index.html                # 主页面
```

## 使用说明

### 后端设置

1. 确保您的服务器已安装PHP和MySQL
2. 将`contacts-backend`目录放置在您的Web服务器根目录下
3. 创建名为`contacts_db`的MySQL数据库
4. 根据需要修改`config/config.php`中的数据库连接信息

### 前端设置

1. 将`contacts-frontend`目录放置在您的Web服务器根目录下
2. 如果需要，请修改`js/app.js`中的`API_URL`变量，使其指向您的后端API地址

## 技术栈

- 后端：PHP、ThinkPHP框架、MySQL
- 前端：HTML5、CSS3、JavaScript (原生)

## 扩展功能说明

1. **联系人搜索功能**：实时搜索联系人，可以根据姓名、电话、邮箱或地址进行搜索
2. **响应式设计**：适配不同屏幕尺寸的设备
3. **数据验证**：前后端都实现了基本的数据验证

## 注意事项

- 本应用为前后端分离设计，API遵循RESTful风格
- 后端API支持CORS，允许跨域请求
- 前端使用纯原生JavaScript实现，无需额外框架
