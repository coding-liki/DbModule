<?php
namespace CodingLiki\DbModule;

use CodingLiki\DbModule\Concrete\Pdo\PdoDb;
use CodingLiki\DbModule\Exceptions\DbTypeNotKnownException;

class DbContainer
{

    private const KNOWN_DB_TYPES = [
        'pdo' => PdoDb::class
    ];

    /** 
     * @var DbInterface[]
     */
    private static array $databases = [];

    public static function get(string $dbName = 'main'): ?DbInterface
    {
        return self::$databases[$dbName] ?? null;
    }
    
    public static function add(array $params, string $name = 'main'): DbInterface
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

