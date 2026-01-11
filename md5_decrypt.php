<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5解密 - MD5在线加密解密工具</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
    <style>
        .decryption-info {
            background-color: #fff8e1;
            border-left: 4px solid #ff9800;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .decryption-info h3 {
            color: #ff9800;
            margin-bottom: 15px;
        }
        
        .hash-examples {
            margin-top: 20px;
        }
        
        .example-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .example-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .example-item:hover {
            background-color: #f0f7ff;
            border-color: #1a73e8;
        }
        
        .example-hash {
            color: #1a73e8;
            font-weight: bold;
            margin-bottom: 5px;
            word-break: break-all;
        }
        
        .example-text {
            color: #34a853;
            font-size: 0.85rem;
        }
        
        .batch-decrypt-input {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-family: 'Consolas', 'Monaco', monospace;
            margin-bottom: 15px;
        }
        
        .decrypt-results {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .result-found {
            border-left: 4px solid #34a853;
        }
        
        .result-not-found {
            border-left: 4px solid #ea4335;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-unlock"></i>
                <h1>MD5解密工具</h1>
                <span class="tagline">通过彩虹表查询MD5哈希对应的原始字符串</span>
            </div>
            <div class="header-info">
                <div class="stat">
                    <i class="fas fa-database"></i>
                    <span>数据库记录: <strong>加载中...</strong></span>
                </div>
                <div class="stat">
                    <i class="fas fa-bolt"></i>
                    <span>查询速度: <strong>极快</strong></span>
                </div>
            </div>
        </header>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> 首页</a></li>
                <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密</a></li>
                <li class="active"><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密</a></li>
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
                        <h2><i class="fas fa-unlock"></i> MD5解密工具</h2>
                        <p>输入MD5哈希值，尝试查询对应的原始字符串</p>
                    </div>
                    
                    <div class="tool-controls">
                        <div class="input-section">
                            <label for="inputHash"><i class="fas fa-hashtag"></i> 输入MD5哈希值:</label>
                            <textarea id="inputHash" placeholder="请输入32位或16位MD5哈希值，例如：e10adc3949ba59abbe56e057f20f883e"></textarea>
                            <div class="input-actions">
                                <button id="decryptBtn" class="btn-primary">
                                    <i class="fas fa-search"></i> 查询原始字符串
                                </button>
                                <button id="clearBtn" class="btn-outline">
                                    <i class="fas fa-trash"></i> 清空
                                </button>
                            </div>
                        </div>
                        
                        <div class="output-section">
                            <label for="outputText"><i class="fas fa-result"></i> 查询结果:</label>
                            <textarea id="outputText" readonly placeholder="查询结果将显示在这里..."></textarea>
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
                        <h2><i class="fas fa-layer-group"></i> 批量MD5解密</h2>
                        <p>一次性查询多个MD5哈希值，每行一个哈希</p>
                    </div>
                    
                    <div class="tool-controls">
                        <textarea class="batch-decrypt-input" id="batchDecryptInput" placeholder="请输入要批量解密的MD5哈希值，每行一个：
e10adc3949ba59abbe56e057f20f883e
21232f297a57a5a743894a0e4a801fc3
5f4dcc3b5aa765d61d8327deb882cf99"></textarea>
                        
                        <div class="input-actions">
                            <button id="batchDecryptBtn" class="btn-primary">
                                <i class="fas fa-search"></i> 批量查询
                            </button>
                            <button id="batchClearBtn" class="btn-outline">
                                <i class="fas fa-trash"></i> 清空
                            </button>
                        </div>
                        
                        <div class="decrypt-results" id="batchDecryptResults" style="display: none;">
                            <h4><i class="fas fa-list"></i> 批量查询结果</h4>
                            <div id="batchDecryptResultsContent"></div>
                        </div>
                    </div>
                </div>
                
                <div class="decryption-info">
                    <h3><i class="fas fa-lightbulb"></i> MD5解密原理说明</h3>
                    <p>MD5解密并非真正的"解密"，而是通过庞大的彩虹表（Rainbow Table）进行哈希碰撞查询。</p>
                    <ul>
                        <li><strong>彩虹表</strong>: 包含大量常见字符串及其对应的MD5哈希值的数据库。</li>
                        <li><strong>查询过程</strong>: 当您输入一个MD5哈希值时，系统会在数据库中查找是否有匹配的原始字符串。</li>
                        <li><strong>成功率</strong>: 对于常见字符串（如简单密码、常用单词）成功率较高，对于复杂随机字符串成功率较低。</li>
                        <li><strong>加盐处理</strong>: 如果原始字符串在加密时使用了"盐值"（Salt），则无法通过彩虹表查询。</li>
                        <li><strong>安全性</strong>: 由于MD5存在碰撞漏洞，建议使用更安全的哈希算法（如SHA-256）并配合盐值使用。</li>
                    </ul>
                </div>
                
                <div class="hash-examples">
                    <h3><i class="fas fa-list-alt"></i> 常见MD5哈希示例（点击尝试）</h3>
                    <p>点击下面的哈希值可以自动填充到输入框进行查询。</p>
                    
                    <div class="example-list">
                        <div class="example-item" data-hash="e10adc3949ba59abbe56e057f20f883e">
                            <div class="example-hash">e10adc3949ba59abbe56e057f20f883e</div>
                            <div class="example-text">原始字符串: 123456</div>
                        </div>
                        
                        <div class="example-item" data-hash="21232f297a57a5a743894a0e4a801fc3">
                            <div class="example-hash">21232f297a57a5a743894a0e4a801fc3</div>
                            <div class="example-text">原始字符串: admin</div>
                        </div>
                        
                        <div class="example-item" data-hash="5f4dcc3b5aa765d61d8327deb882cf99">
                            <div class="example-hash">5f4dcc3b5aa765d61d8327deb882cf99</div>
                            <div class="example-text">原始字符串: password</div>
                        </div>
                        
                        <div class="example-item" data-hash="827ccb0eea8a706c4c34a16891f84e7b">
                            <div class="example-hash">827ccb0eea8a706c4c34a16891f84e7b</div>
                            <div class="example-text">原始字符串: 12345</div>
                        </div>
                        
                        <div class="example-item" data-hash="25d55ad283aa400af464c76d713c07ad">
                            <div class="example-hash">25d55ad283aa400af464c76d713c07ad</div>
                            <div class="example-text">原始字符串: 12345678</div>
                        </div>
                        
                        <div class="example-item" data-hash="d8578edf8458ce06fbc5bb76a58c5ca4">
                            <div class="example-hash">d8578edf8458ce06fbc5bb76a58c5ca4</div>
                            <div class="example-text">原始字符串: qwerty</div>
                        </div>
                    </div>
                </div>
            </main>
            
            <aside class="sidebar">
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-star"></i> 推荐
                    </div>
                    <div class="ad-content">
                        <h4>高级解密服务</h4>
                        <p>解锁更大彩虹表，包含超过50亿条记录，提高解密成功率。</p>
                        <a href="#" class="ad-button">升级服务</a>
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
                        <h4>批量解密API</h4>
                        <p>提供批量MD5解密API接口，适合大数据分析场景。</p>
                        <a href="api_docs.php" class="ad-button">API文档</a>
                    </div>
                    <div class="ad-footer">
                        <small>开发者服务</small>
                    </div>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-chart-line"></i> 今日解密统计</h3>
                    <div id="decryptStats">
                        <p><i class="fas fa-spinner fa-spin"></i> 加载统计信息...</p>
                    </div>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 相关工具</h3>
                    <ul>
                        <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密工具</a></li>
                        <li><a href="sha1_encrypt.php"><i class="fas fa-code"></i> SHA1加密工具</a></li>
                        <li><a href="mysql_encrypt.php"><i class="fas fa-database"></i> MySQL加密工具</a></li>
                        <li><a href="hash_identifier.php"><i class="fas fa-fingerprint"></i> 哈希识别工具</a></li>
                        <li><a href="rainbow_table.php"><i class="fas fa-table"></i> 彩虹表下载</a></li>
                    </ul>
                </div>
            </aside>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-info-circle"></i> 关于MD5解密</h4>
                    <p>我们提供基于彩虹表的MD5哈希查询服务，数据库收录超过15亿条常见字符串的MD5哈希值。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-shield-alt"></i> 免责声明</h4>
                    <p>本工具仅供合法用途。请勿用于破解他人密码或任何非法活动。使用本工具即表示您同意遵守相关法律法规。</p>
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
        // 加载解密统计
        loadDecryptStats();
        
        // 单个解密
        $('#decryptBtn').click(function() {
            const inputHash = $('#inputHash').val().trim();
            
            if (!inputHash) {
                showNotification('请输入MD5哈希值！', 'warning');
                $('#inputHash').focus();
                return;
            }
            
            // 验证MD5格式（32位或16位十六进制）
            const md5Regex32 = /^[a-fA-F0-9]{32}$/;
            const md5Regex16 = /^[a-fA-F0-9]{16}$/;
            
            if (!md5Regex32.test(inputHash) && !md5Regex16.test(inputHash)) {
                showNotification('请输入有效的MD5哈希值（16或32位十六进制）！', 'error');
                return;
            }
            
            performDecryption(inputHash);
        });
        
        // 批量解密
        $('#batchDecryptBtn').click(function() {
            const batchInput = $('#batchDecryptInput').val().trim();
            
            if (!batchInput) {
                showNotification('请输入要批量解密的MD5哈希值！', 'warning');
                $('#batchDecryptInput').focus();
                return;
            }
            
            const lines = batchInput.split('\n').filter(line => line.trim() !== '');
            if (lines.length === 0) {
                showNotification('没有有效的输入行！', 'warning');
                return;
            }
            
            if (lines.length > 50) {
                showNotification('批量处理限制：最多50个哈希值', 'warning');
                return;
            }
            
            // 验证每个哈希值的格式
            const invalidHashes = [];
            lines.forEach((hash, index) => {
                const md5Regex32 = /^[a-fA-F0-9]{32}$/;
                const md5Regex16 = /^[a-fA-F0-9]{16}$/;
                
                if (!md5Regex32.test(hash) && !md5Regex16.test(hash)) {
                    invalidHashes.push(`第${index + 1}行: ${hash}`);
                }
            });
            
            if (invalidHashes.length > 0) {
                showNotification('以下哈希值格式无效：\n' + invalidHashes.join('\n'), 'error');
                return;
            }
            
            // 显示加载状态
            $('#batchDecryptResults').show();
            $('#batchDecryptResultsContent').html('<p><i class="fas fa-spinner fa-spin"></i> 批量查询中，请稍候...</p>');
            
            // 使用AJAX批量查询
            $.ajax({
                url: 'process.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'batch_decrypt',
                    hashes: JSON.stringify(lines),
                    algorithm: 'md5'
                },
                success: function(response) {
                    if (response.success) {
                        let resultsHtml = '';
                        let foundCount = 0;
                        
                        response.results.forEach((result, index) => {
                            const isFound = result.found;
                            const className = isFound ? 'result-item result-found' : 'result-item result-not-found';
                            
                            if (isFound) foundCount++;
                            
                            resultsHtml += `
                                <div class="${className}">
                                    <strong>${index + 1}.</strong> ${result.hash}<br>
                                    ${isFound ? 
                                        `<span style="color: #34a853;">✓ 找到: "${result.original_string}"</span>` : 
                                        `<span style="color: #ea4335;">✗ 未找到匹配项</span>`
                                    }
                                </div>
                            `;
                        });
                        
                        $('#batchDecryptResultsContent').html(`
                            <div style="margin-bottom: 15px; padding: 10px; background-color: #e8f5e9; border-radius: 6px;">
                                <i class="fas fa-chart-bar"></i> 统计: 共查询 ${lines.length} 个哈希值，成功匹配 ${foundCount} 个
                            </div>
                            ${resultsHtml}
                        `);
                        
                        showNotification(`批量查询完成，成功匹配 ${foundCount}/${lines.length} 个哈希值`, 'success');
                    } else {
                        $('#batchDecryptResultsContent').html(`<p style="color: #ea4335;">错误: ${response.message}</p>`);
                        showNotification('批量查询失败: ' + response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    $('#batchDecryptResultsContent').html(`<p style="color: #ea4335;">请求失败: ${error}</p>`);
                    showNotification('请求失败，请稍后重试', 'error');
                }
            });
        });
        
        // 清空按钮
        $('#clearBtn').click(function() {
            $('#inputHash').val('');
            $('#outputText').val('');
            showNotification('已清空输入和输出', 'info');
        });
        
        $('#batchClearBtn').click(function() {
            $('#batchDecryptInput').val('');
            $('#batchDecryptResults').hide();
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
        
        // 示例哈希点击事件
        $('.example-item').click(function() {
            const hash = $(this).data('hash');
            $('#inputHash').val(hash);
            showNotification('已填充哈希值，点击"查询原始字符串"按钮进行查询', 'info');
        });
    });
    
    function performDecryption(hash) {
        // 显示加载状态
        $('#outputText').val('查询中...');
        
        // 使用AJAX调用后端
        $.ajax({
            url: 'process.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'decrypt',
                input: hash,
                algorithm: 'md5'
            },
            success: function(response) {
                if (response.success) {
                    $('#outputText').val(response.result);
                    
                    // 更新统计信息
                    loadDecryptStats();
                    
                    showNotification('查询成功！查询耗时: ' + response.query_time, 'success');
                } else {
                    $('#outputText').val(response.result);
                    showNotification('未找到匹配项', 'warning');
                }
            },
            error: function(xhr, status, error) {
                $('#outputText').val('请求失败: ' + error);
                showNotification('请求失败，请稍后重试', 'error');
            }
        });
    }
    
    function loadDecryptStats() {
        // 模拟加载解密统计
        setTimeout(function() {
            const statsHtml = `
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: #1a73e8; font-weight: bold;">8,542</div>
                    <div style="color: #666; font-size: 0.9rem;">今日查询次数</div>
                    
                    <div style="display: flex; justify-content: space-around; margin-top: 20px;">
                        <div>
                            <div style="font-size: 1.5rem; color: #34a853;">71.8%</div>
                            <div style="color: #666; font-size: 0.8rem;">成功率</div>
                        </div>
                        <div>
                            <div style="font-size: 1.5rem; color: #ff9800;">0.03s</div>
                            <div style="color: #666; font-size: 0.8rem;">平均响应</div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#decryptStats').html(statsHtml);
        }, 500);
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
