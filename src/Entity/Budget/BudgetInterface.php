<?php

namespace App\Entity\Budget;

use App\Entity\User\UserInterface;

interface BudgetInterface
{
    public function getId(): integer;
    public function setId(integer $id);
    public function getTitle(): string;
    public function setTitle(string $title);
    public function getDescription(): string;
    public function setDescription(string $description);
    public function getCategory(): string;
    public function setCategory(string $category);
    public function getStatus(): string;
    public function setStatus(string $status);
    public function getUser(): UserInterface;
    public function setUser(UserInterface $user);
}