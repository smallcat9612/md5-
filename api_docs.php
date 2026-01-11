<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API文档 - MD5在线加密解密工具</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
    <style>
        .api-section {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }
        
        .api-method {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.9rem;
            margin-right: 10px;
        }
        
        .method-get {
            background-color: #61affe;
            color: white;
        }
        
        .method-post {
            background-color: #49cc90;
            color: white;
        }
        
        .api-url {
            background-color: #f6f6f6;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Consolas', 'Monaco', monospace;
            margin: 15px 0;
            border-left: 4px solid #1a73e8;
        }
        
        .code-block {
            background-color: #282c34;
            color: #abb2bf;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9rem;
            margin: 15px 0;
            overflow-x: auto;
        }
        
        .param-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .param-table th, .param-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .param-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .example-request {
            margin: 20px 0;
        }
        
        .example-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-code"></i>
                <h1>API文档</h1>
                <span class="tagline">MD5加密解密API接口</span>
            </div>
            <div class="header-info">
                <div class="stat">
                    <i class="fas fa-bolt"></i>
                    <span>响应速度: <strong>极快</strong></span>
                </div>
                <div class="stat">
                    <i class="fas fa-shield-alt"></i>
                    <span>安全性: <strong>HTTPS支持</strong></span>
                </div>
            </div>
        </header>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> 首页</a></li>
                <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密</a></li>
                <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密</a></li>
                <li><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密</a></li>
                <li><a href="mysql_encrypt.php"><i class="fas fa-database"></i> MySQL加密</a></li>
                <li><a href="help.php"><i class="fas fa-question-circle"></i> 帮助</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系</a></li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <main class="main-content">
                <div class="api-section">
                    <h2><i class="fas fa-book"></i> API概述</h2>
                    <p>MD5在线加密解密工具提供RESTful API接口，支持MD5加密、解密以及多种哈希算法。所有API均返回JSON格式数据。</p>
                    
                    <div class="api-info">
                        <p><strong>基础URL:</strong> <code>https://api.md5tool.com/v1/</code> (正式环境) 或 <code>http://localhost:8000/process.php</code> (测试环境)</p>
                        <p><strong>响应格式:</strong> JSON</p>
                        <p><strong>字符编码:</strong> UTF-8</p>
                        <p><strong>速率限制:</strong> 1000次/小时 (免费版), 10000次/小时 (专业版)</p>
                    </div>
                </div>
                
                <div class="api-section">
                    <h2><i class="fas fa-key"></i> 加密API</h2>
                    <p>将字符串加密为MD5或其他哈希算法。</p>
                    
                    <div class="api-method method-post">POST</div>
                    <div class="api-url">
                        POST /process.php?action=encrypt
                    </div>
                    
                    <h3>请求参数</h3>
                    <table class="param-table">
                        <tr>
                            <th>参数</th>
                            <th>类型</th>
                            <th>必需</th>
                            <th>说明</th>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>string</td>
                            <td>是</td>
                            <td>固定值: "encrypt"</td>
                        </tr>
                        <tr>
                            <td>input</td>
                            <td>string</td>
                            <td>是</td>
                            <td>要加密的字符串</td>
                        </tr>
                        <tr>
                            <td>algorithm</td>
                            <td>string</td>
                            <td>否</td>
                            <td>算法类型，默认: "md5"。可选值: md5, md5-upper, md5-16, md5-16-upper, sha1, sha256, mysql, base64</td>
                        </tr>
                    </table>
                    
                    <h3>响应示例</h3>
                    <div class="example-request">
                        <div class="example-title">请求:</div>
                        <div class="code-block">
POST /process.php
Content-Type: application/x-www-form-urlencoded

