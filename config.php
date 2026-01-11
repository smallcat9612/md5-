<?php
/**
 * 数据库配置文件
 * 支持ClickHouse + Redis架构，用于千亿级别数据量
 */

// 运行模式：production（生产模式）或 simulation（模拟模式）
define('RUN_MODE', 'simulation'); // 默认使用模拟模式，避免依赖外部服务

// ClickHouse 配置
define('CLICKHOUSE_HOST', 'localhost');
define('CLICKHOUSE_PORT', 8123);
define('CLICKHOUSE_USER', 'default');
define('CLICKHOUSE_PASSWORD', '');
define('CLICKHOUSE_DATABASE', 'hash_database');
define('CLICKHOUSE_TABLE', 'hash_table');

// Redis 配置
define('REDIS_HOST', 'localhost');
define('REDIS_PORT', 6379);
define('REDIS_PASSWORD', '');
define('REDIS_DATABASE', 0);
define('REDIS_PREFIX', 'md5tool:');
define('REDIS_CACHE_TTL', 3600); // 缓存时间（秒）

// 数据库表结构定义
define('HASH_TABLE_SCHEMA', [
    'hash' => 'String',
    'original_string' => 'String',
    'algorithm' => 'String',
    'length' => 'Int32',
    'created_date' => 'Date',
    'query_count' => 'UInt64'
]);

// 算法支持列表
define('SUPPORTED_ALGORITHMS', [
    'md5' => 'MD5 (32位小写)',
    'md5-upper' => 'MD5 (32位大写)',
    'md5-16' => 'MD5 (16位小写)',
    'md5-16-upper' => 'MD5 (16位大写)',
    'sha1' => 'SHA1',
    'sha256' => 'SHA256',
    'mysql' => 'MySQL密码',
    'base64' => 'Base64编码'
]);

// 模拟数据库数据（用于模拟模式）
$MOCK_DATABASE = [
    'e10adc3949ba59abbe56e057f20f883e' => '123456',
    '21232f297a57a5a743894a0e4a801fc3' => 'admin',
    '5f4dcc3b5aa765d61d8327deb882cf99' => 'password',
    'd41d8cd98f00b204e9800998ecf8427e' => '',
    '827ccb0eea8a706c4c34a16891f84e7b' => '12345',
    '098f6bcd4621d373cade4e832627b4f6' => 'test',
    '25d55ad283aa400af464c76d713c07ad' => '12345678',
    'e99a18c428cb38d5f260853678922e03' => 'abc123',
    'fcea920f7412b5da7be0cf42b8c93759' => '1234567',
    'c33367701511b4f6020ec61ded352059' => '654321',
    '482c811da5d5b4bc6d497ffa98491e38' => 'password123',
    '9d4e1e23bd5b727046a9e3b4b7db57bd' => 'hello',
    '900150983cd24fb0d6963f7d28e17f72' => 'abc',
    '0cc175b9c0f1b6a831c399e269772661' => 'a',
    '92eb5ffee6ae2fec3ad71c777531578f' => 'b',
    '4a8a08f09d37b73795649038408b5f33' => 'c',
    'f1c1592588411002af340cbaedd6fc33' => 'hello world',
    '25f9e794323b453885f5181f1b624d0b' => '123456789',
    'd8578edf8458ce06fbc5bb76a58c5ca4' => 'qwerty',
];

// ClickHouse 建表SQL
define('CLICKHOUSE_CREATE_TABLE_SQL', "
CREATE TABLE IF NOT EXISTS " . CLICKHOUSE_DATABASE . "." . CLICKHOUSE_TABLE . " (
    hash String,
    original_string String,
    algorithm String,
    length Int32,
    created_date Date DEFAULT today(),
    query_count UInt64 DEFAULT 0
) ENGINE = MergeTree()
ORDER BY (hash, algorithm)
PARTITION BY toYYYYMM(created_date)
SETTINGS index_granularity = 8192;
");

// 性能监控配置
define('ENABLE_PERFORMANCE_MONITOR', true);
define('QUERY_LOG_PATH', __DIR__ . '/logs/queries.log');
define('ERROR_LOG_PATH', __DIR__ . '/logs/errors.log');

// 确保日志目录存在
if (!is_dir(__DIR__ . '/logs')) {
    @mkdir(__DIR__ . '/logs', 0755, true);
}
?>
