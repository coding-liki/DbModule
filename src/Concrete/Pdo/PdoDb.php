<?php
namespace CodingLiki\Db\Concrete\Pdo;

use CodingLiki\Db\Concrete\Pdo\Drivers\DriverInterface;
use CodingLiki\Db\Concrete\Pdo\Drivers\PostgreSqlDriver;
use CodingLiki\Db\Concrete\Pdo\Exceptions\DriverIsNotInstalled;
use CodingLiki\Db\Concrete\Pdo\Exceptions\DriverNotKnown;
use CodingLiki\Db\DbInterface;
use CodingLiki\Db\QueryResultInterface;
use PDO;
use PDOStatement;

class PdoDb implements DbInterface
{
    private const KNOWN_DRIVERS = [
        'postgreSql' => PostgreSqlDriver::class,
        'psql' => PostgreSqlDriver::class,
        'pgsql' => PostgreSqlDriver::class,
    ];

    private PDO $connection;

    public function __construct(string $dsn, string $login, string $password, array $params) {
        $this->connection = new PDO($dsn, $login, $password, $params);
    }

    public static function connect(array $params): static
    {
        $dbLogin = $params['login'];
        $dbPassword = $params['password'];
        $dbParams = $params['params'] ?? 
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

        return new static(static::buildDsnFromParams($params), $dbLogin, $dbPassword, $dbParams);
    }

    public function query(string $query, array $params): QueryResultInterface
    {

        [$normalizedQuery, $normalizedParams] = $this->normalizeQueryAndParams($query, $params);

        $statement = $this->connection->prepare($normalizedQuery, [
            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
        ]);

        $statement($this->bindParametersToStatement($statement, $normalizedParams));

        $executeResult = $statement->execute();

        return new PdoQueryResult($statement);
    }

    private function normalizeQueryAndParams(string $query, array $params)
    {
        foreach($params as $name => $parameter) {
            if(is_array($parameter)){
                $newName = '';
                foreach($parameter as $key => $value){
                    $newKey = $name.'_'.$key;
                    $newName .= ",$newKey";

                    $params[$newKey] = $value;
                }

                $query = str_replace($name, $newName, $query);
                unset($params[$name]);
            }
        }

        return [$query, $params];
    }
    private function bindParametersToStatement(PDOStatement &$statement, $params) {
        foreach($params as $name => $value){
            switch(true){
                case is_integer($value):
                    $statement->bindParam($name, $value, PDO::PARAM_INT);
                    break;
                case is_bool($value):
                    $statement->bindParam($name, $value, PDO::PARAM_BOOL);
                    break;
                default: 
                    $statement->bindParam($name, $value);
                    break;
            }
        }
    }
    private static function buildDsnFromParams(array $params): string
    {
        $driverName = $params['driver'];

        

        $driver = static::getDriver($driverName);

        if(!$driver->checkInstalled()) {
            throw new DriverIsNotInstalled($driverName, $driver->getInstallationDescription());
        }
        
        
        return $driver->buildDsnFromParams($params);
    }

    private static function getDriver(string $driverName): DriverInterface
    {
        $driverClass = self::KNOWN_DRIVERS[$driverName] ?? null;

        if($driverClass === null){
            throw new DriverNotKnown($driverName);
        }

        return new $driverClass;
    }
}

