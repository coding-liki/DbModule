<?php
namespace CodingLiki\DbModule\Concrete\Pdo;

use CodingLiki\DbModule\QueryResultInterface;
use PDO;
use PDOStatement;

class PdoQueryResult implements QueryResultInterface
{
    private PDOStatement $statement;
    public function __construct(PDOStatement $statement) {
        $this->statement = $statement;
    }
    public function getRow(int $offset = 0): array
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, $offset);
    }

    public function getNextRow(): array
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllRows(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScalar(string|int $name = 0){
        return $this->getNextRow()[$name];
    }

    public function count(): int
    {
        return $this->statement->rowCount();
    }
}