action=encrypt&input=123456&algorithm=md5
                        </div>
                        
                        <div class="example-title">响应:</div>
                        <div class="code-block">
{
  "success": true,
  "message": "加密成功",
  "result": "e10adc3949ba59abbe56e057f20f883e",
  "algorithm": "md5",
  "timestamp": "2023-12-01 14:30:25",
  "query_time": "12.34ms",
  "mode": "simulation"
}
                        </div>
                    </div>
                </div>
                
                <div class="api-section">
                    <h2><i class="fas fa-unlock"></i> 解密API</h2>
                    <p>查询MD5哈希对应的原始字符串。</p>
                    
                    <div class="api-method method-post">POST</div>
                    <div class="api-url">
                        POST /process.php?action=decrypt
                    </div>
                    
                    <h3>请求参数</h3>
                    <table class="param-table">
                        <tr>
                            <th>参数</th>
                            <th>类型</th>
                            <th>必需</th>
                            <th>说明</th>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>string</td>
                            <td>是</td>
                            <td>固定值: "decrypt"</td>
                        </tr>
                        <tr>
                            <td>input</td>
                            <td>string</td>
                            <td>是</td>
                            <td>要解密的MD5哈希值</td>
                        </tr>
                        <tr>
                            <td>algorithm</td>
                            <td>string</td>
                            <td>否</td>
                            <td>算法类型，默认: "md5"。可选值: md5, md5-upper, md5-16, md5-16-upper</td>
                        </tr>
                    </table>
                    
                    <h3>响应示例</h3>
                    <div class="example-request">
                        <div class="example-title">请求:</div>
                        <div class="code-block">
POST /process.php
Content-Type: application/x-www-form-urlencoded

action=decrypt&input=e10adc3949ba59abbe56e057f20f883e&algorithm=md5
                        </div>
                        
                        <div class="example-title">响应:</div>
                        <div class="code-block">
{
  "success": true,
  "message": "解密成功",
  "result": "原始字符串: 123456",
  "algorithm": "md5",
  "timestamp": "2023-12-01 14:30:25",
  "query_time": "8.45ms",
  "found": true,
  "original_string": "123456",
  "source": "mock",
  "mode": "simulation"
}
                        </div>
                    </div>
                </div>
                
                <div class="api-section">
                    <h2><i class="fas fa-layer-group"></i> 批量解密API</h2>
                    <p>批量查询多个MD5哈希值对应的原始字符串。</p>
                    
                    <div class="api-method method-post">POST</div>
                    <div class="api-url">
                        POST /process.php?action=batch_decrypt
                    </div>
                    
                    <h3>请求参数</h3>
                    <table class="param-table">
                        <tr>
                            <th>参数</th>
                            <th>类型</th>
                            <th>必需</th>
                            <th>说明</th>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>string</td>
                            <td>是</td>
                            <td>固定值: "batch_decrypt"</td>
                        </tr>
                        <tr>
                            <td>hashes</td>
                            <td>JSON array</td>
                            <td>是</td>
                            <td>要解密的MD5哈希值数组，JSON格式</td>
                        </tr>
                        <tr>
                            <td>algorithm</td>
                            <td>string</td>
                            <td>否</td>
                            <td>算法类型，默认: "md5"</td>
                        </tr>
                    </table>
                    
                    <h3>响应示例</h3>
                    <div class="example-request">
                        <div class="example-title">请求:</div>
                        <div class="code-block">
POST /process.php
Content-Type: application/x-www-form-urlencoded

action=batch_decrypt&hashes=["e10adc3949ba59abbe56e057f20f883e","21232f297a57a5a743894a0e4a801fc3"]&algorithm=md5
                        </div>
                        
                        <div class="example-title">响应:</div>
                        <div class="code-block">
{
  "success": true,
  "message": "批量查询完成",
  "algorithm": "md5",
  "timestamp": "2023-12-01 14:30:25",
  "query_time": "25.67ms",
  "total": 2,
  "found": 2,
  "results": [
    {
      "found": true,
      "original_string": "123456",
      "hash": "e10adc3949ba59abbe56e057f20f883e",
      "algorithm": "md5",
      "source": "mock"
    },
    {
      "found": true,
      "original_string": "admin",
      "hash": "21232f297a57a5a743894a0e4a801fc3",
      "algorithm": "md5",
      "source": "mock"
    }
  ]
}
                        </div>
                    </div>
                </div>
                
                <div class="api-section">
                    <h2><i class="fas fa-chart-bar"></i> 统计信息API</h2>
                    <p>获取系统统计信息。</p>
                    
                    <div class="api-method method-get">GET</div>
                    <div class="api-url">
                        GET /process.php?admin=stats
                    </div>
                    
                    <h3>响应示例</h3>
                    <div class="code-block">
{
  "success": true,
  "message": "统计信息获取成功",
  "timestamp": "2023-12-01 14:30:25",
  "mode": "simulation",
  "stats": {
    "total_records": 20,
    "today_queries": 157,
    "successful_decrypts": 123,
    "cache_hit_rate": "0%",
    "avg_query_time": "0.1秒",
    "database_size": "模拟模式",
    "last_updated": "2023-12-01 14:30:25"
  }
}
                    </div>
                </div>
                
                <div class="api-section">
                    <h2><i class="fas fa-code"></i> 客户端代码示例</h2>
                    
                    <h3>JavaScript (jQuery)</h3>
                    <div class="code-block">
