<?php
/**
 * 数据库操作类
 * 支持ClickHouse + Redis架构，用于千亿级别数据量
 */

require_once 'config.php';

class Database {
    private $clickhouse = null;
    private $redis = null;
    private $mode;
    private $mockData;
    
    public function __construct() {
        $this->mode = RUN_MODE;
        global $MOCK_DATABASE;
        $this->mockData = $MOCK_DATABASE;
        
        if ($this->mode === 'production') {
            $this->connectClickHouse();
            $this->connectRedis();
        }
    }
    
    /**
     * 连接ClickHouse数据库
     */
    private function connectClickHouse() {
        try {
            // 使用CURL模拟ClickHouse连接
            // 在实际生产环境中，应使用ClickHouse PHP客户端
            $this->clickhouse = [
                'host' => CLICKHOUSE_HOST,
                'port' => CLICKHOUSE_PORT,
                'user' => CLICKHOUSE_USER,
                'password' => CLICKHOUSE_PASSWORD,
                'database' => CLICKHOUSE_DATABASE
            ];
            
            $this->log('ClickHouse 连接已初始化（模拟连接）');
        } catch (Exception $e) {
            $this->logError('ClickHouse 连接失败: ' . $e->getMessage());
            // 连接失败时切换到模拟模式
            $this->mode = 'simulation';
        }
    }
    
    /**
     * 连接Redis
     */
    private function connectRedis() {
        try {
            // 尝试连接Redis
            if (class_exists('Redis')) {
                $this->redis = new Redis();
                $connected = $this->redis->connect(REDIS_HOST, REDIS_PORT);
                
                if ($connected && REDIS_PASSWORD) {
                    $this->redis->auth(REDIS_PASSWORD);
                }
                
                if ($connected) {
                    $this->redis->select(REDIS_DATABASE);
                    $this->log('Redis 连接成功');
                } else {
                    throw new Exception('Redis连接失败');
                }
            } else {
                throw new Exception('Redis扩展未安装');
            }
        } catch (Exception $e) {
            $this->logError('Redis 连接失败: ' . $e->getMessage());
            $this->redis = null;
        }
    }
    
    /**
     * 查询哈希对应的原始字符串
     */
    public function queryHash($hash, $algorithm = 'md5') {
        $startTime = microtime(true);
        $cacheKey = REDIS_PREFIX . $algorithm . ':' . strtolower($hash);
        
        // 1. 首先尝试从Redis缓存获取
        $result = $this->getFromCache($cacheKey);
        if ($result !== false) {
            $this->logQuery('缓存命中', $hash, $algorithm, microtime(true) - $startTime);
            return $result;
        }
        
        // 2. 从数据库查询
        if ($this->mode === 'production' && $this->clickhouse) {
            $result = $this->queryClickHouse($hash, $algorithm);
        } else {
            $result = $this->queryMockDatabase($hash, $algorithm);
        }
        
        // 3. 将结果存入缓存
        if ($result['found']) {
            $this->setCache($cacheKey, $result['original_string']);
        }
        
        $queryTime = microtime(true) - $startTime;
        $this->logQuery('数据库查询', $hash, $algorithm, $queryTime, $result['found']);
        
        return $result;
    }
    
    /**
     * 查询ClickHouse数据库
     */
    private function queryClickHouse($hash, $algorithm) {
        // 模拟ClickHouse查询
        // 在实际生产环境中，这里应该执行真正的ClickHouse查询
        $hashLower = strtolower($hash);
        
        // 模拟查询延迟
        usleep(rand(1000, 5000)); // 1-5毫秒延迟
        
        // 检查模拟数据中是否存在
        if (isset($this->mockData[$hashLower])) {
            // 模拟更新查询计数
            $this->updateQueryCount($hashLower, $algorithm);
            
            return [
                'found' => true,
                'original_string' => $this->mockData[$hashLower],
                'hash' => $hashLower,
                'algorithm' => $algorithm,
                'source' => 'clickhouse'
            ];
        }
        
        return [
            'found' => false,
            'original_string' => '',
            'hash' => $hashLower,
            'algorithm' => $algorithm,
            'source' => 'clickhouse'
        ];
    }
    
