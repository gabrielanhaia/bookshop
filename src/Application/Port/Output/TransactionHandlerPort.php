<?php

namespace App\Application\Port\Output;

interface TransactionHandlerPort
{
    public function execute(callable $callable): mixed;

    public function open(): void;

    public function commit(): void;

    public function rollback(): void;

}