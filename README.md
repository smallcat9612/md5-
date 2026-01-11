# MD5在线加密解密工具部署文档

## 项目概述

这是一个类似cmd5.com的MD5加密解密网站，使用PHP开发，支持多种哈希算法和批量处理功能。项目包含完整的网站界面、API接口和自动化数据生成工具。

## 功能特性

- **MD5加密/解密**：支持32位/16位大小写格式
- **多算法支持**：MD5、SHA1、SHA256、MySQL密码、Base64编码
- **批量处理**：支持批量加密和解密操作
- **API接口**：提供RESTful API供开发者调用
- **自动化数据生成**：内置数据生成器，可自动填充测试数据
- **双模式运行**：模拟模式（无外部依赖）和生产模式（ClickHouse+Redis）
- **响应式设计**：适配桌面和移动设备

## 系统要求

### 最低配置
- **操作系统**：Windows/Linux/macOS
- **Web服务器**：Apache/Nginx/IIS 或 PHP内置服务器
- **PHP版本**：PHP 5.6+（建议PHP 7.4+）
- **内存**：至少128MB RAM
- **磁盘空间**：至少10MB

### 推荐配置（生产环境）
- **操作系统**：Linux（Ubuntu/CentOS）
- **Web服务器**：Nginx + PHP-FPM
- **PHP版本**：PHP 7.4+
- **内存**：512MB RAM 或更高
- **数据库**：ClickHouse + Redis（用于大规模数据存储）
- **磁盘空间**：根据数据量调整

## 快速开始

### 1. 下载项目文件
```bash
# 克隆或下载项目到web目录
cd /var/www/html
git clone <repository-url> md5tool
cd md5tool
```

### 2. 检查PHP环境
```bash
# 检查PHP是否安装
php -v

# 如果未安装PHP，根据系统安装
# Ubuntu/Debian
sudo apt update
sudo apt install php php-cli

# CentOS/RHEL
sudo yum install php php-cli
```

### 3. 启动开发服务器
```bash
# 进入项目目录
cd md5tool

# 使用PHP内置服务器启动（开发环境）
php -S localhost:8000

# 在浏览器中访问
# http://localhost:8000
```

## 详细部署步骤

### 方案一：使用PHP内置服务器（最简单）

1. **下载项目文件**
   ```bash
   # 假设项目文件在f:\jiemi目录
   cd f:\jiemi
   ```

2. **启动服务器**
   ```bash
   # 在项目根目录执行
   php -S localhost:8000
   ```

3. **访问网站**
   - 打开浏览器，访问：`http://localhost:8000`
   - 首页：`http://localhost:8000/index.php`
   - MD5加密：`http://localhost:8000/md5_encrypt.php`
   - MD5解密：`http://localhost:8000/md5_decrypt.php`

### 方案二：Apache服务器部署

1. **安装Apache和PHP**
   ```bash
   # Ubuntu/Debian
   sudo apt update
   sudo apt install apache2 php libapache2-mod-php

   # CentOS/RHEL
   sudo yum install httpd php php-cli
   ```

2. **配置Apache**
   ```bash
   # 将项目文件复制到web目录
   sudo cp -r f:\jiemi /var/www/html/md5tool

   # 设置权限
   sudo chown -R www-data:www-data /var/www/html/md5tool
   sudo chmod -R 755 /var/www/html/md5tool

   # 重启Apache
   sudo systemctl restart apache2  # Ubuntu/Debian
   sudo systemctl restart httpd    # CentOS/RHEL
   ```

3. **访问网站**
   - 打开浏览器，访问：`http://your-server-ip/md5tool`

### 方案三：Nginx服务器部署

1. **安装Nginx和PHP-FPM**
   ```bash
   # Ubuntu/Debian
   sudo apt update
   sudo apt install nginx php-fpm

   # CentOS/RHEL
   sudo yum install nginx php-fpm
   ```

2. **配置Nginx**
   ```bash
   # 创建Nginx配置文件
   sudo nano /etc/nginx/sites-available/md5tool
   ```

   添加以下配置：
   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       root /var/www/html/md5tool;
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$args;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```

3. **启用站点并重启**
   ```bash
   sudo ln -s /etc/nginx/sites-available/md5tool /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl restart nginx
   ```

## 配置说明

### 运行模式配置

项目支持两种运行模式：

1. **模拟模式**（默认）
   - 使用PHP数组模拟数据库
   - 无需外部服务依赖
   - 适合开发和测试
   - 配置：修改`config.php`中的`RUN_MODE`为`'simulation'`

2. **生产模式**
   - 使用ClickHouse + Redis架构
   - 支持千亿级别数据量
   - 需要安装和配置外部服务
   - 配置：修改`config.php`中的`RUN_MODE`为`'production'`

### 数据库配置（生产模式）

如果需要使用生产模式，需要配置以下服务：

#### ClickHouse安装
```bash
# Ubuntu/Debian
sudo apt-get install apt-transport-https ca-certificates dirmngr
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv E0C56BD4
echo "deb https://repo.clickhouse.com/deb/stable/ main/" | sudo tee /etc/apt/sources.list.d/clickhouse.list
sudo apt-get update
sudo apt-get install clickhouse-server clickhouse-client

# 启动ClickHouse
sudo service clickhouse-server start
```

#### Redis安装
```bash
# Ubuntu/Debian
sudo apt install redis-server

# CentOS/RHEL
sudo yum install redis

# 启动Redis
sudo systemctl start redis
```

### 配置文件说明

主要配置文件：`config.php`

```php
// 运行模式：simulation（模拟）或 production（生产）
define('RUN_MODE', 'simulation');

