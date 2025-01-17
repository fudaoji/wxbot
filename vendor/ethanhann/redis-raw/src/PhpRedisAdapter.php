<?php

namespace Ehann\RedisRaw;

use Redis;
use RedisException;

/**
 * Class PhpRedisAdapter
 * @package Ehann\RedisRaw
 *
 * This class wraps the PhpRedis client: https://github.com/phpredis/phpredis
 */
class PhpRedisAdapter extends AbstractRedisRawClient
{
    /** @var Redis */
    public $redis;

    public function connect($hostname = '127.0.0.1', $port = 6379, $db = 0, $password = null): RedisRawClientInterface
    {
        $this->redis = new Redis();
        $this->redis->connect($hostname, $port);
        $this->redis->auth($password);
        $this->redis->select($db);
        return $this;
    }

    public function multi(bool $usePipeline = false)
    {
        return $this->redis->multi($usePipeline ? Redis::PIPELINE : Redis::MULTI);
    }

    public function rawCommand(string $command, array $arguments)
    {
        $arguments = $this->prepareRawCommandArguments($command, $arguments);
        $rawResult = null;
        try {
            $rawResult = call_user_func_array([$this->redis, 'rawCommand'], $arguments);
        } catch (RedisException $exception) {
            $this->validateRawCommandResults($exception);
        }
        return $this->normalizeRawCommandResult($rawResult);
    }
}
