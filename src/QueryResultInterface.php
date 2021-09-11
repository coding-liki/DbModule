<?php
namespace CodingLiki\Db;

interface QueryResultInterface
{
    public function getRow(int $offset = 0): array;

    public function getNextRow(): array;
    public function getAllRows(): array;

    public function getScalar(string|int $name);

    public function count(): int;
}

