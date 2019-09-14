<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

interface BudgetRequestDTOInterface
{
    public function __construct(Request $request);
}