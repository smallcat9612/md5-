$(document).ready(function() {
    // 初始化
    initializeTool();
    
    // 加密按钮点击事件
    $('#encryptBtn').click(function() {
        performEncryption();
    });
    
    // 解密按钮点击事件
    $('#decryptBtn').click(function() {
        performDecryption();
    });
    
    // 清空按钮点击事件
    $('#clearBtn').click(function() {
        clearAll();
    });
    
    // 复制结果按钮点击事件
    $('#copyBtn').click(function() {
        copyResult();
    });
    
    // 保存结果按钮点击事件
    $('#saveBtn').click(function() {
        saveResult();
    });
    
    // 分享按钮点击事件
    $('#shareBtn').click(function() {
        shareResult();
    });
    
    // 输入框回车键支持
    $('#inputText').keydown(function(e) {
        if (e.ctrlKey && e.keyCode === 13) {
            // Ctrl+Enter 触发加密
            performEncryption();
        } else if (e.shiftKey && e.keyCode === 13) {
            // Shift+Enter 触发解密
            performDecryption();
        }
    });
    
    // 算法选择变化时更新提示
    $('#algorithm').change(function() {
        updateAlgorithmHint();
    });
});

// 初始化工具
function initializeTool() {
    // 设置今日日期
    const today = new Date();
    const dateStr = today.toLocaleDateString('zh-CN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // 首先从后端获取统计数据并更新显示
    loadDatabaseStats();
    
    // 更新统计数据的随机动画
    animateStatistics();
    
    // 更新算法提示
    updateAlgorithmHint();
    
    // 显示欢迎通知
    showNotification('MD5加密解密工具已就绪！', 'info');
}

// 执行加密操作
function performEncryption() {
    const inputText = $('#inputText').val().trim();
    const algorithm = $('#algorithm').val();
    
    if (!inputText) {
        showNotification('请输入要加密的文本！', 'warning');
        $('#inputText').focus();
        return;
    }
    
    // 显示加载状态
    setLoadingState(true, '加密中...');
    
    // 使用AJAX调用PHP后端
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
                $('#outputText').val(response.result);
                
                // 记录到历史
                recordHistory(inputText, response.result, 'encrypt', algorithm);
                
                // 更新统计
                updateStats('encrypt');
                
                // 显示成功通知
                showNotification('加密成功！查询耗时: ' + response.query_time, 'success');
            } else {
                $('#outputText').val('错误: ' + response.message);
                showNotification('加密失败: ' + response.message, 'error');
            }
            
            // 取消加载状态
            setLoadingState(false);
        },
        error: function(xhr, status, error) {
            $('#outputText').val('请求失败: ' + error + '\n请检查网络连接或服务器状态。');
            showNotification('请求失败，请稍后重试', 'error');
            setLoadingState(false);
            
            // 如果后端不可用，使用前端模拟作为后备
            setTimeout(function() {
                fallbackEncryption(inputText, algorithm);
            }, 100);
        }
    });
}

// 后备加密函数（当后端不可用时）
function fallbackEncryption(inputText, algorithm) {
    let result;
    
    switch(algorithm) {
        case 'md5':
            result = md5(inputText);
            break;
        case 'md5-upper':
            result = md5(inputText).toUpperCase();
            break;
        case 'md5-16':
            result = md5(inputText).substring(8, 24);
            break;
        case 'md5-16-upper':
            result = md5(inputText).substring(8, 24).toUpperCase();
            break;
        case 'sha1':
            result = sha1(inputText);
            break;
        case 'sha256':
            result = sha256(inputText);
            break;
        case 'mysql':
            result = mysqlHash(inputText);
            break;
        case 'base64':
            result = btoa(unescape(encodeURIComponent(inputText)));
            break;
        default:
            result = md5(inputText);
    }
    
    $('#outputText').val(result + '\n\n[注意: 使用本地模拟加密，结果可能与实际算法有差异]');
    recordHistory(inputText, result, 'encrypt', algorithm);
    updateStats('encrypt');
    showNotification('使用本地模拟加密完成', 'warning');
}

