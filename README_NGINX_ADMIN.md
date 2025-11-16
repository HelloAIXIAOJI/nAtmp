# Nginx虚拟主机管理系统

基于Laravel 8、Nginx和PHP构建的虚拟主机管理系统。

## 功能特性

### 核心功能
- ✅ **用户注册与登录** - 完整的身份验证系统
- ✅ **网站创建管理** - 创建和管理多个虚拟主机
- ✅ **域名绑定** - 为每个网站绑定多个域名，支持主域名设置
- ✅ **文件管理** - 上传、下载和删除网站文件
- ✅ **系统安装** - 可视化安装向导
- ✅ **数据库迁移** - 可视化执行数据库迁移和回滚

### 系统架构
- 每个网站都有唯一的数字ID
- Nginx配置文件存储在 `/www/vhost/{网站ID}.conf`
- 网站文件存储在 `/www/hostfiles/{网站ID}/`
- 数据库仅维护网站ID与用户的关系，不存储配置内容

## 安装步骤

### 1. 环境要求
- PHP >= 7.3
- MySQL/MariaDB
- Composer
- Nginx
- Linux服务器（生产环境）

### 2. 配置数据库
编辑 `.env` 文件：
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. 安装依赖
```bash
composer install
```

### 4. 生成应用密钥
```bash
php artisan key:generate
```

### 5. 设置目录权限
```bash
chmod -R 755 storage bootstrap/cache
```

### 6. 访问安装页面
在浏览器中访问：`http://your-domain/install`

按照安装向导完成系统安装。

## 使用说明

### 注册账户
1. 访问注册页面 `/register`
2. 填写用户名、邮箱和密码
3. 提交注册

### 创建网站
1. 登录后进入仪表盘
2. 点击"创建新网站"
3. 填写网站名称和描述
4. 系统会自动生成网站ID和配置文件

### 绑定域名
1. 进入网站详情页
2. 点击"添加域名"
3. 输入域名（如 example.com）
4. 可选择设为主域名
5. 提交后会自动更新Nginx配置

### 上传文件
1. 进入网站的"文件管理"
2. 选择要上传的文件（支持多选）
3. 点击"上传"
4. 文件会存储到对应的网站目录

### 系统更新
1. 进入"系统更新"页面
2. 查看待执行的迁移
3. 点击"执行迁移"应用数据库更新
4. 如需回滚，点击"回滚迁移"

## 目录结构

```
nginxAdmin/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php          # 认证控制器
│   │       ├── DashboardController.php     # 仪表盘
│   │       ├── WebsiteController.php       # 网站管理
│   │       ├── DomainController.php        # 域名管理
│   │       ├── FileController.php          # 文件管理
│   │       ├── InstallController.php       # 安装控制器
│   │       └── UpdateController.php        # 更新控制器
│   ├── Models/
│   │   ├── User.php                        # 用户模型
│   │   ├── Website.php                     # 网站模型
│   │   ├── Domain.php                      # 域名模型
│   │   └── SystemSetting.php               # 系统设置模型
│   └── Policies/
│       └── WebsitePolicy.php               # 网站授权策略
├── database/
│   └── migrations/
│       ├── 2025_01_01_000001_create_websites_table.php
│       ├── 2025_01_01_000002_create_domains_table.php
│       └── 2025_01_01_000003_create_system_settings_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # 主布局
│       ├── auth/
│       │   ├── login.blade.php             # 登录页
│       │   └── register.blade.php          # 注册页
│       ├── websites/
│       │   ├── index.blade.php             # 网站列表
│       │   ├── create.blade.php            # 创建网站
│       │   ├── show.blade.php              # 网站详情
│       │   └── edit.blade.php              # 编辑网站
│       ├── files/
│       │   └── index.blade.php             # 文件管理
│       ├── install/
│       │   └── index.blade.php             # 安装页面
│       ├── update/
│       │   └── index.blade.php             # 更新页面
│       └── dashboard.blade.php             # 仪表盘
└── routes/
    └── web.php                             # Web路由
```

## 数据库表结构

### users 表
- id: 用户ID
- name: 用户名
- email: 邮箱
- password: 密码（加密）
- created_at, updated_at: 时间戳

### websites 表
- id: 网站ID（数字，对应conf文件名）
- user_id: 所属用户
- name: 网站名称
- description: 描述
- status: 状态（active/inactive/suspended）
- created_at, updated_at: 时间戳

### domains 表
- id: 域名ID
- website_id: 所属网站
- domain: 域名
- is_primary: 是否主域名
- created_at, updated_at: 时间戳

### system_settings 表
- id: 设置ID
- key: 设置键
- value: 设置值
- created_at, updated_at: 时间戳

## Nginx配置示例

系统自动生成的配置文件格式：

```nginx
server {
    listen 80;
    server_name example.com www.example.com;
    
    root /www/hostfiles/{网站ID};
    index index.php index.html index.htm;
    
    access_log /var/log/nginx/{网站ID}_access.log;
    error_log /var/log/nginx/{网站ID}_error.log;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

## 生产部署

### 1. 创建必要的目录
```bash
sudo mkdir -p /www/vhost
sudo mkdir -p /www/hostfiles
sudo chown -R www-data:www-data /www
```

### 2. 配置Nginx主配置
在 `/etc/nginx/nginx.conf` 中添加：
```nginx
include /www/vhost/*.conf;
```

### 3. 配置PHP-FPM
确保PHP-FPM运行并配置正确的socket路径。

### 4. 设置文件权限
```bash
sudo chown -R www-data:www-data /var/www/html/nginxAdmin
sudo chmod -R 755 /var/www/html/nginxAdmin
```

### 5. 优化Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 安全建议

1. **修改默认密码复杂度** - 在生产环境增强密码策略
2. **启用HTTPS** - 使用Let's Encrypt配置SSL证书
3. **限制文件上传大小** - 根据需求调整上传限制
4. **定期备份数据库** - 设置自动备份计划
5. **监控系统日志** - 定期检查Nginx和PHP错误日志
6. **防火墙配置** - 仅开放必要端口
7. **更新系统组件** - 定期更新PHP、Nginx和Laravel

## 开发说明

本系统在开发环境中：
- 文件存储使用Laravel的storage系统
- Nginx配置文件仅生成内容，不实际写入
- 不执行系统级命令，适合在Windows开发环境测试

部署到Linux生产环境时，需要：
- 修改FileController实际操作Linux文件系统
- 修改WebsiteController实际写入Nginx配置文件
- 添加Nginx重载功能（nginx -s reload）

## 技术栈

- **后端框架**: Laravel 8
- **Web服务器**: Nginx
- **数据库**: MySQL/MariaDB
- **PHP版本**: 7.3+
- **前端**: 原生HTML/CSS/JavaScript（无依赖）

## 许可证

MIT License

## 作者

开发用于虚拟主机管理和Nginx配置自动化。
