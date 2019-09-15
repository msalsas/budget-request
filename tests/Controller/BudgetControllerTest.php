<?php

namespace Test\Controller;

use App\DTO\GetBudgetsResponseDTO;
use App\Entity\Budget\Budget;
use App\Helper\EntityCreationHelper;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BudgetControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $this->client = static::createClient();
    }

    public function testGetAllStatusCode()
    {
        $this->client->request('GET', '/budget');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetAllEmptyArray()
    {
        $this->client->request('GET', '/budget');

        $this->assertEquals($this->toJson([]), $this->client->getResponse()->getContent());
    }

    public function testGetAllFilledArray()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->client->request('GET', '/budget');
        $content = $this->client->getResponse()->getContent();
        $budgets = $this->toArray($content);
        $this->assertIsArray($budgets);
        $this->assertEquals(2, count($budgets));

        $this->assertEquals(EntityCreationHelper::TITLE_A, $budgets[0][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_A, $budgets[0][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_A, $budgets[0][GetBudgetsResponseDTO::CATEGORY]);
        $this->assertEquals(EntityCreationHelper::EMAIL_A, $budgets[0][GetBudgetsResponseDTO::EMAIL]);
        $this->assertEquals(EntityCreationHelper::TELEPHONE_A, $budgets[0][GetBudgetsResponseDTO::TELEPHONE]);
        $this->assertEquals(EntityCreationHelper::ADDRESS_A, $budgets[0][GetBudgetsResponseDTO::ADDRESS]);
        $this->assertEquals(Budget::STATUS_PENDING, $budgets[0][GetBudgetsResponseDTO::STATUS]);

        $this->assertEquals(EntityCreationHelper::TITLE_B, $budgets[1][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_B, $budgets[1][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_B, $budgets[1][GetBudgetsResponseDTO::CATEGORY]);
        $this->assertEquals(EntityCreationHelper::EMAIL_B, $budgets[1][GetBudgetsResponseDTO::EMAIL]);
        $this->assertEquals(EntityCreationHelper::TELEPHONE_B, $budgets[1][GetBudgetsResponseDTO::TELEPHONE]);
        $this->assertEquals(EntityCreationHelper::ADDRESS_B, $budgets[1][GetBudgetsResponseDTO::ADDRESS]);
        $this->assertEquals(Budget::STATUS_PENDING, $budgets[1][GetBudgetsResponseDTO::STATUS]);
    }

    public function testGetAllByEmailFilledArray()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->client->request('GET', '/budget/' . EntityCreationHelper::EMAIL_A);
        $content = $this->client->getResponse()->getContent();
        $budgets = $this->toArray($content);
        $this->assertIsArray($budgets);

        $this->assertEquals(1, count($budgets));

        $this->assertEquals(EntityCreationHelper::TITLE_A, $budgets[0][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_A, $budgets[0][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_A, $budgets[0][GetBudgetsResponseDTO::CATEGORY]);
        $this->assertEquals(EntityCreationHelper::EMAIL_A, $budgets[0][GetBudgetsResponseDTO::EMAIL]);
        $this->assertEquals(EntityCreationHelper::TELEPHONE_A, $budgets[0][GetBudgetsResponseDTO::TELEPHONE]);
        $this->assertEquals(EntityCreationHelper::ADDRESS_A, $budgets[0][GetBudgetsResponseDTO::ADDRESS]);
        $this->assertEquals(Budget::STATUS_PENDING, $budgets[0][GetBudgetsResponseDTO::STATUS]);
    }

    public function testGetAllByWrongEmailThrowsException()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->expectException(\Exception::class);

        $this->client->request('GET', '/budget/' . EntityCreationHelper::WRONG_EMAIL);
    }

    public function testCreateNewBudgetStatus()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->createBudgetWithOtherEmail();

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateNewBudgetWithOtherUser()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->createBudgetWithOtherEmail();
        $this->client->request('GET', '/budget/' . EntityCreationHelper::EMAIL_OTHER);
        $content = $this->client->getResponse()->getContent();

        $budgets = $this->toArray($content);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, count($budgets));

        $this->assertIsInt($budgets[0][GetBudgetsResponseDTO::ID]);
        $this->assertEquals(EntityCreationHelper::TITLE_A, $budgets[0][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_A, $budgets[0][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_A, $budgets[0][GetBudgetsResponseDTO::CATEGORY]);
        $this->assertEquals(EntityCreationHelper::EMAIL_OTHER, $budgets[0][GetBudgetsResponseDTO::EMAIL]);
        $this->assertEquals(EntityCreationHelper::TELEPHONE_A, $budgets[0][GetBudgetsResponseDTO::TELEPHONE]);
        $this->assertEquals(EntityCreationHelper::ADDRESS_A, $budgets[0][GetBudgetsResponseDTO::ADDRESS]);
        $this->assertEquals(Budget::STATUS_PENDING, $budgets[0][GetBudgetsResponseDTO::STATUS]);
    }

    public function testCreateNewBudgetWithExistingUser()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->createBudgetWithExistingEmail();
        $this->client->request('GET', '/budget/' . EntityCreationHelper::EMAIL_A);
        $content = $this->client->getResponse()->getContent();

        $budgets = $this->toArray($content);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(2, count($budgets));

        $this->assertIsInt($budgets[0][GetBudgetsResponseDTO::ID]);
        $this->assertEquals(EntityCreationHelper::TITLE_A, $budgets[0][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_A, $budgets[0][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_A, $budgets[0][GetBudgetsResponseDTO::CATEGORY]);
        $this->assertEquals(EntityCreationHelper::EMAIL_A, $budgets[0][GetBudgetsResponseDTO::EMAIL]);
        $this->assertEquals(EntityCreationHelper::TELEPHONE_OTHER, $budgets[0][GetBudgetsResponseDTO::TELEPHONE]);
        $this->assertEquals(EntityCreationHelper::ADDRESS_OTHER, $budgets[0][GetBudgetsResponseDTO::ADDRESS]);
        $this->assertEquals(Budget::STATUS_PENDING, $budgets[0][GetBudgetsResponseDTO::STATUS]);
    }

    public function testCreateBudgetWrongEmailThrowsException()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("email:
    This value is not a valid email address. (code bd79c0ab-ddba-46cc-a703-a7a4b08de310)");

        $this->createBudgetWithWrongEmail();
    }

    public function testCreateBudgetWrongTelephoneThrowsException()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("telephone:
    This value is too long. It should have 32 characters or less. (code d94b19cc-114f-4f44-9cc4-4138e80a87b9)");

        $this->createBudgetWithWrongTelephone();
    }

    protected function createBudgetWithOtherEmail()
    {
        $parameters = array(
            GetBudgetsResponseDTO::TITLE => EntityCreationHelper::TITLE_A,
            GetBudgetsResponseDTO::DESCRIPTION => EntityCreationHelper::DESCRIPTION_A,
            GetBudgetsResponseDTO::CATEGORY => EntityCreationHelper::CATEGORY_A,
            GetBudgetsResponseDTO::EMAIL => EntityCreationHelper::EMAIL_OTHER,
            GetBudgetsResponseDTO::TELEPHONE => EntityCreationHelper::TELEPHONE_A,
            GetBudgetsResponseDTO::ADDRESS => EntityCreationHelper::ADDRESS_A,
        );

        $this->client->request('POST', '/budget', [json_encode($parameters, true)]);
    }

    protected function createBudgetWithExistingEmail()
    {
        $parameters = array(
            GetBudgetsResponseDTO::TITLE => EntityCreationHelper::TITLE_A,
            GetBudgetsResponseDTO::DESCRIPTION => EntityCreationHelper::DESCRIPTION_A,
            GetBudgetsResponseDTO::CATEGORY => EntityCreationHelper::CATEGORY_A,
            GetBudgetsResponseDTO::EMAIL => EntityCreationHelper::EMAIL_A,
            GetBudgetsResponseDTO::TELEPHONE => EntityCreationHelper::TELEPHONE_OTHER,
            GetBudgetsResponseDTO::ADDRESS => EntityCreationHelper::ADDRESS_OTHER,
        );

        $this->client->request('POST', '/budget', [json_encode($parameters, true)]);
    }

    protected function createBudgetWithWrongEmail()
    {
        $parameters = array(
            GetBudgetsResponseDTO::TITLE => EntityCreationHelper::TITLE_A,
            GetBudgetsResponseDTO::DESCRIPTION => EntityCreationHelper::DESCRIPTION_A,
            GetBudgetsResponseDTO::CATEGORY => EntityCreationHelper::CATEGORY_A,
            GetBudgetsResponseDTO::EMAIL => EntityCreationHelper::WRONG_EMAIL,
            GetBudgetsResponseDTO::TELEPHONE => EntityCreationHelper::TELEPHONE_A,
            GetBudgetsResponseDTO::ADDRESS => EntityCreationHelper::ADDRESS_A,
        );

        $this->client->request('POST', '/budget', [json_encode($parameters, true)]);
    }

    protected function createBudgetWithWrongTelephone()
    {
        $parameters = array(
            GetBudgetsResponseDTO::TITLE => EntityCreationHelper::TITLE_A,
            GetBudgetsResponseDTO::DESCRIPTION => EntityCreationHelper::DESCRIPTION_A,
            GetBudgetsResponseDTO::CATEGORY => EntityCreationHelper::CATEGORY_A,
            GetBudgetsResponseDTO::EMAIL => EntityCreationHelper::EMAIL_OTHER,
            GetBudgetsResponseDTO::TELEPHONE => EntityCreationHelper::WRONG_TELEPHONE,
            GetBudgetsResponseDTO::ADDRESS => EntityCreationHelper::ADDRESS_A,
        );

        $this->client->request('POST', '/budget', [json_encode($parameters, true)]);
    }

    protected function toJson(array $array)
    {
        return json_encode($array);
    }

    protected function toArray(string $json)
    {
        return json_decode($json, true);
    }
}