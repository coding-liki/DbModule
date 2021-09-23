<?php
namespace CodingLiki\DbModule\Concrete\Pdo\Exceptions;

class DriverNotKnown extends \Exception
{
    public function __construct(string $driverName) {
        parent::__construct("Pdo driver $driverName is not known!!!");
    }
}

