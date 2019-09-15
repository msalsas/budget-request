<?php

namespace App\Helper;

use App\Entity\Budget\Budget;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class EntityCreationHelper
{
    const EMAIL_A = "johnDoe@email.com";
    const TELEPHONE_A = "+34 91 234 567";
    const ADDRESS_A = "c/ John Doe, 45 - 46596, New York";

    const EMAIL_B = "janeDoe@email.com";
    const TELEPHONE_B = "+34 93-432-456";
    const ADDRESS_B = "c/ Jane Doe, 54 - 1234, New Jersey";

    const EMAIL_OTHER = "other@email.com";
    const TELEPHONE_OTHER = "+34 987 123 564";
    const ADDRESS_OTHER = "c/ Other, 222 - 5645, Dallas";

    const TITLE_A = "Budget 1";
    const DESCRIPTION_A = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    const CATEGORY_A = "Plumbing";

    const TITLE_B = "Budget 2";
    const DESCRIPTION_B = "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    const CATEGORY_B = "Illumination";

    const WRONG_EMAIL = "NOT_A_REAL_EMAIL";
    const WRONG_TELEPHONE = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

    protected $userA;
    protected $userB;
    protected $budgetA;
    protected $budgetB;

    public static function createUsersAndBudgets(EntityManagerInterface $entityManager)
    {
        self::createUserAAndBudgetA($entityManager);
        self::createUserBAndBudgetB($entityManager);
    }

    public static function getBudgets()
    {
        $user = self::getUserA();
        $budgetA = self::getBudgetA();
        $budgetA->setUser($user);

        $user = self::getUserB();
        $budgetB = self::getBudgetB();
        $budgetB->setUser($user);

        return array($budgetA, $budgetB);
    }

    protected static function createUserAAndBudgetA(EntityManagerInterface $entityManager)
    {
        $user = self::getUserA();
        $entityManager->persist($user);

        $budget = self::getBudgetA();
        $budget->setUser($user);
        $entityManager->persist($budget);
        $entityManager->flush();
    }

    protected static function createUserBAndBudgetB(EntityManagerInterface $entityManager)
    {
        $user = self::getUserB();
        $entityManager->persist($user);
        $entityManager->flush();

        $budget = self::getBudgetB();
        $budget->setUser($user);
        $entityManager->persist($budget);
        $entityManager->flush();
    }

    protected static function getUserA()
    {
        $user = new User();
        $user->setEmail(self::EMAIL_A);
        $user->setTelephone(self::TELEPHONE_A);
        $user->setAddress(self::ADDRESS_A);

        return $user;
    }

    protected static function getUserB()
    {
        $user = new User();
        $user->setEmail(self::EMAIL_B);
        $user->setTelephone(self::TELEPHONE_B);
        $user->setAddress(self::ADDRESS_B);

        return $user;
    }

    protected static function getBudgetA()
    {
        $budget = new Budget();
        $budget->setTitle(self::TITLE_A);
        $budget->setDescription(self::DESCRIPTION_A);
        $budget->setCategory(self::CATEGORY_A);
        $budget->setStatus(Budget::STATUS_PENDING);

        return $budget;
    }

    protected static function getBudgetB()
    {
        $budget = new Budget();
        $budget->setTitle(self::TITLE_B);
        $budget->setDescription(self::DESCRIPTION_B);
        $budget->setCategory(self::CATEGORY_B);
        $budget->setStatus(Budget::STATUS_PENDING);

        return $budget;
    }
}