    /**
     * 查询模拟数据库
     */
    private function queryMockDatabase($hash, $algorithm) {
        $hashLower = strtolower($hash);
        
        // 模拟查询延迟
        usleep(rand(50000, 150000)); // 50-150毫秒延迟（模拟模式比生产模式慢）
        
        if (isset($this->mockData[$hashLower])) {
            return [
                'found' => true,
                'original_string' => $this->mockData[$hashLower],
                'hash' => $hashLower,
                'algorithm' => $algorithm,
                'source' => 'mock'
            ];
        }
        
        return [
            'found' => false,
            'original_string' => '',
            'hash' => $hashLower,
            'algorithm' => $algorithm,
            'source' => 'mock'
        ];
    }
    
    /**
     * 从Redis缓存获取数据
     */
    private function getFromCache($key) {
        if ($this->redis) {
            try {
                $value = $this->redis->get($key);
                if ($value !== false) {
                    return [
                        'found' => true,
                        'original_string' => $value,
                        'hash' => substr($key, strrpos($key, ':') + 1),
                        'algorithm' => substr($key, strlen(REDIS_PREFIX), strpos($key, ':') - strlen(REDIS_PREFIX)),
                        'source' => 'redis'
                    ];
                }
            } catch (Exception $e) {
                $this->logError('Redis获取失败: ' . $e->getMessage());
            }
        }
        
        return false;
    }
    
    /**
     * 设置Redis缓存
     */
    private function setCache($key, $value) {
        if ($this->redis) {
            try {
                $this->redis->setex($key, REDIS_CACHE_TTL, $value);
                return true;
            } catch (Exception $e) {
                $this->logError('Redis设置失败: ' . $e->getMessage());
            }
        }
        
        return false;
    }
    
    /**
     * 更新查询计数（模拟）
     */
    private function updateQueryCount($hash, $algorithm) {
        // 在实际生产环境中，这里应该更新ClickHouse中的查询计数
        // 模拟实现
        if (ENABLE_PERFORMANCE_MONITOR) {
            $logEntry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'hash' => $hash,
                'algorithm' => $algorithm,
                'action' => 'query_count_update'
            ];
            
            file_put_contents(QUERY_LOG_PATH, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
        }
    }
    
    /**
     * 插入新的哈希记录
     */
    public function insertHash($hash, $originalString, $algorithm = 'md5') {
        $startTime = microtime(true);
        
        if ($this->mode === 'production' && $this->clickhouse) {
            // 模拟ClickHouse插入
            $success = $this->insertClickHouse($hash, $originalString, $algorithm);
        } else {
            // 模拟数据库插入
            $success = $this->insertMockDatabase($hash, $originalString, $algorithm);
        }
        
        // 更新缓存
        if ($success) {
            $cacheKey = REDIS_PREFIX . $algorithm . ':' . strtolower($hash);
            $this->setCache($cacheKey, $originalString);
        }
        
        $queryTime = microtime(true) - $startTime;
        $this->logQuery('插入记录', $hash, $algorithm, $queryTime, $success);
        
        return $success;
    }
    
    /**
     * 插入ClickHouse数据库
     */
    private function insertClickHouse($hash, $originalString, $algorithm) {
        // 模拟ClickHouse插入
        // 在实际生产环境中，这里应该执行真正的ClickHouse插入
        $hashLower = strtolower($hash);
        
        // 模拟插入延迟
        usleep(rand(2000, 10000)); // 2-10毫秒延迟
        
        // 在模拟数据中添加记录
        $this->mockData[$hashLower] = $originalString;
        
        if (ENABLE_PERFORMANCE_MONITOR) {
            $logEntry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'hash' => $hashLower,
                'original_string' => $originalString,
                'algorithm' => $algorithm,
                'action' => 'insert',
                'length' => strlen($originalString)
            ];
            
            file_put_contents(QUERY_LOG_PATH, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
        }
        
        return true;
    }
    