// MD5加密
$.ajax({
    url: 'process.php',
    method: 'POST',
    dataType: 'json',
    data: {
        action: 'encrypt',
        input: 'password123',
        algorithm: 'md5'
    },
    success: function(response) {
        if (response.success) {
            console.log('MD5哈希:', response.result);
        }
    }
});

// MD5解密
$.ajax({
    url: 'process.php',
    method: 'POST',
    dataType: 'json',
    data: {
        action: 'decrypt',
        input: '482c811da5d5b4bc6d497ffa98491e38',
        algorithm: 'md5'
    },
    success: function(response) {
        if (response.success) {
            console.log('原始字符串:', response.original_string);
        }
    }
});
                    </div>
                    
                    <h3>Python</h3>
                    <div class="code-block">
import requests
import json

# MD5加密
response = requests.post('http://localhost:8000/process.php', data={
    'action': 'encrypt',
    'input': 'password123',
    'algorithm': 'md5'
})
result = response.json()
if result['success']:
    print(f"MD5哈希: {result['result']}")

# MD5解密
response = requests.post('http://localhost:8000/process.php', data={
    'action': 'decrypt',
    'input': '482c811da5d5b4bc6d497ffa98491e38',
    'algorithm': 'md5'
})
result = response.json()
if result['success']:
    print(f"原始字符串: {result['original_string']}")
                    </div>
                    
                    <h3>PHP</h3>
                    <div class="code-block">
// MD5加密
$data = [
    'action' => 'encrypt',
    'input' => 'password123',
    'algorithm' => 'md5'
];

$ch = curl_init('http://localhost:8000/process.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "MD5哈希: " . $result['result'];
}

// MD5解密
$data = [
    'action' => 'decrypt',
    'input' => '482c811da5d5b4bc6d497ffa98491e38',
    'algorithm' => 'md5'
];

$ch = curl_init('http://localhost:8000/process.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "原始字符串: " . $result['original_string'];
}
                    </div>
                </div>
            </main>
            
            <aside class="sidebar">
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-star"></i> 推荐
                    </div>
                    <div class="ad-content">
                        <h4>专业API服务</h4>
                        <p>解锁更高API调用限制，获得专属技术支持。</p>
                        <a href="#" class="ad-button">立即升级</a>
                    </div>
                    <div class="ad-footer">
                        <small>高级功能</small>
                    </div>
                </div>
                
                <div class="advertisement">
                    <div class="ad-label">
                        <i class="fas fa-ad"></i> 广告
                    </div>
                    <div class="ad-content">
                        <h4>开发者工具包</h4>
                        <p>提供多语言SDK，简化API集成流程。</p>
                        <a href="#" class="ad-button">下载SDK</a>
                    </div>
                    <div class="ad-footer">
                        <small>开发者服务</small>
                    </div>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-cogs"></i> API状态</h3>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: #34a853; font-weight: bold;">在线</div>
                        <div style="color: #666; font-size: 0.9rem;">服务状态正常</div>
                        
                        <div style="display: flex; justify-content: space-around; margin-top: 20px;">
                            <div>
                                <div style="font-size: 1.5rem; color: #1a73e8;">99.9%</div>
                                <div style="color: #666; font-size: 0.8rem;">可用性</div>
                            </div>
                            <div>
                                <div style="font-size: 1.5rem; color: #ff9800;">0.02s</div>
                                <div style="color: #666; font-size: 0.8rem;">平均响应</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 快速链接</h3>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> 首页</a></li>
                        <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密工具</a></li>
                        <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密工具</a></li>
                        <li><a href="help.php"><i class="fas fa-question-circle"></i> 帮助文档</a></li>
                        <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系我们</a></li>
                    </ul>
                </div>
            </aside>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-info-circle"></i> 关于API</h4>
                    <p>我们提供稳定、高效的API接口，支持多种编程语言调用。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-shield-alt"></i> 使用条款</h4>
                    <p>API调用请遵守使用条款，禁止滥用和非法用途。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-envelope"></i> 技术支持</h4>
                    <p>邮箱: api-support@md5tool.com</p>
                    <p>文档: docs.md5tool.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 MD5在线加密解密工具. 保留所有权利.</p>
                <p class="footer-note">专业哈希工具服务提供商</p>
            </div>
        </footer>
    </div>
</body>
</html>
