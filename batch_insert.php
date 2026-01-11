<?php
/**
 * 批量数据插入脚本
 * 专门用于生成数字序列并插入数据库
 * 
 * 示例：生成1-1000的数字，加密后插入数据库
 */

require_once 'config.php';
require_once 'Database.php';

// 命令行参数解析
$options = getopt("", ["start:", "end:", "algorithm:", "mode:", "help"]);

if (isset($options['help'])) {
    showHelp();
    exit(0);
}

$start = isset($options['start']) ? (int)$options['start'] : 1;
$end = isset($options['end']) ? (int)$options['end'] : 1000;
$algorithm = isset($options['algorithm']) ? $options['algorithm'] : 'md5';
$mode = isset($options['mode']) ? $options['mode'] : 'simulation';

// 验证参数
if ($start <= 0 || $end <= 0 || $start > $end) {
    echo "错误: 起始值必须小于等于结束值，且都大于0\n";
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

$count = $end - $start + 1;

echo "=== 数字序列批量插入工具 ===\n";
echo "起始数字: $start\n";
echo "结束数字: $end\n";
echo "生成数量: $count\n";
echo "加密算法: $algorithm\n";
echo "运行模式: $mode\n";
echo "开始时间: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("-", 60) . "\n";

// 初始化数据库连接
$db = new Database();

// 如果指定了模式，切换到该模式
if ($mode === 'production') {
    $db->switchToProduction();
    echo "✓ 切换到生产模式 (ClickHouse + Redis)\n";
} else {
    $db->switchToSimulation();
    echo "✓ 使用模拟模式 (PHP数组)\n";
}

// 插入数据
$successCount = 0;
$failCount = 0;
$startTime = microtime(true);

for ($i = $start; $i <= $end; $i++) {
    $originalString = (string)$i;
    $hash = encryptText($originalString, $algorithm);
    
    echo sprintf("处理 %6d/%d: %s → %s", 
        $i, $end, 
        $originalString,
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
    
    // 每50条显示一次进度
    if (($i - $start + 1) % 50 === 0) {
        $current = $i - $start + 1;
        echo sprintf("进度: %d/%d (%.1f%%) - 成功: %d, 失败: %d\n", 
            $current, $count, 
            $current / $count * 100,
            $successCount, $failCount
        );
    }
}

$totalTime = microtime(true) - $startTime;

echo str_repeat("-", 60) . "\n";
echo "批量插入完成!\n";
echo "成功插入: $successCount 条\n";
echo "失败插入: $failCount 条\n";
echo "总耗时: " . round($totalTime, 2) . " 秒\n";
echo "平均每条: " . round($totalTime / $count * 1000, 2) . " 毫秒\n";
echo "结束时间: " . date('Y-m-d H:i:s') . "\n";

// 显示统计信息
echo "\n=== 数据库统计信息 ===\n";
$stats = $db->getStatistics();
echo "数据库总记录数: " . (isset($stats['total_records']) ? $stats['total_records'] : 'N/A') . "\n";
if (isset($stats['formatted_records'])) {
    echo "格式化记录数: " . $stats['formatted_records'] . "\n";
}
echo "当前模式: " . $db->getMode() . "\n";

if ($mode === 'simulation') {
    echo "\n提示: 在模拟模式下，数据仅保存在内存中，重启后消失。\n";
    echo "      如需持久化存储，请使用 --mode=production 参数。\n";
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
    echo "数字序列批量插入工具\n\n";
    echo "专门用于生成数字序列并插入到MD5数据库中。\n\n";
    echo "使用方法:\n";
    echo "  php batch_insert.php [选项]\n\n";
    echo "选项:\n";
    echo "  --start=数字       起始数字 (默认: 1)\n";
    echo "  --end=数字         结束数字 (默认: 1000)\n";
    echo "  --algorithm=算法   使用的加密算法 (默认: md5)\n";
    echo "                    可选算法: md5, md5-upper, md5-16, md5-16-upper,\n";
    echo "                            sha1, sha256, mysql, base64\n";
    echo "  --mode=模式        运行模式: simulation 或 production (默认: simulation)\n";
    echo "  --help            显示此帮助信息\n\n";
    echo "示例:\n";
    echo "  # 生成1-9的数字并插入\n";
    echo "  php batch_insert.php --start=1 --end=9 --algorithm=md5\n";
    echo "  \n";
    echo "  # 生成1-100的数字，使用SHA1算法\n";
    echo "  php batch_insert.php --start=1 --end=100 --algorithm=sha1\n";
    echo "  \n";
    echo "  # 生成1000-2000的数字，使用生产模式\n";
    echo "  php batch_insert.php --start=1000 --end=2000 --mode=production\n";
    echo "  \n";
    echo "  # 生成简单的1-10测试数据\n";
    echo "  php batch_insert.php --start=1 --end=10 --mode=simulation\n\n";
    echo "注意:\n";
    echo "  1. 在simulation模式下，数据仅保存在内存中，程序结束即消失\n";
    echo "  2. 在production模式下，需要配置ClickHouse和Redis服务\n";
    echo "  3. 插入大量数据时建议分批次进行，避免内存溢出\n";
}
?>
