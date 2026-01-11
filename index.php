<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5在线加密解密工具 - 类似cmd5.com</title>
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
                <i class="fas fa-lock"></i>
                <h1>MD5在线加密解密工具</h1>
                <span class="tagline">类似 cmd5.com 的MD5哈希工具</span>
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
                <li class="active"><a href="index.php"><i class="fas fa-home"></i> 首页</a></li>
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
                <div class="tool-card">
                    <div class="card-header">
                        <h2><i class="fas fa-key"></i> MD5加密/解密工具</h2>
                        <p>输入字符串进行MD5加密，或输入MD5哈希值尝试解密</p>
                    </div>
                    
                    <div class="tool-controls">
                        <div class="algorithm-selector">
                            <label for="algorithm"><i class="fas fa-cogs"></i> 选择算法:</label>
                            <select id="algorithm">
                                <option value="md5" selected>MD5 (32位小写)</option>
                                <option value="md5-upper">MD5 (32位大写)</option>
                                <option value="md5-16">MD5 (16位小写)</option>
                                <option value="md5-16-upper">MD5 (16位大写)</option>
                                <option value="sha1">SHA1</option>
                                <option value="sha256">SHA256</option>
                                <option value="mysql">MySQL密码</option>
                                <option value="base64">Base64编码</option>
                            </select>
                        </div>
                        
                        <div class="input-section">
                            <label for="inputText"><i class="fas fa-pencil-alt"></i> 输入文本或MD5哈希:</label>
                            <textarea id="inputText" placeholder="请输入要加密的字符串或要解密的MD5哈希值..."></textarea>
                            <div class="input-actions">
                                <button id="encryptBtn" class="btn-primary">
                                    <i class="fas fa-lock"></i> 加密
                                </button>
                                <button id="decryptBtn" class="btn-secondary">
                                    <i class="fas fa-unlock"></i> 解密
                                </button>
                                <button id="clearBtn" class="btn-outline">
                                    <i class="fas fa-trash"></i> 清空
                                </button>
                            </div>
                        </div>
                        
                        <div class="output-section">
                            <label for="outputText"><i class="fas fa-result"></i> 结果:</label>
                            <textarea id="outputText" readonly placeholder="加密/解密结果将显示在这里..."></textarea>
                            <div class="output-actions">
                                <button id="copyBtn" class="btn-success">
                                    <i class="fas fa-copy"></i> 复制结果
                                </button>
                                <button id="saveBtn" class="btn-outline">
                                    <i class="fas fa-save"></i> 保存结果
                                </button>
                                <button id="shareBtn" class="btn-outline">
                                    <i class="fas fa-share"></i> 分享
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tool-info">
                        <h3><i class="fas fa-info-circle"></i> 使用说明</h3>
                        <ul>
                            <li><strong>加密</strong>: 输入任意字符串，点击"加密"按钮生成对应的MD5哈希值。</li>
                            <li><strong>解密</strong>: 输入MD5哈希值，点击"解密"按钮尝试查询原始字符串（仅限已收录的哈希）。</li>
                            <li>支持多种算法：MD5(16/32位大小写)、SHA1、SHA256、MySQL密码、Base64编码。</li>
                            <li>我们的数据库收录了超过15亿条MD5记录，解密成功率极高。</li>
                            <li>所有查询都是实时的，结果立即返回。</li>
                        </ul>
                    </div>
                </div>
                
                <div class="stats-card">
                    <h3><i class="fas fa-chart-bar"></i> 今日统计</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">8,542</div>
                                <div class="stat-label">今日查询</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">6,128</div>
                                <div class="stat-label">成功解密</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">0.03秒</div>
                                <div class="stat-label">平均响应时间</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">1,245</div>
                                <div class="stat-label">在线用户</div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <aside class="sidebar">
                <!-- 新增广告位 1 -->
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-star"></i> 新广告位
                    </div>
                    <div class="ad-content">
                        <h4>高级MD5解密服务</h4>
                        <p>解锁更多MD5记录，访问超过50亿条彩虹表数据。</p>
                        <a href="#" class="ad-button">立即升级</a>
                    </div>
                    <div class="ad-footer">
                        <small>赞助商广告</small>
                    </div>
                </div>
                
                <div class="advertisement">
                    <div class="ad-label">
                        <i class="fas fa-ad"></i> 广告
                    </div>
                    <div class="ad-content">
                        <h4>云服务器特惠</h4>
                        <p>高性能云服务器，低至每月¥99，专为开发者打造。</p>
                        <a href="#" class="ad-button">查看详情</a>
                    </div>
                    <div class="ad-footer">
                        <small>赞助商广告</small>
                    </div>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-bolt"></i> 特色功能</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> 超高速查询响应</li>
                        <li><i class="fas fa-check"></i> 超大MD5数据库</li>
                        <li><i class="fas fa-check"></i> 多算法支持</li>
                        <li><i class="fas fa-check"></i> API接口提供</li>
                        <li><i class="fas fa-check"></i> 批量查询支持</li>
                        <li><i class="fas fa-check"></i> 数据安全保证</li>
                    </ul>
                    <a href="#" class="btn-feature">
                        <i class="fas fa-rocket"></i> 了解更多
                    </a>
                </div>
                
                <!-- 新增广告位 2 -->
                <div class="advertisement new-ad">
                    <div class="ad-label">
                        <i class="fas fa-gem"></i> 推荐工具
                    </div>
                    <div class="ad-content">
                        <h4>批量哈希生成器</h4>
                        <p>一次性处理数千个字符串的哈希生成，提高工作效率。</p>
                        <a href="#" class="ad-button">免费试用</a>
                    </div>
                    <div class="ad-footer">
                        <small>合作伙伴推荐</small>
                    </div>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 快速链接</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-download"></i> 下载彩虹表</a></li>
                        <li><a href="#"><i class="fas fa-code"></i> API文档</a></li>
                        <li><a href="#"><i class="fas fa-question"></i> 常见问题</a></li>
                        <li><a href="#"><i class="fas fa-history"></i> 查询历史</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> 联系客服</a></li>
                    </ul>
                </div>
            </aside>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-info-circle"></i> 关于我们</h4>
                    <p>我们提供专业的MD5加密解密服务，拥有庞大的哈希数据库，致力于为用户提供准确、快速的查询体验。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-shield-alt"></i> 免责声明</h4>
                    <p>本工具仅供合法用途。请勿用于破解他人密码或任何非法活动。使用本工具即表示您同意遵守相关法律法规。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-envelope"></i> 联系我们</h4>
                    <p>邮箱: support@md5tool.com</p>
                    <p>电话: 400-123-4567</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 MD5在线加密解密工具. 保留所有权利. | 类似 cmd5.com 的克隆版本</p>
                <p class="footer-note">新增广告位设计，提升网站收益</p>
            </div>
        </footer>
    </div>
    
    <div class="notification" id="notification">
        <span id="notificationText">操作成功！</span>
    </div>
    
    <!-- 处理表单提交的iframe，用于无刷新提交 -->
    <iframe name="hiddenFrame" style="display: none;"></iframe>
</body>
</html>
