<?php

namespace Test\Service;

use App\Entity\Budget\Budget;
use App\Entity\User\User;
use App\Repository\Budget\BudgetRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Service\BudgetService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class BudgetServiceTest extends TestCase
{
    const USER_EMAIL = "email@email.com";
    const USER_ADDRESS = "Jack street, 5, 04576 - U.K.";
    const USER_TELEPHONE = "+34 971-25-67-43";

    const BUDGET_ID = 123;
    const TITLE = "Budget Title";
    const DESCRIPTION = "Budget description";
    const CATEGORY = "Budget category";

    public function testCreateBudgetWithNonExistingUser()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('create');

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $userRepositoryMock->expects($this->once())
            ->method('create');
        $userRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budgetService->create($this->getBudgetWithoutStatus(), $this->getUser());
    }

    public function testCreateBudgetWithExistingUser()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('create');

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->getUser());
        $userRepositoryMock->expects($this->never())
            ->method('create');
        $userRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budgetService->create($this->getBudgetWithoutStatus(), $this->getUser());
    }

    public function testCreateBudgetWithoutUser()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('create');

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->never())
            ->method('findOneBy')
            ->willReturn($this->getUser());
        $userRepositoryMock->expects($this->never())
            ->method('create');
        $userRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\TypeError::class);

        $budgetService->create($this->getBudgetWithoutStatus(), null);
    }

    public function testCreateBudgetWithoutDescription()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('create')
            ->willThrowException(new \Exception());

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->getUser());
        $userRepositoryMock->expects($this->never())
            ->method('create');
        $userRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\Exception::class);

        $budgetService->create($this->getBudgetWithoutDescriptionAndStatus(), $this->getUser());
    }

    public function testCreateBudgetWithPublishedStatus()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('create');

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->getUser());
        $userRepositoryMock->expects($this->never())
            ->method('create');
        $userRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budget = $this->getPublishedBudgetWithoutDescription();
        $budgetService->create($budget, $this->getUser());

        $this->assertEquals(Budget::STATUS_PENDING, $budget->getStatus());
    }

    public function testUpdateBudgetWithNoId()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $this->expectException(\TypeError::class);

        $budgetService = new BudgetService($emMock);

        $budgetService->update($this->getBudgetWithoutStatus());
    }

    public function testUpdateBudget()
    {
        $budget = $this->getPendingBudgetWithId();
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('get')
            ->willReturn($budget);
        $budgetRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budgetService->update($budget);
    }

    public function testUpdateBudgetWithPublishedStatus()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\Exception::class);

        $budget = $this->getPublishedBudgetWithId();
        $budgetService->update($budget);
    }

    public function testUpdateBudgetWithDiscardedStatus()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\Exception::class);

        $budget = $this->getDiscardedBudgetWithId();
        $budgetService->update($budget);
    }

    public function testPublishBudget()
    {
        $budget = $this->getPendingBudgetWithId();
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('get')
            ->willReturn($budget);
        $budgetRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budgetService->publish($budget->getId());
    }

    public function testPublishBudgetWithPublishedStatus()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\Exception::class);

        $budgetService->publish(self::BUDGET_ID);
    }

    public function testPublishBudgetWithDiscardedStatus()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\Exception::class);

        $budgetService->publish(self::BUDGET_ID);
    }

    public function testDiscardBudget()
    {
        $budget = $this->getPendingBudgetWithId();
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('get')
            ->willReturn($budget);
        $budgetRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budgetService->discard($budget->getId());
    }

    public function testDiscardBudgetWithPublishedStatus()
    {
        $budget = $this->getPublishedBudgetWithId();
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('get')
            ->willReturn($budget);
        $budgetRepositoryMock->expects($this->once())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $budgetService->discard($budget->getId());
    }

    public function testDiscardBudgetWithDiscardedStatus()
    {
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('update');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->with(Budget::class)
            ->willReturn($budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $this->expectException(\Exception::class);

        $budgetService->discard(self::BUDGET_ID);
    }

    public function testGetBudgetsWithoutEmail()
    {
        $budgets = $this->getBudgetList();
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->once())
            ->method('findBy')
            ->willReturn($budgets);

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->never())
            ->method('findOneBy');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $paginatedBudgets = $budgetService->getAllPaginated();

        $this->assertEquals($budgets, $paginatedBudgets);
    }

    public function testGetBudgetsWithEmail()
    {
        $budgets = $this->getBudgetList();
        $budgetRepositoryMock = $this->createMock(BudgetRepositoryInterface::class);
        $budgetRepositoryMock->expects($this->never())
            ->method('findBy')
            ->willReturn($budgets);

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->getUser());


        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->withConsecutive([User::class], [Budget::class])
            ->willReturn($userRepositoryMock, $budgetRepositoryMock);

        $budgetService = new BudgetService($emMock);

        $paginatedBudgets = $budgetService->getAllPaginated($this->getUser()->getEmail());

        $this->assertEquals($budgets, $paginatedBudgets);
    }


    protected function getBudgetWithoutStatus()
    {
        $budget = new Budget();
        $budget->setTitle(self::TITLE);
        $budget->setDescription(self::DESCRIPTION);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getPendingBudgetWithoutId()
    {
        $budget = new Budget();
        $budget->setTitle(self::TITLE);
        $budget->setStatus(Budget::STATUS_PENDING);
        $budget->setDescription(self::DESCRIPTION);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getBudgetWithoutDescriptionAndStatus()
    {
        $budget = new Budget();
        $budget->setTitle(self::TITLE);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getPublishedBudgetWithoutDescription()
    {
        $budget = new Budget();
        $budget->setTitle(self::TITLE);
        $budget->setStatus(Budget::STATUS_PUBLISHED);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getPublishedBudgetWithId()
    {
        $budget = new Budget();
        $budget->setId(0);
        $budget->setTitle(self::TITLE);
        $budget->setStatus(Budget::STATUS_PUBLISHED);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getDiscardedBudgetWithId()
    {
        $budget = new Budget();
        $budget->setId(0);
        $budget->setTitle(self::TITLE);
        $budget->setStatus(Budget::STATUS_DISCARDED);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getPendingBudgetWithId()
    {
        $budget = new Budget();
        $budget->setId(0);
        $budget->setTitle(self::TITLE);
        $budget->setStatus(Budget::STATUS_PENDING);
        $budget->setCategory(self::CATEGORY);

        return $budget;
    }

    protected function getUser()
    {
        $user = new User();
        $user->setEmail(self::USER_EMAIL);
        $user->setAddress(self::USER_ADDRESS);
        $user->setTelephone(self::USER_TELEPHONE);
        $user->setBudgets($this->getBudgetList());

        return $user;
    }

    protected function getBudgetList()
    {
        return new ArrayCollection(array(
            $this->getPendingBudgetWithId(),
            $this->getPublishedBudgetWithId(),
            $this->getDiscardedBudgetWithId(),
        ));
    }
}