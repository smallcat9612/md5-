<?php
/**
 * 简单数据插入脚本
 * 独立运行，不依赖其他配置文件
 * 生成数字序列并模拟插入数据库
 */

// 模拟数据库（数组）
$mockDatabase = [];

// 支持的算法
$supportedAlgorithms = [
    'md5' => 'MD5 (32位小写)',
    'md5-upper' => 'MD5 (32位大写)',
    'md5-16' => 'MD5 (16位小写)',
    'md5-16-upper' => 'MD5 (16位大写)',
    'sha1' => 'SHA1',
    'sha256' => 'SHA256',
    'mysql' => 'MySQL密码',
    'base64' => 'Base64编码'
];

// 命令行参数解析
$options = getopt("", ["start:", "end:", "algorithm:", "help"]);

if (isset($options['help'])) {
    showHelp($supportedAlgorithms);
    exit(0);
}

$start = isset($options['start']) ? (int)$options['start'] : 1;
$end = isset($options['end']) ? (int)$options['end'] : 9;
$algorithm = isset($options['algorithm']) ? $options['algorithm'] : 'md5';

// 验证参数
if ($start <= 0 || $end <= 0 || $start > $end) {
    echo "错误: 起始值必须小于等于结束值，且都大于0\n";
    exit(1);
}

if (!array_key_exists($algorithm, $supportedAlgorithms)) {
    echo "错误: 不支持的算法。支持的算法: " . implode(', ', array_keys($supportedAlgorithms)) . "\n";
    exit(1);
}

$count = $end - $start + 1;

echo "=== 简单数据插入工具 ===\n";
echo "起始数字: $start\n";
echo "结束数字: $end\n";
echo "生成数量: $count\n";
echo "加密算法: $algorithm\n";
echo "开始时间: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("-", 60) . "\n";

// 插入数据
$successCount = 0;
$startTime = microtime(true);

for ($i = $start; $i <= $end; $i++) {
    $originalString = (string)$i;
    $hash = encryptText($originalString, $algorithm);
    
    echo sprintf("处理 %6d/%d: %s → %s", 
        $i, $end, 
        $originalString,
        $hash
    );
    
    // 模拟插入数据库
    $result = insertToMockDatabase($mockDatabase, $hash, $originalString, $algorithm);
    
    if ($result) {
        echo " [✓]\n";
        $successCount++;
    } else {
        echo " [✗]\n";
    }
    
    // 每10条显示一次进度
    if (($i - $start + 1) % 10 === 0) {
        $current = $i - $start + 1;
        echo sprintf("进度: %d/%d (%.1f%%)\n", 
            $current, $count, 
            $current / $count * 100
        );
    }
}

$totalTime = microtime(true) - $startTime;

echo str_repeat("-", 60) . "\n";
echo "数据插入完成!\n";
echo "成功插入: $successCount 条\n";
echo "总耗时: " . round($totalTime, 2) . " 秒\n";
echo "平均每条: " . round($totalTime / $count * 1000, 2) . " 毫秒\n";
echo "结束时间: " . date('Y-m-d H:i:s') . "\n";

// 显示数据库内容
echo "\n=== 数据库内容（前10条） ===\n";
$displayCount = min(10, count($mockDatabase));
if ($displayCount > 0) {
    $index = 0;
    foreach ($mockDatabase as $hash => $record) {
        echo sprintf("%2d. %s → %s (%s)\n", 
            ++$index,
            $record['original'],
            $hash,
            $record['algorithm']
        );
        if ($index >= $displayCount) break;
    }
} else {
    echo "数据库为空\n";
}

echo "\n数据库总记录数: " . count($mockDatabase) . "\n";

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
 * 模拟插入数据库
 */
function insertToMockDatabase(&$database, $hash, $original, $algorithm) {
    // 检查是否已存在
    if (isset($database[$hash])) {
        return false;
    }
    
    // 插入记录
    $database[$hash] = [
        'original' => $original,
        'algorithm' => $algorithm,
        'timestamp' => time()
    ];
    
    return true;
}

/**
 * 显示帮助信息
 */
function showHelp($supportedAlgorithms) {
    echo "简单数据插入工具\n\n";
    echo "独立运行，不依赖外部配置文件。\n\n";
    echo "使用方法:\n";
    echo "  php simple_insert.php [选项]\n\n";
    echo "选项:\n";
    echo "  --start=数字       起始数字 (默认: 1)\n";
    echo "  --end=数字         结束数字 (默认: 9)\n";
    echo "  --algorithm=算法   使用的加密算法 (默认: md5)\n";
    echo "                    可选算法: " . implode(', ', array_keys($supportedAlgorithms)) . "\n";
    echo "  --help            显示此帮助信息\n\n";
    echo "示例:\n";
    echo "  # 生成1-9的数字并插入（默认）\n";
    echo "  php simple_insert.php\n";
    echo "  \n";
    echo "  # 生成1-100的数字，使用SHA1算法\n";
    echo "  php simple_insert.php --start=1 --end=100 --algorithm=sha1\n";
    echo "  \n";
    echo "  # 生成简单的1-10测试数据\n";
    echo "  php simple_insert.php --start=1 --end=10\n\n";
    echo "注意:\n";
    echo "  数据仅保存在内存中，程序结束即消失。用于测试和演示。\n";
}
?>