// 执行解密操作
function performDecryption() {
    const inputText = $('#inputText').val().trim();
    const algorithm = $('#algorithm').val();
    
    if (!inputText) {
        showNotification('请输入要解密的哈希值！', 'warning');
        $('#inputText').focus();
        return;
    }
    
    // 验证输入格式（基本的MD5校验）
    if (algorithm.includes('md5')) {
        const md5Regex = /^[a-fA-F0-9]{32}$/;
        const md5_16Regex = /^[a-fA-F0-9]{16}$/;
        
        if (!md5Regex.test(inputText) && !md5_16Regex.test(inputText)) {
            showNotification('请输入有效的MD5哈希值（16或32位十六进制）！', 'error');
            return;
        }
    }
    
    // 显示加载状态
    setLoadingState(true, '解密中...');
    
    // 使用AJAX调用PHP后端
    $.ajax({
        url: 'process.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'decrypt',
            input: inputText,
            algorithm: algorithm
        },
        success: function(response) {
            if (response.success) {
                $('#outputText').val(response.result);
                
                // 记录到历史
                recordHistory(inputText, response.result, 'decrypt', algorithm);
                
                // 更新统计
                updateStats('decrypt', response.found || false);
                
                // 显示成功通知
                showNotification('解密成功！查询耗时: ' + response.query_time, 'success');
            } else {
                $('#outputText').val(response.result);
                
                // 记录到历史（即使未找到也记录）
                recordHistory(inputText, response.result, 'decrypt', algorithm);
                
                // 更新统计
                updateStats('decrypt', false);
                
                // 显示通知
                showNotification('未找到匹配项，请尝试其他方法。', 'warning');
            }
            
            // 取消加载状态
            setLoadingState(false);
        },
        error: function(xhr, status, error) {
            $('#outputText').val('请求失败: ' + error + '\n请检查网络连接或服务器状态。');
            showNotification('请求失败，请稍后重试', 'error');
            setLoadingState(false);
            
            // 如果后端不可用，使用前端模拟作为后备
            setTimeout(function() {
                fallbackDecryption(inputText, algorithm);
            }, 100);
        }
    });
}

// 后备解密函数（当后端不可用时）
function fallbackDecryption(inputText, algorithm) {
    let result = '';
    let found = false;
    
    // 模拟数据库查询
    const mockDatabase = {
        // 一些常见的MD5哈希和对应的原文
        'e10adc3949ba59abbe56e057f20f883e': '123456',
        '21232f297a57a5a743894a0e4a801fc3': 'admin',
        '5f4dcc3b5aa765d61d8327deb882cf99': 'password',
        'd41d8cd98f00b204e9800998ecf8427e': '',
        '827ccb0eea8a706c4c34a16891f84e7b': '12345',
        '098f6bcd4621d373cade4e832627b4f6': 'test',
        '25d55ad283aa400af464c76d713c07ad': '12345678',
        'e99a18c428cb38d5f260853678922e03': 'abc123',
        'fcea920f7412b5da7be0cf42b8c93759': '1234567',
        'c33367701511b4f6020ec61ded352059': '654321'
    };
    
    if (mockDatabase[inputText.toLowerCase()]) {
        result = `解密成功！原始字符串为: "${mockDatabase[inputText.toLowerCase()]}"`;
        found = true;
    } else {
        // 如果没有找到，尝试模拟彩虹表查询
        result = '抱歉，在我们的数据库中未找到此哈希对应的原始字符串。\n';
        result += '这可能是因为：\n';
        result += '1. 该哈希值尚未被收录到我们的数据库中\n';
        result += '2. 原始字符串非常复杂或长度较长\n';
        result += '3. 该哈希值使用了加盐处理\n\n';
        result += '您可以尝试：\n';
        result += '- 使用我们的高级解密服务（侧边栏广告）\n';
        result += '- 尝试其他可能的原始字符串组合';
        found = false;
    }
    
    $('#outputText').val(result + '\n\n[注意: 使用本地模拟数据库查询]');
    recordHistory(inputText, result, 'decrypt', algorithm);
    updateStats('decrypt', found);
    
    if (found) {
        showNotification('解密成功！在本地模拟数据库中找到了匹配项。', 'success');
    } else {
        showNotification('未找到匹配项，请尝试其他方法。', 'warning');
    }
}

// 清空所有输入和输出
function clearAll() {
    $('#inputText').val('');
    $('#outputText').val('');
    showNotification('已清空所有内容', 'info');
}

