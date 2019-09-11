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

    public function get(string $email): UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->findOneBy(array('email' => $email));
        return $user;
    }

    public function create(UserInterface $user)
    {
        if($this->exists($user)) {
            throw new \Exception("User already exists");
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function update(UserInterface $user)
    {
        if(!$this->exists($user)) {
            throw new \Exception("User does not exist");
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function delete(UserInterface $user)
    {
        if(!$this->exists($user)) {
            throw new \Exception("User does not exist");
        }

        $user = $this->get($user->getEmail());
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function exists(UserInterface $user): boolean
    {
        return !!$this->get($user->getEmail());
    }
}