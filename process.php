<?php
/**
 * MD5加密解密工具后端处理
 * 类似cmd5.com的PHP后端实现
 * 使用ClickHouse + Redis架构支持千亿级别数据量
 */

// 设置响应头为JSON
header('Content-Type: application/json; charset=utf-8');

// 允许跨域请求（用于开发测试）
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 引入配置文件
require_once 'config.php';
require_once 'Database.php';

// 获取请求参数
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$input = isset($_POST['input']) ? $_POST['input'] : (isset($_GET['input']) ? $_GET['input'] : '');
$algorithm = isset($_POST['algorithm']) ? $_POST['algorithm'] : (isset($_GET['algorithm']) ? $_GET['algorithm'] : 'md5');
$adminAction = isset($_GET['admin']) ? $_GET['admin'] : '';

// 初始化数据库
$db = new Database();

// 初始化响应数据
$response = [
    'success' => false,
    'message' => '',
    'result' => '',
    'algorithm' => $algorithm,
    'timestamp' => date('Y-m-d H:i:s'),
    'query_time' => 0,
    'mode' => $db->getMode(),
];

// 记录开始时间
$startTime = microtime(true);

try {
    // 管理接口
    if ($adminAction === 'stats') {
        // 获取统计信息
        $stats = $db->getStatistics();
        $response['success'] = true;
        $response['message'] = '统计信息获取成功';
        $response['stats'] = $stats;
        $response['mode'] = $db->getMode();
    } elseif ($adminAction === 'switchMode') {
        // 切换模式（生产/模拟）
        $targetMode = isset($_GET['target']) ? $_GET['target'] : '';
        if ($targetMode === 'production') {
            $db->switchToProduction();
            $response['success'] = true;
            $response['message'] = '已切换到生产模式（ClickHouse+Redis）';
        } elseif ($targetMode === 'simulation') {
            $db->switchToSimulation();
            $response['success'] = true;
            $response['message'] = '已切换到模拟模式';
        } else {
            throw new Exception('无效的目标模式，请指定 target=production 或 target=simulation');
        }
        $response['mode'] = $db->getMode();
        } else {
            // 验证输入
            if (empty($input)) {
                throw new Exception('请输入要处理的文本或哈希值');
            }
            
            // 根据操作类型处理
            if ($action === 'encrypt') {
                $result = encryptText($input, $algorithm);
                
                // 将加密结果存入数据库（可选，用于后续解密）
                // 注意：实际应用中，加密结果通常是单向哈希，不需要存储原始值
                // 这里仅演示数据库插入功能
                $db->insertHash($result, $input, $algorithm);
                
                $response['success'] = true;
                $response['message'] = '加密成功';
                $response['result'] = $result;
                $response['hash'] = $result;
                $response['original'] = $input;
            } elseif ($action === 'decrypt') {
                $result = $db->queryHash($input, $algorithm);
                
                $response['success'] = $result['found'];
                $response['message'] = $result['found'] ? '解密成功' : '未找到匹配项';
                $response['result'] = $result['found'] ? 
                    '原始字符串: ' . $result['original_string'] : 
                    getNotFoundMessage($input, $algorithm);
                $response['found'] = $result['found'];
                $response['original_string'] = isset($result['original_string']) ? $result['original_string'] : '';
                $response['source'] = isset($result['source']) ? $result['source'] : 'unknown';
            } elseif ($action === 'batch_decrypt') {
                // 批量解密功能
                $hashes = json_decode($input, true);
                if (!is_array($hashes) || empty($hashes)) {
                    throw new Exception('无效的批量哈希数据');
                }
                
                // 限制批量处理数量
                if (count($hashes) > 100) {
                    throw new Exception('批量处理限制：最多100个哈希值');
                }
                
                $results = $db->batchQuery($hashes, $algorithm);
                $response['success'] = true;
                $response['message'] = '批量查询完成';
                $response['results'] = $results;
                $response['total'] = count($hashes);
                $response['found'] = count(array_filter($results, function($r) { return $r['found']; }));
            } else {
                throw new Exception('无效的操作类型，请指定 action=encrypt, action=decrypt 或 action=batch_decrypt');
            }
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['result'] = '';
}

// 计算查询时间
$response['query_time'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

// 输出JSON响应
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

/**
 * 加密文本
 *
 * @param string $input 要加密的文本
 * @param string $algorithm 算法类型
 * @return string 加密结果
 */
function encryptText($input, $algorithm) {
    switch ($algorithm) {
        case 'md5':
            return md5($input);
        case 'md5-upper':
            return strtoupper(md5($input));
        case 'md5-16':
            return substr(md5($input), 8, 16);
        case 'md5-16-upper':
            return strtoupper(substr(md5($input), 8, 16));
        case 'sha1':
            return sha1($input);
        case 'sha256':
            return hash('sha256', $input);
        case 'mysql':
            // MySQL风格的密码哈希（旧版本）
            return '*' . strtoupper(sha1(sha1($input, true)));
        case 'base64':
            return base64_encode($input);
        default:
            return md5($input);
    }
}

/**
 * 获取未找到哈希时的消息
 *
 * @param string $hash 哈希值
 * @param string $algorithm 算法类型
 * @return string 错误消息
 */
function getNotFoundMessage($hash, $algorithm) {
    $message = "抱歉，在数据库中未找到此哈希对应的原始字符串。\n\n";
    $message .= "哈希: " . $hash . "\n";
    $message .= "算法: " . $algorithm . "\n\n";
    $message .= "可能原因：\n";
    $message .= "1. 该哈希值尚未被收录到我们的数据库中\n";
    $message .= "2. 原始字符串非常复杂或长度较长\n";
    $message .= "3. 该哈希值使用了加盐处理\n\n";
    $message .= "建议：\n";
    $message .= "- 使用我们的高级解密服务（侧边栏广告）\n";
    $message .= "- 尝试其他可能的原始字符串组合\n";
    $message .= "- 检查哈希值格式是否正确";
    
    return $message;
}
?>
