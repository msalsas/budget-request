<?php

namespace App\Service;

use App\Entity\Budget\Budget;
use App\Entity\Budget\BudgetInterface;
use App\Entity\User\User;
use App\Entity\User\UserInterface;
use App\Repository\Budget\BudgetRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BudgetService
{
    const EMAIL = "email";
    const USER = "user";
    const TITLE = "title";
    const ASC = "ASC";

    protected $em;
    protected $validator;

    public function __construct(EntityManagerInterface $em, Validator $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }

    public function get(int $id)
    {
        return $this->getBudgetRepository()->get($id);
    }

    public function create(BudgetInterface $budget, UserInterface $user)
    {
        $this->validator->validate($user);

        $email = $user->getEmail();
        $userRepository = $this->getUserRepository();

        if (!$userRepository->exists($email)) {
            $userRepository->create($user);
            $budget->setUser($user);
        } else {
            $existingUser = $userRepository->get($email);

            if ($telephone = $user->getTelephone()) {
                $existingUser->setTelephone($telephone);
            }
            if ($address = $user->getAddress()) {
                $existingUser->setAddress($address);
            }

            $userRepository->update($existingUser);
            $budget->setUser($existingUser);
        }

        $budget->setStatus(Budget::STATUS_PENDING);

        $this->validator->validate($budget);

        $this->getBudgetRepository()->create($budget);
    }

    public function update(BudgetInterface $budget)
    {
        $id = $budget->getId();
        $budgetRepository = $this->getBudgetRepository();
        $existingBudget = $budgetRepository->get($id);

        $this->validateUpdate($existingBudget, $budget);

        if ($title = $budget->getTitle()) {
            $existingBudget->setTitle($title);
        }
        if ($description = $budget->getDescription()) {
            $existingBudget->setDescription($description);
        }
        if ($category = $budget->getCategory()) {
            $existingBudget->setCategory($category);
        }

        $this->validator->validate($existingBudget);

        $this->getBudgetRepository()->update($existingBudget);
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

    public function getAllPaginated(string $email = null, int $offset = 0, int $limit = 20, array $orderBy = [self::TITLE => self::ASC])
    {
        $userRepository = $this->getUserRepository();
        $budgetRepository = $this->getBudgetRepository();

        if ($email) {
            /** @var UserInterface $user */
            $user = $userRepository->findOneBy([self::EMAIL => $email]);

            if ($user) {
                return $budgetRepository->findBy([self::USER => $user], $orderBy, $limit, $offset);
            } else {
                throw new NotFoundHttpException(sprintf("User with email %s not found", $email));
            }
        }

        return $budgetRepository->findBy([], $orderBy, $limit, $offset);
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

    protected function validatePendingStatus(string $status = null)
    {
        if (!$status || !$this->isPendingStatus($status)) {
            throw new \Exception(sprintf("Status is %s. It should be pending", $status));
        }
    }

    protected function validatePendingOrPublishedStatus(string $status = null)
    {
        if (!$status || !$this->isPendingStatus($status) && !$this->isPublishedStatus($status)) {
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