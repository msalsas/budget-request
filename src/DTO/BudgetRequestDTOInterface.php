<?php

namespace App\DTO;

interface BudgetRequestDTOInterface
{
    public function getTitle(): string;
    public function getDescription(): string;
    public function getCategory(): string;
}