// ClickHouse配置（生产模式使用）
define('CLICKHOUSE_HOST', 'localhost');
define('CLICKHOUSE_PORT', 8123);
define('CLICKHOUSE_USER', 'default');
define('CLICKHOUSE_PASSWORD', '');
define('CLICKHOUSE_DATABASE', 'hash_database');

// Redis配置（生产模式使用）
define('REDIS_HOST', 'localhost');
define('REDIS_PORT', 6379);
define('REDIS_PASSWORD', '');
define('REDIS_DATABASE', 0);
```

## 工具脚本使用

### 1. 简单数据插入工具
```bash
# 生成1-9的数字MD5加密数据
php simple_insert.php --start=1 --end=9 --algorithm=md5

# 生成1-100的数字，使用SHA1算法
php simple_insert.php --start=1 --end=100 --algorithm=sha1

# 查看帮助
php simple_insert.php --help
```

### 2. 批量数据生成器
```bash
# 生成100条测试数据
php data_generator.php --count=100 --algorithm=md5

# 生成500条SHA1数据，使用模拟模式
php data_generator.php --count=500 --algorithm=sha1 --mode=simulation

# 查看帮助
php data_generator.php --help
```

### 3. 批量插入工具
```bash
# 生成1-1000的数字并插入
php batch_insert.php --start=1 --end=1000 --algorithm=md5

# 使用生产模式插入数据
php batch_insert.php --start=1000 --end=2000 --mode=production
```

## API接口使用

### API文档地址
```
http://your-domain.com/api_docs.php
```

### 主要API接口

1. **加密接口**
   ```
   POST /process.php
   参数：
     action=encrypt
     input=要加密的字符串
     algorithm=算法类型（md5, sha1等）
   ```

2. **解密接口**
   ```
   POST /process.php
   参数：
     action=decrypt
     input=要解密的哈希值
     algorithm=算法类型
   ```

3. **批量查询接口**
   ```
   POST /process.php
   参数：
     action=batch_decrypt
     hashes=JSON格式的哈希数组
     algorithm=算法类型
   ```

4. **统计接口**
   ```
   GET /process.php?admin=stats
   ```

## 网站结构

```
md5tool/
├── index.php              # 首页
├── md5_encrypt.php       # MD5加密工具
├── md5_decrypt.php       # MD5解密工具
├── sha1_encrypt.php      # SHA1加密工具
├── mysql_encrypt.php     # MySQL加密工具
├── api_docs.php          # API文档
├── help.php              # 帮助中心
├── contact.php           # 联系页面（框架）
├── process.php           # 后端处理API
├── Database.php          # 数据库操作类
├── config.php            # 配置文件
├── style.css             # 样式文件
├── script.js             # 前端脚本
├── data_generator.php    # 数据生成器
├── batch_insert.php      # 批量插入工具
├── simple_insert.php     # 简单插入工具
├── logs/                 # 日志目录
│   ├── queries.log      # 查询日志
│   └── errors.log       # 错误日志
└── data/                 # 数据目录（可选）
```

## 故障排除

### 常见问题

1. **PHP版本不兼容**
   ```
   错误信息：Parse error: syntax error, unexpected '?'
   解决方案：确保PHP版本在5.6以上，建议使用PHP 7.0+
   ```

2. **权限问题**
   ```
   错误信息：Permission denied
   解决方案：确保web目录有正确的读写权限
   chmod -R 755 /var/www/html/md5tool
   chown -R www-data:www-data /var/www/html/md5tool
   ```

3. **数据库连接失败**（生产模式）
   ```
   错误信息：Could not connect to ClickHouse/Redis
   解决方案：
   1. 检查服务是否启动
   2. 检查防火墙设置
   3. 验证配置文件中的连接参数
   ```

4. **中文乱码**
   ```
   解决方案：确保文件使用UTF-8编码，并在HTML中添加
   <meta charset="UTF-8">
   ```

### 日志查看

项目会自动记录错误和查询日志：
```bash
# 查看错误日志
tail -f logs/errors.log

# 查看查询日志
tail -f logs/queries.log
```

## 安全建议

1. **生产环境配置**
   - 修改默认配置参数
   - 使用强密码保护数据库
   - 启用HTTPS加密传输
   - 定期备份数据

2. **访问控制**
   - 限制API调用频率
   - 设置IP白名单（如需要）
   - 监控异常访问模式

3. **数据安全**
   - 定期清理日志文件
   - 加密敏感配置信息
   - 使用安全的会话管理

## 性能优化

1. **启用缓存**
   - 配置Redis缓存策略
   - 使用OPcache加速PHP
   - 启用浏览器缓存

2. **数据库优化**
   - 为ClickHouse表创建合适的分区
   - 建立索引优化查询性能
   - 定期清理过期数据

3. **代码优化**
   - 启用PHP的OPcache扩展
   - 压缩CSS和JavaScript文件
   - 使用CDN加速静态资源

## 更新和维护

### 更新项目
```bash
# 如果有使用版本控制
cd /var/www/html/md5tool
git pull origin master

# 如果没有版本控制，手动备份后替换文件
cp -r /var/www/html/md5tool /backup/md5tool_backup
# 然后上传新文件
```

### 日常维护
1. **监控日志**：定期检查错误日志和查询日志
2. **备份数据**：定期备份数据库和配置文件
3. **更新软件**：及时更新PHP、数据库等依赖软件
4. **安全检查**：定期进行安全扫描和漏洞检查

## 技术支持

- **问题反馈**：通过contact.php页面提交问题
- **文档更新**：查看help.php获取最新帮助信息
- **API支持**：参考api_docs.php了解API使用方法

## 许可证

本项目仅供学习和研究使用，请遵守相关法律法规。

---

*最后更新：2026年1月12日*
*文档版本：v1.0*
