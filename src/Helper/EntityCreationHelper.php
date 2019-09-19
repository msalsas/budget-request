<?php

namespace App\Helper;

use App\Entity\Budget\Budget;
use App\Entity\User\User;
use App\Entity\User\UserInterface;
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

    const TITLE_OTHER = "Budget Other";
    const DESCRIPTION_OTHER = "sunt in culpa qui officia deserunt mollit anim id est laborum.";
    const CATEGORY_OTHER = "Other";

    const EMAIL_PREFIX = "email";
    const EMAIL_SUFFIX = "@email.com";
    const TELEPHONE_PREFIX = "342 456 7 - ";
    const ADDRESS_PREFIX = "c/ Doe, ";
    const FIRST_TITLE = "A Title";
    const DESCRIPTION_PREFIX = "Description ";
    const CATEGORY_PREFIX = "Category ";

    const WRONG_ID = 9999999999999999999999999999999999999999999999999999999999;
    const WRONG_TITLE = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    const WRONG_EMAIL = "NOT_A_REAL_EMAIL";
    const WRONG_TELEPHONE = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

    const DESCRIPTION_HEATING = "Presupuesto de colocación de 1 termo a gas-butano de 11 litros junker.";
    const DESCRIPTION_KITCHEN = "Reformar cocina, cambiando azulejos, suelo, puntos de electricidad y fontanería y techo. Retirada de  muebles antiguos  aproximadamente a partir del mes de junio.";
    const DESCRIPTION_BATHROOM = "Necesito arreglar el baño de casa, tengo que cambiar el plato de ducha (que es muy pequeño) y poner uno nuevo un poco más grande con mamparas. Lo más económico posible.";
    const DESCRIPTION_AIR_CONDITIONING = "Presupuesto de mantenimiento i reparación de aire acondicionado de la marca  mitsubishi electric.";
    const DESCRIPTION_HOUSE_CONSTRUCTIONS = "Necesito presupuesto para construir una casa de dos plantas en churriana de la vega (Granada). Tengo la parcela de 220 metros cuadrados en propiedad. Se trataría de una casa de 3 plantas con 5 o más habitaciones. Estoy pendiente de adquirir el proyecto.";


    protected $userA;
    protected $userB;
    protected $budgetA;
    protected $budgetB;

    public static function createUsersAndBudgets(EntityManagerInterface $entityManager)
    {
        self::createUserAAndBudgetA($entityManager);
        self::createUserBAndBudgetB($entityManager);
    }

    public static function createBunchOfUsersAndBudgets(EntityManagerInterface $entityManager)
    {
        $title = self::FIRST_TITLE;
        $index = 0;
        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setEmail(self::EMAIL_PREFIX . $i . self::EMAIL_SUFFIX);
            $user->setTelephone(self::TELEPHONE_PREFIX . $i);
            $user->setAddress(self::ADDRESS_PREFIX . $i);
            $entityManager->persist($user);

            for($j = 0; $j < 20; $j++) {
                $budget = new Budget();
                $budget->setTitle($title++);
                $budget->setDescription(self::DESCRIPTION_PREFIX . $index);
                $budget->setCategory(self::CATEGORY_PREFIX . $index);
                $budget->setUser($user);
                $budget->setStatus(Budget::STATUS_PENDING);
                $entityManager->persist($budget);
                $index++;
            }
        }

        $entityManager->flush();
    }

    public static function createBudgetsForMatchingDescription(EntityManagerInterface $entityManager)
    {
        $user = self::getUserA();
        $entityManager->persist($user);
        self::createBudgetWithDescriptionAndUser(self::DESCRIPTION_HEATING, $user, $entityManager);
        self::createBudgetWithDescriptionAndUser(self::DESCRIPTION_KITCHEN, $user, $entityManager);
        self::createBudgetWithDescriptionAndUser(self::DESCRIPTION_BATHROOM, $user, $entityManager);
        self::createBudgetWithDescriptionAndUser(self::DESCRIPTION_AIR_CONDITIONING, $user, $entityManager);
        self::createBudgetWithDescriptionAndUser(self::DESCRIPTION_HOUSE_CONSTRUCTIONS, $user, $entityManager);
    }

    public static function createBudgetsWithoutDescription(EntityManagerInterface $entityManager)
    {
        $user = self::getUserA();
        $entityManager->persist($user);
        self::createBudgetWithoutDescription($user, $entityManager);
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

    protected static function createBudgetWithDescriptionAndUser(string $description, UserInterface $user, EntityManagerInterface $entityManager)
    {
        $budget = self::getBudgetA();
        $budget->setUser($user);
        $budget->setDescription($description);
        $entityManager->persist($budget);

        $entityManager->flush();
    }

    protected static function createBudgetWithoutDescription(UserInterface $user, EntityManagerInterface $entityManager)
    {
        $budget = self::getBudgetA();
        $budget->setUser($user);
        $budget->setDescription('');
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