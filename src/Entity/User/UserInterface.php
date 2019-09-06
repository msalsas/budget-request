<?php

namespace App\Entity\User;

interface UserInterface
{
    public function getId(): integer;
    public function setId(integer $id);
    public function getEmail(): string;
    public function setEmail(string $email);
    public function getTelephone(): string ;
    public function setTelephone(string $telephone);
    public function getAddress(): string;
    public function setAddress(string $address);
}