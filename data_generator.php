<?php
/**
 * 数据生成器脚本
 * 自动生成测试数据并插入到数据库中
 * 
 * 使用方法: php data_generator.php [选项]
 * 选项:
 *   --count=数字   生成的数据条数 (默认: 100)
 *   --algorithm=算法 使用的加密算法 (默认: md5)
 *   --mode=模式     运行模式: simulation 或 production (默认: simulation)
 *   --help         显示帮助信息
 */

require_once 'config.php';
require_once 'Database.php';

// 命令行参数解析
$options = getopt("", ["count:", "algorithm:", "mode:", "help"]);

if (isset($options['help'])) {
    showHelp();
    exit(0);
}

$count = isset($options['count']) ? (int)$options['count'] : 100;
$algorithm = isset($options['algorithm']) ? $options['algorithm'] : 'md5';
$mode = isset($options['mode']) ? $options['mode'] : 'simulation';

// 验证参数
if ($count <= 0) {
    echo "错误: 生成数量必须大于0\n";
    exit(1);
}

if (!in_array($algorithm, array_keys(SUPPORTED_ALGORITHMS))) {
    echo "错误: 不支持的算法。支持的算法: " . implode(', ', array_keys(SUPPORTED_ALGORITHMS)) . "\n";
    exit(1);
}

if (!in_array($mode, ['simulation', 'production'])) {
    echo "错误: 模式必须是 simulation 或 production\n";
    exit(1);
}

echo "=== MD5数据库数据生成器 ===\n";
echo "生成数量: $count\n";
echo "加密算法: $algorithm\n";
echo "运行模式: $mode\n";
echo "开始时间: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("-", 50) . "\n";

// 初始化数据库连接
$db = new Database();

// 如果指定了模式，切换到该模式
if ($mode === 'production') {
    $db->switchToProduction();
    echo "切换到生产模式 (ClickHouse + Redis)\n";
} else {
    $db->switchToSimulation();
    echo "使用模拟模式 (PHP数组)\n";
}

// 生成测试数据
$testData = generateTestData($count);

// 插入数据
$successCount = 0;
$failCount = 0;
$startTime = microtime(true);

foreach ($testData as $index => $originalString) {
    $hash = encryptText($originalString, $algorithm);
    
    echo sprintf("处理 %4d/%d: %-20s → %s", 
        $index + 1, $count, 
        strlen($originalString) > 20 ? substr($originalString, 0, 17) . '...' : $originalString,
        $hash
    );
    
    $result = $db->insertHash($hash, $originalString, $algorithm);
    
    if ($result) {
        echo " [✓]\n";
        $successCount++;
    } else {
        echo " [✗]\n";
        $failCount++;
    }
    
    // 每10条显示一次进度
    if (($index + 1) % 10 === 0) {
        echo sprintf("进度: %d/%d (%.1f%%)\n", 
            $index + 1, $count, 
            ($index + 1) / $count * 100
        );
    }
}

$totalTime = microtime(true) - $startTime;

echo str_repeat("-", 50) . "\n";
echo "数据生成完成!\n";
echo "成功插入: $successCount 条\n";
echo "失败插入: $failCount 条\n";
echo "总耗时: " . round($totalTime, 2) . " 秒\n";
echo "平均每条: " . round($totalTime / $count * 1000, 2) . " 毫秒\n";
echo "结束时间: " . date('Y-m-d H:i:s') . "\n";

if ($mode === 'simulation') {
    echo "\n提示: 在模拟模式下，数据仅保存在内存中，重启后消失。\n";
    echo "      如需持久化存储，请使用 --mode=production 参数。\n";
}

/**
 * 生成测试数据
 */
function generateTestData($count) {
    $data = [];
    
    // 基础数据集
    $baseData = [
        // 数字序列
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '0',
        '10', '11', '12', '13', '14', '15', '16', '17', '18', '19',
        '20', '21', '22', '23', '24', '25', '26', '27', '28', '29',
        
        // 常见密码
        'password', '123456', 'admin', '12345678', '123456789',
        'qwerty', 'abc123', 'password1', '12345', '1234',
        '111111', '1234567', 'dragon', '123123', 'baseball',
        'football', 'letmein', 'monkey', 'shadow', 'master',
        
        // 常见单词
        'hello', 'world', 'test', 'data', 'string',
        'example', 'sample', 'demo', 'temp', 'file',
        'user', 'name', 'email', 'phone', 'address',
        'city', 'country', 'state', 'zip', 'code',
        
        // 组合数据
        'user123', 'test2023', 'admin@123', 'pass#word', 'secure123',
        'hello123', 'world2023', 'demo_user', 'temp_pass', 'file_name',
    ];
    
    // 如果需要的数量少于基础数据集，则取子集
    if ($count <= count($baseData)) {
        return array_slice($baseData, 0, $count);
    }
    
    // 否则使用基础数据集，并生成更多数据
    $data = $baseData;
    
    // 生成更多组合数据
    $prefixes = ['user', 'test', 'admin', 'demo', 'temp', 'my', 'new', 'old', 'big', 'small'];
    $suffixes = ['123', '456', '789', '2023', '2024', 'abc', 'xyz', 'test', 'demo', 'data'];
    $words = ['hello', 'world', 'password', 'secure', 'random', 'string', 'number', 'code', 'name', 'file'];
    
    for ($i = count($data); $i < $count; $i++) {
        // 随机生成不同类型的数据
        $type = rand(0, 5);
        
        switch ($type) {
            case 0: // 纯数字
                $data[] = (string)rand(100000, 999999);
                break;
                
            case 1: // 单词+数字
                $data[] = $words[array_rand($words)] . rand(10, 999);
                break;
                
            case 2: // 前缀+后缀
                $data[] = $prefixes[array_rand($prefixes)] . $suffixes[array_rand($suffixes)];
                break;
                
            case 3: // 随机字符串
                $length = rand(5, 15);
                $data[] = generateRandomString($length);
                break;
                
            case 4: // 邮箱格式
                $data[] = $prefixes[array_rand($prefixes)] . rand(1, 99) . '@example.com';
                break;
                
            default: // 单词组合
                $data[] = $words[array_rand($words)] . '_' . $words[array_rand($words)];
                break;
        }
    }
    
    return $data;
}

/**
 * 生成随机字符串
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

/**
 * 加密文本
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
            return '*' . strtoupper(sha1(sha1($input, true)));
        case 'base64':
            return base64_encode($input);
        default:
            return md5($input);
    }
}

/**
 * 显示帮助信息
 */
function showHelp() {
    echo <<<HELP
MD5数据库数据生成器

使用方法:
  php data_generator.php [选项]

选项:
  --count=数字      生成的数据条数 (默认: 100)
  --algorithm=算法  使用的加密算法 (默认: md5)
                    可选算法: md5, md5-upper, md5-16, md5-16-upper,
                            sha1, sha256, mysql, base64
  --mode=模式       运行模式: simulation 或 production (默认: simulation)
  --help           显示此帮助信息

示例:
  php data_generator.php --count=50 --algorithm=md5
  php data_generator.php --count=200 --algorithm=sha1 --mode=simulation
  php data_generator.php --count=1000 --mode=production

HELP;
}
?>
