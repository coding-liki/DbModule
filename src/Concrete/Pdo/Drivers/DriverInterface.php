<?php
namespace CodingLiki\DbModule\Concrete\Pdo\Drivers;

interface DriverInterface
{
    public function buildDsnFromParams(array $params): string;

    public function checkInstalled(): bool;

    public function getInstallationDescription(): string;
}

