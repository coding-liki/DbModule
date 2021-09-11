<?php
namespace CodingLiki\Db\Concrete\Pdo\Drivers;

class PostgreSqlDriver implements DriverInterface
{
    private const DEFAULT_PORT = 5432;
    
    public function buildDsnFromParams(array $params): string
    {
        $dbHost  = $params['host'];
        $dbPort  = $params['port'] ?? self::DEFAULT_PORT;

        $dbName = $params['name'];


        return sprintf('pgsql:host=%s;port=%d;dbname=%s;', $dbHost, $dbPort, $dbName);
    }

    public function checkInstalled(): bool
    {
        return extension_loaded('pdo_pgsql');
    }

    public function getInstallationDescription(): string
    {
        $phpVersion = phpversion();
        return sprintf('for debian-based use "sudo apt install php%s-pgsql"', $phpVersion);
    }
}

