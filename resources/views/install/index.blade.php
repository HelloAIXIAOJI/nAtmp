<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统安装 - Nginx虚拟主机管理系统</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-container {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 2rem;
        }
        .info-box {
            background: #ecf0f1;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-box h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .info-box ul {
            margin-left: 1.5rem;
            color: #34495e;
        }
        .info-box li {
            margin-bottom: 0.5rem;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background: #27ae60;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.2s;
            font-weight: 600;
        }
        .btn:hover {
            background: #229954;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        code {
            background: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h1>🚀 Nginx虚拟主机管理系统</h1>
        <p class="subtitle">欢迎使用，请先完成系统安装</p>
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="info-box">
            <h3>安装前准备</h3>
            <ul>
                <li>确保数据库连接配置正确（.env文件）</li>
                <li>数据库用户需要有创建表的权限</li>
                <li>确保 storage 和 bootstrap/cache 目录可写</li>
            </ul>
        </div>
        
        <div class="info-box">
            <h3>安装内容</h3>
            <ul>
                <li>创建数据库表结构</li>
                <li>初始化系统设置</li>
                <li>配置文件将存储在 <code>/www/vhost/</code></li>
                <li>网站文件将存储在 <code>/www/hostfiles/</code></li>
            </ul>
        </div>
        
        <form action="{{ route('install.process') }}" method="POST">
            @csrf
            <button type="submit" class="btn">开始安装</button>
        </form>
    </div>
</body>
</html>
