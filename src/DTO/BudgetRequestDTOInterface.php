<?php

namespace App\DTO;

use App\Entity\Budget\Budget;

interface BudgetRequestDTOInterface
{
    public function getTitle(): ?string;
    public function getDescription(): ?string;
    public function getCategory(): ?string;
    public function toBudget(): ?Budget;
}