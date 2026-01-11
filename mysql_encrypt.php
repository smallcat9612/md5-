<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL加密 - MD5在线加密解密工具</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-database"></i>
                <h1>MySQL加密工具</h1>
                <span class="tagline">生成MySQL风格的密码哈希</span>
            </div>
            <div class="header-info">
                <div class="stat">
                    <i class="fas fa-database"></i>
                    <span>算法版本: <strong>MySQL 4.1+</strong></span>
                </div>
                <div class="stat">
                    <i class="fas fa-bolt"></i>
                    <span>处理速度: <strong>极快</strong></span>
                </div>
            </div>
        </header>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> 首页</a></li>
                <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密</a></li>
                <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密</a></li>
                <li><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密</a></li>
                <li class="active"><a href="mysql_encrypt.php"><i class="fas fa-database"></i> MySQL加密</a></li>
                <li><a href="help.php"><i class="fas fa-question-circle"></i> 帮助</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系</a></li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <main class="main-content">
                <div class="tool-card">
                    <div class="card-header">
                        <h2><i class="fas fa-database"></i> MySQL加密工具</h2>
                        <p>生成MySQL风格的密码哈希值</p>
                    </div>
                    
                    <div class="tool-controls">
                        <div class="input-section">
                            <label for="inputText"><i class="fas fa-pencil-alt"></i> 输入要加密的密码:</label>
                            <textarea id="inputText" placeholder="请输入要加密的密码，例如：mypassword123"></textarea>
                            <div class="input-actions">
                                <button id="encryptBtn" class="btn-primary">
                                    <i class="fas fa-lock"></i> 生成MySQL哈希
                                </button>
                                <button id="clearBtn" class="btn-outline">
                                    <i class="fas fa-trash"></i> 清空
                                </button>
                            </div>
                        </div>
                        
                        <div class="output-section">
                            <label for="outputText"><i class="fas fa-result"></i> MySQL哈希结果:</label>
                            <textarea id="outputText" readonly placeholder="MySQL哈希值将显示在这里..."></textarea>
                            <div class="output-actions">
                                <button id="copyBtn" class="btn-success">
                                    <i class="fas fa-copy"></i> 复制结果
                                </button>
                                <button id="saveBtn" class="btn-outline">
                                    <i class="fas fa-save"></i> 保存结果
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tool-info">
                    <h3><i class="fas fa-info-circle"></i> MySQL加密说明</h3>
                    <ul>
                        <li><strong>MySQL密码哈希</strong>: MySQL使用特定的算法对密码进行哈希处理，存储到mysql.user表中。</li>
                        <li><strong>算法版本</strong>: MySQL 4.1+ 使用SHA1(SHA1(password))算法，并在结果前添加"*"字符。</li>
                        <li><strong>不可逆性</strong>: MySQL密码哈希是单向加密算法，无法从哈希值反向推导出原始密码。</li>
                        <li><strong>应用场景</strong>: 主要用于MySQL数据库用户密码存储和验证。</li>
                        <li><strong>格式说明</strong>: MySQL哈希值以"*"开头，后跟40位十六进制字符串（例如：*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9）。</li>
                        <li><strong>安全性注意</strong>: MySQL旧版本的哈希算法安全性较低，建议使用MySQL 8.0+的新验证方式（caching_sha2_password）。</li>
                    </ul>
                </div>
            </main>
            
            <aside class="sidebar">
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-star"></i> 推荐
                    </div>
                    <div class="ad-content">
                        <h4>数据库安全工具</h4>
                        <p>提供更多数据库加密工具：PostgreSQL、MongoDB等。</p>
                        <a href="#" class="ad-button">了解更多</a>
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
                        <h4>数据库管理工具</h4>
                        <p>专业的数据库管理软件，支持多种数据库类型。</p>
                        <a href="#" class="ad-button">免费试用</a>
                    </div>
                    <div class="ad-footer">
                        <small>开发者工具</small>
                    </div>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 相关工具</h3>
                    <ul>
                        <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密工具</a></li>
                        <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密工具</a></li>
                        <li><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密工具</a></li>
                        <li><a href="batch_tool.php"><i class="fas fa-layer-group"></i> 批量处理工具</a></li>
                        <li><a href="api_docs.php"><i class="fas fa-code"></i> API文档</a></li>
                    </ul>
                </div>
            </aside>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-info-circle"></i> 关于MySQL加密</h4>
                    <p>MySQL加密工具提供标准的MySQL密码哈希生成服务。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-shield-alt"></i> 安全提示</h4>
                    <p>MySQL旧版本哈希算法存在安全风险，建议在生产环境中使用更安全的密码存储方案。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-envelope"></i> 技术支持</h4>
                    <p>邮箱: support@md5tool.com</p>
                    <p>反馈: feedback@md5tool.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 MD5在线加密解密工具. 保留所有权利.</p>
                <p class="footer-note">专业哈希工具服务提供商</p>
            </div>
        </footer>
    </div>
    
    <div class="notification" id="notification">
        <span id="notificationText">操作成功！</span>
    </div>
    
    <script>
    $(document).ready(function() {
        $('#encryptBtn').click(function() {
            const inputText = $('#inputText').val().trim();
            
            if (!inputText) {
                showNotification('请输入要加密的密码！', 'warning');
                $('#inputText').focus();
                return;
            }
            
            performMySQLEncryption(inputText);
        });
        
        $('#clearBtn').click(function() {
            $('#inputText').val('');
            $('#outputText').val('');
            showNotification('已清空输入和输出', 'info');
        });
        
        $('#copyBtn').click(function() {
            const outputText = $('#outputText').val();
            
            if (!outputText) {
                showNotification('没有可复制的内容！', 'warning');
                return;
            }
            
            navigator.clipboard.writeText(outputText).then(function() {
                showNotification('结果已复制到剪贴板！', 'success');
            }, function(err) {
                const textArea = document.createElement('textarea');
                textArea.value = outputText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('结果已复制到剪贴板！', 'success');
            });
        });
    });
    
    function performMySQLEncryption(inputText) {
        $('#outputText').val('加密中...');
        
        $.ajax({
            url: 'process.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'encrypt',
                input: inputText,
                algorithm: 'mysql'
            },
            success: function(response) {
                if (response.success) {
                    $('#outputText').val(response.result);
                    showNotification('加密成功！查询耗时: ' + response.query_time, 'success');
                } else {
                    $('#outputText').val('错误: ' + response.message);
                    showNotification('加密失败: ' + response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                $('#outputText').val('请求失败: ' + error);
                showNotification('请求失败，请稍后重试', 'error');
            }
        });
    }
    
    function showNotification(message, type = 'info') {
        const notification = $('#notification');
        const notificationText = $('#notificationText');
        
        notificationText.text(message);
        notification.removeClass('info success warning error');
        notification.addClass(type);
        notification.addClass('show');
        
        setTimeout(function() {
            notification.removeClass('show');
        }, 3000);
    }
    </script>
</body>
</html>
