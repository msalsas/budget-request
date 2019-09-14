<?php

namespace App\Repository\User;

use App\Entity\User\UserInterface;

interface UserRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = NULL, $limit = NULL, $offset = NULL);
    public function findOneBy(array $criteria, array $orderBy = null);

    public function get(string $email): ?UserInterface;
    public function create(UserInterface $user);
    public function update(UserInterface $user);
    public function delete(UserInterface $user);
    public function exists(string $email): bool;
}