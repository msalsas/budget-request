<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

interface BudgetRequestDTOInterface
{
    public function __construct(Request $request);
    public function getTitle(): string;
    public function getDescription(): string;
    public function getCategory(): string;
    public function getEmail(): string;
    public function getTelephone(): string;
    public function getAddress(): string;
}