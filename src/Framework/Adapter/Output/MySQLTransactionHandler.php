<?php

namespace App\Framework\Adapter\Output;

use App\Application\Port\Output\TransactionHandlerPort;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class MySQLTransactionHandler implements TransactionHandlerPort
{

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws \Throwable
     */
    public function execute(callable $callable): mixed
    {
        $this->open();
        try {
            $result = $callable();
            $this->commit();
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function open(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * @throws Exception
     */
    public function commit(): void
    {
        $this->connection->commit();
    }

    /**
     * @throws Exception
     */
    public function rollback(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->rollback();
    }
}