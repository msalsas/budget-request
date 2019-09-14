<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

interface BudgetResponseDTOInterface
{
    public static function toDTO($data): Response;
}