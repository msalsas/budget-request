<?php

namespace App\DTO;

use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;

interface CreateBudgetRequestDTOInterface extends BudgetRequestDTOInterface
{
    public function __construct(Request $request);
    public function getEmail(): string;
    public function getTelephone(): string;
    public function getAddress(): string;
    public function toUser(): ?User;
}