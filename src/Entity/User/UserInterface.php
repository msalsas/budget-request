<?php

namespace App\Entity\User;

use Doctrine\Common\Collections\Collection;

interface UserInterface
{
    public function getId(): int;
    public function setId(int $id);
    public function getEmail(): string;
    public function setEmail(string $email);
    public function getTelephone(): string ;
    public function setTelephone(string $telephone);
    public function getAddress(): string;
    public function setAddress(string $address);
    public function getBudgets(): Collection;
    public function setBudgets(Collection $budgets);
}