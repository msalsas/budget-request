<?php

namespace App\Service;

use App\Entity\Budget\Budget;
use App\Entity\Budget\BudgetInterface;
use App\Entity\User\User;
use App\Entity\User\UserInterface;
use App\Repository\Budget\BudgetRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class BudgetService
{
    const EMAIL = "email";

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get(int $id)
    {
        return $this->getBudgetRepository()->get($id);
    }

    public function create(BudgetInterface $budget, UserInterface $user)
    {
        $email = $user->getEmail();
        $userRepository = $this->getUserRepository();

        if (!$userRepository->findOneBy([self::EMAIL => $email])) {
            $userRepository->create($user);
        } else {
            $userRepository->update($user);
        }

        $budget->setStatus(Budget::STATUS_PENDING);
        $this->getBudgetRepository()->create($budget);
    }

    public function update(BudgetInterface $budget)
    {
        $id = $budget->getId();
        $budgetRepository = $this->getBudgetRepository();
        $oldBudget = $budgetRepository->get($id);

        $this->validateUpdate($oldBudget, $budget);

        $this->getBudgetRepository()->update($budget);
    }

    public function publish(int $id)
    {
        $budgetRepository = $this->getBudgetRepository();
        $budget = $budgetRepository->get($id);

        $this->validatePublish($id, $budget);

        $budget->setStatus(Budget::STATUS_PUBLISHED);
        $this->getBudgetRepository()->update($budget);
    }

    public function discard(int $id)
    {
        $budgetRepository = $this->getBudgetRepository();
        $budget = $budgetRepository->get($id);

        $this->validateDiscard($id, $budget);

        $budget->setStatus(Budget::STATUS_DISCARDED);
        $this->getBudgetRepository()->update($budget);
    }

    public function getAllPaginated(string $email = null, int $offset = 0, int $limit = 20, array $orderBy = [self::EMAIL])
    {
        $budgetRepository = $this->getBudgetRepository();

        return $budgetRepository->findBy([self::EMAIL => $email], $orderBy, $limit, $offset);
    }

    protected function exists(integer $id)
    {
        return $this->getBudgetRepository()->exists($id);
    }

    protected function isPendingStatus(string $status)
    {
        return $status === Budget::STATUS_PENDING;
    }

    protected function isDiscardedStatus(string $status)
    {
        return $status === Budget::STATUS_DISCARDED;
    }

    protected function isPublishedStatus(string $status)
    {
        return $status === Budget::STATUS_PUBLISHED;
    }

    protected function validateUpdate(?BudgetInterface $oldBudget, BudgetInterface $newBudget)
    {
        $this->validateBudgetId($newBudget->getId(), $oldBudget);
        $this->validatePendingStatus($oldBudget->getStatus());
    }

    protected function validatePublish(int $id, ?BudgetInterface $budget)
    {
        $this->validateBudgetId($id, $budget);
        $this->validatePendingStatus($budget->getStatus());
    }

    protected function validateDiscard(int $id, ?BudgetInterface $budget)
    {
        $this->validateBudgetId($id, $budget);
        $this->validatePendingOrPublishedStatus($budget->getStatus());
    }

    protected function validateBudgetId(int $id, ?BudgetInterface $budget)
    {
        if (!$budget) {
            throw new \Exception(sprintf("Id %s is invalid", $id));
        }
    }

    protected function validatePendingStatus(string $status)
    {
        if (!$this->isPendingStatus($status)) {
            throw new \Exception(sprintf("Status is %s. It should be pending", $status));
        }
    }

    protected function validatePendingOrPublishedStatus(string $status)
    {
        if (!$this->isPendingStatus($status) && !$this->isPublishedStatus($status)) {
            throw new \Exception(sprintf("Status is %s. It should be pending or discarded", $status));
        }
    }

    /**
     * @return BudgetRepositoryInterface
     */
    protected function getBudgetRepository()
    {
        return $this->em->getRepository(Budget::class);
    }

    /**
     * @return UserRepositoryInterface
     */
    protected function getUserRepository()
    {
        return $this->em->getRepository(User::class);
    }
}