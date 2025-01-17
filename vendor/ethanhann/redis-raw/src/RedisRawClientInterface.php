<?php

namespace Ehann\RedisRaw;

use Psr\Log\LoggerInterface;

interface RedisRawClientInterface
{
    public function connect($hostname = '127.0.0.1', $port = 6379, $db = 0, $password = null): RedisRawClientInterface;
    public function flushAll();
    public function multi(bool $usePipeline = false);
    public function rawCommand(string $command, array $arguments);
    public function setLogger(LoggerInterface $logger): RedisRawClientInterface;
    public function prepareRawCommandArguments(string $command, array $arguments) : array;
    public function validateRawCommandResults($rawResult);
}
