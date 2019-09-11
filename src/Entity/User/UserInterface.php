<?php

namespace App\Entity\User;

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
}