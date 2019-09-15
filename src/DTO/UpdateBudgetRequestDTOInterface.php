<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

interface UpdateBudgetRequestDTOInterface extends BudgetRequestDTOInterface
{
    public function __construct(int $id, Request $request);
    public function getId(): int;
}