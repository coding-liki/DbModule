<?php
namespace CodingLiki\Db;

use CodingLiki\Db\Concrete\Pdo\PdoDb;
use CodingLiki\Db\Exceptions\DbTypeNotKnownException;

class DbContainer
{

    private const KNOWN_DB_TYPES = [
        'pdo' => PdoDb::class
    ];

    /** 
     * @var DbInterface[]
     */
    private static $databases = [];

    public static function get(string $dbName = 'main'): ?DbInterface
    {
        return $databases[$dbName] ?? null;
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

