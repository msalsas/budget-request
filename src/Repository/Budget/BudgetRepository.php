<?php

namespace App\Repository\Budget;

use App\Entity\Budget\Budget;
use App\Entity\Budget\BudgetInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BudgetRepository extends ServiceEntityRepository implements BudgetRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Budget::class);
    }

    public function get(int $id): BudgetInterface
    {
        /** @var BudgetInterface $budget */
        $budget = $this->find($id);
        return $budget;
    }

    public function create(BudgetInterface $budget)
    {
        if($budget->getId() && $this->exists($budget->getId())) {
            throw new \Exception("User already exists");
        }

        $this->getEntityManager()->persist($budget);
        $this->getEntityManager()->flush();
    }

    public function update(BudgetInterface $budget)
    {
        if(!$this->exists($budget->getId())) {
            throw new \Exception("User does not exist");
        }

        $this->getEntityManager()->persist($budget);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id)
    {
        if(!$this->exists($id)) {
            throw new \Exception("User does not exist");
        }

        $budget = $this->get($id);
        $this->getEntityManager()->remove($budget);
        $this->getEntityManager()->flush();
    }

    public function exists(int $id): bool
    {
        return !!$this->find($id);
    }
}