<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>帮助 - MD5在线加密解密工具</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
    <style>
        .faq-section {
            margin-bottom: 30px;
        }
        
        .faq-item {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .faq-item:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
        }
        
        .faq-question {
            font-weight: bold;
            color: #1a73e8;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        
        .faq-answer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: none;
        }
        
        .faq-icon {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .faq-item.active .faq-answer {
            display: block;
        }
        
        .help-category {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-question-circle"></i>
                <h1>帮助中心</h1>
                <span class="tagline">常见问题和使用指南</span>
            </div>
            <div class="header-info">
                <div class="stat">
                    <i class="fas fa-book"></i>
                    <span>文档数量: <strong>25+</strong></span>
                </div>
                <div class="stat">
                    <i class="fas fa-users"></i>
                    <span>已帮助: <strong>10万+</strong></span>
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
                <li class="active"><a href="help.php"><i class="fas fa-question-circle"></i> 帮助</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系</a></li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <main class="main-content">
                <div class="tool-card">
                    <div class="card-header">
                        <h2><i class="fas fa-question-circle"></i> 帮助中心</h2>
                        <p>常见问题解答和使用指南</p>
                    </div>
                    
                    <div class="faq-section">
                        <div class="help-category">
                            <h3><i class="fas fa-key"></i> MD5加密常见问题</h3>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    什么是MD5加密？
                                </div>
                                <div class="faq-answer">
                                    <p>MD5（Message-Digest Algorithm 5）是一种广泛使用的密码散列函数，可以产生一个128位（16字节）的散列值。它通常用于验证数据完整性，生成不可逆的哈希值。</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    MD5加密是否安全？
                                </div>
                                <div class="faq-answer">
                                    <p>MD5存在碰撞漏洞，不推荐用于安全性要求极高的场景（如密码存储）。建议使用SHA-256、bcrypt等更安全的算法。但在文件完整性验证、简单数据标识等场景中仍可使用。</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    32位MD5和16位MD5有什么区别？
                                </div>
                                <div class="faq-answer">
                                    <p>32位MD5是完整的128位哈希值的32位十六进制表示。16位MD5是取32位MD5的第9-24位字符（共16位），主要用于某些特定系统或遗留应用。</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="help-category">
                            <h3><i class="fas fa-unlock"></i> MD5解密常见问题</h3>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    MD5解密是如何工作的？
                                </div>
                                <div class="faq-answer">
                                    <p>MD5解密并非真正的"解密"，而是通过彩虹表（Rainbow Table）进行哈希碰撞查询。我们的数据库收录了超过15亿条常见字符串的MD5哈希值，当您输入一个MD5哈希时，系统会在数据库中查找是否有匹配的原始字符串。</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    为什么有些MD5哈希无法解密？
                                </div>
                                <div class="faq-answer">
                                    <p>可能原因包括：</p>
                                    <ul>
                                        <li>原始字符串非常复杂或长度较长</li>
                                        <li>该哈希值尚未被收录到我们的数据库中</li>
                                        <li>原始字符串使用了加盐（Salt）处理</li>
                                        <li>哈希值格式不正确</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    如何提高MD5解密的成功率？
                                </div>
                                <div class="faq-answer">
                                    <p>您可以：</p>
                                    <ul>
                                        <li>使用我们的高级解密服务（访问更大数据库）</li>
                                        <li>确保输入的MD5哈希格式正确（32位或16位十六进制）</li>
                                        <li>尝试批量解密功能</li>
                                        <li>检查哈希值是否包含空格或其他无效字符</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="help-category">
                            <h3><i class="fas fa-code"></i> API使用指南</h3>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    如何使用API接口？
                                </div>
                                <div class="faq-answer">
                                    <p>我们提供RESTful API接口，支持POST和GET请求。详细文档请参考<a href="api_docs.php">API文档</a>页面。基本使用方式：</p>
                                    <pre>
POST /process.php
Content-Type: application/x-www-form-urlencoded

action=encrypt&input=your_string&algorithm=md5
                                    </pre>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    API调用有限制吗？
                                </div>
                                <div class="faq-answer">
                                    <p>免费版API限制为1000次/小时。专业版用户可享受10000次/小时的调用限制。如需更高限制，请联系我们升级服务。</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="help-category">
                            <h3><i class="fas fa-tools"></i> 工具使用指南</h3>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    如何使用批量处理功能？
                                </div>
                                <div class="faq-answer">
                                    <p>在MD5加密或解密页面，找到"批量处理"区域，输入多个字符串或哈希值（每行一个），点击"批量处理"按钮即可。批量处理最多支持100行数据。</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="fas fa-chevron-right"></i></span>
                                    如何保存查询结果？
                                </div>
                                <div class="faq-answer">
                                    <p>在结果区域点击"保存结果"按钮，系统会生成一个文本文件供您下载。您也可以使用"复制结果"按钮将结果复制到剪贴板。</p>
                                </div>
                            </div>
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
                        <h4>视频教程</h4>
                        <p>观看详细的使用教程视频，快速掌握工具使用方法。</p>
                        <a href="#" class="ad-button">观看教程</a>
                    </div>
                    <div class="ad-footer">
                        <small>学习资源</small>
                    </div>
                </div>
                
                <div class="advertisement">
                    <div class="ad-label">
                        <i class="fas fa-ad"></i> 广告
                    </div>
                    <div class="ad-content">
                        <h4>开发者手册</h4>
                        <p>完整的技术文档和开发指南，适合开发者参考。</p>
                        <a href="api_docs.php" class="ad-button">查看文档</a>
                    </div>
                    <div class="ad-footer">
                        <small>技术资源</small>
                    </div>
                </div>
                
                <div class="feature-card">
                    <h3><i class="fas fa-phone-alt"></i> 需要帮助？</h3>
                    <p>如果您的问题在这里找不到答案，可以：</p>
                    <ul>
                        <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系客服</a></li>
                        <li><i class="fas fa-comments"></i> 在线咨询 (9:00-18:00)</li>
                        <li><i class="fas fa-file-alt"></i> 提交工单</li>
                    </ul>
                    <p style="margin-top: 15px; font-size: 0.9rem;">
                        <i class="fas fa-clock"></i> 响应时间: 通常在24小时内
                    </p>
                </div>
                
                <div class="quick-links">
                    <h3><i class="fas fa-link"></i> 快速链接</h3>
                    <ul>
                        <li><a href="md5_encrypt.php"><i class="fas fa-key"></i> MD5加密工具</a></li>
                        <li><a href="md5_decrypt.php"><i class="fas fa-unlock"></i> MD5解密工具</a></li>
                        <li><a href="api_docs.php"><i class="fas fa-code"></i> API文档</a></li>
                        <li><a href="contact.php"><i class="fas fa-envelope"></i> 联系我们</a></li>
                        <li><a href="#"><i class="fas fa-download"></i> 下载桌面版</a></li>
                    </ul>
                </div>
            </aside>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-info-circle"></i> 关于帮助中心</h4>
                    <p>我们致力于为用户提供清晰、详细的使用指南和问题解答。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-book"></i> 文档更新</h4>
                    <p>文档定期更新，确保信息准确性和时效性。</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-envelope"></i> 反馈建议</h4>
                    <p>邮箱: help@md5tool.com</p>
                    <p>文档反馈: docs-feedback@md5tool.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 MD5在线加密解密工具. 保留所有权利.</p>
                <p class="footer-note">专业哈希工具服务提供商</p>
            </div>
        </footer>
    </div>
    
    <script>
    $(document).ready(function() {
        $('.faq-item').click(function() {
            $(this).toggleClass('active');
            $(this).find('.faq-icon i').toggleClass('fa-chevron-right fa-chevron-down');
        });
    });
    </script>
</body>
</html>
