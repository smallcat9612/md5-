<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHA1加密 - MD5在线加密解密工具</title>
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
                <i class="fas fa-code"></i>
                <h1>SHA1加密工具</h1>
                <span class="tagline">生成SHA1哈希值</span>
            </div>
            <div class="header-info">
                <div class="stat">
                    <i class="fas fa-database"></i>
                    <span>数据库记录: <strong>12.5亿</strong></span>
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
                <li class="active"><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密</a></li>
                <li><a href="mysql_encrypt.php"><i class="fas fa-database"></i> MySQL加密</a></li>
                <li><a href="help.php"><i class="fas fa-question-circle"></i> 帮助</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系</a></li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <main class="main-content">
                <div class="tool-card">
                    <div class="card-header">
                        <h2><i class="fas fa-code"></i> SHA1加密工具</h2>
                        <p>输入字符串生成SHA1哈希值</p>
                    </div>
                    
                    <div class="tool-controls">
                        <div class="input-section">
                            <label for="inputText"><i class="fas fa-pencil-alt"></i> 输入要加密的文本:</label>
                            <textarea id="inputText" placeholder="请输入要加密的字符串，例如：password123"></textarea>
                            <div class="input-actions">
                                <button id="encryptBtn" class="btn-primary">
                                    <i class="fas fa-lock"></i> 生成SHA1哈希
                                </button>
                                <button id="clearBtn" class="btn-outline">
                                    <i class="fas fa-trash"></i> 清空
                                </button>
                            </div>
                        </div>
                        
                        <div class="output-section">
                            <label for="outputText"><i class="fas fa-result"></i> SHA1哈希结果:</label>
                            <textarea id="outputText" readonly placeholder="SHA1哈希值将显示在这里..."></textarea>
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
                    <h3><i class="fas fa-info-circle"></i> SHA1加密说明</h3>
                    <ul>
                        <li><strong>SHA1简介</strong>: SHA-1（安全哈希算法1）是一种密码散列函数，可以产生一个160位（20字节）的散列值。</li>
                        <li><strong>不可逆性</strong>: SHA-1是单向加密算法，无法从哈希值反向推导出原始字符串。</li>
                        <li><strong>应用场景</strong>: 常用于数字签名、文件完整性验证等场景。</li>
                        <li><strong>安全性注意</strong>: 由于SHA-1存在碰撞漏洞，不推荐用于安全性要求极高的场景，建议使用SHA-256等更安全的算法。</li>
                        <li><strong>格式说明</strong>: SHA-1哈希值为40位十六进制字符串。</li>
                    </ul>
                </div>
            </main>
            
            <aside class="sidebar">
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-star"></i> 推荐
                    </div>
                    <div class="ad-content">
                        <h4>高级哈希服务</h4>
                        <p>支持更多哈希算法：SHA-256、SHA-512、bcrypt等。</p>
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
                        <h4>批量处理API</h4>
                        <p>提供批量SHA1加密API接口，适合大数据处理场景。</p>
                        <a href="api_docs.php" class="ad-button">API文档</a>
                    </div>
                    <div class="ad-footer">
                        <small>开发者服务</small>
                    </div>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 相关工具</h3>
                    <ul>
                        <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密工具</a></li>
                        <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密工具</a></li>
                        <li><a href="mysql_encrypt.php"><i class="fas fa-database"></i> MySQL加密工具</a></li>
                        <li><a href="batch_tool.php"><i class="fas fa-layer-group"></i> 批量处理工具</a></li>
                        <li><a href="api_docs.php"><i class="fas fa-code"></i> API文档</a></li>
                    </ul>
                </div>
            </aside>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-info-circle"></i> 关于SHA1加密</h4>
                    <p>SHA1加密工具提供安全、高效的哈希生成服务。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-shield-alt"></i> 安全提示</h4>
                    <p>SHA-1存在安全漏洞，不建议用于密码存储等安全敏感场景。请根据实际需求选择合适的加密算法。</p>
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
                showNotification('请输入要加密的文本！', 'warning');
                $('#inputText').focus();
                return;
            }
            
            performSHA1Encryption(inputText);
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
    
    function performSHA1Encryption(inputText) {
        $('#outputText').val('加密中...');
        
        $.ajax({
            url: 'process.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'encrypt',
                input: inputText,
                algorithm: 'sha1'
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
