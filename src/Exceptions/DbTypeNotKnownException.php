<?php
namespace CodingLiki\Db\Exceptions;

class DbTypeNotKnownException extends \Exception
{
    public function __construct(string $dbType) {
        parent::__construct("Database type `$dbType` not known");
    }
}

