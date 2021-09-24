<?php
namespace CodingLiki\DbModule;

interface DbInterface
{
    public function query(string $query, array $params = []): QueryResultInterface;

    public static function connect(array $params): static;
}

