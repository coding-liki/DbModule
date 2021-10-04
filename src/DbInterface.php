<?php
namespace CodingLiki\DbModule;

interface DbInterface
{
    public function query(string $query, array $params = []): QueryResultInterface;

    public static function connect(array $params): static;

    /**
     * @return string|int
     */
    public function getLastInsertId(?string $name = null);

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}