// 复制结果到剪贴板
function copyResult() {
    const outputText = $('#outputText').val();
    
    if (!outputText) {
        showNotification('没有可复制的内容！', 'warning');
        return;
    }
    
    // 使用现代剪贴板API
    navigator.clipboard.writeText(outputText).then(function() {
        showNotification('结果已复制到剪贴板！', 'success');
    }, function(err) {
        // 降级方案
        const textArea = document.createElement('textarea');
        textArea.value = outputText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('结果已复制到剪贴板！', 'success');
    });
}

// 保存结果（模拟）
function saveResult() {
    const outputText = $('#outputText').val();
    
    if (!outputText) {
        showNotification('没有可保存的内容！', 'warning');
        return;
    }
    
    // 模拟保存操作
    showNotification('结果已保存到历史记录！', 'info');
}

// 分享结果（模拟）
function shareResult() {
    const outputText = $('#outputText').val();
    
    if (!outputText) {
        showNotification('没有可分享的内容！', 'warning');
        return;
    }
    
    // 模拟分享操作
    showNotification('分享功能已调用（在实际网站中会打开分享对话框）', 'info');
}

// 设置加载状态
function setLoadingState(isLoading, message = '处理中...') {
    if (isLoading) {
        // 禁用所有按钮
        $('button').prop('disabled', true);
        
        // 添加加载指示器到输出框
        $('#outputText').val(message + '\n请稍候...');
        
        // 显示加载动画
        $('.tool-card').addClass('loading');
    } else {
        // 启用所有按钮
        $('button').prop('disabled', false);
        
        // 移除加载状态
        $('.tool-card').removeClass('loading');
    }
}

// 更新算法提示
function updateAlgorithmHint() {
    const algorithm = $('#algorithm').val();
    let hint = '';
    
    switch(algorithm) {
        case 'md5':
            hint = '将生成32位小写MD5哈希值';
            break;
        case 'md5-upper':
            hint = '将生成32位大写MD5哈希值';
            break;
        case 'md5-16':
            hint = '将生成16位小写MD5哈希值（取32位MD5的第9-24位）';
            break;
        case 'md5-16-upper':
            hint = '将生成16位大写MD5哈希值（取32位MD5的第9-24位）';
            break;
        case 'sha1':
            hint = '将生成40位SHA1哈希值';
            break;
        case 'sha256':
            hint = '将生成64位SHA256哈希值';
            break;
        case 'mysql':
            hint = '将生成MySQL风格的密码哈希';
            break;
        case 'base64':
            hint = '将生成Base64编码结果';
            break;
    }
    
    // 更新提示（在实际界面中可以显示在某个元素中）
    console.log('当前算法: ' + hint);
}

// 显示通知
function showNotification(message, type = 'info') {
    const notification = $('#notification');
    const notificationText = $('#notificationText');
    
    // 设置消息和类型
    notificationText.text(message);
    
    // 根据类型设置背景颜色
    notification.removeClass('info success warning error');
    notification.addClass(type);
    
    // 显示通知
    notification.addClass('show');
    
    // 3秒后隐藏
    setTimeout(function() {
        notification.removeClass('show');
    }, 3000);
}

// 更新统计信息（模拟）
function updateStats(action, success = true) {
    // 在实际应用中，这里会发送AJAX请求到服务器更新统计
    console.log(`统计更新: ${action} ${success ? '成功' : '失败'}`);
    
    // 更新页面上的今日查询计数（模拟）
    if (action === 'encrypt' || action === 'decrypt') {
        const queryElement = $('.stat-item:first-child .stat-number');
        let currentQueries = parseInt(queryElement.text().replace(/,/g, ''));
        currentQueries++;
        queryElement.text(currentQueries.toLocaleString());
    }
    
    // 如果解密成功，更新成功解密计数
    if (action === 'decrypt' && success) {
        const successElement = $('.stat-item:nth-child(2) .stat-number');
        let currentSuccess = parseInt(successElement.text().replace(/,/g, ''));
        currentSuccess++;
        successElement.text(currentSuccess.toLocaleString());
    }
}

// 记录历史（模拟）
function recordHistory(input, output, action, algorithm) {
    // 在实际应用中，这里会发送AJAX请求到服务器保存历史记录
    const historyEntry = {
        timestamp: new Date().toISOString(),
        input: input,
        output: output,
        action: action,
        algorithm: algorithm
    };
    
    console.log('历史记录:', historyEntry);
    
    // 存储到本地存储（模拟）
    try {
        let history = JSON.parse(localStorage.getItem('md5tool_history') || '[]');
        history.unshift(historyEntry);
        
        // 只保留最近50条记录
        if (history.length > 50) {
            history = history.slice(0, 50);
        }
        
        localStorage.setItem('md5tool_history', JSON.stringify(history));
    } catch (e) {
        console.log('本地存储不可用');
    }
}

