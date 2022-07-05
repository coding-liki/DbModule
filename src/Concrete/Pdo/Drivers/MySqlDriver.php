<?php
namespace CodingLiki\DbModule\Concrete\Pdo\Drivers;

class MySqlDriver implements DriverInterface
{
    private const DEFAULT_PORT = 3306;
    
    public function buildDsnFromParams(array $params): string
    {
        $dbHost  = $params['host'];
        $dbPort  = $params['port'] ?? self::DEFAULT_PORT;

        $dbName = $params['name'];


        return sprintf('mysql:host=%s;port=%d;dbname=%s;', $dbHost, $dbPort, $dbName);
    }

    public function checkInstalled(): bool
    {
        return true;
        // return extension_loaded('pdo_pgsql');
    }

    public function getInstallationDescription(): string
    {
        $phpVersion = phpversion();
        return sprintf('for debian-based use "sudo apt install php%s-pgsql"', $phpVersion);
    }
}

