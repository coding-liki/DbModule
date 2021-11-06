<?php
namespace CodingLiki\DbModule;

use CodingLiki\DbModule\Concrete\Pdo\PdoDb;
use CodingLiki\DbModule\Exceptions\DbTypeNotKnownException;

class DbContainer
{
    public const DEFAULT_DB_NAME = 'main';

    private const KNOWN_DB_TYPES = [
        'pdo' => PdoDb::class
    ];

    /** 
     * @var DbInterface[]
     */
    private static array $databases = [];

    public static function get(string $dbName = self::DEFAULT_DB_NAME): ?DbInterface
    {
        return self::$databases[$dbName] ?? null;
    }
    
    public static function add(array $params, string $name = self::DEFAULT_DB_NAME): DbInterface
    {
        $dbType = $params['dbType'] ?? 'pdo';

        /** @var DbInterface $dbClass  */
        $dbClass = self::KNOWN_DB_TYPES[$dbType] ?? null;

        if($dbClass === null){
            throw new DbTypeNotKnownException($dbType);
        }

        $db = $dbClass::connect($params);

        self::$databases[$name] = $db;

        return $db;
    }
}