// 加载数据库统计信息并更新页面显示
function loadDatabaseStats() {
    $.ajax({
        url: 'process.php?admin=stats',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.stats) {
                const stats = response.stats;
                // 格式化数据库记录数
                let dbRecordsText;
                if (stats.formatted_records) {
                    // 使用后端返回的格式化记录数
                    dbRecordsText = stats.formatted_records;
                } else if (stats.total_records) {
                    // 手动格式化
                    dbRecordsText = formatNumber(stats.total_records);
                } else {
                    dbRecordsText = '15.8亿'; // 默认值
                }
                
                // 更新所有页面上的数据库记录显示
                $('.header-info .stat:first-child strong').text(dbRecordsText);
                
                // 更新今日查询次数
                if (stats.today_queries) {
                    $('.stat-item:first-child .stat-number').text(stats.today_queries.toLocaleString());
                }
                
                // 更新成功解密次数
                if (stats.successful_decrypts) {
                    $('.stat-item:nth-child(2) .stat-number').text(stats.successful_decrypts.toLocaleString());
                }
                
                // 更新平均响应时间
                if (stats.avg_query_time) {
                    $('.stat-item:nth-child(3) .stat-number').text(stats.avg_query_time);
                }
                
                console.log('数据库统计信息已更新:', stats);
            } else {
                console.warn('获取统计信息失败:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.warn('无法获取统计信息，使用默认值:', error);
            // 使用默认值
            $('.header-info .stat:first-child strong').text('15.8亿');
        }
    });
}

// 格式化数字为易读的格式
function formatNumber(number) {
    if (number >= 100000000) {
        return round(number / 100000000, 1) + '亿';
    } else if (number >= 10000) {
        return round(number / 10000, 1) + '万';
    } else {
        return number.toString();
    }
}

// 四舍五入到指定小数位
function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

// 动画显示统计数据
function animateStatistics() {
    $('.stat-number').each(function() {
        const $this = $(this);
        const originalText = $this.text();
        const originalNumber = parseInt(originalText.replace(/,/g, ''));
        
        // 如果文本不是数字，则跳过动画
        if (isNaN(originalNumber)) {
            return;
        }
        
        // 添加一个简单的计数器动画
        $this.prop('counter', 0).animate({
            counter: originalNumber
        }, {
            duration: 2000,
            easing: 'swing',
            step: function(now) {
                $this.text(Math.floor(now).toLocaleString());
            }
        });
    });
}

// ============================================
// 哈希函数实现（简化版）
// 注意：这些是简化实现，实际应用中应该使用更安全的库
// ============================================

// MD5哈希函数（简化模拟）
function md5(input) {
    // 这是一个简化的MD5模拟函数
    // 在实际应用中，应该使用成熟的MD5库如CryptoJS
    return 'simulated_md5_hash_' + input.length + '_' + Date.now();
}

// SHA1哈希函数（简化模拟）
function sha1(input) {
    return 'simulated_sha1_hash_' + input.length + '_' + Date.now();
}

// SHA256哈希函数（简化模拟）
function sha256(input) {
    return 'simulated_sha256_hash_' + input.length + '_' + Date.now();
}

// MySQL密码哈希函数（简化模拟）
function mysqlHash(input) {
    return 'simulated_mysql_hash_' + input.length + '_' + Date.now();
}

// 在实际的网站中，这些函数应该调用后端的PHP实现
// 或者使用前端的CryptoJS等库

// 添加一些CSS类用于加载状态
$(document).ready(function() {
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .tool-card.loading {
                position: relative;
            }
            
            .tool-card.loading::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8);
                border-radius: 10px;
                z-index: 10;
            }
            
            .tool-card.loading::before {
                content: '处理中...';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 11;
                font-size: 1.2rem;
                font-weight: bold;
                color: #1a73e8;
            }
            
            .notification.info {
                background-color: #1a73e8;
            }
            
            .notification.success {
                background-color: #34a853;
            }
            
            .notification.warning {
                background-color: #fbbc05;
            }
            
            .notification.error {
                background-color: #ea4335;
            }
        `)
        .appendTo('head');
});
