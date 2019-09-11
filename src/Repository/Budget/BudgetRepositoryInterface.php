<?php

namespace App\Repository\Budget;

use App\Entity\Budget\BudgetInterface;

interface BudgetRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = NULL, $limit = NULL, $offset = NULL);
    public function findOneBy(array $criteria, array $orderBy = null);

    public function get(int $id): BudgetInterface;
    public function create(BudgetInterface $budget);
    public function update(BudgetInterface $budget);
    public function delete(integer $id);
    public function exists(integer $id): boolean;
}