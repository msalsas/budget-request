<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Entity\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function get(integer $id): UserInterface
    {
        return $this->find($id);
    }

    public function create(UserInterface $user)
    {
        if($this->exists($user->getId())) {
            throw new \Exception("User already exists");
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function update(UserInterface $user)
    {
        if(!$this->exists($user->getId())) {
            throw new \Exception("User does not exist");
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function delete(integer $id)
    {
        if(!$this->exists($id)) {
            throw new \Exception("User does not exist");
        }

        $user = $this->get($id);
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function exists(integer $id): boolean
    {
        return !!$this->find($id);
    }
}