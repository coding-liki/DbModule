<?php
namespace CodingLiki\Db\Concrete\Pdo\Exceptions;

class DriverIsNotInstalled extends \Exception
{
    public function __construct(string $driverName, string $installationDescription) {
        parent::__construct("Driver $driverName is not installed. Please follow these instructions to install it: $installationDescription ");
    }
}

