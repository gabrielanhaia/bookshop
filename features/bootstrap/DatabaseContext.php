<?php

namespace App\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use PDO;
use Exception;
use Symfony\Component\Dotenv\Dotenv;

class DatabaseContext implements Context
{
    private $pdo;

    public function __construct()
    {
        // Load environment variables from .env file
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = new Dotenv();
            $dotenv->load(__DIR__ . '/../../.env');
        }

        $databaseUrl = getenv('DATABASE_URL');
        if (!$databaseUrl) {
            throw new Exception('DATABASE_URL environment variable is not set.');
        }

        $url = parse_url($databaseUrl);
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $url['host'],
            ltrim($url['path'], '/')
        );

        $this->pdo = new PDO($dsn, $url['user'], $url['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    /**
     * @BeforeScenario @db
     */
    public function clearDatabase(BeforeScenarioScope $scope): void
    {
        if ('test' !== getenv('APP_ENV')) {
            throw new Exception('This method should only be called in the test environment');
        }

        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $tables = $this->pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $this->pdo->exec("TRUNCATE TABLE `$table`");
        }
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}
