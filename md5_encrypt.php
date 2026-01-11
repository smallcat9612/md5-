<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5加密 - MD5在线加密解密工具</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
    <style>
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature-item {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 1.5rem;
        }
        
        .batch-input {
            width: 100%;
            min-height: 200px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-family: 'Consolas', 'Monaco', monospace;
            margin-bottom: 15px;
        }
        
        .batch-results {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .batch-result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-lock"></i>
                <h1>MD5加密工具</h1>
                <span class="tagline">安全、高效的MD5哈希生成</span>
            </div>
            <div class="header-info">
                <div class="stat">
                    <i class="fas fa-database"></i>
                    <span>数据库记录: <strong>加载中...</strong></span>
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
                <li class="active"><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密</a></li>
                <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密</a></li>
                <li><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密</a></li>
                <li><a href="mysql_encrypt.php"><i class="fas fa-database"></i> MySQL加密</a></li>
                <li><a href="help.php"><i class="fas fa-question-circle"></i> 帮助</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系</a></li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <main class="main-content">
                <div class="tool-card">
                    <div class="card-header">
                        <h2><i class="fas fa-key"></i> MD5加密工具</h2>
                        <p>输入字符串生成MD5哈希值，支持多种格式和批量处理</p>
                    </div>
                    
                    <div class="tool-controls">
                        <div class="algorithm-selector">
                            <label for="algorithm"><i class="fas fa-cogs"></i> 选择MD5格式:</label>
                            <select id="algorithm">
                                <option value="md5">MD5 (32位小写)</option>
                                <option value="md5-upper">MD5 (32位大写)</option>
                                <option value="md5-16">MD5 (16位小写)</option>
                                <option value="md5-16-upper">MD5 (16位大写)</option>
                            </select>
                        </div>
                        
                        <div class="input-section">
                            <label for="inputText"><i class="fas fa-pencil-alt"></i> 输入要加密的文本:</label>
                            <textarea id="inputText" placeholder="请输入要加密的字符串，例如：password123"></textarea>
                            <div class="input-actions">
                                <button id="encryptBtn" class="btn-primary">
                                    <i class="fas fa-lock"></i> 生成MD5哈希
                                </button>
                                <button id="clearBtn" class="btn-outline">
                                    <i class="fas fa-trash"></i> 清空
                                </button>
                            </div>
                        </div>
                        
                        <div class="output-section">
                            <label for="outputText"><i class="fas fa-result"></i> MD5哈希结果:</label>
                            <textarea id="outputText" readonly placeholder="MD5哈希值将显示在这里..."></textarea>
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
                
                <div class="tool-card">
                    <div class="card-header">
                        <h2><i class="fas fa-batch"></i> 批量MD5加密</h2>
                        <p>一次性处理多个字符串的MD5加密，每行一个字符串</p>
                    </div>
                    
                    <div class="tool-controls">
                        <textarea class="batch-input" id="batchInput" placeholder="请输入要批量加密的字符串，每行一个：
password
admin
123456
hello world"></textarea>
                        
                        <div class="input-actions">
                            <button id="batchEncryptBtn" class="btn-primary">
                                <i class="fas fa-layer-group"></i> 批量加密
                            </button>
                            <button id="batchClearBtn" class="btn-outline">
                                <i class="fas fa-trash"></i> 清空批量输入
                            </button>
                        </div>
                        
                        <div class="batch-results" id="batchResults" style="display: none;">
                            <h4><i class="fas fa-list"></i> 批量加密结果</h4>
                            <div id="batchResultsContent"></div>
                        </div>
                    </div>
                </div>
                
                <div class="feature-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>安全可靠</h3>
                        <p>使用标准的MD5算法，生成不可逆的哈希值，确保数据安全。</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>高速处理</h3>
                        <p>优化的算法实现，支持大规模数据快速加密处理。</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3>多格式支持</h3>
                        <p>支持32位/16位、大写/小写多种MD5格式输出。</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>批量处理</h3>
                        <p>支持一次性处理多个字符串，提高工作效率。</p>
                    </div>
                </div>
                
                <div class="tool-info">
                    <h3><i class="fas fa-info-circle"></i> MD5加密说明</h3>
                    <ul>
                        <li><strong>MD5简介</strong>: MD5（Message-Digest Algorithm 5）是一种广泛使用的密码散列函数，可以产生一个128位（16字节）的散列值。</li>
                        <li><strong>不可逆性</strong>: MD5是单向加密算法，无法从哈希值反向推导出原始字符串。</li>
                        <li><strong>应用场景</strong>: 常用于密码存储、文件完整性验证、数字签名等场景。</li>
                        <li><strong>安全性注意</strong>: 由于MD5存在碰撞漏洞，不推荐用于安全性要求极高的场景，建议使用SHA-256等更安全的算法。</li>
                        <li><strong>格式说明</strong>: 
                            <ul>
                                <li>32位小写: 标准的32位十六进制小写字符串</li>
                                <li>32位大写: 标准的32位十六进制大写字符串</li>
                                <li>16位小写: 取32位MD5的第9-24位字符（16位）</li>
                                <li>16位大写: 取32位MD5的第9-24位字符（16位）的大写形式</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </main>
            
            <aside class="sidebar">
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-star"></i> 推荐
                    </div>
                    <div class="ad-content">
                        <h4>高级加密服务</h4>
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
                        <h4>API接口服务</h4>
                        <p>提供稳定的MD5加密API接口，适合集成到您的应用程序中。</p>
                        <a href="api_docs.php" class="ad-button">查看文档</a>
                    </div>
                    <div class="ad-footer">
                        <small>开发者服务</small>
                    </div>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-history"></i> 最近加密记录</h3>
                    <div id="recentEncrypts">
                        <p class="empty-message">暂无最近加密记录</p>
                    </div>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 相关工具</h3>
                    <ul>
                        <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密工具</a></li>
                        <li><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密工具</a></li>
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
                    <h4><i class="fas fa-info-circle"></i> 关于MD5加密</h4>
                    <p>MD5加密工具提供安全、高效的哈希生成服务，支持多种格式和批量处理功能。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-shield-alt"></i> 安全提示</h4>
                    <p>MD5存在安全漏洞，不建议用于密码存储等安全敏感场景。请根据实际需求选择合适的加密算法。</p>
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
        // 加载最近加密记录
        loadRecentEncrypts();
        
        // 单个加密
        $('#encryptBtn').click(function() {
            const inputText = $('#inputText').val().trim();
            const algorithm = $('#algorithm').val();
            
            if (!inputText) {
                showNotification('请输入要加密的文本！', 'warning');
                $('#inputText').focus();
                return;
            }
            
            performEncryption(inputText, algorithm, '#outputText');
        });
        
        // 批量加密
        $('#batchEncryptBtn').click(function() {
            const batchInput = $('#batchInput').val().trim();
            const algorithm = $('#algorithm').val();
            
            if (!batchInput) {
                showNotification('请输入要批量加密的文本！', 'warning');
                $('#batchInput').focus();
                return;
            }
            
            const lines = batchInput.split('\n').filter(line => line.trim() !== '');
            if (lines.length === 0) {
                showNotification('没有有效的输入行！', 'warning');
                return;
            }
            
            if (lines.length > 100) {
                showNotification('批量处理限制：最多100行', 'warning');
                return;
            }
            
            // 显示加载状态
            $('#batchResults').show();
            $('#batchResultsContent').html('<p><i class="fas fa-spinner fa-spin"></i> 处理中，请稍候...</p>');
            
            // 模拟批量处理
            setTimeout(function() {
                let resultsHtml = '';
                lines.forEach((line, index) => {
                    let hash = '';
                    switch(algorithm) {
                        case 'md5':
                            hash = md5(line);
                            break;
                        case 'md5-upper':
                            hash = md5(line).toUpperCase();
                            break;
                        case 'md5-16':
                            hash = md5(line).substring(8, 24);
                            break;
                        case 'md5-16-upper':
                            hash = md5(line).substring(8, 24).toUpperCase();
                            break;
                    }
                    
                    resultsHtml += `
                        <div class="batch-result-item">
                            <strong>${index + 1}.</strong> "${line}" → ${hash}
                        </div>
                    `;
                    
                    // 保存到最近记录
                    saveToRecent(line, hash, algorithm);
                });
                
                $('#batchResultsContent').html(resultsHtml);
                showNotification(`批量加密完成，共处理 ${lines.length} 行`, 'success');
                
                // 更新最近记录显示
                loadRecentEncrypts();
            }, 500);
        });
        
        // 清空按钮
        $('#clearBtn').click(function() {
            $('#inputText').val('');
            $('#outputText').val('');
            showNotification('已清空输入和输出', 'info');
        });
        
        $('#batchClearBtn').click(function() {
            $('#batchInput').val('');
            $('#batchResults').hide();
            showNotification('已清空批量输入', 'info');
        });
        
        // 复制结果
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
    
    function performEncryption(inputText, algorithm, outputSelector) {
        // 显示加载状态
        $(outputSelector).val('加密中...');
        
        // 使用AJAX调用后端
        $.ajax({
            url: 'process.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'encrypt',
                input: inputText,
                algorithm: algorithm
            },
            success: function(response) {
                if (response.success) {
                    $(outputSelector).val(response.result);
                    
                    // 保存到最近记录
                    saveToRecent(inputText, response.result, algorithm);
                    
                    // 更新最近记录显示
                    loadRecentEncrypts();
                    
                    showNotification('加密成功！查询耗时: ' + response.query_time, 'success');
                } else {
                    $(outputSelector).val('错误: ' + response.message);
                    showNotification('加密失败: ' + response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                $(outputSelector).val('请求失败: ' + error);
                showNotification('请求失败，请稍后重试', 'error');
            }
        });
    }
    
    function saveToRecent(original, hash, algorithm) {
        try {
            let recent = JSON.parse(localStorage.getItem('md5_encrypt_recent') || '[]');
            
            // 添加新记录
            const record = {
                original: original,
                hash: hash,
                algorithm: algorithm,
                timestamp: new Date().toISOString()
            };
            
            recent.unshift(record);
            
            // 只保留最近10条记录
            if (recent.length > 10) {
                recent = recent.slice(0, 10);
            }
            
            localStorage.setItem('md5_encrypt_recent', JSON.stringify(recent));
        } catch (e) {
            console.log('保存最近记录失败:', e);
        }
    }
    
    function loadRecentEncrypts() {
        try {
            const recent = JSON.parse(localStorage.getItem('md5_encrypt_recent') || '[]');
            const container = $('#recentEncrypts');
            
            if (recent.length === 0) {
                container.html('<p class="empty-message">暂无最近加密记录</p>');
                return;
            }
            
            let html = '';
            recent.forEach((record, index) => {
                const time = new Date(record.timestamp).toLocaleTimeString('zh-CN', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                
                html += `
                    <div class="recent-item" style="padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.9rem;">
                        <div style="color: #666; font-size: 0.8rem;">${time}</div>
                        <div style="margin-top: 4px; word-break: break-all;">"${record.original.substring(0, 20)}${record.original.length > 20 ? '...' : ''}"</div>
                        <div style="color: #1a73e8; font-family: monospace; font-size: 0.85rem;">${record.hash.substring(0, 20)}${record.hash.length > 20 ? '...' : ''}</div>
                    </div>
                `;
            });
            
            container.html(html);
        } catch (e) {
            console.log('加载最近记录失败:', e);
        }
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