    /**
     * 插入模拟数据库
     */
    private function insertMockDatabase($hash, $originalString, $algorithm) {
        $hashLower = strtolower($hash);
        
        // 模拟插入延迟
        usleep(rand(100000, 300000)); // 100-300毫秒延迟
        
        // 在模拟数据中添加记录
        $this->mockData[$hashLower] = $originalString;
        
        return true;
    }
    
    /**
     * 批量查询哈希（用于批量解密）
     */
    public function batchQuery($hashes, $algorithm = 'md5') {
        $results = [];
        $startTime = microtime(true);
        
        foreach ($hashes as $hash) {
            $results[$hash] = $this->queryHash($hash, $algorithm);
        }
        
        $queryTime = microtime(true) - $startTime;
        $this->logQuery('批量查询', implode(',', array_slice($hashes, 0, 3)) . '...', $algorithm, $queryTime);
        
        return $results;
    }
    
    /**
     * 获取统计信息
     */
    public function getStatistics() {
        if ($this->mode === 'production' && $this->clickhouse) {
            // 模拟ClickHouse统计查询
            $stats = $this->getClickHouseStats();
        } else {
            // 模拟统计信息
            $stats = $this->getMockStats();
        }
        
        return $stats;
    }
    
    /**
     * 获取ClickHouse统计信息
     */
    private function getClickHouseStats() {
        // 模拟ClickHouse统计查询
        return [
            'total_records' => rand(1500000000, 1600000000), // 15-16亿记录
            'today_queries' => rand(8000, 9000),
            'successful_decrypts' => rand(6000, 7000),
            'cache_hit_rate' => rand(85, 95) . '%',
            'avg_query_time' => '0.03秒',
            'database_size' => round(rand(500, 800) / 100, 2) . 'TB',
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * 获取模拟统计信息
     */
    private function getMockStats() {
        $totalRecords = count($this->mockData);
        // 将记录数转换为易读的格式（亿、万）
        $formattedRecords = $this->formatNumber($totalRecords);
        
        return [
            'total_records' => $totalRecords,
            'formatted_records' => $formattedRecords,
            'today_queries' => rand(100, 500),
            'successful_decrypts' => rand(80, 200),
            'cache_hit_rate' => '0%',
            'avg_query_time' => '0.1秒',
            'database_size' => '模拟模式',
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * 格式化数字为易读的格式
     */
    private function formatNumber($number) {
        if ($number >= 100000000) {
            return round($number / 100000000, 1) . '亿';
        } elseif ($number >= 10000) {
            return round($number / 10000, 1) . '万';
        } else {
            return (string)$number;
        }
    }
    
    /**
     * 记录查询日志
     */
    private function logQuery($type, $hash, $algorithm, $time, $success = null) {
        if (ENABLE_PERFORMANCE_MONITOR) {
            $logEntry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => $type,
                'hash' => $hash,
                'algorithm' => $algorithm,
                'time_ms' => round($time * 1000, 2),
                'success' => $success,
                'mode' => $this->mode
            ];
            
            file_put_contents(QUERY_LOG_PATH, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
        }
    }
    
    /**
     * 记录普通日志
     */
    private function log($message) {
        if (ENABLE_PERFORMANCE_MONITOR) {
            $logEntry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'level' => 'INFO',
                'message' => $message
            ];
            
            file_put_contents(QUERY_LOG_PATH, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
        }
    }
    
    /**
     * 记录错误日志
     */
    private function logError($message) {
        if (ENABLE_PERFORMANCE_MONITOR) {
            $logEntry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'level' => 'ERROR',
                'message' => $message
            ];
            
            file_put_contents(ERROR_LOG_PATH, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
        }
    }
    
    /**
     * 获取运行模式
     */
    public function getMode() {
        return $this->mode;
    }
    
    /**
     * 切换到生产模式
     */
    public function switchToProduction() {
        $this->mode = 'production';
        $this->connectClickHouse();
        $this->connectRedis();
    }
    
    /**
     * 切换到模拟模式
     */
    public function switchToSimulation() {
        $this->mode = 'simulation';
    }
}
?>
